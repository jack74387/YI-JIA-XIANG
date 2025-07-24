<template>
  <div class="order-confirmation-page">
    <div class="max-w-4xl mx-auto py-8 px-4">
      <!-- 載入中 -->
      <div v-if="loading" class="text-center py-12">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
        <p class="mt-2 text-gray-600">載入中...</p>
      </div>

      <!-- 訂單確認內容 -->
      <div v-else-if="order" class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <!-- 成功標題 -->
        <div class="bg-green-50 border-b border-green-200 p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <svg class="h-8 w-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div class="ml-3">
              <h1 class="text-2xl font-bold text-green-800">訂單建立成功！</h1>
              <p class="text-green-600 mt-1">訂單編號：{{ order.id }}</p>
            </div>
          </div>
        </div>

        <!-- 訂單狀態 -->
        <div class="p-6 border-b border-gray-200">
          <h2 class="text-lg font-semibold mb-4">訂單狀態</h2>
          <div class="flex items-center">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
              {{ order.status_text }}
            </span>
            <span class="ml-2 text-sm text-gray-600">建立時間：{{ formatDate(order.created_at) }}</span>
          </div>
        </div>

        <!-- 收件人資訊 -->
        <div class="p-6 border-b border-gray-200">
          <h2 class="text-lg font-semibold mb-4">收件人資訊</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">收件人姓名</label>
              <p class="mt-1 text-sm text-gray-900">{{ order.recipient_name }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">聯絡電話</label>
              <p class="mt-1 text-sm text-gray-900">{{ order.recipient_phone }}</p>
            </div>
            <div v-if="order.recipient_email">
              <label class="block text-sm font-medium text-gray-700">Email</label>
              <p class="mt-1 text-sm text-gray-900">{{ order.recipient_email }}</p>
            </div>
            
            <!-- 宅配地址 -->
            <div v-if="order.shipping_method === '宅配'" class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700">收件地址</label>
              <p class="mt-1 text-sm text-gray-900">{{ order.shipping_address }}</p>
            </div>
            
            <!-- 門市自取資訊 -->
            <div v-if="order.shipping_method === '門市自取' && order.store_name" class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700">取貨門市</label>
              <div class="mt-2 p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center mb-2">
                  <h4 class="font-semibold text-gray-900">{{ order.store_name }}</h4>
                  <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                    一佳香門市
                  </span>
                </div>
                <div class="space-y-1 text-sm text-gray-600">
                  <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>{{ order.store_address }}</span>
                  </div>
                  <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    <span>{{ order.store_phone }}</span>
                  </div>
                  <div v-if="order.store_hours" class="flex items-center">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ order.store_hours }}</span>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- 超商取貨資訊 -->
            <div v-if="order.shipping_method === '超商取貨' && order.convenience_store_name" class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700">取貨超商</label>
              <div class="mt-2 p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center mb-2">
                  <h4 class="font-semibold text-gray-900">{{ order.convenience_store_name }}</h4>
                  <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    {{ order.convenience_store_chain }}
                  </span>
                </div>
                <div class="space-y-1 text-sm text-gray-600">
                  <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>{{ order.convenience_store_address }}</span>
                  </div>
                  <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    <span>{{ order.convenience_store_phone }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- 配送與付款資訊 -->
        <div class="p-6 border-b border-gray-200">
          <h2 class="text-lg font-semibold mb-4">配送與付款資訊</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">配送方式</label>
              <p class="mt-1 text-sm text-gray-900">{{ order.shipping_method }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">付款方式</label>
              <p class="mt-1 text-sm text-gray-900">{{ order.payment_method }}</p>
            </div>
            <div v-if="order.note" class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700">備註</label>
              <p class="mt-1 text-sm text-gray-900">{{ order.note }}</p>
            </div>
          </div>
        </div>

        <!-- 商品清單 -->
        <div class="p-6 border-b border-gray-200">
          <h2 class="text-lg font-semibold mb-4">商品清單</h2>
          <div class="space-y-4">
            <div v-for="item in order.items" :key="item.id" class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
              <img :src="getImageUrl(
                item.product?.primary_image?.image_path
                || (item.product?.images && item.product.images[0]?.image_path)
                || item.image
              ) || '/images/placeholder.jpg'" :alt="item.name" class="w-16 h-16 object-cover rounded" />
              <div class="flex-1">
                <h3 class="font-semibold">{{ item.name }}</h3>
                <p class="text-sm text-gray-600">{{ item.spec_text }}</p>
                <p class="text-sm text-gray-500">數量：{{ item.quantity }}</p>
              </div>
              <div class="text-right">
                <p class="font-bold text-primary-600">NT${{ item.price }}</p>
                <p class="text-sm text-gray-600">小計：NT${{ item.subtotal }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- 訂單總計 -->
        <div class="p-6 bg-gray-50">
          <div class="flex flex-col gap-1">
            <div class="flex justify-between items-center text-lg font-bold">
              <span>原始金額</span>
              <span class="text-primary-600">NT${{ order.total }}</span>
            </div>
            <div v-if="order.discount && order.discount > 0" class="flex justify-between items-center text-base">
              <span>優惠券折扣</span>
              <span class="text-green-600">-NT${{ order.discount }}</span>
            </div>
            <div v-if="order.point_discount && order.point_discount > 0" class="flex justify-between items-center text-base">
              <span>點數折抵</span>
              <span class="text-green-600">-NT${{ order.point_discount }}</span>
            </div>
            <div class="flex justify-between items-center text-lg font-bold mt-2 border-t pt-2">
              <span>折抵後總計</span>
              <span class="text-primary-700">NT${{ order.final_amount ?? order.total }}</span>
            </div>
          </div>
        </div>

        <!-- 操作按鈕 -->
        <div class="p-6 bg-white">
          <div class="flex flex-col sm:flex-row gap-3 w-full">
            <router-link to="/products" class="btn-secondary order-btn">
              繼續購物
            </router-link>
            <router-link to="/orders" class="btn-primary order-btn">
              查看我的訂單
            </router-link>
          </div>
        </div>
      </div>

      <!-- 錯誤狀態 -->
      <div v-else class="text-center py-16">
        <div class="text-6xl mb-4">❌</div>
        <h3 class="text-xl font-semibold mb-2">找不到訂單</h3>
        <p class="text-gray-600 mb-6">訂單可能不存在或已被刪除</p>
        <router-link to="/orders" class="btn-primary">查看我的訂單</router-link>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const loading = ref(true)
const order = ref<any>(null)

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

function getImageUrl(imagePath: string | { image_path: string } | undefined) {
  if (!imagePath) return null
  if (typeof imagePath === 'object') {
    imagePath = imagePath.image_path
  }
  if (imagePath.startsWith('http')) return imagePath
  if (imagePath.startsWith('/storage')) return `${window.location.protocol}//${window.location.hostname}:8000${imagePath}`
  if (imagePath.startsWith('/')) return `${window.location.protocol}//${window.location.hostname}:8000${imagePath}`
  return imagePath
}

// 載入訂單資料
async function loadOrder() {
  try {
    const orderId = route.params.id
    const response = await axios.get(`/api/v1/orders/${orderId}`)
    
    if (response.data.success) {
      order.value = response.data.order
    }
  } catch (error) {
    console.error('載入訂單失敗:', error)
  } finally {
    loading.value = false
  }
}

onMounted(async () => {
  try {
    const orderId = route.params.id
    const response = await axios.get(`/api/v1/orders/${orderId}`)
    
    if (response.data.success) {
      order.value = response.data.order
    }
  } catch (error) {
    console.error('載入訂單失敗:', error)
  } finally {
    loading.value = false
  }
})
</script>

<style scoped>
.order-confirmation-page {
  background: #f8f6f2;
  min-height: 100vh;
}

.btn-primary {
  @apply bg-primary-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors;
}

.btn-secondary {
  @apply bg-gray-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors;
}

@media (max-width: 600px) {
  .order-btn {
    width: auto;
    min-width: 120px;
    font-size: 1.01rem;
    padding: 0.6em 1.5em;
    margin: 0;
    border-radius: 0.7em;
    box-sizing: border-box;
    display: inline-block;
  }
  .order-btn + .order-btn {
    margin-left: 0.7em;
    margin-top: 0;
  }
  .order-action-group, .flex.flex-col.sm\:flex-row {
    flex-direction: row !important;
    justify-content: flex-end !important;
    align-items: center;
    gap: 0;
  }
}
</style> 