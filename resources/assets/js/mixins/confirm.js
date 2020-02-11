import Vue from 'vue';
import { i18n } from '@/i18n/';

export default {
  _confirm({
    title,
    message = '',
    action = 'delete',
    beforeClose = null,
    confirmButtonClass = '',
    thenCallback,
    catchCallback
  }) {
    // make sure the context is correct
    const $t = i18n.t.bind(i18n);
    const type = 'warning';
    title = title || $t(`components.notice.type.${type}`);
    Vue.prototype
      .$msgbox({
        title,
        message,
        beforeClose,
        type,
        showCancelButton: true,
        confirmButtonText: $t(`base.actions.${action}`),
        confirmButtonClass,
        cancelButtonText: this.$t('base.actions.cancel'),
        dangerouslyUseHTMLString: true
      })
      .then(thenCallback)
      .catch(catchCallback);
  },

  confirm({ title, message, action = 'create', confirmButtonClass = '' }) {
    return new Promise((resolve, reject) => {
      this._confirm({
        title,
        message,
        action,
        confirmButtonClass,
        thenCallback: resolve,
        catchCallback: reject
      });
    });
  },

  confirmStart({ title, message }) {
    return new Promise((resolve, reject) => {
      this._confirm({
        title,
        message,
        action: 'start',
        thenCallback: resolve,
        catchCallback: reject
      });
    });
  },

  confirmDelete({ title, message, beforeClose }) {
    return new Promise((resolve, reject) => {
      this._confirm({
        title,
        message,
        action: 'delete',
        confirmButtonClass: 'el-button--danger',
        beforeClose,
        thenCallback: resolve,
        catchCallback: reject
      });
    });
  },

  confirmLoseChanges() {
    return new Promise((resolve, reject) => {
      this._confirm({
        title: this.$t('components.notice.title.pending_changes'),
        message: this.$t('components.notice.message.pending_changes'),
        action: 'discard',
        confirmButtonClass: 'el-button--danger',
        thenCallback: resolve,
        catchCallback: reject
      });
    });
  },

  confirmSubmitForm() {
    return new Promise((resolve, reject) => {
      this._confirm({
        title: this.$t('components.notice.title.submit_form'),
        message: this.$t('components.notice.message.submit_form'),
        action: 'submit',
        confirmButtonClass: 'el-button--warning',
        thenCallback: resolve,
        catchCallback: reject
      });
    });
  },

  confirmReleaseForm() {
    return new Promise((resolve, reject) => {
      this._confirm({
        title: this.$t('components.notice.title.release_form'),
        message: this.$t('components.notice.message.release_form'),
        action: 'release',
        confirmButtonClass: 'el-button--warning',
        thenCallback: resolve,
        catchCallback: reject
      });
    });
  },

  confirmCancelProcess() {
    return new Promise((resolve, reject) => {
      this._confirm({
        title: this.$t('components.notice.title.cancel_process'),
        message: this.$t('components.notice.message.cancel_process'),
        action: 'proceed',
        confirmButtonClass: 'el-button--warning',
        thenCallback: resolve,
        catchCallback: reject
      });
    });
  }
};
