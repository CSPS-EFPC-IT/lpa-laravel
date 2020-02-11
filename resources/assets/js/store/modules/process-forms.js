export default {
  namespaced: true,

  state: {
    hasFormSectionGroupsRemoved: false
  },

  getters: {},

  actions: {},

  mutations: {
    setHasFormSectionGroupsRemoved(state, hasFormSectionGroupsRemoved) {
      state.hasFormSectionGroupsRemoved = hasFormSectionGroupsRemoved;
    }
  }
};
