<template>
  <div class="flex min-h-screen">
    <AdminSidebar />
    <main class="flex-1 p-10 bg-gray-50">
      <!-- 頁面標題 -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">管理員儀錶板</h1>
        <p class="mt-2 text-gray-600">歡迎回來，{{ adminName }}</p>
      </div>

      <!-- 載入狀態 -->
      <div v-if="loading" class="flex justify-center items-center py-20">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-amber-600"></div>
      </div>

      <!-- 儀錶板內容 -->
      <div v-else>
        <!-- 概覽統計卡片 -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <div 
            class="bg-white rounded-lg shadow p-6 cursor-pointer hover:shadow-lg transition-shadow duration-200"
            @click="navigateTo('/admin/products')"
          >
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center">
                  <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                  </svg>
                </div>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">商品總數</p>
                <p class="text-2xl font-semibold text-gray-900">{{ dashboardData.overview?.total_products || 0 }}</p>
              </div>
            </div>
            <div class="mt-2 text-xs text-blue-600">點擊查看商品管理</div>
          </div>

          <div 
            class="bg-white rounded-lg shadow p-6 cursor-pointer hover:shadow-lg transition-shadow duration-200"
            @click="navigateTo('/admin/orders')"
          >
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="h-8 w-8 bg-green-100 rounded-full flex items-center justify-center">
                  <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                </div>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">訂單總數</p>
                <p class="text-2xl font-semibold text-gray-900">{{ dashboardData.overview?.total_orders || 0 }}</p>
              </div>
            </div>
            <div class="mt-2 text-xs text-green-600">點擊查看訂單管理</div>
          </div>

          <div 
            class="bg-white rounded-lg shadow p-6 cursor-pointer hover:shadow-lg transition-shadow duration-200"
            @click="navigateTo('/admin/members')"
          >
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="h-8 w-8 bg-purple-100 rounded-full flex items-center justify-center">
                  <svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                  </svg>
                </div>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">會員總數</p>
                <p class="text-2xl font-semibold text-gray-900">{{ dashboardData.overview?.total_members || 0 }}</p>
              </div>
            </div>
            <div class="mt-2 text-xs text-purple-600">點擊查看會員管理</div>
          </div>

          <div 
            class="bg-white rounded-lg shadow p-6 cursor-pointer hover:shadow-lg transition-shadow duration-200"
            @click="navigateTo('/admin/coupons')"
          >
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="h-8 w-8 bg-amber-100 rounded-full flex items-center justify-center">
                  <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                  </svg>
                </div>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">優惠券總數</p>
                <p class="text-2xl font-semibold text-gray-900">{{ dashboardData.overview?.total_coupons || 0 }}</p>
              </div>
            </div>
            <div class="mt-2 text-xs text-amber-600">點擊查看優惠券管理</div>
          </div>
        </div>

        <!-- 今日統計 -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">今日統計</h3>
            <div class="space-y-3">
              <div class="flex justify-between">
                <span class="text-gray-600">新訂單</span>
                <span class="font-semibold">{{ dashboardData.today?.orders || 0 }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">營業額</span>
                <span class="font-semibold text-green-600">NT$ {{ formatNumber(dashboardData.today?.revenue || 0) }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">新會員</span>
                <span class="font-semibold">{{ dashboardData.today?.new_members || 0 }}</span>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">本週統計</h3>
            <div class="space-y-3">
              <div class="flex justify-between">
                <span class="text-gray-600">訂單數</span>
                <span class="font-semibold">{{ dashboardData.week?.orders || 0 }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">營業額</span>
                <span class="font-semibold text-green-600">NT$ {{ formatNumber(dashboardData.week?.revenue || 0) }}</span>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">本月統計</h3>
            <div class="space-y-3">
              <div class="flex justify-between">
                <span class="text-gray-600">訂單數</span>
                <span class="font-semibold">{{ dashboardData.month?.orders || 0 }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">營業額</span>
                <span class="font-semibold text-green-600">NT$ {{ formatNumber(dashboardData.month?.revenue || 0) }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- 圖表和詳細資訊 -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
          <!-- 銷售趨勢圖 -->
          <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">最近7天銷售趨勢</h3>
            <div class="h-64 flex items-end justify-between space-x-2">
                               <div
                   v-for="(day, index) in (dashboardData.sales_trend || [])"
                   :key="index"
                   class="flex-1 flex flex-col items-center"
                 >
                <div class="text-xs text-gray-500 mb-1">{{ day.date }}</div>
                <div
                  class="w-full bg-amber-200 rounded-t"
                  :style="{ height: getBarHeight(day.revenue) + 'px' }"
                ></div>
                <div class="text-xs text-gray-600 mt-1">NT$ {{ formatNumber(day.revenue) }}</div>
              </div>
            </div>
          </div>

          <!-- 訂單狀態統計 -->
          <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">訂單狀態統計</h3>
            <div class="space-y-3">
                             <div
                 v-for="(count, status) in (dashboardData.order_status || {})"
                 :key="status"
                 class="flex justify-between items-center"
               >
                <span class="text-gray-600">{{ getStatusText(status) }}</span>
                <span class="font-semibold">{{ count }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- 最近活動 -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- 最近訂單 -->
          <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">最近訂單</h3>
            <div class="space-y-3">
                             <div
                 v-for="order in (dashboardData.recent_orders || [])"
                 :key="order.id"
                 class="flex justify-between items-center p-3 bg-gray-50 rounded"
               >
                <div>
                  <div class="font-medium text-sm">{{ order.user_name }}</div>
                  <div class="text-xs text-gray-500">{{ order.created_at }}</div>
                </div>
                <div class="text-right">
                  <div class="font-semibold text-sm">NT$ {{ formatNumber(order.total_amount) }}</div>
                  <div class="text-xs" :class="getStatusColor(order.status)">{{ getStatusText(order.status) }}</div>
                </div>
              </div>
            </div>
          </div>

          <!-- 最近會員 -->
          <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">最近會員</h3>
            <div class="space-y-3">
                             <div
                 v-for="member in (dashboardData.recent_members || [])"
                 :key="member.id"
                 class="flex justify-between items-center p-3 bg-gray-50 rounded"
               >
                <div>
                  <div class="font-medium text-sm">{{ member.name }}</div>
                  <div class="text-xs text-gray-500">{{ member.email }}</div>
                </div>
                <div class="text-right">
                  <div class="font-semibold text-sm">{{ member.points }} 點</div>
                  <div class="text-xs text-gray-500">{{ member.created_at }}</div>
                </div>
              </div>
            </div>
          </div>

          <!-- 最近優惠券 -->
          <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">最近優惠券</h3>
            <div class="space-y-3">
              <div
                v-for="coupon in (dashboardData.recent_coupons || [])"
                :key="coupon.id"
                class="p-3 bg-gray-50 rounded"
              >
                <div class="flex justify-between items-start">
                  <div class="flex-1">
                    <div class="flex items-center space-x-2 mb-1">
                      <div class="font-medium text-sm">{{ coupon.name }}</div>
                      <span class="text-xs px-2 py-1 rounded-full" :class="coupon.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                        {{ coupon.is_active ? '啟用' : '停用' }}
                      </span>
                    </div>
                    <div class="text-xs text-gray-500 space-y-1">
                      <div>代碼: {{ coupon.code }}</div>
                      <div>{{ coupon.type === 'percent' ? '折扣' : '固定金額' }}: {{ coupon.value }}{{ coupon.type === 'percent' ? '%' : '元' }}</div>
                      <div v-if="coupon.expires_at">到期: {{ coupon.expires_at }}</div>
                    </div>
                  </div>
                  <div class="text-xs text-gray-500 ml-2">{{ coupon.created_at }}</div>
                </div>
              </div>
              <div v-if="!dashboardData.recent_coupons || dashboardData.recent_coupons.length === 0" class="text-center text-gray-500 py-4">
                暫無優惠券
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- 管理員密碼修改表單 -->
      <div class="mt-12 max-w-md bg-white rounded shadow p-6">
        <h2 class="text-xl font-bold mb-4">修改密碼</h2>
        <form @submit.prevent="changePassword">
          <div class="mb-4">
            <label class="block mb-1">舊密碼</label>
            <input v-model="oldPassword" type="password" class="input" required />
          </div>
          <div class="mb-4">
            <label class="block mb-1">新密碼</label>
            <input v-model="newPassword" type="password" class="input" required />
          </div>
          <div class="mb-4">
            <label class="block mb-1">確認新密碼</label>
            <input v-model="confirmPassword" type="password" class="input" required />
          </div>
          <button type="submit" class="btn-admin w-full" :disabled="passwordLoading">
            {{ passwordLoading ? '修改中...' : '修改密碼' }}
          </button>
          <p v-if="error" class="text-red-500 mt-2">{{ error }}</p>
          <p v-if="success" class="text-green-600 mt-2">{{ success }}</p>
        </form>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AdminSidebar from './AdminSidebar.vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import { useAdminAuthStore } from '@/stores/adminAuth'

const adminAuth = useAdminAuthStore()
const router = useRouter()

// 儀錶板資料
const dashboardData = ref({})
const loading = ref(true)
const adminName = ref('管理員')

// 密碼修改
const oldPassword = ref('')
const newPassword = ref('')
const confirmPassword = ref('')
const passwordLoading = ref(false)
const error = ref('')
const success = ref('')

// 獲取儀錶板資料
const fetchDashboardData = async () => {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/v1/admin/dashboard')
    if (response.data.success) {
      // 確保所有必要欄位都有預設值
      const data = response.data.data
      dashboardData.value = {
        overview: {
          total_products: 0,
          total_orders: 0,
          total_members: 0,
          total_coupons: 0,
          ...data.overview
        },
        today: {
          orders: 0,
          revenue: 0,
          new_members: 0,
          ...data.today
        },
        week: {
          orders: 0,
          revenue: 0,
          ...data.week
        },
        month: {
          orders: 0,
          revenue: 0,
          ...data.month
        },
        order_status: data.order_status || {},
        recent_orders: data.recent_orders || [],
        recent_members: data.recent_members || [],
        recent_coupons: data.recent_coupons || [],
        sales_trend: data.sales_trend || []
      }
    }
  } catch (error) {
    console.error('獲取儀錶板資料失敗:', error)
    // 設置預設值
    dashboardData.value = {
      overview: { total_products: 0, total_orders: 0, total_members: 0, total_coupons: 0 },
      today: { orders: 0, revenue: 0, new_members: 0 },
      week: { orders: 0, revenue: 0 },
      month: { orders: 0, revenue: 0 },
      order_status: {},
      recent_orders: [],
      recent_members: [],
      recent_coupons: [],
      sales_trend: []
    }
  } finally {
    loading.value = false
  }
}

// 格式化數字
const formatNumber = (num) => {
  if (num === null || num === undefined) return '0'
  return Number(num).toLocaleString()
}

// 取得狀態文字
const getStatusText = (status) => {
  const statusMap = {
    'pending': '待處理',
    'processing': '處理中',
    'shipped': '已出貨',
    'delivered': '已送達',
    'completed': '已完成',
    'cancelled': '已取消'
  }
  return statusMap[status] || status
}

// 取得狀態顏色
const getStatusColor = (status) => {
  const colorMap = {
    'pending': 'text-yellow-600',
    'processing': 'text-blue-600',
    'shipped': 'text-purple-600',
    'delivered': 'text-green-600',
    'completed': 'text-green-600',
    'cancelled': 'text-red-600'
  }
  return colorMap[status] || 'text-gray-600'
}

// 取得柱狀圖高度
const getBarHeight = (revenue) => {
  if (!dashboardData.value.sales_trend || !Array.isArray(dashboardData.value.sales_trend)) return 0
  const revenues = dashboardData.value.sales_trend.map(day => day.revenue || 0)
  const maxRevenue = Math.max(...revenues)
  if (maxRevenue === 0) return 0
  return ((revenue || 0) / maxRevenue) * 200 // 最大高度 200px
}

// 導航到指定頁面
const navigateTo = (path) => {
  router.push(path)
}

// 修改密碼
const changePassword = async () => {
  error.value = ''
  success.value = ''
  if (newPassword.value !== confirmPassword.value) {
    error.value = '新密碼與確認密碼不一致'
    return
  }
  passwordLoading.value = true
  try {
    const response = await axios.put(
      'http://127.0.0.1:8000/api/v1/auth/admin/password',
      {
        old_password: oldPassword.value,
        password: newPassword.value,
        password_confirmation: confirmPassword.value
      }
    )
    if (response.data.success) {
      success.value = '密碼修改成功，請重新登入'
      setTimeout(() => {
        adminAuth.logout()
        router.push('/admin/login')
      }, 1500)
    } else {
      error.value = response.data.message || '密碼修改失敗'
    }
  } catch (e) {
    error.value = e.response?.data?.message || '密碼修改失敗'
  } finally {
    passwordLoading.value = false
    oldPassword.value = ''
    newPassword.value = ''
    confirmPassword.value = ''
  }
}

onMounted(async () => {
  await adminAuth.initAuth()
  if (!adminAuth.isAuthenticated) {
    router.push('/admin/login')
    return
  }
  
  // 獲取管理員名稱
  if (adminAuth.user) {
    adminName.value = adminAuth.user.name || '管理員'
  }
  
  await fetchDashboardData()
})
</script>

<style scoped>
.btn-admin {
  @apply bg-amber-600 text-white font-semibold py-2 px-6 rounded hover:bg-amber-700 transition-colors;
}
.input {
  @apply w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-amber-500 mb-2;
}
</style> 