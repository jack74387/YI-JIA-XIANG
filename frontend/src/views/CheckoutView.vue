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

          <!-- 優惠券選擇區塊（美化下拉選單+已選卡片） -->
          <section class="mb-6">
            <label class="block mb-1 font-semibold">選擇優惠券</label>
            <div class="flex items-center gap-4">
              <select v-model="selectedCouponId" class="input-sm w-full max-w-xs rounded border border-gray-300 focus:ring-2 focus:ring-gray-300 focus:border-gray-400 shadow-sm transition" @change="applyCoupon">
                <option value="">不使用優惠券</option>
                <option v-for="coupon in availableCoupons" :key="coupon.id" :value="coupon.id">
                  {{ coupon.name }} ({{ coupon.code }}) - {{ coupon.type === 'percent' ? coupon.value + '%' : 'NT$' + coupon.value }}
                </option>
              </select>
              <button v-if="selectedCoupon" @click="removeCoupon" class="ml-2 px-3 py-1 rounded bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm">移除</button>
            </div>
            <!-- 已選優惠券資訊卡片 -->
            <div v-if="selectedCoupon" class="mt-3 p-4 rounded border border-primary-200 bg-primary-50 shadow-sm flex flex-col gap-1">
              <div class="font-bold text-primary-700 text-lg flex items-center gap-2">
                <span>{{ selectedCoupon.name }}</span>
                <span class="text-xs bg-primary-200 text-primary-800 rounded px-2 py-0.5">{{ selectedCoupon.code }}</span>
              </div>
              <div class="text-sm text-gray-700">{{ selectedCoupon.description }}</div>
              <div class="text-xs text-gray-500">{{ selectedCoupon.type === 'percent' ? selectedCoupon.value + '% 折扣' : '折 NT$' + selectedCoupon.value }}</div>
              <div class="text-xs text-gray-500">滿 NT${{ selectedCoupon.min_amount || 0 }} 可用</div>
              <div class="text-xs text-gray-400">到期日：{{ selectedCoupon.expired_at ? selectedCoupon.expired_at.split('T')[0] : '-' }}</div>
            </div>
          </section>

          <!-- 點數折抵區塊（允許與優惠券同時選擇） -->
          <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">點數折抵</h2>
            <div v-if="memberPoints > 0">
              <div class="mb-2">可用點數：<span class="font-bold text-primary-600">{{ memberPoints }}</span></div>
              <div class="mb-2">本次最多可折抵：<span class="font-bold text-primary-600">{{ maxUsablePoints }}</span> 點（單筆最多折抵50%）</div>
              <div class="flex items-center gap-2">
                <input type="number" v-model.number="usePoints" :max="maxUsablePoints" min="0" class="input-field w-32" :disabled="pointsLoading" />
                <span class="ml-2 text-gray-500">點</span>
                <span v-if="pointsLoading" class="inline-block animate-spin rounded-full h-5 w-5 border-b-2 border-primary-500"></span>
              </div>
              <transition name="fade">
                <div v-if="pointsError" class="text-red-500 text-sm mt-1">{{ pointsError }}</div>
              </transition>
              <div v-if="!pointsError" class="text-gray-500 text-sm mt-1">剩餘點數：{{ remainingPoints }}</div>
              <div v-if="usePoints > memberPoints" class="text-red-500 text-sm mt-1">超過可用點數</div>
              <div v-if="usePoints > maxUsablePoints" class="text-red-500 text-sm mt-1">超過本次可折抵上限</div>
            </div>
            <div v-else class="text-gray-500">您目前沒有可用點數</div>
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
                <div class="flex gap-2 mb-2">
                  <select v-model="selectedCity" @change="onCityChange" class="input-field w-40">
                    <option value="">請選擇縣市</option>
                    <option v-for="city in cities" :key="city.name" :value="city.name">{{ city.name }}</option>
                  </select>
                  <select v-model="selectedDistrict" class="input-field w-40" :disabled="!selectedCity">
                    <option value="">請選擇鄉鎮市區</option>
                    <option v-for="district in availableDistricts" :key="district" :value="district">{{ district }}</option>
                  </select>
                </div>
                <input v-model="detailAddress" type="text" class="input-field w-full" placeholder="請輸入詳細地址（如路名、門牌）" />
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
              <span class="text-primary-600">NT${{ cart.totalPrice }}</span>
            </div>
            <!-- 優惠券折扣 -->
            <div v-if="discount > 0" class="flex justify-between items-center text-base mt-2">
              <span>優惠券折扣</span>
              <span class="text-green-600">-NT${{ discount }}</span>
            </div>
            <!-- 點數折抵 -->
            <div v-if="discountByPoints > 0" class="flex justify-between items-center text-base mt-2">
              <span>點數折抵</span>
              <span class="text-green-600">-NT${{ discountByPoints }}</span>
            </div>
            <!-- 折抵後總計 -->
            <div class="flex justify-between items-center text-lg font-bold mt-2 border-t pt-2">
              <span>折抵後總計</span>
              <span class="text-primary-700">NT${{ finalTotalWithPoints }}</span>
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
import { ref, computed, onMounted, watch } from 'vue'
import { useRouter } from 'vue-router'
import { useCartStore } from '@/stores/cart'
import axios from 'axios'
import { useMemberStore } from '@/stores/member'

