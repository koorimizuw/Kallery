import { createRouter, createWebHistory, RouteRecordRaw } from "vue-router";
import axios from "axios";

const routes: Array<RouteRecordRaw> = [
  {
    path: "/",
    name: "Index",
    component: () => import("@/pages/Index.vue"),
  },
  {
    path: "/login",
    name: "Login",
    component: () => import("@/pages/Login.vue"),
  },
  {
    path: "/register",
    name: "Register",
    component: () => import("@/pages/Register.vue"),
  },
  {
    path: "/upload",
    name: "Upload",
    component: () => import("@/pages/Upload.vue"),
  },
  {
    path: "/detail/:id",
    name: "Detail",
    component: () => import("@/pages/Detail.vue"),
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

const secretPath = ["Upload"];
router.beforeEach(async (to, from, next) => {
  if (secretPath.includes(<string>to.name)) {
    await axios.get('/api/auth/me')
      .catch(e => {
        next({ name: "Login" });
      })
    next()
  } else next();
});

export default router;
