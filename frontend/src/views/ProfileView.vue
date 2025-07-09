<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- 頁面標題 -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">個人資料</h1>
        <p class="mt-2 text-gray-600">管理您的帳戶資訊和設定</p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- 側邊欄 -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-lg shadow p-6">
            <!-- 用戶頭像和基本資訊 -->
            <div class="text-center mb-6">
              <div class="h-24 w-24 rounded-full bg-amber-600 flex items-center justify-center text-white font-bold text-2xl mx-auto mb-4">
                {{ authStore.userInitials }}
              </div>
              <h2 class="text-xl font-semibold text-gray-900">{{ authStore.user?.name }}</h2>
              <p class="text-gray-500">{{ authStore.user?.email }}</p>
              <p class="text-sm text-gray-400 mt-1">
                會員自 {{ formatDate(authStore.user?.created_at) }}
              </p>
            </div>

            <!-- 快速操作 -->
            <div class="space-y-3">
              <router-link
                to="/orders"
                class="flex items-center w-full px-4 py-3 text-sm font-medium text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-150"
              >
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                我的訂單
              </router-link>
              
              <router-link
                to="/points"
                class="flex items-center w-full px-4 py-3 text-sm font-medium text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-150"
              >
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                </svg>
                我的點數
              </router-link>
              
              <router-link
                to="/coupon"
                class="flex items-center w-full px-4 py-3 text-sm font-medium text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-150"
              >
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                </svg>
                我的優惠券
              </router-link>
            </div>

            <!-- 登出按鈕 -->
            <div class="mt-6 pt-6 border-t border-gray-200">
              <LogoutButton 
                variant="outline"
                text="登出帳戶"
                :show-confirm="true"
                @logout="handleLogout"
                @error="handleLogoutError"
              />
            </div>
          </div>
        </div>

        <!-- 主要內容區 -->
        <div class="lg:col-span-2">
          <div class="bg-white rounded-lg shadow">
            <!-- 個人資料表單 -->
            <div class="px-6 py-4 border-b border-gray-200">
              <h3 class="text-lg font-medium text-gray-900">基本資料</h3>
            </div>
            
            <div class="px-6 py-6">
              <form @submit.prevent="updateProfile">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                  <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">姓名</label>
                    <input
                      id="name"
                      v-model="form.name"
                      type="text"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500 sm:text-sm"
                      placeholder="請輸入您的姓名"
                    />
                  </div>

                  <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">電子郵件</label>
                    <input
                      id="email"
                      v-model="form.email"
                      type="email"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500 sm:text-sm"
                      placeholder="請輸入您的電子郵件"
                      disabled
                    />
                    <p class="mt-1 text-sm text-gray-500">電子郵件地址無法修改</p>
                  </div>
                </div>

                <div class="mt-6 flex justify-end">
                  <button
                    type="submit"
                    :disabled="loading"
                    class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-md text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 disabled:opacity-50"
                  >
                    {{ loading ? '更新中...' : '更新資料' }}
                  </button>
                </div>
              </form>
            </div>
          </div>

          <!-- 密碼修改區塊 -->
          <div class="mt-6 bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
              <h3 class="text-lg font-medium text-gray-900">修改密碼</h3>
            </div>
            
            <div class="px-6 py-6">
              <form @submit.prevent="changePassword">
                <div class="space-y-4">
                  <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700">目前密碼</label>
                    <input
                      id="current_password"
                      v-model="passwordForm.current_password"
                      type="password"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500 sm:text-sm"
                      placeholder="請輸入目前密碼"
                    />
                  </div>

                  <div>
                    <label for="new_password" class="block text-sm font-medium text-gray-700">新密碼</label>
                    <input
                      id="new_password"
                      v-model="passwordForm.new_password"
                      type="password"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500 sm:text-sm"
                      placeholder="請輸入新密碼"
                    />
                  </div>

                  <div>
                    <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">確認新密碼</label>
                    <input
                      id="new_password_confirmation"
                      v-model="passwordForm.new_password_confirmation"
                      type="password"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500 sm:text-sm"
                      placeholder="請再次輸入新密碼"
                    />
                  </div>
                </div>

                <div class="mt-6 flex justify-end">
                  <button
                    type="submit"
                    :disabled="passwordLoading"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 disabled:opacity-50"
                  >
                    {{ passwordLoading ? '更新中...' : '修改密碼' }}
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- 登出確認對話框 -->
    <LogoutConfirmDialog 
      :show="showLogoutDialog" 
      @close="showLogoutDialog = false" 
    />
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import LogoutButton from '@/components/LogoutButton.vue'
import LogoutConfirmDialog from '@/components/LogoutConfirmDialog.vue'

const router = useRouter()
const authStore = useAuthStore()

const loading = ref(false)
const passwordLoading = ref(false)
const showLogoutDialog = ref(false)

const form = reactive({
  name: '',
  email: ''
})

const passwordForm = reactive({
  current_password: '',
  new_password: '',
  new_password_confirmation: ''
})

// 格式化日期
const formatDate = (dateString: string | undefined) => {
  if (!dateString) return ''
  return new Date(dateString).toLocaleDateString('zh-TW')
}

// 初始化表單資料
const initForm = () => {
  if (authStore.user) {
    form.name = authStore.user.name
    form.email = authStore.user.email
  }
}

// 更新個人資料
const updateProfile = async () => {
  loading.value = true
  try {
    // 這裡應該呼叫 API 更新個人資料
    console.log('更新個人資料:', form)
    // 模擬 API 呼叫
    await new Promise(resolve => setTimeout(resolve, 1000))
    console.log('個人資料更新成功')
  } catch (error) {
    console.error('更新失敗:', error)
  } finally {
    loading.value = false
  }
}

// 修改密碼
const changePassword = async () => {
  passwordLoading.value = true
  try {
    // 這裡應該呼叫 API 修改密碼
    console.log('修改密碼:', passwordForm)
    // 模擬 API 呼叫
    await new Promise(resolve => setTimeout(resolve, 1000))
    console.log('密碼修改成功')
    
    // 清空表單
    passwordForm.current_password = ''
    passwordForm.new_password = ''
    passwordForm.new_password_confirmation = ''
  } catch (error) {
    console.error('修改失敗:', error)
  } finally {
    passwordLoading.value = false
  }
}

// 處理登出
const handleLogout = () => {
  showLogoutDialog.value = true
}

// 處理登出錯誤
const handleLogoutError = (error: any) => {
  console.error('登出錯誤:', error)
}

onMounted(() => {
  initForm()
})
</script>

<style scoped>
/* 使用 Tailwind CSS */
</style> 