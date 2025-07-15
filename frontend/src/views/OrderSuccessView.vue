<template>
  <div class="min-h-screen flex flex-col items-center justify-center bg-green-50 py-12">
    <div class="bg-white rounded-lg shadow-lg p-8 max-w-md w-full text-center">
      <div class="flex justify-center mb-4">
        <svg class="h-16 w-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2l4-4m6 2a9 9 0 11-18 0a9 9 0 0118 0z" />
        </svg>
      </div>
      <h1 class="text-2xl font-bold text-green-700 mb-2">訂單成立成功！</h1>
      <p class="text-gray-700 mb-4">感謝您的訂購，我們已收到您的訂單。</p>
      <div v-if="orderId" class="mb-4">
        <span class="text-gray-500">訂單編號：</span>
        <span class="font-mono text-lg text-green-800">{{ orderId }}</span>
      </div>
      <router-link to="/" class="btn-primary w-full mb-2">返回首頁</router-link>
      <router-link to="/orders" class="btn-secondary w-full">查看我的訂單</router-link>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useRoute } from 'vue-router'
const route = useRoute()
const orderId = route.params.orderId
</script>

<style scoped>
.btn-primary {
  @apply bg-green-600 text-white font-bold py-2 px-4 rounded hover:bg-green-700 transition;
  display: block;
}
.btn-secondary {
  @apply bg-gray-200 text-gray-800 font-bold py-2 px-4 rounded hover:bg-gray-300 transition;
  display: block;
}
</style> 