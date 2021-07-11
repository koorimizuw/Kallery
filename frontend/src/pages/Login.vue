<template>
  <div class="container">
    <h1 class="title">Kallery</h1>
    <a-form :model="credentials">
      <a-form-item>
        <a-input v-model:value="credentials.email" placeholder="Email" />
      </a-form-item>
      <a-form-item>
        <a-input
          v-model:value="credentials.password"
          type="password"
          placeholder="Password"
        />
      </a-form-item>
      <a-form-item>
        <a-button
          class="submit"
          type="primary"
          html-type="submit"
          @click="login"
          :disabled="credentials.email === '' || credentials.password === ''"
        >
          Log in
        </a-button>
      </a-form-item>
    </a-form>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive } from "vue";
import { useStore } from "vuex";
import router from "../router";
import { message } from "ant-design-vue";

const credentials = reactive({
  email: "",
  password: "",
});

const store = useStore();
const login = async () => {
  await store
    .dispatch("login", {
      email: credentials.email,
      password: credentials.password,
    })
    .then((res) => {
      message.success("ログインに成功しました。");
      router.push("/");
      console.log("success");
    })
    .catch((e) => {
      message.error("ログインに失敗しました。");
    });
};
</script>

<style lang="scss" scoped>
.title {
  text-align: center;
  font-size: 3em;
}
.container {
  max-width: 480px;
  margin: auto;
  margin-top: 100px;
}

.submit {
  width: 100%;
}
</style>