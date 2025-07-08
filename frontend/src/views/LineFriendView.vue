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
      <a href="https://line.me/R/ti/p/@yourlineid" target="_blank" class="add-btn">加入 LINE 好友</a>
    </div>
  </div>
</template>

<script setup lang="ts">
// 無需邏輯
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
}
.add-btn:hover {
  background: #b8860b;
  color: #fffbe8;
  transform: scale(1.04);
  box-shadow: 0 4px 16px #e0c68a44;
}
</style> 