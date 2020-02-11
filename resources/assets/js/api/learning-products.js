import axios from '@axios/interceptor';

import { helpers } from '@/helpers.js';

export default {
  create(learningProduct) {
    return axios.post('learning-products', learningProduct);
  },

  update(learningProduct) {
    return axios.put(`learning-products/${learningProduct.id}`, learningProduct);
  },

  delete(id) {
    return axios.delete(`learning-products/${id}`);
  },

  get(id, context = false) {
    return axios.get(`learning-products/${id}`, {
      params: { context }
    });
  },

  getAll() {
    return axios.get('learning-products');
  },

  getProjectLearningProducts(projectId) {
    return axios.get(`learning-products?project_id=${projectId}`);
  },

  async getCreateInfo() {
    const response = await axios.get('learning-products/create');
    response.data.organizational_units = response.data.organizational_units.sort((a, b) => helpers.localeSort(a, b, 'name'));
    return Promise.resolve(response);
  },

  async getEditInfo(id) {
    const response = await axios.get(`learning-products/${id}/edit`);
    response.data.organizational_units = response.data.organizational_units.sort((a, b) => helpers.localeSort(a, b, 'name'));
    return Promise.resolve(response);
  },

  getCodes(code) {
    return axios.get(`learning-product-codes`);
  },

  can(id, actions = []) {
    return axios.get(`authorizations/learning-product/${id}`, {
      params: { actions }
    });
  },

  canCreate() {
    return axios.get('authorizations/learning-product/create');
  },

  canDelete(id) {
    return axios.get(`authorizations/learning-product/${id}/delete`);
  },

  canEdit(id) {
    return axios.get(`authorizations/learning-product/${id}/edit`);
  },

  canStartProcess(id, processDefinitionNameKey) {
    return axios.get(`authorizations/learning-product/${id}/start-process/${processDefinitionNameKey}`);
  }
};