const router = useRouter()
const cart = useCartStore()
const memberStore = useMemberStore()

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
         form.value.shipping_method &&
         form.value.payment_method &&
         selectedCity.value &&
         selectedDistrict.value &&
         detailAddress.value
})

// 規格標籤轉換
function getSpecLabel(spec: string | undefined) {
  if (spec === 'large') return '600g'
  if (spec === 'small') return '300g'
  if (spec === 'sample') return '隨手包'
  return '-'
}

// 優惠券資料
const coupons = ref<any[]>([])
const selectedCouponId = ref('')
const discount = ref(0)

// 新增：分可用/不可用
const availableCoupons = computed(() => {
  return coupons.value.filter((c: any) => c.is_active && isCouponAvailable(c))
})
const unavailableCoupons = computed(() => {
  return coupons.value.filter((c: any) => !isCouponAvailable(c)).map((c: any) => ({
    ...c,
    unavailableReason: getCouponUnavailableReason(c)
  }))
})

function isCouponAvailable(coupon: any) {
  // 滿額、未過期、啟用
  const now = new Date()
  if (!coupon.is_active) return false
  if (coupon.expired_at && new Date(coupon.expired_at) < now) return false
  if (coupon.min_amount && cart.totalPrice < coupon.min_amount) return false
  return true
}
function getCouponUnavailableReason(coupon: any) {
  const now = new Date()
  if (!coupon.is_active) return '已停用'
  if (coupon.expired_at && new Date(coupon.expired_at) < now) return '已過期'
  if (coupon.min_amount && cart.totalPrice < coupon.min_amount) return `未達滿 NT$${coupon.min_amount}`
  return '不可用'
}

const fetchCoupons = async () => {
  const res = await axios.get('http://127.0.0.1:8000/api/v1/coupons')
  coupons.value = (res.data.data?.data || res.data.data || []).filter((c: any) => c.is_active)
}

// 新增 applyCoupon for 下拉選單
const applyCoupon = async () => {
  if (!selectedCouponId.value) {
    discount.value = 0
    return
  }
  try {
    const coupon = coupons.value.find((c: any) => c.id == selectedCouponId.value)
    if (!coupon) {
      discount.value = 0
      return
    }
    const response = await axios.post('http://127.0.0.1:8000/api/v1/coupons/validate', {
      code: coupon.code,
      order_amount: cart.totalPrice
    })
    if (response.data.success) {
      discount.value = response.data.discount
      alert('優惠券已套用！')
    } else {
      alert('優惠券驗證失敗：' + response.data.message)
      discount.value = 0
    }
  } catch (error: any) {
    if (error.response && error.response.status === 400) {
      alert('此優惠券無法使用，可能原因：\n1. 未達滿額\n2. 已過期\n3. 已用過\n4. 已用完\n請重新選擇其他優惠券。')
    } else {
      alert('優惠券驗證失敗：' + (error.response?.data?.message || error.message))
    }
    discount.value = 0
  }
}

// 總金額計算
const total = computed(() => {
  return cart.totalPrice - discount.value
})

const finalTotal = computed(() => Math.max(0, total.value))

// 點數相關
const memberPoints = computed(() => memberStore.userPoints.current)
const usePoints = ref(0)
const maxUsablePoints = computed(() => Math.min(memberPoints.value, Math.floor(cart.totalPrice / 2)))
const discountByPoints = computed(() => Math.min(usePoints.value, maxUsablePoints.value))
const finalTotalWithPoints = computed(() => Math.max(0, finalTotal.value - discountByPoints.value))
const remainingPoints = computed(() => memberPoints.value - discountByPoints.value)
const pointsError = ref('')
const pointsLoading = ref(false)
let debounceTimer: any = null

