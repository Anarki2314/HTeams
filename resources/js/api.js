import axios from "axios";
import router from "./router.js";

const API_URL = import.meta.env.VITE_API_URL;
const api = axios.create({
    withCredentials: false,
    baseURL: API_URL
});

api.interceptors.request.use((config) => {
    const token = localStorage.getItem("token");

    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
      }
      
    config.headers.Accept = "application/json";
    return config;
});

api.interceptors.response.use(
    async (response) => {
      return Promise.resolve(response);
    },
    async (err) => {
      if (err.response.status == 404) {
        return router.push('/404')
      }
      return Promise.reject(err.response);
    }
  );
  export default api;
  