<template>
  <div class="home-hero">
    <!-- Hero Section -->
    <div class="hero-bg">
      <div class="hero-content">
        <div class="brand-header">
          <div class="logo-container">
            <img class="logo" src="/images/logo.jpg" alt="品牌LOGO" />
            <div class="logo-glow"></div>
          </div>
          <div class="brand-title">
            <span class="brand-ch">一佳香</span>
            <span class="brand-en">YI JIA XIANG</span>
          </div>
        </div>
        <p class="subtitle">來自台東的陽光風味，封存半甲子的思念。</p>
        <div class="hero-actions">
          <router-link to="/products" class="cta-button primary">
            <span>探索商品</span>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
              <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </router-link>
          <router-link to="/about" class="cta-button secondary">
            <span>品牌故事</span>
          </router-link>
        </div>
      </div>
      <div class="hero-decoration">
        <div class="floating-shape shape-1"></div>
        <div class="floating-shape shape-2"></div>
        <div class="floating-shape shape-3"></div>
      </div>
    </div>

    <!-- Brand Story Section -->
    <div class="brand-story-section">
      <div class="container">
        <div class="section-header">
          <h2 class="section-title">品牌故事</h2>
          <div class="section-subtitle">傳承三代的台東風味</div>
        </div>
        <div class="story-content">
          <div class="story-text">
            <p>一佳香，來自台東的陽光風味，封存半甲子的思念。堅持選用台灣優質豬肉，結合古法與創新，帶給您最純粹的美味與溫度。</p>
            <div class="story-features">
              <div class="feature-item">
                <div class="feature-icon">🌟</div>
                <div class="feature-text">精選食材</div>
              </div>
              <div class="feature-item">
                <div class="feature-icon">🏺</div>
                <div class="feature-text">古法製作</div>
              </div>
              <div class="feature-item">
                <div class="feature-icon">❤️</div>
                <div class="feature-text">用心品質</div>
              </div>
            </div>
          </div>
          <div class="story-visual">
            <div class="visual-card">
              <img src="/images/main-visual.jpg" alt="主視覺" />
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Featured Products Section -->
    <div class="featured-products-section">
      <div class="container">
        <div class="section-header">
          <h2 class="section-title">精選商品</h2>
          <div class="section-subtitle">嚴選好味道</div>
        </div>
        <div class="product-grid">
          <!-- 商品1: 海苔芝麻豬肉鬆 -->
          <router-link :to="'/products/1'" class="product-card-link">
            <div class="product-card product-1">
              <div class="product-image">
                <img src="https://via.placeholder.com/250x250?text=海苔芝麻豬肉鬆" alt="海苔芝麻豬肉鬆" />
                <div class="product-badge">熱銷</div>
                <div class="product-overlay">
                  <div class="quick-view-btn">快速查看</div>
                </div>
              </div>
              <div class="product-info">
                <div class="product-name">海苔芝麻豬肉鬆</div>
                <div class="product-desc">香脆海苔與芝麻的完美結合</div>
                <div class="product-price">
                  <span class="current-price">NT$ 299</span>
                  <span class="original-price">NT$ 350</span>
                </div>
                <div class="product-rating">
                  <div class="stars">★★★★★</div>
                  <span class="rating-text">(4.8)</span>
                </div>
              </div>
            </div>
          </router-link>

          <!-- 商品2: 五香肉條 -->
          <router-link :to="'/products/2'" class="product-card-link">
            <div class="product-card product-2">
              <div class="product-image">
                <img src="https://via.placeholder.com/250x250?text=五香肉條" alt="五香肉條" />
                <div class="product-badge">熱銷</div>
                <div class="product-overlay">
                  <div class="quick-view-btn">快速查看</div>
                </div>
              </div>
              <div class="product-info">
                <div class="product-name">五香肉條</div>
                <div class="product-desc">傳統工法製作，香氣十足</div>
                <div class="product-price">
                  <span class="current-price">NT$ 320</span>
                  <span class="original-price">NT$ 380</span>
                </div>
                <div class="product-rating">
                  <div class="stars">★★★★★</div>
                  <span class="rating-text">(4.9)</span>
                </div>
              </div>
            </div>
          </router-link>

          <!-- 商品3: 五香肉絲 -->
          <router-link :to="'/products/3'" class="product-card-link">
            <div class="product-card product-3">
              <div class="product-image">
                <img src="https://via.placeholder.com/250x250?text=五香肉絲" alt="五香肉絲" />
                <div class="product-badge">熱銷</div>
                <div class="product-overlay">
                  <div class="quick-view-btn">快速查看</div>
                </div>
              </div>
              <div class="product-info">
                <div class="product-name">五香肉絲</div>
                <div class="product-desc">嚴選豬肉，精心調製五香配方</div>
                <div class="product-price">
                  <span class="current-price">NT$ 280</span>
                  <span class="original-price">NT$ 330</span>
                </div>
                <div class="product-rating">
                  <div class="stars">★★★★★</div>
                  <span class="rating-text">(4.7)</span>
                </div>
              </div>
            </div>
          </router-link>

          <!-- 商品4: 蜜汁豬肉乾 -->
          <router-link :to="'/products/4'" class="product-card-link">
            <div class="product-card product-4">
              <div class="product-image">
                <img src="https://via.placeholder.com/250x250?text=蜜汁豬肉乾" alt="蜜汁豬肉乾" />
                <div class="product-badge">熱銷</div>
                <div class="product-overlay">
                  <div class="quick-view-btn">快速查看</div>
                </div>
              </div>
              <div class="product-info">
                <div class="product-name">蜜汁豬肉乾</div>
                <div class="product-desc">甜中帶鹹，口感紮實有嚼勁</div>
                <div class="product-price">
                  <span class="current-price">NT$ 350</span>
                  <span class="original-price">NT$ 420</span>
                </div>
                <div class="product-rating">
                  <div class="stars">★★★★★</div>
                  <span class="rating-text">(4.9)</span>
                </div>
              </div>
            </div>
          </router-link>
        </div>
        <div class="section-footer">
          <router-link to="/products" class="view-all-btn">
            查看所有商品
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
              <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </router-link>
        </div>
      </div>
    </div>

    <ArticleBlock />
  </div>
