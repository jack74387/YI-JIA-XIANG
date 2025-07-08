<template>
  <div class="member-center">
    <h1>會員專區</h1>
    <div class="profile">
      <div class="avatar">👤</div>
      <div class="info">
        <div class="name">{{ user.name || '訪客' }}</div>
        <div class="email">{{ user.email || '尚未登入' }}</div>
        <div class="points">紅利點數：{{ user.points ?? 0 }}</div>
      </div>
    </div>
    <div class="orders">
      <h2>訂單查詢</h2>
      <div v-if="orders.length === 0" class="empty">尚無訂單紀錄</div>
      <div v-for="order in orders" :key="order.id" class="order-item">
        <div>訂單編號：{{ order.id }}</div>
        <div>金額：NT$ {{ order.amount }}</div>
        <div>狀態：{{ order.status }}</div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
const user = ref({ name: '王小明', email: 'test@example.com', points: 120 })
const orders = ref([
  { id: 1001, amount: 680, status: '已完成' },
  { id: 1002, amount: 340, status: '處理中' },
])
</script>

<style scoped>
.member-center {
  max-width: 520px;
  margin: 2rem auto;
  background: #fffbe8;
  border-radius: 1.2rem;
  box-shadow: 0 4px 24px #e0c68a33;
  padding: 2.5rem 2rem 2rem 2rem;
  font-family: 'Noto Sans TC', 'Segoe UI', Arial, sans-serif;
  color: #a67c00;
}
.member-center h1 {
  text-align: center;
  font-size: 1.5rem;
  margin-bottom: 1.5rem;
  color: #b8860b;
}
.profile {
  display: flex;
  align-items: center;
  gap: 1.2rem;
  margin-bottom: 2rem;
}
.avatar {
  width: 64px;
  height: 64px;
  background: #ffe9b2;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2.2rem;
  color: #b8860b;
  box-shadow: 0 2px 8px #e0c68a22;
}
.info .name {
  font-size: 1.15rem;
  font-weight: 700;
  color: #b8860b;
}
.info .email {
  font-size: 1rem;
  color: #a67c00;
}
.info .points {
  font-size: 1rem;
  color: #a67c00;
  margin-top: 0.2rem;
}
.orders {
  margin-top: 1.5rem;
}
.orders h2 {
  font-size: 1.1rem;
  color: #b8860b;
  margin-bottom: 0.7rem;
}
.order-item {
  background: #fffdfa;
  border-radius: 0.7rem;
  box-shadow: 0 1px 4px #e0c68a22;
  padding: 1rem 1.2rem;
  margin-bottom: 1rem;
  color: #a67c00;
  font-size: 1rem;
  transition: box-shadow 0.2s;
}
.order-item:hover {
  box-shadow: 0 4px 16px #b8860b33;
}
.empty {
  color: #b8860b;
  text-align: center;
  margin: 1.5rem 0;
}
</style> 