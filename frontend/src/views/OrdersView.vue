<template>
  <div class="orders-view">
    <h1>我的訂單</h1>
    <div v-if="loading" class="loading">載入中...</div>
    <div v-else-if="orders.length === 0" class="empty">目前沒有訂單</div>
    <div v-else class="order-list">
      <div v-for="order in orders" :key="order.id" class="order-item" @click="goDetail(order.id)">
        <div class="id">#{{ order.id }}</div>
        <div class="amount">NT${{ order.amount }}</div>
        <div class="status">{{ order.status }}</div>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'
const orders = ref([])
const loading = ref(false)
function goDetail(id: number) {
  window.location.href = `/orders/${id}`
}
onMounted(async () => {
  loading.value = true
  try {
    const res = await axios.get('http://127.0.0.1:8000/api/v1/user/orders')
    if (res.data.success) {
      orders.value = res.data.orders
    }
  } finally {
    loading.value = false
  }
})
</script>
<style scoped>
.orders-view { max-width: 600px; margin: 2rem auto; background: #fffbe8; border-radius: 1.2rem; box-shadow: 0 4px 24px #e0c68a33; padding: 2.5rem 2rem 2rem 2rem; color: #a67c00; }
.orders-view h1 { text-align: center; font-size: 1.5rem; margin-bottom: 1.5rem; color: #b8860b; }
.order-list { display: flex; flex-direction: column; gap: 1.2rem; }
.order-item { background: #fffdfa; border-radius: 0.7rem; box-shadow: 0 1px 4px #e0c68a22; padding: 1rem 1.2rem; display: flex; justify-content: space-between; align-items: center; cursor: pointer; transition: box-shadow 0.2s; }
.order-item:hover { box-shadow: 0 4px 16px #b8860b33; }
.id { font-weight: 700; color: #b8860b; }
.amount { color: #b85c38; font-size: 1.1rem; font-weight: 600; }
.status { color: #a67c00; font-size: 1rem; }
.loading, .empty { color: #b85c38; text-align: center; margin: 2rem 0; }
</style> 