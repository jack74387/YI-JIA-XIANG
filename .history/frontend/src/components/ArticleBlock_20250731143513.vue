<template>
  <div class="article-block">
    <h2 class="text-2xl font-bold mb-4">最新文章</h2>
    <div v-if="loading" class="text-center py-8">載入中...</div>
    <div v-else-if="articles.length === 0" class="text-center py-8 text-gray-500">暫無文章</div>
    <div v-else class="grid md:grid-cols-3 gap-8">
      <router-link v-for="a in articles.slice(0, 3)" :key="a.id" :to="`/articles/${a.id}`" class="article-card group">
        <div v-if="a.images && a.images.length" class="aspect-w-16 aspect-h-10 rounded-xl overflow-hidden mb-3 bg-gray-100">
          <img :src="getImageUrl(a.images[0])" class="object-contain w-full h-full group-hover:scale-105 transition-transform duration-300" />
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-1 group-hover:text-amber-700 transition">{{ a.title }}</h3>
        <div class="text-xs text-gray-400 mb-2 flex items-center gap-1">
          <svg class="w-4 h-4 inline-block text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
          {{ a.published_at ? a.published_at.split('T')[0] : '' }}
        </div>
        <div class="text-gray-700 text-sm line-clamp-2 mb-2">{{ a.content }}</div>
        <span class="text-amber-600 text-xs font-bold group-hover:underline">閱讀更多 &rarr;</span>
      </router-link>
    </div>
    <div v-if="articles.length > 3" class="text-center mt-8">
      <router-link to="/articles" class="see-more-btn">查看更多主題專欄</router-link>
    </div>
  </div>
</template>
<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { getImageUrl } from '@/utils/imageUtils'
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
  const apiBaseUrl = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000'
  if (path.startsWith('/storage')) {
    return `${apiBaseUrl}${path}`
  }
  return path
}
</script>
<style scoped>
.article-block {
  @apply max-w-7xl mx-auto py-12 px-4;
}
.article-card {
  @apply bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-shadow duration-300 p-5 flex flex-col cursor-pointer border border-transparent hover:border-amber-200;
  min-height: 320px;
  box-shadow: 0 4px 24px 0 rgba(180, 140, 90, 0.08);
}
.article-card:hover {
  box-shadow: 0 8px 32px 0 rgba(180, 140, 90, 0.16);
  border-color: #f7cd81;
}
.aspect-w-16.aspect-h-10 {
  aspect-ratio: 16/10;
}
.see-more-btn {
  @apply inline-block px-8 py-3 rounded-full font-bold text-white text-lg shadow-lg transition bg-gradient-to-r from-amber-500 to-amber-700 hover:from-amber-600 hover:to-amber-800;
  letter-spacing: 0.05em;
}
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style> 