<template>
  <div class="customer-service-widget">
    <button class="cs-btn line" @click="openLine"><i class="icon">L</i></button>
    <button class="cs-btn fb" @click="openFB"><i class="icon">f</i></button>
  </div>
</template>

<script setup lang="ts">
function openLine() {
  window.open('https://line.me/R/ti/p/@yourlineid', '_blank')
}
function openFB() {
  window.open('https://m.me/yourpageid', '_blank')
}
</script>

<style scoped>
.customer-service-widget {
  position: fixed;
  bottom: 2.2rem;
  right: 2.2rem;
  display: flex;
  flex-direction: column;
  gap: 1rem;
  z-index: 1000;
}
.cs-btn {
  width: 56px;
  height: 56px;
  border-radius: 50%;
  border: none;
  background: #fffbe8;
  color: #a67c00;
  font-size: 2rem;
  box-shadow: 0 2px 8px #e0c68a22;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background 0.2s, color 0.2s, transform 0.2s;
}
.cs-btn.line:hover {
  background: #06c755;
  color: #fff;
  transform: scale(1.12);
}
.cs-btn.fb:hover {
  background: #4267B2;
  color: #fff;
  transform: scale(1.12);
}
.icon {
  font-style: normal;
  font-weight: bold;
}
</style> 