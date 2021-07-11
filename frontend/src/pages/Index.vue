<template>
  <a-spin v-if="list.length == 0" class="loading" />
  <Card
    v-else
    v-for="i in list"
    :key="i.id"
    :image="`http://localhost:8000/api/v1/file?id=${i.id}`"
    :title="i.title"
  ></Card>
</template>

<script setup lang="ts">
import axios from "axios";
// @ts-ignore
import Card from "@/components/Card.vue";
import { onBeforeMount, ref } from "vue";

const list = ref([]);

onBeforeMount(async () => {
  const res = await axios.get("/api/v1/all");
  list.value = res.data;
});

const url = "http://localhost:8000/api/v1/file?id=12";
</script>

<style lang="scss" scoped>
.loading {
  margin: 20px;
}
</style>