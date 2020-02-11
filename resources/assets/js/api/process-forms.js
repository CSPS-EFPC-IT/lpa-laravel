import axios from '@axios/interceptor';

export default {
  getInstanceForm(id, context = false) {
    return axios.get(`process-instance-forms/${id}`, {
      params: { context }
    });
  },

  claimForm(id) {
    return axios.put(`process-instance-forms/${id}/claim`);
  },

  unclaimForm(id) {
    return axios.put(`process-instance-forms/${id}/unclaim`);
  },

  saveForm(id, form) {
    return axios.put(`process-instance-forms/${id}/edit`, form);
  },

  releaseForm(id, username) {
    return axios.put(`process-instance-forms/${id}/release?editor=${username}`);
  },

  submitForm(id, form) {
    return axios.put(`process-instance-forms/${id}/submit`, form);
  },

  can(id, actions = []) {
    return axios.get(`authorizations/process-instance-form/${id}`, {
      params: { actions }
    });
  },

  canEditForm(id) {
    return axios.get(`authorizations/process-instance-form/${id}/edit`);
  },

  canClaimForm(id) {
    return axios.get(`authorizations/process-instance-form/${id}/claim`);
  },

  canUnclaimForm(id) {
    return axios.get(`authorizations/process-instance-form/${id}/unclaim`);
  },

  canSubmitForm(id) {
    return axios.get(`authorizations/process-instance-form/${id}/submit`);
  },

  canReleaseForm(id) {
    return axios.get(`authorizations/process-instance-form/${id}/release`);
  }
};
