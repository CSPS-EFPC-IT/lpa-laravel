<template>
  <div v-if="displayPage">
    <el-card shadow="never">
      <template v-slot:header>
        <span>
          <h2><i class="el-icon-lpa-settings"></i>{{ $t('base.navigation.admin_learning_product_code_create') }}</h2>
        </span>
      </template>

      <el-form ref="form" :model="form" label-position="top" @submit.native.prevent :disabled="isFormDisabled">
        <el-form-item-wrap
          :label="$t('entities.learning_product.code')"
          prop="code"
          required
        >
          <input-wrap
            id="code"
            name="code"
            :data-vv-as="$t('entities.learning_product.code')"
            v-model="form.code"
            maxlength="15"
            v-validate="'required'"
            auto-complete="off"
          />
        </el-form-item-wrap>

        <el-form-item-wrap
          :label="$t('entities.general.active')"
          prop="active"
          required
        >
          <yes-no
            v-model="form.active"
            v-validate="'required'"
            :data-vv-as="$t('entities.general.active')"
            name="active"
          />
        </el-form-item-wrap>

        <el-form-item-wrap
          :label="$t('entities.general.comments')"
          prop="comments"
        >
          <input-wrap
            id="comments"
            name="comments"
            :data-vv-as="$t('entities.general.comments')"
            v-model="form.comments"
            maxlength="255"
            v-validate="''"
            auto-complete="off"
          />
        </el-form-item-wrap>

        <el-form-item class="form-footer">
          <el-button :disabled="isFormDisabled" @click="goToParentPage()">{{ $t('base.actions.cancel') }}</el-button>
          <el-button :disabled="isFormPristine || isFormDisabled" :loading="isSubmitting" type="primary" @click="onSubmit()">{{ $t('base.actions.create') }}</el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
  import Page from '@components/page';
  import ElFormItemWrap from '@components/forms/el-form-item-wrap';
  import InputWrap from '@components/forms/input-wrap';
  import YesNo from '@components/forms/yes-no';
  import FormUtils from '@mixins/form/utils.js';

  import LearningProductCodesAPI from '@api/learning-product-codes';

  export default {
    name: 'learning-product-code-create',

    extends: Page,

    inject: ['$validator'],

    mixins: [ FormUtils ],

    components: { ElFormItemWrap, InputWrap, YesNo },

    data() {
      return {
        isUsingNewLoading: true,
        form: {
          code: '',
          comments: '',
          active: null,
        }
      }
    },

    methods: {
      // Form handlers
      onSubmit() {
        this.submit(this.handleCreate);
      },

      async handleCreate() {
        await LearningProductCodesAPI.create(this.form);

        this.isSubmitting = false;
        this.notifySuccess({
          message: this.$t('components.notice.message.learning_product_code_created')
        });
        this.goToParentPage();
      }
    },

    mounted() {
      this.autofocus('code');
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';
  @import '~@sass/abstracts/mixins/helpers';

  .learning-product-code-create {
    h2 i {
      @include svg(settings, $--color-primary);
    }
  }
</style>
