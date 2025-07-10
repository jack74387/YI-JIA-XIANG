<template>
  <div id="app">
    <Navigation v-if="!isAdminRoute" />

    <main class="min-h-screen">
      <router-view />
    </main>

    <footer v-if="!isAdminRoute" class="bg-gray-800 text-white py-8">
      <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
          <div>
            <h3 class="text-lg font-semibold mb-4">一佳香</h3>
            <p class="text-gray-300">
              專業的肉乾製造商，提供高品質的肉乾產品。
            </p>
          </div>
          <div>
            <h4 class="text-md font-semibold mb-4">關於我們</h4>
            <ul class="space-y-2 text-gray-300">
              <li><router-link to="/about" class="hover:text-white">品牌故事</router-link></li>
              <li><router-link to="/about" class="hover:text-white">職人工藝</router-link></li>
              <li><router-link to="/store-locator" class="hover:text-white">門市據點</router-link></li>
              <li><router-link to="/contact" class="hover:text-white">聯絡我們</router-link></li>
            </ul>
          </div>
          <div>
            <h4 class="text-md font-semibold mb-4">客戶服務</h4>
            <ul class="space-y-2 text-gray-300">
              <li><a href="#" class="hover:text-white">購物須知</a></li>
              <li><a href="#" class="hover:text-white">退換貨政策</a></li>
              <li><a href="#" class="hover:text-white">配送說明</a></li>
              <li><a href="#" class="hover:text-white">常見問題</a></li>
            </ul>
          </div>
          <div>
            <h4 class="text-md font-semibold mb-4">聯絡資訊</h4>
            <ul class="space-y-2 text-gray-300">
              <li>電話：(089) 357-996</li>
              <li>信箱：yijiaxiang88@gmail.com</li>
              <li>地址：台東縣台東市廣東路269號</li>
            </ul>
          </div>
        </div>
        <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-300">
          <p>&copy; 2025 一佳香. All rights reserved.</p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup lang="ts">
import { onMounted, computed } from 'vue'
import { useRoute } from 'vue-router'
import Navigation from '@/components/Navigation.vue'
import { useAuthStore } from '@/stores/auth'

const authStore = useAuthStore()
const route = useRoute()
const isAdminRoute = computed(() => route.path.startsWith('/admin'))

onMounted(() => {
  // 初始化認證狀態
  authStore.initAuth()
})
</script>

<style scoped>
/* 保留原有的樣式，但移除導航相關樣式 */
</style> 