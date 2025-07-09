<template>
  <div class="service-nav flex flex-wrap gap-3 justify-center mt-8">
    <RouterLink to="/member" class="nav-btn"><span class="icon">👤</span>會員專區</RouterLink>
    <RouterLink to="/line-friend" class="nav-btn"><span class="icon">🟩</span>LINE好友專區</RouterLink>
    <RouterLink to="/group-order" class="nav-btn"><span class="icon">🤝</span>團購/企業訂購</RouterLink>
    <RouterLink to="/gift-service" class="nav-btn"><span class="icon">🎁</span>禮盒加值服務</RouterLink>
    <RouterLink to="/food-trace" class="nav-btn"><span class="icon">🔍</span>食品履歷查詢</RouterLink>
    <RouterLink to="/store-locator" class="nav-btn"><span class="icon">📍</span>門市據點</RouterLink>
  </div>
</template>
<script setup lang="ts">
// 無需邏輯
</script>
<style scoped>
.service-nav {
  margin-top: 2.5rem;
}
.nav-btn {
  background: #fff;
  border: 1.5px solid #e0c68a;
  color: #a67c00;
  border-radius: 2em;
  padding: 0.6em 1.6em;
  font-size: 1.05rem;
  font-weight: 700;
  box-shadow: 0 2px 8px #e0c68a22;
  transition: background 0.2s, color 0.2s, transform 0.2s;
  display: flex;
  align-items: center;
  gap: 0.5em;
  text-decoration: none;
}
.nav-btn:hover, .nav-btn.router-link-active {
  background: #ffe9b2;
  color: #b8860b;
  transform: scale(1.04);
}
.icon {
  font-size: 1.2em;
}
</style> 