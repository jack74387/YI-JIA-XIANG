<template>
  <div v-if="show" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
      <div class="mt-3 text-center">
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-amber-100">
          <svg class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
          </svg>
        </div>
        <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">
          確認登出
        </h3>
        <div class="mt-2 px-7 py-3">
          <p class="text-sm text-gray-500">
            您確定要登出嗎？登出後需要重新登入才能使用會員功能。
          </p>
        </div>
        <div class="items-center px-4 py-3">
          <button
            @click="confirmLogout"
            :disabled="loading"
            class="px-4 py-2 bg-amber-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-500 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span v-if="loading" class="inline-flex items-center">
              <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              登出中...
            </span>
            <span v-else>確認登出</span>
          </button>
          <button
            @click="cancelLogout"
            :disabled="loading"
            class="mt-3 px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            取消
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

interface Props {
  show: boolean
}

interface Emits {
  (e: 'close'): void
}

const props = defineProps<Props>()
const emit = defineEmits<Emits>()

const router = useRouter()
const authStore = useAuthStore()
const loading = ref(false)

const confirmLogout = async () => {
  loading.value = true
  
  try {
    await authStore.logout()
    emit('close')
    router.push('/')
    console.log('登出成功')
  } catch (error) {
    console.error('登出失敗:', error)
    // 即使登出失敗，也要清除前端狀態並跳轉
    router.push('/')
  } finally {
    loading.value = false
  }
}

const cancelLogout = () => {
  emit('close')
}
</script>

<style scoped>
/* 使用 Tailwind CSS */
</style> 