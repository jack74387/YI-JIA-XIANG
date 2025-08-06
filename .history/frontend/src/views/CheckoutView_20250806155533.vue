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
                <img :src="getImageUrl(item.product?.primary_image?.image_path) || '/images/placeholder.jpg'" :alt="item.name" class="w-16 h-16 object-cover rounded" />
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

          <!-- 優惠券選擇區塊 -->
          <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4 flex items-center">
              <svg class="w-6 h-6 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
              </svg>
              優惠券選擇
            </h2>
            
            <!-- 優惠券選擇下拉選單 -->
            <div class="relative">
              <label class="block text-sm font-medium text-gray-700 mb-2">選擇優惠券</label>
              <div class="relative">
                <select 
                  v-model="selectedCouponId" 
                  @change="applyCoupon"
                  class="w-full px-4 py-3 pr-10 text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 appearance-none cursor-pointer hover:border-gray-400"
                >
                  <option value="" class="py-2">🎫 不使用優惠券</option>
                  <option v-for="coupon in availableCoupons" :key="coupon.id" :value="coupon.id" class="py-2">
                    💎 {{ coupon.name }} - {{ coupon.type === 'percent' ? coupon.value + '% 折扣' : 'NT$' + coupon.value + ' 折抵' }}
                  </option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                  <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                  </svg>
                </div>
              </div>
              
              <!-- 移除按鈕 -->
              <button 
                v-if="selectedCoupon" 
                @click="removeCoupon" 
                class="mt-3 inline-flex items-center px-4 py-2 text-sm font-medium text-red-600 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 hover:border-red-300 transition-all duration-200"
              >
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                移除優惠券
              </button>
            </div>
            
            <!-- 已選優惠券資訊卡片 -->
            <div v-if="selectedCoupon" class="mt-4 p-4 rounded-xl border-2 border-primary-200 bg-gradient-to-br from-primary-50 to-primary-100 shadow-lg">
              <div class="flex items-start justify-between">
                <div class="flex-1">
                  <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 bg-primary-600 rounded-full flex items-center justify-center">
                      <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                      </svg>
                    </div>
                    <div>
                      <h3 class="font-bold text-primary-800 text-lg">{{ selectedCoupon.name }}</h3>
                      <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-primary-200 text-primary-800">
                        {{ selectedCoupon.code }}
                      </span>
                    </div>
                  </div>
                  
                  <div class="space-y-2 text-sm">
                    <div class="text-gray-700">{{ selectedCoupon.description }}</div>
                    <div class="flex items-center gap-4 text-gray-600">
                      <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        <span class="font-medium text-green-600">
                          {{ selectedCoupon.type === 'percent' ? selectedCoupon.value + '% 折扣' : 'NT$' + selectedCoupon.value + ' 折抵' }}
                        </span>
                      </div>
                      <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        <span>滿 NT${{ selectedCoupon.min_amount || 0 }} 可用</span>
                      </div>
                    </div>
                    <div class="flex items-center text-gray-500">
                      <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                      </svg>
                      <span>到期日：{{ selectedCoupon.expired_at ? selectedCoupon.expired_at.split('T')[0] : '無期限' }}</span>
                    </div>
                  </div>
                </div>
                
                <!-- 折扣金額顯示 -->
                <div class="text-right">
                  <div class="text-2xl font-bold text-green-600">-NT${{ discount }}</div>
                  <div class="text-xs text-gray-500">本次折抵</div>
                </div>
              </div>
            </div>
            
            <!-- 無可用優惠券提示 -->
            <div v-if="availableCoupons.length === 0 && !selectedCoupon" class="mt-4 p-4 rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 text-center">
              <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
              </svg>
              <p class="text-gray-500">目前沒有可用的優惠券</p>
              <p class="text-sm text-gray-400 mt-1">請稍後再來查看或前往會員中心領取優惠券</p>
            </div>
          </div>

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
          </div>

          <!-- 收件人資訊 -->
          <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">收件人資訊</h2>
            
            <!-- 宅配地址選擇 -->
            <div v-if="form.shipping_method === '宅配'" class="mb-6">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">收件地址</h3>
                <div class="flex gap-2">
                  <button @click="showAddressSelectDialog = true" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    選擇地址
                  </button>
                  <button @click="showAddressDialog = true" class="inline-flex items-center px-3 py-2 text-sm font-medium text-primary-600 bg-primary-50 border border-primary-200 rounded-lg hover:bg-primary-100 hover:border-primary-300 transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    新增地址
                  </button>
                </div>
              </div>
              
              <!-- 當前選擇的地址顯示 -->
              <div v-if="selectedAddressId" class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                <div class="flex items-center justify-between">
                  <div class="flex-1">
                    <div class="flex items-center mb-2">
                      <h4 class="font-semibold text-gray-900 text-lg">{{ form.recipient_name }}</h4>
                      <span v-if="selectedAddress && selectedAddress.is_default" class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        預設
                      </span>
                    </div>
                    <div class="space-y-1 text-sm text-gray-600">
                      <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        {{ form.recipient_phone }}
                      </div>
                      <div class="flex items-start">
                        <svg class="w-4 h-4 mr-2 mt-0.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>{{ selectedCity }}{{ selectedDistrict }}{{ detailAddress }}</span>
                      </div>
                      <div v-if="form.recipient_email" class="flex items-center">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        {{ form.recipient_email }}
                      </div>
                    </div>
                  </div>
                  <button @click="clearSelectedAddress" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                  </button>
                </div>
              </div>
              <div v-else class="p-4 border-2 border-dashed border-gray-300 rounded-lg text-center">
                <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <p class="text-gray-500">請選擇收件地址</p>
              </div>
            </div>
            
            <!-- 超商取貨門市選擇 -->
            <div v-if="form.shipping_method === '超商取貨'" class="mb-6">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">超商門市選擇</h3>
                <button @click="showConvenienceStoreDialog = true" class="inline-flex items-center px-3 py-2 text-sm font-medium text-primary-600 bg-primary-50 border border-primary-200 rounded-lg hover:bg-primary-100 hover:border-primary-300 transition-colors">
                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                  </svg>
                  選擇門市
                </button>
              </div>
              
              <div v-if="selectedConvenienceStore" class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                <div class="flex items-center justify-between">
                  <div class="flex-1">
                    <div class="flex items-center mb-2">
                      <h4 class="font-semibold text-gray-900 text-lg">{{ selectedConvenienceStore.name }}</h4>
                      <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        {{ selectedConvenienceStore.chain }}
                      </span>
                    </div>
                    <div class="space-y-1 text-sm text-gray-600">
                      <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>{{ selectedConvenienceStore.address }}</span>
                      </div>
                      <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <span>{{ selectedConvenienceStore.phone }}</span>
                      </div>
                    </div>
                  </div>
                  <button @click="selectedConvenienceStore = null" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                  </button>
                </div>
              </div>
              <div v-else class="p-4 border-2 border-dashed border-gray-300 rounded-lg text-center">
                <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <p class="text-gray-500">請選擇超商門市</p>
              </div>
            </div>
            
            <!-- 門市自取選擇 -->
            <div v-if="form.shipping_method === '門市自取'" class="mb-6">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">門市選擇</h3>
                <button @click="showStoreDialog = true" class="inline-flex items-center px-3 py-2 text-sm font-medium text-primary-600 bg-primary-50 border border-primary-200 rounded-lg hover:bg-primary-100 hover:border-primary-300 transition-colors">
                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                  </svg>
                  選擇門市
                </button>
              </div>
              
              <div v-if="selectedStore" class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                <div class="flex items-center justify-between">
                  <div class="flex-1">
                    <div class="flex items-center mb-2">
                      <h4 class="font-semibold text-gray-900 text-lg">{{ selectedStore.name }}</h4>
                      <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                        一佳香門市
                      </span>
                    </div>
                    <div class="space-y-1 text-sm text-gray-600">
                      <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>{{ selectedStore.address }}</span>
                      </div>
                      <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <span>{{ selectedStore.phone }}</span>
                      </div>
                      <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ selectedStore.hours }}</span>
                      </div>
                    </div>
                  </div>
                  <button @click="selectedStore = null" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                  </button>
                </div>
              </div>
              <div v-else class="p-4 border-2 border-dashed border-gray-300 rounded-lg text-center">
                <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <p class="text-gray-500">請選擇門市</p>
              </div>
            </div>
            
            <!-- 基本收件人資訊 -->
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
              
              <!-- 宅配地址欄位 -->
              <div v-if="form.shipping_method === '宅配'" class="md:col-span-2">
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
          
          <!-- 備註 -->
          <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">備註</h2>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">特殊需求說明</label>
              <textarea
                v-model="form.note"
                rows="3"
                class="input-field"
                placeholder="如有特殊需求請在此說明（選填）"
              ></textarea>
            </div>
          </div>
          
          <!-- 地址選擇 Dialog -->
          <div v-if="showAddressSelectDialog" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-10 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
              <div class="mt-3">
                <div class="flex items-center justify-between mb-6">
                  <h3 class="text-xl font-semibold text-gray-900">選擇收件地址</h3>
                  <button @click="showAddressSelectDialog = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                  </button>
                </div>
                
                <div v-if="addressStore.loading" class="text-center py-12">
                  <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
                  <p class="mt-2 text-gray-500">載入地址中...</p>
                </div>
                <div v-else-if="addressStore.error" class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                  <div class="flex">
                    <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="ml-3 text-sm text-red-800">{{ addressStore.error }}</p>
                  </div>
                </div>
                <div v-else>
                  <div v-if="addressStore.addresses.length === 0" class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <p class="text-gray-500 mb-4">尚未新增任何常用地址</p>
                    <button @click="showAddressDialog = true; showAddressSelectDialog = false" class="inline-flex items-center px-4 py-2 text-sm font-medium text-primary-600 bg-primary-50 border border-primary-200 rounded-lg hover:bg-primary-100 hover:border-primary-300 transition-colors">
                      <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                      </svg>
                      新增地址
                    </button>
                  </div>
                  <div v-else class="space-y-3 max-h-96 overflow-y-auto">
                    <div v-for="address in addressStore.addresses" :key="address.id" 
                         class="relative p-4 border rounded-lg cursor-pointer transition-all duration-200 hover:shadow-md"
                         :class="{ 
                           'border-primary-500 bg-primary-50 shadow-sm': selectedAddressId === address.id,
                           'border-gray-200 bg-white hover:border-gray-300': selectedAddressId !== address.id 
                         }"
                         @click="selectAddressFromDialog(address)">
                      <!-- 選中標記 -->
                      <div v-if="selectedAddressId === address.id" class="absolute top-3 right-3">
                        <div class="w-6 h-6 bg-primary-600 rounded-full flex items-center justify-center">
                          <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                          </svg>
                        </div>
                      </div>
                      
                      <!-- 預設標記 -->
                      <div v-if="address.is_default" class="absolute top-3 left-3">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                          <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                          </svg>
                          預設
                        </span>
                      </div>
                      
                      <div class="pr-8">
                        <div class="flex items-center mb-2">
                          <h4 class="font-semibold text-gray-900 text-lg">{{ address.recipient_name }}</h4>
                        </div>
                        <div class="space-y-1 text-sm text-gray-600">
                          <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            {{ address.recipient_phone }}
                          </div>
                          <div class="flex items-start">
                            <svg class="w-4 h-4 mr-2 mt-0.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>{{ address.city }}{{ address.district }}{{ address.detail_address }}</span>
                          </div>
                          <div v-if="address.recipient_email" class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            {{ address.recipient_email }}
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="mt-6 flex justify-between items-center">
                    <button @click="showAddressDialog = true; showAddressSelectDialog = false" class="inline-flex items-center px-4 py-2 text-sm font-medium text-primary-600 bg-primary-50 border border-primary-200 rounded-lg hover:bg-primary-100 hover:border-primary-300 transition-colors">
                      <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                      </svg>
                      新增地址
                    </button>
                    <div class="flex gap-3">
                      <button @click="showAddressSelectDialog = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        取消
                      </button>
                      <button @click="confirmAddressSelection" :disabled="!selectedAddressId" class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        確認選擇
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- 超商門市選擇 Dialog -->
          <div v-if="showConvenienceStoreDialog" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-10 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
              <div class="mt-3">
                <div class="flex items-center justify-between mb-6">
                  <h3 class="text-xl font-semibold text-gray-900">選擇超商門市</h3>
                  <button @click="showConvenienceStoreDialog = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                  </button>
                </div>
                
                <div class="space-y-3 max-h-96 overflow-y-auto">
                  <div v-for="store in convenienceStores" :key="store.id" 
                       class="relative p-4 border rounded-lg cursor-pointer transition-all duration-200 hover:shadow-md"
                       :class="{ 
                         'border-primary-500 bg-primary-50 shadow-sm': selectedConvenienceStore?.id === store.id,
                         'border-gray-200 bg-white hover:border-gray-300': selectedConvenienceStore?.id !== store.id 
                       }"
                       @click="selectedConvenienceStore = store">
                    <!-- 選中標記 -->
                    <div v-if="selectedConvenienceStore?.id === store.id" class="absolute top-3 right-3">
                      <div class="w-6 h-6 bg-primary-600 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                      </div>
                    </div>
                    
                    <div class="pr-8">
                      <div class="flex items-center mb-2">
                        <h4 class="font-semibold text-gray-900 text-lg">{{ store.name }}</h4>
                        <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                          {{ store.chain }}
                        </span>
                      </div>
                      <div class="space-y-1 text-sm text-gray-600">
                        <div class="flex items-center">
                          <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                          </svg>
                          <span>{{ store.address }}</span>
                        </div>
                        <div class="flex items-center">
                          <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                          </svg>
                          <span>{{ store.phone }}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div class="mt-6 flex justify-end gap-3">
                  <button @click="showConvenienceStoreDialog = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    取消
                  </button>
                  <button @click="showConvenienceStoreDialog = false" :disabled="!selectedConvenienceStore" class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    確認選擇
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- 門市選擇 Dialog -->
          <div v-if="showStoreDialog" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-10 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
              <div class="mt-3">
                <div class="flex items-center justify-between mb-6">
                  <h3 class="text-xl font-semibold text-gray-900">選擇門市</h3>
                  <button @click="showStoreDialog = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                  </button>
                </div>
                
                <div class="space-y-3 max-h-96 overflow-y-auto">
                  <div v-for="store in stores" :key="store.id" 
                       class="relative p-4 border rounded-lg cursor-pointer transition-all duration-200 hover:shadow-md"
                       :class="{ 
                         'border-primary-500 bg-primary-50 shadow-sm': selectedStore?.id === store.id,
                         'border-gray-200 bg-white hover:border-gray-300': selectedStore?.id !== store.id 
                       }"
                       @click="selectedStore = store">
                    <!-- 選中標記 -->
                    <div v-if="selectedStore?.id === store.id" class="absolute top-3 right-3">
                      <div class="w-6 h-6 bg-primary-600 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                      </div>
                    </div>
                    
                    <div class="pr-8">
                      <div class="flex items-center mb-2">
                        <h4 class="font-semibold text-gray-900 text-lg">{{ store.name }}</h4>
                        <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                          一佳香門市
                        </span>
                      </div>
                      <div class="space-y-1 text-sm text-gray-600">
                        <div class="flex items-center">
                          <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                          </svg>
                          <span>{{ store.address }}</span>
                        </div>
                        <div class="flex items-center">
                          <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                          </svg>
                          <span>{{ store.phone }}</span>
                        </div>
                        <div class="flex items-center">
                          <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                          </svg>
                          <span>{{ store.hours }}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div class="mt-6 flex justify-end gap-3">
                  <button @click="showStoreDialog = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    取消
                  </button>
                  <button @click="showStoreDialog = false" :disabled="!selectedStore" class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    確認選擇
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- 地址新增/編輯 Dialog -->
          <div v-if="showAddressDialog" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-md bg-white">
              <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">新增常用地址</h3>
                <form @submit.prevent="submitAddress">
                  <div class="grid grid-cols-1 gap-4">
                    <input v-model="addressForm.recipient_name" required placeholder="收件人姓名" class="border rounded px-3 py-2 w-full" />
                    <input v-model="addressForm.recipient_phone" required placeholder="收件人電話" class="border rounded px-3 py-2 w-full" />
                    <input v-model="addressForm.recipient_email" required placeholder="收件人 Email" class="border rounded px-3 py-2 w-full" />
                    
                    <!-- 縣市下拉選單 -->
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">縣市</label>
                      <select v-model="addressForm.city" @change="onAddressCityChange" required class="border rounded px-3 py-2 w-full">
                        <option value="">請選擇縣市</option>
                        <option v-for="city in addressStore.cities" :key="city" :value="city">{{ city }}</option>
                      </select>
                    </div>
                    
                    <!-- 鄉鎮市區下拉選單 -->
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">鄉鎮市區</label>
                      <select v-model="addressForm.district" required class="border rounded px-3 py-2 w-full" :disabled="!addressForm.city">
                        <option value="">請選擇鄉鎮市區</option>
                        <option v-for="district in addressAvailableDistricts" :key="district" :value="district">{{ district }}</option>
                      </select>
                    </div>
                    
                    <input v-model="addressForm.detail_address" required placeholder="詳細地址" class="border rounded px-3 py-2 w-full" />
                    <label class="flex items-center space-x-2">
                      <input type="checkbox" v-model="addressForm.is_default" />
                      <span>設為預設地址</span>
                    </label>
                  </div>
                  <div class="mt-6 flex justify-end space-x-3">
                    <button @click="closeAddressDialog" type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">取消</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-md hover:bg-primary-700">{{ addressDialogLoading ? '儲存中...' : '儲存' }}</button>
                  </div>
                </form>
              </div>
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
import { useUserAddresses } from '@/stores/userAddresses'
import { getImageUrl } from '@/utils/imageUtils'

