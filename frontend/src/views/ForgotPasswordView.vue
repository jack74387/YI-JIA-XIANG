<template>
  <div class="forgot-view">
    <h1>忘記密碼</h1>
    <form @submit.prevent="submit">
      <div class="form-row">
        <label>Email</label>
        <input v-model="email" type="email" required />
      </div>
      <button class="submit-btn">送出重設連結</button>
    </form>
    <div v-if="submitted" class="success-msg">已寄送重設密碼連結，請至信箱查收！</div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
const email = ref('')
const submitted = ref(false)
function submit() {
  submitted.value = true
  setTimeout(() => (submitted.value = false), 2000)
}
</script>

<style scoped>
.forgot-view {
  max-width: 420px;
  margin: 2rem auto;
  background: #fffbe8;
  border-radius: 1.2rem;
  box-shadow: 0 4px 24px #e0c68a33;
  padding: 2.5rem 2rem 2rem 2rem;
  font-family: 'Noto Sans TC', 'Segoe UI', Arial, sans-serif;
  color: #a67c00;
}
.forgot-view h1 {
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
</style> 