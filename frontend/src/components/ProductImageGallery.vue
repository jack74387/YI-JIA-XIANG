<template>
  <div class="gallery">
    <div class="relative">
      <img v-if="Array.isArray(images) && images.length > 0" :src="images[activeIndex]" class="main-img" />
    </div>
    <div v-if="Array.isArray(images) && images.length > 0" class="thumbs flex gap-2 mt-2">
      <img v-for="(img, i) in images" :key="i" :src="img" class="thumb" :class="{active: i===activeIndex}" @click="activeIndex=i" />
    </div>
  </div>
</template>
<script setup lang="ts">
import { ref } from 'vue'
const props = defineProps<{ images: string[]|undefined, weight: string }>()
const activeIndex = ref(0)
</script>
<style scoped>
.main-img { width: 320px; border-radius: 8px; }
.thumb { width: 60px; height: 60px; object-fit: cover; border-radius: 6px; cursor: pointer; border: 2px solid transparent; }
.thumb.active { border-color: #b8860b; }
</style> 