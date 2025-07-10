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
              <div class="relative inline-block">
                <img 
                  :src="avatarUrl" 
                  :alt="authStore.user?.name"
                  class="h-24 w-24 rounded-full object-cover mx-auto mb-4 border-4 border-gray-200"
                />
                <button 
                  @click="showAvatarUpload = true"
                  class="absolute bottom-0 right-0 bg-amber-600 text-white rounded-full p-2 hover:bg-amber-700 transition-colors"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.768-6.768a2 2 0 112.828 2.828L11.828 15.828a4 4 0 01-1.414.94l-3.364 1.122a1 1 0 01-1.263-1.263l1.122-3.364a4 4 0 01.94-1.414z" />
                  </svg>
                </button>
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
                to="/member-center"
                class="flex items-center w-full px-4 py-3 text-sm font-medium text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-150"
              >
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                </svg>
                會員中心
              </router-link>
              
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
              <button
                @click="handleLogout"
                class="flex items-center w-full px-4 py-3 text-sm font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors duration-150"
              >
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                登出帳戶
              </button>
            </div>
          </div>
        </div>

        <!-- 主要內容區 -->
        <div class="lg:col-span-2">
          <!-- 錯誤訊息 -->
          <div v-if="memberStore.error" class="mb-6 bg-red-50 border border-red-200 rounded-md p-4">
            <div class="flex">
              <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div class="ml-3">
                <p class="text-sm text-red-800">{{ memberStore.error }}</p>
              </div>
              <div class="ml-auto pl-3">
                <button @click="memberStore.clearError()" class="text-red-400 hover:text-red-600">
                  <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
            </div>
          </div>

          <!-- 成功訊息 -->
          <div v-if="successMessage" class="mb-6 bg-green-50 border border-green-200 rounded-md p-4">
            <div class="flex">
              <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div class="ml-3">
                <p class="text-sm text-green-800">{{ successMessage }}</p>
              </div>
              <div class="ml-auto pl-3">
                <button @click="successMessage = ''" class="text-green-400 hover:text-green-600">
                  <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
            </div>
          </div>

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
                    <label for="phone" class="block text-sm font-medium text-gray-700">電話</label>
                    <input
                      id="phone"
                      v-model="form.phone"
                      type="tel"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500 sm:text-sm"
                      placeholder="請輸入您的電話"
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

                  <div>
                    <label for="birthday" class="block text-sm font-medium text-gray-700">生日</label>
                    <input
                      id="birthday"
                      v-model="form.birthday"
                      type="date"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500 sm:text-sm"
                    />
                  </div>

                  <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700">性別</label>
                    <select
                      id="gender"
                      v-model="form.gender"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500 sm:text-sm"
                    >
                      <option value="">請選擇</option>
                      <option value="male">男性</option>
                      <option value="female">女性</option>
                      <option value="other">其他</option>
                    </select>
                  </div>

                  <div class="sm:col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700">地址</label>
                    <textarea
                      id="address"
                      v-model="form.address"
                      rows="3"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500 sm:text-sm"
                      placeholder="請輸入您的地址"
                    ></textarea>
                  </div>

                  <div class="sm:col-span-2">
                    <div class="flex items-center">
                      <input
                        id="email_notifications"
                        v-model="form.email_notifications"
                        type="checkbox"
                        class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300 rounded"
                      />
                      <label for="email_notifications" class="ml-2 block text-sm text-gray-900">
                        接收電子郵件通知
                      </label>
                    </div>
                  </div>
                </div>

                <div class="mt-6 flex justify-end">
                  <button
                    type="submit"
                    :disabled="memberStore.loading"
                    class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-md text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 disabled:opacity-50"
                  >
                    {{ memberStore.loading ? '更新中...' : '更新資料' }}
                  </button>
                </div>
              </form>
            </div>
          </div>

          <!-- 密碼修改區塊 -->
          <div class="mt-8 bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
              <h3 class="text-lg font-medium text-gray-900">修改密碼</h3>
            </div>
            
            <div class="px-6 py-6">
              <form @submit.prevent="changePassword">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
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
                    :disabled="memberStore.loading"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 disabled:opacity-50"
                  >
                    {{ memberStore.loading ? '更新中...' : '修改密碼' }}
                  </button>
                </div>
              </form>
            </div>
          </div>

          <!-- 刪除帳戶區塊 -->
          <div class="mt-8 bg-red-50 border border-red-200 rounded-lg">
            <div class="px-6 py-4 border-b border-red-200">
              <h3 class="text-lg font-medium text-red-900">刪除帳戶</h3>
            </div>
            
            <div class="px-6 py-6">
              <p class="text-sm text-red-700 mb-4">
                刪除帳戶是永久性的操作，無法復原。所有您的資料、訂單記錄和點數都將被永久刪除。
              </p>
              <button
                @click="showDeleteDialog = true"
                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
              >
                刪除帳戶
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- 頭像上傳對話框 -->
    <div v-if="showAvatarUpload" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <h3 class="text-lg font-medium text-gray-900 mb-4">上傳頭像</h3>
          <input
            type="file"
            ref="avatarInput"
            accept="image/*"
            @change="handleAvatarUpload"
            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100"
          />
          <div class="mt-4 flex justify-end space-x-3">
            <button
              @click="showAvatarUpload = false"
              class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200"
            >
              取消
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- 刪除帳戶確認對話框 -->
    <div v-if="showDeleteDialog" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <h3 class="text-lg font-medium text-red-900 mb-4">確認刪除帳戶</h3>
          <p class="text-sm text-gray-600 mb-4">
            請輸入您的密碼以確認刪除帳戶。此操作無法復原。
          </p>
          <input
            v-model="deletePassword"
            type="password"
            placeholder="請輸入密碼"
            class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm mb-4"
          />
          <input
            v-model="deleteConfirmation"
            type="text"
            placeholder="請輸入 DELETE 確認"
            class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm mb-4"
          />
          <div class="flex justify-end space-x-3">
            <button
              @click="showDeleteDialog = false"
              class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200"
            >
              取消
            </button>
            <button
              @click="confirmDeleteAccount"
              :disabled="!deletePassword || deleteConfirmation !== 'DELETE'"
              class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 disabled:opacity-50"
            >
              確認刪除
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useMemberStore } from '@/stores/member'
import axios from 'axios'

