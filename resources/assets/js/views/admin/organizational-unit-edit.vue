<template>
  <div v-if="displayPage">
    <el-card shadow="never">
      <template v-slot:header>
        <span>
          <h2><i class="el-icon-lpa-edit"></i>{{ $t('base.navigation.admin_organizational_unit_edit') }}</h2>
        </span>
      </template>

      <el-form label-position="top" :disabled="isFormDisabled">
        <el-form-item-wrap
          prop="name"
          :label="$t('entities.general.name')"
          :required="true"
        >
          <input-localized
            :modelEn.sync="form.name_en"
            :modelFr.sync="form.name_fr"
            :validate="'required'"
            :dataVvAsEn="$t('entities.general.name')"
            :dataVvAsFr="$t('entities.general.name')"
            nameEn="name_en"
            nameFr="name_fr"
            maxlength="150"
          />
        </el-form-item-wrap>

        <el-form-item-wrap
          :label="$t('entities.general.email')"
          prop="email"
          required
        >
          <input-wrap
            name="email"
            :data-vv-as="$t('entities.general.email')"
            v-model="form.email"
            maxlength="250"
            v-validate="'required|email'"
          />
        </el-form-item-wrap>

        <el-form-item-wrap
          :label="$t('entities.organizational_unit.director')"
          prop="director"
          required
        >
          <user-search
            name="director"
            :label="$t('entities.organizational_unit.director')"
            v-bind:user.sync="form.director"
          />
          <form-error name="director" />
        </el-form-item-wrap>

        <el-form-item class="form-footer">
          <el-button
            :disabled="isFormDisabled"
            @click="goToParentPage()"
          >
            {{ $t('base.actions.cancel') }}
          </el-button>
          <el-button
            :disabled="!isFormDirty || isFormDisabled"
            :loading="isSubmitting"
            type="primary"
            @click="onSubmit()"
          >
            {{ $t('base.actions.save') }}
          </el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
  import Page from '@components/page';

  import ElFormItemWrap from '@components/forms/el-form-item-wrap';
  import InputWrap from '@components/forms/input-wrap';
  import InputLocalized from '@components/forms/input-localized';
  import UserSearch from '@components/forms/user-search';
  import FormError from '@components/forms/error.vue';

  import FormUtils from '@mixins/form/utils.js';

  import OrganizationalUnitsAPI from '@api/organizational-units';

  export default {
    name: 'organizational-unit-edit',

    extends: Page,

    inject: ['$validator'],

    mixins: [ FormUtils ],

    components: { ElFormItemWrap, InputWrap, InputLocalized, UserSearch, FormError },

    props: {
      entityId: {
        type: String,
        required: true
      }
    },

    data() {
      return {
        isUsingNewLoading: true,
        form: {}
      }
    },

    methods: {
      async loadData() {
        const requests = [];
        requests.push(
          OrganizationalUnitsAPI.getEditInfo(this.entityId)
        );
        // Exception handled by interceptor
        const [
          { data: { organizational_unit } }
        ] = await Promise.all(requests);

        if (this.isInitializing) {
          this.form = organizational_unit;
        }

        this.setBreadcrumbs([ organizational_unit.name ]);
      },

      // Form handlers
      onSubmit() {
        this.submit(this.handleUpdate);
      },

      async handleUpdate() {
        // @note: no try-catch required here
        // since we already do it in the form utils
        await OrganizationalUnitsAPI.update(this.form);
        this.isSubmitting = false;
        this.notifySuccess({
          message: this.$t('components.notice.message.organizational_unit_updated')
        });
        this.goToParentPage();
      }
    }
  };
</script>

<style lang="scss">

</style>
