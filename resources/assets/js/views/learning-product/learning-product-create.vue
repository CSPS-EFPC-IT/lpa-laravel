<template>
  <div v-if="displayPage">
    <el-card shadow="never">
      <template v-slot:header>
        <span>
          <h2><i class="el-icon-lpa-learning-products"></i>{{ $t('pages.learning_product_create.title') }}</h2>
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
            :options="organizationalUnits"
          />
        </el-form-item-wrap>

        <el-form-item-wrap
          :label="$t('entities.learning_product.parent_project')"
          prop="project_id"
          required
        >
          <el-select-wrap
            v-model="form.project_id"
            filterable
            :clearable=true
            name="project_id"
            :data-vv-as="$t('entities.learning_product.parent_project')"
            v-validate="'required'"
            :options="innerProjects"
          />
        </el-form-item-wrap>

        <el-form-item-wrap
          :label="$t('entities.learning_product.type')"
          prop="type_id"
          required
        >
          <el-cascader
            v-model="learningProductType"
            name="type_id"
            :disabled="!projectSelected"
            :clearable=true
            element-loading-spinner="el-icon-loading"
            v-validate="'required'"
            :data-vv-as="$t('entities.learning_product.type')"
            :options="learningProductTypeListFormatted"
            :props="learningProductTypeOptions"
          />
          <form-error name="type_id" />
        </el-form-item-wrap>

        <el-form-item-wrap
          :label="$t('entities.learning_product.manager')"
          prop="manager"
          required
        >
          <user-search
            name="manager"
            :label="$t('entities.learning_product.manager')"
            :user.sync="form.manager"
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
            :user.sync="form.primary_contact"
          />
          <form-error name="primary_contact" />
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
  import FormError from '@components/forms/error.vue';
  import ElFormItemWrap from '@components/forms/el-form-item-wrap';
  import ElSelectWrap from '@components/forms/el-select-wrap';
  import UserSearch from '@components/forms/user-search';
  import InputWrap from '@components/forms/input-wrap';

  import FormUtils from '@mixins/form/utils.js';

  import LearningProductsAPI from '@api/learning-products';
  import ListsAPI from '@api/lists';

  export default {
    name: 'learning-product-create',

    extends: Page,

    inject: ['$validator'],

    mixins: [ FormUtils ],

    components: { ElFormItemWrap, ElSelectWrap, InputWrap, FormError, UserSearch },

    watch: {
      learningProductType(value) {
        // Update learning product type and sub type id when interacting with cascader control.
        this.form.type_id = value[0];
        this.form.sub_type_id = value[1];
      },

      'form.project_id'(projectId) {
        if (!projectId) {
          this.projectSelected = false;
          this.learningProductType = [];
          return;
        }

        this.projectSelected = true;

        const project = this.innerProjects.find(project => project.id === projectId );
        let availableProductTypes = _.cloneDeep(project.available_learning_product_types);

        // If a learning product type was entered beforehand, clear its value.
        if (this.learningProductType.length !== 0) {
          this.learningProductType = [];
        }

        // Update learning product type list when selecting a project
        // to only enable the available learning product types.
        this.learningProductTypeList.forEach(productType => {
          productType.children.forEach(productSubType => {
            productSubType.disabled = true;
            availableProductTypes.forEach((availableProductType, index) => {
              if (availableProductType && availableProductType.sub_type_id === productSubType.id) {
                productSubType.disabled = false;
                availableProductTypes.splice(index, 1);
                return false;
              }
            });
          });
        });
      }
    },

    data() {
      return {
        isUsingNewLoading: true,
        form: {
          project_id: null,
          name: '',
          organizational_unit_id: null,
          type_id: null,
          sub_type_id: null,
          manager: {},
          primary_contact: {}
        },
        learningProductType: [],
        learningProductTypeOptions: {
          label: 'name',
          value: 'id'
        },
        projectSelected: false,
        projects: [],
        learningProductTypeList: [],
        organizationalUnits: []
      }
    },

    computed: {
      learningProductTypeListFormatted: {
        get() {
          return this.formatLearningProductList(this.learningProductTypeList);
        },
        set(val) {
          this.learningProductTypeList = val;
        }
      },

      innerProjects() {
        let projects = _.cloneDeep(this.projects)
                        .sort((a, b) => this.$helpers.localeSort(a, b, 'name'));
        // Add LPA number to each project name.
        projects.forEach(project => {
          project.name = this.$options.filters.LPANumFilter(project.id) + ' - ' + project.name;
        });
        return projects;
      }
    },

    methods: {
      async loadData() {
        const requests = [];
        requests.push(
          LearningProductsAPI.getCreateInfo(),
          ListsAPI.getList('learning-product-type')
        );
        // Exception handled by interceptor
        const [
          { data: { projects, organizational_units } },
          { data: learningProductTypeList }
        ] = await Promise.all(requests);

        this.projects = projects;
        this.organizationalUnits = organizational_units;
        this.learningProductTypeList = learningProductTypeList;
      },

      // Form handlers
      async onSubmit() {
        this.submit(this.handleCreate);
      },

      async handleCreate() {
        // Update form data before submission to only submit usernames.
        const formData = Object.assign({}, this.form);
        formData.manager = this.form.manager.username;
        formData.primary_contact = this.form.primary_contact.username;
        const response = await LearningProductsAPI.create(formData);
        this.isSubmitting = false;
        this.notifySuccess({
          message: this.$t('components.notice.message.learning_product_created')
        });
        this.goToPage({
          name: 'learning-product',
          params: { entityId: `${response.data.id}` }
        });
      },

      // Remove children attribute when empty and disable all terms by default.
      formatLearningProductList(list) {
        list.forEach(item => {
          item.children.forEach(itemSub => {
            itemSub.disabled = true;
            if (itemSub.children && itemSub.children.length === 0) {
              delete itemSub.children;
            }
          });
        });
        return list;
      }
    },

    mounted() {
      this.autofocus('name');
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';
  @import '~@sass/abstracts/mixins/helpers';

  .learning-product-create {
    h2 i {
      @include svg(learning-product, $--color-primary);
    }
  }
</style>