const router = useRouter()
const authStore = useAuthStore()
const memberStore = useMemberStore()

const showAvatarUpload = ref(false)
const showDeleteDialog = ref(false)
const avatarInput = ref<HTMLInputElement>()
const successMessage = ref('')
const deletePassword = ref('')
const deleteConfirmation = ref('')

const form = reactive({
  name: '',
  email: '',
  phone: '',
  address: '',
  birthday: '',
  gender: '',
  email_notifications: true,
})

const passwordForm = reactive({
  current_password: '',
  new_password: '',
  new_password_confirmation: ''
})

// 計算屬性
const userProfile = computed(() => memberStore.userProfile)
const userAvatarUrl = computed(() => {
  if (!authStore.user?.name) return ''
  return `https://ui-avatars.com/api/?name=${encodeURIComponent(authStore.user.name)}&color=7C3AED&background=F3E8FF`
})

// 計算頭像URL
const avatarUrl = computed(() => {
  if (authStore.user?.avatar_url) {
    return authStore.user.avatar_url
  }
  return userAvatarUrl.value
})

// 格式化日期
const formatDate = (dateString: string | undefined) => {
  if (!dateString) return ''
  return new Date(dateString).toLocaleDateString('zh-TW')
}

// 初始化表單資料
const initForm = () => {
  if (authStore.user) {
    form.name = authStore.user.name || ''
    form.email = authStore.user.email || ''
    form.phone = authStore.user.phone || ''
    form.address = authStore.user.address || ''
    form.birthday = authStore.user.birthday || ''
    form.gender = authStore.user.gender || ''
    form.email_notifications = authStore.user.email_notifications ?? true
  }
}

// 更新個人資料
const updateProfile = async () => {
  const result = await memberStore.updateProfile(form)
  if (result?.success) {
    successMessage.value = result.message || '更新成功'
    // 更新 auth store 中的用戶資料
    if (authStore.user) {
      Object.assign(authStore.user, memberStore.userProfile)
    }
  } else {
    successMessage.value = result?.message || '更新失敗'
  }
}

// 修改密碼
const changePassword = async () => {
  const result = await memberStore.changePassword(passwordForm)
  if (result?.success) {
    successMessage.value = result.message || '密碼修改成功'
    // 清空表單
    passwordForm.current_password = ''
    passwordForm.new_password = ''
    passwordForm.new_password_confirmation = ''
  } else {
    successMessage.value = result?.message || '密碼修改失敗'
  }
}

// 處理頭像上傳
const handleAvatarUpload = async (event: Event) => {
  const file = (event.target as HTMLInputElement).files?.[0]
  if (!file) return

  const result = await memberStore.uploadAvatar(file)
  if (result?.success) {
    successMessage.value = result.message || '頭像上傳成功'
    // 更新用戶頭像
    if (authStore.user && result.avatar_url) {
      authStore.user.avatar_url = result.avatar_url
      // 更新localStorage中的用戶資料
      localStorage.setItem('auth_user', JSON.stringify(authStore.user))
    }
    showAvatarUpload.value = false
  } else {
    successMessage.value = result?.message || '頭像上傳失敗'
  }
}

// 確認刪除帳戶
const confirmDeleteAccount = async () => {
  if (deleteConfirmation.value !== 'DELETE') {
    successMessage.value = '請輸入 DELETE 確認刪除'
    return
  }

  const result = await memberStore.deleteAccount(deletePassword.value)
  if (result?.success) {
    await authStore.logout()
    router.push('/login')
  } else {
    successMessage.value = result?.message || '刪除帳戶失敗'
  }
}

// 處理登出
const handleLogout = async () => {
  try {
    await authStore.logout()
    router.push('/login')
  } catch (error) {
    console.error('登出失敗:', error)
  }
}

onMounted(async () => {
  initForm()
  // 獲取最新的用戶資料
  await fetchUserProfile()
})

// 獲取用戶資料
const fetchUserProfile = async () => {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/v1/member/profile')
    if (response.data.success && authStore.user) {
      // 更新auth store中的用戶資料
      Object.assign(authStore.user, response.data.user)
      // 更新localStorage
      localStorage.setItem('auth_user', JSON.stringify(authStore.user))
    }
  } catch (error) {
    console.error('獲取用戶資料失敗:', error)
  }
}
</script>

<style scoped>
/* 使用 Tailwind CSS */
</style> 