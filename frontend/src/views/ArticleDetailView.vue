<template>
  <div class="max-w-3xl mx-auto py-8 px-4">
    <div v-if="loading" class="text-center py-12">載入中...</div>
    <div v-else-if="!article" class="text-center py-12 text-gray-500">找不到文章</div>
    <div v-else>
      <h1 class="text-3xl font-bold mb-2">{{ article.title }}</h1>
      <div class="text-gray-500 text-sm mb-4">{{ article.published_at ? article.published_at.split('T')[0] : '' }}</div>
      <div v-if="article.images && article.images.length" class="mb-4">
        <img :src="getImageUrl(article.images[0])" class="w-full h-64 object-contain rounded mb-2 bg-gray-100" />
        <div class="flex gap-2 overflow-x-auto">
          <img v-for="img in article.images.slice(1)" :key="img" :src="getImageUrl(img)" class="w-20 h-20 object-contain rounded border bg-gray-100" />
        </div>
      </div>
      <div v-if="article.videos && article.videos.length" class="mb-4">
        <video v-for="vid in article.videos" :key="vid" :src="getImageUrl(vid)" class="w-full h-56 rounded mb-2" controls />
      </div>
      <div class="text-gray-800 whitespace-pre-line text-lg">{{ article.content }}</div>
    </div>
  </div>
</template>
<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'
const route = useRoute()
const article = ref<any>(null)
const loading = ref(true)
onMounted(async () => {
  const id = route.params.id
  try {
    const res = await axios.get(`/api/v1/articles/${id}`)
    article.value = res.data.data
  } catch {
    article.value = null
  }
  loading.value = false
})
function getImageUrl(path: string) {
  if (!path) return ''
  if (path.startsWith('http')) return path
  if (path.startsWith('/storage')) {
    // 前端運行在 3000 端口，後端運行在 8000 端口
    return `http://localhost:8000${path}`
  }
  return path
}
</script> 