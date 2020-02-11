<template>
  <div v-if="displayPage">
    <el-card shadow="never">
      <template v-slot:header>
        <span>
          <h2><i class="el-icon-lpa-edit"></i>{{ $t('pages.project_edit.title') }}</h2>
        </span>
      </template>

      <el-form label-position="top" :disabled="isFormDisabled">
        <el-form-item-wrap
          :label="$t('entities.general.name')"
          prop="name"
          required
        >
          <input-wrap
            name="name"
            :data-vv-as="$t('entities.general.name')"
            v-model="form.name"
            maxlength="175"
            v-validate="'required'"
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
          <el-button :disabled="!isFormDirty || isFormDisabled" :loading="isSubmitting" type="primary" @click="onSubmit()">{{ $t('base.actions.save') }}</el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
  import Page from '@components/page';
  import ElFormItemWrap from '@components/forms/el-form-item-wrap';
  import ElSelectWrap from '@components/forms/el-select-wrap';
  import InputWrap from '@components/forms/input-wrap';
  import FormError from '@components/forms/error.vue';
  import FormUtils from '@mixins/form/utils.js';
  import ProjectsAPI from '@api/projects';

  export default {
    name: 'project-edit',

    extends: Page,

    inject: ['$validator'],

    mixins: [ FormUtils ],

    components: { ElFormItemWrap, ElSelectWrap, InputWrap, FormError },

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
          outline: ''
        },
        organizationalUnits: []
      };
    },

    methods: {
      async loadData() {
        const { data: { project, organizational_units }} = await ProjectsAPI.getEditInfo(this.entityId);
        if (this.isInitializing) {
          this.form = project;
        }
        this.organizationalUnits = organizational_units;

        this.setBreadcrumbs([ project.name ]);
      },

      onSubmit() {
        this.submit(this.handleUpdate);
      },

      async handleUpdate() {
        const response = await ProjectsAPI.update(this.form);
        const project = response.data;

        this.isSubmitting = false;
        this.notifySuccess({
          message: this.$t('components.notice.message.project_updated')
        });

        this.goToParentPage();
      }
    }
  };
</script>

<style lang="scss">

</style>
