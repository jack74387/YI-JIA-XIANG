<template>
  <div class="points-view">
    <h1>紅利點數</h1>
    <div class="balance">目前點數：<b>{{ points }}</b></div>
    <div class="history">
      <h2>點數紀錄</h2>
      <div v-if="history.length === 0" class="empty">尚無點數紀錄</div>
      <div v-for="item in history" :key="item.id" class="history-item">
        <span>{{ item.date }}</span>
        <span>{{ item.desc }}</span>
        <span :class="item.change > 0 ? 'plus' : 'minus'">{{ item.change > 0 ? '+' : '' }}{{ item.change }}</span>
      </div>
    </div>
    <div v-if="loading" class="loading">載入中...</div>
    <div v-if="error" class="error">{{ error }}</div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'
const points = ref(0)
const history = ref([])
const loading = ref(false)
const error = ref('')
onMounted(async () => {
  loading.value = true
  try {
    const res = await axios.get('http://127.0.0.1:8000/api/v1/points')
    if (res.data.success) {
      points.value = res.data.points
      history.value = res.data.history
    }
  } catch (e) {
    error.value = '無法取得點數資料'
  } finally {
    loading.value = false
  }
})
</script>

<style scoped>
.points-view {
  max-width: 420px;
  margin: 2rem auto;
  background: #fffbe8;
  border-radius: 1.2rem;
  box-shadow: 0 4px 24px #e0c68a33;
  padding: 2.5rem 2rem 2rem 2rem;
  font-family: 'Noto Sans TC', 'Segoe UI', Arial, sans-serif;
  color: #a67c00;
}
.points-view h1 {
  text-align: center;
  font-size: 1.5rem;
  margin-bottom: 1.5rem;
  color: #b8860b;
}
.balance {
  font-size: 1.15rem;
  color: #b8860b;
  margin-bottom: 1.2rem;
  text-align: center;
}
.history h2 {
  color: #b8860b;
  font-size: 1.1rem;
  margin-bottom: 0.7rem;
}
.history-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: #fffdfa;
  border-radius: 0.5rem;
  padding: 0.5em 0.8em;
  margin-bottom: 0.5em;
  color: #a67c00;
  font-size: 1rem;
  box-shadow: 0 1px 4px #e0c68a22;
}
.plus {
  color: #06c755;
  font-weight: 700;
}
.minus {
  color: #b8860b;
  font-weight: 700;
}
.empty {
  color: #b8860b;
  text-align: center;
  margin: 1.5rem 0;
}
.loading {
  color: #b85c38;
  text-align: center;
  margin: 1.5rem 0;
}
.error {
  color: #b85c38;
  background: #fff3e0;
  border-radius: 0.5rem;
  padding: 0.5rem 1rem;
  margin-bottom: 1rem;
  text-align: center;
}
</style> 