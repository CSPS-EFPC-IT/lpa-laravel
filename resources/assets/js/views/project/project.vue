<template>
  <div v-if="displayPage">
    <el-row :gutter="20" class="equal-height">
      <el-col>
        <infobox-project
          :project="project"
          :canEdit="canEdit"
          :canDelete="canDelete"
          @delete="onDelete"
        />
      </el-col>
      <process-actions-col
        :entity="project"
        entityType="project"
        :processDefinitions="processDefinitions"
        :processPermissions="processPermissions"
        @error="onError"
      />
    </el-row>
    <el-row>
      <el-col>
        <el-tabs type="border-card">
          <!-- Project outline -->
          <el-tab-pane>
            <template v-slot:label>
              <span>
                <i class="el-icon el-icon-lpa-outline tab-icon"></i> {{ $t('entities.project.outline.label') }}
              </span>
            </template>
            <div class="outline" v-html="project.outline"></div>
          </el-tab-pane>
          <!-- Learning products -->
          <el-tab-pane>
            <template v-slot:label>
              <span>
                <i class="el-icon el-icon-lpa-learning-product tab-icon"></i> {{ $t('base.navigation.learning_products') }}
              </span>
            </template>
            <entity-data-table
              :data="learningProducts"
              :attributes="dataTableAttributes.learningProducts"
              @rowClick="onLearningProductRowClick"
              @formatData="onFormatData"
              :searchable="false"
            />
          </el-tab-pane>
          <!-- Process history -->
          <el-tab-pane>
            <template v-slot:label>
              <span>
                <i class="el-icon el-icon-lpa-history"></i> {{ $t('entities.process.history') }}
              </span>
            </template>
            <entity-data-table
              :data="processHistory"
              :attributes="dataTableAttributes.processHistory"
              @rowClick="onProcessRowClick"
              :searchable="false"
            />
          </el-tab-pane>
          <!-- Information sheets -->
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
      </el-col>
    </el-row>
  </div>
</template>

<script>
  import Vue from 'vue';
  import Page from '@components/page';
  import InfoboxProject from '@components/infoboxes/infobox-project';
  import EntityDataTable from '@components/entity-data-table';
  import ProcessActionsCol from '@components/process-actions-col';
  import ProjectsAPI from '@api/projects';

  export default {
    name: 'project',

    extends: Page,

    components: { InfoboxProject, EntityDataTable, ProcessActionsCol },

    props: {
      entityId: {
        type: String,
        required: true
      }
    },

    data() {
      return {
        isUsingNewLoading: true,
        project: {},
        informationSheets: [],
        learningProducts: [],
        processHistory: [],
        processDefinitions: [],
        processPermissions: {},
        canEdit: false,
        canDelete: false
      };
    },

    computed: {
      dataTableAttributes: {
        get() {
          return {
            learningProducts: {
              id: {
                label: this.$t('entities.general.lpa_num'),
                minWidth: 12
              },
              name: {
                label: this.$t('entities.learning_product.name'),
                minWidth: 36
              },
              type: {
                label: this.$t('entities.learning_product.type'),
                minWidth: 13,
                areFiltersSorted: true,
                isFilterable: true
              },
              sub_type: {
                isColumn: false
              },
              organizational_unit: {
                label: this.$tc('entities.general.organizational_units'),
                minWidth: 25,
                areFiltersSorted: true,
                isFilterable: true
              },
              updated_at: {
                label: this.$t('entities.general.updated'),
                minWidth: 20
              },
              updated_by: {
                isColumn: false
              },
              state: {
                label: this.$t('entities.general.status'),
                minWidth: 14,
                areFiltersSorted: true,
                isFilterable: true
              },
              'current_process.definition': {
                label: this.$t('entities.process.current'),
                minWidth: 21,
                isFilterable: true
              }
            },

            processHistory: {
              id: {
                isColumn: false
              },
              entity_id: {
                isColumn: false
              },
              definition: {
                label: this.$t('entities.general.name')
              },
              created_at: {
                label: this.$t('entities.process.started')
              },
              created_by: {
                isColumn: false
              },
              updated_at: {
                label: this.$t('entities.general.updated')
              },
              updated_by: {
                isColumn: false
              },
              state: {
                label: this.$t('entities.general.status'),
                areFiltersSorted: true,
                isFilterable: true
              }
            }
          }
        }
      },
    },

    methods: {
      async loadData() {
        const requests = [];
        const context = true;

        // Load project with all of its related information and authorizations.
        requests.push(
          ProjectsAPI.get(this.entityId, context),
          ProjectsAPI.can(this.entityId, ['update', 'delete']),
        );

        const [
          { data: project, meta },
          { data: { update: canEdit, delete: canDelete }},
        ] = await Promise.all(requests);

        // Update view data.
        this.project = project;
        this.canEdit = canEdit;
        this.canDelete = canDelete;
        this.processDefinitions = meta.process_definitions;
        this.processHistory = meta.process_instances;
        this.informationSheets = _.sortBy(meta.information_sheets, 'definition.display_sequence');
        this.learningProducts = meta.learning_products;

        // Update breadcrumb.
        this.setBreadcrumbs([ this.project.name ]);

        // Load process definition authorizations.
        Promise.all(this.processDefinitions.map(
          (async ({ name_key }) => {
            const response = await ProjectsAPI.canStartProcess(this.entityId, name_key);
            Vue.set(this.processPermissions, name_key, response.data['start-process']);
          })
        ));
      },

      onFormatData(normEntity) {
        normEntity.type = this.$options.filters.learningProductTypeSubTypeFilter(normEntity);
      },

      onLearningProductRowClick(learningProduct) {
        this.scrollToTop();
        this.goToPage({
          name: 'learning-product',
          params: { entityId: `${learningProduct.id}` }
        });
      },

      onProcessRowClick(process) {
        this.scrollToTop();
        this.$router.push(`${process.entity_id}/process/${process.id}`);
      },

      async onDelete() {
        await ProjectsAPI.delete(this.entityId);
        this.notifySuccess({
          message: this.$t('components.notice.message.project_deleted')
        });
        this.goToParentPage();
      },

      onError() {
        this.loadData();
      }
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';
  @import '~@sass/abstracts/mixins/helpers';

  .project {
    margin: 0 auto;

    .information-sheets-table {
      .el-table__row {
        cursor: default;
      }
    }

    .outline {
      border: 1px solid #ccc;
      padding: 12px;
      white-space: pre-line;
    }
  }

  .el-icon-lpa-learning-product.tab-icon {
    @include svg(learning-product, $--color-primary);
  }
</style>
