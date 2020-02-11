<template>
  <div class="process-form" v-if="displayPage">
    <el-row>
      <el-col>
        <infobox-process-form
          :processInstanceForm="processInstanceForm"
          :isClaimed.sync="isClaimed"
          @releaseForm="onReleaseForm"
          :canRelease="canRelease"
        />
      </el-col>
    </el-row>
    <el-row class="form-row">
      <el-col>
        <el-container class="form-wrap" direction="vertical">
          <el-main>
            <el-form label-position="top" @submit.native.prevent :disabled="!isClaimed" :class="{'is-disabled': !isClaimed}">
              <component
                :is="formComponent"
                type="border-card"
                tabPosition="left"
                :activeIndex.sync="activeIndex"
                :formData="formData"
                :errorTabs="errorTabs"
                :processEntityType="processInstance.entity_type"
                :processEntity="processEntity"
                @mounted="onFormMounted"
                :meta="meta"
              />
            </el-form>
          </el-main>
          <div class="process-form-footer">
            <el-footer height="50px"></el-footer>
            <el-footer height="50px">
              <div class="process-form-footer-nav">
                <el-button
                  size="mini"
                  icon="el-icon-arrow-left"
                  :disabled="isPrevDisabled"
                  @click="prevTabIndex()">
                    {{ $t('base.pagination.previous') }}
                </el-button>
                <el-button
                  size="mini"
                  :disabled="isNextDisabled"
                  @click="nextTabIndex()">
                    {{ $t('base.pagination.next') }}<i class="el-icon-arrow-right"></i>
                </el-button>
              </div>
              <div class="process-form-footer-actions" v-if="hasRole('process-contributor') || hasRole('admin')">
                <el-button :disabled="!isFormDirty" @click="onCancel" :loading="isCancelling" size="mini">{{ $t('base.actions.cancel') }}</el-button>
                <el-button :disabled="!isFormDirty" :loading="isSaving" @click="onSave" size="mini">{{ $t('base.actions.save') }}</el-button>
                <el-button :disabled="!canSubmit || (isFormEmpty || !isClaimed)" :loading="isSubmitting" @click="onSubmit" size="mini">{{ $t('base.actions.submit') }}</el-button>
              </div>
            </el-footer>
          </div>
        </el-container>
      </el-col>
    </el-row>
  </div>
</template>

