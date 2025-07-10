<template>
  <div class="product-detail flex flex-col md:flex-row gap-8">
    <!-- 左側圖片區 -->
    <ProductImageGallery :images="product.images || []" :weight="product.weight || ''" />
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
import { ref, onMounted } from 'vue'
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
  const id = route.params.id
  try {
    const res = await axios.get(`/api/v1/products/${id}`)
    product.value = res.data.product
  } catch (error) {
    console.error('載入商品失敗:', error)
  }
})

function addToCart(specData: any) {
  const { product: productData, spec, price, weight } = specData
  
  selectedProduct.value = {
    id: productData.id,
    name: `${productData.name}（${getSpecLabel(spec)}）`,
    price: price,
    spec, // <--- 修正: 加入 spec
    image: productData.images?.[0] || productData.image
  }
  showAddToCart.value = true
}

function buyNow(specData: any) {
  const { product: productData, spec, price, weight } = specData
  
  // 先加入購物車，然後跳轉到結帳頁面
  addToCart(specData)
  // 這裡可以添加跳轉到結帳頁面的邏輯
  // router.push('/checkout')
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
</script>

<style scoped>
.product-detail { 
  margin: 2rem auto; 
  max-width: 1100px; 
  padding: 0 1rem;
}
</style>
