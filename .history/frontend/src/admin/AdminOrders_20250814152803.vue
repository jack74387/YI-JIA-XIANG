<template>
  <div class="flex min-h-screen">
    <AdminSidebar />
    <main class="flex-1 p-10 bg-gray-50">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <h1 class="text-2xl font-bold">訂單管理</h1>
        <div class="flex gap-2 items-center">
          <input v-model="search" @keyup.enter="fetchOrders(1)" type="text" placeholder="搜尋訂單編號/會員..." class="input-sm" />
          <select v-model="statusFilter" @change="fetchOrders(1)" class="input-sm w-auto">
            <option value="">全部狀態</option>
            <option value="pending">待處理</option>
            <option value="processing">處理中</option>
            <option value="shipped">已出貨</option>
            <option value="delivered">已送達</option>
            <option value="cancelled">已取消</option>
          </select>
          <button class="btn-admin-sm" @click="fetchOrders(1)">搜尋</button>
          <button class="btn-admin-sm bg-green-600 hover:bg-green-700" @click="exportOrders">匯出訂單</button>
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
              <td class="py-2">{{ order.id }}</td>
              <td class="py-2">{{ order.user?.name || '-' }}</td>
              <td class="py-2">NT${{ order.final_amount ?? order.total }}</td>
              <td class="py-2">
                <span :class="statusColor(order.status)">{{ order.status_text || order.status }}</span>
              </td>
              <td class="py-2">{{ formatTime(order.created_at) }}</td>
              <td class="py-2">
                <button class="text-blue-600 hover:underline mr-2" @click="viewOrder(order)">檢視</button>
              </td>
            </tr>
          </tbody>
        </table>
        <div v-if="orders.length === 0" class="text-center text-gray-400 py-8">尚無訂單</div>
        <!-- 分頁按鈕 -->
        <div v-if="pagination.last_page > 1" class="flex justify-center mt-6 gap-2">
          <button
            v-if="pagination.current_page > 1"
            @click="fetchOrders(pagination.current_page - 1)"
            class="px-3 py-1 rounded bg-gray-100 hover:bg-amber-100"
          >
            上一頁
          </button>
          <button
            v-for="page in getPageNumbers()"
            :key="page"
            @click="typeof page === 'number' ? fetchOrders(page) : null"
            :class="['px-3 py-1 rounded', page === pagination.current_page ? 'bg-amber-600 text-white' : 'bg-gray-100 hover:bg-amber-100']"
            :disabled="typeof page !== 'number'"
          >
            {{ page }}
          </button>
          <button
            v-if="pagination.current_page < pagination.last_page"
            @click="fetchOrders(pagination.current_page + 1)"
            class="px-3 py-1 rounded bg-gray-100 hover:bg-amber-100"
          >
            下一頁
          </button>
        </div>
        <!-- 分頁資訊 -->
        <div class="text-center text-sm text-gray-600 mt-2">
          共 {{ pagination.total }} 筆訂單，第 {{ pagination.current_page }} 頁，共 {{ pagination.last_page }} 頁
        </div>
      </div>

      <!-- 訂單詳情 Modal -->
      <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50 p-4" @click.self="closeModal">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-2xl relative max-h-[90vh] overflow-y-auto">
          <h2 class="text-lg font-bold mb-4">訂單詳情</h2>
          <div v-if="loadingDetail" class="text-center py-8">載入中...</div>
          <div v-else-if="orderDetail">
            <div class="mb-3"><b>訂單編號：</b>{{ orderDetail.id }}</div>
            <div class="mb-3"><b>會員：</b>{{ orderDetail.user?.name || '-' }}</div>
            <div class="mb-3"><b>金額：</b>NT${{ orderDetail.final_amount ?? orderDetail.total }}</div>
            <div v-if="orderDetail.discount && orderDetail.discount > 0" class="mb-1 text-sm text-green-700">優惠券折扣：-NT${{ orderDetail.discount }}</div>
            <div v-if="orderDetail.point_discount && orderDetail.point_discount > 0" class="mb-1 text-sm text-green-700">點數折抵：-NT${{ orderDetail.point_discount }}</div>
            <div v-if="orderDetail.final_amount !== undefined && orderDetail.final_amount !== orderDetail.total" class="mb-1 text-sm text-amber-700">原始金額：NT${{ orderDetail.total }}</div>
            <div class="mb-3"><b>狀態：</b>
              <select v-model="orderStatus" class="input-sm w-auto">
                <option value="pending">待處理</option>
                <option value="processing">處理中</option>
                <option value="shipped">已出貨</option>
                <option value="delivered">已送達</option>
                <option value="cancelled">已取消</option>
              </select>
              <button class="btn-admin-sm ml-2" @click="updateStatus" :disabled="updatingStatus">{{ updatingStatus ? '儲存中...' : '儲存' }}</button>
            </div>
            <div class="mb-3"><b>收件人：</b>{{ orderDetail.recipient_name }}，{{ orderDetail.recipient_phone }}
              <span v-if="orderDetail.recipient_email">，{{ orderDetail.recipient_email }}</span>
            </div>
            
            <!-- 配送資訊 -->
            <div class="mb-3">
              <b>配送方式：</b>{{ orderDetail.shipping_method }}
              <div v-if="orderDetail.shipping_method === '宅配'" class="ml-4 text-sm text-gray-600">
                地址：{{ orderDetail.shipping_address }}
              </div>
              <div v-if="orderDetail.shipping_method === '門市自取' && orderDetail.store_name" class="ml-4 text-sm text-gray-600">
                門市：{{ orderDetail.store_name }}<br>
                地址：{{ orderDetail.store_address }}<br>
                電話：{{ orderDetail.store_phone }}
                <span v-if="orderDetail.store_hours">，營業時間：{{ orderDetail.store_hours }}</span>
              </div>
              <div v-if="orderDetail.shipping_method === '超商取貨' && orderDetail.convenience_store_name" class="ml-4 text-sm text-gray-600">
                超商：{{ orderDetail.convenience_store_name }} ({{ orderDetail.convenience_store_chain }})<br>
                地址：{{ orderDetail.convenience_store_address }}<br>
                電話：{{ orderDetail.convenience_store_phone }}
              </div>
            </div>
            
            <div class="mb-3"><b>付款方式：</b>{{ orderDetail.payment_method }}</div>
            <div v-if="orderDetail.note" class="mb-3"><b>備註：</b>{{ orderDetail.note }}</div>
            <div class="mb-3"><b>建立時間：</b>{{ formatTime(orderDetail.created_at) }}</div>
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
                  <tr v-for="item in orderDetail.items || []" :key="item.id">
                    <td class="py-1 px-2">{{ item.name }}</td>
                    <td class="py-1 px-2">{{ item.spec_text || item.spec || '-' }}</td>
                    <td class="py-1 px-2">{{ item.quantity }}</td>
                    <td class="py-1 px-2">NT${{ item.price }}</td>
                    <td class="py-1 px-2">NT${{ item.subtotal }}</td>
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
const statusFilter = ref('')

