<template>
  <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6 relative animate-fade-in">
      <button class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 text-2xl" @click="$emit('close')">&times;</button>
      <div class="flex gap-4">
        <img :src="getImageUrl(product.primary_image?.image_path || (Array.isArray(product.images) && product.images[0]?.image_path) || product.image) || '/images/placeholder.jpg'" :alt="product.name" class="w-28 h-28 object-cover rounded-lg border" />
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
    images?: { image_path: string }[] // 新增 images
  }
}>()
const emit = defineEmits(['close', 'added'])

const cartStore = useCartStore()
const quantity = ref(1)
const loading = ref(false)

watch(() => props.show, (val) => {
  if (val) quantity.value = 1
})

function increase() {
  quantity.value++
}
function decrease() {
  if (quantity.value > 1) quantity.value--
}

function getImageUrl(imagePath: string | undefined) {
  if (!imagePath) return null
  if (imagePath.startsWith('http')) return imagePath
  if (imagePath.startsWith('/storage')) return `${window.location.protocol}//${window.location.hostname}:8000${imagePath}`
  if (imagePath.startsWith('/')) return `${window.location.protocol}//${window.location.hostname}:8000${imagePath}`
  return imagePath
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