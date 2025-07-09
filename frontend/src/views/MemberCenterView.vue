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
    <div class="flex gap-3 mb-4">
      <button class="btn-main flex items-center"><svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 13l6.768-6.768a2 2 0 112.828 2.828L11.828 15.828a4 4 0 01-1.414.94l-3.364 1.122a1 1 0 01-1.263-1.263l1.122-3.364a4 4 0 01.94-1.414z"/></svg>編輯個人資料</button>
      <button class="btn-sub flex items-center"><svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a5 5 0 00-10 0v2M5 9h14a2 2 0 012 2v7a2 2 0 01-2 2H5a2 2 0 01-2-2v-7a2 2 0 012-2z"/></svg>我的優惠券</button>
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
    <button class="btn-sub w-full mt-6 flex items-center justify-center"><svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1"/></svg>登出</button>
    <ServiceNavButtons />
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import ServiceNavButtons from '@/components/ServiceNavButtons.vue'
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