<template>
  <div class="login-view">
    <h1>會員登入</h1>
    <form @submit.prevent="login">
      <label>Email <input v-model="form.email" type="email" required /></label>
      <label>密碼 <input v-model="form.password" type="password" required /></label>
      <div v-if="error" class="error">{{ error }}</div>
      <button class="login-btn" type="submit" :disabled="loading">
        {{ loading ? '登入中...' : '登入' }}
      </button>
    </form>
    <div class="social-login">
      <button class="social-btn">LINE 登入</button>
      <button class="social-btn">Facebook 登入</button>
      <button class="social-btn">Google 登入</button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue'
import axios from 'axios'
const form = reactive({ email: '', password: '' })
const error = ref('')
const loading = ref(false)
async function login() {
  error.value = ''
  loading.value = true
  try {
    const res = await axios.post('http://127.0.0.1:8000/api/v1/auth/login', form)
    if (res.data.success) {
      // 儲存會員資訊，可用 Pinia store
      localStorage.setItem('user', JSON.stringify(res.data.user))
      window.location.href = '/'
    } else {
      error.value = res.data.message || '登入失敗'
    }
  } catch (e) {
    error.value = '登入失敗，請檢查帳號密碼'
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.login-view {
  max-width: 400px;
  margin: 3rem auto;
  padding: 2rem 1rem;
  background: #fff8e1;
  border-radius: 1rem;
  box-shadow: 0 2px 8px #e0c68a44;
}
form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  margin-bottom: 1.5rem;
}
.login-btn {
  background: #b8860b;
  color: #fff;
  border: none;
  border-radius: 0.5rem;
  padding: 0.75rem 2rem;
  font-size: 1.1rem;
  cursor: pointer;
  transition: background 0.2s;
}
.login-btn:hover {
  background: #a0761a;
}
.social-login {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}
.social-btn {
  background: #e0c68a;
  color: #3d2c1e;
  border: none;
  border-radius: 0.5rem;
  padding: 0.5rem 1rem;
  font-size: 1rem;
  cursor: pointer;
  transition: background 0.2s;
}
.social-btn:hover {
  background: #ffe082;
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