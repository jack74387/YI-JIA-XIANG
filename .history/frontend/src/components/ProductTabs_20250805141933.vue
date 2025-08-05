<template>
  <div class="tabs mt-8">
    <button v-for="tab in tabs" :key="tab" @click="active=tab" :class="{active:active===tab}">{{ tab }}</button>
    
    <!-- 商品說明 -->
    <div v-if="active==='商品說明'" class="mt-6 tab-content">
      <div class="description-section">
        <h3 class="text-lg font-bold text-gray-800 mb-4">商品特色</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
          <div class="feature-card">
            <div class="feature-icon">🌾</div>
            <h4 class="font-semibold text-gray-700 mb-2">嚴選食材</h4>
            <p class="text-gray-600 text-sm">精選台灣在地優質豬肉，堅持使用新鮮食材，確保每一口都是最純粹的美味。</p>
          </div>
          <div class="feature-card">
            <div class="feature-icon">👨‍🍳</div>
            <h4 class="font-semibold text-gray-700 mb-2">職人手作</h4>
            <p class="text-gray-600 text-sm">傳承三代製肉技術，每一道工序都經過精心調配，呈現最完美的口感與風味。</p>
          </div>
          <div class="feature-card">
            <div class="feature-icon">🔒</div>
            <h4 class="font-semibold text-gray-700 mb-2">品質保證</h4>
            <p class="text-gray-600 text-sm">通過SGS食品安全認證，無添加防腐劑，讓您吃得安心又健康。</p>
          </div>
          <div class="feature-card">
            <div class="feature-icon">📦</div>
            <h4 class="font-semibold text-gray-700 mb-2">新鮮配送</h4>
            <p class="text-gray-600 text-sm">當日製作，隔日配送，確保您收到最新鮮的商品。</p>
          </div>
        </div>
        
        <h3 class="text-lg font-bold text-gray-800 mb-4">商品描述</h3>
        <div class="description-text">
          <p class="text-gray-700 mb-4">{{ product.description || '經典風味豬肉鬆，採用傳統工藝精心製作，口感香Q有嚼勁，鹹香適中不膩口。嚴選台灣在地優質豬肉，搭配特製調味料，每一口都能感受到濃濃的台灣味。' }}</p>
          <p class="text-gray-700 mb-4">適合搭配白飯、粥品、麵包等各種主食，也可作為零食直接食用。無論是早餐、午餐、晚餐或是下午茶，都是絕佳的選擇。</p>
          <p class="text-gray-700">包裝採用密封設計，開封後請冷藏保存，以保持最佳風味。</p>
        </div>
      </div>
    </div>
    
    <!-- 商品規格 -->
    <div v-if="active==='商品規格'" class="mt-6 tab-content">
      <div class="specs-section">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <!-- 基本資訊 -->
          <div class="specs-card">
            <h3 class="text-lg font-bold text-gray-800 mb-4">基本資訊</h3>
            <div class="specs-list">
              <div class="spec-item">
                <span class="spec-label">商品名稱</span>
                <span class="spec-value">{{ product.name }}</span>
              </div>
              <div class="spec-item">
                <span class="spec-label">商品編號</span>
                <span class="spec-value">{{ product.id || 'N/A' }}</span>
              </div>
              <div class="spec-item">
                <span class="spec-label">商品分類</span>
                <span class="spec-value">{{ product.category_name || '肉鬆系列' }}</span>
              </div>
              <div class="spec-item">
                <span class="spec-label">保存期限</span>
                <span class="spec-value">180天（未開封）</span>
              </div>
              <div class="spec-item">
                <span class="spec-label">保存方式</span>
                <span class="spec-value">常溫保存，開封後請冷藏</span>
              </div>
            </div>
          </div>
          
          <!-- 營養資訊 -->
          <div class="specs-card">
            <h3 class="text-lg font-bold text-gray-800 mb-4">營養資訊</h3>
            <div class="specs-list">
              <div class="spec-item">
                <span class="spec-label">每100公克</span>
                <span class="spec-value"></span>
              </div>
              <div class="spec-item">
                <span class="spec-label">熱量</span>
                <span class="spec-value">320大卡</span>
              </div>
              <div class="spec-item">
                <span class="spec-label">蛋白質</span>
                <span class="spec-value">25公克</span>
              </div>
              <div class="spec-item">
                <span class="spec-label">脂肪</span>
                <span class="spec-value">18公克</span>
              </div>
              <div class="spec-item">
                <span class="spec-label">碳水化合物</span>
                <span class="spec-value">12公克</span>
              </div>
              <div class="spec-item">
                <span class="spec-label">鈉</span>
                <span class="spec-value">850毫克</span>
              </div>
            </div>
          </div>
        </div>
        
        <!-- 成分資訊 -->
        <div class="specs-card mt-6">
          <h3 class="text-lg font-bold text-gray-800 mb-4">成分資訊</h3>
          <div class="ingredients">
            <p class="text-gray-700 mb-3"><strong>主要成分：</strong>豬肉、糖、鹽、醬油、香料</p>
            <p class="text-gray-700 mb-3"><strong>過敏原：</strong>本產品含有大豆製品</p>
            <p class="text-gray-700"><strong>產地：</strong>台灣</p>
          </div>
        </div>
        
        <!-- 包裝規格 -->
        <div class="specs-card mt-6">
          <h3 class="text-lg font-bold text-gray-800 mb-4">包裝規格</h3>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="package-option">
              <div class="package-icon">📦</div>
              <h4 class="font-semibold text-gray-700">600g 大包裝</h4>
              <p class="text-sm text-gray-600">適合家庭分享</p>
            </div>
            <div class="package-option">
              <div class="package-icon">📦</div>
              <h4 class="font-semibold text-gray-700">300g 中包裝</h4>
              <p class="text-sm text-gray-600">適合小家庭</p>
            </div>
            <div class="package-option">
              <div class="package-icon">📦</div>
              <h4 class="font-semibold text-gray-700">隨手包</h4>
              <p class="text-sm text-gray-600">適合個人享用</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- 相關推薦 -->
    <div v-if="active==='相關推薦'" class="mt-6 tab-content">
      <div class="recommendations-section">
        <h3 class="text-lg font-bold text-gray-800 mb-6">您可能也喜歡</h3>
        
        <!-- 載入狀態 -->
        <div v-if="isLoadingRecommendations" class="flex justify-center items-center py-8">
          <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
          <span class="ml-2 text-gray-600">載入推薦商品中...</span>
        </div>
        
        <!-- 推薦商品 -->
        <div v-else-if="actualRecommendations.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="recommendation in actualRecommendations" :key="recommendation.id" class="recommendation-card">
            <div class="recommendation-image">
              <img 
                :src="getProductImage(recommendation)" 
                :alt="recommendation.name" 
                class="w-full h-32 object-cover rounded-lg"
                @error="handleImageError"
              />
              <!-- 狀態標籤 -->
              <div v-if="!recommendation.can_add_to_cart" class="absolute top-2 right-2">
                <span class="bg-blue-500 text-white text-xs px-2 py-1 rounded">
                  {{ getStatusText(recommendation.status) }}
                </span>
              </div>
            </div>
            <div class="recommendation-info p-4">
              <h4 class="font-semibold text-gray-800 mb-2">{{ recommendation.name }}</h4>
              <p class="text-sm text-gray-600 mb-2">{{ recommendation.category_name || '美味商品' }}</p>
              <div class="flex justify-between items-center">
                <span class="text-primary-600 font-bold">
                  NT$ {{ formatPrice(recommendation) }}
                </span>
                <button class="btn-view-new" @click="viewProduct(recommendation.id)">
                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                  </svg>
                  查看詳情
                </button>
              </div>
            </div>
          </div>
        </div>
        
        <!-- 無推薦商品 -->
        <div v-else class="text-center py-8">
          <p class="text-gray-500">暫時沒有相關推薦商品</p>
        </div>
        
        <!-- 推薦理由 -->
        <div class="mt-8">
          <h3 class="text-lg font-bold text-gray-800 mb-4">為什麼推薦這些商品？</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="reason-card">
              <div class="reason-icon">🔄</div>
              <h4 class="font-semibold text-gray-700 mb-2">同系列商品</h4>
              <p class="text-sm text-gray-600">與您正在查看的商品屬於同一系列，風味相近但各有特色。</p>
            </div>
            <div class="reason-card">
              <div class="reason-icon">👥</div>
              <h4 class="font-semibold text-gray-700 mb-2">熱門選擇</h4>
              <p class="text-sm text-gray-600">這些都是其他顧客經常一起購買的商品組合。</p>
            </div>
            <div class="reason-card">
              <div class="reason-icon">⭐</div>
              <h4 class="font-semibold text-gray-700 mb-2">高評價商品</h4>
              <p class="text-sm text-gray-600">獲得顧客高度評價，品質有保證。</p>
            </div>
            <div class="reason-card">
              <div class="reason-icon">🎯</div>
              <h4 class="font-semibold text-gray-700 mb-2">完美搭配</h4>
              <p class="text-sm text-gray-600">與您選擇的商品搭配使用，能創造更豐富的味覺體驗。</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

