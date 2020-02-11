import Vue from 'vue';
import VueI18n from 'vue-i18n';
import CustomFormatter from '@/i18n/format';
import Config from '@/config';

Vue.use(VueI18n);
export const i18n = new VueI18n({
  formatter: new CustomFormatter(),
  locale: Config.DEFAULT_LANGUAGE, // set locale
  fallbackLocale: Config.FALLBACK_LANGUAGE
});
