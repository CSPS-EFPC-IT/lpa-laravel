<template>
  <div v-if="displayPage">
    <el-card shadow="never">
      <template v-slot:header>
        <span>
          <h2><i class="el-icon-lpa-edit"></i>{{ $t('base.navigation.admin_user_edit') }}</h2>
        </span>
      </template>

      <el-form label-position="top" :disabled="isFormDisabled">
        <el-form-item-wrap
          :label="$tc('entities.general.organizational_units', 2)"
          prop="organizationalUnits"
        >
          <el-select-wrap
            v-model="form.organizational_units"
            name="organizational_units"
            :data-vv-as="$tc('entities.general.organizational_units', 2)"
            v-validate="''"
            :options="organizationalUnits"
            multiple
          />
        </el-form-item-wrap>

        <el-form-item-wrap
          :label="$t('entities.user.roles')"
          prop="roles"
        >
          <el-select-wrap
            v-model="form.roles"
            name="roles"
            :data-vv-as="$t('entities.user.roles')"
            v-validate="''"
            :options="roles"
            multiple
            sorted
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

  import FormUtils from '@mixins/form/utils.js';

  import UsersAPI from '@api/users';

  export default {
    name: 'user-edit',

    extends: Page,

    inject: ['$validator'],

    mixins: [ FormUtils ],

    components: { ElFormItemWrap, ElSelectWrap },

    props: {
      userId: {
        type: String,
        required: true
      }
    },

    data() {
      return {
        isUsingNewLoading: true,
        organizationalUnits: [],
        roles: [],
        form: {
          organizational_units: [],
          roles: []
        }
      }
    },

    methods: {
      async loadData() {
        const requests = [];
        requests.push(
          UsersAPI.getEditInfo(this.userId)
        );
        // Exception handled by interceptor
        const [
          { data: { user, organizational_units, roles } }
        ] = await Promise.all(requests);

        if (this.isInitializing) {
          this.form = user;
          // replace our internal organizational_units with only the ids
          // since ElementUI only need ids to populate the selected options
          this.form.organizational_units = user.organizational_units.map(unit => unit.id);
          this.form.roles = user.roles.map(role => role.id);
        }

        this.organizationalUnits = organizational_units;
        this.roles = roles;

        this.setBreadcrumbs([ user.name ]);
      },

      search(name) {
        return this.searchUser(name);
      },

      // Form handlers
      onSubmit() {
        this.submit(this.handleUpdate);
      },

      async handleUpdate() {
        await UsersAPI.update(this.form);
        this.isSubmitting = false;
        this.notifySuccess({
          message: this.$t('components.notice.message.user_updated')
        });
        this.goToParentPage();
      }
    }
  };
</script>

<style lang="scss">

</style>
