<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { getImageUrl } from '@/utils/imageUtils'
import ArticleBlock from '../components/ArticleBlock.vue'
import axios from 'axios'

// 商品類型定義
interface Product {
  id: number
  name: string
  description: string
  image: string
  price_large: number
  price_small?: number
}

// 精選商品數據
const featuredProducts = ref<Product[]>([])
const loading = ref(true)

// 獲取精選商品
const fetchFeaturedProducts = async () => {
  try {
    const response = await axios.get('/api/v1/products/featured')
    if (response.data.success) {
      featuredProducts.value = response.data.data
    }
  } catch (error) {
    console.error('獲取精選商品失敗:', error)
    // 如果獲取失敗，使用默認商品
    featuredProducts.value = [
      {
        id: 2,
        name: '海苔芝麻豬肉鬆',
        description: '香脆海苔與芝麻的完美結合',
        image: 'https://res.cloudinary.com/daeb3goxf/image/upload/v1754376263/yijiaxiang/products/zibx7lhjapg6g7bqgbni.png',
        price_large: 340,
        price_small: 370
      },
      {
        id: 4,
        name: '五香豬肉條',
        description: '傳統工法製作，香氣十足',
        image: 'https://res.cloudinary.com/daeb3goxf/image/upload/v1754376263/yijiaxiang/products/gfwp01goehxbycqqql7k.png',
        price_large: 440,
        price_small: 470
      },
      {
        id: 5,
        name: '五香豬肉絲',
        description: '嚴選豬肉，精心調製五香配方',
        image: 'https://res.cloudinary.com/daeb3goxf/image/upload/v1754376263/yijiaxiang/products/zibx7lhjapg6g7bqgbni.png',
        price_large: 440,
        price_small: 470
      },
      {
        id: 6,
        name: '蜜汁原味豬肉乾',
        description: '甜中帶鹹，口感紮實有嚼勁',
        image: 'https://res.cloudinary.com/daeb3goxf/image/upload/v1754376263/yijiaxiang/products/zibx7lhjapg6g7bqgbni.png',
        price_large: 420,
        price_small: 450
      }
    ]
  } finally {
    loading.value = false
  }
}

// 格式化價格
const formatPrice = (price: number | null | undefined): string => {
  if (!price) return 'N/A'
  return new Intl.NumberFormat('zh-TW').format(price)
}

onMounted(() => {
  fetchFeaturedProducts()
})
</script>

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
        <div class="floating-shape shape-4"></div>
        <div class="floating-shape shape-5"></div>
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
          <!-- 載入狀態 -->
          <div v-if="loading" class="col-span-full flex justify-center items-center py-20">
            <div class="text-center">
              <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-amber-600 mx-auto mb-4"></div>
              <p class="text-gray-600">載入精選商品中...</p>
            </div>
          </div>
          
          <!-- 動態精選商品 -->
          <router-link 
            v-for="(product, index) in featuredProducts" 
            :key="product.id"
            :to="`/products/${product.id}`" 
            class="product-card-link"
          >
            <div class="product-card" :class="`product-${index + 1}`">
              <div class="product-image">
                <img 
                  :src="getImageUrl(product.image) || '/images/product-placeholder.jpg'" 
                  :alt="product.name" 
                  class="w-full h-48 object-cover rounded-t-lg mx-auto" 
                />
                <div class="product-badge">精選</div>
                <div class="product-overlay">
                  <div class="quick-view-btn">查看詳情</div>
                </div>
              </div>
              <div class="product-info">
                <div class="product-name">{{ product.name }}</div>
                <div class="product-desc">{{ product.description || '精選商品' }}</div>
                <div class="product-price">
                  <span class="current-price">NT$ {{ formatPrice(product.price_large) }}</span>
                </div>
              </div>
            </div>
          </router-link>
          
          <!-- 如果沒有精選商品，顯示提示 -->
          <div v-if="!loading && featuredProducts.length === 0" class="col-span-full text-center py-20">
            <div class="text-center">
              <div class="text-6xl mb-4">🛍️</div>
              <p class="text-gray-500 text-lg mb-2">暫無精選商品</p>
              <p class="text-gray-400 text-sm">管理員尚未設置精選商品</p>
            </div>
          </div>
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
  background: 
    linear-gradient(135deg, 
      #d1774e 0%, 
      #c67e5a 25%,
      #bc8560 50%,
      #b68b65 75%,
      #a69068 100%
    );
  min-height: 70vh;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  position: relative;
  clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);
  margin-bottom: 40px;
}

