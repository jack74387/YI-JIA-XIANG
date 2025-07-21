<template>
  <div class="group-order">
    <h1>團購 / 企業訂購</h1>
    <form @submit.prevent="submit">
      <div class="form-row">
        <label>聯絡人姓名</label>
        <input v-model="name" required />
      </div>
      <div class="form-row">
        <label>聯絡電話</label>
        <input v-model="phone" required />
      </div>
      <div class="form-row">
        <label>電子郵件</label>
        <input v-model="email" type="email" required />
      </div>
      <div class="form-row">
        <label>需求說明</label>
        <textarea v-model="desc" placeholder="請簡述訂購數量、品項、到貨需求等" required></textarea>
      </div>
      <div v-if="error" class="error">{{ error }}</div>
      <button class="submit-btn flex items-center justify-center" :disabled="loading">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        {{ loading ? '送出中...' : '送出需求' }}
      </button>
      <button class="cs-btn flex items-center" type="button" @click="contactCS">
        <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184C17.437 1.13 14.09.015 10.5.015 4.703.015 0 3.94 0 8.8c0 2.23 1.09 4.25 2.91 5.77-.13.44-.82 2.77-.85 2.96 0 .08.02.16.07.22.06.07.15.11.24.11.29 0 2.09-.41 3.36-.74 1.44.4 2.98.62 4.77.62 5.797 0 10.5-3.925 10.5-8.785 0-1.87-.89-3.63-2.385-5.175zM10.5 15.2c-1.7 0-3.25-.22-4.62-.62l-.32-.09-2.17.48.46-1.5-.25-.2C2.01 11.8 1.1 10.36 1.1 8.8c0-3.7 4.13-6.715 9.4-6.715 3.23 0 6.26 1.01 8.13 2.77 1.23 1.19 1.91 2.56 1.91 4.025 0 3.7-4.13 6.72-9.4 6.72z"/></svg>
        聯絡客服
      </button>
      <button class="btn-sub mt-2 flex items-center mx-auto" type="button">
        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        下載團購單
      </button>
    </form>
    <div v-if="submitted" class="success-msg">已送出，我們將盡快與您聯繫！</div>
    <ServiceNavButtons />
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import axios from 'axios'
import ServiceNavButtons from '@/components/ServiceNavButtons.vue'
const name = ref('')
const phone = ref('')
const email = ref('')
const desc = ref('')
const submitted = ref(false)
const error = ref('')
const loading = ref(false)
async function submit() {
  error.value = ''
  loading.value = true
  try {
    const res = await axios.post('/api/v1/group-orders', {
      name: name.value,
      phone: phone.value,
      email: email.value,
      desc: desc.value
    })
    if (res.data.success) {
      submitted.value = true
      setTimeout(() => (submitted.value = false), 2000)
    } else {
      error.value = res.data.message || '送出失敗'
    }
  } catch (e) {
    error.value = '送出失敗，請檢查資料'
  } finally {
    loading.value = false
  }
}
function contactCS() {
  window.open('https://line.me/R/ti/p/@yourlineid', '_blank')
}
</script>

<style scoped>
.group-order {
  max-width: 480px;
  margin: 2rem auto;
  background: #fffbe8;
  border-radius: 1.2rem;
  box-shadow: 0 4px 24px #e0c68a33;
  padding: 2.5rem 2rem 2rem 2rem;
  font-family: 'Noto Sans TC', 'Segoe UI', Arial, sans-serif;
  color: #a67c00;
}
.group-order h1 {
  text-align: center;
  font-size: 1.5rem;
  margin-bottom: 1.5rem;
  color: #b8860b;
}
form {
  display: flex;
  flex-direction: column;
  gap: 1.2rem;
}
.form-row {
  display: flex;
  flex-direction: column;
  gap: 0.4rem;
}
input, textarea {
  border-radius: 0.7rem;
  border: 1px solid #e0c68a;
  padding: 0.7rem 1rem;
  font-size: 1rem;
  background: #fffdfa;
  color: #a67c00;
  box-shadow: 0 1px 4px #e0c68a22;
  transition: border 0.2s;
}
input:focus, textarea:focus {
  border: 1.5px solid #b8860b;
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
.submit-btn {
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
  display: flex;
  align-items: center;
  justify-content: center;
}
.submit-btn:hover {
  background: linear-gradient(90deg, #ffe9b2 0%, #f7d08a 100%);
  color: #fff;
  transform: scale(1.04);
  box-shadow: 0 4px 16px #e0c68a44;
}
.cs-btn {
  margin-top: 0.7rem;
  padding: 0.6em 0;
  background: #06c755;
  color: #fff;
  border: none;
  border-radius: 2em;
  font-size: 1rem;
  font-weight: 600;
  box-shadow: 0 2px 8px #e0c68a22;
  cursor: pointer;
  transition: background 0.2s, transform 0.2s, box-shadow 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
}
.cs-btn:hover {
  background: #b8860b;
  color: #fffbe8;
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
.error {
  color: #b85c38;
  background: #fff3e0;
  border-radius: 0.5rem;
  padding: 0.5rem 1rem;
  margin-bottom: 1rem;
  text-align: center;
}
</style> 