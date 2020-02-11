<template>
  <div v-if="displayPage">
    <el-card shadow="never">
      <template v-slot:header>
        <span>
          <h2><i class="el-icon-lpa-edit"></i>{{ $t('base.navigation.admin_process_notification_edit') }}</h2>
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
          prop="subject"
          :label="$t('entities.process_notification.subject')"
          :required="true"
        >
          <input-localized
            :modelEn.sync="form.subject_en"
            :modelFr.sync="form.subject_fr"
            :validate="'required'"
            :dataVvAsEn="$t('entities.process_notification.subject')"
            :dataVvAsFr="$t('entities.process_notification.subject')"
            nameEn="subject_en"
            nameFr="subject_fr"
            maxlength="150"
          />
        </el-form-item-wrap>

        <el-form-item-wrap
          prop="body"
          :label="$t('entities.process_notification.body')"
          :required="true"
        >
          <input-localized
            :modelEn.sync="form.body_en"
            :modelFr.sync="form.body_fr"
            :validate="'required'"
            :dataVvAsEn="$t('entities.process_notification.body')"
            :dataVvAsFr="$t('entities.process_notification.body')"
            nameEn="body_en"
            nameFr="body_fr"
            maxlength="2500"
            type="textarea"
          />
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

  import ProcessNotificationsAPI from '@api/process-notifications';

  export default {
    name: 'process-notification-edit',

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
          ProcessNotificationsAPI.getEditInfo(this.entityId)
        );
        // Exception handled by interceptor
        const [
          { data: { process_notification } }
        ] = await Promise.all(requests);

        if (this.isInitializing) {
          this.form = process_notification;
        }

        this.setBreadcrumbs([ process_notification.name ]);
      },

      // Form handlers
      onSubmit() {
        this.submit(this.handleUpdate);
      },

      async handleUpdate() {
        // @note: no try-catch required here
        // since we already do it in the form utils
        await ProcessNotificationsAPI.update(this.form);
        this.isSubmitting = false;
        this.notifySuccess({
          message: this.$t('components.notice.message.process_notification_updated')
        });
        this.goToParentPage();
      }
    }
  };
</script>

<style lang="scss">

</style>