.hero-bg::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: 
    radial-gradient(circle at 20% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
    radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.08) 0%, transparent 50%),
    radial-gradient(circle at 40% 40%, rgba(0, 0, 0, 0.05) 0%, transparent 30%);
  z-index: 1;
}

.hero-bg::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  height: 150px;
  background: linear-gradient(to top, rgba(250, 247, 240, 0.3), transparent);
  z-index: 1;
}

.hero-content {
  text-align: center;
  z-index: 10;
  position: relative;
  max-width: 900px;
  padding: 60px 20px;
  background: rgba(255, 255, 255, 0.05);
  backdrop-filter: blur(20px);
  border-radius: 20px;
  border: 1px solid rgba(255, 255, 255, 0.1);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

.brand-header {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 30px;
  margin-bottom: 40px;
  position: relative;
}

.logo-container {
  position: relative;
  display: inline-block;
  margin-bottom: 10px;
}

.logo {
  width: 140px;
  height: 140px;
  border-radius: 50%;
  object-fit: cover;
  border: 4px solid rgba(255, 255, 255, 0.6);
  box-shadow: 
    0 0 0 8px rgba(255, 255, 255, 0.2),
    0 20px 40px rgba(0, 0, 0, 0.15),
    0 0 60px rgba(255, 255, 255, 0.1);
  transition: all 0.4s ease;
  z-index: 1;
  position: relative;
  filter: brightness(1.1) contrast(1.1);
}

.logo:hover {
  transform: scale(1.08) rotateY(15deg);
  box-shadow: 
    0 0 0 12px rgba(255, 255, 255, 0.3),
    0 25px 50px rgba(0, 0, 0, 0.2),
    0 0 80px rgba(255, 255, 255, 0.2);
  filter: brightness(1.2) contrast(1.2);
}

.logo-glow {
  position: absolute;
  top: -20px;
  left: -20px;
  right: -20px;
  bottom: -20px;
  background: radial-gradient(
    circle, 
    rgba(255, 255, 255, 0.4) 0%, 
    rgba(255, 255, 255, 0.1) 40%,
    transparent 70%
  );
  border-radius: 50%;
  animation: logoGlow 4s ease-in-out infinite;
  z-index: -1;
}

@keyframes logoGlow {
  0%, 100% { 
    opacity: 0.3; 
    transform: scale(0.8) rotate(0deg); 
  }
  50% { 
    opacity: 0.7; 
    transform: scale(1.2) rotate(180deg); 
  }
}

.brand-title {
  display: flex;
  flex-direction: column;
  align-items: center;
  position: relative;
}

.brand-title::before {
  content: '';
  position: absolute;
  top: -20px;
  left: 50%;
  transform: translateX(-50%);
  width: 100px;
  height: 2px;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.6), transparent);
}

.brand-title::after {
  content: '';
  position: absolute;
  bottom: -20px;
  left: 50%;
  transform: translateX(-50%);
  width: 150px;
  height: 2px;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
}

