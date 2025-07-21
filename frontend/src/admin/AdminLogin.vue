<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <form @submit.prevent="login" class="bg-white p-8 rounded shadow w-96">
      <h2 class="text-2xl font-bold mb-6 text-center">後台登入</h2>
      <input v-model="email" type="email" placeholder="Email" class="input mb-4" required />
      <input v-model="password" type="password" placeholder="密碼" class="input mb-6" required />
      <button type="submit" class="btn-primary w-full" :disabled="loading">
        {{ loading ? '登入中...' : '登入' }}
      </button>
      <p v-if="error" class="text-red-500 mt-4 text-center">{{ error }}</p>
    </form>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import { useAdminAuthStore } from '@/stores/adminAuth'

const email = ref('')
const password = ref('')
const loading = ref(false)
const error = ref('')
const router = useRouter()
const adminAuthStore = useAdminAuthStore()

const login = async () => {
  loading.value = true
  error.value = ''
  try {
    const res = await axios.post('/api/v1/auth/admin-login', {
      email: email.value,
      password: password.value
    })
    if (res.data.success) {
      // 使用 store 設置認證
      adminAuthStore.setAuth(res.data.token, res.data.user)
      // 跳轉到後台首頁
      router.push('/admin')
    } else {
      error.value = res.data.message || '登入失敗'
    }
  } catch (e: any) {
    error.value = e.response?.data?.message || '登入失敗'
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.input {
  @apply w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-amber-500 mb-2;
}
.btn-primary {
  @apply bg-amber-600 text-white font-semibold py-2 px-4 rounded hover:bg-amber-700 transition-colors;
}
</style> 