<template>
  <div v-if="displayPage">
    <el-card shadow="never">
      <template v-slot:header>
        <span>
          <h2><i class="el-icon-lpa-edit"></i>{{ $t('pages.learning_product_edit.title') }}</h2>
        </span>
      </template>

      <el-form ref="form" :model="form" label-position="top" @submit.native.prevent :disabled="isFormDisabled">
        <el-form-item-wrap
          :label="$t('entities.learning_product.name')"
          prop="name"
          required
        >
          <input-wrap
            id="name"
            name="name"
            :data-vv-as="$t('entities.learning_product.name')"
            v-model="form.name"
            maxlength="175"
            v-validate="'required'"
            auto-complete="off"
          />
        </el-form-item-wrap>

        <el-form-item-wrap
          :label="$tc('entities.general.organizational_units')"
          prop="organizational_unit_id"
          required
        >
          <el-select-wrap
            v-model="form.organizational_unit_id"
            name="organizational_unit_id"
            :data-vv-as="$tc('entities.general.organizational_units')"
            v-validate="'required'"
            :options="organizationalUnitList"
          />
        </el-form-item-wrap>

        <el-form-item-wrap
          v-if="hasRole('admin')"
          :label="$tc('entities.general.status')"
          prop="state_id"
          required
        >
          <el-select-wrap
            v-model="form.state_id"
            name="state_id"
            :data-vv-as="$tc('entities.general.status')"
            v-validate="'required'"
            :options="stateList"
          />
        </el-form-item-wrap>

        <el-form-item-wrap
          :label="$t('entities.learning_product.manager')"
          prop="manager"
          required
        >
          <user-search
            name="manager"
            :label="$t('entities.learning_product.manager')"
            v-bind:user.sync="form.manager"
          />
          <form-error name="manager" />
        </el-form-item-wrap>

        <el-form-item-wrap
          :label="$t('entities.learning_product.primary_contact')"
          prop="primary_contact"
          required
        >
          <user-search
            name="primary_contact"
            :label="$t('entities.learning_product.primary_contact')"
            v-bind:user.sync="form.primary_contact"
          />
          <form-error name="primary_contact" />
        </el-form-item-wrap>

        <el-form-item class="form-footer">
          <el-button :disabled="isFormDisabled" @click="goToParentPage()">{{ $t('base.actions.cancel') }}</el-button>
          <el-button :disabled="isFormPristine || isFormDisabled" :loading="isSubmitting" type="primary" @click="onSubmit()">{{ $t('base.actions.save') }}</el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
  import Page from '@components/page';
  import FormError from '@components/forms/error.vue';
  import ElFormItemWrap from '@components/forms/el-form-item-wrap';
  import ElSelectWrap from '@components/forms/el-select-wrap';
  import UserSearch from '@components/forms/user-search';
  import InputWrap from '@components/forms/input-wrap';

  import FormUtils from '@mixins/form/utils.js';

  import LearningProductsAPI from '@api/learning-products';

  export default {
    name: 'learning-product-edit',

    extends: Page,

    inject: ['$validator'],

    mixins: [ FormUtils ],

    components: { ElFormItemWrap, ElSelectWrap, InputWrap, FormError, UserSearch },

    props: {
      entityId: {
        type: String,
        required: true
      }
    },

    data() {
      return {
        isUsingNewLoading: true,
        form: {
          name: '',
          organizational_unit_id: null,
          manager: {},
          primary_contact: {}
        },
        organizationalUnitList: [],
        stateList: []
      }
    },

    methods: {
      async loadData() {
        const requests = [];
        requests.push(
          LearningProductsAPI.getEditInfo(this.entityId)
        );
        // Exception handled by interceptor
        const [
          { data: { learning_product, organizational_units, states } }
        ] = await Promise.all(requests);

        if (this.isInitializing) {
          this.form = learning_product;
        }
        this.organizationalUnitList = organizational_units;
        this.stateList = states;

        this.setBreadcrumbs([ learning_product.name ]);
      },

      async onSubmit() {
        this.submit(this.handleUpdate);
      },

      async handleUpdate() {
        const formData = Object.assign({}, this.form);
        formData.manager = formData.manager.username;
        formData.primary_contact = formData.primary_contact.username;
        // @note: no try-catch required here
        // since we already do it in the form utils
        await LearningProductsAPI.update(formData);
        this.isSubmitting = false;
        this.notifySuccess({
          message: this.$t('components.notice.message.learning_product_updated')
        });
        this.goToParentPage();
      }
    }
  };
</script>

<style lang="scss">

</style>