interface ProductRecommendItem {
  id: number;
  name: string;
  price_large?: number;
  price_small?: number;
  display_price?: number;
  image?: string;
  images?: any[];
  primary_image?: { image_path: string };
  category_name?: string;
  category_id?: number;
  status: string;
  can_add_to_cart: boolean;
  hot?: boolean;
  rating?: number;
  sold_count?: number;
}

const props = defineProps<{ product: any }>()
const router = useRouter()

const tabs = ['商品說明', '商品規格', '相關推薦']
const active = ref('商品說明')

// 推薦相關狀態
const actualRecommendations = ref<ProductRecommendItem[]>([])
const isLoadingRecommendations = ref(false)

// 取得推薦商品
const fetchRecommendations = async () => {
  if (!props.product?.id) return;
  
  isLoadingRecommendations.value = true;
  try {
    const response = await axios.get(`/api/v1/products/${props.product.id}/recommendations`, {
      params: { limit: 6 }
    });
    
    if (response.data.success) {
      actualRecommendations.value = response.data.data.recommendations;
    }
  } catch (error) {
    console.error('Failed to fetch recommendations:', error);
    actualRecommendations.value = [];
  } finally {
    isLoadingRecommendations.value = false;
  }
};

// 取得商品圖片
const getProductImage = (product: ProductRecommendItem): string => {
  // 優先使用 primary_image
  if (product.primary_image?.image_path) {
    return product.primary_image.image_path;
  }
  
  // 使用 image 欄位
  if (product.image) {
    return product.image.startsWith('/') ? product.image : `/${product.image}`;
  }
  
  // 使用 images 陣列第一張
  if (product.images && product.images.length > 0) {
    const firstImage = product.images[0];
    if (typeof firstImage === 'string') {
      return firstImage.startsWith('/') ? firstImage : `/${firstImage}`;
    }
    if (firstImage.image_path) {
      return firstImage.image_path.startsWith('/') ? firstImage.image_path : `/${firstImage.image_path}`;
    }
  }
  
  // 預設圖片
  return '/images/placeholder.jpg';
};

