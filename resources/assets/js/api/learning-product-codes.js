import axios from '@axios/interceptor';
import _ from 'lodash';

export default {
  create(data) {
    return axios.post('learning-product-codes', data);
  },

  update(data) {
    return axios.put(`learning-product-codes/${data.id}`, data);
  },

  delete(id) {
    return axios.delete(`learning-product-codes/${id}`);
  },

  async getAll() {
    const response = await axios.get('learning-product-codes');
    response.data = _.sortBy(response.data, 'code');
    return Promise.resolve(response);
  },

  get(id) {
    return axios.get(`learning-product-codes/${id}`);
  },

  canDelete(id) {
    return axios.get(`authorizations/learning-product-code/${id}/delete`);
  },

  async search(params) {
    const response = await axios.get('search/learning-product-code', {
      params,
      showMainLoading: false
    });
    response.data = _.sortBy(response.data, 'code');
    return Promise.resolve(response);
  }
};
