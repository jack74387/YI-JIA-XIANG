<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- 頁面標題 -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">我的訂單</h1>
        <p class="mt-2 text-gray-600">查看您的訂單歷史和狀態</p>
      </div>

      <!-- 訂單統計 -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center">
                <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-500">總訂單數</p>
              <p class="text-2xl font-semibold text-gray-900">{{ orders.length }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="h-8 w-8 bg-yellow-100 rounded-full flex items-center justify-center">
                <svg class="h-5 w-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-500">待處理</p>
              <p class="text-2xl font-semibold text-gray-900">{{ pendingOrders.length }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="h-8 w-8 bg-green-100 rounded-full flex items-center justify-center">
                <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-500">已完成</p>
              <p class="text-2xl font-semibold text-gray-900">{{ completedOrders.length }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="h-8 w-8 bg-red-100 rounded-full flex items-center justify-center">
                <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-500">已取消</p>
              <p class="text-2xl font-semibold text-gray-900">{{ cancelledOrders.length }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- 訂單列表 -->
      <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
          <h2 class="text-lg font-medium text-gray-900">訂單歷史</h2>
        </div>

        <div v-if="orders.length === 0" class="text-center py-12">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
          </svg>
          <h3 class="mt-2 text-sm font-medium text-gray-900">還沒有訂單</h3>
          <p class="mt-1 text-sm text-gray-500">開始購物來建立您的第一個訂單</p>
          <div class="mt-6">
            <router-link
              to="/products"
              class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-amber-600 hover:bg-amber-700"
            >
              去購物
            </router-link>
          </div>
        </div>

        <div v-else class="divide-y divide-gray-200">
          <div
            v-for="order in orders"
            :key="order.id"
            class="p-6 hover:bg-gray-50 transition-colors duration-200"
          >
            <div class="flex items-center justify-between mb-4">
              <div>
                <h3 class="text-lg font-medium text-gray-900">訂單 #{{ order.order_number }}</h3>
                <p class="text-sm text-gray-500">下單時間：{{ formatDate(order.created_at) }}</p>
              </div>
              <div class="text-right">
                <span
                  :class="[
                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                    getStatusClass(order.status)
                  ]"
                >
                  {{ getStatusText(order.status) }}
                </span>
                <p class="text-lg font-semibold text-gray-900 mt-1">${{ order.total_amount }}</p>
              </div>
            </div>

            <!-- 商品列表 -->
            <div class="space-y-3 mb-4">
              <div
                v-for="item in order.items"
                :key="item.id"
                class="flex items-center space-x-4"
              >
                <img
                  :src="item.product.image"
                  :alt="item.product.name"
                  class="h-16 w-16 rounded-lg object-cover"
                />
                <div class="flex-1">
                  <h4 class="text-sm font-medium text-gray-900">{{ item.product.name }}</h4>
                  <p class="text-sm text-gray-500">數量：{{ item.quantity }}</p>
                </div>
                <div class="text-right">
                  <p class="text-sm font-medium text-gray-900">${{ item.price }}</p>
                </div>
              </div>
            </div>

            <!-- 訂單操作 -->
            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
              <div class="flex space-x-4">
                <button
                  @click="viewOrderDetail(order.id)"
                  class="text-amber-600 hover:text-amber-700 text-sm font-medium"
                >
                  查看詳情
                </button>
                <button
                  v-if="order.status === 'pending'"
                  @click="cancelOrder(order.id)"
                  class="text-red-600 hover:text-red-700 text-sm font-medium"
                >
                  取消訂單
                </button>
                <button
                  v-if="order.status === 'shipped'"
                  @click="trackOrder(order.id)"
                  class="text-blue-600 hover:text-blue-700 text-sm font-medium"
                >
                  追蹤包裹
                </button>
              </div>
              
              <div class="flex space-x-2">
                <button
                  v-if="order.status === 'completed'"
                  @click="reorder(order.id)"
                  class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-md text-sm font-medium"
                >
                  再次購買
                </button>
                <button
                  v-if="order.status === 'completed'"
                  @click="writeReview(order.id)"
                  class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium"
                >
                  寫評價
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

// 模擬訂單資料
const orders = ref([
  {
    id: 1,
    order_number: 'ORD-2025-001',
    status: 'completed',
    total_amount: 1200,
    created_at: '2025-07-01T10:30:00Z',
    items: [
      {
        id: 1,
        product: {
          name: '原味豬肉乾',
          image: '/images/pork-jerky-1.jpg'
        },
        quantity: 2,
        price: 600
      }
    ]
  },
  {
    id: 2,
    order_number: 'ORD-2025-002',
    status: 'shipped',
    total_amount: 800,
    created_at: '2025-07-05T14:20:00Z',
    items: [
      {
        id: 2,
        product: {
          name: '黑胡椒豬肉乾',
          image: '/images/pork-jerky-2.jpg'
        },
        quantity: 1,
        price: 800
      }
    ]
  },
  {
    id: 3,
    order_number: 'ORD-2025-003',
    status: 'pending',
    total_amount: 1500,
    created_at: '2025-07-08T09:15:00Z',
    items: [
      {
        id: 3,
        product: {
          name: '蜜汁豬肉乾',
          image: '/images/pork-jerky-3.jpg'
        },
        quantity: 3,
        price: 500
      }
    ]
  }
])

// 計算不同狀態的訂單數量
const pendingOrders = computed(() => 
  orders.value.filter(order => order.status === 'pending')
)

const completedOrders = computed(() => 
  orders.value.filter(order => order.status === 'completed')
)

const cancelledOrders = computed(() => 
  orders.value.filter(order => order.status === 'cancelled')
)

// 格式化日期
const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('zh-TW', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

// 取得狀態樣式
const getStatusClass = (status: string) => {
  const classes = {
    pending: 'bg-yellow-100 text-yellow-800',
    processing: 'bg-blue-100 text-blue-800',
    shipped: 'bg-purple-100 text-purple-800',
    completed: 'bg-green-100 text-green-800',
    cancelled: 'bg-red-100 text-red-800'
  }
  return classes[status as keyof typeof classes] || 'bg-gray-100 text-gray-800'
}

// 取得狀態文字
const getStatusText = (status: string) => {
  const texts = {
    pending: '待處理',
    processing: '處理中',
    shipped: '已出貨',
    completed: '已完成',
    cancelled: '已取消'
  }
  return texts[status as keyof typeof texts] || '未知狀態'
}

// 查看訂單詳情
const viewOrderDetail = (orderId: number) => {
  console.log('查看訂單詳情:', orderId)
  // 這裡可以導向訂單詳情頁面
}

// 取消訂單
const cancelOrder = (orderId: number) => {
  console.log('取消訂單:', orderId)
  // 這裡可以呼叫 API 取消訂單
}

// 追蹤包裹
const trackOrder = (orderId: number) => {
  console.log('追蹤包裹:', orderId)
  // 這裡可以導向包裹追蹤頁面
}

// 再次購買
const reorder = (orderId: number) => {
  console.log('再次購買:', orderId)
  // 這裡可以將商品加入購物車
}

// 寫評價
const writeReview = (orderId: number) => {
  console.log('寫評價:', orderId)
  // 這裡可以導向評價頁面
}

onMounted(() => {
  // 這裡可以載入用戶的訂單資料
  console.log('載入訂單資料')
})
</script>

<style scoped>
/* 使用 Tailwind CSS */
</style> 