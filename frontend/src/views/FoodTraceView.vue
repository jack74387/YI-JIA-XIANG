<template>
  <div class="food-trace">
    <h1>食品履歷查詢</h1>
    <div class="search-bar">
      <input v-model="query" placeholder="輸入商品名稱或編號" />
      <button @click="search">查詢</button>
    </div>
    <div v-if="result" class="trace-result">
      <h2>{{ result.name }}</h2>
      <ul>
        <li><b>原料：</b>{{ result.ingredients }}</li>
        <li><b>產地：</b>{{ result.origin }}</li>
        <li><b>過敏原：</b>{{ result.allergens }}</li>
        <li><b>製造日期：</b>{{ result.mfg_date }}</li>
        <li><b>有效日期：</b>{{ result.exp_date }}</li>
      </ul>
    </div>
    <div v-else-if="searched">查無資料，請確認輸入。</div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
const query = ref('')
const result = ref<any>(null)
const searched = ref(false)
function search() {
  // 假資料，實際應呼叫 API
  if (query.value === '黃金經典豬肉條') {
    result.value = {
      name: '黃金經典豬肉條',
      ingredients: '豬肉、糖、鹽、醬油',
      origin: '台灣台東',
      allergens: '大豆、小麥',
      mfg_date: '2025-01-01',
      exp_date: '2025-07-01',
    }
  } else {
    result.value = null
  }
  searched.value = true
}
</script>

<style scoped>
.food-trace {
  max-width: 420px;
  margin: 2rem auto;
  background: #fffbe8;
  border-radius: 1.2rem;
  box-shadow: 0 4px 24px #e0c68a33;
  padding: 2.5rem 2rem 2rem 2rem;
  font-family: 'Noto Sans TC', 'Segoe UI', Arial, sans-serif;
  color: #a67c00;
}
.food-trace h1 {
  text-align: center;
  font-size: 1.5rem;
  margin-bottom: 1.5rem;
  color: #b8860b;
}
.search-bar {
  display: flex;
  gap: 0.7rem;
  margin-bottom: 1.5rem;
}
.search-bar input {
  flex: 1;
  padding: 0.6rem 1rem;
  border-radius: 0.7rem;
  border: 1px solid #e0c68a;
  font-size: 1rem;
  background: #fffdfa;
  color: #a67c00;
  box-shadow: 0 1px 4px #e0c68a22;
  transition: border 0.2s;
}
.search-bar input:focus {
  border: 1.5px solid #b8860b;
}
.search-bar button {
  padding: 0.6rem 1.2rem;
  background: linear-gradient(90deg, #f7e7c4 0%, #f3d9a4 100%);
  color: #a67c00;
  border: none;
  border-radius: 2em;
  font-size: 1rem;
  font-weight: 600;
  box-shadow: 0 2px 8px #e0c68a22;
  cursor: pointer;
  transition: background 0.2s, transform 0.2s, box-shadow 0.2s;
}
.search-bar button:hover {
  background: linear-gradient(90deg, #ffe9b2 0%, #f7d08a 100%);
  color: #fff;
  transform: scale(1.04);
  box-shadow: 0 4px 16px #e0c68a44;
}
.trace-result {
  background: #f9f6f1;
  border-radius: 0.7rem;
  padding: 1.2rem;
  box-shadow: 0 1px 4px #e0c68a22;
  margin-top: 1.2rem;
}
.trace-result h2 {
  color: #b8860b;
  margin-bottom: 0.7rem;
}
.trace-result ul {
  list-style: none;
  padding: 0;
  color: #a67c00;
  font-size: 1.05rem;
}
</style> 