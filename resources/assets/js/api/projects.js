import axios from '@axios/interceptor';

import { helpers } from '@/helpers.js';

export default {
  create(project) {
    return axios.post('projects', project);
  },

  update(project) {
    return axios.put(`projects/${project.id}`, project);
  },

  delete(id) {
    return axios.delete(`projects/${id}`);
  },

  get(id, context = false) {
    return axios.get(`projects/${id}`, {
      params: { context }
    });
  },

  getAll() {
    return axios.get(`projects`);
  },

  async getCreateInfo() {
    const response = await axios.get('projects/create');
    response.data.organizational_units = response.data.organizational_units.sort((a, b) => helpers.localeSort(a, b, 'name'));
    return Promise.resolve(response);
  },

  async getEditInfo(id) {
    const response = await axios.get(`projects/${id}/edit`);
    response.data.organizational_units = response.data.organizational_units.sort((a, b) => helpers.localeSort(a, b, 'name'));
    return Promise.resolve(response);
  },

  can(id, actions = []) {
    return axios.get(`authorizations/project/${id}`, {
      params: { actions }
    });
  },

  canCreate() {
    return axios.get('authorizations/project/create');
  },

  canEdit(id) {
    return axios.get(`authorizations/project/${id}/edit`);
  },

  canDelete(id) {
    return axios.get(`authorizations/project/${id}/delete`);
  },

  canStartProcess(id, processDefinitionNameKey) {
    return axios.get(`authorizations/project/${id}/start-process/${processDefinitionNameKey}`);
  }
};
