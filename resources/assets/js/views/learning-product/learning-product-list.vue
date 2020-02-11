<template>
  <div v-if="displayPage">
    <el-row>
      <el-col>
        <el-card-wrap
          icon="el-icon-lpa-learning-product"
          :headerTitle="$t('base.navigation.learning_products')"
        >
          <template v-slot:controls v-if="hasRole('owner') || hasRole('admin')">
            <el-control-wrap
              componentName="el-button"
              :tooltip="$t('pages.learning_product_list.create_learning_product')"
              size="mini"
              icon="el-icon-lpa-add"
              @click.native="goToPage({ name: 'learning-product-create' })"
            />
          </template>
        </el-card-wrap>
      </el-col>
    </el-row>
    <el-row>
      <el-col>
        <entity-data-table
          :data="learningProducts"
          :attributes="dataTableAttributes.learningProducts"
          @rowClick="onLearningProductRowClick"
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

  import LearningProductsAPI from '@api/learning-products';

  export default {
    name: 'learning-product-list',

    extends: Page,

    components: { EntityDataTable, ElCardWrap, ElControlWrap },

    data() {
      return {
        isUsingNewLoading: true,
        learningProducts: []
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
            }
          }
        }
      }
    },

    methods: {
      async loadData() {
        const { data: { learning_products } } = await LearningProductsAPI.getAll();
        this.learningProducts = learning_products;
      },

      onLearningProductRowClick(learningProduct) {
        this.scrollToTop();
        this.goToPage({
          name: 'learning-product',
          params: { entityId: `${learningProduct.id}` }
        });
      },

      onFormatData(normEntity) {
        normEntity.type = this.$options.filters.learningProductTypeSubTypeFilter(normEntity);
      }
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';
  @import '~@sass/abstracts/mixins/helpers';

  .learning-product-list {
    i.el-icon-lpa-learning-product {
      @include svg(learning-product, $--color-primary);
    }
  }
</style>
