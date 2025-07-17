<template>
  <div class="orders-page">
    <div class="max-w-6xl mx-auto py-8 px-4">
      <!-- 頁面標題 -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">我的訂單</h1>
        <p class="text-gray-600 mt-2">查看您的訂單歷史</p>
      </div>

      <!-- 載入中 -->
      <div v-if="loading" class="text-center py-12">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
        <p class="mt-2 text-gray-600">載入中...</p>
      </div>

      <!-- 訂單列表 -->
      <div v-else-if="orders.length > 0" class="space-y-6">
        <div v-for="order in orders" :key="order.id" class="bg-white rounded-lg shadow-sm border overflow-hidden">
          <!-- 訂單標題 -->
          <div class="p-6 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
              <div>
                <h2 class="text-lg font-semibold">訂單 #{{ order.id }}</h2>
                <p class="text-sm text-gray-600 mt-1">建立時間：{{ formatDate(order.created_at) }}</p>
              </div>
              <div class="mt-2 sm:mt-0">
                <span :class="getStatusClass(order.status)" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium">
                  {{ order.status_text }}
                </span>
              </div>
            </div>
          </div>

          <!-- 訂單摘要 -->
          <div class="p-6 border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700">收件人</label>
                <p class="mt-1 text-sm text-gray-900">{{ order.recipient_name }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">配送方式</label>
                <p class="mt-1 text-sm text-gray-900">{{ order.shipping_method }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">付款方式</label>
                <p class="mt-1 text-sm text-gray-900">{{ order.payment_method }}</p>
              </div>
            </div>
          </div>

          <!-- 商品清單 -->
          <div class="p-6 border-b border-gray-200">
            <h3 class="text-md font-semibold mb-4">商品清單</h3>
            <div class="space-y-3">
              <div v-for="item in order.items" :key="item.id" class="flex items-center gap-4">
                <img :src="getImageUrl(item.product?.primary_image?.image_path || (item.product?.images && item.product.images[0]) || item.image) || '/images/placeholder.jpg'" :alt="item.name" class="w-12 h-12 object-cover rounded" />
                <div class="flex-1">
                  <h4 class="font-medium">{{ item.name }}</h4>
                  <p class="text-sm text-gray-600">{{ item.spec_text }} x {{ item.quantity }}</p>
                </div>
                <div class="text-right">
                  <p class="font-medium">NT${{ item.subtotal }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- 訂單總計與操作 -->
          <div class="p-6 bg-gray-50">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
              <div class="mb-4 sm:mb-0">
                <span class="text-lg font-bold">總計：NT${{ order.final_amount ?? order.total }}</span>
              </div>
              <div class="flex flex-col sm:flex-row gap-2">
                <router-link :to="`/orders/${order.id}`" class="btn-secondary">
                  查看詳情
                </router-link>
                <button v-if="order.status === 'pending'" @click="cancelOrder(order.id)" class="btn-danger">
                  取消訂單
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- 無訂單 -->
      <div v-else class="text-center py-16">
        <div class="text-6xl mb-4">📦</div>
        <h3 class="text-xl font-semibold mb-2">還沒有訂單</h3>
        <p class="text-gray-600 mb-6">開始購物，建立您的第一個訂單</p>
        <router-link to="/products" class="btn-primary">前往購物</router-link>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'

const loading = ref(true)
const orders = ref<any[]>([])

// 格式化日期
function formatDate(dateString: string) {
  return new Date(dateString).toLocaleString('zh-TW', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit'
  })
}

// 取得狀態樣式
function getStatusClass(status: string) {
  const statusClasses = {
    'pending': 'bg-yellow-100 text-yellow-800',
    'processing': 'bg-blue-100 text-blue-800',
    'shipped': 'bg-purple-100 text-purple-800',
    'delivered': 'bg-green-100 text-green-800',
    'cancelled': 'bg-red-100 text-red-800'
  }
  return statusClasses[status as keyof typeof statusClasses] || 'bg-gray-100 text-gray-800'
}

function getImageUrl(imagePath: string | undefined) {
  if (!imagePath) return null
  if (imagePath.startsWith('http')) return imagePath
  if (imagePath.startsWith('/storage')) return 'http://127.0.0.1:8000' + imagePath
  if (imagePath.startsWith('/')) return 'http://127.0.0.1:8000' + imagePath
  return imagePath
}

// 載入訂單列表
async function loadOrders() {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/v1/user/orders')
    if (response.data.success) {
      orders.value = response.data.orders
    }
  } catch (error) {
    console.error('載入訂單失敗:', error)
  } finally {
    loading.value = false
  }
}

// 取消訂單
async function cancelOrder(orderId: number) {
  if (!confirm('確定要取消這個訂單嗎？')) {
    return
  }

  try {
    const response = await axios.put(`http://127.0.0.1:8000/api/v1/orders/${orderId}/status`, {
      status: 'cancelled'
    })
    
    if (response.data.success) {
      // 重新載入訂單列表
      await loadOrders()
      alert('訂單已取消')
    }
  } catch (error) {
    console.error('取消訂單失敗:', error)
    alert('取消訂單失敗')
  }
}

onMounted(() => {
  loadOrders()
})
</script>

<style scoped>
.orders-page {
  background: #f8f6f2;
  min-height: 100vh;
}

.btn-primary {
  @apply bg-primary-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors;
}

.btn-secondary {
  @apply bg-gray-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors;
}

.btn-danger {
  @apply bg-red-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors;
}
</style> 