  <template>
  <div class="container">
    <div class="progress">
      <a-steps :current="current" size="small">
        <a-step title="画像アップロード" />
        <a-step title="コメントを書く" />
        <a-step title="確認" />
      </a-steps>
    </div>
    <div v-if="current == 0">
      <a-upload-dragger
        v-model:fileList="fileList"
        name="file"
        :customRequest="() => {}"
        @change="handleChange"
      >
        <p class="ant-upload-text">画像をこちらにドラッグしてください。</p>
        <p class="ant-upload-hint">
          こちらの拡張子のファイルがアップロードできます：jpg,jpeg,png,gif,svg
        </p>
      </a-upload-dragger>
    </div>
    <div v-if="current == 1">
      <a-form :model="form">
        <a-form-item label="タイトル" name="title">
          <a-input v-model:value="form.title" />
        </a-form-item>
        <a-form-item label="コメント" name="desc">
          <a-textarea v-model:value="form.desc" />
        </a-form-item>
      </a-form>
      <a-button class="btn" type="primary" size="large" @click="current += 1">
        次へ
      </a-button>
    </div>
    <div v-if="current == 2">
      <div>タイトル: {{ form.title }}</div>
      <div>コメント: {{ form.desc }}</div>
      <a-button class="btn" type="primary" size="large" @click="upload">
        アップロード
      </a-button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive } from "vue";
import axios from "axios";
import { message } from "ant-design-vue";

const current = ref(0);
const fileList = ref([]);
const form = reactive({
  title: "",
  desc: "",
});

const handleChange = () => {
  current.value += 1;
};

const upload = async () => {
  const data = new FormData();
  data.append("title", form.title);
  data.append("description", form.desc);
  data.append("file", fileList.value[0].originFileObj);
  await axios.post("/api/v1/add", data).catch((e) => {
    message.error("アップロードに失敗しました。");
  });
  message.info("アップロードしました。");
};
</script>

<style lang="scss" scoped>
.container {
  margin: 0 auto;
  max-width: 800px;
  padding: 0 10px;
}
.progress {
  margin: 50px 10px;
}

.btn {
  float: right;
}
</style>