<template>
  <div v-if="displayPage">
    <el-row :gutter="20" class="equal-height">
      <el-col>
        <infobox-learning-product
          :learningProduct="learningProduct"
          :canEdit="canEdit"
          :canDelete="canDelete"
          @delete="onDelete"
        />
      </el-col>
      <process-actions-col
        :entity="learningProduct"
        :entityType="learningProduct.type.name_key"
        :processDefinitions="processDefinitions"
        :processPermissions="processPermissions"
        @error="onError"
      />
    </el-row>
    <el-row>
      <el-col>
        <el-tabs type="border-card">
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
          <!-- Information Sheet -->
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
                :label="$t('entities.learning_product.name')"
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
  import _ from 'lodash';
  import { mapMutations } from 'vuex';

  import Page from '@components/page';
  import InfoboxLearningProduct from '@components/infoboxes/infobox-learning-product';
  import EntityDataTable from '@components/entity-data-table';
  import ProcessActionsCol from '@components/process-actions-col';

  import LearningProductsAPI from '@api/learning-products';
  import InformationSheetsAPI from '@api/information-sheets';
  import ProcessesAPI from '@api/processes';

  export default {
    name: 'learning-products',

    extends: Page,

    components: { InfoboxLearningProduct, EntityDataTable, ProcessActionsCol },

    props: {
      entityId: {
        type: String,
        required: true
      }
    },

    data() {
      return {
        isUsingNewLoading: true,
        learningProduct: {},
        informationSheets: [],
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
      }
    },

    methods: {
      async loadData() {
        const requests = [];
        const context = true;

        // Load learning product with all of its related information and authorizations.
        requests.push(
          LearningProductsAPI.get(this.entityId, context),
          LearningProductsAPI.can(this.entityId, ['update', 'delete'])
        );
        // Exception handled by interceptor
        const [
          { data: learning_product, meta },
          { data: { update: canEdit, delete: canDelete }},
        ] = await Promise.all(requests);

        // Update view data.
        this.learningProduct = learning_product;
        this.canEdit = canEdit;
        this.canDelete = canDelete;
        this.informationSheets = _.sortBy(meta.information_sheets, 'definition.display_sequence');
        this.processDefinitions = meta.process_definitions;
        this.processHistory = meta.process_instances;

        // Update breadcrumb.
        this.setBreadcrumbs([ this.learningProduct.name ]);

        // Load process definition authorizations.
        Promise.all(this.processDefinitions.map(
          (async ({ name_key }) => {
            const response = await LearningProductsAPI.canStartProcess(this.entityId, name_key);
            Vue.set(this.processPermissions, name_key, response.data['start-process']);
          })
        ));
      },

      viewProcess(process) {
        this.$router.push(`${process.entity_id}/process/${process.id}`);
      },

      onProcessRowClick(process) {
        this.scrollToTop();
        this.$router.push(`${process.entity_id}/process/${process.id}`);
      },

      async onDelete() {
        await LearningProductsAPI.delete(this.entityId);
        this.notifySuccess({
          message: this.$t('components.notice.message.learning_product_deleted')
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

</style>
