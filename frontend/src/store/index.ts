import { InjectionKey } from "vue";
import { createStore, Store, useStore as baseUseStore } from 'vuex'
import axios from "axios"
import createPersistedState from 'vuex-persistedstate'

export interface GlobalState {
  accessToken: string;
  refreshToken: string;
  user: string;
  authenticated: boolean;
}

export const StateKey: InjectionKey<Store<GlobalState>> = Symbol();

export const store = createStore({
  plugins: [createPersistedState({ storage: window.sessionStorage })],
  state: {
    accessToken: '',
    refreshToken: '',
    user: '',
    authenticated: false,
  },
  mutations: {
    setAccessToken(state, data) {
      state.accessToken = data;
    },
    setRefreshToken(state, data) {
      state.refreshToken = data;
    },
    setUser(state, data) {
      state.user = data;
      state.authenticated = true;
    },
    initData(state) {
      state.accessToken = '';
      state.refreshToken = '';
      state.user = '';
      state.authenticated = false;
    }
  },
  actions: {
    async login(ctx, credentials) {
      const res = await axios.post("/api/auth/login", {
        email: credentials.email,
        password: credentials.password
      })
      if (res) {
        console.log("ok")
        ctx.commit("setAccessToken", res.data.access_token)
        ctx.commit("setRefreshToken", res.data.access_token)
        ctx.commit("setUser", res.data.user.name)
      }
    },
    async register(ctx, credentials) {
      await axios.post("/api/auth/register", {
        name: credentials.name,
        email: credentials.email,
        password: credentials.password,
        password_confirmation: credentials.password_confirmation,
      })
    },
    async refresh(ctx) {
      const res = await axios.post('/api/auth/refresh')
      if (res) {
        ctx.commit("setAccessToken", res.data.access_token)
        ctx.commit("setRefreshToken", res.data.access_token)
      }
    },
    async me(ctx) {
      const res = await axios.get('/api/auth/me')
      if (res) {
        console.log(res.data)
      } else {
        console.log("no data")
      }
    }
  },
});

export function useStore() {
  return baseUseStore(StateKey);
}