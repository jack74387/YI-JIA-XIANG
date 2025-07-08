<template>
  <div class="floating-buttons">
    <button class="line"><i class="icon-line" /></button>
    <button class="fb"><i class="icon-fb" /></button>
    <button class="link"><i class="icon-link" /></button>
  </div>
</template>
<script setup lang="ts">
</script>
<style scoped>
.floating-buttons {
  position: fixed;
  right: 24px;
  bottom: 32px;
  display: flex;
  flex-direction: column;
  gap: 16px;
  z-index: 100;
}
.floating-buttons button {
  width: 54px;
  height: 54px;
  border-radius: 50%;
  border: none;
  background: #fffbe9;
  box-shadow: 0 2px 8px #e2d6c2;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.6em;
  cursor: pointer;
  transition: background .2s, transform .2s;
}
.floating-buttons button:hover {
  background: #c49a6c;
  color: #fff;
  transform: scale(1.12);
}
.line { color: #06c755; }
.fb { color: #1877f3; }
.link { color: #b85c38; }
</style> 