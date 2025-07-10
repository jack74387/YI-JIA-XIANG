<template>
  <div class="checkout-page">
    <div class="max-w-6xl mx-auto py-8 px-4">
      <!-- 頁面標題 -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">結帳</h1>
        <p class="text-gray-600 mt-2">請確認您的訂單資訊</p>
      </div>

      <!-- 載入中 -->
      <div v-if="loading" class="text-center py-12">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
        <p class="mt-2 text-gray-600">載入中...</p>
      </div>

      <!-- 購物車為空 -->
      <div v-else-if="cart.items.length === 0" class="text-center py-16">
        <div class="text-6xl mb-4">🛒</div>
        <h3 class="text-xl font-semibold mb-2">購物車是空的</h3>
        <p class="text-gray-600 mb-6">請先選擇商品加入購物車</p>
        <router-link to="/products" class="btn-primary">前往購物</router-link>
      </div>

      <!-- 結帳表單 -->
      <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- 左側：商品清單 -->
        <div class="lg:col-span-2">
          <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">商品清單</h2>
            <div class="space-y-4">
              <div v-for="item in cart.items" :key="item.id" class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                <img :src="item.image || '/images/placeholder.jpg'" :alt="item.name" class="w-16 h-16 object-cover rounded" />
                <div class="flex-1">
                  <h3 class="font-semibold">{{ item.name }}</h3>
                  <p class="text-sm text-gray-600">{{ getSpecLabel(item.spec) }}</p>
                  <p class="text-sm text-gray-500">數量：{{ item.quantity }}</p>
                </div>
                <div class="text-right">
                  <p class="font-bold text-primary-600">NT${{ item.price }}</p>
                  <p class="text-sm text-gray-600">小計：NT${{ item.price * item.quantity }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- 收件人資訊 -->
          <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">收件人資訊</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">收件人姓名 *</label>
                <input
                  v-model="form.recipient_name"
                  type="text"
                  required
                  class="input-field"
                  placeholder="請輸入收件人姓名"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">聯絡電話 *</label>
                <input
                  v-model="form.recipient_phone"
                  type="tel"
                  required
                  class="input-field"
                  placeholder="請輸入聯絡電話"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input
                  v-model="form.recipient_email"
                  type="email"
                  class="input-field"
                  placeholder="請輸入 Email（選填）"
                />
              </div>
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">收件地址 *</label>
                <textarea
                  v-model="form.shipping_address"
                  required
                  rows="3"
                  class="input-field"
                  placeholder="請輸入完整收件地址"
                ></textarea>
              </div>
            </div>
          </div>

          <!-- 配送與付款方式 -->
          <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">配送與付款方式</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">配送方式 *</label>
                <select v-model="form.shipping_method" required class="input-field">
                  <option value="">請選擇配送方式</option>
                  <option value="宅配">宅配</option>
                  <option value="超商取貨">超商取貨</option>
                  <option value="門市自取">門市自取</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">付款方式 *</label>
                <select v-model="form.payment_method" required class="input-field">
                  <option value="">請選擇付款方式</option>
                  <option value="貨到付款">貨到付款</option>
                  <option value="信用卡">信用卡</option>
                  <option value="LINE Pay">LINE Pay</option>
                </select>
              </div>
            </div>
            <div class="mt-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">備註</label>
              <textarea
                v-model="form.note"
                rows="3"
                class="input-field"
                placeholder="如有特殊需求請在此說明（選填）"
              ></textarea>
            </div>
          </div>

          <section class="mb-6">
            <label class="block mb-1 font-semibold">選擇優惠券</label>
            <select v-model="selectedCouponId" class="input-sm w-full max-w-xs" @change="applyCoupon">
              <option value="">不使用優惠券</option>
              <option v-for="coupon in coupons" :key="coupon.id" :value="coupon.id">
                {{ coupon.name }} ({{ coupon.code }}) - {{ coupon.type === 'percent' ? coupon.value + '%' : 'NT$' + coupon.value }}
              </option>
            </select>
          </section>
        </div>

        <!-- 右側：訂單摘要 -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-lg shadow-sm border p-6 sticky top-8">
            <h2 class="text-xl font-semibold mb-4">訂單摘要</h2>
            
            <!-- 商品小計 -->
            <div class="space-y-2 mb-4">
              <div v-for="item in cart.items" :key="item.id" class="flex justify-between text-sm">
                <span>{{ item.name }}（{{ getSpecLabel(item.spec) }}）x{{ item.quantity }}</span>
                <span>NT${{ item.price * item.quantity }}</span>
              </div>
            </div>

            <hr class="my-4">

            <!-- 總計 -->
            <div class="flex justify-between items-center text-lg font-bold">
              <span>總計</span>
              <span class="text-primary-600">NT${{ finalTotal }}</span>
            </div>

            <!-- 結帳按鈕 -->
            <button
              @click="submitOrder"
              :disabled="submitting || !isFormValid"
              class="w-full mt-6 btn-primary text-lg py-3"
            >
              <span v-if="submitting" class="flex items-center justify-center">
                <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-white mr-2"></div>
                處理中...
              </span>
              <span v-else>確認結帳</span>
            </button>

            <!-- 返回購物車 -->
            <router-link to="/cart" class="block w-full mt-3 text-center text-gray-600 hover:text-gray-800">
              返回購物車
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useCartStore } from '@/stores/cart'
import axios from 'axios'

