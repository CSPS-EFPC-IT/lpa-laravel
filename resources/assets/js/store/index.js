import '@babel/polyfill';

import Vue from 'vue';
import Vuex from 'vuex';

// Root Scope of Vuex
import { state, getters, actions, mutations } from './root';

import processForms from '@/store/modules/process-forms';
import users from '@/store/modules/users';

Vue.use(Vuex);

window.store = new Vuex.Store({
  state,
  getters,
  actions,
  mutations,
  modules: {
    processForms,
    users
  },
  // https://vuex.vuejs.org/en/strict.html
  strict: process.env.NODE_ENV !== 'production'
});

export default window.store;
