<template>
  <div class="flex min-h-screen">
    <AdminSidebar />
    <main class="flex-1 p-10 bg-gray-50">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <h1 class="text-2xl font-bold">會員管理</h1>
        <div class="flex gap-2 items-center">
          <input v-model="search" @keyup.enter="fetchMembers(1)" type="text" placeholder="搜尋名稱/Email/電話..." class="input-sm" />
          <select v-model="statusFilter" @change="fetchMembers(1)" class="input-sm">
            <option value="">全部狀態</option>
            <option value="active">啟用</option>
            <option value="inactive">停用</option>
          </select>
          <select v-model="levelFilter" @change="fetchMembers(1)" class="input-sm">
            <option value="">全部等級</option>
            <option value="bronze">銅牌會員</option>
            <option value="silver">銀牌會員</option>
            <option value="gold">金牌會員</option>
            <option value="platinum">白金會員</option>
          </select>
          <button class="btn-admin-sm" @click="fetchMembers(1)">搜尋</button>
          <button class="btn-admin-sm bg-green-600 hover:bg-green-700" @click="exportMembers">匯出</button>
        </div>
      </div>
      
      <div class="bg-white rounded shadow p-6">
        <table class="w-full text-left">
          <thead>
            <tr class="border-b">
              <th class="py-3 px-2">名稱</th>
              <th class="py-3 px-2">Email</th>
              <th class="py-3 px-2">電話</th>
              <th class="py-3 px-2">等級</th>
              <th class="py-3 px-2">點數</th>
              <th class="py-3 px-2">訂單數</th>
              <th class="py-3 px-2">總消費</th>
              <th class="py-3 px-2">狀態</th>
              <th class="py-3 px-2">註冊時間</th>
              <th class="py-3 px-2">操作</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="member in members" :key="member.id" class="border-b hover:bg-gray-50">
              <td class="py-3 px-2">{{ member.name }}</td>
              <td class="py-3 px-2">{{ member.email }}</td>
              <td class="py-3 px-2">{{ member.phone || '-' }}</td>
              <td class="py-3 px-2">
                <span :class="member.member_level_color">
                  {{ member.member_level_name }}
                </span>
              </td>
              <td class="py-3 px-2">{{ member.points }}</td>
              <td class="py-3 px-2">{{ member.total_orders }}</td>
              <td class="py-3 px-2">${{ member.total_spent || 0 }}</td>
              <td class="py-3 px-2">
                <span :class="member.is_active ? 'text-green-600' : 'text-gray-400'">
                  {{ member.is_active ? '啟用' : '停用' }}
                </span>
              </td>
              <td class="py-3 px-2">{{ formatDate(member.created_at) }}</td>
              <td class="py-3 px-2">
                <button class="text-blue-600 hover:underline mr-2" @click="viewMember(member)">檢視</button>
                <button class="text-green-600 hover:underline" @click="editMember(member)">編輯</button>
              </td>
            </tr>
          </tbody>
        </table>
        <div v-if="members.length === 0" class="text-center text-gray-400 py-8">尚無會員</div>
        
        <!-- 分頁 -->
        <div v-if="pagination.total > pagination.per_page" class="flex justify-center mt-6 gap-2">
          <button
            v-for="page in totalPages"
            :key="page"
            @click="fetchMembers(page)"
            :class="['px-3 py-1 rounded', page === pagination.current_page ? 'bg-amber-600 text-white' : 'bg-gray-100 hover:bg-amber-100']"
          >
            {{ page }}
          </button>
        </div>
      </div>

      <!-- 會員詳情 Modal -->
      <div v-if="showMemberModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">會員詳情</h2>
            <button @click="showMemberModal = false" class="text-gray-500 hover:text-gray-700">✕</button>
          </div>
          
          <div v-if="selectedMember" class="space-y-6">
            <!-- 基本資料 -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <h3 class="font-semibold mb-2">基本資料</h3>
                <div class="space-y-2 text-sm">
                  <div><span class="font-medium">姓名：</span>{{ selectedMember.name }}</div>
                  <div><span class="font-medium">Email：</span>{{ selectedMember.email }}</div>
                  <div><span class="font-medium">電話：</span>{{ selectedMember.phone || '-' }}</div>
                  <div><span class="font-medium">地址：</span>{{ selectedMember.address || '-' }}</div>
                  <div><span class="font-medium">生日：</span>{{ formatDateOnly(selectedMember.birthday) || '-' }}</div>
                  <div><span class="font-medium">性別：</span>{{ genderText(selectedMember.gender) || '-' }}</div>
                </div>
              </div>
              <div>
                <h3 class="font-semibold mb-2">會員統計</h3>
                <div class="space-y-2 text-sm">
                  <div><span class="font-medium">會員等級：</span>
                    <span :class="selectedMember.statistics?.member_level_color">
                      {{ selectedMember.statistics?.member_level_name || '-' }}
                    </span>
                  </div>
                  <div><span class="font-medium">目前點數：</span>{{ selectedMember.statistics?.current_points }}</div>
                  <div><span class="font-medium">累積已用點數：</span>{{ selectedMember.total_used_points || 0 }}</div>
                  <div><span class="font-medium">總訂單數：</span>{{ selectedMember.statistics?.total_orders }}</div>
                  <div><span class="font-medium">總消費金額：</span>${{ selectedMember.statistics?.total_spent || 0 }}</div>
                  <div><span class="font-medium">平均訂單金額：</span>${{ selectedMember.statistics?.average_order_value || 0 }}</div>
                  <div><span class="font-medium">最後登入：</span>{{ formatDateTime(selectedMember.statistics?.last_login) || '-' }}</div>
                </div>
              </div>
            </div>

            <!-- 最近訂單 -->
            <div v-if="selectedMember.orders && selectedMember.orders.length > 0">
              <h3 class="font-semibold mb-2">最近訂單</h3>
              <div class="overflow-x-auto">
                <table class="w-full text-sm">
                  <thead>
                    <tr class="border-b">
                      <th class="py-2 px-2 text-left">訂單編號</th>
                      <th class="py-2 px-2 text-left">狀態</th>
                      <th class="py-2 px-2 text-left">金額</th>
                      <th class="py-2 px-2 text-left">日期</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="order in selectedMember.orders.slice(0, 5)" :key="order.id" class="border-b">
                      <td class="py-2 px-2">{{ order.id }}</td>
                      <td class="py-2 px-2">{{ order.status }}</td>
                      <td class="py-2 px-2">${{ order.final_amount !== undefined ? order.final_amount : '-' }}</td>
                      <td class="py-2 px-2">{{ formatDate(order.created_at) }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- 點數交易記錄 -->
            <div v-if="selectedMember.point_transactions && selectedMember.point_transactions.length > 0">
              <h3 class="font-semibold mb-2">點數交易記錄</h3>
              <div class="overflow-x-auto">
                <table class="w-full text-sm">
                  <thead>
                    <tr class="border-b">
                      <th class="py-2 px-2 text-left">類型</th>
                      <th class="py-2 px-2 text-left">點數</th>
                      <th class="py-2 px-2 text-left">說明</th>
                      <th class="py-2 px-2 text-left">日期</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="transaction in pagedPointTransactions" :key="transaction.id" class="border-b">
                      <td class="py-2 px-2">{{ transaction.type }}</td>
                      <td class="py-2 px-2">{{ transaction.points }}</td>
                      <td class="py-2 px-2">{{ transaction.description }}</td>
                      <td class="py-2 px-2">{{ formatDate(transaction.created_at) }}</td>
                    </tr>
                  </tbody>
                </table>
                <!-- 分頁按鈕 -->
                <div v-if="totalPointsPages > 1" class="flex justify-center mt-2 gap-2">
                  <button
                    class="px-3 py-1 rounded border"
                    :disabled="currentPointsPage === 1"
                    @click="goToPointsPage(currentPointsPage - 1)"
                  >上一頁</button>
                  <span>第 {{ currentPointsPage }} 頁 / 共 {{ totalPointsPages }} 頁</span>
                  <button
                    class="px-3 py-1 rounded border"
                    :disabled="currentPointsPage === totalPointsPages"
                    @click="goToPointsPage(currentPointsPage + 1)"
                  >下一頁</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- 編輯會員 Modal -->
      <div v-if="showEditModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-2xl w-full mx-4">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">編輯會員資料</h2>
            <button @click="showEditModal = false" class="text-gray-500 hover:text-gray-700">✕</button>
          </div>
          
          <form @submit.prevent="updateMember" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium mb-1">姓名</label>
                <input v-model="editForm.name" type="text" class="input-sm w-full" required />
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">電話</label>
                <input v-model="editForm.phone" type="text" class="input-sm w-full" />
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">地址</label>
                <input v-model="editForm.address" type="text" class="input-sm w-full" />
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">生日</label>
                <input v-model="editForm.birthday" type="date" class="input-sm w-full" />
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">性別</label>
                <select v-model="editForm.gender" class="input-sm w-full">
                  <option value="">請選擇</option>
                  <option value="male">男性</option>
                  <option value="female">女性</option>
                  <option value="other">其他</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">會員等級</label>
                <select v-model="editForm.member_level" class="input-sm w-full">
                  <option value="bronze">銅牌會員</option>
                  <option value="silver">銀牌會員</option>
                  <option value="gold">金牌會員</option>
                  <option value="platinum">白金會員</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">點數</label>
                <input v-model="editForm.points" type="number" min="0" class="input-sm w-full" />
              </div>
            </div>
            
            <div class="flex gap-2 justify-end">
              <button type="button" @click="showEditModal = false" class="btn-admin-sm bg-gray-500 hover:bg-gray-600">取消</button>
              <button type="submit" class="btn-admin-sm">更新</button>
            </div>
          </form>
        </div>
      </div>

      <!-- 調整點數 Modal -->
      <div v-if="showPointsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">調整點數</h2>
            <button @click="showPointsModal = false" class="text-gray-500 hover:text-gray-700">✕</button>
          </div>
          
          <form @submit.prevent="adjustPoints" class="space-y-4">
            <div>
              <label class="block text-sm font-medium mb-1">調整類型</label>
              <select v-model="pointsForm.type" class="input-sm w-full" required>
                <option value="add">增加點數</option>
                <option value="subtract">扣除點數</option>
                <option value="set">設定點數</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">點數</label>
              <input v-model="pointsForm.points" type="number" min="0" class="input-sm w-full" required />
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">調整原因</label>
              <input v-model="pointsForm.reason" type="text" class="input-sm w-full" required />
            </div>
            
            <div class="flex gap-2 justify-end">
              <button type="button" @click="showPointsModal = false" class="btn-admin-sm bg-gray-500 hover:bg-gray-600">取消</button>
              <button type="submit" class="btn-admin-sm">調整</button>
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

const members = ref<any[]>([])
const pagination = ref({ current_page: 1, last_page: 1, total: 0, per_page: 15 })
const totalPages = computed(() => pagination.value.last_page)
const search = ref('')
const statusFilter = ref('')
const levelFilter = ref('')

// Modal 狀態
const showMemberModal = ref(false)
const showEditModal = ref(false)
const showPointsModal = ref(false)
const selectedMember = ref<any>(null)
const editForm = ref({
  name: '',
  phone: '',
  address: '',
  birthday: '',
  gender: '',
  member_level: 'bronze',
  points: 0
})
const pointsForm = ref({
  type: 'add',
  points: 0,
  reason: ''
})

// 新增：點數交易分頁狀態
const currentPointsPage = ref(1)
const pointsPerPage = 5
const pagedPointTransactions = computed(() => {
  if (!selectedMember.value?.point_transactions) return []
  const start = (currentPointsPage.value - 1) * pointsPerPage
  return selectedMember.value.point_transactions.slice(start, start + pointsPerPage)
})
const totalPointsPages = computed(() => {
  if (!selectedMember.value?.point_transactions) return 1
  return Math.ceil(selectedMember.value.point_transactions.length / pointsPerPage) || 1
})
function goToPointsPage(page: number) {
  if (page < 1 || page > totalPointsPages.value) return
  currentPointsPage.value = page
}

const fetchMembers = async (page = 1) => {
  try {
    let url = `/api/v1/admin/members?page=${page}`
    if (search.value) url += `&search=${encodeURIComponent(search.value)}`
    if (statusFilter.value) url += `&status=${statusFilter.value}`
    if (levelFilter.value) url += `&level=${levelFilter.value}`
    
    const res = await axios.get(url)
    if (res.data.success) {
      members.value = res.data.data.data || []
      pagination.value = {
        current_page: res.data.data.current_page,
        last_page: res.data.data.last_page,
        total: res.data.data.total,
        per_page: res.data.data.per_page
      }
    }
  } catch (error) {
    console.error('載入會員資料失敗:', error)
  }
}

const viewMember = async (member: any) => {
  try {
    const res = await axios.get(`/api/v1/admin/members/${member.id}`)
    if (res.data.success) {
      selectedMember.value = res.data.member
      showMemberModal.value = true
    }
  } catch (error) {
    console.error('載入會員詳情失敗:', error)
  }
}

const editMember = (member: any) => {
  editForm.value = {
    name: member.name,
    phone: member.phone || '',
    address: member.address || '',
    birthday: member.birthday || '',
    gender: member.gender || '',
    member_level: member.member_level || 'bronze',
    points: member.points || 0
  }
  selectedMember.value = member
  showEditModal.value = true
}

const updateMember = async () => {
  try {
    const res = await axios.put(`/api/v1/admin/members/${selectedMember.value.id}`, editForm.value)
    if (res.data.success) {
      alert('會員資料更新成功')
      showEditModal.value = false
      fetchMembers(pagination.value.current_page)
    }
  } catch (error: any) {
    alert('更新失敗：' + (error.response?.data?.message || error.message))
  }
}

const adjustPoints = async () => {
  try {
    const res = await axios.post(`/api/v1/admin/members/${selectedMember.value.id}/points`, pointsForm.value)
    if (res.data.success) {
      alert('點數調整成功')
      showPointsModal.value = false
      fetchMembers(pagination.value.current_page)
      // 重新載入會員詳情
      if (showMemberModal.value) {
        await viewMember(selectedMember.value)
      }
    }
  } catch (error: any) {
    alert('點數調整失敗：' + (error.response?.data?.message || error.message))
  }
}

const exportMembers = async () => {
  try {
    let url = '/api/v1/admin/members/export'
    const params = new URLSearchParams()
    if (search.value) params.append('search', search.value)
    if (statusFilter.value) params.append('status', statusFilter.value)
    if (levelFilter.value) params.append('level', levelFilter.value)
    
    if (params.toString()) {
      url += '?' + params.toString()
    }
    
    const res = await axios.get(url)
    if (res.data.success) {
      // 下載檔案
      const link = document.createElement('a')
      link.href = res.data.download_url
      link.download = `members_${new Date().toISOString().slice(0, 10)}.csv`
      document.body.appendChild(link)
      link.click()
      document.body.removeChild(link)
      alert('會員資料匯出成功')
    }
  } catch (error: any) {
    console.error('匯出失敗:', error)
    alert('匯出失敗：' + (error.response?.data?.message || error.message))
  }
}

const formatDate = (dateString: string) => {
  if (!dateString) return '-'
  return new Date(dateString).toLocaleString('zh-TW')
}

// 新增格式化函數
const formatDateOnly = (dateString: string) => {
  if (!dateString) return ''
  const d = new Date(dateString)
  if (isNaN(d.getTime())) return dateString
  return d.toISOString().slice(0, 10)
}
const genderText = (gender: string) => {
  if (gender === 'male') return '男'
  if (gender === 'female') return '女'
  if (gender === 'other') return '其他'
  return ''
}

// 新增格式化時間函數
const formatDateTime = (dateString: string) => {
  if (!dateString) return '-'
  const d = new Date(dateString)
  if (isNaN(d.getTime())) return dateString
  return d.toLocaleString('zh-TW', { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit' })
}

onMounted(() => fetchMembers(1))
</script>

<style scoped>
.input-sm {
  @apply w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-amber-500;
}
.btn-admin-sm {
  @apply bg-amber-600 text-white font-semibold py-1 px-3 text-sm rounded-md shadow hover:bg-amber-700 transition-colors whitespace-nowrap;
}
</style> 