import '@babel/polyfill';

import Vue from 'vue';
import axios from 'axios';

import VeeValidate from 'vee-validate';
import veeLocaleFR from 'vee-validate/dist/locale/fr';

import ElementUI from 'element-ui';
import elementUILocaleEN from 'element-ui/lib/locale/lang/en';
import elementUILocaleFR from 'element-ui/lib/locale/lang/fr';

import Logger from '@/plugins/logger';
import Notify from '@mixins/notify';

import Config from '@/config';

import FormError from '@components/forms/error';
import FormUtils from '@mixins/form/utils';

const elementUILocale = Config.DEFAULT_LANGUAGE === 'en' ? elementUILocaleEN : elementUILocaleFR;
Vue.use(ElementUI, { locale: elementUILocale });

Vue.mixin({ methods: Notify });

Vue.use(Logger, {
  logLevel          : Config.DEBUG ? 'debug' : 'error',
  separator         : '',
  stringifyArguments: false,
  showLogLevel      : false,
  showMethodName    : true,
  showConsoleColors : true
});

Vue.use(VeeValidate, {
  events: 'input',
  strict: false,
  locale: Config.DEFAULT_LANGUAGE,
  // modify the defaults for errors and fields
  // since ElementUI already has these properties injected
  errorBagName: 'verrors',
  fieldsBagName: 'vfields',
  dictionary: {
    fr: veeLocaleFR
  },
  // Gives us the ability to inject validation in child components
  // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
  inject: false
});

new Vue({
  el: '#app',

  mixins: [ FormUtils ],

  components: { FormError },

  data() {
    return {
      username: '',
      password: '',
      remember: false,
      isPasswordVisible: false,
      toggledLocale: Config.DEFAULT_LANGUAGE === 'en' ? 'fr' : 'en',
      acceptToUseIE: false
    };
  },

  computed: {
    showWarning() {
      // User has accepted to use IE, hide warning.
      if (!Config.ENABLE_IE_DETECTION || this.acceptToUseIE) {
        return false;
      }
      // Show warning if browser is IE or Edge.
      return this.detectIE();
    }
  },

  methods: {
    detectIE() {
      const ua = window.navigator.userAgent;

      const msie = ua.indexOf('MSIE ');
      if (msie > 0) {
        // IE 10 or older => return version number
        return parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10);
      }

      const trident = ua.indexOf('Trident/');
      if (trident > 0) {
        // IE 11 => return version number
        const rv = ua.indexOf('rv:');
        return parseInt(ua.substring(rv + 3, ua.indexOf('.', rv)), 10);
      }

      const edge = ua.indexOf('Edge/');
      if (edge > 0) {
        // Edge (IE 12+) => return version number
        return parseInt(ua.substring(edge + 5, ua.indexOf('.', edge)), 10);
      }

      // Any other browsers...
      return false;
    },

    // manage basic validation
    onSubmit() {
      this.submit(this.login);
    },

    // attempt to login if basic validation succeed
    async login() {
      const request = axios.create({baseURL: '/' + Config.DEFAULT_LANGUAGE});
      const response = await request.post('login', {
        username: this.username,
        password: this.password,
        remember: this.remember
      });
      // all good, submit form manually
      window.location = response.data.redirectURL;
    },

    manageBackendErrors(errors) {
      for (let fieldName in errors) {
        // gives us an error in the ErrorBag-item format
        const fieldErrors = errors[fieldName].map(msg => {
          return { field: fieldName, msg };
        });
        // get me the first field that has this name.
        const field = this.$validator.fields.find({ name: fieldName });

        // Make sure that field returned by the backend can be mapped to an existing field in the form.
        if (!field) {
          console.warn('Cannot map error field [' + fieldName + ']');
          continue;
        }

        // then for that field, make a correlation on the id of the error and the field
        // and add the error to our Error Bag
        fieldErrors.forEach(error => {
          // we can access the id using field.id
          error.id = field.id;
          this.verrors.add(error);
        });

        // finally update the flags, which also updates the opposite flags
        // (i.e: valid-invalid, dirty-pristine)
        field.setFlags({
          valid: !! fieldErrors.length
        });
      }

      this.focusOnError();
    }
  },

  async mounted() {
    const spinner = document.querySelectorAll('.loading-spinner')[0];
    if (spinner) {
      spinner.classList.add('fade-out');
    }
  }
});
