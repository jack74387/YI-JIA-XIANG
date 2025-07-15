<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- 頁面標題 -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">會員中心</h1>
        <p class="mt-2 text-gray-600">管理您的帳戶資訊和購物記錄</p>
      </div>

      <!-- 會員統計卡片 -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-500">總訂單數</p>
              <p class="text-2xl font-semibold text-gray-900">{{ statistics.total_orders || 0 }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-500">累積消費</p>
              <p class="text-2xl font-semibold text-gray-900">NT$ {{ formatPrice(statistics.total_spent || 0) }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-500">目前點數</p>
              <p class="text-2xl font-semibold text-gray-900">{{ statistics.current_points || 0 }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-500">會員等級</p>
              <p class="text-lg font-semibold" :class="statistics.member_level_color || 'text-gray-900'">
                {{ statistics.member_level || '一般會員' }}
              </p>
            </div>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- 側邊欄 -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-lg shadow p-6">
            <!-- 用戶頭像和基本資訊 -->
            <div class="text-center mb-6">
              <div class="relative inline-block">
                <img 
                  :src="user?.avatar_url || userAvatarUrl" 
                  :alt="user?.name"
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
              <h2 class="text-xl font-semibold text-gray-900">{{ user?.name }}</h2>
              <p class="text-gray-500">{{ user?.email }}</p>
              <p class="text-sm text-gray-400 mt-1">
                會員自 {{ user?.created_at ? formatDate(user.created_at) : '' }}
              </p>
            </div>

            <!-- 快速操作 -->
            <div class="space-y-3">
              <router-link
                to="/profile"
                class="flex items-center w-full px-4 py-3 text-sm font-medium text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-150"
              >
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                個人資料
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
        <div class="lg:col-span-2 space-y-8">
          <!-- 最近訂單 -->
          <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
              <h3 class="text-lg font-medium text-gray-900">最近訂單</h3>
            </div>
            
            <div class="p-6">
              <div v-if="recentOrders.length === 0" class="text-center py-8">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <p class="text-gray-500">尚無訂單紀錄</p>
                <router-link to="/products" class="mt-2 inline-block text-amber-600 hover:text-amber-700">
                  立即購物
                </router-link>
              </div>
              
              <div v-else class="space-y-4">
                <div 
                  v-for="order in recentOrders" 
                  :key="order.id"
                  class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow"
                >
                  <div class="flex justify-between items-start">
                    <div>
                      <p class="font-medium text-gray-900">訂單 #{{ order.order_number || order.id }}</p>
                      <p class="text-sm text-gray-500">{{ formatDate(order.created_at) }}</p>
                      <p class="text-sm text-gray-500">NT$ {{ formatPrice(order.final_amount ?? order.total) }}</p>
                    </div>
                    <div class="text-right">
                      <span 
                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                        :class="getStatusClass(order.status)"
                      >
                        {{ getStatusText(order.status) }}
                      </span>
                    </div>
                  </div>
                </div>
              </div>
              
              <div v-if="recentOrders.length > 0" class="mt-4 text-center">
                <router-link 
                  to="/orders"
                  class="text-amber-600 hover:text-amber-700 font-medium"
                >
                  查看所有訂單 →
                </router-link>
              </div>
            </div>
          </div>

          <!-- 點數交易記錄 -->
          <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
              <h3 class="text-lg font-medium text-gray-900">點數交易記錄</h3>
            </div>
            
            <div class="p-6">
              <div v-if="pointTransactions.length === 0" class="text-center py-8">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                </svg>
                <p class="text-gray-500">尚無點數交易記錄</p>
              </div>
              
              <div v-else class="space-y-3">
                <div 
                  v-for="transaction in pointTransactions.slice(0, 5)" 
                  :key="transaction.id"
                  class="flex justify-between items-center py-3 border-b border-gray-100 last:border-b-0"
                >
                  <div>
                    <p class="font-medium text-gray-900">{{ transaction.description || '點數交易' }}</p>
                    <p class="text-sm text-gray-500">{{ formatDate(transaction.created_at) }}</p>
                  </div>
                  <div class="text-right">
                    <p 
                      class="font-semibold"
                      :class="transaction.points >= 0 ? 'text-green-600' : 'text-red-600'"
                    >
                      {{ transaction.points >= 0 ? '+' : '' }}{{ transaction.points }}
                    </p>
                    <p class="text-xs text-gray-500">{{ transaction.type_name }}</p>
                  </div>
                </div>
              </div>
              
              <div v-if="pointTransactions.length > 0" class="mt-4 text-center">
                <router-link 
                  to="/points"
                  class="text-amber-600 hover:text-amber-700 font-medium"
                >
                  查看完整記錄 →
                </router-link>
              </div>
            </div>
          </div>

          <!-- 我的優惠券 -->
          <section ref="couponsSection" class="mt-8">
            <h2 class="text-xl font-bold mb-4">我的優惠券</h2>
            <div v-if="loadingCoupons" class="text-gray-400">載入中...</div>
            <div v-else>
              <div v-if="coupons.length === 0" class="text-gray-400">目前沒有可用優惠券</div>
              <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div v-for="coupon in coupons" :key="coupon.id" class="border rounded p-4 flex flex-col gap-2 bg-gray-50">
                  <div class="font-semibold">{{ coupon.name }} <span class="ml-2 text-xs text-gray-500">({{ coupon.code }})</span></div>
                  <div>折扣：{{ coupon.type === 'percent' ? coupon.value + '%' : 'NT$' + coupon.value }}</div>
                  <div>有效期限：{{ coupon.expired_at ? coupon.expired_at.slice(0, 10) : '無期限' }}</div>
                  <div>狀態：<span :class="coupon.active ? 'text-green-600' : 'text-gray-400'">{{ coupon.active ? '啟用' : '停用' }}</span></div>
                  <button v-if="!coupon.redeemed" class="btn-admin-sm w-max" @click="redeemCoupon(coupon)">領取/兌換</button>
                  <span v-else class="text-blue-600">已領取</span>
                </div>
              </div>
            </div>
          </section>
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
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import axios from 'axios'

const router = useRouter()
const authStore = useAuthStore()

const loading = ref(false)
const showAvatarUpload = ref(false)
const avatarInput = ref<HTMLInputElement>()
const couponsSection = ref<HTMLElement | null>(null)

// 計算屬性：使用authStore中的用戶資料
const user = computed(() => authStore.user)
const statistics = ref({
  total_orders: 0,
  total_spent: 0,
  current_points: 0,
  member_level: '一般會員',
  member_level_color: 'text-gray-900',
  is_premium: false
})
// recentOrders、pointTransactions、coupons 型別明確化
const recentOrders = ref<any[]>([])
const pointTransactions = ref<any[]>([])
const coupons = ref<any[]>([])
const loadingCoupons = ref(false)

// 計算屬性
const userAvatarUrl = computed(() => {
  if (!user.value?.name) return ''
  return `https://ui-avatars.com/api/?name=${encodeURIComponent(user.value.name)}&color=7C3AED&background=F3E8FF`
})

// 格式化價格
const formatPrice = (price: number | undefined | null) => {
  if (typeof price !== 'number' || isNaN(price)) return '0'
  return price.toLocaleString()
}

// 格式化日期
const formatDate = (dateString: string) => {
  if (!dateString) return ''
  return new Date(dateString).toLocaleDateString('zh-TW')
}

// 取得訂單狀態樣式
const getStatusClass = (status: string): string => {
  const classes: Record<string, string> = {
    'pending': 'bg-yellow-100 text-yellow-800',
    'paid': 'bg-blue-100 text-blue-800',
    'processing': 'bg-purple-100 text-purple-800',
    'shipped': 'bg-indigo-100 text-indigo-800',
    'delivered': 'bg-green-100 text-green-800',
    'cancelled': 'bg-red-100 text-red-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}

// 取得訂單狀態文字
const getStatusText = (status: string): string => {
  const texts: Record<string, string> = {
    'pending': '待處理',
    'paid': '已付款',
    'processing': '處理中',
    'shipped': '已出貨',
    'delivered': '已送達',
    'cancelled': '已取消'
  }
  return texts[status] || status
}

// 載入會員資料
const loadMemberData = async () => {
  loading.value = true
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/v1/member/statistics')
    if (response.data.success) {
      statistics.value = response.data.statistics
      recentOrders.value = response.data.recent_orders
      pointTransactions.value = response.data.point_transactions
    }
  } catch (error) {
    console.error('載入會員資料失敗:', error)
  } finally {
    loading.value = false
  }
}

// 載入優惠券資料
const fetchCoupons = async () => {
  loadingCoupons.value = true
  try {
    const res = await axios.get('http://127.0.0.1:8000/api/v1/coupons/user')
    if (res.data.success) {
      // 只顯示可用的優惠券
      coupons.value = res.data.data.available.map((item: any) => ({
        ...item.coupon,
        redeemed: false
      }))
    }
  } catch (e: any) {
    console.error('獲取優惠券失敗:', e)
  } finally {
    loadingCoupons.value = false
  }
}

// 兌換優惠券
const redeemCoupon = async (coupon: any) => {
  try {
    await axios.post('http://127.0.0.1:8000/api/v1/coupons/redeem', { code: coupon.code })
    alert('優惠券已領取/兌換')
    fetchCoupons()
  } catch (e: any) {
    alert('兌換失敗：' + (e.response?.data?.message || e.message))
  }
}

// 處理頭像上傳
const handleAvatarUpload = async (event: Event) => {
  const file = (event.target as HTMLInputElement).files?.[0]
  if (!file) return

  const formData = new FormData()
  formData.append('avatar', file)

  try {
    const response = await axios.post('http://127.0.0.1:8000/api/v1/member/avatar', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })

    if (response.data.success) {
      // 更新用戶資料
      if (authStore.user) {
        authStore.user.avatar_url = response.data.avatar_url
        // 更新localStorage中的用戶資料
        localStorage.setItem('auth_user', JSON.stringify(authStore.user))
      }
      showAvatarUpload.value = false
    }
  } catch (error) {
    console.error('頭像上傳失敗:', error)
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

const scrollToCoupons = () => {
  couponsSection.value?.scrollIntoView({ behavior: 'smooth' })
}

onMounted(() => {
  loadMemberData()
  fetchCoupons()
})
</script>

<style scoped>
.member-center {
  max-width: 520px;
  margin: 2rem auto;
  background: #fffbe8;
  border-radius: 1.2rem;
  box-shadow: 0 4px 24px #e0c68a33;
  padding: 2.5rem 2rem 2rem 2rem;
  font-family: 'Noto Sans TC', 'Segoe UI', Arial, sans-serif;
  color: #a67c00;
}
.member-center h1 {
  text-align: center;
  font-size: 1.5rem;
  margin-bottom: 1.5rem;
  color: #b8860b;
}
.profile {
  display: flex;
  align-items: center;
  gap: 1.2rem;
  margin-bottom: 2rem;
}
.avatar {
  width: 64px;
  height: 64px;
  background: #ffe9b2;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2.2rem;
  color: #b8860b;
  box-shadow: 0 2px 8px #e0c68a22;
}
.info .name {
  font-size: 1.15rem;
  font-weight: 700;
  color: #b8860b;
}
.info .email {
  font-size: 1rem;
  color: #a67c00;
}
.info .points {
  font-size: 1rem;
  color: #a67c00;
  margin-top: 0.2rem;
}
.orders {
  margin-top: 1.5rem;
}
.orders h2 {
  font-size: 1.1rem;
  color: #b8860b;
  margin-bottom: 0.7rem;
}
.order-item {
  background: #fffdfa;
  border-radius: 0.7rem;
  box-shadow: 0 1px 4px #e0c68a22;
  padding: 1rem 1.2rem;
  margin-bottom: 1rem;
  color: #a67c00;
  font-size: 1rem;
  transition: box-shadow 0.2s;
}
.order-item:hover {
  box-shadow: 0 4px 16px #b8860b33;
}
.empty {
  color: #b8860b;
  text-align: center;
  margin: 1.5rem 0;
}
.btn-main {
  background: #b8860b;
  color: #fff;
  border-radius: 2em;
  padding: 0.6em 1.6em;
  font-size: 1rem;
  font-weight: 700;
  box-shadow: 0 2px 8px #e0c68a22;
  border: none;
  transition: background 0.2s, color 0.2s, transform 0.2s;
  display: flex;
  align-items: center;
}
.btn-main:hover {
  background: #a67c00;
  color: #fffbe8;
  transform: scale(1.04);
}
.btn-sub {
  background: #fff;
  color: #a67c00;
  border: 1.5px solid #e0c68a;
  border-radius: 2em;
  padding: 0.6em 1.6em;
  font-size: 1rem;
  font-weight: 700;
  box-shadow: 0 2px 8px #e0c68a22;
  transition: background 0.2s, color 0.2s, transform 0.2s;
  display: flex;
  align-items: center;
}
.btn-sub:hover {
  background: #ffe9b2;
  color: #b8860b;
  transform: scale(1.04);
}
</style> 