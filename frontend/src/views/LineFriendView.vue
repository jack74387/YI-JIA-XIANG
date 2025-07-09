<template>
  <div class="line-friend">
    <h1>加入 LINE 好友，享專屬優惠！</h1>
    <div class="benefits">
      <div class="benefit">🎁 首次加入送 95 折券</div>
      <div class="benefit">🛒 LINE 好友限定商品</div>
      <div class="benefit">📢 最新活動搶先知</div>
      <div class="benefit">🤖 智能客服隨時問</div>
    </div>
    <div class="qrcode-block">
      <img src="https://via.placeholder.com/300x300?text=LINE+QR" alt="LINE QR" />
      <div class="desc">掃描 QR Code 或點擊下方按鈕加入好友</div>
      <a href="https://line.me/R/ti/p/@yourlineid" target="_blank" class="add-btn flex items-center justify-center text-lg">
        <svg class="w-7 h-7 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184C17.437 1.13 14.09.015 10.5.015 4.703.015 0 3.94 0 8.8c0 2.23 1.09 4.25 2.91 5.77-.13.44-.82 2.77-.85 2.96 0 .08.02.16.07.22.06.07.15.11.24.11.29 0 2.09-.41 3.36-.74 1.44.4 2.98.62 4.77.62 5.797 0 10.5-3.925 10.5-8.785 0-1.87-.89-3.63-2.385-5.175zM10.5 15.2c-1.7 0-3.25-.22-4.62-.62l-.32-.09-2.17.48.46-1.5-.25-.2C2.01 11.8 1.1 10.36 1.1 8.8c0-3.7 4.13-6.715 9.4-6.715 3.23 0 6.26 1.01 8.13 2.77 1.23 1.19 1.91 2.56 1.91 4.025 0 3.7-4.13 6.72-9.4 6.72z"/></svg>
        加入 LINE 好友
      </a>
      <button class="btn-sub mt-3 flex items-center mx-auto" @click="copyLineId">
        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
        複製官方 LINE ID
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
// 無需邏輯
import { ref } from 'vue'
function copyLineId() {
  navigator.clipboard.writeText('@yourlineid')
  alert('已複製 LINE ID!')
}
</script>

<style scoped>
.line-friend {
  max-width: 420px;
  margin: 2rem auto;
  background: #fffbe8;
  border-radius: 1.2rem;
  box-shadow: 0 4px 24px #e0c68a33;
  padding: 2.5rem 2rem 2rem 2rem;
  font-family: 'Noto Sans TC', 'Segoe UI', Arial, sans-serif;
  color: #a67c00;
  text-align: center;
}
.line-friend h1 {
  font-size: 1.3rem;
  margin-bottom: 1.5rem;
  color: #b8860b;
}
.benefits {
  display: flex;
  flex-direction: column;
  gap: 0.7rem;
  margin-bottom: 1.5rem;
}
.benefit {
  background: #fffdfa;
  border-radius: 0.7rem;
  box-shadow: 0 1px 4px #e0c68a22;
  padding: 0.7rem 1rem;
  color: #a67c00;
  font-size: 1.05rem;
  font-weight: 600;
}
.qrcode-block {
  margin-top: 1.2rem;
}
.qrcode {
  width: 140px;
  height: 140px;
  border-radius: 1rem;
  box-shadow: 0 2px 8px #e0c68a22;
  margin-bottom: 0.7rem;
}
.desc {
  color: #a67c00;
  font-size: 0.98rem;
  margin-bottom: 0.7rem;
}
.add-btn {
  display: inline-block;
  background: #06c755;
  color: #fff;
  border-radius: 2em;
  padding: 0.7em 2em;
  font-size: 1.1rem;
  font-weight: 700;
  box-shadow: 0 2px 8px #e0c68a22;
  text-decoration: none;
  transition: background 0.2s, transform 0.2s, box-shadow 0.2s;
  min-width: 220px;
  font-size: 1.2rem;
  padding: 0.9em 2.2em;
}
.add-btn:hover {
  background: #b8860b;
  color: #fffbe8;
  transform: scale(1.04);
  box-shadow: 0 4px 16px #e0c68a44;
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
</style> 