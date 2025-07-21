<template>
  <div class="flex min-h-screen">
    <AdminSidebar />
    <main class="flex-1 p-10 bg-gray-50">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <h1 class="text-2xl font-bold">門市管理</h1>
        <button class="btn-admin" @click="showAddModal = true">新增門市</button>
      </div>
      
      <div class="bg-white rounded shadow p-6">
        <table class="w-full text-left">
          <thead>
            <tr>
              <th class="py-2">ID</th>
              <th class="py-2">門市名稱</th>
              <th class="py-2">地址</th>
              <th class="py-2">電話</th>
              <th class="py-2">營業時間</th>
              <th class="py-2">狀態</th>
              <th class="py-2">排序</th>
              <th class="py-2">操作</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="store in stores" :key="store.id">
              <td class="py-2">{{ store.id }}</td>
              <td class="py-2">{{ store.name }}</td>
              <td class="py-2">{{ store.address }}</td>
              <td class="py-2">{{ store.phone }}</td>
              <td class="py-2">{{ store.hours || '-' }}</td>
              <td class="py-2">
                <span :class="store.is_active ? 'text-green-600' : 'text-red-600'">
                  {{ store.is_active ? '啟用' : '停用' }}
                </span>
              </td>
              <td class="py-2">{{ store.sort_order }}</td>
              <td class="py-2">
                <button class="text-blue-600 hover:underline mr-2" @click="editStore(store)">編輯</button>
                <button class="text-red-600 hover:underline" @click="deleteStore(store.id)">刪除</button>
              </td>
            </tr>
          </tbody>
        </table>
        <div v-if="stores.length === 0" class="text-center text-gray-400 py-8">尚無門市資料</div>
      </div>

      <!-- 新增/編輯門市 Modal -->
      <div v-if="showAddModal || showEditModal" class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-2xl relative max-h-[90vh] overflow-y-auto">
          <h2 class="text-lg font-bold mb-4">{{ showEditModal ? '編輯門市' : '新增門市' }}</h2>
          
          <form @submit.prevent="submitForm">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">門市名稱 *</label>
                <input v-model="form.name" type="text" required class="input-field" />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">電話 *</label>
                <input v-model="form.phone" type="text" required class="input-field" />
              </div>
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">地址 *</label>
                <input v-model="form.address" type="text" required class="input-field" />
              </div>
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">營業時間</label>
                <input v-model="form.hours" type="text" class="input-field" placeholder="例：週一至週日 09:00-21:00" />
              </div>
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Google Maps 嵌入連結</label>
                <input v-model="form.map" type="url" class="input-field" placeholder="https://www.google.com/maps/embed?..." />
              </div>
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Google Maps 導航連結</label>
                <input v-model="form.map_link" type="url" class="input-field" placeholder="https://www.google.com/maps/search/?..." />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">排序順序</label>
                <input v-model.number="form.sort_order" type="number" min="0" class="input-field" />
              </div>
              <div>
                <label class="flex items-center">
                  <input v-model="form.is_active" type="checkbox" class="mr-2" />
                  <span class="text-sm font-medium text-gray-700">啟用</span>
                </label>
              </div>
            </div>
            
            <div class="flex justify-end mt-6 gap-2">
              <button type="button" class="btn-cancel" @click="closeModal">取消</button>
              <button type="submit" class="btn-admin" :disabled="submitting">
                {{ submitting ? '儲存中...' : '儲存' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'
import AdminSidebar from './AdminSidebar.vue'

const stores = ref<any[]>([])
const showAddModal = ref(false)
const showEditModal = ref(false)
const submitting = ref(false)
const editingStore = ref<any>(null)

const form = ref({
  name: '',
  address: '',
  phone: '',
  hours: '',
  map: '',
  map_link: '',
  is_active: true,
  sort_order: 0
})

const fetchStores = async () => {
  try {
    const token = localStorage.getItem('admin_token')
    const res = await axios.get('/api/v1/admin/stores', { 
      headers: { Authorization: `Bearer ${token}` } 
    })
    stores.value = res.data.stores
  } catch (e) {
    alert('載入門市資料失敗')
  }
}

const editStore = (store: any) => {
  editingStore.value = store
  form.value = { ...store }
  showEditModal.value = true
}

const closeModal = () => {
  showAddModal.value = false
  showEditModal.value = false
  editingStore.value = null
  form.value = {
    name: '',
    address: '',
    phone: '',
    hours: '',
    map: '',
    map_link: '',
    is_active: true,
    sort_order: 0
  }
}

const submitForm = async () => {
  submitting.value = true
  try {
    const token = localStorage.getItem('admin_token')
    const headers = { Authorization: `Bearer ${token}` }
    
    if (showEditModal.value && editingStore.value) {
      await axios.put(`/api/v1/admin/stores/${editingStore.value.id}`, form.value, { headers })
      alert('門市更新成功')
    } else {
      await axios.post('/api/v1/admin/stores', form.value, { headers })
      alert('門市建立成功')
    }
    
    closeModal()
    await fetchStores()
  } catch (e: any) {
    alert('操作失敗：' + (e.response?.data?.message || e.message))
  } finally {
    submitting.value = false
  }
}

const deleteStore = async (id: number) => {
  if (!confirm('確定要刪除此門市嗎？')) return
  
  try {
    const token = localStorage.getItem('admin_token')
    await axios.delete(`/api/v1/admin/stores/${id}`, { 
      headers: { Authorization: `Bearer ${token}` } 
    })
    alert('門市刪除成功')
    await fetchStores()
  } catch (e: any) {
    alert('刪除失敗：' + (e.response?.data?.message || e.message))
  }
}

onMounted(() => fetchStores())
</script>

<style scoped>
.input-field {
  @apply w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500;
}
.btn-admin {
  @apply bg-amber-600 text-white font-semibold py-2 px-4 rounded-md shadow hover:bg-amber-700 transition-colors;
}
.btn-cancel {
  @apply bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded hover:bg-gray-400 transition-colors;
}
</style> 