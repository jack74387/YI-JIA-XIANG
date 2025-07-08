<template>
  <div id="app">
    <nav class="main-navbar">
      <div class="navbar-inner">
        <router-link to="/" class="brand"><span class="brand-icon">🐷</span> 一佳香</router-link>
        <input type="checkbox" id="nav-toggle" class="nav-toggle" />
        <label for="nav-toggle" class="nav-toggle-label">
          <span></span>
        </label>
        <div class="nav-links">
          <router-link to="/products" class="nav-link"><span class="icon">🛍️</span> 商品</router-link>
          <router-link to="/about" class="nav-link"><span class="icon">💡</span> 關於我們</router-link>
          <router-link to="/gift" class="nav-link"><span class="icon">🎁</span> 禮盒加值</router-link>
          <router-link to="/food-trace" class="nav-link"><span class="icon">📝</span> 食品履歷</router-link>
          <router-link to="/faq" class="nav-link"><span class="icon">❓</span> 常見問答</router-link>
          <router-link to="/stores" class="nav-link"><span class="icon">🏬</span> 門市據點</router-link>
          <router-link to="/group-order" class="nav-link"><span class="icon">🏢</span> 團購/企業訂購</router-link>
          <router-link to="/member" class="nav-link"><span class="icon">👤</span> 會員專區</router-link>
          <router-link to="/line-friend" class="nav-link"><span class="icon">📱</span> LINE 好友</router-link>
          <router-link to="/cart" class="nav-link"><span class="icon">🛒</span> 購物車</router-link>
          <router-link to="/login" class="nav-link"><span class="icon">🔑</span> 登入</router-link>
        </div>
      </div>
    </nav>

    <main class="min-h-screen">
      <router-view />
    </main>

    <footer class="bg-gray-800 text-white py-8">
      <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
          <div>
            <h3 class="text-lg font-semibold mb-4">一佳香</h3>
            <p class="text-gray-300">
              專業的肉乾製造商，提供高品質的肉乾產品。
            </p>
          </div>
          <div>
            <h4 class="text-md font-semibold mb-4">商品分類</h4>
            <ul class="space-y-2 text-gray-300">
              <li><a href="#" class="hover:text-white">豬肉乾</a></li>
              <li><a href="#" class="hover:text-white">豬肉鬆</a></li>
              <li><a href="#" class="hover:text-white">豬肉絲</a></li>
              <li><a href="#" class="hover:text-white">豬肉條</a></li>
            </ul>
          </div>
          <div>
            <h4 class="text-md font-semibold mb-4">客戶服務</h4>
            <ul class="space-y-2 text-gray-300">
              <li><a href="#" class="hover:text-white">購物須知</a></li>
              <li><a href="#" class="hover:text-white">退換貨政策</a></li>
              <li><a href="#" class="hover:text-white">配送說明</a></li>
              <li><a href="#" class="hover:text-white">常見問題</a></li>
            </ul>
          </div>
          <div>
            <h4 class="text-md font-semibold mb-4">聯絡資訊</h4>
            <ul class="space-y-2 text-gray-300">
              <li>電話：(089) 357-996</li>
              <li>信箱：yijiaxiang88@gmail.com</li>
              <li>地址：台東縣台東市廣東路265號</li>
            </ul>
          </div>
        </div>
        <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-300">
          <p>&copy; 2025 一佳香. All rights reserved.</p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup lang="ts">
// 使用 Composition API
</script>

<style scoped>
.main-navbar {
  background: linear-gradient(90deg, #fffbe8 60%, #f7e7c4 100%);
  box-shadow: 0 4px 24px #e0c68a22;
  padding: 0.2rem 0;
  position: sticky;
  top: 0;
  z-index: 100;
}
.navbar-inner {
  max-width: 1200px;
  margin: 0 auto;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0.2rem 1.5rem;
  position: relative;
}
.brand {
  font-size: 2rem;
  font-weight: 900;
  color: #b8860b;
  letter-spacing: 0.1em;
  text-shadow: 0 2px 8px #e0c68a33;
  padding: 0.2em 0.7em;
  border-radius: 1.5em;
  background: #fffdfa;
  box-shadow: 0 1px 4px #e0c68a22;
  transition: background 0.2s, color 0.2s;
  display: flex;
  align-items: center;
  gap: 0.4em;
}
.brand-icon {
  font-size: 1.3em;
  margin-right: 0.1em;
}
.icon {
  font-size: 1.1em;
  margin-right: 0.3em;
  vertical-align: middle;
}
.nav-links {
  display: flex;
  align-items: center;
  gap: 0.5em;
}
.nav-link {
  color: #a67c00;
  background: #fffdfa;
  border-radius: 2em;
  padding: 0.45em 1.3em;
  font-weight: 600;
  font-size: 1.05rem;
  transition: background 0.2s, color 0.2s, transform 0.2s, box-shadow 0.2s;
  margin-left: 0.1em;
  box-shadow: 0 1px 4px #e0c68a22;
  border: 1.5px solid transparent;
  letter-spacing: 0.02em;
}
.nav-link:hover, .router-link-exact-active.nav-link {
  background: #ffe9b2;
  color: #b8860b;
  transform: scale(1.08);
  box-shadow: 0 4px 16px #e0c68a44;
  border: 1.5px solid #e0c68a;
}
/* 手機響應式 */
.nav-toggle {
  display: none;
}
.nav-toggle-label {
  display: none;
  cursor: pointer;
  width: 2.2em;
  height: 2.2em;
  align-items: center;
  justify-content: center;
}
.nav-toggle-label span,
.nav-toggle-label span::before,
.nav-toggle-label span::after {
  display: block;
  background: #b8860b;
  height: 4px;
  width: 28px;
  border-radius: 2px;
  position: relative;
  transition: all 0.3s;
}
.nav-toggle-label span::before,
.nav-toggle-label span::after {
  content: '';
  position: absolute;
}
.nav-toggle-label span::before {
  top: -9px;
}
.nav-toggle-label span::after {
  top: 9px;
}
@media (max-width: 900px) {
  .nav-links {
    position: absolute;
    top: 100%;
    right: 0;
    background: #fffbe8;
    flex-direction: column;
    align-items: flex-end;
    width: 220px;
    border-radius: 1.2em;
    box-shadow: 0 4px 24px #e0c68a33;
    padding: 1.2em 1em;
    gap: 0.7em;
    opacity: 0;
    pointer-events: none;
    transform: translateY(-10px);
    transition: all 0.25s;
    z-index: 100;
  }
  .nav-toggle:checked ~ .nav-links {
    opacity: 1;
    pointer-events: auto;
    transform: translateY(0);
  }
  .nav-toggle-label {
    display: flex;
  }
}
</style> 