watch(usePoints, (val) => {
  pointsError.value = ''
  pointsLoading.value = false
  if (val < 0) usePoints.value = 0
  if (val > maxUsablePoints.value) usePoints.value = maxUsablePoints.value
  if (val > memberPoints.value) usePoints.value = memberPoints.value
  // debounce
  if (debounceTimer) clearTimeout(debounceTimer)
  debounceTimer = setTimeout(async () => {
    if (usePoints.value > 0) {
      pointsLoading.value = true
      try {
        const res = await axios.post('http://127.0.0.1:8000/api/v1/points/spend', { amount: usePoints.value }, { withCredentials: true })
        if (!res.data.success) {
          pointsError.value = res.data.message || '點數折抵驗證失敗'
          usePoints.value = 0
        }
      } catch (e: any) {
        pointsError.value = e.response?.data?.message || '點數折抵驗證失敗'
        usePoints.value = 0
      } finally {
        pointsLoading.value = false
      }
    }
  }, 400)
})

// 移除點數折抵與優惠券二擇一的 watch 互斥邏輯

// 台灣縣市鄉鎮資料
const cities = [
  { name: '台北市', districts: ['中正區','大同區','中山區','松山區','大安區','萬華區','信義區','士林區','北投區','內湖區','南港區','文山區'] },
  { name: '新北市', districts: ['板橋區','三重區','中和區','永和區','新莊區','新店區','樹林區','鶯歌區','三峽區','淡水區','汐止區','瑞芳區','土城區','蘆洲區','五股區','泰山區','林口區','深坑區','石碇區','坪林區','三芝區','石門區','八里區','平溪區','雙溪區','貢寮區','金山區','萬里區','烏來區'] },
  { name: '基隆市', districts: ['仁愛區','信義區','中正區','中山區','安樂區','暖暖區','七堵區'] },
  { name: '宜蘭縣', districts: ['宜蘭市','羅東鎮','蘇澳鎮','頭城鎮','礁溪鄉','壯圍鄉','員山鄉','冬山鄉','五結鄉','三星鄉','大同鄉','南澳鄉'] },
  { name: '桃園市', districts: ['桃園區','中壢區','平鎮區','八德區','楊梅區','蘆竹區','大溪區','大園區','龜山區','龍潭區','新屋區','觀音區','復興區'] },
  { name: '新竹市', districts: ['東區','北區','香山區'] },
  { name: '新竹縣', districts: ['竹北市','竹東鎮','新埔鎮','關西鎮','湖口鄉','新豐鄉','芎林鄉','橫山鄉','北埔鄉','寶山鄉','峨眉鄉','尖石鄉','五峰鄉'] },
  { name: '苗栗縣', districts: ['苗栗市','苑裡鎮','通霄鎮','竹南鎮','頭份市','後龍鎮','卓蘭鎮','大湖鄉','公館鄉','銅鑼鄉','南庄鄉','頭屋鄉','三義鄉','西湖鄉','造橋鄉','三灣鄉','獅潭鄉','泰安鄉'] },
  { name: '台中市', districts: ['中區','東區','南區','西區','北區','北屯區','西屯區','南屯區','太平區','大里區','霧峰區','烏日區','豐原區','后里區','石岡區','東勢區','和平區','新社區','潭子區','大雅區','神岡區','大肚區','沙鹿區','龍井區','梧棲區','清水區','大甲區','外埔區','大安區'] },
  { name: '彰化縣', districts: ['彰化市','員林市','和美鎮','鹿港鎮','溪湖鎮','二林鎮','田中鎮','北斗鎮','花壇鄉','芬園鄉','大村鄉','永靖鄉','伸港鄉','福興鄉','秀水鄉','埔鹽鄉','埔心鄉','溪州鄉','竹塘鄉','二水鄉','田尾鄉','埤頭鄉','芳苑鄉','大城鄉','竹山鎮','集集鎮','名間鄉','鹿谷鄉','水里鄉','魚池鄉','信義鄉','仁愛鄉'] },
  { name: '南投縣', districts: ['南投市','埔里鎮','草屯鎮','竹山鎮','集集鎮','名間鄉','鹿谷鄉','中寮鄉','魚池鄉','國姓鄉','水里鄉','信義鄉','仁愛鄉'] },
  { name: '雲林縣', districts: ['斗六市','斗南鎮','虎尾鎮','西螺鎮','土庫鎮','北港鎮','古坑鄉','大埤鄉','莿桐鄉','林內鄉','二崙鄉','崙背鄉','麥寮鄉','東勢鄉','褒忠鄉','台西鄉','元長鄉','四湖鄉','口湖鄉','水林鄉'] },
  { name: '嘉義市', districts: ['東區','西區'] },
  { name: '嘉義縣', districts: ['太保市','朴子市','布袋鎮','大林鎮','民雄鄉','溪口鄉','新港鄉','六腳鄉','東石鄉','義竹鄉','鹿草鄉','水上鄉','中埔鄉','竹崎鄉','梅山鄉','番路鄉','大埔鄉','阿里山鄉'] },
  { name: '台南市', districts: ['中西區','東區','南區','北區','安平區','安南區','永康區','歸仁區','新化區','左鎮區','玉井區','楠西區','南化區','仁德區','關廟區','龍崎區','官田區','麻豆區','佳里區','西港區','七股區','將軍區','學甲區','北門區','新營區','後壁區','白河區','東山區','六甲區','下營區','柳營區','鹽水區','善化區','大內區','山上區','新市區','安定區'] },
  { name: '高雄市', districts: ['新興區','前金區','苓雅區','鹽埕區','鼓山區','旗津區','前鎮區','三民區','楠梓區','小港區','左營區','仁武區','大社區','岡山區','路竹區','阿蓮區','田寮區','燕巢區','橋頭區','梓官區','彌陀區','永安區','湖內區','鳳山區','大寮區','林園區','鳥松區','大樹區','旗山區','美濃區','六龜區','內門區','杉林區','甲仙區','桃源區','那瑪夏區','茂林區','茄萣區'] },
  { name: '屏東縣', districts: ['屏東市','潮州鎮','東港鎮','恆春鎮','萬丹鄉','長治鄉','麟洛鄉','九如鄉','里港鄉','鹽埔鄉','高樹鄉','萬巒鄉','內埔鄉','竹田鄉','新埤鄉','枋寮鄉','新園鄉','崁頂鄉','林邊鄉','南州鄉','佳冬鄉','琉球鄉','車城鄉','滿州鄉','枋山鄉','三地門鄉','霧台鄉','瑪家鄉','泰武鄉','來義鄉','春日鄉','獅子鄉','牡丹鄉'] },
  { name: '台東縣', districts: ['台東市','成功鎮','關山鎮','卑南鄉','鹿野鄉','池上鄉','東河鄉','長濱鄉','太麻里鄉','大武鄉','綠島鄉','海端鄉','延平鄉','金峰鄉','達仁鄉','蘭嶼鄉'] },
  { name: '花蓮縣', districts: ['花蓮市','鳳林鎮','玉里鎮','新城鄉','吉安鄉','壽豐鄉','光復鄉','豐濱鄉','瑞穗鄉','富里鄉','秀林鄉','萬榮鄉','卓溪鄉'] },
  { name: '澎湖縣', districts: ['馬公市','西嶼鄉','望安鄉','七美鄉','白沙鄉','湖西鄉'] },
  { name: '金門縣', districts: ['金城鎮','金沙鎮','金湖鎮','金寧鄉','烈嶼鄉','烏坵鄉'] },
  { name: '連江縣', districts: ['南竿鄉','北竿鄉','莒光鄉','東引鄉'] }
]
const selectedCity = ref('')
const selectedDistrict = ref('')
const detailAddress = ref('')
const availableDistricts = computed(() => {
  const city = cities.find(c => c.name === selectedCity.value)
  return city ? city.districts : []
})
function onCityChange() {
  selectedDistrict.value = ''
}

