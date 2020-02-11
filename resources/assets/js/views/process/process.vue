<template>
  <div v-if="displayPage">
    <el-row>
      <el-col>
        <infobox-process
          :process="process"
          :canCancel="canCancel"
          @cancel="onCancel"
        />
      </el-col>
    </el-row>

    <el-row>
      <el-col>
        <el-card shadow="never" class="process-steps">
          <el-steps :active="activeStep" finish-status="finish" align-center>
            <el-step
              v-for="(step, index) in steps"
              :class="{ 'is-selected': activeStep === index }"
              :title="$t('entities.process.step', { num: index + 1 })"
              :description="step.definition.name"
              :key="step.id"
              @click.native="onStepChange(index)"
              :status="
                step.state.name_key === 'locked' ? 'wait' :
                step.state.name_key === 'unlocked' ? 'process' :
                step.state.name_key === 'completed' ? 'finish' :
                step.state.name_key === 'cancelled' ? 'error' : ''
              "
              :icon="'el-icon-lpa-' + step.state.name_key">
            </el-step>
          </el-steps>
        </el-card>
      </el-col>
    </el-row>

    <el-row>
      <el-tabs type="border-card" class="no-shadow">
        <el-tab-pane>
          <template v-slot:label>
            <span>
              <i class="el-icon-lpa-form"></i> <b>{{ $t('pages.process.forms') }}</b>
            </span>
          </template>
          <el-table
            class="process-forms-table"
            :data="forms"
            :row-class-name="getFormRowClassName"
            @row-click="viewForm"
            stripe>
            <el-table-column width="50px" class-name="icon">
              <template slot-scope="scope">
                <i :class="'el-icon-lpa-' + scope.row.state.name_key"></i>
              </template>
            </el-table-column>
            <el-table-column
              prop="definition.name"
              :label="$t('entities.general.name')"
              class-name="name">
            </el-table-column>
            <el-table-column
              :label="$t('entities.general.status')"
              width="180px"
              >
              <template slot-scope="scope">
                <span :class="'state ' +  scope.row.state.name_key">{{ scope.row.state.name }}</span>
              </template>
            </el-table-column>
            <el-table-column
              :label="$tc('entities.form.assigned_organizational_units')"
              >
              <template slot-scope="scope">
                <span>{{ scope.row.organizational_unit ? scope.row.organizational_unit.name : $t('entities.general.none') }}</span>
              </template>
            </el-table-column>
            <el-table-column
              :label="$t('entities.form.current_editor')">
              <template slot-scope="scope">
                <span>{{ scope.row.current_editor ? scope.row.current_editor.name : $t('entities.general.none') }}</span>
              </template>
            </el-table-column>
            <el-table-column
              :label="$t('entities.general.updated')">
              <template slot-scope="scope">
                <span v-audit:updated="scope.row"></span>
              </template>
            </el-table-column>
          </el-table>
        </el-tab-pane>

        <el-tab-pane>
          <template v-slot:label>
            <span>
              <i class="el-icon-lpa-info-sheet"></i> <b>{{ $tc('entities.general.information_sheet', 2) }}</b>
            </span>
          </template>
          <el-table
            class="information-sheets-table"
            :data="informationSheets"
            stripe
          >
            <el-table-column
              :label="$t('entities.general.name')"
              class-name="is-link"
            >
              <template slot-scope="scope">
                <a :href="`/${language}/information-sheets/${scope.row.id}`" target="_blank">{{ scope.row.definition.name }}</a>
              </template>
            </el-table-column>
          </el-table>
        </el-tab-pane>
      </el-tabs>
    </el-row>
  </div>
</template>