<script>
  import _ from 'lodash';
  import { mapMutations } from 'vuex';

  import HttpStatusCodes from '@axios/http-status-codes';

  import Page from '@components/page';
  import InfoboxProcessForm from '@components/infoboxes/infobox-process-form';

  import FormUtils from '@mixins/form/utils';

  import ProcessFormsAPI from '@api/process-forms';

  export default {
    name: 'process-form',

    extends: Page,

    components: { InfoboxProcessForm },

    mixins: [ FormUtils ],

    props: {
      entityName: {
        type: String,
        required: true
      },
      entityId: {
        type: String,
        required: true
      },
      processId: {
        type: String,
        required: true
      },
      formId: {
        type: String,
        required: true
      }
    },

    data() {
      return {
        isUsingNewLoading: true,
        options: {
          hasTabsToValidate: true
        },
        activeIndex: '0',
        formWasDirty: false,
        hasSubmittedForm: false,
        tabsLength: 0,
        isCancelling: false,
        processInstance: {},
        // this is used as a backup in case we discard the info
        processInstanceForm: {},
        formData: {},
        meta: {},
        canEdit: false,
        canClaim: false,
        canUnclaim: false,
        canSubmit: false,
        canRelease: false
      }
    },

    computed: {
      formComponent() {
        return () => import(
          /* webpackMode: "eager" */
          `@components/forms/entities/${this.processInstanceForm.definition.name_key}.vue`
        );
      },

      isClaimed: {
        get() {
          // @todo: create loaded flags so that we know when the data has been loaded
          return this.isCurrentUser(this.processInstanceForm.current_editor);
        },
        // update the value server side
        async set(val) {
          // claiming
          if (val) {
            try {
              this.handleClaim();
            } catch (e) {
              // Exception handled by interceptor
              if (!e.response) {
                throw e;
              }
            }
          // unclaiming
          } else if (this.shouldConfirmBeforeLeaving) {
            this.confirmLoseChanges()
              .then(() => {
                this.handleUnclaim();
              }).catch(() => false);
          } else {
            this.handleUnclaim();
          }
        }
      },

      isPrevDisabled() {
        return parseInt(this.activeIndex, 10) === 0;
      },

      isNextDisabled() {
        return parseInt(this.activeIndex, 10) === this.tabsLength;
      },

      /**
       * This function gets all values from the form,
       * removes the falsy values,
       * put them on the same level of depth
       * and checks for the remaining values to be empty
       */
      isFormEmpty() {
        return !_.chain(this.formData)
                .omit('id', 'process_instance_id', 'process_instance_form_id').values()
                .filter(val => {
                  // we cannot use _.compact here
                  // since we need to keep the false values from the yes-no control
                  return val !== null && val !== undefined && val.length !== 0 && val !== NaN;
                })
                .flattenDeep()
                .value().filter(item => {
                  if (_.isArray(item) || _.isObject(item)) {
                    // returns true if the props from the item are falsy values
                    return !_.isEmpty(_.compact(_.toArray(item)));
                  } else {
                    return !_.isNull(item);
                  }
                }
        ).length;
      }
    },

    watch: {
      isFormDirty: function () {
        this.setShouldConfirmBeforeLeaving(this.isFormDirty);
      }
    },

    methods: {
      ...mapMutations('processForms', [
        'setHasFormSectionGroupsRemoved'
      ]),

      async loadData() {
        const requests = [];
        const context = true;

        requests.push(
          ProcessFormsAPI.getInstanceForm(this.formId, context),
          ProcessFormsAPI.can(this.formId, ['edit', 'submit', 'claim', 'unclaim', 'release']),
        );
        // Exception handled by interceptor
        const [
          { data: form, meta },
          { data: { edit: canEdit, submit: canSubmit, claim: canClaim, unclaim: canUnclaim, release: canRelease }}
        ] = await Promise.all(requests);

        // Update view data.
        this.processInstanceForm = form;
        if (this.isInitializing) {
          this.formData = _.cloneDeep(this.processInstanceForm.form_data);
        }
        this.meta = meta;
        this.processInstance = this.meta.process_instance;
        this.processEntity = this.meta.process_entity;
        this.canEdit = canEdit;
        this.canClaim = canClaim;
        this.canUnclaim = canUnclaim;
        this.canSubmit = canSubmit;
        this.canRelease = canRelease;

        // Update breadcrumb.
        this.setBreadcrumbs([
          this.processEntity.name,
          this.processInstance.definition.name,
          this.processInstanceForm.definition.name
        ]);
      },

      prevTabIndex() {
        let index = parseInt(this.activeIndex, 10);
        index = index !== '0' ? --index : index;
        // cast to string since el-tabs value prop requires a string to work
        this.activeIndex = `${index}`;
      },

      nextTabIndex() {
        let index = parseInt(this.activeIndex, 10);
        index = index !== this.tabsLength ? ++index : index;
        // cast to string since el-tabs value prop requires a string to work
        this.activeIndex = `${index}`;
      },

      onCancel() {
        this.isCancelling = true;
        this.confirmLoseChanges()
          .then(async () => {
            // we cannot reloadData here
            // since if we lost ownership the form will refresh
            // and we won't be claiming the form anymore
            this.refreshForm();
            this.isCancelling = false;
          }).catch(() => {
            this.isCancelling = false;
          });
      },

      refreshForm() {
        // keep a copy of current state
        // so that we can compare later on if the data has changed
        const formWasDirty = this.isFormDirty;
        // affect the newly fetched info locally
        this.formData = _.cloneDeep(this.processInstanceForm.form_data);
        this.setHasFormSectionGroupsRemoved(false);
        this.$nextTick(() => {
          // reset the fields states
          // so that we get a pristine form
          // but wait until dom is refreshed before resetting the fields state
          this.resetFieldsState();
          this.resetErrors();
        });

        if (formWasDirty) {
          this.notifyInfo({
            message: this.$t('components.notice.message.changes_discarded')
          });
        }
      },

      async reloadData() {
        const oldUpdatedDate = this.processInstanceForm.updated_at;
        await this.loadData();

        this.refreshForm();

        // check if there was new data that was fetched
        if (oldUpdatedDate !== this.processInstanceForm.updated_at) {
          this.notifyInfo({
            message: this.$t('components.notice.message.data_refreshed')
          });
        }
      },

      getCurrentEditorUsername() {
        return !_.isEmpty(this.processInstanceForm.current_editor) ? this.processInstanceForm.current_editor.username : null;
      },

      async onReleaseForm() {
        try {
          await ProcessFormsAPI.releaseForm(
            this.formId,
            this.getCurrentEditorUsername()
          );
          this.notifySuccess({
            message: this.$t('components.notice.message.form_released')
          });
        } catch (e) {
          // Exception handled by interceptor
          if (!e.response) {
            throw e;
          }
        } finally {
          this.reloadData();
        }
      },

      async onSave() {
        this.isSaving = true;
        try {
          const { data: process_instance_form } = await ProcessFormsAPI.saveForm(this.formId, this.formData);
          this.processInstanceForm = process_instance_form;
          // affect the newly fetched info locally
          // @note: this will make sure that newly created items like complex-data
          //        receives their assigned id by the backend.
          this.formData = _.cloneDeep(this.processInstanceForm.form_data);
          this.setHasFormSectionGroupsRemoved(false);
          // reset the fields states
          // so that we get a pristine form with the new values
          this.resetFieldsState();
          // make sure to not keep the current errors
          // so that validated childrens gets a pristine state as well
          this.resetErrors();
          this.isSaving = false;
          this.notifySuccess({
            message: this.$t('components.notice.message.changes_saved')
          });
        } catch (e) {
          if (e.response && e.response.status === HttpStatusCodes.FORBIDDEN) {
            await this.reloadData();
          } else {
            throw e;
          }
        } finally {
          this.isSaving = false;
        }
      },

      onSubmit() {
        this.confirmSubmitForm().then(() => {
          this.submit(this.handleSubmit);
        }).catch(() => false);
      },

      async handleSubmit() {
        try {
          await ProcessFormsAPI.submitForm(this.formId, this.formData);
          // since the backend unclaims the form after submitting it,
          // we need to keep track of when it was submitted,
          // so that we do not attempt to unclaim when leaving
          this.hasSubmittedForm = true;
          this.setHasFormSectionGroupsRemoved(false);
          this.notifySuccess({
            message: this.$t('components.notice.message.form_submitted')
          });
          this.$nextTick(() => {
            // reset the fields states
            // so that we get a pristine form
            // but wait until dom is refreshed before resetting the fields state
            this.resetFieldsState();
            this.goToParentPage();
          });
        } catch (e) {
          if (e.response && e.response.status === HttpStatusCodes.FORBIDDEN) {
            this.reloadData();
          } else {
            throw e;
          }
        }
      },

      async handleClaim() {
        try {
          await ProcessFormsAPI.claimForm(this.formId);
          this.reloadData();
        } catch (e) {
          // Exception handled by interceptor
          // we tried to claim a form that is already owned by someone else, -> refresh data
          if (e.response && e.response.status === HttpStatusCodes.FORBIDDEN) {
            this.reloadData();
          } else if (!e.response) {
            throw e;
          }
        }
      },

      /**
       * @param { Boolean } isNavigating - Whether or not to refresh data
       */
      async handleUnclaim(isNavigating = false) {
        try {
          if (this.isCurrentUser(this.processInstanceForm.current_editor)) {
            const { data: process_instance_form } = await ProcessFormsAPI.unclaimForm(this.formId);
            // merge the process instance form from the server with the info we already have
            // @note: When doing an unclaim, the server doesn't send back the formData,
            //        hence why we need to merge the info instead of replacing it entirely.
            this.processInstanceForm = Object.assign(this.processInstanceForm, process_instance_form);
          }
          if (!isNavigating && this.isFormDirty) {
            this.refreshForm();
          } else if (this.isFormDirty) {
            // this make sure to reset the flag "shouldConfirmBeforeLeaving"
            this.$nextTick(() => {
              this.resetFieldsState();
              this.resetErrors();
            });
          }
        } catch (e) {
          // Exception handled by interceptor
          // we tried to unclaim a form that we no longer own, -> refresh data
          // @note: if we are in a case that isNavigating is false, do not discard and refresh the data
          if (e.response && e.response.status === HttpStatusCodes.FORBIDDEN && !isNavigating) {
            this.reloadData();
          } else if (!e.response) {
            throw e;
          }
        }
      },

      setupTabPanes() {
        this.$nextTick(() => {
          // we need to wait until the dom is ready
          // so that we have access to the tabs panes
          if (this.$el.querySelectorAll('.el-tab-pane')) {
            // tabsLength needs to be zero-based
            this.tabsLength = this.$el.querySelectorAll('.el-tab-pane').length - 1;
          }
        });
      },

      onFormMounted() {
        this.setupTabPanes();
      },

      async beforeLogout() {
        if (this.shouldConfirmBeforeLeaving) {
          this.confirmLoseChanges().then(async () => {
            // load only the form info so that we know if we have to unclaim the form or not
            await this.handleUnclaim(true);
            this.setWaitForLogout(false);
          }).catch(() => false);
        } else {
          await this.handleUnclaim(true);
          this.setWaitForLogout(false);
        }
      }
    },

    beforeRouteLeave(to, from, next) {
      const leave = async () => {
        await this.handleUnclaim(true);
        next();
      };
      if (!this.hasSubmittedForm && this.shouldConfirmBeforeLeaving) {
        this.confirmLoseChanges().then(() => {
          leave();
        }).catch(() => false);
      } else if (!this.hasSubmittedForm) {
        leave();
      } else {
        next();
      }
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';
  @import '~@sass/abstracts/mixins/helpers';

  .process-form {
    height: calc(100% - 20px);
    display: flex;
    flex-direction: column;
    // override: no space at the very end of the page
    padding-bottom: 0;
    .el-tabs {
      position: absolute;
      // fixes the height of the tab list when there are less tabs than content
      display: flex;
      width: 100%;
      height: 100%;
      .el-tabs__content {
        overflow-y: auto;
        overflow-x: hidden;
        flex: 1;
      }
      .el-tabs__item.is-left {
        color: $--color-text-regular;
        text-align: left;
        padding: 0;
        &:hover span:before {
          background-color: $--color-primary-light-4;
        }
        &.is-active {
          color: $--color-primary;
        }
        &.is-active span:before {
          background-color: $--color-primary;
        }
        &.is-active span.is-error, &.is-active span.is-error:hover {
          color: $--color-danger;
          &:before {
            background-color: $--color-danger;
          }
        }
        span {
          transition: $--all-transition;
          text-overflow: ellipsis;
          white-space: nowrap;
          overflow: hidden;
          display: inline-block;
          vertical-align: middle;
          width: 100%;
          &:before {
            @include size($svg-icons-size-x-small);
            content: '';
            // Fixes IE11 not showing the before element
            display: inline-block;
            margin: 0 10px;
            margin-right: 5px;
            transition: $--all-transition;
            background-color: #ccc;
            border-radius: 50%;
          }
        }
        span.is-error {
          color: mix($--color-white, $--color-danger, 40%);
          &:after {
            background-color: mix($--color-white, $--color-danger, 40%);
          }
          &:hover {
            color: mix($--color-white, $--color-danger, 20%);
            &:after {
              background-color: mix($--color-white, $--color-danger, 20%);
            }
          }
        }
      }
    }

    header, footer {
      background: #656273;
      color: #fff;
      display: flex;
      align-items: center;
    }

    .form-row {
      flex: 1;
      .el-col, .form-wrap {
        height: 100%;
      }
    }
    .form-wrap {
      min-height: 400px;
      header, footer {
        padding: 0 30px;
        &:nth-child(2) {
          flex: 1;
        }
      }
      header {
        height: 40px !important;
        .el-switch__label {
          color: $--color-white;
        }
      }
      .el-main {
        overflow: hidden;
        background-color: $--color-white;
        border-right: $--border-base;
        border-radius: $--border-radius-base;
        // make sure that when the content is loading, that we display a fake form index so that layout is consistant
        &:before {
          content: '';
          position: absolute;
          left: 0;
          top: 0;
          height: 100%;
          background-color: $--background-color-base;
          border: $--border-base;
        }
        .has-other .el-form-item__content {
          display: flex;
          align-items: flex-start;
          // make sure childrens are spaced
          > * {
            // make the last child take all the remaining space
            &:last-child {
              flex: 1;
            }
          }
        }
        .el-tab-pane h3 {
          margin-top: 0;
          text-transform: uppercase;
        }
      }
      .process-form-footer footer:first-of-type,
      .el-tabs__header,
      .el-main:before {
        width: 20%;
        min-width: 250px;
        box-sizing: border-box;
      }

      .process-form-footer {
        z-index: $--index-top;
        display: flex;
        margin-top: 0;
        &-nav {
          flex: 1;
          text-align: center;
          button {
            // This fixes a bug in IE11 when the pseudo :after is overflowing the button and is not visible
            overflow: visible;
            &:first-child {
              margin-right: 20px;
              position: relative;
              &:after {
                content: '';
                position: absolute;
                top: 0;
                // -12 = 20px margin + 2px border
                right: -22px;
                width: 2px;
                height: 100%;
                background-color: $--color-white;
                // make sure the button is not clickable through the pseudo element
                pointer-events: none;
              }
            }
            &:last-child {
              margin-left: 20px;
            }
          }
        }
        footer:first-of-type {
          padding: 0 30px;
          border-right: 1px solid;
        }
      }
    }
  }
</style>
