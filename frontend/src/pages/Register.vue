<template>
  <div class="container">
    <h1 class="title">Kallery</h1>
    <a-form :model="credentials">
      <a-form-item>
        <a-input v-model:value="credentials.name" placeholder="Name" />
      </a-form-item>
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
        <a-input
          v-model:value="credentials.password_confirmation"
          type="password"
          placeholder="Password Confirm"
        />
      </a-form-item>
      <a-form-item>
        <a-button
          class="submit"
          type="primary"
          html-type="submit"
          @click="login"
          :disabled="
            credentials.email === '' ||
            credentials.password === '' ||
            credentials.password_confirmation === ''
          "
        >
          登録
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
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
});

const store = useStore();
const login = async () => {
  if (credentials.password !== credentials.password_confirmation) {
    message.success("パスワードが一致していません。");
    return;
  }
  await store
    .dispatch("register", {
      name: credentials.name,
      email: credentials.email,
      password: credentials.password,
      password_confirmation: credentials.password_confirmation,
    })
    .then((res) => {
      message.success("登録に成功しました。");
      router.push("/login");
      console.log("success");
    })
    .catch((e) => {
      message.error("登録に失敗しました。");
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