</template>

<script setup lang="ts">
import ArticleBlock from '../components/ArticleBlock.vue'
</script>

<style scoped>
/* 全局容器樣式 */
.home-hero {
  background: linear-gradient(135deg, #faf7f0 0%, #f4ede4 100%);
  min-height: 100vh;
  overflow-x: hidden;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 24px;
}

/* Hero Section */
.hero-bg {
  position: relative;
  background: linear-gradient(135deg, #be633f 0%, #b46f53 50%, #d48f6d 100%);
  min-height: 80vh;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 0 0 60px 60px;
  overflow: hidden;
}

.hero-content {
  text-align: center;
  z-index: 2;
  position: relative;
  max-width: 800px;
  padding: 40px 20px;
}

.brand-header {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 24px;
  margin-bottom: 32px;
}

.logo-container {
  position: relative;
  display: inline-block;
}

.logo {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  object-fit: cover;
  border: 4px solid rgba(255, 255, 255, 0.3);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2), 0 0 0 8px rgba(255, 255, 255, 0.1);
  transition: transform 0.3s ease;
  z-index: 1;
  position: relative;
}

.logo:hover {
  transform: scale(1.05);
}

.logo-glow {
  position: absolute;
  top: -10px;
  left: -10px;
  right: -10px;
  bottom: -10px;
  background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 0%, transparent 70%);
  border-radius: 50%;
  animation: pulse 3s ease-in-out infinite;
}

@keyframes pulse {
  0%, 100% { opacity: 0.5; transform: scale(1); }
  50% { opacity: 0.8; transform: scale(1.1); }
}

.brand-title {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.brand-ch {
  font-size: 3.5rem;
  font-weight: 900;
  color: white;
  font-family: 'Noto Sans TC', 'Microsoft JhengHei', '黑體-繁', sans-serif;
  letter-spacing: -0.02em;
  text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
  margin-bottom: 8px;
}

.brand-en {
  font-size: 1.2rem;
  font-weight: 300;
  color: rgba(255, 255, 255, 0.9);
  letter-spacing: 0.4em;
  text-transform: uppercase;
}

.subtitle {
  color: rgba(255, 255, 255, 0.95);
  font-size: 1.3rem;
  font-weight: 400;
  margin-bottom: 40px;
  line-height: 1.6;
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.hero-actions {
  display: flex;
  gap: 16px;
  justify-content: center;
  flex-wrap: wrap;
}

.cta-button {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 16px 32px;
  border-radius: 50px;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.3s ease;
  font-size: 1.1rem;
  border: none;
  cursor: pointer;
}

.cta-button.primary {
  background: white;
  color: #b85c38;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
}

.cta-button.primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 12px 32px rgba(0, 0, 0, 0.25);
  background: #f8f8f8;
}

.cta-button.secondary {
  background: transparent;
  color: white;
  border: 2px solid rgba(255, 255, 255, 0.7);
}

.cta-button.secondary:hover {
  background: rgba(255, 255, 255, 0.1);
  border-color: white;
  transform: translateY(-2px);
}

/* Hero裝飾元素 */
.hero-decoration {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  pointer-events: none;
}

.floating-shape {
  position: absolute;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.1);
  animation: float 6s ease-in-out infinite;
}

.shape-1 {
  width: 100px;
  height: 100px;
  top: 20%;
  left: 10%;
  animation-delay: 0s;
}

.shape-2 {
  width: 60px;
  height: 60px;
  top: 60%;
  right: 15%;
  animation-delay: 2s;
}

.shape-3 {
  width: 80px;
  height: 80px;
  bottom: 20%;
  left: 20%;
  animation-delay: 4s;
}

@keyframes float {
  0%, 100% { transform: translateY(0px) rotate(0deg); }
  50% { transform: translateY(-20px) rotate(180deg); }
}

/* Section Header */
.section-header {
  text-align: center;
  margin-bottom: 60px;
}

.section-title {
  font-size: 2.5rem;
  font-weight: 800;
  color: #b85c38;
  margin-bottom: 16px;
  position: relative;
}

