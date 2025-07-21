<template>
  <div class="order-detail">
    <h1>訂單詳情</h1>
    <div class="order-info">
      <div><b>訂單編號：</b>{{ order.id }}</div>
      <div><b>狀態：</b>{{ order.status_text || order.status }}</div>
      <div><b>原始金額：</b>NT$ {{ order.total?.toLocaleString?.() ?? order.total ?? '-' }}</div>
      <div v-if="order.discount && order.discount > 0"><b>折扣：</b>-NT${{ order.discount?.toLocaleString?.() ?? order.discount }}</div>
      <div v-if="order.point_discount && order.point_discount > 0"><b>點數折抵：</b>-NT${{ order.point_discount?.toLocaleString?.() ?? order.point_discount }}</div>
      <div><b>折抵後金額：</b>NT$ {{ order.final_amount?.toLocaleString?.() ?? order.final_amount ?? order.total }}</div>
    </div>
    <div class="order-items">
      <h2>商品明細</h2>
      <div v-for="item in order.items || []" :key="item.id" class="item-row">
        <img :src="getImageUrl(
          item.product?.primary_image?.image_path
          || (item.product?.images && item.product.images[0]?.image_path)
          || item.image
        ) || '/images/placeholder.jpg'" :alt="item.name" class="w-12 h-12 object-cover rounded inline-block mr-3" />
        <span>{{ item.name }}</span>
        <span>x{{ item.quantity }}</span>
        <span>NT$ {{ item.price }}</span>
        <span v-if="item.weight">（{{ item.weight }}）</span>
        <span v-else-if="item.spec_text">（{{ item.spec_text }}）</span>
      </div>
    </div>
    <div class="flex gap-3 mt-6">
      <button class="btn-main flex items-center"><svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h18v2a2 2 0 01-2 2H5a2 2 0 01-2-2V3zm0 0v16a2 2 0 002 2h14a2 2 0 002-2V3"/></svg>再次購買</button>
      <button class="btn-sub flex items-center"><svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8s-9-3.582-9-8a9 9 0 1118 0z"/></svg>聯絡客服</button>
    </div>
    <ServiceNavButtons />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'
import ServiceNavButtons from '@/components/ServiceNavButtons.vue'

const route = useRoute()
const order = ref<any>({})

onMounted(async () => {
  try {
    const orderId = route.params.id
    const res = await axios.get(`/api/v1/orders/${orderId}`)
    if (res.data.success) {
      order.value = res.data.order
    }
  } catch (e) {
    order.value = { id: '-', status: '查無訂單', items: [] }
  }
})

function getImageUrl(imagePath: string | { image_path: string } | undefined) {
  if (!imagePath) return null
  if (typeof imagePath === 'object' && imagePath.image_path) imagePath = imagePath.image_path
  if (typeof imagePath !== 'string') return null
  if (imagePath.startsWith('http')) return imagePath
  if (imagePath.startsWith('/storage')) return '/storage' + imagePath
  if (imagePath.startsWith('/')) return '/storage' + imagePath
  return imagePath
}
</script>

<style scoped>
.order-detail {
  max-width: 420px;
  margin: 2rem auto;
  background: #fffbe8;
  border-radius: 1.2rem;
  box-shadow: 0 4px 24px #e0c68a33;
  padding: 2.5rem 2rem 2rem 2rem;
  font-family: 'Noto Sans TC', 'Segoe UI', Arial, sans-serif;
  color: #a67c00;
}
.order-detail h1 {
  text-align: center;
  font-size: 1.5rem;
  margin-bottom: 1.5rem;
  color: #b8860b;
}
.order-info {
  background: #fffdfa;
  border-radius: 0.7rem;
  box-shadow: 0 1px 4px #e0c68a22;
  padding: 1rem 1.2rem;
  margin-bottom: 1.2rem;
  color: #a67c00;
  font-size: 1rem;
}
.order-items h2 {
  color: #b8860b;
  font-size: 1.1rem;
  margin-bottom: 0.7rem;
}
.item-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: #fffbe8;
  border-radius: 0.5rem;
  padding: 0.5em 0.8em;
  margin-bottom: 0.5em;
  color: #a67c00;
  font-size: 1rem;
  box-shadow: 0 1px 4px #e0c68a22;
}
.btn-main {
  background: #b8860b;
  color: #fff;
  border-radius: 2em;
  padding: 0.6em 1.6em;
  font-size: 1rem;
  font-weight: 700;
  box-shadow: 0 2px 8px #e0c68a22;
  border: none;
  transition: background 0.2s, color 0.2s, transform 0.2s;
  display: flex;
  align-items: center;
}
.btn-main:hover {
  background: #a67c00;
  color: #fffbe8;
  transform: scale(1.04);
}
.btn-sub {
  background: #fff;
  color: #a67c00;
  border: 1.5px solid #e0c68a;
  border-radius: 2em;
  padding: 0.6em 1.6em;
  font-size: 1rem;
  font-weight: 700;
  box-shadow: 0 2px 8px #e0c68a22;
  transition: background 0.2s, color 0.2s, transform 0.2s;
  display: flex;
  align-items: center;
}
.btn-sub:hover {
  background: #ffe9b2;
  color: #b8860b;
  transform: scale(1.04);
}
</style> 