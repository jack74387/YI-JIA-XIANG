<template>
  <div class="flex min-h-screen">
    <AdminSidebar />
    <main class="flex-1 p-10 bg-gray-50">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <h1 class="text-2xl font-bold">管理員管理</h1>
        <button class="btn-admin-sm" @click="showAddModal = true">新增管理員</button>
      </div>
      <div class="bg-white rounded shadow p-6">
        <table class="w-full text-left">
          <thead>
            <tr>
              <th class="py-2">名稱</th>
              <th class="py-2">Email</th>
              <th class="py-2">建立時間</th>
              <th class="py-2">操作</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="admin in admins" :key="admin.id">
              <td class="py-2">{{ admin.name }}</td>
              <td class="py-2">{{ admin.email }}</td>
              <td class="py-2">{{ admin.created_at ? admin.created_at.slice(0, 19).replace('T', ' ') : '-' }}</td>
              <td class="py-2">
                <button class="text-blue-600 hover:underline mr-2" @click="editAdmin(admin)">編輯</button>
                <button class="text-red-600 hover:underline" @click="deleteAdmin(admin)" :disabled="admin.id === 1">刪除</button>
              </td>
            </tr>
          </tbody>
        </table>
        <div v-if="admins.length === 0" class="text-center text-gray-400 py-8">尚無管理員</div>
      </div>

      <!-- 新增管理員 Modal -->
      <div v-if="showAddModal" class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-container">
            <h3 class="text-lg font-bold mb-4">新增管理員</h3>
            <form @submit.prevent="addAdmin">
              <input v-model="addForm.name" type="text" placeholder="名稱" class="input mb-2" required />
              <input v-model="addForm.email" type="email" placeholder="Email" class="input mb-2" required />
              <input v-model="addForm.password" type="password" placeholder="密碼" class="input mb-2" required />
              <input v-model="addForm.password_confirmation" type="password" placeholder="確認密碼" class="input mb-4" required />
              <div class="flex gap-2">
                <button type="submit" class="btn-admin-sm" :disabled="loading">{{ loading ? '新增中...' : '新增' }}</button>
                <button type="button" class="btn-admin-sm bg-gray-300 text-gray-700" @click="showAddModal = false">取消</button>
              </div>
              <p v-if="error" class="text-red-500 mt-2">{{ error }}</p>
            </form>
          </div>
        </div>
      </div>

      <!-- 編輯管理員 Modal -->
      <div v-if="showEditModal" class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-container">
            <h3 class="text-lg font-bold mb-4">編輯管理員</h3>
            <form @submit.prevent="updateAdmin">
              <input v-model="editForm.name" type="text" placeholder="名稱" class="input mb-2" required />
              <input v-model="editForm.email" type="email" placeholder="Email" class="input mb-2" required />
              <input v-model="editForm.password" type="password" placeholder="新密碼（可留空）" class="input mb-2" />
              <input v-model="editForm.password_confirmation" type="password" placeholder="確認新密碼" class="input mb-4" />
              <div class="flex gap-2">
                <button type="submit" class="btn-admin-sm" :disabled="loading">{{ loading ? '儲存中...' : '儲存' }}</button>
                <button type="button" class="btn-admin-sm bg-gray-300 text-gray-700" @click="showEditModal = false">取消</button>
              </div>
              <p v-if="error" class="text-red-500 mt-2">{{ error }}</p>
            </form>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'
import AdminSidebar from './AdminSidebar.vue'

const admins = ref<any[]>([])
const showAddModal = ref(false)
const showEditModal = ref(false)
const loading = ref(false)
const error = ref('')
const addForm = ref({ name: '', email: '', password: '', password_confirmation: '' })
const editForm = ref({ id: 0, name: '', email: '', password: '', password_confirmation: '' })

const fetchAdmins = async () => {
  const token = localStorage.getItem('admin_token')
  const res = await axios.get('/api/v1/admins', {
    headers: { Authorization: `Bearer ${token}` }
  })
  admins.value = res.data.admins || []
}

const extractError = (e: any) => {
  if (e.response?.data?.errors) {
    // 聚合所有欄位錯誤
    return Object.values(e.response.data.errors).flat().join(' ')
  }
  return e.response?.data?.message || '操作失敗'
}

const addAdmin = async () => {
  error.value = ''
  loading.value = true
  try {
    const token = localStorage.getItem('admin_token')
    await axios.post('/api/v1/admins', addForm.value, {
      headers: { Authorization: `Bearer ${token}` }
    })
    showAddModal.value = false
    addForm.value = { name: '', email: '', password: '', password_confirmation: '' }
    fetchAdmins()
  } catch (e: any) {
    error.value = extractError(e)
  } finally {
    loading.value = false
  }
}

const editAdmin = (admin: any) => {
  editForm.value = { id: admin.id, name: admin.name, email: admin.email, password: '', password_confirmation: '' }
  showEditModal.value = true
  error.value = ''
}

const updateAdmin = async () => {
  error.value = ''
  loading.value = true
  try {
    const token = localStorage.getItem('admin_token')
    const payload: any = { name: editForm.value.name, email: editForm.value.email }
    if (editForm.value.password) {
      payload.password = editForm.value.password
      payload.password_confirmation = editForm.value.password_confirmation
    }
    await axios.put(`/api/v1/admins/${editForm.value.id}`, payload, {
      headers: { Authorization: `Bearer ${token}` }
    })
    showEditModal.value = false
    fetchAdmins()
  } catch (e: any) {
    error.value = extractError(e)
  } finally {
    loading.value = false
  }
}

const deleteAdmin = async (admin: any) => {
  if (!confirm('確定要刪除此管理員嗎？')) return
  if (admin.id === 1) return
  loading.value = true
  try {
    const token = localStorage.getItem('admin_token')
    await axios.delete(`/api/v1/admins/${admin.id}`, {
      headers: { Authorization: `Bearer ${token}` }
    })
    fetchAdmins()
  } catch (e: any) {
    error.value = extractError(e)
  } finally {
    loading.value = false
  }
}

onMounted(fetchAdmins)
</script>

<style scoped>
.input {
  @apply w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-amber-500 mb-2;
}
.btn-admin-sm {
  @apply bg-amber-600 text-white font-semibold py-1 px-3 text-sm rounded-md shadow hover:bg-amber-700 transition-colors whitespace-nowrap;
}
.modal-mask {
  position: fixed; z-index: 40; inset: 0; background: rgba(0,0,0,0.2); display: flex; align-items: center; justify-content: center;
}
.modal-wrapper { min-width: 320px; }
.modal-container { background: #fff; border-radius: 8px; padding: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1); min-width: 320px; }
</style> 