<template>
  <div class="flex min-h-screen">
    <AdminSidebar />
    <main class="flex-1 p-10 bg-gray-50">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <h1 class="text-2xl font-bold">會員管理</h1>
        <div class="flex gap-2 items-center">
          <input v-model="search" @keyup.enter="fetchMembers(1)" type="text" placeholder="搜尋名稱/Email/電話..." class="input-sm" />
          <button class="btn-admin-sm" @click="fetchMembers(1)">搜尋</button>
        </div>
      </div>
      <div class="bg-white rounded shadow p-6">
        <table class="w-full text-left">
          <thead>
            <tr>
              <th class="py-2">名稱</th>
              <th class="py-2">Email</th>
              <th class="py-2">電話</th>
              <th class="py-2">等級</th>
              <th class="py-2">狀態</th>
              <th class="py-2">註冊時間</th>
              <th class="py-2">操作</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="member in members" :key="member.id">
              <td class="py-2">{{ member.name }}</td>
              <td class="py-2">{{ member.email }}</td>
              <td class="py-2">{{ member.phone || '-' }}</td>
              <td class="py-2">{{ member.level || '-' }}</td>
              <td class="py-2">
                <span :class="member.active ? 'text-green-600' : 'text-gray-400'">
                  {{ member.active ? '啟用' : '停用' }}
                </span>
              </td>
              <td class="py-2">{{ member.created_at ? member.created_at.slice(0, 19).replace('T', ' ') : '-' }}</td>
              <td class="py-2">
                <button class="text-blue-600 hover:underline mr-2" @click="viewMember(member)">檢視</button>
              </td>
            </tr>
          </tbody>
        </table>
        <div v-if="members.length === 0" class="text-center text-gray-400 py-8">尚無會員</div>
        <!-- 分頁按鈕 -->
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
    </main>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import AdminSidebar from './AdminSidebar.vue'

const members = ref<any[]>([])
const pagination = ref({ current_page: 1, last_page: 1, total: 0, per_page: 12 })
const totalPages = computed(() => pagination.value.last_page)
const search = ref('')

const fetchMembers = async (page = 1) => {
  let url = `http://127.0.0.1:8000/api/v1/members?page=${page}`
  if (search.value) url += `&search=${encodeURIComponent(search.value)}`
  const res = await axios.get(url)
  const pageData = res.data.data
  members.value = pageData.data || []
  pagination.value = {
    current_page: pageData.current_page,
    last_page: pageData.last_page,
    total: pageData.total,
    per_page: pageData.per_page
  }
}

const viewMember = (member: any) => {
  // 之後實作詳情 modal
  alert('會員詳情功能待實作')
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