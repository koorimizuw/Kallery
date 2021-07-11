import axios from 'axios'
import { store } from '@/store'
import router from '@/router'

export default function axiosSetUp() {
  axios.defaults.baseURL = 'http://localhost:8000';
  axios.interceptors.request.use(
    function (config) {
      const token = store.state.accessToken;
      if (token) {
        config.headers.Authorization = `Bearer ${token}`; // AuthヘッダーにaccessTokenを添える
      }
      return config;
    },
    function (error) {
      return Promise.reject(error);
    }
  )
}

axios.interceptors.response.use(
  function (response) {
    return response;
  },
  async function (error) {
    const originalRequest = error.config;
    if (
      error.response.status == 401 &&
      originalRequest.url.includes('/api/auth/refresh') // 401はユーザーが認証されていないことを示す
    ) {
      store.commit('init');
      router.push({ name: 'login' });
      return Promise.reject(error);
    } else if (error.response.status == 401 && !originalRequest._retry) {
      originalRequest._retry = true;
      store.dispatch('refresh')
        .then(res => {
          if (originalRequest.url.includes('/api/auth/logout')) {
            originalRequest.data = JSON.stringify({ refresh: res.data.refresh });
            axios(originalRequest);
          } else axios(originalRequest);
        })
    }
    return Promise.reject(error);
  }
)