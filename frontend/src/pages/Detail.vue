<template>
  <div class="container">
    <a-card :title="info.title" :bordered="false">
      <a-spin v-if="loading" class="loading" />
      <div v-else>
        <a-image
          :src="`http://localhost:8000/api/v1/file?original=1&id=${$route.params.id}`"
        />
        <a-divider orientation="left">作者のコメント</a-divider>
        <p>{{ info.desc }}</p>
        <a-divider orientation="left">タグ</a-divider>
        <p>
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
            v-if="isAuthenticated && !tag.inputVisible"
            @click="showInput"
            style="background: #fff; border-style: dashed"
          >
            <plus-outlined />
            New Tag
          </a-tag>
        </p>
        <a-list
          class="comment"
          :header="`${comments.length} コメント`"
          item-layout="horizontal"
          :data-source="comments"
        >
          <template #renderItem="{ item }">
            <a-list-item>
              <a-comment :author="item.name">
                <template #content>
                  <p>
                    {{ item.text }}
                  </p>
                </template>
              </a-comment>
            </a-list-item>
          </template>
        </a-list>
        <a-form-item>
          <a-textarea :rows="4" v-model:value="comment" />
        </a-form-item>
        <a-form-item>
          <a-button
            html-type="submit"
            :loading="submitting"
            type="primary"
            @click="addComment"
          >
            Add Comment
          </a-button>
        </a-form-item>
      </div>
    </a-card>
  </div>
</template>

<script setup lang="ts">
import axios from "axios";
import { onBeforeMount, reactive, ref, nextTick } from "@vue/runtime-core";
import { useStore } from "vuex";
import { useRoute, useRouter } from "vue-router";

const store = useStore();
const route = useRoute();

const loading = ref(true);

const isAuthenticated = store.state.authenticated;
const info = reactive({
  title: "",
  desc: "",
});
const comments = ref([]);

const tags = ref([]);

const submitting = ref<boolean>(false);
const comment = ref("");

const addComment = async () => {
  submitting.value = true;
  const res = await axios.post("/api/v1/comment", {
    id: route.params.id,
    text: comment.value,
  });
  comments.value.push({
    uid: 0,
    name: store.state.user,
    text: comment.value,
  });
  comment.value = "";
  submitting.value = false;
};

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
    id: route.params.id,
    name: tag.inputValue,
  });
  tags.value.push({
    id: 0,
    name: tag.inputValue,
  });
  tag.inputVisible = false;
  tag.inputValue = "";
};

onBeforeMount(async () => {
  const res = await axios.get("/api/v1/detail", {
    params: { id: route.params.id },
  });
  info.title = res.data.title;
  info.desc = res.data.description;

  const t = await axios.get("/api/v1/tag", {
    params: { id: route.params.id },
  });
  tags.value = t.data;

  const comm = await axios.get("/api/v1/comment", {
    params: { id: route.params.id },
  });
  comments.value = comm.data;

  loading.value = false;
});
</script>

<style lang="scss" scoped>
.container {
  max-width: 800px;
  margin: 10px auto;
}
</style>