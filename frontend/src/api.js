import axios from 'axios';

const apiClient = axios.create({
  baseURL: 'https://todo-app.ddev.site/api/',
  withCredentials: true
});

export default apiClient;