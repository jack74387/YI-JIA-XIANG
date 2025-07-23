<template>
  <div>
    <Navigation v-if="!isAdmin" />
    <Sidebar v-if="!isAdmin" class="lg:hidden" />
    <div class="main-content min-h-screen bg-[#f9f6f1] transition-all duration-300 pb-20">
      <router-view />
    </div>
    <Footer v-if="!isAdmin" />
  </div>
</template>

<script setup lang="ts">
import { onMounted, computed, watch } from 'vue'
import { useRoute } from 'vue-router'
import Navigation from '@/components/Navigation.vue'
import Sidebar from '@/components/Sidebar.vue'
import Footer from '@/components/Footer.vue'
import { useAuthStore } from '@/stores/auth'

const authStore = useAuthStore()
const route = useRoute()
const isAdmin = computed(() => route.path.startsWith('/admin'))

onMounted(() => {
  // 初始化認證狀態
  authStore.initAuth()
})

watch(
  () => route.fullPath,
  () => {
    window.scrollTo(0, 0)
    document.body.scrollTop = 0
    document.documentElement.scrollTop = 0
    const app = document.getElementById('app')
    if (app) app.scrollTop = 0
    const mainLayout = document.querySelector('.main-layout')
    if (mainLayout) (mainLayout as HTMLElement).scrollTop = 0
  }
)
</script>

<style scoped>
/* 保留原有的樣式，但移除導航相關樣式 */
</style> 