import _ from 'lodash';
import { Trans } from '@/i18n/Translation';

import Config from '@/config.js';

export const helpers = {
  debounceAction: _.debounce(callback => {
    callback();
  }, Config.DEBOUNCE_WAIT_TIME),

  /**
   * Sorting method called when a column header is clicked to compare each rows and sort them accordingly
   * See: https://stackoverflow.com/questions/19993639/difference-in-performance-between-calling-localecompare-on-string-objects-and-c
   */
  localeSort(a, b, attr = null) {
    const collator = new Intl.Collator(Trans.currentLanguage);
    // if we are dealing with an object, we want to sort by a certain attribute
    if (_.isObject(a) && _.isObject(b) && !_.isNull(attr)) {
      const flag = a[attr] - b[attr];
      return _.isNaN(flag) ? collator.compare(a[attr], b[attr]) : flag;
    } else {
      const aName = String(a).toLowerCase();
      const bName = String(b).toLowerCase();

      const flag = aName - bName;
      return _.isNaN(flag) ? collator.compare(aName, bName) : flag;
    }
  },
};

const HelpersPlugin = {
  install(Vue, options) {
    Vue.helpers = helpers;
    Vue.prototype.$helpers = helpers;
  }
};

export default HelpersPlugin;
