<template>
  <div v-if="displayPage">
    <el-row>
      <el-col>
        <infobox-learning-product-code
          :learningProductCode="learningProductCode"
          :canDelete="canDelete"
          @delete="onDelete"
        />
      </el-col>
    </el-row>
  </div>
</template>

<script>
  import Page from '@components/page';
  import InfoboxLearningProductCode from '@components/infoboxes/infobox-learning-product-code';
  import EntityDataTable from '@components/entity-data-table.vue';
  import ElCardWrap from '@components/el-card-wrap';
  import ElControlWrap from '@components/el-control-wrap';

  import LearningProductCodesAPI from '@api/learning-product-codes';

  export default {
    name: 'learning-product-code',

    extends: Page,

    components: { InfoboxLearningProductCode },

    props: {
      entityId: {
        type: String,
        required: true
      }
    },

    data() {
      return {
        isUsingNewLoading: true,
        canDelete: false
      }
    },

    methods: {
      async loadData() {
        const requests = [];

        // Load learning product code with all of its related information and authorizations.
        requests.push(
          LearningProductCodesAPI.get(this.entityId),
          LearningProductCodesAPI.canDelete(this.entityId),
        );
        // Exception handled by interceptor
        const [
          { data: learning_product_code },
          { data: { delete: canDelete } }
        ] = await Promise.all(requests);

        // Update view data.
        this.learningProductCode = learning_product_code;
        this.canDelete = canDelete;

        this.setBreadcrumbs([ this.learningProductCode.code ]);
      },

      async onDelete() {
        await LearningProductCodesAPI.delete(this.entityId);
        this.notifySuccess({
          message: this.$t('components.notice.message.learning_product_code_deleted')
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

  .learning-product-code {
    h2 i {
      @include svg(settings, $--color-primary);
    }
  }
</style>