.brand-ch {
  font-size: 4rem;
  font-weight: 900;
  color: white;
  font-family: 'Noto Sans TC', 'Microsoft JhengHei', '黑體-繁', sans-serif;
  letter-spacing: -0.03em;
  text-shadow: 
    0 2px 4px rgba(0, 0, 0, 0.3),
    0 4px 8px rgba(0, 0, 0, 0.2),
    0 0 20px rgba(255, 255, 255, 0.1);
  margin-bottom: 12px;
  background: linear-gradient(135deg, #ffffff 0%, #f0f0f0 100%);
  background-clip: text;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  position: relative;
}

.brand-ch::before {
  content: '一佳香';
  position: absolute;
  top: 0;
  left: 0;
  color: white;
  z-index: -1;
  filter: blur(2px);
}

.brand-en {
  font-size: 1.4rem;
  font-weight: 200;
  color: rgba(255, 255, 255, 0.95);
  letter-spacing: 0.8em;
  text-transform: uppercase;
  font-family: 'Arial', sans-serif;
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.subtitle {
  color: rgba(255, 255, 255, 0.95);
  font-size: 1.3rem;
  font-weight: 300;
  margin-bottom: 40px;
  line-height: 1.6;
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
  max-width: 600px;
  margin-left: auto;
  margin-right: auto;
  position: relative;
  padding: 0 20px;
}

.subtitle::before {
  content: '"';
  position: absolute;
  left: 0;
  top: -10px;
  font-size: 3rem;
  color: rgba(255, 255, 255, 0.3);
  font-family: serif;
}

.subtitle::after {
  content: '"';
  position: absolute;
  right: 0;
  bottom: -20px;
  font-size: 3rem;
  color: rgba(255, 255, 255, 0.3);
  font-family: serif;
}

.hero-actions {
  display: flex;
  gap: 20px;
  justify-content: center;
  flex-wrap: wrap;
  margin-top: 20px;
}

.cta-button {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 16px 32px;
  border-radius: 30px;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.4s ease;
  font-size: 1.1rem;
  border: none;
  cursor: pointer;
  position: relative;
  overflow: hidden;
  min-width: 160px;
  justify-content: center;
}

.cta-button::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
  transition: left 0.5s ease;
}

.cta-button:hover::before {
  left: 100%;
}

.cta-button.primary {
  background: linear-gradient(135deg, #ffffff 0%, #f8f8f8 100%);
  color: #b85c38;
  box-shadow: 
    0 8px 24px rgba(0, 0, 0, 0.15),
    0 4px 12px rgba(255, 255, 255, 0.2);
  border: 2px solid rgba(255, 255, 255, 0.3);
}

.cta-button.primary:hover {
  transform: translateY(-3px) scale(1.05);
  box-shadow: 
    0 12px 32px rgba(0, 0, 0, 0.25),
    0 8px 20px rgba(255, 255, 255, 0.1);
  background: linear-gradient(135deg, #fff 0%, #f0f0f0 100%);
}

.cta-button.secondary {
  background: transparent;
  color: white;
  border: 2px solid rgba(255, 255, 255, 0.8);
  backdrop-filter: blur(10px);
}

.cta-button.secondary:hover {
  background: rgba(255, 255, 255, 0.15);
  border-color: white;
  transform: translateY(-3px) scale(1.05);
  box-shadow: 0 8px 24px rgba(255, 255, 255, 0.2);
}

/* Hero裝飾元素 */
.hero-decoration {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  pointer-events: none;
  z-index: 2;
}

.floating-shape {
  position: absolute;
  border-radius: 50%;
  background: radial-gradient(circle at 30% 30%, 
    rgba(255, 255, 255, 0.2) 0%, 
    rgba(255, 255, 255, 0.1) 40%,
    rgba(255, 255, 255, 0.05) 70%,
    transparent 100%
  );
  border: 1px solid rgba(255, 255, 255, 0.15);
  animation: floatElegant 12s ease-in-out infinite;
  box-shadow: 
    0 8px 24px rgba(255, 255, 255, 0.1),
    inset 0 1px 3px rgba(255, 255, 255, 0.2);
  backdrop-filter: blur(10px);
}

.shape-1 {
  width: 120px;
  height: 120px;
  top: 10%;
  left: 5%;
  animation-delay: 0s;
  background: radial-gradient(circle at 40% 40%, 
    rgba(255, 255, 255, 0.25) 0%, 
    rgba(255, 255, 255, 0.08) 60%,
    transparent 100%
  );
}

.shape-2 {
  width: 80px;
  height: 80px;
  top: 60%;
  right: 8%;
  animation-delay: 4s;
  background: radial-gradient(circle at 60% 30%, 
    rgba(255, 255, 255, 0.2) 0%, 
    rgba(255, 255, 255, 0.06) 60%,
    transparent 100%
  );
}

.shape-3 {
  width: 100px;
  height: 100px;
  bottom: 10%;
  left: 12%;
  animation-delay: 8s;
  background: radial-gradient(circle at 50% 60%, 
    rgba(255, 255, 255, 0.22) 0%, 
    rgba(255, 255, 255, 0.07) 60%,
    transparent 100%
  );
}

.shape-4 {
  width: 60px;
  height: 60px;
  top: 30%;
  right: 25%;
  animation-delay: 2s;
  background: radial-gradient(circle at 70% 20%, 
    rgba(255, 255, 255, 0.18) 0%, 
    rgba(255, 255, 255, 0.05) 60%,
    transparent 100%
  );
}

.shape-5 {
  width: 90px;
  height: 90px;
  bottom: 40%;
  right: 5%;
  animation-delay: 6s;
  background: radial-gradient(circle at 20% 80%, 
    rgba(255, 255, 255, 0.15) 0%, 
    rgba(255, 255, 255, 0.04) 60%,
    transparent 100%
  );
}

@keyframes floatElegant {
  0%, 100% { 
    transform: translateY(0px) translateX(0px) scale(1) rotate(0deg); 
    opacity: 0.6;
  }
  25% { 
    transform: translateY(-20px) translateX(10px) scale(1.1) rotate(90deg); 
    opacity: 0.8;
  }
  50% { 
    transform: translateY(-30px) translateX(-15px) scale(0.9) rotate(180deg); 
    opacity: 1;
  }
  75% { 
    transform: translateY(-15px) translateX(20px) scale(1.05) rotate(270deg); 
    opacity: 0.7;
  }
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

.col-span-full {
  grid-column: 1 / -1;
}

.product-card {
  background: white;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
  position: relative;
  max-width: 300px;
  margin: 0 auto;
  height: 100%;
  display: flex;
  flex-direction: column;
}

.product-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.product-image {
  position: relative;
  overflow: hidden;
  height: 192px; /* h-48 equivalent */
}

.product-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 8px 8px 0 0;
  transition: transform 0.3s ease, filter 0.3s ease;
}

.product-card:hover .product-image img {
  transform: scale(1.05);
}

.product-image.notification img {
  filter: brightness(75%) grayscale(100%);
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

.product-card-link {
  text-decoration: none;
  color: inherit;
  display: block;
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
  color: #2d3748;
  margin-bottom: 8px;
  line-height: 1.4;
}

.product-desc {
  color: #718096;
  font-size: 0.9rem;
  margin-bottom: 12px;
  line-height: 1.5;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.product-price {
  display: flex;
  align-items: center;
  margin-bottom: 10px;
}

.current-price {
  font-size: 1.25rem;
  font-weight: 700;
  color: #b85c38;
  letter-spacing: -0.02em;
  transition: all 0.2s ease;
}

.product-card:hover .current-price {
  color: #9d4b29;
  transform: scale(1.05);
}

.product-card:hover .product-name {
  color: #b85c38;
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
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
  }
  
  .product-card {
    max-width: 280px;
  }
}

@media (max-width: 768px) {
  .hero-bg {
    min-height: 65vh;
    clip-path: polygon(0 0, 100% 0, 100% 90%, 0 100%);
    margin-bottom: 24px;
  }
  
  .hero-content {
    padding: 40px 16px;
    margin: 0 16px;
  }
  
  .brand-ch {
    font-size: 2.8rem;
  }
  
  .brand-en {
    font-size: 1.1rem;
    letter-spacing: 0.5em;
  }
  
  .logo {
    width: 110px;
    height: 110px;
  }
  
  .subtitle {
    font-size: 1.1rem;
    margin-bottom: 32px;
  }
  
  .hero-actions {
    flex-direction: column;
    align-items: center;
    gap: 16px;
  }
  
  .cta-button {
    width: 100%;
    max-width: 260px;
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
  
  .product-name {
    font-size: 1rem;
  }
  
  .product-desc {
    font-size: 0.85rem;
  }
  
  .container {
    padding: 0 16px;
  }
}

@media (max-width: 480px) {
  .hero-content {
    padding: 24px 16px;
  }
  
  .hero-bg {
    min-height: 50vh;
    border-radius: 0 0 16px 16px;
  }
  
  .brand-ch {
    font-size: 1.8rem;
  }
  
  .subtitle {
    font-size: 1rem;
  }
  
  .logo {
    width: 70px;
    height: 70px;
  }
  
  .section-header {
    margin-bottom: 32px;
  }
  
  .brand-story-section,
  .featured-products-section {
    padding: 50px 0;
  }
  
  .product-grid {
    grid-template-columns: 1fr;
    gap: 12px;
  }
}
</style> 