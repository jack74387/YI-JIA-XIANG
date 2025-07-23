<template>
  <div class="flex min-h-screen">
    <AdminSidebar />
    <main class="flex-1 p-10 bg-gray-50">
      <!-- 頁面標題 -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">庫存管理</h1>
        <p class="mt-2 text-gray-600">管理所有商品庫存與警戒值</p>
      </div>

      <!-- 搜尋區塊 -->
      <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">搜尋商品</label>
            <input v-model="search" @keyup.enter="fetchInventories(1)" type="text" placeholder="輸入商品名稱..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">分類篩選</label>
            <select v-model="categoryFilter" @change="fetchInventories(1)" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500">
              <option value="">全部分類</option>
              <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">警戒值篩選</label>
            <select v-model="alertFilter" @change="fetchInventories(1)" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500">
              <option value="">全部</option>
              <option value="low">低於警戒值</option>
              <option value="normal">正常</option>
            </select>
          </div>
          <div class="flex items-end space-x-2">
            <button class="btn-admin flex-1" @click="fetchInventories(1)" :disabled="loading">
              {{ loading ? '載入中...' : '搜尋' }}
            </button>
            <button class="btn-secondary" @click="resetFilters" :disabled="loading">
              重置
            </button>
          </div>
        </div>
      </div>

      <!-- 載入狀態 -->
      <div v-if="loading" class="flex justify-center items-center py-20">
        <div class="animate-spin rounded-full h-12 border-b-2 border-amber-600"></div>
      </div>

      <!-- 庫存列表 -->
      <div v-else class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">商品名稱</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">分類</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">規格</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">價格選項</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">庫存</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">警戒值</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">操作</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="item in inventories" :key="item.id" :class="{ 'bg-red-50': item.stock < item.alert_level }">
                <td class="px-6 py-4 whitespace-nowrap">{{ item.product_name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ item.category?.name || '-' }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="inline-block bg-gray-100 text-gray-700 rounded px-2 py-1 text-xs font-semibold">
                    {{ item.spec_name }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    v-for="price in (item.prices || [])"
                    :key="price && price.price !== undefined ? price.price : Math.random()"
                    v-if="price && price.price !== null && price.price !== undefined"
                    class="inline-block bg-blue-50 text-blue-800 rounded px-2 py-1 mr-1 mb-1 text-xs font-semibold"
                  >
                    NT${{ price.price }}<span v-if="price.label">（{{ price.label }}）</span>
                  </span>
                  <span v-if="!item.prices || item.prices.length === 0" class="text-gray-400 text-sm">無價格資訊</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  {{ item.stock }}
                  <span v-if="item.stock < item.alert_level" class="text-red-500 ml-1" title="低於警戒值">⚠️</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <input
                    type="number"
                    min="0"
                    class="input-sm w-16"
                    v-model.number="item._edit_alert_level"
                    @change="updateAlertLevel(item)"
                  />
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <button class="btn-admin-sm" @click="openEdit(item)">調整</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div v-if="inventories.length === 0" class="text-center text-gray-400 py-8">尚無庫存資料</div>
      </div>

      <!-- 分頁按鈕 -->
      <div v-if="pagination.total > pagination.per_page" class="flex justify-center mt-6 gap-2">
        <button
          v-for="page in totalPages"
          :key="page"
          @click="fetchInventories(page)"
          :class="['px-3 py-1 rounded', page === pagination.current_page ? 'bg-amber-600 text-white' : 'bg-gray-100 hover:bg-amber-100']"
        >
          {{ page }}
        </button>
      </div>

      <!-- 編輯庫存 Modal -->
      <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50 p-4" @click.self="showModal=false">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-sm relative">
          <h2 class="text-lg font-bold mb-4">調整庫存</h2>
          <div class="mb-4">商品：{{ editingItem?.product_name }}</div>
          <label class="block mb-2">庫存數量</label>
          <input v-model.number="editQuantity" type="number" min="0" class="input-sm w-full mb-4" />
          <div class="flex gap-2 justify-end">
            <button class="btn-admin-sm bg-gray-500 hover:bg-gray-600" @click="showModal=false">取消</button>
            <button class="btn-admin-sm" @click="submitEdit">儲存</button>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>
<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'
import AdminSidebar from './AdminSidebar.vue'

const inventories = ref([])
const categories = ref([])
const pagination = ref({ current_page: 1, last_page: 1, total: 0, per_page: 15 })
const totalPages = computed(() => pagination.value.last_page)
const search = ref('')
const categoryFilter = ref('')
const alertFilter = ref('')
const showModal = ref(false)
const editingItem = ref(null)
const editQuantity = ref(0)
const loading = ref(false)

async function fetchInventories(page = 1) {
  loading.value = true
  try {
    let url = '/api/v1/admin/inventories?page=' + page
    if (search.value) url += `&search=${encodeURIComponent(search.value)}`
    if (categoryFilter.value) url += `&category_id=${encodeURIComponent(categoryFilter.value)}`
    if (alertFilter.value) url += `&alert_level=${encodeURIComponent(alertFilter.value)}`
    
    console.log('API 請求:', url) // 除錯用
    
    const res = await axios.get(url)
    inventories.value = res.data.data || []
    // 初始化可編輯警戒值
    inventories.value.forEach(item => { item._edit_alert_level = item.alert_level })
    pagination.value = res.data.pagination || { current_page: 1, last_page: 1, total: 0, per_page: 15 }
    
    console.log('篩選結果:', {
      categoryFilter: categoryFilter.value,
      alertFilter: alertFilter.value,
      totalItems: inventories.value.length
    })
  } catch (error) {
    console.error('獲取庫存資料失敗:', error)
    inventories.value = []
  } finally {
    loading.value = false
  }
}

async function fetchCategories() {
  try {
    const response = await axios.get('/api/v1/categories')
    if (response.data.success) {
      categories.value = response.data.data
    }
  } catch (error) {
    console.error('獲取分類列表失敗:', error)
    categories.value = []
  }
}

// 重置篩選
function resetFilters() {
  search.value = ''
  categoryFilter.value = ''
  alertFilter.value = ''
  fetchInventories(1)
}

onMounted(async () => {
  await Promise.all([
    fetchInventories(1),
    fetchCategories()
  ])
})

function openEdit(item) {
  editingItem.value = item
  editQuantity.value = item.stock
  showModal.value = true
}

async function submitEdit() {
  await axios.post(`/api/v1/admin/inventories/${editingItem.value.id}/adjust`, { quantity: editQuantity.value })
  showModal.value = false
  fetchInventories(pagination.value.current_page)
}

async function updateAlertLevel(item) {
  if (item._edit_alert_level !== item.alert_level) {
    await axios.post(`/api/v1/admin/inventories/${item.id}/adjust`, {
      quantity: item.stock,
      alert_level: item._edit_alert_level
    })
    item.alert_level = item._edit_alert_level
  }
}
</script>
<style scoped>
.btn-admin {
  @apply bg-amber-600 text-white font-semibold py-2 px-4 rounded hover:bg-amber-700 transition-colors;
}
.btn-secondary {
  @apply bg-gray-50 text-white font-semibold py-2 px-4 rounded hover:bg-gray-600 transition-colors;
}
.btn-admin-sm {
  @apply bg-amber-600 text-white font-semibold py-1 px-3 text-sm rounded-md shadow hover:bg-amber-700 transition-colors whitespace-nowrap;
}
.input-sm {
  @apply w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-amber-500;
}
</style> 