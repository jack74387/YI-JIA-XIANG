<template>
  <div class="min-h-screen bg-gradient-to-br from-amber-50 to-orange-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div class="text-center">
        <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
          忘記密碼
        </h2>
        <p class="mt-2 text-sm text-gray-600">
          請輸入您的電子郵件地址，我們將發送重設密碼連結給您
        </p>
      </div>
      
      <form class="mt-8 space-y-6" @submit.prevent="handleForgotPassword">
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700">電子郵件</label>
          <input
            id="email"
            v-model="email"
            name="email"
            type="email"
            required
            class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-amber-500 focus:border-amber-500 focus:z-10 sm:text-sm"
            placeholder="請輸入您的電子郵件地址"
            :disabled="authStore.loading"
          />
        </div>

        <!-- 錯誤訊息 -->
        <div v-if="authStore.error" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative">
          <span class="block sm:inline">{{ authStore.error }}</span>
          <button
            @click="authStore.clearError"
            class="absolute top-0 bottom-0 right-0 px-4 py-3"
          >
            <span class="sr-only">關閉</span>
            <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
              <title>關閉</title>
              <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
            </svg>
          </button>
        </div>

        <!-- 成功訊息 -->
        <div v-if="successMessage" class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
          <span class="block sm:inline">{{ successMessage }}</span>
        </div>

        <div class="flex items-center justify-center">
          <div class="text-sm">
            <router-link
              to="/login"
              class="font-medium text-amber-600 hover:text-amber-500"
            >
              回到登入頁面
            </router-link>
          </div>
        </div>

        <div>
          <button
            type="submit"
            :disabled="authStore.loading || !email"
            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span v-if="authStore.loading" class="absolute left-0 inset-y-0 flex items-center pl-3">
              <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
            </span>
            {{ authStore.loading ? '發送中...' : '發送重設密碼連結' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const email = ref('')
const successMessage = ref('')

const handleForgotPassword = async () => {
  if (!email.value) {
    return
  }
  
  const result = await authStore.forgotPassword(email.value)
  if (result.success) {
    successMessage.value = result.message || '重設密碼連結已發送到您的電子郵件'
    email.value = ''
  }
}

onMounted(() => {
  // 如果已經登入，直接跳轉到首頁
  if (authStore.isAuthenticated) {
    router.push('/')
  }
})
</script>

<style scoped>
/* 使用 Tailwind CSS */
</style> 