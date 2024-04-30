import axios from "axios";

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
      return Promise.reject(err.response);
    }
  );
  export default api;
  