const router = useRouter()
const cart = useCartStore()

const loading = ref(false)
const submitting = ref(false)

// 表單資料
const form = ref({
  recipient_name: '',
  recipient_phone: '',
  recipient_email: '',
  shipping_address: '',
  shipping_method: '',
  payment_method: '',
  note: ''
})

// 表單驗證
const isFormValid = computed(() => {
  return form.value.recipient_name &&
         form.value.recipient_phone &&
         form.value.shipping_address &&
         form.value.shipping_method &&
         form.value.payment_method
})

// 規格標籤轉換
function getSpecLabel(spec: string | undefined) {
  if (spec === 'large') return '600g'
  if (spec === 'small') return '300g'
  if (spec === 'sample') return '隨手包'
  return '-'
}

// 優惠券資料
const coupons = ref([])
const selectedCouponId = ref('')
const discount = ref(0)

const fetchCoupons = async () => {
  const res = await axios.get('http://127.0.0.1:8000/api/v1/coupons')
  coupons.value = (res.data.data?.data || res.data.data || []).filter(c => c.active)
}

const applyCoupon = () => {
  const coupon = coupons.value.find(c => c.id == selectedCouponId.value)
  if (!coupon) {
    discount.value = 0
    return
  }
  if (coupon.type === 'percent') {
    discount.value = Math.round(cart.totalPrice * coupon.value / 100)
  } else {
    discount.value = coupon.value
  }
}

// 總金額計算
const total = computed(() => {
  return cart.totalPrice - discount.value
})

const finalTotal = computed(() => Math.max(0, total.value))

// 提交訂單
async function submitOrder() {
  if (!isFormValid.value) {
    alert('請填寫完整的收件人資訊')
    return
  }

  submitting.value = true
  try {
    const response = await axios.post('http://127.0.0.1:8000/api/v1/orders', form.value)
    
    if (response.data.success) {
      // 清空購物車
      cart.clearCart()
      
      // 跳轉到訂單確認頁面
      router.push(`/orders/${response.data.order_id}`)
    } else {
      alert('訂單建立失敗：' + response.data.message)
    }
  } catch (error: any) {
    console.error('結帳失敗:', error)
    alert('結帳失敗：' + (error.response?.data?.message || '網路錯誤'))
  } finally {
    submitting.value = false
  }
}

// 頁面載入時取得購物車資料
onMounted(() => {
  loading.value = true
  cart.fetchCart().finally(() => {
    loading.value = false
  })
  fetchCoupons()
})
</script>

<style scoped>
.checkout-page {
  background: #f8f6f2;
  min-height: 100vh;
}

.input-field {
  @apply w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent;
}

.btn-primary {
  @apply bg-primary-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors;
}

.btn-primary:disabled {
  @apply bg-gray-400 cursor-not-allowed hover:bg-gray-400;
}
</style> 