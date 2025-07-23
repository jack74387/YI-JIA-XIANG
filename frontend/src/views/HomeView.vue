<template>
  <div class="home-hero">
    <div class="hero-bg">
      <div class="brand-header">
        <img class="logo" src="/images/logo.jpg" alt="品牌LOGO" />
        <div class="brand-title">
          <span class="brand-ch">一佳香</span>
          <span class="brand-en">YI JIA XIANG</span>
        </div>
      </div>
      <p class="subtitle">來自台東的陽光風味，封存半甲子的思念。</p>
    </div>
    <div class="brand-story">
      <h2>品牌故事</h2>
      <p>一佳香，來自台東的陽光風味，封存半甲子的思念。堅持選用台灣優質豬肉，結合古法與創新，帶給您最純粹的美味與溫度。</p>
    </div>
    <div class="main-visual">
      <img src="/images/main-visual.jpg" alt="主視覺" />
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
  padding: 32px 0 24px 0;
  text-align: center;
  margin-bottom: 0;
}
.brand-header {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 8px;
  margin-bottom: 8px;
}
.logo {
  border-radius: 50%;
  box-shadow: 0 2px 8px #e2d6c2;
  width: 90px;
  height: 90px;
  object-fit: cover;
  margin-bottom: 0;
}
.brand-title {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-top: 2px;
}
.brand-ch {
  font-size: 2.5rem;
  font-weight: 900;
  color: #b85c38;
  font-family: 'Noto Sans TC', 'Microsoft JhengHei', '黑體-繁', sans-serif;
  letter-spacing: -0.04em;
  line-height: 1.05;
}
.brand-en {
  font-size: 1rem;
  font-weight: 400;
  color: #b85c38;
  letter-spacing: 0.35em;
  text-transform: uppercase;
  margin-top: 2px;
}
.subtitle {
  color: #a67c52;
  font-size: 1.08em;
  margin-bottom: 16px;
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
  margin: 48px auto 0 auto;
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
  .logo { width: 60px; height: 60px; }
}
@media (max-width: 600px) {
  .brand-header {
    flex-direction: column;
    gap: 4px;
  }
  .brand-ch {
    font-size: 1.5rem;
  }
  .brand-en {
    font-size: 0.8rem;
  }
  .brand-story {
    padding: 18px 8px;
  }
  .main-visual {
    margin: 24px auto 0 auto;
  }
  .featured-products {
    padding: 18px 8px;
  }
  .product-list {
    gap: 8px;
  }
  .product-card {
    min-width: 140px;
    max-width: 100%;
    padding: 10px 4px;
  }
  .product-card img {
    border-radius: 8px;
  }
}
</style> 