// 格式化價格
const formatPrice = (product: ProductRecommendItem): string => {
  const price = product.display_price || product.price_large || product.price_small || 0;
  return new Intl.NumberFormat('zh-TW').format(price);
};

// 取得狀態文字
const getStatusText = (status: string): string => {
  const statusMap: Record<string, string> = {
    published: '上架',
    notification: '通知',
    draft: '草稿',
    archived: '封存'
  };
  return statusMap[status] || status;
};

// 處理圖片載入錯誤
const handleImageError = (event: Event) => {
  const img = event.target as HTMLImageElement;
  img.src = '/images/placeholder.jpg';
};

// 查看商品詳情
const viewProduct = (productId: number) => {
  router.push({ name: 'product-detail', params: { id: productId } });
};

// 監聽商品變化
watch(() => props.product?.id, () => {
  if (props.product?.id) {
    fetchRecommendations();
  }
}, { immediate: true });

// 當切換到相關推薦頁籤時載入推薦
watch(active, (newTab) => {
  if (newTab === '相關推薦' && props.product?.id && actualRecommendations.value.length === 0) {
    fetchRecommendations();
  }
});

onMounted(() => {
  if (props.product?.id) {
    fetchRecommendations();
  }
});

// 保留原有的推薦數據作為備用（如果 API 失敗時使用）
const recommendations = computed(() => {
  const baseRecommendations = [
    // 經典系列 (ID: 1-5)
    {
      id: 1,
      name: '黃金經典豬肉鬆',
      description: '經典風味豬肉鬆，口感香Q，適合全家共享。',
      price: 340,
      price_small: 170,
      image: '/images/products/golden-classic-pork-jerky.jpg'
    },
    {
      id: 2,
      name: '海苔芝麻豬肉鬆',
      description: '嚴選豬肉鬆搭配香脆海苔與芝麻，鹹香酥脆、口感層次豐富。',
      price: 340,
      price_small: 170,
      image: '/images/products/seaweed-classic-pork-jerky.jpg'
    },
    {
      id: 3,
      name: '豬肉脯',
      description: '傳統豬肉脯，香氣四溢，入口即化。',
      price: 350,
      price_small: 175,
      image: '/images/products/pork-floss.jpg'
    },
    {
      id: 4,
      name: '五香豬肉條',
      description: '五香調味，鹹香適中，越吃越涮嘴，是下酒菜的首選。',
      price: 440,
      price_small: 220,
      image: '/images/products/five-spice-pork-jerky.jpg'
    },
    {
      id: 5,
      name: '五香豬肉絲',
      description: '精選豬後腿肉，搭配五香秘製配方，鹹香甘甜、越嚼越香。',
      price: 440,
      price_small: 220,
      image: '/images/products/five-spice-pork-jerky-dry.jpg'
    },
    // 肉乾系列 (ID: 6-12)
    {
      id: 6,
      name: '蜜汁原味豬肉乾',
      description: '特製蜜汁醃製，甜鹹適中，肉質鮮嫩有嚼勁。',
      price: 420,
      price_small: 210,
      image: '/images/products/honey-jerky.jpg'
    },
    {
      id: 7,
      name: '黑胡椒豬肉乾',
      description: '黑胡椒香氣濃郁，微辣開胃，適合下酒。',
      price: 420,
      price_small: 210,
      image: '/images/products/black-pepper-jerky.jpg'
    },
    {
      id: 8,
      name: '泰式檸檬豬肉乾',
      description: '泰式檸檬風味，酸甜開胃，清爽不膩。',
      price: 520,
      price_small: 260,
      image: '/images/products/thai-lemon-jerky.jpg'
    },
    {
      id: 9,
      name: '杏仁厚片豬肉乾',
      description: '厚切豬肉乾搭配杏仁片，口感豐富。',
      price: 500,
      price_small: 250,
      image: '/images/products/almond-thick-jerky.jpg'
    },
    {
      id: 10,
      name: '杏仁脆片豬肉乾',
      description: '酥脆杏仁片與豬肉乾完美結合，香脆可口。',
      price: 600,
      price_small: 300,
      image: '/images/products/almond-crispy-jerky.jpg'
    },
    {
      id: 11,
      name: '原味薄片豬肉乾',
      description: '薄片設計，原味呈現，入口即化。',
      price: 600,
      price_small: 300,
      image: '/images/products/original-thin-jerky.jpg'
    },
    {
      id: 12,
      name: '黑胡椒薄片豬肉乾',
      description: '黑胡椒薄片，香辣夠味，越吃越涮嘴。',
      price: 660,
      price_small: 330,
      image: '/images/products/black-pepper-thin-jerky.jpg'
    },
    // 海鮮系列 (ID: 13-17)
    {
      id: 13,
      name: '旗魚鬆',
      description: '旗魚鬆，細緻綿密，入口即化，營養豐富。',
      price: 380,
      price_small: 180,
      image: '/images/products/sailfish-floss.jpg'
    },
    {
      id: 14,
      name: '旗魚脯',
      description: '旗魚脯，鮮美可口，營養豐富。',
      price: 380,
      price_small: 180,
      image: '/images/products/sailfish-jerky.jpg'
    },
    {
      id: 15,
      name: '原味魷魚絲',
      description: '原味魷魚絲，Q彈有嚼勁，海味十足。',
      price: 660,
      price_small: 330,
      image: '/images/products/original-squid-strips.jpg'
    },
    {
      id: 16,
      name: '碳烤魷魚絲',
      description: '碳烤風味，香氣撲鼻，越嚼越香。',
      price: 660,
      price_small: 330,
      image: '/images/products/grilled-squid-strips.jpg'
    },
    {
      id: 17,
      name: '魷魚片',
      description: '魷魚片，厚實彈牙，鮮味十足。',
      price: 660,
      price_small: 330,
      image: '/images/products/squid-slices.jpg'
    },
    // 休閒系列 (ID: 18-26)
    {
      id: 18,
      name: '雲林黑金剛花生',
      description: '嚴選雲林黑金剛花生，顆粒飽滿，香脆可口。',
      price: 200,
      price_small: 100,
      image: '/images/products/peanut-black.jpg'
    },
    {
      id: 19,
      name: '焦糖葵瓜子',
      description: '焦糖包裹葵瓜子，甜而不膩，越吃越香。',
      price: 200,
      price_small: 100,
      image: '/images/products/caramel-sunflower.jpg'
    },
    {
      id: 20,
      name: '桂圓紅棗葵瓜子',
      description: '桂圓紅棗風味，葵瓜子新體驗。',
      price: 200,
      price_small: 100,
      image: '/images/products/longan-jujube-sunflower.jpg'
    }
  ]
  
  // 根據當前商品分類過濾推薦商品，並隨機選擇6個
  const filteredRecommendations = baseRecommendations.filter(item => item.id !== props.product.id)
  
  // 隨機打亂並取前6個
  const shuffled = filteredRecommendations.sort(() => 0.5 - Math.random())
  return shuffled.slice(0, 6)
})