const router = useRouter()
const cart = useCartStore()
const memberStore = useMemberStore()
const addressStore = useUserAddresses()

const loading = ref(false)
const submitting = ref(false)

// 常用地址相關
const showAddressDialog = ref(false)
const showAddressSelectDialog = ref(false)
const selectedAddressId = ref<number | null>(null)
const addressDialogLoading = ref(false)

// 超商取貨相關
const showConvenienceStoreDialog = ref(false)
const selectedConvenienceStore = ref<any>(null)

// 門市自取相關
const showStoreDialog = ref(false)
const selectedStore = ref<any>(null)
const addressForm = ref({
  recipient_name: '',
  recipient_phone: '',
  recipient_email: '',
  city: '',
  district: '',
  detail_address: '',
  is_default: false,
})

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
  const basicValid = form.value.recipient_name &&
                    form.value.recipient_phone &&
                    form.value.shipping_method &&
                    form.value.payment_method
  
  // 根據配送方式進行不同驗證
  if (form.value.shipping_method === '宅配') {
    return basicValid && selectedCity.value && selectedDistrict.value && detailAddress.value
  } else if (form.value.shipping_method === '超商取貨') {
    return basicValid && selectedConvenienceStore.value
  } else if (form.value.shipping_method === '門市自取') {
    return basicValid && selectedStore.value
  }
  
  return basicValid
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
function isCouponAvailable(coupon: any) {
  // 滿額、未過期、啟用
  const now = new Date()
  if (!coupon.is_active) return false
  if (coupon.expired_at && new Date(coupon.expired_at) < now) return false
  // 支援多種欄位名稱
  const minAmount = coupon.min_amount ?? coupon.min_order ?? coupon.min_total ?? 0
  if (minAmount && cart.totalPrice < minAmount) return false
  if (coupon.status === 'used') return false
  return true
}

