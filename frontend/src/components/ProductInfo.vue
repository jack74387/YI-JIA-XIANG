<template>
  <div>
    <h1 class="text-xl font-bold">{{ product.name }}</h1>
    <div class="text-sm text-gray-500 mb-1" v-if="product.subtitle">{{ product.subtitle }}</div>
    <div class="flex items-center gap-2 mt-1">
      <span class="text-yellow-500 font-bold text-lg" v-if="product.rating">★ {{ product.rating }}</span>
      <span class="text-xs text-gray-400" v-if="product.rating_count">({{ product.rating_count }} 則評價)</span>
      <span class="ml-2 text-xs text-gray-500" v-if="product.sold_count">已售出：{{ product.sold_count }}件</span>
    </div>
    <div class="flex items-center gap-2 mt-2">
      <span class="text-red-500 font-bold text-2xl" v-if="product.price_small">NT${{ product.price_small }}</span>
      <span v-if="product.origin_price" class="line-through text-gray-400">NT${{ product.origin_price }}</span>
      <span v-if="product.origin_price && product.price_small" class="ml-2 text-xs bg-red-100 text-red-500 px-2 py-0.5 rounded">省{{ product.origin_price - product.price_small }}</span>
    </div>
    <div class="flex gap-2 mt-4">
      <button class="btn-main" @click="$emit('add-to-cart')">加入購物車</button>
      <button class="btn-buy" @click="$emit('buy-now')">立即結帳</button>
    </div>
    <div class="mt-4 text-xs text-gray-500" v-if="product.id">商品編號：{{ product.id }}</div>
    <div class="mt-2 text-xs text-gray-500" v-if="product.category_name">分類：{{ product.category_name }}</div>
    <div class="flex gap-2 mt-2" v-if="product.share_links">
      <a v-if="product.share_links.facebook" :href="product.share_links.facebook" target="_blank">分享 Facebook</a>
      <a v-if="product.share_links.line" :href="product.share_links.line" target="_blank">分享 LINE</a>
    </div>
  </div>
</template>
<script setup lang="ts">
const props = defineProps<{ product: any }>()
</script> 