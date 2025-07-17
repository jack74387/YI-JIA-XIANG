<template>
  <div>
    <h1 class="text-xl font-bold">{{ product.name }}</h1>
    <div class="text-sm text-gray-500 mb-1" v-if="product.subtitle">{{ product.subtitle }}</div>
    <div class="flex items-center gap-2 mt-1">
      <span class="text-yellow-500 font-bold text-lg" v-if="product.rating">★ {{ product.rating }}</span>
      <span class="text-xs text-gray-400" v-if="product.rating_count">({{ product.rating_count }} 則評價)</span>
      <span class="ml-2 text-xs text-gray-500" v-if="product.sold_count">已售出：{{ product.sold_count }}件</span>
    </div>
    
    <!-- 規格選擇 -->
    <div class="mt-4">
      <h3 class="text-sm font-semibold text-gray-700 mb-2">選擇規格：</h3>
      <div class="flex flex-wrap gap-2">
        <button
          v-for="spec in specs"
          :key="spec.key"
          :class="[
            'spec-btn',
            selectedSpec === spec.key ? 'active' : ''
          ]"
          @click="selectSpec(spec.key)"
        >
          {{ spec.label }}
        </button>
      </div>
    </div>

    <!-- 隨手包價格選擇器 -->
    <div v-if="selectedSpec === 'sample'" class="mt-4">
      <h3 class="text-sm font-semibold text-gray-700 mb-2">選擇隨手包價格：</h3>
      <div class="flex flex-wrap gap-2">
        <button
          v-for="priceOption in samplePriceOptions"
          :key="priceOption.price"
          :class="[
            'price-btn',
            selectedSamplePrice === priceOption.price ? 'active' : ''
          ]"
          @click="selectSamplePrice(priceOption.price)"
        >
          NT${{ priceOption.price }}
        </button>
      </div>
    </div>

    <!-- 價格顯示 -->
    <div class="flex items-center gap-2 mt-4">
      <span class="text-red-500 font-bold text-2xl">NT${{ currentPrice }}</span>
      <span v-if="currentOriginalPrice" class="line-through text-gray-400">NT${{ currentOriginalPrice }}</span>
      <span v-if="currentOriginalPrice && currentPrice && savings > 0" class="ml-2 text-xs bg-red-100 text-red-500 px-2 py-0.5 rounded">省{{ savings }}</span>
    </div>

    <!-- 重量資訊 -->
    <div class="mt-2 text-sm text-gray-600">
      <span>重量：{{ currentWeight }}</span>
    </div>

    <div class="flex gap-2 mt-4" v-if="product.status !== 'notification'">
      <button class="btn-main" @click="addToCart">加入購物車</button>
      <button class="btn-buy" @click="buyNow">立即結帳</button>
    </div>
    <div class="flex gap-2 mt-4" v-else>
      <button class="btn-notify" disabled>貨到通知</button>
    </div>
    <div class="mt-2 text-xs text-gray-500" v-if="product.status === 'notification'">
      此商品目前僅供公告，暫不開放購買
    </div>
    <div class="mt-4 text-xs text-gray-500" v-if="product.id">商品編號：{{ product.id }}</div>
    <div class="mt-2 text-xs text-gray-500" v-if="product.category_name">分類：{{ product.category_name }}</div>
    <div class="flex gap-2 mt-2" v-if="product.share_links">
      <a v-if="product.share_links.facebook" :href="product.share_links.facebook" target="_blank" class="share-btn facebook">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
          <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
        </svg>
        分享 Facebook
      </a>
      <a v-if="product.share_links.line" :href="product.share_links.line" target="_blank" class="share-btn line">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
          <path d="M19.365 9.863c.349 0 .63.285.63.631 0 .345-.281.63-.63.63H17.61v1.125h1.755c.349 0 .63.283.63.63 0 .344-.281.629-.63.629h-2.386c-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63h2.386c.346 0 .627.285.627.63 0 .349-.281.63-.63.63H17.61v1.125h1.755zm-3.855 3.016c0 .27-.174.51-.432.596-.064.021-.133.031-.199.031-.211 0-.391-.09-.51-.25l-2.443-3.317v2.94c0 .344-.279.629-.631.629-.346 0-.626-.285-.626-.629V8.108c0-.27.173-.51.43-.595.06-.023.136-.033.194-.033.195 0 .375.104.495.254l2.462 3.33V8.108c0-.345.282-.63.63-.63.345 0 .63.285.63.63v4.771zm-5.741 0c0 .344-.282.629-.631.629-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63.346 0 .628.285.628.63v4.771zm-2.466.629H4.917c-.345 0-.63-.285-.63-.629V8.108c0-.345.285-.63.63-.63.348 0 .63.285.63.63v4.141h1.756c.348 0 .629.283.629.63 0 .344-.282.629-.629.629M24 10.314C24 4.943 18.615.572 12 .572S0 4.943 0 10.314c0 5.372 5.385 9.743 12 9.743s12-4.371 12-9.743z"/>
        </svg>
        分享 LINE
      </a>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'

const props = defineProps<{ product: any }>()
const emit = defineEmits(['add-to-cart', 'buy-now'])

// 規格選項
const specs = [
  { key: 'large', label: '600g', weight: '600g', priceRatio: 1 },
  { key: 'small', label: '300g', weight: '300g', priceRatio: 0.5 },
  { key: 'sample', label: '隨手包', weight: '100g', priceRatio: 0.167 }
]

const selectedSpec = ref('small') // 預設選擇300g
const selectedSamplePrice = ref(100) // 預設隨手包價格

