<template>
  <div class="home-hero">
    <div class="hero-bg">
      <img class="logo" src="https://via.placeholder.com/120x120?text=LOGO" alt="品牌LOGO" />
      <h1 class="main-title">一佳香 YI JIA XIANG</h1>
      <p class="subtitle">來自台東的陽光風味，封存半甲子的思念。</p>
      <div class="cta-row">
        <router-link to="/register" class="cta-btn">立即註冊</router-link>
        <router-link to="/login" class="cta-btn secondary">會員登入</router-link>
      </div>
    </div>
    <div class="brand-story">
      <h2>品牌故事</h2>
      <p>一佳香，來自台東的陽光風味，封存半甲子的思念。，堅持選用台灣優質豬肉，結合古法與創新，帶給您最純粹的美味與溫度。</p>
    </div>
    <div class="main-visual">
      <img src="https://via.placeholder.com/900x320?text=主視覺大圖" alt="主視覺" />
    </div>
    <div class="featured-products">
      <h2>精選商品</h2>
      <div class="product-list">
        <div v-for="i in 4" :key="i" class="product-card">
          <img src="https://via.placeholder.com/220x220?text=商品佔位圖" alt="商品" />
          <div class="name">商品名稱</div>
          <div class="desc">商品描述佔位</div>
          <div class="price">NT$ 999</div>
        </div>
      </div>
    </div>
  </div>
</template>
<style scoped>
.home-hero {
  background: #f9f6f1;
  min-height: 100vh;
  padding-bottom: 48px;
}
.hero-bg {
  background: linear-gradient(120deg, #f9f6f1 60%, #ede3d0 100%);
  border-radius: 0 0 48px 48px;
  box-shadow: 0 4px 32px #e2d6c2;
  padding: 48px 0 32px 0;
  text-align: center;
}
.logo {
  border-radius: 50%;
  box-shadow: 0 2px 8px #e2d6c2;
  margin-bottom: 18px;
}
.main-title {
  font-size: 2.2em;
  color: #b85c38;
  font-weight: 800;
  margin-bottom: 8px;
  letter-spacing: 2px;
}
.subtitle {
  color: #a67c52;
  font-size: 1.15em;
  margin-bottom: 18px;
}
.cta-row {
  display: flex;
  justify-content: center;
  gap: 18px;
  margin-bottom: 12px;
}
.cta-btn {
  background: #b85c38;
  color: #fff;
  border-radius: 2em;
  padding: 0.7em 2.2em;
  font-size: 1.1em;
  font-weight: 700;
  box-shadow: 0 2px 8px #e2d6c2;
  text-decoration: none;
  transition: background .2s, transform .2s;
  display: inline-block;
}
.cta-btn.secondary {
  background: #ede3d0;
  color: #b85c38;
  border: 2px solid #b85c38;
}
.cta-btn:hover {
  background: #a67c52;
  color: #fffbe8;
  transform: scale(1.07);
}
.brand-story {
  max-width: 700px;
  margin: 36px auto 0 auto;
  background: #fffbe8;
  border-radius: 18px;
  box-shadow: 0 2px 8px #e2d6c2;
  padding: 32px 28px;
  text-align: center;
}
.brand-story h2 {
  color: #a67c52;
  font-size: 1.3em;
  margin-bottom: 10px;
}
.brand-story p {
  color: #7c6a58;
  font-size: 1.08em;
}
.main-visual {
  margin: 38px auto 0 auto;
  max-width: 900px;
  text-align: center;
}
.main-visual img {
  width: 100%;
  border-radius: 18px;
  box-shadow: 0 2px 16px #e2d6c2;
}
.featured-products {
  max-width: 1100px;
  margin: 48px auto 0 auto;
  background: #fffbe8;
  border-radius: 18px;
  box-shadow: 0 2px 8px #e2d6c2;
  padding: 32px 28px;
}
.featured-products h2 {
  color: #a67c52;
  font-size: 1.2em;
  margin-bottom: 18px;
}
.product-list {
  display: flex;
  gap: 32px;
  justify-content: center;
  flex-wrap: wrap;
}
.product-card {
  background: #f9f6f1;
  border-radius: 14px;
  box-shadow: 0 2px 8px #e2d6c2;
  padding: 18px 12px;
  text-align: center;
  min-width: 200px;
  max-width: 220px;
  transition: box-shadow .2s, transform .2s;
}
.product-card:hover {
  box-shadow: 0 6px 24px #d6c3a1;
  transform: translateY(-4px) scale(1.03);
}
.product-card img {
  width: 100%;
  border-radius: 10px;
  margin-bottom: 10px;
}
.name {
  color: #b85c38;
  font-weight: 700;
  font-size: 1.08em;
  margin-bottom: 4px;
}
.desc {
  color: #7c6a58;
  font-size: 0.98em;
  margin-bottom: 6px;
}
.price {
  color: #a67c52;
  font-size: 1.08em;
  font-weight: 700;
}
@media (max-width: 900px) {
  .product-list { flex-direction: column; gap: 18px; }
  .main-visual img { max-width: 100%; }
}
</style> 