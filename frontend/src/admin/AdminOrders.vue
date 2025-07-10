<template>
  <div class="flex min-h-screen">
    <AdminSidebar />
    <main class="flex-1 p-10 bg-gray-50">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <h1 class="text-2xl font-bold">訂單管理</h1>
        <div class="flex gap-2 items-center">
          <input v-model="search" @keyup.enter="fetchOrders(1)" type="text" placeholder="搜尋訂單編號/會員..." class="input-sm" />
          <button class="btn-admin-sm" @click="fetchOrders(1)">搜尋</button>
        </div>
      </div>
      <div class="bg-white rounded shadow p-6">
        <table class="w-full text-left">
          <thead>
            <tr>
              <th class="py-2">訂單編號</th>
              <th class="py-2">會員</th>
              <th class="py-2">金額</th>
              <th class="py-2">狀態</th>
              <th class="py-2">建立時間</th>
              <th class="py-2">操作</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="order in orders" :key="order.id">
              <td class="py-2">{{ order.order_number || order.id }}</td>
              <td class="py-2">{{ order.user?.name || order.user_name || '-' }}</td>
              <td class="py-2">NT${{ order.total }}</td>
              <td class="py-2">
                <span :class="statusColor(order.status)">{{ order.status_label || order.status }}</span>
              </td>
              <td class="py-2">{{ order.created_at ? order.created_at.slice(0, 19).replace('T', ' ') : '-' }}</td>
              <td class="py-2">
                <button class="text-blue-600 hover:underline mr-2" @click="viewOrder(order)">檢視</button>
              </td>
            </tr>
          </tbody>
        </table>
        <div v-if="orders.length === 0" class="text-center text-gray-400 py-8">尚無訂單</div>
        <!-- 分頁按鈕 -->
        <div v-if="pagination.total > pagination.per_page" class="flex justify-center mt-6 gap-2">
          <button
            v-for="page in totalPages"
            :key="page"
            @click="fetchOrders(page)"
            :class="['px-3 py-1 rounded', page === pagination.current_page ? 'bg-amber-600 text-white' : 'bg-gray-100 hover:bg-amber-100']"
          >
            {{ page }}
          </button>
        </div>
      </div>

      <!-- 訂單詳情 Modal -->
      <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-2xl relative max-h-[90vh] overflow-y-auto">
          <h2 class="text-lg font-bold mb-4">訂單詳情</h2>
          <div v-if="loadingDetail" class="text-center py-8">載入中...</div>
          <div v-else-if="orderDetail">
            <div class="mb-3"><b>訂單編號：</b>{{ orderDetail.order_number || orderDetail.id }}</div>
            <div class="mb-3"><b>會員：</b>{{ orderDetail.user?.name || orderDetail.user_name || '-' }}</div>
            <div class="mb-3"><b>金額：</b>NT${{ orderDetail.total }}</div>
            <div class="mb-3"><b>狀態：</b>
              <select v-model="orderStatus" class="input-sm w-auto">
                <option value="pending">待付款</option>
                <option value="paid">已付款</option>
                <option value="shipped">已出貨</option>
                <option value="completed">已完成</option>
                <option value="cancelled">已取消</option>
              </select>
              <button class="btn-admin-sm ml-2" @click="updateStatus" :disabled="updatingStatus">{{ updatingStatus ? '儲存中...' : '儲存' }}</button>
            </div>
            <div class="mb-3"><b>收件人：</b>{{ orderDetail.recipient_name }}，{{ orderDetail.recipient_phone }}，{{ orderDetail.recipient_address }}</div>
            <div class="mb-3"><b>建立時間：</b>{{ orderDetail.created_at ? orderDetail.created_at.slice(0, 19).replace('T', ' ') : '-' }}</div>
            <div class="mb-3"><b>商品明細：</b>
              <table class="w-full text-left border mt-2">
                <thead>
                  <tr>
                    <th class="py-1 px-2">商品名稱</th>
                    <th class="py-1 px-2">規格</th>
                    <th class="py-1 px-2">數量</th>
                    <th class="py-1 px-2">單價</th>
                    <th class="py-1 px-2">小計</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="item in orderDetail.items || orderDetail.order_items || []" :key="item.id">
                    <td class="py-1 px-2">{{ item.product_name || item.name }}</td>
                    <td class="py-1 px-2">{{ item.spec || '-' }}</td>
                    <td class="py-1 px-2">{{ item.quantity }}</td>
                    <td class="py-1 px-2">NT${{ item.price }}</td>
                    <td class="py-1 px-2">NT${{ item.price * item.quantity }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="flex justify-end mt-6">
              <button class="btn-cancel-sm" @click="closeModal">關閉</button>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import AdminSidebar from './AdminSidebar.vue'

const orders = ref<any[]>([])
const pagination = ref({ current_page: 1, last_page: 1, total: 0, per_page: 12 })
const totalPages = computed(() => pagination.value.last_page)
const search = ref('')

const showModal = ref(false)
const orderDetail = ref<any>(null)
const loadingDetail = ref(false)
const orderStatus = ref('')
const updatingStatus = ref(false)

const fetchOrders = async (page = 1) => {
  let url = `http://127.0.0.1:8000/api/v1/orders?page=${page}`
  if (search.value) url += `&search=${encodeURIComponent(search.value)}`
  const res = await axios.get(url)
  const pageData = res.data.data
  orders.value = pageData.data || []
  pagination.value = {
    current_page: pageData.current_page,
    last_page: pageData.last_page,
    total: pageData.total,
    per_page: pageData.per_page
  }
}

const statusColor = (status: string) => {
  switch (status) {
    case 'pending': return 'text-gray-500'
    case 'paid': return 'text-blue-600'
    case 'shipped': return 'text-amber-600'
    case 'completed': return 'text-green-600'
    case 'cancelled': return 'text-red-600'
    default: return 'text-gray-400'
  }
}

const viewOrder = async (order: any) => {
  showModal.value = true
  loadingDetail.value = true
  orderDetail.value = null
  try {
    const res = await axios.get(`http://127.0.0.1:8000/api/v1/orders/${order.id}`)
    orderDetail.value = res.data.product || res.data.data || res.data.order || res.data
    orderStatus.value = orderDetail.value.status
  } catch (e) {
    orderDetail.value = null
    alert('載入訂單詳情失敗')
  } finally {
    loadingDetail.value = false
  }
}

const closeModal = () => {
  showModal.value = false
  orderDetail.value = null
}

const updateStatus = async () => {
  if (!orderDetail.value) return
  updatingStatus.value = true
  try {
    await axios.put(`http://127.0.0.1:8000/api/v1/orders/${orderDetail.value.id}`, { status: orderStatus.value })
    alert('狀態已更新')
    closeModal()
    await fetchOrders(pagination.value.current_page)
  } catch (e) {
    alert('狀態更新失敗')
  } finally {
    updatingStatus.value = false
  }
}

onMounted(() => fetchOrders(1))
</script>

<style scoped>
.input-sm {
  @apply w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-amber-500;
}
.btn-admin-sm {
  @apply bg-amber-600 text-white font-semibold py-1 px-3 text-sm rounded-md shadow hover:bg-amber-700 transition-colors whitespace-nowrap;
}
.btn-cancel-sm {
  @apply bg-gray-300 text-gray-700 font-semibold py-1 px-4 text-sm rounded hover:bg-gray-400 transition-colors;
}
</style> 