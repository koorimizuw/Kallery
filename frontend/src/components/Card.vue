<template>
  <a-card class="card" hoverable>
    <template #cover>
      <img max class="card-image" :src="image" />
    </template>
    <a-card-meta :title="title" />
  </a-card>
</template>

<script setup lang="ts">
import { defineProps } from "vue";

const props =
  defineProps<{
    image: string;
    title: string;
  }>();
</script>

<style lang="scss" scoped>
.card {
  display: inline-block;
  margin: 10px;
  max-width: 300px;
  &-image {
    max-height: 200px;
    object-fit: cover;
  }
}
</style>