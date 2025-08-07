<template>
  <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40" @click.self="$emit('close')">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6 relative animate-fade-in" @click.stop>
      <button class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 text-2xl" @click="$emit('close')">&times;</button>
      <div class="flex gap-4">
        <img 
          :src="getProductImage()" 
          :alt="product.name" 
          class="w-28 h-28 object-cover rounded-lg border" 
        />
        <div class="flex-1 flex flex-col justify-between">
          <div>
            <h2 class="text-lg font-bold text-gray-900 mb-1">{{ product.name }}</h2>
            <div class="text-amber-700 text-xl font-bold mb-2">NT${{ product.price }}</div>
            <div v-if="product.weight" class="text-sm text-gray-500">重量：{{ product.weight }}</div>
          </div>
          <div class="flex items-center gap-2 mt-2">
            <button @click="decrease" class="w-8 h-8 rounded bg-gray-100 hover:bg-gray-200 text-xl">-</button>
            <span class="w-8 text-center">{{ quantity }}</span>
            <button @click="increase" class="w-8 h-8 rounded bg-gray-100 hover:bg-gray-200 text-xl">+</button>
          </div>
        </div>
      </div>
      <button
        class="w-full mt-6 py-3 rounded bg-amber-700 hover:bg-amber-800 text-white font-bold text-lg transition"
        @click="addToCart"
        :disabled="loading"
      >
        {{ loading ? '加入中...' : '加入購物車' }}
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import { useCartStore } from '@/stores/cart'
import { getImageUrl } from '@/utils/imageUtils'

const props = defineProps<{
  show: boolean
  product: {
    id: number
    name: string
    price: number
    image?: string
    spec?: string // Added spec to product type
    weight?: string // 新增 weight
    spec_id?: number // 新增: 新增 spec_id
    primary_image?: { image_path: string } // 新增 primary_image
    images?: (string | { image_path: string })[] // 更新 images 類型定義
  }
}>()
const emit = defineEmits(['close', 'added'])

const cartStore = useCartStore()
const quantity = ref(1)
const loading = ref(false)

// 獲取商品圖片的函數
function getProductImage() {
  console.log('Getting product image for:', props.product)
  console.log('Primary image:', props.product.primary_image)
  console.log('Images array:', props.product.images)
  console.log('Old image prop:', props.product.image)
  
  // 優先使用 primary_image
  if (props.product.primary_image?.image_path) {
    const imageUrl = getImageUrl(props.product.primary_image.image_path)
    console.log('Using primary_image:', imageUrl)
    return imageUrl
  }
  
  // 如果沒有 primary_image，使用 images 陣列的第一張
  if (props.product.images && props.product.images.length > 0) {
    const firstImage = props.product.images[0]
    if (typeof firstImage === 'string') {
      const imageUrl = getImageUrl(firstImage)
      console.log('Using first image (string):', imageUrl)
      return imageUrl
    } else if (firstImage?.image_path) {
      const imageUrl = getImageUrl(firstImage.image_path)
      console.log('Using first image (object):', imageUrl)
      return imageUrl
    }
  }
  
  // 如果還是沒有，使用舊的 image 屬性
  if (props.product.image) {
    const imageUrl = getImageUrl(props.product.image)
    console.log('Using old image prop:', imageUrl)
    return imageUrl
  }
  
  // 最後使用預設圖片
  console.log('Using placeholder image')
  return '/images/placeholder.jpg'
}

watch(() => props.show, (val) => {
  if (val) quantity.value = 1
})

function increase() {
  quantity.value++
}
function decrease() {
  if (quantity.value > 1) quantity.value--
}

async function addToCart() {
  loading.value = true
  // 新增 price 傳遞，型別保證
  const price = props.product.spec === 'sample' ? props.product.price : undefined
  await cartStore.addToCart(
    props.product.id,
    quantity.value,
    props.product.spec,
    price,
    props.product.weight, // 新增 weight 傳遞
    props.product.spec_id // 新增: 傳遞 spec_id
  )
  loading.value = false
  emit('added')
  emit('close')
}
</script>

<style scoped>
.animate-fade-in {
  animation: fadeIn .2s;
}
@keyframes fadeIn {
  from { opacity: 0; transform: scale(0.97); }
  to { opacity: 1; transform: scale(1); }
}
</style> 