// 提交訂單時組合完整地址
async function submitOrder() {
  if (!isFormValid.value) {
    alert('請填寫完整的收件人資訊')
    return
  }
  if (!selectedCity.value || !selectedDistrict.value || !detailAddress.value) {
    alert('請選擇完整的縣市、鄉鎮市區並填寫詳細地址')
    return
  }
  submitting.value = true
  try {
    const payload = {
      ...form.value,
      shipping_address: `${selectedCity.value}${selectedDistrict.value}${detailAddress.value}`,
      city: selectedCity.value,
      district: selectedDistrict.value,
      detail_address: detailAddress.value,
      use_points: usePoints.value,
      coupon_id: selectedCouponId.value || undefined,
      final_amount: finalTotalWithPoints.value, // 折抵後總計
      discount: discount.value + discountByPoints.value // 優惠券+點數折抵總和
    }
    const response = await axios.post('http://127.0.0.1:8000/api/v1/orders', payload)
    
    if (response.data.success) {
      // 清空購物車
      cart.clearCart()
      
      // 跳轉到訂單確認頁面
      router.push({ name: 'OrderSuccess', params: { orderId: response.data.order_id } })
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
  memberStore.fetchPoints() // ← 新增，確保點數正確
})

const selectedCoupon = computed(() => coupons.value.find((c: any) => c.id == selectedCouponId.value) || null)
const removeCoupon = () => {
  discount.value = 0
  selectedCouponId.value = ''
}
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

.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style> 