<template>
  <div class="faq-view">
    <h1>常見問答</h1>
    <div class="faq-list">
      <div v-for="(item, idx) in faqs" :key="idx" class="faq-item">
        <div class="question" @click="toggle(idx)">
          <span>{{ item.q }}</span>
          <span class="arrow" :class="{ open: openIdx === idx }">▼</span>
        </div>
        <div v-if="openIdx === idx" class="answer">{{ item.a }}</div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'
const faqs = ref([])
const openIdx = ref(-1)
function toggle(idx: number) {
  openIdx.value = openIdx.value === idx ? -1 : idx
}
onMounted(async () => {
  try {
    const res = await axios.get('http://127.0.0.1:8000/api/v1/faqs')
    if (res.data.success) {
      faqs.value = res.data.faqs
    }
  } catch {}
})
</script>

<style scoped>
.faq-view {
  max-width: 520px;
  margin: 2rem auto;
  background: #fffbe8;
  border-radius: 1.2rem;
  box-shadow: 0 4px 24px #e0c68a33;
  padding: 2.5rem 2rem 2rem 2rem;
  font-family: 'Noto Sans TC', 'Segoe UI', Arial, sans-serif;
  color: #a67c00;
}
.faq-view h1 {
  text-align: center;
  font-size: 1.5rem;
  margin-bottom: 1.5rem;
  color: #b8860b;
}
.faq-list {
  display: flex;
  flex-direction: column;
  gap: 1.2rem;
}
.faq-item {
  background: #fffdfa;
  border-radius: 0.7rem;
  box-shadow: 0 1px 4px #e0c68a22;
  padding: 1rem 1.2rem;
  transition: box-shadow 0.2s;
}
.faq-item .question {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 1.08rem;
  font-weight: 600;
  cursor: pointer;
  color: #b8860b;
  transition: color 0.2s;
}
.faq-item .question:hover {
  color: #a67c00;
}
.arrow {
  transition: transform 0.2s;
}
.arrow.open {
  transform: rotate(180deg);
}
.answer {
  margin-top: 0.7rem;
  color: #a67c00;
  font-size: 1rem;
  animation: fadeIn 0.4s;
}
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}
</style> 