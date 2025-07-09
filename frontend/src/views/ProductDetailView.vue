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
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'
import ProductImageGallery from '../components/ProductImageGallery.vue'
import ProductInfo from '../components/ProductInfo.vue'
import ProductTabs from '../components/ProductTabs.vue'

const product = ref<any>({})
const route = useRoute()

onMounted(async () => {
  const id = route.params.id
  const res = await axios.get(`/api/v1/products/${id}`)
  product.value = res.data.product
})

function addToCart() { /* ... */ }
function buyNow() { /* ... */ }
</script>

<style scoped>
.product-detail { margin: 2rem auto; max-width: 1100px; }
</style>
