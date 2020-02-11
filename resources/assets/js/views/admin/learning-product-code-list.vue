<template>
  <div v-if="displayPage">
    <el-row>
      <el-col>
        <el-card-wrap
          icon="el-icon-lpa-settings"
          :headerTitle="$t('base.navigation.admin_learning_product_code_list')"
        >
          <template v-slot:controls>
            <el-control-wrap
              componentName="el-button"
              :tooltip="$t('components.tooltip.create_learning_product_code')"
              size="mini"
              icon="el-icon-lpa-add"
              @click.native="goToPage({ name: 'learning-product-code-create' })"
            />
          </template>
        </el-card-wrap>
      </el-col>
    </el-row>
    <el-row>
      <el-col>
        <entity-data-table
          :data="learningProductCodes"
          :attributes="dataTableAttributes.learningProductCodes"
          :table-props="tableProps"
          @rowClick="onRowClick"
          @formatData="onFormatData"
        />
      </el-col>
    </el-row>
  </div>
</template>

<script>
  import Page from '@components/page';
  import EntityDataTable from '@components/entity-data-table.vue';
  import ElCardWrap from '@components/el-card-wrap';
  import ElControlWrap from '@components/el-control-wrap';

  import LearningProductCodesAPI from '@api/learning-product-codes';

  export default {
    name: 'learning-product-code-list',

    extends: Page,

    components: { EntityDataTable, ElCardWrap, ElControlWrap },

    data() {
      return  {
        isUsingNewLoading: true,
        learningProductCodes: [],
        tableProps: {
          defaultSort: {
            prop: 'code',
            order: 'ascending'
          }
        }
      }
    },

    computed: {
      dataTableAttributes: {
        get() {
          return {
            learningProductCodes: {
              id: {
                isColumn: false
              },
              code: {
                label: this.$t('entities.learning_product.code'),
                minWidth: 10
              },
              active: {
                label: this.$t('entities.general.active'),
                minWidth: 10,
                isFilterable: true
              },
              comments: {
                label: this.$t('entities.general.comments'),
                minWidth: 20
              },
              created_at: {
                label: this.$t('entities.general.created'),
                minWidth: 14
              },
              created_by: {
                isColumn: false
              },
              updated_at: {
                label: this.$t('entities.general.updated'),
                minWidth: 14
              },
              updated_by: {
                isColumn: false
              }
            }
          }
        }
      }
    },

    methods: {
      async loadData() {
        const { data: learning_product_codes } = await LearningProductCodesAPI.getAll();
        this.learningProductCodes = learning_product_codes;
      },

      onRowClick(learningProductCode) {
        this.scrollToTop();
        this.goToPage({
          name: 'learning-product-code',
          params: { entityId: `${learningProductCode.id}` }
        });
      },

      onFormatData(normEntity) {
        normEntity.active = this.$options.filters.booleanFilter(normEntity.active);
        normEntity.comments = this.$options.filters.nullFilter(normEntity.comments);
      }
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';
  @import '~@sass/abstracts/mixins/helpers';

  .learning-product-code-list {
    h2 i {
      @include svg(settings, $--color-primary);
    }
  }
</style>