.section-title::after {
  content: '';
  position: absolute;
  bottom: -8px;
  left: 50%;
  transform: translateX(-50%);
  width: 60px;
  height: 4px;
  background: linear-gradient(90deg, #b85c38, #d4754f);
  border-radius: 2px;
}

.section-subtitle {
  font-size: 1.2rem;
  color: #a67c52;
  font-weight: 400;
}

/* Brand Story Section */
.brand-story-section {
  padding: 100px 0;
  background: white;
}

.story-content {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 60px;
  align-items: center;
}

.story-text p {
  font-size: 1.3rem;
  line-height: 1.8;
  color: #666;
  margin-bottom: 40px;
}

.story-features {
  display: flex;
  gap: 32px;
}

.feature-item {
  text-align: center;
  flex: 1;
}

.feature-icon {
  font-size: 2.5rem;
  margin-bottom: 12px;
}

.feature-text {
  font-size: 1.1rem;
  font-weight: 600;
  color: #b85c38;
}

.story-visual {
  position: relative;
}

.visual-card {
  border-radius: 24px;
  overflow: hidden;
  box-shadow: 0 24px 48px rgba(0, 0, 0, 0.15);
  transition: transform 0.3s ease;
}

.visual-card:hover {
  transform: translateY(-8px);
}

.visual-card img {
  width: 100%;
  height: auto;
  display: block;
}

/* Featured Products Section */
.featured-products-section {
  padding: 100px 0;
  background: linear-gradient(135deg, #faf7f0 0%, #f4ede4 100%);
}

.product-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
  gap: 24px;
  margin-bottom: 40px;
}

.product-card {
  background: white;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 6px 24px rgba(0, 0, 0, 0.06);
  transition: all 0.3s ease;
  position: relative;
  max-width: 300px;
  margin: 0 auto;
}

.product-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
}

.product-image {
  position: relative;
  overflow: hidden;
  aspect-ratio: 1;
}

.product-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.product-card:hover .product-image img {
  transform: scale(1.05);
}

.product-badge {
  position: absolute;
  top: 12px;
  left: 12px;
  background: #e74c3c;
  color: white;
  padding: 4px 10px;
  border-radius: 16px;
  font-size: 0.8rem;
  font-weight: 600;
}

.product-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.7);
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.product-card:hover .product-overlay {
  opacity: 1;
}

.quick-view-btn {
  background: white;
  color: #b85c38;
  border: none;
  padding: 12px 24px;
  border-radius: 25px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.quick-view-btn:hover {
  background: #b85c38;
  color: white;
}

.product-info {
  padding: 18px;
}

.product-name {
  font-size: 1.1rem;
  font-weight: 700;
  color: #333;
  margin-bottom: 6px;
}

.product-desc {
  color: #666;
  font-size: 0.9rem;
  margin-bottom: 12px;
  line-height: 1.4;
}

.product-price {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 10px;
}

.current-price {
  font-size: 1.2rem;
  font-weight: 700;
  color: #b85c38;
}

.original-price {
  font-size: 1rem;
  color: #999;
  text-decoration: line-through;
}

.product-rating {
  display: flex;
  align-items: center;
  gap: 8px;
}

.stars {
  color: #ffc107;
  font-size: 1rem;
}

.rating-text {
  color: #666;
  font-size: 0.9rem;
}

.section-footer {
  text-align: center;
}

.view-all-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: #b85c38;
  color: white;
  padding: 16px 32px;
  border-radius: 50px;
  text-decoration: none;
  font-weight: 600;
  transition: all 0.3s ease;
}

.view-all-btn:hover {
  background: #9d4a2f;
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(184, 92, 56, 0.3);
}

/* 響應式設計 */
@media (max-width: 1024px) {
  .story-content {
    grid-template-columns: 1fr;
    gap: 40px;
  }
  
  .product-grid {
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
  }
}

@media (max-width: 768px) {
  .hero-bg {
    min-height: 70vh;
    border-radius: 0 0 40px 40px;
  }
  
  .brand-ch {
    font-size: 2.5rem;
  }
  
  .brand-en {
    font-size: 1rem;
  }
  
  .logo {
    width: 90px;
    height: 90px;
  }
  
  .hero-actions {
    flex-direction: column;
    align-items: center;
  }
  
  .cta-button {
    width: 100%;
    max-width: 280px;
    justify-content: center;
  }
  
  .section-title {
    font-size: 2rem;
  }
  
  .story-features {
    flex-direction: column;
    gap: 24px;
  }
  
  .product-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
  }
  
  .product-card {
    max-width: none;
  }
  
  .container {
    padding: 0 16px;
  }
}

@media (max-width: 480px) {
  .hero-content {
    padding: 32px 16px;
  }
  
  .brand-ch {
    font-size: 2rem;
  }
  
  .subtitle {
    font-size: 1.1rem;
  }
  
  .section-header {
    margin-bottom: 40px;
  }
  
  .brand-story-section,
  .featured-products-section {
    padding: 60px 0;
  }
  
  .product-grid {
    grid-template-columns: 1fr;
    gap: 12px;
  }
}
</style> 