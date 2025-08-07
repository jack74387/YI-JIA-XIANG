<template>
  <div class="product-detail flex flex-col md:flex-row gap-8">
    <!-- 左側圖片區 -->
    <div class="relative">
      <ProductImageGallery :images="getAllImages()" :weight="product.weight || ''" :status="product.status || ''" :title="product.name" />
      <div v-if="product.status === 'notification'" class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-60 z-10">
        <span class="text-lg font-bold bg-black bg-opacity-60 text-white px-6 py-2 rounded">貨到通知</span>
      </div>
    </div>
    <!-- 右側資訊區 -->
    <div class="flex-1">
      <ProductInfo :product="product || {}" @add-to-cart="addToCart" @buy-now="buyNow" />
    </div>
  </div>
  <ProductTabs :product="product || {}" />
  
  <!-- 加入購物車彈窗 -->
  <ProductAddToCartModal
    :show="showAddToCart"
    :product="selectedProduct"
    @close="showAddToCart = false"
    @added="onAddedToCart"
  />
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useCartStore } from '@/stores/cart'
import axios from 'axios'
import ProductImageGallery from '../components/ProductImageGallery.vue'
import ProductInfo from '../components/ProductInfo.vue'
import ProductTabs from '../components/ProductTabs.vue'
import ProductAddToCartModal from '../components/ProductAddToCartModal.vue'

const product = ref<any>({})
const route = useRoute()
const router = useRouter()
const cartStore = useCartStore()

// 購物車相關
const showAddToCart = ref(false)
const selectedProduct = ref<any>(null)

onMounted(async () => {
  await loadProduct()
})

// 監聽路由變化，當商品 ID 改變時重新載入
watch(() => route.params.id, async (newId) => {
  if (newId) {
    await loadProduct()
  }
})

// 載入商品數據
const loadProduct = async () => {
  const id = route.params.id
  try {
    const res = await axios.get(`/api/v1/products/${id}`)
    product.value = res.data.product
    console.log('Loaded product data:', product.value)
    console.log('Primary image from API:', product.value.primary_image)
    console.log('Images from API:', product.value.images)
  } catch (error) {
    console.error('Failed to load product:', error)
  }
}

function addToCart(specData: any) {
  const { product: productData, spec, spec_id, price, weight } = specData
  
  // 調試：檢查圖片數據
  console.log('Product data in addToCart:', productData)
  console.log('Main product data:', product.value)
  console.log('Primary image:', productData.primary_image || product.value.primary_image)
  console.log('Images array:', productData.images || product.value.images)
  
  selectedProduct.value = {
    id: productData.id,
    name: `${productData.name}（${getSpecLabel(spec)}）`,
    price: price,
    spec, // <--- 修正: 加入 spec
    spec_id, // 新增: 傳遞 spec_id
    weight, // <--- 新增: 傳遞 weight
    primary_image: productData.primary_image || product.value.primary_image, // 確保 primary_image 正確傳遞
    images: productData.images || product.value.images // 也傳遞 images 作為備用
  }
  
  console.log('Selected product for modal:', selectedProduct.value)
  showAddToCart.value = true
}

function buyNow(specData: any) {
  const { product: productData, spec, price, weight } = specData
  
  // 先加入購物車，然後跳轉到結帳頁面
  // addToCart(specData)
  // 這裡可以添加跳轉到結帳頁面的邏輯
  router.push('/checkout')
}

function onAddedToCart() {
  cartStore.fetchCart()
}

// 規格標籤轉換
function getSpecLabel(spec: string) {
  if (spec === 'large') return '600g'
  if (spec === 'small') return '300g'
  if (spec === 'sample') return '隨手包'
  return ''
}

// 傳遞主圖+多圖給 ProductImageGallery
function getAllImages() {
  const arr = []
  if (product.value.primary_image?.image_path) arr.push(product.value.primary_image.image_path)
  if (Array.isArray(product.value.images)) {
    for (const img of product.value.images) {
      if (img && !arr.includes(img)) arr.push(img)
    }
  }
  return arr.slice(0, 10)
}
</script>

<style scoped>
.product-detail { 
  margin: 2rem auto; 
  max-width: 1100px; 
  padding: 0 1rem;
}

@media (max-width: 600px) {
  .product-detail {
    flex-direction: column !important;
    gap: 1rem !important;
    padding: 0 1rem;
  }
}
</style>
