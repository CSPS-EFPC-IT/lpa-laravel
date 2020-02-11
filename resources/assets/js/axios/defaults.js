import Vue from 'vue';
import Config from '@/config';
import Notify from '@mixins/notify';
import Qs from 'qs';

import { i18n } from '@/i18n/';

const defaultLang = Config.DEFAULT_LANGUAGE;

const defaults = {
  baseURL: `/${defaultLang}/api`,
  // buff to 20s to be sure that when on slow connections we are still capable of loading data
  timeout: 20000,
  headers: {
    common: {
      'Accept-Language': defaultLang,
      'X-Requested-With': 'XMLHttpRequest',
      'Accept': 'application/json'
    }
  },
  paramsSerializer: function (params) {
    return Qs.stringify(params, {arrayFormat: 'brackets'})
  },
  // Show main loading by default on every requests.
  showMainLoading: true
};

// Register the CSRF Token as a common header with Axios so that
// all outgoing HTTP requests automatically have it attached. This is just
// a simple convenience so we don't have to attach every token manually.
const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
  defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
  // make sure the context is correct
  const $t = i18n.t.bind(i18n);
  Notify.notifyError({
    message: $t('errors.csrf_not_found')
  });
}

export default defaults;
