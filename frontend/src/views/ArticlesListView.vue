<template>
  <div class="max-w-5xl mx-auto py-8 px-4">
    <h1 class="text-3xl font-bold mb-6">主題專欄</h1>
    <div v-if="loading" class="text-center py-12">載入中...</div>
    <div v-else-if="articles.length === 0" class="text-center py-12 text-gray-500">暫無文章</div>
    <div v-else class="grid md:grid-cols-2 gap-8">
      <router-link v-for="a in articles" :key="a.id" :to="`/articles/${a.id}`" class="block bg-white rounded-lg shadow p-4 hover:shadow-lg transition">
        <div v-if="a.images && a.images.length">
          <img :src="getImageUrl(a.images[0])" class="w-full h-44 object-contain rounded mb-2 bg-gray-100" />
        </div>
        <h2 class="text-xl font-semibold mb-1">{{ a.title }}</h2>
        <div class="text-gray-500 text-sm mb-2">{{ a.published_at ? a.published_at.split('T')[0] : '' }}</div>
        <div class="text-gray-700 line-clamp-2 mb-2">{{ a.content }}</div>
        <span class="text-amber-600 text-sm font-bold">閱讀更多 &rarr;</span>
      </router-link>
    </div>
  </div>
</template>
<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'
const articles = ref<any[]>([])
const loading = ref(true)
onMounted(async () => {
  const res = await axios.get('/api/v1/articles')
  articles.value = res.data.data.data || []
  loading.value = false
})
function getImageUrl(path: string) {
  if (!path) return ''
  if (path.startsWith('http')) return path
  if (path.startsWith('/storage')) return `${window.location.protocol}//${window.location.hostname}:8000${path}`
  return path
}
</script>
<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style> 