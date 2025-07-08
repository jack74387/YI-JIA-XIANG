<template>
  <div class="product-detail" v-if="product">
    <img :src="product.primary_image?.image_path || `https://placehold.co/320x320?text=${product.name}`" :alt="product.name" />
    <div class="info">
      <h1>{{ product.name }}</h1>
      <p>{{ product.short_description || product.description }}</p>
      <div class="specs">{{ product.specs }}</div>
      <div class="price">NT$ {{ product.final_price }}</div>
      <button class="add-cart">加入購物車</button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted, computed } from 'vue'
import { useRoute } from 'vue-router'
import { useProductsStore } from '../stores/products'

const route = useRoute()
const store = useProductsStore()
const id = Number(route.params.id)

onMounted(() => {
  store.fetchProduct(id)
})

const product = computed(() => store.product)
</script>

<style scoped>
.product-detail {
  display: flex;
  flex-wrap: wrap;
  gap: 2rem;
  max-width: 900px;
  margin: 2rem auto;
  background: #fff8e1;
  border-radius: 1rem;
  box-shadow: 0 2px 8px #e0c68a44;
  padding: 2rem;
}
.product-detail img {
  width: 320px;
  height: 320px;
  object-fit: cover;
  border-radius: 1rem;
}
.info {
  flex: 1;
  display: flex;
  flex-direction: column;
  justify-content: center;
}
.price {
  color: #b8860b;
  font-size: 1.5rem;
  font-weight: bold;
  margin: 1rem 0;
}
.add-cart {
  background: #b8860b;
  color: #fff;
  border: none;
  border-radius: 0.5rem;
  padding: 0.75rem 2rem;
  font-size: 1.1rem;
  cursor: pointer;
  transition: background 0.2s;
}
.add-cart:hover {
  background: #a0761a;
}
</style>
