<template>
  <div v-if="displayPage">
    <el-card shadow="never">
      <template v-slot:header>
        <span>
          <h2><i class="el-icon-lpa-pr"></i>{{ $t('pages.project_create.title') }}</h2>
        </span>
      </template>

      <el-form ref="form" :model="form" label-position="top" @submit.native.prevent :disabled="isFormDisabled">
        <el-form-item-wrap
          :label="$t('entities.general.name')"
          prop="name"
          required
        >
          <input-wrap
            id="name"
            name="name"
            :data-vv-as="$t('entities.general.name')"
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
          prop="outline"
          contextPath="entities.project.outline"
          required
        >
          <input-wrap
            v-model="form.outline"
            v-validate="'required'"
            :data-vv-as="$t('entities.project.outline.label')"
            name="outline"
            maxlength="1250"
            type="textarea"
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
  import FormError from '@components/forms/error.vue';
  import ElFormItemWrap from '@components/forms/el-form-item-wrap';
  import ElSelectWrap from '@components/forms/el-select-wrap';
  import InputWrap from '@components/forms/input-wrap';
  import FormUtils from '@mixins/form/utils.js';
  import ProjectsAPI from '@api/projects';

  export default {
    name: 'project-create',

    extends: Page,

    inject: ['$validator'],

    mixins: [ FormUtils ],

    components: { ElFormItemWrap, ElSelectWrap, InputWrap, FormError },

    data() {
      return {
        isUsingNewLoading: true,
        form: {
          name: '',
          organizational_unit_id: null,
          outline: ''
        },
        organizationalUnits: []
      }
    },

    methods: {
      async loadData() {
        const response = await ProjectsAPI.getCreateInfo();
        this.organizationalUnits = response.data.organizational_units;
      },

      // Form handlers
      async onSubmit() {
        this.submit(this.handleCreate);
      },

      async handleCreate() {
        const response = await ProjectsAPI.create(this.form);
        const project = response.data;

        this.isSubmitting = false;
        this.notifySuccess({
          message: this.$t('components.notice.message.project_created')
        });
        this.goToPage({
          name: 'project',
          params: { entityId: `${project.id}` }
        });
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

  .project-create {
    h2 i {
      @include svg(projects, $--color-primary);
    }
  }
</style>
