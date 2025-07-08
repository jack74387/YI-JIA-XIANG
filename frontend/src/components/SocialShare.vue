<template>
  <div class="social-share">
    <button class="share-btn fb" @click="shareFB"><i class="icon">f</i> Facebook</button>
    <button class="share-btn line" @click="shareLine"><i class="icon">L</i> LINE</button>
    <button class="share-btn link" @click="copyLink"><i class="icon">🔗</i> 複製連結</button>
    <span v-if="copied" class="copied-msg">已複製！</span>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
const copied = ref(false)
const url = window.location.href
function shareFB() {
  window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`,'_blank')
}
function shareLine() {
  window.open(`https://social-plugins.line.me/lineit/share?url=${encodeURIComponent(url)}`,'_blank')
}
function copyLink() {
  navigator.clipboard.writeText(url)
  copied.value = true
  setTimeout(() => (copied.value = false), 1500)
}
</script>

<style scoped>
.social-share {
  display: flex;
  gap: 1rem;
  align-items: center;
  margin-top: 1.2rem;
}
.share-btn {
  background: #fffbe8;
  color: #a67c00;
  border: none;
  border-radius: 2em;
  padding: 0.5em 1.2em;
  font-size: 1rem;
  font-weight: 600;
  box-shadow: 0 2px 8px #e0c68a22;
  cursor: pointer;
  transition: background 0.2s, color 0.2s, transform 0.2s;
  display: flex;
  align-items: center;
  gap: 0.4em;
}
.share-btn .icon {
  font-style: normal;
  font-weight: bold;
  font-size: 1.1em;
}
.share-btn.fb:hover {
  background: #4267B2;
  color: #fff;
  transform: scale(1.07);
}
.share-btn.line:hover {
  background: #06c755;
  color: #fff;
  transform: scale(1.07);
}
.share-btn.link:hover {
  background: #ffe9b2;
  color: #b8860b;
  transform: scale(1.07);
}
.copied-msg {
  color: #b8860b;
  font-size: 0.98em;
  margin-left: 0.5em;
  animation: fadeIn 0.4s;
}
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}
</style> 