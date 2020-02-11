<template>
  <div v-if="displayPage">
    <el-card shadow="never">
      <template v-slot:header>
        <span>
          <h2><i class="el-icon-lpa-user"></i>{{ $t('base.navigation.admin_user_create') }}</h2>
        </span>
      </template>

      <el-form ref="form" :model="form" label-position="top" @submit.native.prevent :disabled="isFormDisabled">
        <el-form-item-wrap
          :label="$t('entities.general.full_name')"
          prop="username"
          required>
          <user-search
            name="username"
            :label="$t('entities.general.full_name')"
            v-bind:user.sync="form.user">
          </user-search>
          <form-error name="username" />
        </el-form-item-wrap>

        <el-form-item :label="$tc('entities.general.organizational_units', 2)" for="organizationalUnits" prop="organizational_units">
          <el-select-wrap
            v-model="form.organizational_units"
            name="organizational_units"
            :data-vv-as="$tc('entities.general.organizational_units', 2)"
            v-validate="''"
            :options="organizationalUnits"
            multiple
          />
        </el-form-item>

        <el-form-item :label="$t('entities.user.roles')" for="roles" prop="roles">
          <el-select-wrap
            v-model="form.roles"
            name="roles"
            :data-vv-as="$t('entities.user.roles')"
            v-validate="''"
            :options="roles"
            multiple
          />
        </el-form-item>

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
  import ElSelectWrap from '@components/forms/el-select-wrap';
  import FormError from '@components/forms/error.vue';
  import UserSearch from '@components/forms/user-search';

  import UsersAPI from '@api/users';

  import FormUtils from '@mixins/form/utils.js';

  export default {
    name: 'user-create',

    extends: Page,

    inject: ['$validator'],

    mixins: [ FormUtils ],

    components: { FormError, ElSelectWrap, ElFormItemWrap, UserSearch },

    data() {
      return {
        isUsingNewLoading: true,
        organizationalUnits: [],
        roles: [],
        form: {
          user: {},
          organizational_units: [],
          roles: []
        }
      }
    },

    methods: {
      async loadData() {
        const requests = [];
        requests.push(
          UsersAPI.getCreateInfo(),
        );
        // Exception handled by interceptor
        const [
          { data: { organizational_units, roles } }
        ] = await Promise.all(requests);

        this.organizationalUnits = organizational_units;
        this.roles = roles;
      },

      // Form handlers
      onSubmit() {
        this.submit(this.handleCreate);
      },

      async handleCreate() {
        const formData = Object.assign({}, this.form);
        formData.username = this.form.user.username;
        await UsersAPI.create(formData);

        this.isSubmitting = false;
        this.notifySuccess({
          message: this.$t('components.notice.message.user_created')
        });
        this.goToParentPage();
      }
    },

    mounted() {
      this.autofocus('username');
    }
  };
</script>

<style lang="scss">

</style>
