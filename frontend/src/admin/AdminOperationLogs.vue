<template>
  <div class="flex min-h-screen">
    <AdminSidebar />
    <main class="flex-1 p-10 bg-gray-50">
      <h1 class="text-2xl font-bold mb-6">操作日誌</h1>
      <div class="bg-white rounded shadow p-6">
        <table class="w-full text-left">
          <thead>
            <tr>
              <th class="py-2">時間</th>
              <th class="py-2">操作人</th>
              <th class="py-2">動作</th>
              <th class="py-2">IP</th>
              <th class="py-2">User Agent</th>
              <th class="py-2">資料</th>
              <th class="py-2 w-16">操作</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="log in logs.data" :key="log.id">
              <td class="py-2">{{ log.created_at ? log.created_at.replace('T', ' ').slice(0, 19) : '-' }}</td>
              <td class="py-2">{{ log.admin?.name || '-' }}<br><span class="text-xs text-gray-400">{{ log.admin?.email }}</span></td>
              <td class="py-2">{{ log.action }}</td>
              <td class="py-2">{{ log.ip }}</td>
              <td class="py-2 truncate max-w-xs">{{ log.user_agent }}</td>
              <td class="py-2 text-xs text-gray-600">
                <pre class="whitespace-pre-wrap">{{ log.data ? JSON.stringify(log.data, null, 2) : '-' }}</pre>
              </td>
              <td class="py-2 text-center">
                <button @click="deleteLog(log.id)" class="text-red-600 hover:underline text-xs">刪除</button>
              </td>
            </tr>
          </tbody>
        </table>
        <div v-if="logs.data.length === 0" class="text-center text-gray-400 py-8">尚無日誌</div>
        <!-- 分頁按鈕 -->
        <div v-if="logs.last_page > 1" class="flex justify-center mt-6 gap-2">
          <button
            v-for="page in logs.last_page"
            :key="page"
            @click="fetchLogs(page)"
            :class="['px-3 py-1 rounded', page === logs.current_page ? 'bg-amber-600 text-white' : 'bg-gray-100 hover:bg-amber-100']"
          >
            {{ page }}
          </button>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'
import AdminSidebar from './AdminSidebar.vue'

const logs = ref<any>({ data: [], current_page: 1, last_page: 1 })
const fetchLogs = async (page = 1) => {
  const token = localStorage.getItem('admin_token')
  const res = await axios.get(`/api/v1/operation-logs?page=${page}`, {
    headers: { Authorization: `Bearer ${token}` }
  })
  logs.value = res.data.logs
}
onMounted(() => fetchLogs(1))

const deleteLog = async (id: number) => {
  if (!confirm('確定要刪除此操作日誌？')) return
  const token = localStorage.getItem('admin_token')
  try {
    await axios.delete(`/api/v1/operation-logs/${id}`, {
      headers: { Authorization: `Bearer ${token}` }
    })
    await fetchLogs(logs.value.current_page)
    alert('刪除成功')
  } catch (e: any) {
    alert('刪除失敗: ' + (e.response?.data?.message || e.message))
  }
}
</script>

<style scoped>
pre { font-size: 0.85em; background: #f8fafc; padding: 0.5em; border-radius: 4px; }
</style> 