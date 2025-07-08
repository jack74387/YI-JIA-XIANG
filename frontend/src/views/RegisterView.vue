<template>
  <div class="register-view">
    <h1>會員註冊</h1>
    <form @submit.prevent="submit">
      <label>姓名 <input v-model="name" required /></label>
      <label>Email <input v-model="email" type="email" required /></label>
      <label>密碼 <input v-model="password" type="password" required autocomplete="new-password" /></label>
      <label>確認密碼 <input v-model="confirm" type="password" required autocomplete="new-password" /></label>
      <div v-if="error" class="error">{{ error }}</div>
      <button class="register-btn" type="submit" :disabled="loading">
        {{ loading ? '註冊中...' : '註冊' }}
      </button>
    </form>
    <div v-if="submitted" class="success-msg">註冊成功！</div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import axios from 'axios'
const name = ref('')
const email = ref('')
const password = ref('')
const confirm = ref('')
const submitted = ref(false)
const error = ref('')
const loading = ref(false)
async function submit() {
  error.value = ''
  if (password.value !== confirm.value) {
    error.value = '密碼不一致'
    return
  }
  loading.value = true
  try {
    const res = await axios.post('http://127.0.0.1:8000/auth/register', {
      name: name.value,
      email: email.value,
      password: password.value,
      password_confirmation: confirm.value
    })
    if (res.data.success) {
      // 自動登入
      localStorage.setItem('user', JSON.stringify(res.data.user))
      window.location.href = '/'
    } else {
      error.value = res.data.message || '註冊失敗'
    }
  } catch (e) {
    error.value = '註冊失敗，請檢查資料'
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.register-view {
  max-width: 420px;
  margin: 2rem auto;
  background: #fffbe8;
  border-radius: 1.2rem;
  box-shadow: 0 4px 24px #e0c68a33;
  padding: 2.5rem 2rem 2rem 2rem;
  font-family: 'Noto Sans TC', 'Segoe UI', Arial, sans-serif;
  color: #a67c00;
}
.register-view h1 {
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
input {
  border-radius: 0.7rem;
  border: 1px solid #e0c68a;
  padding: 0.7rem 1rem;
  font-size: 1rem;
  background: #fffdfa;
  color: #a67c00;
  box-shadow: 0 1px 4px #e0c68a22;
  transition: border 0.2s;
}
input:focus {
  border: 1.5px solid #b8860b;
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
.error {
  color: #b85c38;
  background: #fff3e0;
  border-radius: 0.5rem;
  padding: 0.5rem 1rem;
  margin-bottom: 1rem;
  text-align: center;
}
</style> 