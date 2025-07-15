<template>
  <div class="min-h-screen bg-gradient-to-br from-amber-50 to-orange-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div class="text-center">
        <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
          會員註冊
        </h2>
        <p class="mt-2 text-sm text-gray-600">
          加入一佳香，享受優質商品與服務
        </p>
      </div>
      
      <form class="mt-8 space-y-6" @submit.prevent="handleRegister">
        <div class="space-y-4">
          <!-- 姓名 -->
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700">姓名 *</label>
            <input
              id="name"
              v-model="form.name"
              name="name"
              type="text"
              required
              class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-amber-500 focus:border-amber-500 focus:z-10 sm:text-sm"
              placeholder="請輸入您的姓名"
              :disabled="authStore.loading"
              @input="clearErrorOnInput"
            />
          </div>
          
          <!-- 手機號碼 -->
          <div>
            <label for="phone" class="block text-sm font-medium text-gray-700">手機號碼 *</label>
            <input
              id="phone"
              v-model="form.phone"
              name="phone"
              type="tel"
              required
              class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-amber-500 focus:border-amber-500 focus:z-10 sm:text-sm"
              placeholder="請輸入您的手機號碼"
              :disabled="authStore.loading"
              @input="clearErrorOnInput"
            />
            <p class="mt-1 text-xs text-gray-500">手機號碼將作為您的登入帳號</p>
          </div>
          
          <!-- 電子信箱 -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">電子信箱 *</label>
            <input
              id="email"
              v-model="form.email"
              name="email"
              type="email"
              required
              class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-amber-500 focus:border-amber-500 focus:z-10 sm:text-sm"
              placeholder="請輸入您的電子信箱"
              :disabled="authStore.loading"
              @input="clearErrorOnInput"
            />
          </div>
          
          <!-- 出生日期 -->
          <div>
            <label for="birthday" class="block text-sm font-medium text-gray-700">出生日期 *</label>
            <input
              id="birthday"
              v-model="form.birthday"
              name="birthday"
              type="date"
              required
              class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-amber-500 focus:border-amber-500 focus:z-10 sm:text-sm"
              :max="maxDate"
              :disabled="authStore.loading"
              @input="clearErrorOnInput"
            />
            <p class="mt-1 text-xs text-gray-500">* 生日送出後無法修改，請確認正確填寫</p>
          </div>
          
          <!-- 密碼 -->
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700">密碼 *</label>
            <input
              id="password"
              v-model="form.password"
              name="password"
              type="password"
              required
              class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-amber-500 focus:border-amber-500 focus:z-10 sm:text-sm"
              placeholder="請輸入密碼（至少6位）"
              :disabled="authStore.loading"
              @input="clearErrorOnInput"
            />
          </div>
          
          <!-- 確認密碼 -->
          <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">確認密碼 *</label>
            <input
              id="password_confirmation"
              v-model="form.password_confirmation"
              name="password_confirmation"
              type="password"
              required
              class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-amber-500 focus:border-amber-500 focus:z-10 sm:text-sm"
              placeholder="請再次輸入密碼"
              :disabled="authStore.loading"
              @input="clearErrorOnInput"
            />
          </div>
        </div>

        <!-- 錯誤訊息 -->
        <div v-if="showError" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative">
          <div class="flex">
            <div class="flex-shrink-0">
              <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
              </svg>
            </div>
            <div class="ml-3 flex-1">
              <p class="text-sm font-medium">{{ errorMessage }}</p>
              <p v-if="errorDetails" class="text-xs mt-1 text-red-600">{{ errorDetails }}</p>
            </div>
            <div class="ml-auto pl-3">
              <div class="-mx-1.5 -my-1.5">
                <button
                  @click="clearError"
                  class="inline-flex bg-red-50 rounded-md p-1.5 text-red-500 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-red-50 focus:ring-red-600"
                >
                  <span class="sr-only">關閉</span>
                  <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- 表單驗證錯誤 -->
        <div v-if="formErrors.length > 0" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
          <div class="flex">
            <div class="flex-shrink-0">
              <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
              </svg>
            </div>
            <div class="ml-3">
              <ul class="text-sm list-disc list-inside space-y-1">
                <li v-for="error in formErrors" :key="error">{{ error }}</li>
              </ul>
            </div>
          </div>
        </div>

        <div class="flex items-center justify-center">
          <div class="text-sm">
            <router-link
              to="/login"
              class="font-medium text-amber-600 hover:text-amber-500"
            >
              已有帳號？立即登入
            </router-link>
          </div>
        </div>

        <div>
          <button
            type="submit"
            :disabled="authStore.loading || !isFormValid"
            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span v-if="authStore.loading" class="absolute left-0 inset-y-0 flex items-center pl-3">
              <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
            </span>
            {{ authStore.loading ? '註冊中...' : '註冊' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { reactive, computed, onMounted, ref, watch } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const form = reactive({
  name: '',
  phone: '',
  email: '',
  birthday: '',
  password: '',
  password_confirmation: ''
})

// 本地錯誤狀態管理
const localError = ref('')
const errorDetails = ref('')
const hasSubmitted = ref(false) // 新增：追蹤是否已提交過表單

// 從 localStorage 恢復錯誤狀態（如果存在）
const restoreErrorState = () => {
  const savedError = localStorage.getItem('register_error')
  const savedSubmitted = localStorage.getItem('register_submitted')
  
  if (savedError) {
    localError.value = savedError
    localStorage.removeItem('register_error') // 清除保存的錯誤
  }
  
  if (savedSubmitted === 'true') {
    hasSubmitted.value = true
    localStorage.removeItem('register_submitted') // 清除保存的狀態
  }
}

// 保存錯誤狀態到 localStorage
const saveErrorState = (error: string) => {
  localStorage.setItem('register_error', error)
  localStorage.setItem('register_submitted', 'true')
}

// 計算最大日期（今天）
const maxDate = computed(() => {
  const today = new Date()
  return today.toISOString().split('T')[0]
})

// 計算屬性
const showError = computed(() => {
  // 只有在已提交過表單且有錯誤時才顯示
  return hasSubmitted.value && (localError.value || authStore.error)
})

const errorMessage = computed(() => {
  return localError.value || authStore.error || ''
})

// 表單驗證錯誤
const formErrors = computed(() => {
  // 只有在已提交過表單時才顯示驗證錯誤
  if (!hasSubmitted.value) {
    return []
  }
  
  const errors: string[] = []
  
  if (!form.name.trim()) {
    errors.push('請輸入姓名')
  }
  
  if (!form.phone.trim()) {
    errors.push('請輸入手機號碼')
  } else if (!/^09\d{8}$/.test(form.phone)) {
    errors.push('請輸入有效的手機號碼格式（09開頭，共10位數字）')
  }
  
  if (!form.email.trim()) {
    errors.push('請輸入電子信箱')
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) {
    errors.push('請輸入有效的電子信箱格式')
  }
  
  if (!form.birthday) {
    errors.push('請選擇出生日期')
  }
  
  if (form.password.length < 6) {
    errors.push('密碼至少需要6個字元')
  }
  
  if (form.password !== form.password_confirmation) {
    errors.push('密碼確認不符')
  }
  
  return errors
})

const isFormValid = computed(() => {
  return formErrors.value.length === 0
})

// 監聽 auth store 的錯誤變化
watch(() => authStore.error, (newError) => {
  if (newError) {
    localError.value = newError
    // 保存錯誤狀態
    saveErrorState(newError)
  }
})

// 清除錯誤
const clearError = () => {
  localError.value = ''
  errorDetails.value = ''
  authStore.clearError()
  // 清除保存的錯誤狀態
  localStorage.removeItem('register_error')
  localStorage.removeItem('register_submitted')
}

// 輸入時清除錯誤
const clearErrorOnInput = () => {
  if (localError.value || authStore.error) {
    clearError()
  }
}

const handleRegister = async (event?: Event) => {
  // 防止表單預設提交行為
  if (event) {
    event.preventDefault()
  }
  
  // 標記已提交過表單
  hasSubmitted.value = true
  
  // 清除之前的錯誤
  clearError()
  
  if (!isFormValid.value) {
    return
  }
  
  try {
    const result = await authStore.register(form)
    if (result.success) {
      // 註冊成功，清除錯誤狀態
      clearError()
      router.push('/')
    } else {
      // 設置本地錯誤訊息
      const errorMsg = result.error || '註冊失敗'
      localError.value = errorMsg
      // 保存錯誤狀態
      saveErrorState(errorMsg)
    }
  } catch (error: any) {
    const errorMsg = error.message || '註冊過程中發生錯誤'
    localError.value = errorMsg
    // 保存錯誤狀態
    saveErrorState(errorMsg)
  }
}

onMounted(() => {
  // 恢復錯誤狀態
  restoreErrorState()
  
  // 如果已經登入，直接跳轉到首頁
  if (authStore.isAuthenticated) {
    router.push('/')
  }
})
</script>

<style scoped>
/* 使用 Tailwind CSS */
</style> 