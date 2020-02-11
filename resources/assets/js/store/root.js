import Constants from '@/constants.js';

export const state = {
  filteredDataTableList: [],
  waitForLogout: false,
  shouldConfirmBeforeLeaving: false,
  isAppLoading: false,
  isMainLoading: false,
  mainLoadingCount: 0,
  breadcrumbs: []
};

export const getters = {};

export const actions = {
  hideMainLoading({ commit }, context) {
    setTimeout(() => {
      if (state.mainLoadingCount === 1) {
        commit('toggleMainLoading', false);
      }
      commit('setMainLoadingCount', state.mainLoadingCount - 1);
    }, Constants.DELAY_HIDE_MAIN_LOADING);
  }
};

export const mutations = {
  // Loading handlers
  showAppLoading(state, context) {
    this.commit('toggleAppLoading', true);
  },

  hideAppLoading(state, context) {
    this.commit('toggleAppLoading', false);
  },

  // This is how it goes when we show a main loading.
  // For example:
  // user toggles language, showMainLoadingCount = 1
  // sends an event that language is toggling,
  // components show main loading and fetch data, showMainLoadingCount = 2
  // route is transitioned, language is set and hideMainLoading is called, showMainLoadingCount = 1
  // component fetch is done, showMainLoadingCount = 0 => hideMainLoading
  showMainLoading(state, context) {
    // grab the actual mainLoadingCount value and increase it
    if (state.mainLoadingCount === 0) {
      this.commit('toggleMainLoading', true);
    }
    this.commit('setMainLoadingCount', state.mainLoadingCount + 1);
  },

  toggleAppLoading(state, isShown) {
    let spinner = document.querySelectorAll('.loading-spinner')[0];
    if (spinner) {
      if (!isShown) {
        spinner.classList.add('fade-out');
        spinner.classList.remove('fade-in');
      } else {
        spinner.classList.add('fade-in');
        spinner.classList.remove('fade-out');
      }
    }
    state.isAppLoading = isShown;
  },

  toggleMainLoading(state, isShown) {
    state.isMainLoading = isShown;
  },

  setMainLoadingCount(state, count) {
    state.mainLoadingCount = count;
  },

  setShouldConfirmBeforeLeaving(state, val) {
    state.shouldConfirmBeforeLeaving = val;
  },

  setWaitForLogout(state, waitForLogout) {
    state.waitForLogout = waitForLogout;
  },

  setBreadcrumbs(state, breadcrumbs) {
    state.breadcrumbs = breadcrumbs;
  },

  addFilteredDataTable(state, dataTableName) {
    if (state.filteredDataTableList.indexOf(dataTableName) === -1) {
      state.filteredDataTableList.push(dataTableName);
    }
  },

  deleteFilteredDataTable(state, dataTableName) {
    const index = state.filteredDataTableList.indexOf(dataTableName);
    if (index !== -1) {
      state.filteredDataTableList.splice(index, 1);
    }
  }
};
