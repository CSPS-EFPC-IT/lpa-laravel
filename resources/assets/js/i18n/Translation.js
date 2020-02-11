// Taken and inspired by:
// https://github.com/dobromir-hristov/vue-i18n-starter/tree/master/src
import axios from 'axios';
import Config from '@/config';
import { i18n } from '@/i18n/';
import { Validator } from 'vee-validate';
import elementUILocaleEN from 'element-ui/lib/locale/lang/en';
import elementUILocaleFR from 'element-ui/lib/locale/lang/fr';

const Trans = {
  get defaultLanguage() {
    return Config.DEFAULT_LANGUAGE;
  },
  get supportedLanguages() {
    return Config.SUPPORTED_LANGUAGES;
  },
  get currentLanguage() {
    return i18n.locale;
  },
  set currentLanguage(lang) {
    i18n.locale = lang;
  },
  /**
   * Gets the first supported language that matches the user's
   * @return {String}
   */
  getUserSupportedLang() {
    const userPreferredLang = Trans.getUserLang();

    // Check if user preferred browser lang is supported
    if (Trans.isLangSupported(userPreferredLang.lang)) {
      return userPreferredLang.lang;
    }
    // Check if user preferred lang without the ISO is supported
    if (Trans.isLangSupported(userPreferredLang.langNoISO)) {
      return userPreferredLang.langNoISO;
    }
    return Trans.defaultLanguage;
  },
  /**
   * Returns the users preferred language
   */
  getUserLang() {
    const lang = window.navigator.language || window.navigator.userLanguage || Trans.defaultLanguage;
    return {
      lang: lang,
      langNoISO: lang.split('-')[0]
    };
  },
  /**
   * Sets the language to various services(axios, the html tag etc)
   * @param {String} lang
   * @return {String} lang
   */
  setI18nLanguageInServices(lang) {
    Trans.currentLanguage = lang;
    axios.defaults.baseURL = `/${lang}/api`;
    axios.defaults.headers.common['Accept-Language'] = lang;
    document.querySelector('html').setAttribute('lang', lang);
    Validator.localize(lang);
    return lang;
  },
  /**
   * Async loads translation file
   * @return {Promise<*>|*}
   */
  async loadLanguages() {
    try {
      const response = await axios.get('locales');

      // merge elementUI's messages with ours
      const enMessages = Object.assign(response.data.en, elementUILocaleEN);
      const frMessages = Object.assign(response.data.fr, elementUILocaleFR);
      i18n.setLocaleMessage('en', enMessages);
      i18n.setLocaleMessage('fr', frMessages);

      // set default language
      Trans.setI18nLanguageInServices(Trans.defaultLanguage);
    } catch (e) {
      alert('Languages could not be loaded. Please contact your administrator.');
      Vue.$log.error(`[app][loadLanguages]: ${e}`);
    }
  },
  /**
   * Checks if a lang is supported
   * @param {String} lang
   * @return {boolean}
   */
  isLangSupported(lang) {
    return Trans.supportedLanguages.includes(lang);
  },
  /**
   * Returns a new route object that has the current language already defined
   * To be used on pages and components, outside of the main \ route, like on Headers and Footers.
   * @example <router-link :to="$i18nRoute({ name: 'someRoute'})">Click Me </router-link>
   * @param {Object} to - route object to construct
   */
  i18nRoute(to) {
    return {
      ...to,
      params: { lang: this.currentLanguage, ...to.params }
    };
  }
}

export { Trans };
