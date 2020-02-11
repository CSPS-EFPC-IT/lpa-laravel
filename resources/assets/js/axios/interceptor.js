import '@babel/polyfill';

import Vue from 'vue';
import axios from 'axios';
import axiosDefaults from './defaults';
import HttpStatusCodes from './http-status-codes';
import router from '@/router';
import store from '@/store/';
import Config from '@/config';
import Notify from '@mixins/notify';

import { Trans } from '@/i18n/Translation';
import { i18n } from '@/i18n/';

Object.assign(axios.defaults, axiosDefaults);

// General Request Processing
axios.interceptors.request.use(
  config => {
    if (config.showMainLoading) {
      // Show Main loading animation before sending any request.
      store.commit('showMainLoading');
    }
    return config;
  },
  error => {
    if (error.request.config.showMainLoading) {
      // Hide Main loading animation upon request processing error.
      store.dispatch('hideMainLoading');
    }
    Vue.$log.debug(error);
    return Promise.reject(error);
  }
);

// General Response Processing
axios.interceptors.response.use(
  response => {
    if (response.config.showMainLoading) {
      // Hide Main loading animation after receiving a response.
      store.dispatch('hideMainLoading');
    }
    return response.data;
  },
  error => {
    // make sure the context is correct
    const $t = i18n.t.bind(i18n);
    const response = error.response;

    if (!response || (response && response.config.showMainLoading)) {
      // Hide Main loading animation upon response processing error.
      store.dispatch('hideMainLoading');
    }

    // if the response is undefined, we likely got a timeout
    if (!response) {
      Notify.notifyError({
        message: $t('errors.timeout')
      });
      Vue.$log.error(`[axios][interceptor]: ${$t('errors.timeout')}`);
      return Promise.reject(error);
    }

    const status = response.status;
    const errorResponse = response.data;

    if (status === HttpStatusCodes.UNAUTHORIZED) {
      // not logged in, redirect to login
      Vue.prototype.$alert(
        $t('auth.session_expired'),
        $t('components.notice.type.info'),
        {
          type: 'info',
          showClose: false,
          confirmButtonText: $t('base.actions.ok'),
          callback: action => {
            // we need to change the location manually since the backend handles the login page
            window.location.href = `/${Trans.currentLanguage}/login`;
          }
        });
    } else if (status === HttpStatusCodes.NOT_FOUND) {
      const newPath = router.history.pending ? router.history.pending.path : router.history.current.path;
      const newPathSplit = newPath.split('/');
      // do not push again if we already pushed to not-found
      // this is only viable when there are multiple requests that throw 404s
      if (newPathSplit[newPathSplit.length - 1] !== 'not-found') {
        router.replace({ name: 'not-found', params: { '0': `${newPath}/not-found` } });
      }
    } else if (status === HttpStatusCodes.FORBIDDEN) {
      Notify.notifyError({
        title: errorResponse.type,
        message: errorResponse.message || $t('errors.forbidden')
      });
    } else if (status === HttpStatusCodes.SERVER_ERROR) {
      // internal error
      Notify.notifyError({
        message: $t('errors.general')
      });
    }

    // Log error to the console.
    if (errorResponse) {
      let errorMessage = '';
      errorMessage += errorResponse.message || '';
      errorMessage += errorResponse.debug || '';
      if (errorMessage) {
        Vue.$log.error(`[axios][interceptor]: ${errorMessage}`);
      }
    }

    return Promise.reject(error);
  }
);

window.axios = axios;

export default axios;
