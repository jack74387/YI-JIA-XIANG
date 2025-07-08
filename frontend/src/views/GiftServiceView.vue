<template>
  <div class="gift-service">
    <h1>禮盒加值服務</h1>
    <div class="gift-options">
      <label class="option">
        <input type="checkbox" v-model="giftWrap" />
        <span>精美禮盒包裝（+NT$80）</span>
      </label>
      <label class="option">
        <input type="checkbox" v-model="card" />
        <span>代寫祝福卡片（+NT$30）</span>
      </label>
      <textarea v-if="card" v-model="cardMessage" placeholder="請輸入祝福內容（限50字）" maxlength="50"></textarea>
    </div>
    <button class="submit-btn" @click="submit">送出加值服務</button>
    <div v-if="submitted" class="success-msg">已成功加入加值服務！</div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
const giftWrap = ref(false)
const card = ref(false)
const cardMessage = ref('')
const submitted = ref(false)
function submit() {
  submitted.value = true
  setTimeout(() => (submitted.value = false), 2000)
}
</script>

<style scoped>
.gift-service {
  max-width: 420px;
  margin: 2rem auto;
  background: #fffbe8;
  border-radius: 1.2rem;
  box-shadow: 0 4px 24px #e0c68a33;
  padding: 2.5rem 2rem 2rem 2rem;
  font-family: 'Noto Sans TC', 'Segoe UI', Arial, sans-serif;
  color: #a67c00;
}
.gift-service h1 {
  text-align: center;
  font-size: 1.5rem;
  margin-bottom: 1.5rem;
  color: #b8860b;
}
.gift-options {
  display: flex;
  flex-direction: column;
  gap: 1.2rem;
  margin-bottom: 1.5rem;
}
.option {
  display: flex;
  align-items: center;
  gap: 0.7rem;
  font-size: 1.08rem;
  background: #f9f6f1;
  border-radius: 0.7rem;
  padding: 0.7rem 1rem;
  box-shadow: 0 1px 4px #e0c68a22;
  transition: background 0.2s;
}
.option:hover {
  background: #ffe9b2;
}
textarea {
  width: 100%;
  min-height: 60px;
  border-radius: 0.5rem;
  border: 1px solid #e0c68a;
  padding: 0.7rem;
  font-size: 1rem;
  background: #fffdfa;
  color: #a67c00;
  margin-top: 0.5rem;
  box-shadow: 0 1px 4px #e0c68a22;
  transition: border 0.2s;
}
textarea:focus {
  border: 1.5px solid #b8860b;
}
.submit-btn {
  width: 100%;
  margin-top: 1.2rem;
  padding: 0.7em 0;
  background: linear-gradient(90deg, #f7e7c4 0%, #f3d9a4 100%);
  color: #a67c00;
  border: none;
  border-radius: 2em;
  font-size: 1.1rem;
  font-weight: 700;
  box-shadow: 0 2px 8px #e0c68a22;
  cursor: pointer;
  transition: background 0.2s, transform 0.2s, box-shadow 0.2s;
}
.submit-btn:hover {
  background: linear-gradient(90deg, #ffe9b2 0%, #f7d08a 100%);
  color: #fff;
  transform: scale(1.04);
  box-shadow: 0 4px 16px #e0c68a44;
}
.success-msg {
  margin-top: 1.2rem;
  text-align: center;
  color: #fff;
  background: #b8860b;
  border-radius: 1em;
  padding: 0.7em 0;
  font-weight: 600;
  letter-spacing: 1px;
  animation: fadeIn 0.5s;
}
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}
</style> 