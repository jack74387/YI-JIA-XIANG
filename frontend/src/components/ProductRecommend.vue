<template>
  <div class="recommend">
    <h2>你可能會喜歡</h2>
    <div class="recommend-list">
      <div v-for="item in products" :key="item.id" class="recommend-card">
        <img :src="item.image" :alt="item.name" />
        <div class="name">{{ item.name }}</div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'
const products = ref([])
onMounted(async () => {
  try {
    const res = await axios.get('http://127.0.0.1:8000/api/v1/recommend')
    if (res.data.success) {
      products.value = res.data.products
    }
  } catch {}
})
</script>

<style scoped>
.recommend {
  background: #fffbe8;
  border-radius: 1.2rem;
  box-shadow: 0 2px 8px #e0c68a22;
  padding: 1.5rem 1rem;
  margin: 2rem 0;
  font-family: 'Noto Sans TC', 'Segoe UI', Arial, sans-serif;
}
.recommend h2 {
  color: #b8860b;
  font-size: 1.2rem;
  margin-bottom: 1rem;
}
.recommend-list {
  display: flex;
  gap: 1.2rem;
  overflow-x: auto;
  padding-bottom: 0.5rem;
}
.recommend-card {
  min-width: 120px;
  background: #fffdfa;
  border-radius: 0.8rem;
  box-shadow: 0 1px 4px #e0c68a22;
  text-align: center;
  padding: 0.7rem 0.5rem;
  transition: box-shadow 0.2s, transform 0.2s;
  cursor: pointer;
}
.recommend-card:hover {
  box-shadow: 0 4px 16px #b8860b33;
  transform: scale(1.07);
}
.recommend-card img {
  width: 80px;
  height: 80px;
  object-fit: cover;
  border-radius: 0.5rem;
  margin-bottom: 0.5rem;
  background: #f5f5f5;
}
.name {
  color: #a67c00;
  font-size: 1rem;
  font-weight: 600;
}
</style> 