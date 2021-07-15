  <template>
  <div class="container">
    <div class="progress">
      <a-steps :current="current" size="small">
        <a-step title="画像アップロード" />
        <a-step title="コメントを書く" />
        <a-step title="確認" />
        <a-step title="タグを追加する" />
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
    <div v-if="current == 3">
      <a-tag v-for="i in tags" :key="i.id">{{ i.name }}</a-tag>
      <a-input
        v-if="tag.inputVisible"
        ref="inputRef"
        type="text"
        size="small"
        :style="{ width: '78px' }"
        v-model:value="tag.inputValue"
        @blur="addTag"
        @keyup.enter="addTag"
      />
      <a-tag
        v-if="!tag.inputVisible"
        @click="showInput"
        style="background: #fff; border-style: dashed"
      >
        <plus-outlined />
        New Tag
      </a-tag>
      <a-button class="btn" type="primary" size="large" @click="back">
        終了
      </a-button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, nextTick } from "vue";
import axios from "axios";
import router from "../router";
import { message } from "ant-design-vue";

const current = ref(0);
const fileList = ref([]);
const form = reactive({
  title: "",
  desc: "",
});
const id = ref(0);

const handleChange = () => {
  current.value += 1;
};

const upload = async () => {
  const data = new FormData();
  data.append("title", form.title);
  data.append("description", form.desc);
  data.append("file", fileList.value[0].originFileObj);
  await axios
    .post("/api/v1/add", data)
    .then((res) => {
      message.info("アップロードしました。");
      id.value = res.data.id;
      current.value += 1;
    })
    .catch((e) => {
      message.error("アップロードに失敗しました。");
    });
};

const tags = ref([]);
const inputRef = ref();
const tag = reactive({
  inputVisible: false,
  inputValue: "",
});
const showInput = () => {
  tag.inputVisible = true;
  nextTick(() => {
    inputRef.value.focus();
  });
};
const addTag = async () => {
  if (!tag.inputValue) {
    tag.inputVisible = false;
    tag.inputValue = "";
    return;
  }
  const res = await axios.post("/api/v1/tag", {
    id: id.value,
    name: tag.inputValue,
  });
  tags.value.push({
    id: 0,
    name: tag.inputValue,
  });
  tag.inputVisible = false;
  tag.inputValue = "";
};

const back = () => {
  router.push("/");
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