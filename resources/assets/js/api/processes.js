import axios from '@axios/interceptor';

export default {
  start(nameKey, entityId, entityType, sendEmailNotif) {
    return axios.post(`process-definitions/${nameKey}?entity_id=${entityId}&entity_type=${entityType}&send_notifications=${sendEmailNotif}`);
  },

  getDefinitions(entityType) {
    return axios.get(`process-definitions/${entityType}`);
  },

  getInstance(entityId, context = false) {
    return axios.get(`process-instances/${entityId}`, {
      params: { context }
    });
  },

  getHistory(entityType, entityId) {
    return axios.get(`process-instances?entity_type=${entityType}&entity_id=${entityId}`);
  },

  cancelInstance(id) {
    return axios.put(`process-instances/${id}/cancel`);
  },

  canCancel(id) {
    return axios.get(`authorizations/process-instance/${id}/cancel`);
  }
};
