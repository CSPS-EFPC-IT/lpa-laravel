import UserAPI from '@api/users.js';

export default {
  namespaced: true,

  state: {
    // logged-in user
    current: {}
  },

  getters: {
    hasRole(state) {
      return roleLookup => {
        return state.current.roles && !!state.current.roles.find(role => role.name_key === roleLookup);
      };
    },

    isCurrentUser(state) {
      return user => {
        return !!(user && state.current.username === user.username);
      };
    }
  },

  actions: {
    async load({ commit }) {
      const response = await UserAPI.get();
      commit('setCurrentUser', response.data);
    }
  },

  mutations: {
    setCurrentUser(state, user) {
      state.current = user;
    }
  }
};
