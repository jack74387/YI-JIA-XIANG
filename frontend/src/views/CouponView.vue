<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- 頁面標題 -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">我的優惠券</h1>
        <p class="mt-2 text-gray-600">查看您的優惠券和使用記錄</p>
      </div>

      <!-- 統計卡片 -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="h-8 w-8 bg-green-100 rounded-full flex items-center justify-center">
                <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-500">可用優惠券</p>
              <p class="text-2xl font-semibold text-gray-900">{{ loading ? '...' : availableCoupons.length }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center">
                <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-500">已使用</p>
              <p class="text-2xl font-semibold text-gray-900">{{ loading ? '...' : usedCoupons.length }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="h-8 w-8 bg-red-100 rounded-full flex items-center justify-center">
                <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-500">已過期</p>
              <p class="text-2xl font-semibold text-gray-900">{{ loading ? '...' : expiredCoupons.length }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="h-8 w-8 bg-purple-100 rounded-full flex items-center justify-center">
                <svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-500">可領取</p>
              <p class="text-2xl font-semibold text-gray-900">{{ loading ? '...' : claimableCoupons.length }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- 標籤頁 -->
      <div class="bg-white rounded-lg shadow">
        <div class="border-b border-gray-200">
          <nav class="-mb-px flex space-x-8 px-6">
            <button
              @click="activeTab = 'claimable'"
              :class="[
                activeTab === 'claimable'
                  ? 'border-amber-500 text-amber-600'
                  : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm'
              ]"
            >
              可領取 ({{ claimableCoupons.length }})
            </button>
            <button
              @click="activeTab = 'available'"
              :class="[
                activeTab === 'available'
                  ? 'border-amber-500 text-amber-600'
                  : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm'
              ]"
            >
              可用優惠券 ({{ availableCoupons.length }})
            </button>
            <button
              @click="activeTab = 'used'"
              :class="[
                activeTab === 'used'
                  ? 'border-amber-500 text-amber-600'
                  : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm'
              ]"
            >
              已使用 ({{ usedCoupons.length }})
            </button>
            <button
              @click="activeTab = 'expired'"
              :class="[
                activeTab === 'expired'
                  ? 'border-amber-500 text-amber-600'
                  : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm'
              ]"
            >
              已過期 ({{ expiredCoupons.length }})
            </button>
          </nav>
        </div>

        <div class="p-6">
          <!-- 可領取優惠券 -->
          <div v-if="activeTab === 'claimable'">
            <div v-if="claimableCoupons.length === 0" class="text-center py-12">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
              </svg>
              <h3 class="mt-2 text-sm font-medium text-gray-900">暫無可領取優惠券</h3>
              <p class="mt-1 text-sm text-gray-500">目前沒有可領取的優惠券，請稍後再來查看</p>
            </div>
            
            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              <div
                v-for="coupon in claimableCoupons"
                :key="coupon.id"
                class="bg-gradient-to-r from-purple-50 to-pink-50 border border-purple-200 rounded-lg p-6 relative overflow-hidden"
              >
                <div class="absolute top-0 right-0 w-16 h-16 bg-purple-100 rounded-full -mr-8 -mt-8"></div>
                <div class="relative">
                  <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ coupon.name }}</h3>
                    <span class="text-2xl font-bold text-purple-600">{{ coupon.type === 'percent' ? coupon.value + '%' : 'NT$' + coupon.value }}</span>
                  </div>
                  <p class="text-sm text-gray-600 mb-4">{{ coupon.description }}</p>
                  <div class="space-y-2 text-sm text-gray-500">
                    <div class="flex items-center">
                      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                      有效期至：{{ formatDate(coupon.expires_at) }}
                    </div>
                    <div class="flex items-center">
                      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                      </svg>
                      最低消費：${{ coupon.min_order || 0 }}
                    </div>
                                         <div class="flex items-center">
                       <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                       </svg>
                       剩餘數量：{{ getRemainingText(coupon) }}
                     </div>
                  </div>
                  <div class="mt-4">
                    <button
                      @click="claimCoupon(coupon)"
                      :disabled="claimingCoupon === coupon.id"
                      class="w-full bg-purple-600 hover:bg-purple-700 disabled:bg-gray-400 text-white py-2 px-4 rounded-md text-sm font-medium transition-colors duration-200"
                    >
                      {{ claimingCoupon === coupon.id ? '領取中...' : '立即領取' }}
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- 可用優惠券 -->
          <div v-if="activeTab === 'available'">
            <div v-if="availableCoupons.length === 0" class="text-center py-12">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
              </svg>
              <h3 class="mt-2 text-sm font-medium text-gray-900">沒有可用優惠券</h3>
              <p class="mt-1 text-sm text-gray-500">您目前沒有可用的優惠券</p>
              <div class="mt-6">
                <button
                  @click="activeTab = 'claimable'"
                  class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-amber-600 hover:bg-amber-700"
                >
                  去領取優惠券
                </button>
              </div>
            </div>
            
            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              <div
                v-for="coupon in availableCoupons"
                :key="coupon.id"
                class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-lg p-6 relative overflow-hidden"
              >
                <div class="absolute top-0 right-0 w-16 h-16 bg-amber-100 rounded-full -mr-8 -mt-8"></div>
                <div class="relative">
                  <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ coupon.name }}</h3>
                    <span class="text-2xl font-bold text-amber-600">{{ coupon.type === 'percent' ? coupon.value + '%' : 'NT$' + coupon.value }}</span>
                  </div>
                  <p class="text-sm text-gray-600 mb-4">{{ coupon.description }}</p>
                  <div class="space-y-2 text-sm text-gray-500">
                    <div class="flex items-center">
                      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                      有效期至：{{ formatDate(coupon.expires_at) }}
                    </div>
                    <div class="flex items-center">
                      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                      </svg>
                      最低消費：${{ coupon.min_order || 0 }}
                    </div>
                  </div>
                  <div class="mt-4">
                    <button
                      @click="useCoupon(coupon)"
                      class="w-full bg-amber-600 hover:bg-amber-700 text-white py-2 px-4 rounded-md text-sm font-medium transition-colors duration-200"
                    >
                      立即使用
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- 已使用優惠券 -->
          <div v-if="activeTab === 'used'">
            <div v-if="usedCoupons.length === 0" class="text-center py-12">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <h3 class="mt-2 text-sm font-medium text-gray-900">沒有使用記錄</h3>
              <p class="mt-1 text-sm text-gray-500">您還沒有使用過任何優惠券</p>
            </div>
            
            <div v-else class="space-y-4">
              <div
                v-for="coupon in usedCoupons"
                :key="coupon.id"
                class="bg-gray-50 border border-gray-200 rounded-lg p-4"
              >
                <div class="flex items-center justify-between">
                  <div>
                    <h3 class="text-lg font-medium text-gray-900">{{ coupon.name }}</h3>
                    <p class="text-sm text-gray-500">使用時間：{{ formatDate(coupon.used_at) }}</p>
                  </div>
                  <div class="text-right">
                    <span class="text-lg font-semibold text-gray-600">-{{ coupon.type === 'percent' ? coupon.value + '%' : 'NT$' + coupon.value }}</span>
                    <p class="text-sm text-gray-500">已使用</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- 已過期優惠券 -->
          <div v-if="activeTab === 'expired'">
            <div v-if="expiredCoupons.length === 0" class="text-center py-12">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <h3 class="mt-2 text-sm font-medium text-gray-900">沒有過期優惠券</h3>
              <p class="mt-1 text-sm text-gray-500">您的優惠券都還在有效期內</p>
            </div>
            
            <div v-else class="space-y-4">
              <div
                v-for="coupon in expiredCoupons"
                :key="coupon.id"
                class="bg-gray-50 border border-gray-200 rounded-lg p-4 opacity-60"
              >
                <div class="flex items-center justify-between">
                  <div>
                    <h3 class="text-lg font-medium text-gray-900">{{ coupon.name }}</h3>
                    <p class="text-sm text-gray-500">過期時間：{{ formatDate(coupon.expires_at) }}</p>
                  </div>
                  <div class="text-right">
                    <span class="text-lg font-semibold text-gray-400">{{ coupon.type === 'percent' ? coupon.value + '%' : 'NT$' + coupon.value }}</span>
                    <p class="text-sm text-gray-400">已過期</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const activeTab = ref('claimable') // 預設顯示可領取標籤頁
