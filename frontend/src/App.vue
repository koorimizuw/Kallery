<template>
  <header class="appbar">
    <h2 class="title" @click="$router.push(`/`)">Kallery</h2>
    <div class="right">
      <div v-if="store.state.authenticated" class="user">
        ようこそ、{{ store.state.user }}
      </div>
      <div v-else style="display: inline-block">
        <a-button size="large" @click="$router.push(`/login`)">
          ログイン
        </a-button>
      </div>
      <a-button
        class="btn"
        type="primary"
        size="large"
        @click="$router.push(`/upload`)"
      >
        アップロード
      </a-button>
    </div>
  </header>
  <router-view />
</template>

<script setup lang="ts">
import { ref } from "vue";
import { useStore } from "vuex";

const store = useStore();
const authenticated = ref(store.state.authenticated);
const user = ref(store.state.user);
</script>

<style lang="scss" scoped>
header {
  box-shadow: 0 2px 8px #f0f1f2;
}
.appbar {
  height: 60px;
  display: flex;
  justify-content: space-between;
}

.title {
  line-height: 60px;
  padding: 0 40px;
  font-size: 2.5em;
  cursor: pointer;
}

.user {
  display: inline-block;
  line-height: 60px;
  font-size: 1.2em;
}

.btn {
  display: inline-block;
  margin: 10px 20px;
}
</style>