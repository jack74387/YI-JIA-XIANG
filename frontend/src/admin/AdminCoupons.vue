<template>
  <div class="flex min-h-screen">
    <AdminSidebar />
    <main class="flex-1 p-10 bg-gray-50">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <h1 class="text-2xl font-bold">優惠券管理</h1>
        <div class="flex gap-2 items-center">
          <input v-model="search" @keyup.enter="fetchCoupons(1)" type="text" placeholder="搜尋名稱/代碼..." class="input-sm" />
          <button class="btn-admin-sm" @click="fetchCoupons(1)">搜尋</button>
          <button class="btn-admin-sm" @click="openAddModal">新增優惠券</button>
        </div>
      </div>
      <div class="bg-white rounded shadow p-6">
        <table class="w-full text-left">
          <thead>
            <tr>
              <th class="py-2">名稱</th>
              <th class="py-2">代碼</th>
              <th class="py-2">折扣類型</th>
              <th class="py-2">折扣值</th>
              <th class="py-2">最低消費</th>
              <th class="py-2">使用限制</th>
              <th class="py-2">已使用</th>
              <th class="py-2">剩餘</th>
              <th class="py-2">有效期限</th>
              <th class="py-2">狀態</th>
              <th class="py-2">操作</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="coupon in coupons" :key="coupon.id">
              <td class="py-2">{{ coupon.name }}</td>
              <td class="py-2">{{ coupon.code }}</td>
              <td class="py-2">{{ coupon.type === 'percent' ? '百分比' : '金額' }}</td>
              <td class="py-2">{{ coupon.type === 'percent' ? coupon.value + '%' : 'NT$' + coupon.value }}</td>
              <td class="py-2">{{ coupon.min_order ? 'NT$' + coupon.min_order : '無限制' }}</td>
              <td class="py-2">{{ coupon.usage_limit || '無限制' }}</td>
              <td class="py-2">{{ coupon.used_count || 0 }}</td>
              <td class="py-2">
                <span :class="getRemainingClass(coupon)">
                  {{ getRemainingText(coupon) }}
                </span>
              </td>
              <td class="py-2">{{ coupon.expires_at ? coupon.expires_at.slice(0, 10) : '-' }}</td>
              <td class="py-2">
                <button :class="coupon.is_active ? 'text-green-600' : 'text-gray-400'" @click="toggleStatus(coupon)">
                  {{ coupon.is_active ? '啟用' : '停用' }}
                </button>
              </td>
              <td class="py-2">
                <button class="text-blue-600 hover:underline mr-2" @click="openEditModal(coupon)">編輯</button>
                <button class="text-red-600 hover:underline" @click="deleteCoupon(coupon.id)">刪除</button>
              </td>
            </tr>
          </tbody>
        </table>
        <div v-if="coupons.length === 0" class="text-center text-gray-400 py-8">尚無優惠券</div>
        <!-- 分頁按鈕 -->
        <div v-if="pagination.total > pagination.per_page" class="flex justify-center mt-6 gap-2">
          <button
            v-for="page in totalPages"
            :key="page"
            @click="fetchCoupons(page)"
            :class="['px-3 py-1 rounded', page === pagination.current_page ? 'bg-amber-600 text-white' : 'bg-gray-100 hover:bg-amber-100']"
          >
            {{ page }}
          </button>
        </div>
      </div>

      <!-- 新增/編輯優惠券 Modal -->
      <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-lg relative max-h-[90vh] overflow-y-auto">
          <h2 class="text-lg font-bold mb-3">{{ editingCoupon ? '編輯優惠券' : '新增優惠券' }}</h2>
          <form @submit.prevent="submitCoupon">
            <div class="mb-3">
              <label class="block mb-1 text-sm">名稱</label>
              <input v-model="form.name" type="text" class="input-sm" required />
            </div>
            <div class="mb-3">
              <label class="block mb-1 text-sm">代碼</label>
              <input v-model="form.code" type="text" class="input-sm" required />
            </div>
            <div class="mb-3">
              <label class="block mb-1 text-sm">折扣類型</label>
              <select v-model="form.type" class="input-sm" required>
                <option value="fixed">固定金額</option>
                <option value="percent">百分比</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="block mb-1 text-sm">折扣值</label>
              <input v-model="form.value" type="number" class="input-sm" required />
            </div>
            <div class="mb-3">
              <label class="block mb-1 text-sm">最低訂單金額</label>
              <input v-model="form.min_order" type="number" class="input-sm" placeholder="0 表示無限制" />
            </div>
            <div class="mb-3">
              <label class="block mb-1 text-sm">使用次數限制</label>
              <input v-model="form.usage_limit" type="number" class="input-sm" placeholder="0 表示無限制" />
            </div>
            <div class="mb-3">
              <label class="block mb-1 text-sm">描述</label>
              <textarea v-model="form.description" class="input-sm" rows="3" placeholder="優惠券描述"></textarea>
            </div>
            <div class="mb-3">
              <label class="block mb-1 text-sm">有效期限</label>
              <input v-model="form.expires_at" type="date" class="input-sm" />
            </div>
            <div class="flex justify-end gap-2 mt-4">
              <button type="button" class="btn-cancel-sm" @click="closeModal">取消</button>
              <button type="submit" class="btn-admin-sm">{{ editingCoupon ? '儲存變更' : '新增' }}</button>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import AdminSidebar from './AdminSidebar.vue'
