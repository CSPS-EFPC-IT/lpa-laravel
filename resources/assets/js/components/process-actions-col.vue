<template>
  <el-col
    :span="8"
    v-if="entity.current_process || canActionsBeVisible"
  >
    <el-card
      shadow="never"
      class="process-actions-col"
    >
      <template v-slot:header>
        <div>
          <h3>{{ $t('entities.process.actions') }}</h3>
        </div>
      </template>
      <ul class="process-actions-col-list">
        <template v-if="entity.current_process">
          <li>
            <el-button type="primary" @click="continueToProcess(entity.current_process.id)">
              {{ $t('entities.process.view_current') }}
            </el-button>
          </li>
        </template>
        <template v-else-if="canActionsBeVisible">
          <li
            v-for="process in processDefinitions"
            :key="process.id"
          >
            <el-button
              type="primary"
              :disabled="!processPermissions[process.name_key]"
              @click="isDialogVisible = true"
            >
              {{ process.name }}
            </el-button>

            <el-dialog
              :title="$t('entities.process.start')"
              :visible.sync="isDialogVisible"
            >
              <div class="el-dialog__status el-icon-warning"></div>
              <div class="el-dialog__message">
                <span v-html="$t('components.notice.message.start_process', { process_name: process.name })"></span>
                <br>
                <br>
                <span>
                  <el-checkbox
                    v-if="hasRole('admin')"
                    v-model="sendEmailNotif"
                    :disabled="isSubmitting"
                  >
                    {{ $t('base.actions.send_email') }}
                  </el-checkbox>
                </span>
              </div>
              <span slot="footer">
                <el-button size="small" @click="isDialogVisible = false">{{ $t('base.actions.cancel') }}</el-button>
                <el-button size="small" type="primary" :loading="isSubmitting" @click="triggerStartProcess(process.name, process.name_key)">{{ $t('base.actions.start') }}</el-button>
              </span>
            </el-dialog>
          </li>
        </template>
      </ul>
    </el-card>
  </el-col>
</template>

<script>
  import { mapGetters } from 'vuex';
  import ProcessesAPI from '@api/processes';

  import ElControlWrap from '@components/el-control-wrap';

  export default {
    name: 'process-actions-col',

    components: { ElControlWrap },

    inheritAttrs: false,

    props: {
      entity: {
        type: Object,
        required: true
      },
      entityType: {
        type: String,
        required: true
      },
      processDefinitions: {
        type: Array,
        required: true
      },
      processPermissions: {
        type: Object,
        required: true
      }
    },

    data() {
      return {
        sendEmailNotif: false,
        isDialogVisible: false,
        isSubmitting: false
      };
    },

    computed: {
      ...mapGetters({
        hasRole: 'users/hasRole'
      })
    },

    methods: {
      canActionsBeVisible() {
        return this.hasRole('owner') || this.hasRole('admin');
      },

      async triggerStartProcess(processName, processNameKey) {
        this.isSubmitting = true;
        this.isDialogVisible = false;
        try {
          const entityId = this.entity.id;
          const entityType = this.entityType;
          const sendEmailNotif = this.hasRole('admin') ? this.sendEmailNotif : true;
          const response = await ProcessesAPI.start(processNameKey, entityId, entityType, sendEmailNotif);
          const process = response.data.process_instance;

          this.notifySuccess({
            message: this.$t('components.notice.message.process_started')
          });

          this.$router.push(`${entityId}/process/${process.id}`);
        } catch (e) {
          if (!e.response) {
            throw e;
          }
          if (e.response.status === HttpStatusCodes.FORBIDDEN) {
            this.$emit('error');
          }
        }
      },

      continueToProcess(processId) {
        const entityId = this.$route.params.entityId;
        this.$router.push(`${entityId}/process/${processId}`);
      }
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';
  @import '~@sass/abstracts/mixins/helpers';

  .process-actions-col {
    user-select: none;
    .el-card__header {
      background-color: #f5f7fa;
      h3 {
        margin: 0;
        padding: 3px 0px;
        text-align: center;
        display: block;
        color: #524f74;
      }
    }
    &-list {
      list-style: none;
      margin: 0;
      padding: 0;
      > li {
        text-align: center;
        > &:first-child button {
          // reset all before applying to specific
          border-radius: 0;
          border-top-left-radius: $--border-radius-base;
          border-top-right-radius: $--border-radius-base;
        }
        > &:last-child button {
          // reset all before applying to specific
          border-radius: 0;
          border-bottom-left-radius: $--border-radius-base;
          border-bottom-right-radius: $--border-radius-base;
        }
        > button {
          width: 100%;
          border-radius: 0;
        }
        @include quantity-query(to 1) {
          > button {
            border-radius: $--border-radius-base;
          }
        }
      }
    }
  }
</style>
