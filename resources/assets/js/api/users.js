import axios from '@axios/interceptor';

import Config from '@/config';
import { helpers } from '@/helpers.js';

export default {
  create(user) {
    return axios.post('users', user);
  },

  update(user) {
    return axios.put(`users/${user.id}`, user);
  },

  delete(id) {
    return axios.delete(`users/${id}`);
  },

  get(id) {
    const request = !_.isUndefined(id) ? id : 'current';
    return axios.get(`users/${request}`);
  },

  getAll() {
    return axios.get('users');
  },

  async getCreateInfo() {
    const response = await axios.get('users/create');
    response.data.organizational_units = response.data.organizational_units.sort((a, b) => helpers.localeSort(a, b, 'name'));
    return Promise.resolve(response);
  },

  async getEditInfo(id) {
    const response = await axios.get(`users/${id}/edit`);
    response.data.organizational_units = response.data.organizational_units.sort((a, b) => helpers.localeSort(a, b, 'name'));
    return Promise.resolve(response);
  },

  logout() {
    // create a new instance of axios
    // so that we are not using the api defaults
    const request = axios.create({ baseURL: '/' + Config.DEFAULT_LANGUAGE });
    return request.post('logout');
  },

  search(name) {
    return axios.get('users/search', {
      showMainLoading: false,
      params: { name: name }
    });
  }
};