import { useAdminAuthStore } from '@/stores/adminAuth'

const adminAuth = useAdminAuthStore()
const coupons = ref<any[]>([])
const pagination = ref({ current_page: 1, last_page: 1, total: 0, per_page: 12 })
const totalPages = computed(() => pagination.value.last_page)
const search = ref('')
const showModal = ref(false)
const editingCoupon = ref<any>(null)
const form = ref({ 
  name: '', 
  code: '', 
  type: 'fixed', 
  value: '', 
  min_order: '', 
  usage_limit: '', 
  description: '', 
  expires_at: '', 
  is_active: true 
})

const fetchCoupons = async (page = 1) => {
  try {
    let url = `http://127.0.0.1:8000/api/v1/admin/coupons?page=${page}`
    if (search.value) url += `&search=${encodeURIComponent(search.value)}`
    const res = await axios.get(url)
    const pageData = res.data.data
    coupons.value = pageData.data || []
    pagination.value = {
      current_page: pageData.current_page,
      last_page: pageData.last_page,
      total: pageData.total,
      per_page: pageData.per_page
    }
  } catch (error) {
    console.error('獲取優惠券失敗:', error)
    alert('獲取優惠券失敗')
  }
}

const openAddModal = () => {
  editingCoupon.value = null
  form.value = { 
    name: '', 
    code: '', 
    type: 'fixed', 
    value: '', 
    min_order: '', 
    usage_limit: '', 
    description: '', 
    expires_at: '', 
    is_active: true 
  }
  showModal.value = true
}

const openEditModal = (coupon: any) => {
  editingCoupon.value = coupon
  form.value = { ...coupon }
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
}

const submitCoupon = async () => {
  if (!form.value.name || !form.value.code || !form.value.type || !form.value.value) {
    alert('請填寫完整資料')
    return
  }
  try {
    if (editingCoupon.value) {
      await axios.put(`http://127.0.0.1:8000/api/v1/admin/coupons/${editingCoupon.value.id}`, form.value)
      alert('優惠券已更新')
    } else {
      await axios.post('http://127.0.0.1:8000/api/v1/admin/coupons', form.value)
      alert('優惠券已新增')
    }
    showModal.value = false
    await fetchCoupons(pagination.value.current_page)
  } catch (error: any) {
    console.error('操作失敗:', error)
    alert('操作失敗：' + (error.response?.data?.message || error.message))
  }
}

const deleteCoupon = async (id: number) => {
  if (!confirm('確定要刪除這個優惠券嗎？')) return
  try {
    await axios.delete(`http://127.0.0.1:8000/api/v1/admin/coupons/${id}`)
    alert('優惠券已刪除')
    await fetchCoupons(pagination.value.current_page)
  } catch (error: any) {
    console.error('刪除失敗:', error)
    alert('刪除失敗：' + (error.response?.data?.message || error.message))
  }
}

const toggleStatus = async (coupon: any) => {
  try {
    await axios.put(`http://127.0.0.1:8000/api/v1/admin/coupons/${coupon.id}`, { 
      ...coupon, 
      is_active: !coupon.is_active 
    })
    await fetchCoupons(pagination.value.current_page)
  } catch (error: any) {
    console.error('狀態更新失敗:', error)
    alert('狀態更新失敗：' + (error.response?.data?.message || error.message))
  }
}

// 計算剩餘數量文字
const getRemainingText = (coupon: any) => {
  if (!coupon.usage_limit) {
    return '無限制'
  }
  const remaining = coupon.usage_limit - (coupon.used_count || 0)
  return remaining > 0 ? remaining : 0
}

// 取得剩餘數量的 CSS 類別
const getRemainingClass = (coupon: any) => {
  if (!coupon.usage_limit) {
    return 'text-gray-500'
  }
  const remaining = coupon.usage_limit - (coupon.used_count || 0)
  if (remaining <= 0) {
    return 'text-red-600 font-semibold'
  } else if (remaining <= 5) {
    return 'text-orange-600 font-semibold'
  } else {
    return 'text-green-600'
  }
}

onMounted(async () => {
  await adminAuth.initAuth()
  if (!adminAuth.isAuthenticated) {
    alert('請先登入管理員帳號')
    return
  }
  fetchCoupons(1)
})
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