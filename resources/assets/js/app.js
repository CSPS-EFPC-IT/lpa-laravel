import '@babel/polyfill';

import Vue from 'vue';

import VeeValidate from 'vee-validate';
import veeLocaleFR from 'vee-validate/dist/locale/fr';

import ElementUI from 'element-ui';
import VueTheMask from 'vue-the-mask';

import DataTables from 'vue-data-tables'

import { sync } from 'vuex-router-sync';
import router from '@/router';
import store from '@/store/';

import Logger from '@/plugins/logger';
import Helpers from '@/helpers';
import '@/filters';
import '@/directives';
import '@/vee-validate-rules';
import Notify from '@mixins/notify';
import Confirm from '@mixins/confirm';

import { i18n } from '@/i18n/';
import { Trans } from '@/i18n/Translation';

import App from '@/app.vue';
import Config from '@/config';

Vue.use(VueTheMask);

Vue.use(DataTables);

Vue.use(Helpers);

sync(store, router);

Vue.use(Logger, {
  logLevel          : Config.DEBUG ? 'debug' : 'error',
  separator         : '',
  stringifyArguments: false,
  showLogLevel      : false,
  showMethodName    : true,
  showConsoleColors : true
});

Vue.mixin({ methods: Notify });
Vue.mixin({ methods: Confirm });

// used to be able to shorten urls in components
// so that locale is entirely managed by the module Translation
Vue.prototype.$i18nRoute = Trans.i18nRoute.bind(Trans);

const init = async () => {
  await Trans.loadLanguages();

  // Override the default placeholder for the components that has one
  ElementUI.Select.props.placeholder.default = '';
  ElementUI.Cascader.props.placeholder.default = '';
  Vue.use(ElementUI, {
    i18n: (key, value) => i18n.t(key, value)
  });

  Vue.use(VeeValidate, {
    events: 'input',
    strict: false,
    locale: Trans.currentLanguage,
    // modify the defaults for errors and fields
    // since ElementUI already has these properties injected
    errorBagName: 'verrors',
    fieldsBagName: 'vfields',
    dictionary: {
      fr: _.merge(
        veeLocaleFR,
        {
          // Override VeeValidate French translations.
          messages: {
            email: field => `Le champ ${field} doit être une adresse courriel valide.`
          }
        }
      )
    },
    // Gives us the ability to inject validation in child components
    // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
    inject: false
  });

  new Vue({
    el: 'app',
    router,
    store,
    i18n,
    template: '<app/>',
    components: { App }
  });
};

init();