// 生成隨手包價格選項 (100元到600g價格)
const samplePriceOptions = computed(() => {
  const maxPrice = props.product.price_large || props.product.price_small * 2 || 600
  const options = []
  
  // 從100元開始，每50元一個選項，直到600g價格
  for (let price = 100; price <= maxPrice; price += 50) {
    options.push({ price })
  }
  
  // 如果600g價格不是50的倍數，也要加入
  if (maxPrice % 50 !== 0 && !options.find(opt => opt.price === maxPrice)) {
    options.push({ price: maxPrice })
  }
  
  return options
})

// 計算當前價格
const currentPrice = computed(() => {
  if (selectedSpec.value === 'large') {
    return props.product.price_large || 0
  } else if (selectedSpec.value === 'small') {
    return props.product.price_small || 0
  } else if (selectedSpec.value === 'sample') {
    return selectedSamplePrice.value
  }
  return props.product.price_small || 0
})

// 計算當前重量
const currentWeight = computed(() => {
  if (selectedSpec.value === 'large') {
    return '600g'
  } else if (selectedSpec.value === 'small') {
    return '300g'
  } else if (selectedSpec.value === 'sample') {
    // 根據選擇的價格計算重量
    const basePrice = props.product.price_large || props.product.price_small * 2 || 600
    const baseWeight = 600 // 600g
    const calculatedWeight = Math.round((selectedSamplePrice.value / basePrice) * baseWeight)
    return `${calculatedWeight}g`
  }
  return '300g'
})

// 計算當前原價（根據規格調整）
const currentOriginalPrice = computed(() => {
  if (selectedSpec.value === 'large') {
    return props.product.origin_price || 0
  } else if (selectedSpec.value === 'small') {
    // 300g的原價 = 600g原價的一半
    return Math.round((props.product.origin_price || 0) * 0.5)
  } else if (selectedSpec.value === 'sample') {
    // 隨手包的原價 = 根據重量比例計算
    const baseOriginalPrice = props.product.origin_price || 0
    const basePrice = props.product.price_large || props.product.price_small * 2 || 600
    const priceRatio = selectedSamplePrice.value / basePrice
    return Math.round(baseOriginalPrice * priceRatio)
  }
  return 0
})

// 計算省錢金額
const savings = computed(() => {
  return currentOriginalPrice.value - currentPrice.value
})

// 找到當前選擇的 spec 物件
const currentSpecObj = computed(() => {
  if (!props.product.specs || !Array.isArray(props.product.specs)) return null
  return props.product.specs.find((s: any) => s.name === selectedSpec.value || s.key === selectedSpec.value) || null
})

// 選擇規格
function selectSpec(specKey: string) {
  selectedSpec.value = specKey
  // 如果選擇隨手包，設定預設價格
  if (specKey === 'sample') {
    const options = samplePriceOptions.value
    if (options.length > 0) {
      selectedSamplePrice.value = options[0].price
    }
  }
}

// 選擇隨手包價格
function selectSamplePrice(price: number) {
  selectedSamplePrice.value = price
}

// 加入購物車
function addToCart() {
  emit('add-to-cart', {
    product: props.product,
    spec: selectedSpec.value,
    spec_id: currentSpecObj.value ? currentSpecObj.value.id : undefined,
    price: currentPrice.value,
    weight: currentWeight.value
  })
}

// 立即結帳
function buyNow() {
  emit('buy-now', {
    product: props.product,
    spec: selectedSpec.value,
    spec_id: currentSpecObj.value ? currentSpecObj.value.id : undefined,
    price: currentPrice.value,
    weight: currentWeight.value
  })
}
</script>

<style scoped>
.spec-btn {
  background: #f3e2c7;
  color: #b85c38;
  border: none;
  border-radius: 1em;
  padding: 0.5em 1.2em;
  font-size: 0.9em;
  font-weight: 600;
  cursor: pointer;
  transition: background .2s, color .2s;
}

.spec-btn.active, .spec-btn:hover {
  background: #b85c38;
  color: #fffbe8;
}

.price-btn {
  background: #e8f4fd;
  color: #2c5aa0;
  border: none;
  border-radius: 0.5em;
  padding: 0.4em 1em;
  font-size: 0.85em;
  font-weight: 600;
  cursor: pointer;
  transition: background .2s, color .2s;
}

.price-btn.active, .price-btn:hover {
  background: #2c5aa0;
  color: white;
}

.btn-main {
  background: #b85c38;
  color: white;
  border: none;
  padding: 0.75em 1.5em;
  border-radius: 0.5em;
  font-weight: 600;
  cursor: pointer;
  transition: background .2s;
}

.btn-main:hover {
  background: #a04a2e;
}

.btn-buy {
  background: #e74c3c;
  color: white;
  border: none;
  padding: 0.75em 1.5em;
  border-radius: 0.5em;
  font-weight: 600;
  cursor: pointer;
  transition: background .2s;
}

.btn-buy:hover {
  background: #c0392b;
}

.btn-notify {
  @apply bg-gray-400 text-white font-bold py-2 px-4 rounded cursor-not-allowed;
}

.share-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 1rem;
  border-radius: 0.5rem;
  text-decoration: none;
  font-size: 0.875rem;
  font-weight: 500;
  transition: all 0.2s;
}

.share-btn.facebook {
  background: #1877f2;
  color: white;
}

.share-btn.facebook:hover {
  background: #166fe5;
  transform: translateY(-1px);
}

.share-btn.line {
  background: #00b900;
  color: white;
}

.share-btn.line:hover {
  background: #009900;
  transform: translateY(-1px);
}
</style> 