// 查看商品功能
function viewProduct(productId: number) {
  // 使用 router 跳轉到商品詳細頁
  window.location.href = `/products/${productId}`
}
</script>

<style scoped>
.tabs button { 
  margin-right: 1rem; 
  padding: 0.5rem 1.2rem; 
  border: none; 
  background: #f5f5f5; 
  border-radius: 6px 6px 0 0; 
  cursor: pointer; 
  transition: all 0.2s;
}

.tabs button.active { 
  background: #b8860b; 
  color: #fff; 
}

.tabs button:hover:not(.active) {
  background: #e5e5e5;
}

.tab-content {
  background: #fff;
  border-radius: 0 6px 6px 6px;
  padding: 2rem;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.feature-card {
  background: #f8f9fa;
  padding: 1.5rem;
  border-radius: 8px;
  text-align: center;
  transition: transform 0.2s;
}

.feature-card:hover {
  transform: translateY(-2px);
}

.feature-icon {
  font-size: 2rem;
  margin-bottom: 1rem;
}

.description-text {
  line-height: 1.8;
}

.specs-card {
  background: #f8f9fa;
  padding: 1.5rem;
  border-radius: 8px;
}

.specs-list {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.spec-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.5rem 0;
  border-bottom: 1px solid #e5e5e5;
}

.spec-item:last-child {
  border-bottom: none;
}

.spec-label {
  font-weight: 600;
  color: #374151;
}

.spec-value {
  color: #6b7280;
}

.ingredients {
  line-height: 1.6;
}

.package-option {
  text-align: center;
  padding: 1rem;
  background: #fff;
  border-radius: 6px;
  border: 1px solid #e5e5e5;
}

.package-icon {
  font-size: 1.5rem;
  margin-bottom: 0.5rem;
}

.recommendation-card {
  background: #f8f6f2;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0,0,0,0.10);
  transition: transform 0.2s;
}

.recommendation-card:hover {
  transform: translateY(-4px);
}


button.btn-view-new {
  display: inline-flex;
  align-items: center;
  gap: 0.5em;
  background: linear-gradient(90deg, #cb6a43 0%, #b85c38 100%);
  color: #fff;
  border: none;
  padding: 0.7em 1.5em;
  border-radius: 25px;
  font-size: 1rem;
  font-weight: 700;
  letter-spacing: 0.03em;
  cursor: pointer;
  box-shadow: 0 4px 16px rgba(200, 106, 67, 0.18);
  transition: 
    background 0.2s,
    box-shadow 0.2s,
    transform 0.15s;
  outline: none;
  position: relative;
  z-index: 1;
}

button.btn-view-new svg {
  margin-right: 0.5em;
  width: 1.2em;
  height: 1.2em;
  vertical-align: middle;
}

button.btn-view-new:hover, button.btn-view-new:focus {
  background: linear-gradient(90deg, #e07a4a 0%, #a04a2e 100%) !important;
  box-shadow: 0 8px 24px rgba(200, 106, 67, 0.28);
  transform: translateY(-2px) scale(1.04);
}

button.btn-view-new:active {
  background: linear-gradient(90deg, #b85c38 0%, #cb6a43 100%);
  box-shadow: 0 2px 8px rgba(200, 106, 67, 0.15);
  transform: none;
}

.reason-card {
  background: #f8f9fa;
  padding: 1rem;
  border-radius: 6px;
  display: flex;
  align-items: flex-start;
  gap: 0.75rem;
}

.reason-icon {
  font-size: 1.25rem;
  flex-shrink: 0;
}
</style> 