<template>
  <div class="coupon-view">
    <h1>我的優惠券</h1>
    <div v-if="coupons.length === 0" class="empty">目前沒有可用優惠券</div>
    <div v-for="coupon in coupons" :key="coupon.code" class="coupon-item">
      <div class="desc">{{ coupon.description }}</div>
      <div class="code">{{ coupon.code }}</div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'
const coupons = ref([])
onMounted(async () => {
  try {
    const res = await axios.get('http://127.0.0.1:8000/api/v1/coupons')
    if (res.data.success) {
      coupons.value = res.data.coupons
    }
  } catch {}
})
</script>

<style scoped>
.coupon-view {
  max-width: 420px;
  margin: 2rem auto;
  background: #fffbe8;
  border-radius: 1.2rem;
  box-shadow: 0 4px 24px #e0c68a33;
  padding: 2.5rem 2rem 2rem 2rem;
  font-family: 'Noto Sans TC', 'Segoe UI', Arial, sans-serif;
  color: #a67c00;
}
.coupon-view h1 {
  text-align: center;
  font-size: 1.5rem;
  margin-bottom: 1.5rem;
  color: #b8860b;
}
.coupon-item {
  background: #fffdfa;
  border-radius: 0.7rem;
  box-shadow: 0 1px 4px #e0c68a22;
  padding: 1rem 1.2rem;
  margin-bottom: 1rem;
  color: #a67c00;
  font-size: 1rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.code {
  font-weight: 700;
  color: #b8860b;
  font-size: 1.1rem;
}
.empty {
  color: #b8860b;
  text-align: center;
  margin: 1.5rem 0;
}
</style> 