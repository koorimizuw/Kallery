import { createApp } from 'vue'
import Antd from 'ant-design-vue';
import App from './App.vue'
import router from "./router";
import { store } from "./store";
import axiosSetUp from './plugins/axios'
import 'ant-design-vue/dist/antd.css';

axiosSetUp()

createApp(App)
  .use(Antd)
  .use(router)
  .use(store)
  .mount('#app')
