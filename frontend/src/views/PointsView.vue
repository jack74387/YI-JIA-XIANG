<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- 頁面標題 -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">我的點數</h1>
        <p class="mt-2 text-gray-600">查看您的點數餘額和使用記錄</p>
      </div>

      <!-- 點數概覽 -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-r from-amber-500 to-orange-500 rounded-lg shadow-lg p-6 text-white">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-amber-100 text-sm font-medium">目前點數</p>
              <p class="text-3xl font-bold">{{ userPoints.current }}</p>
            </div>
            <div class="h-12 w-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="h-8 w-8 bg-green-100 rounded-full flex items-center justify-center">
                <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-500">累積獲得</p>
              <p class="text-2xl font-semibold text-gray-900">{{ userPoints.totalEarned }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="h-8 w-8 bg-red-100 rounded-full flex items-center justify-center">
                <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-500">已使用</p>
              <p class="text-2xl font-semibold text-gray-900">{{ userPoints.totalUsed }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- 點數兌換 -->
      <div class="bg-white rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
          <h2 class="text-lg font-medium text-gray-900">點數兌換</h2>
        </div>
        <div class="p-6">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div
              v-for="reward in rewards"
              :key="reward.id"
              class="border border-gray-200 rounded-lg p-4 hover:border-amber-300 transition-colors duration-200"
            >
              <div class="text-center">
                <div class="h-16 w-16 mx-auto mb-4 bg-amber-100 rounded-full flex items-center justify-center">
                  <svg class="h-8 w-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                  </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ reward.name }}</h3>
                <p class="text-sm text-gray-600 mb-4">{{ reward.description }}</p>
                <div class="flex items-center justify-center space-x-2 mb-4">
                  <span class="text-2xl font-bold text-amber-600">{{ reward.points }}</span>
                  <span class="text-sm text-gray-500">點</span>
                </div>
                <button
                  @click="redeemReward(reward)"
                  :disabled="userPoints.current < reward.points"
                  class="w-full bg-amber-600 hover:bg-amber-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white py-2 px-4 rounded-md text-sm font-medium transition-colors duration-200"
                >
                  {{ userPoints.current >= reward.points ? '立即兌換' : '點數不足' }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- 點數歷史 -->
      <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
          <h2 class="text-lg font-medium text-gray-900">點數歷史</h2>
        </div>
        
        <div v-if="pointsHistory.length === 0" class="text-center py-12">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
          </svg>
          <h3 class="mt-2 text-sm font-medium text-gray-900">還沒有點數記錄</h3>
          <p class="mt-1 text-sm text-gray-500">開始購物來獲得點數</p>
        </div>

        <div v-else class="divide-y divide-gray-200">
          <div
            v-for="record in pointsHistory"
            :key="record.id"
            class="px-6 py-4 hover:bg-gray-50 transition-colors duration-200"
          >
            <div class="flex items-center justify-between">
              <div class="flex items-center">
                <div
                  :class="[
                    'h-8 w-8 rounded-full flex items-center justify-center',
                    record.type === 'earn' ? 'bg-green-100' : 'bg-red-100'
                  ]"
                >
                  <svg
                    :class="[
                      'h-4 w-4',
                      record.type === 'earn' ? 'text-green-600' : 'text-red-600'
                    ]"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      v-if="record.type === 'earn'"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                    />
                    <path
                      v-else
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M20 12H4"
                    />
                  </svg>
                </div>
                <div class="ml-4">
                  <h3 class="text-sm font-medium text-gray-900">{{ record.description }}</h3>
                  <p class="text-sm text-gray-500">{{ formatDate(record.created_at) }}</p>
                </div>
              </div>
              <div class="text-right">
                <span
                  :class="[
                    'text-sm font-medium',
                    record.type === 'earn' ? 'text-green-600' : 'text-red-600'
                  ]"
                >
                  {{ record.type === 'earn' ? '+' : '-' }}{{ record.points }}
                </span>
                <!-- <p class="text-xs text-gray-500">餘額：{{ record.balance !== undefined ? record.balance : '-' }}</p> -->
              </div>
            </div>
          </div>
        </div>
        <!-- 分頁按鈕 -->
        <div v-if="pointsPagination.last_page > 1" class="flex justify-center items-center gap-2 py-4">
          <button
            class="px-3 py-1 rounded border border-gray-300 bg-white hover:bg-gray-100"
            :disabled="currentPage === 1"
            @click="goToPage(currentPage - 1)"
          >上一頁</button>
          <span>第 {{ pointsPagination.current_page }} / {{ pointsPagination.last_page }} 頁</span>
          <button
            class="px-3 py-1 rounded border border-gray-300 bg-white hover:bg-gray-100"
            :disabled="currentPage === pointsPagination.last_page"
            @click="goToPage(currentPage + 1)"
          >下一頁</button>
        </div>
      </div>

      <!-- 點數說明 -->
      <div class="mt-8 bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">點數說明</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <h4 class="font-medium text-gray-900 mb-2">如何獲得點數</h4>
            <ul class="space-y-2 text-sm text-gray-600">
              <li class="flex items-start">
                <span class="text-green-500 mr-2">•</span>
                購物消費每100元獲得1點
              </li>
              <li class="flex items-start">
                <span class="text-green-500 mr-2">•</span>
                完成訂單評價獲得5點
              </li>
              <li class="flex items-start">
                <span class="text-green-500 mr-2">•</span>
                生日當月購物享雙倍點數
              </li>
            </ul>
          </div>
          <div>
            <h4 class="font-medium text-gray-900 mb-2">點數使用規則</h4>
            <ul class="space-y-2 text-sm text-gray-600">
              <li class="flex items-start">
                <span class="text-amber-500 mr-2">•</span>
                1點可折抵1元購物金
              </li>
              <li class="flex items-start">
                <span class="text-amber-500 mr-2">•</span>
                單筆訂單最多可折抵50%
              </li>
              <li class="flex items-start">
                <span class="text-amber-500 mr-2">•</span>
                點數有效期限為1年
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted, computed, ref } from 'vue'
import { useMemberStore } from '@/stores/member'

const memberStore = useMemberStore()
const pageSize = 10
const currentPage = ref(1)

onMounted(() => {
  memberStore.fetchPoints(currentPage.value, pageSize)
})

const userPoints = computed(() => memberStore.userPoints)
const pointsHistory = computed(() => memberStore.pointsHistory)
const pointsPagination = computed(() => memberStore.pointsPagination)

const goToPage = (page: number) => {
  if (page < 1 || page > pointsPagination.value.last_page) return
  currentPage.value = page
  memberStore.fetchPoints(page, pageSize)
}

// 兌換獎勵（如需串接API可再調整）
const rewards = [
  {
    id: 1,
    name: '購物金折抵券',
    description: '可折抵100元購物金',
    points: 100
  },
  {
    id: 2,
    name: '免運費券',
    description: '單筆訂單免運費',
    points: 200
  },
  {
    id: 3,
    name: '9折優惠券',
    description: '全館商品9折優惠',
    points: 500
  },
  {
    id: 4,
    name: '生日禮券',
    description: '生日當月專屬優惠',
    points: 1000
  }
]

// 格式化日期
const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('zh-TW', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const redeemReward = (reward: any) => {
  alert(`成功兌換 ${reward.name}！`)
}
</script>

<style scoped>
/* 使用 Tailwind CSS */
</style> 