<script>
  import HttpStatusCodes from '@axios/http-status-codes';
  import Page from '@components/page';
  import InfoboxProcess from '@components/infoboxes/infobox-process';
  import ProcessesAPI from '@api/processes';

  export default {
    name: 'process',

    extends: Page,

    components: { InfoboxProcess },

    props: {
      entityName: {
        type: String,
        required: true
      },
      entityId: {
        type: String,
        required: true
      },
      processId: {
        type: String,
        required: true
      }
    },

    data() {
      return {
        isUsingNewLoading: true,
        activeStep: null,
        process: {
          state: {},
          steps: []
        },
        processEntity: {},
        informationSheets: [],
        canCancel: false
      };
    },

    computed: {
      steps() {
        return _.sortBy(this.process.steps, 'definition.display_sequence');
      },

      forms() {
        return this.process.steps.length ? _.sortBy(this.process.steps[this.activeStep].forms, 'definition.display_sequence') : [];
      }
    },

    methods: {
      async loadData() {
        const requests = [];
        const context = true;

        requests.push(
          ProcessesAPI.getInstance(this.processId, context),
          ProcessesAPI.canCancel(this.processId)
        );

        const [
          { data: process, meta },
          { data: { cancel: canCancel }}
        ] = await Promise.all(requests);

        this.process = process;
        this.canCancel = canCancel;
        this.processEntity = meta.process_entity;
        this.informationSheets = _.sortBy(meta.information_sheets, 'definition.display_sequence');
        this.activeStep = this.getActiveStep();

        this.setBreadcrumbs([ this.processEntity.name, this.process.definition.name ]);
      },

      getActiveStep() {
        if (this.process.state.name_key === 'completed') {
          return this.process.steps.length - 1;
        }

        let active = 0;
        // grab the last step that is not locked
        this.process.steps.forEach((step, i) => {
          if (step.state.name_key !== 'locked') {
            active = i;
          }
        });
        return active;
      },

      viewForm(form) {
        this.$router.push(`${this.processId}/form/${form.id}`);
      },

      getFormRowClassName({row, rowIndex}) {
        return row.state.name_key;
      },

      onStepChange(index) {
        this.activeStep = index;
      },

      async onCancel() {
        try {
          await ProcessesAPI.cancelInstance(this.processId);
          this.notifySuccess({
            message: this.$t('components.notice.message.process_cancelled')
          });
          this.loadData();
        } catch (e) {
          if (!e.response) {
            throw e;
          }
          if (e.response.status === HttpStatusCodes.FORBIDDEN) {
            this.loadData();
          }
        }
      }
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';
  @import '~@sass/abstracts/mixins/helpers';

  .process {
    &-steps {
      padding-top: 12px;
      .el-card__header h3 {
        text-align: center;
        margin: 0;
      }
      .el-step {
        .el-step__title, .el-step__description {
          transition: all 0.3s ease;
        }
        &:hover {
          .el-step__head .el-step__icon {
            transition: all 0.3s ease;
            transform: scale(1.1);
            transform-origin: center;
          }
          .el-step__title {
            font-size: 130%;
          }
          .el-step__description {
            font-size: 100%;
          }
        }

        .el-step__head .el-step__icon, .el-step__main {
          transition: transform 0.3s ease;
          cursor: pointer;
        }

        &.is-selected {
          // Default
          .el-step__head .el-step__icon {
            transition: all 0.3s ease;
            transform: scale(1.2);
            transform-origin: center;
          }
          .el-step__title {
            font-size: 140%;
          }
          .el-step__description {
            font-size: 110%;
          }

          // Locked
          .el-step__head.is-wait .el-step__icon-inner {
            @include svg(locked, map-get($color-states, locked));
          }
          .el-step__title.is-wait, .el-step__description.is-wait {
            color: map-get($color-states, locked) !important;
          }
          // Cancelled
          .el-step__head.is-error .el-step__icon-inner {
            @include svg(cancelled, map-get($color-states, cancelled));
          }
          .el-step__title.is-error, .el-step__description.is-error {
            color: map-get($color-states, cancelled) !important;
          }
        }

        .el-step__head .el-step__line {
          color: $--color-text-placeholder;
          border-color: $--color-text-placeholder;
        }

        // Unlocked
        .el-step__title.is-process, .el-step__description.is-process {
          color: map-get($color-states, unlocked) !important;
        }
        // Locked
        .el-step__title.is-wait, .el-step__description.is-wait {
          color: lighten(map-get($color-states, locked), 25%) !important;
        }
        .el-step__head.is-wait .el-step__icon-inner {
          @include svg(locked, lighten(map-get($color-states, locked), 25%));
        }
        // Completed
        .el-step__title.is-finish, .el-step__description.is-finish {
          color: map-get($color-states, completed) !important;
        }
        // Cancelled
        .el-step__title.is-error, .el-step__description.is-error {
          color: lighten(map-get($color-states, cancelled), 25%) !important;
        }
        .el-step__head.is-error .el-step__icon-inner {
          @include svg(cancelled, lighten(map-get($color-states, cancelled), 25%));
          &:before {
            // remove the elementui default icon
            content: '';
          }
        }
      }

      .el-step__icon-inner {
        @include size(100%);
        padding: 5px;
      }
      .el-step__main {
        margin-top: 10px;
        .el-step__title {
          font-weight: bold;
        }
        .el-step__description {
          font-weight: 500;
        }
      }
    }

    &-forms-table {
      .el-table__row {
        cursor: pointer;
      }

      .state {
        line-height: 18px;
      }

      .icon .cell {
        // fixes a bug in IE11 where elipsis would appear underneath icons
        text-overflow: clip;
      }

      .status .cell {
        display: flex;
        align-items: center;
        span {
          flex: 1;
        }
        i {
          @include size(20px);
        }
      }
    }

    .information-sheets-table {
      .el-table__row {
        cursor: default;
      }
    }
  }
</style>