const formatTime = (utcTime: string) => {
  if (!utcTime) return '-';
  return new Date(utcTime).toLocaleString('zh-TW', {
    timeZone: 'Asia/Taipei',
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit',
  });
};

// 取得分頁數字
const getPageNumbers = () => {
  const current = pagination.value.current_page
  const last = pagination.value.last_page
  const pages = []
  
  if (last <= 7) {
    for (let i = 1; i <= last; i++) {
      pages.push(i)
    }
  } else {
    if (current <= 4) {
      for (let i = 1; i <= 5; i++) {
        pages.push(i)
      }
      pages.push('...')
      pages.push(last)
    } else if (current >= last - 3) {
      pages.push(1)
      pages.push('...')
      for (let i = last - 4; i <= last; i++) {
        pages.push(i)
      }
    } else {
      pages.push(1)
      pages.push('...')
      for (let i = current - 1; i <= current + 1; i++) {
        pages.push(i)
      }
      pages.push('...')
      pages.push(last)
    }
  }
  
  return pages
}

const showModal = ref(false)
const orderDetail = ref<any>(null)
const loadingDetail = ref(false)
const orderStatus = ref('')
const updatingStatus = ref(false)

const fetchOrders = async (page = 1) => {
  const token = localStorage.getItem('admin_token')
  let url = `/api/v1/admin/orders?page=${page}`
  if (search.value) url += `&search=${encodeURIComponent(search.value)}`
  if (statusFilter.value) url += `&status=${encodeURIComponent(statusFilter.value)}`
  const res = await axios.get(url, { headers: { Authorization: `Bearer ${token}` } })
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
    case 'processing': return 'text-blue-600'
    case 'shipped': return 'text-amber-600'
    case 'delivered': return 'text-green-600'
    case 'cancelled': return 'text-red-600'
    default: return 'text-gray-400'
  }
}

const viewOrder = async (order: any) => {
  showModal.value = true
  loadingDetail.value = true
  orderDetail.value = null
  try {
    const token = localStorage.getItem('admin_token')
    const res = await axios.get(`/api/v1/admin/orders/${order.id}`, { headers: { Authorization: `Bearer ${token}` } })
    orderDetail.value = res.data.order
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
    const token = localStorage.getItem('admin_token')
    await axios.put(`/api/v1/admin/orders/${orderDetail.value.id}/status`, { status: orderStatus.value }, { headers: { Authorization: `Bearer ${token}` } })
    alert('狀態已更新')
    closeModal()
    await fetchOrders(pagination.value.current_page)
  } catch (e) {
    alert('狀態更新失敗')
  } finally {
    updatingStatus.value = false
  }
}

const exportOrders = async () => {
  try {
    const token = localStorage.getItem('admin_token')
    let url = '/api/v1/admin/orders/export'
    const params = new URLSearchParams()
    if (search.value) params.append('search', search.value)
    if (statusFilter.value) params.append('status', statusFilter.value)
    if (params.toString()) url += '?' + params.toString()
    
    console.log('Exporting orders from:', url)
    
    const response = await axios.get(url, { 
      headers: { Authorization: `Bearer ${token}` },
      responseType: 'blob'
    })
    
    console.log('Response received:', response)
    
    const blob = new Blob([response.data], { type: 'text/csv;charset=utf-8;' })
    const link = document.createElement('a')
    const url2 = window.URL.createObjectURL(blob)
    link.setAttribute('href', url2)
    link.setAttribute('download', `orders_${new Date().toISOString().slice(0, 10)}.csv`)
    link.style.visibility = 'hidden'
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
    
    console.log('Export completed successfully')
  } catch (e: any) {
    console.error('Export error:', e)
    console.error('Error response:', e.response)
    alert('匯出失敗: ' + (e.response?.data?.message || e.message))
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