const availableCoupons = computed(() => {
  return coupons.value.filter((c: any) => isCouponAvailable(c))
})
const unavailableCoupons = computed(() => {
  return coupons.value.filter((c: any) => !isCouponAvailable(c)).map((c: any) => ({
    ...c,
    unavailableReason: getCouponUnavailableReason(c)
  }))
})

function getCouponUnavailableReason(coupon: any) {
  const now = new Date()
  if (!coupon.is_active) return '已停用'
  if (coupon.expired_at && new Date(coupon.expired_at) < now) return '已過期'
  if (coupon.min_amount && cart.totalPrice < coupon.min_amount) return `未達滿 NT$${coupon.min_amount}`
  return '不可用'
}

const fetchCoupons = async () => {
  const res = await axios.get('/api/v1/coupons')
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
    const response = await axios.post('/api/v1/coupons/validate', {
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
        const res = await axios.post('/api/v1/points/spend', { amount: usePoints.value }, { withCredentials: true })
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
  
  // 根據配送方式驗證必要資訊
  if (form.value.shipping_method === '宅配') {
    if (!selectedCity.value || !selectedDistrict.value || !detailAddress.value) {
      alert('請選擇完整的縣市、鄉鎮市區並填寫詳細地址')
      return
    }
  } else if (form.value.shipping_method === '門市自取') {
    if (!selectedStore.value) {
      alert('請選擇門市')
      return
    }
  } else if (form.value.shipping_method === '超商取貨') {
    if (!selectedConvenienceStore.value) {
      alert('請選擇超商門市')
      return
    }
  }
  
  submitting.value = true
  try {
    const payload = {
      ...form.value,
      shipping_address: form.value.shipping_method === '宅配' ? `${selectedCity.value}${selectedDistrict.value}${detailAddress.value}` : null,
      city: form.value.shipping_method === '宅配' ? selectedCity.value : null,
      district: form.value.shipping_method === '宅配' ? selectedDistrict.value : null,
      detail_address: form.value.shipping_method === '宅配' ? detailAddress.value : null,
      used_points: usePoints.value,
      coupon_id: selectedCouponId.value || undefined,
      final_amount: finalTotalWithPoints.value, // 折抵後總計
      discount: discount.value + discountByPoints.value, // 優惠券+點數折抵總和
      // 門市自取相關資訊
      store_id: form.value.shipping_method === '門市自取' && selectedStore.value?.id != null ? String(selectedStore.value.id) : null,
      store_name: form.value.shipping_method === '門市自取' ? selectedStore.value?.name : null,
      store_address: form.value.shipping_method === '門市自取' ? selectedStore.value?.address : null,
      store_phone: form.value.shipping_method === '門市自取' ? selectedStore.value?.phone : null,
      store_hours: form.value.shipping_method === '門市自取' ? selectedStore.value?.hours : null,
      // 超商取貨相關資訊
      convenience_store_name: form.value.shipping_method === '超商取貨' ? selectedConvenienceStore.value?.name : null,
      convenience_store_address: form.value.shipping_method === '超商取貨' ? selectedConvenienceStore.value?.address : null,
      convenience_store_phone: form.value.shipping_method === '超商取貨' ? selectedConvenienceStore.value?.phone : null,
      convenience_store_chain: form.value.shipping_method === '超商取貨' ? selectedConvenienceStore.value?.chain : null,
    }
    const response = await axios.post('/api/v1/orders', payload)
    
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
onMounted(async () => {
  loading.value = true
  cart.fetchCart().finally(() => {
    loading.value = false
  })
  fetchCoupons()
  memberStore.fetchPoints() // ← 新增，確保點數正確
  await addressStore.fetchAddresses() // ← 新增，載入常用地址
  
  // 取得門市資料
  try {
    const res = await axios.get('/api/v1/stores')
    if (res.data.success) {
      stores.value = res.data.stores
      // 預設選擇台東總店
      const taitungStore = stores.value.find((s: any) => s.name.includes('台東總店'))
      if (taitungStore) {
        selectedStore.value = taitungStore
      }
    }
  } catch (e) {
    stores.value = []
  }
  
  // 自動填入預設地址
  const defaultAddress = addressStore.getDefaultAddress()
  if (defaultAddress) {
    selectedAddressId.value = defaultAddress.id
    selectAddress(defaultAddress)
  }
})

const selectedCoupon = computed(() => coupons.value.find((c: any) => c.id == selectedCouponId.value) || null)
const removeCoupon = () => {
  discount.value = 0
  selectedCouponId.value = ''
}

// 選中的地址
const selectedAddress = computed(() => {
  if (!selectedAddressId.value) return null
  return addressStore.addresses.find(a => a.id === selectedAddressId.value)
})

// 清空選中的地址
const clearSelectedAddress = () => {
  selectedAddressId.value = null
  form.value.recipient_name = ''
  form.value.recipient_phone = ''
  form.value.recipient_email = ''
  selectedCity.value = ''
  selectedDistrict.value = ''
  detailAddress.value = ''
}

// 模擬超商門市資料
const convenienceStores = ref([
  {
    id: 1,
    name: '7-ELEVEN 台北車站門市',
    chain: '7-ELEVEN',
    address: '台北市中正區忠孝西路一段49號',
    phone: '02-2311-1234'
  },
  {
    id: 2,
    name: '全家 西門町店',
    chain: '全家',
    address: '台北市萬華區西寧南路50號',
    phone: '02-2311-5678'
  },
  {
    id: 3,
    name: '萊爾富 信義店',
    chain: '萊爾富',
    address: '台北市信義區信義路五段7號',
    phone: '02-2720-1234'
  }
])

// 串接後端取得門市資料
const stores = ref<any[]>([])

// 常用地址相關方法
const selectAddress = (address: any) => {
  selectedAddressId.value = address.id
  form.value.recipient_name = address.recipient_name
  form.value.recipient_phone = address.recipient_phone
  form.value.recipient_email = address.recipient_email
  selectedCity.value = address.city
  selectedDistrict.value = address.district
  detailAddress.value = address.detail_address
}

// 從彈窗選擇地址
const selectAddressFromDialog = (address: any) => {
  selectedAddressId.value = address.id
}

// 確認地址選擇
const confirmAddressSelection = () => {
  if (selectedAddressId.value && selectedAddress.value) {
    const address = selectedAddress.value
    form.value.recipient_name = address.recipient_name
    form.value.recipient_phone = address.recipient_phone
    form.value.recipient_email = address.recipient_email || ''
    selectedCity.value = address.city
    selectedDistrict.value = address.district
    detailAddress.value = address.detail_address
    showAddressSelectDialog.value = false
  }
}



// 地址表單相關
const addressAvailableDistricts = computed(() => {
  if (!addressForm.value.city) return []
  return addressStore.getDistricts(addressForm.value.city)
})

const onAddressCityChange = () => {
  addressForm.value.district = ''
}

const submitAddress = async () => {
  addressDialogLoading.value = true
  try {
    await addressStore.addAddress(addressForm.value)
    showAddressDialog.value = false
    resetAddressForm()
    await addressStore.fetchAddresses()
  } catch (error) {
    console.error('儲存地址失敗:', error)
  } finally {
    addressDialogLoading.value = false
  }
}

const resetAddressForm = () => {
  addressForm.value.recipient_name = ''
  addressForm.value.recipient_phone = ''
  addressForm.value.recipient_email = ''
  addressForm.value.city = ''
  addressForm.value.district = ''
  addressForm.value.detail_address = ''
  addressForm.value.is_default = false
}

const closeAddressDialog = () => {
  showAddressDialog.value = false
  resetAddressForm()
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

@media (max-width: 600px) {
  .checkout-page {
    padding: 0.5rem 0.2rem;
  }
  .max-w-6xl, .py-8, .px-4 {
    max-width: 100% !important;
    padding-left: 0.2rem !important;
    padding-right: 0.2rem !important;
    padding-top: 0.5rem !important;
    padding-bottom: 0.5rem !important;
  }
  .text-3xl, .text-lg, .text-xl {
    font-size: 1.1rem !important;
  }
  .p-6, .mb-8 {
    padding: 0.5rem !important;
    margin-bottom: 0.5rem !important;
  }
  .grid-cols-1, .lg\:grid-cols-3, .md\:grid-cols-2 {
    grid-template-columns: 1fr !important;
  }
  .w-16, .h-16 {
    width: 48px !important;
    height: 48px !important;
  }
  .btn-primary, .checkout-btn {
    width: 100%;
    font-size: 1em;
    padding: 0.7em 0.5em;
  }
}
</style> 