const loading = ref(false)
const claimingCoupon = ref<number | null>(null)

// 優惠券資料
const coupons = ref<any[]>([])
const claimableCoupons = ref<any[]>([])

// 獲取用戶優惠券
const fetchUserCoupons = async () => {
  loading.value = true
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/v1/coupons/user')
    if (response.data.success) {
      // 轉換資料格式以符合現有結構
      const available = response.data.data.available.map((item: any) => ({
        ...item.coupon,
        status: 'available',
        claimed_at: item.claimed_at
      }))
      const used = response.data.data.used.map((item: any) => ({
        ...item.coupon,
        status: 'used',
        used_at: item.used_at
      }))
      const expired = response.data.data.expired.map((item: any) => ({
        ...item.coupon,
        status: 'expired',
        claimed_at: item.claimed_at
      }))
      coupons.value = [...available, ...used, ...expired]
    }
  } catch (error) {
    console.error('獲取優惠券失敗:', error)
  } finally {
    loading.value = false
  }
}

// 獲取可領取的優惠券
const fetchClaimableCoupons = async () => {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/v1/coupons/claimable')
    if (response.data.success) {
      claimableCoupons.value = response.data.data.claimable
    }
  } catch (error) {
    console.error('獲取可領取優惠券失敗:', error)
  }
}

// 領取優惠券
const claimCoupon = async (coupon: any) => {
  claimingCoupon.value = coupon.id
  try {
    const response = await axios.post('http://127.0.0.1:8000/api/v1/coupons/claim', {
      coupon_id: coupon.id
    })
    
    if (response.data.success) {
      alert('優惠券領取成功！')
      // 重新獲取資料
      await fetchUserCoupons()
      await fetchClaimableCoupons()
    } else {
      alert(response.data.message || '領取失敗')
    }
  } catch (error: any) {
    console.error('領取優惠券失敗:', error)
    alert(error.response?.data?.message || '領取失敗')
  } finally {
    claimingCoupon.value = null
  }
}

// 計算不同狀態的優惠券
const availableCoupons = computed(() => 
  coupons.value.filter(coupon => coupon.status === 'available')
)

const usedCoupons = computed(() => 
  coupons.value.filter(coupon => coupon.status === 'used')
)

const expiredCoupons = computed(() => 
  coupons.value.filter(coupon => coupon.status === 'expired')
)

// 格式化日期
const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('zh-TW')
}

// 使用優惠券
const useCoupon = (coupon: any) => {
  // 這裡應該呼叫 API 使用優惠券
  console.log('使用優惠券:', coupon)
  router.push('/cart')
}

// 計算剩餘數量文字
const getRemainingText = (coupon: any) => {
  if (!coupon.usage_limit) {
    return '無限制'
  }
  const remaining = coupon.usage_limit - (coupon.used_count || 0)
  return remaining > 0 ? remaining : 0
}

onMounted(() => {
  fetchUserCoupons()
  fetchClaimableCoupons()
})
</script>

<style scoped>
/* 使用 Tailwind CSS */
</style> 