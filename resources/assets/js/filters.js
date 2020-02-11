import _ from 'lodash';
import Vue from 'vue';
import { i18n } from '@/i18n/';
import Constants from '@/constants';

Vue.filter('LPANumFilter', function (id) {
  return `${id}`.padStart(Constants.LPA_NUM_PAD, 0);
});

Vue.filter('learningProductTypeSubTypeFilter', function (learningProduct) {
  return learningProduct.type.name + (learningProduct.sub_type ? ' / ' + learningProduct.sub_type.name : '');
});

Vue.filter('booleanFilter', function (value) {
  // make sure the context is correct
  const $t = i18n.t.bind(i18n);
  return value ? $t('base.actions.yes') : $t('base.actions.no');
});

Vue.filter('nullFilter', function (value) {
  // make sure the context is correct
  const $t = i18n.t.bind(i18n);
  return value === 'null' || _.isNil(value) ? $t('entities.general.none') : value;
});
