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
                <span class="spec-value">{{ product.shelf_life || '60天（未開封）' }}</span>
              </div>
              <div class="spec-item">
                <span class="spec-label">保存方式</span>
                <span class="spec-value">{{ product.storage_instructions || '常溫保存，開封後請冷藏' }}</span>
              </div>
              <div class="spec-item">
                <span class="spec-label">產地</span>
                <span class="spec-value">{{ product.origin || '台灣' }}</span>
              </div>
            </div>
          </div>
          
          <!-- 營養資訊 -->
          <div class="specs-card">
            <h3 class="text-lg font-bold text-gray-800 mb-4">營養資訊</h3>
            <div class="specs-list" v-if="product.nutrition_info && Object.keys(product.nutrition_info).length > 0">
              <div class="spec-item">
                <span class="spec-label">每100公克</span>
                <span class="spec-value"></span>
              </div>
              <div v-for="(value, key) in product.nutrition_info" :key="key" class="spec-item">
                <span class="spec-label">{{ getNutritionLabel(key) }}</span>
                <span class="spec-value">{{ value }}</span>
              </div>
            </div>
            <div v-else class="specs-list">
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
            <p class="text-gray-700 mb-3"><strong>主要成分：</strong>{{ product.ingredients || '豬肉、糖、鹽、醬油、香料' }}</p>
            <p class="text-gray-700 mb-3"><strong>過敏原：</strong>{{ product.allergens || '本產品含有大豆製品' }}</p>
            <p class="text-gray-700"><strong>產地：</strong>{{ product.origin || '台灣' }}</p>
          </div>
        </div>
        
        <!-- 包裝規格 -->
        <div class="specs-card mt-6">
          <h3 class="text-lg font-bold text-gray-800 mb-4">包裝規格</h3>
          <div v-if="product.package_info && product.package_info.length > 0" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div v-for="(pkg, index) in product.package_info" :key="index" class="package-option">
              <div class="package-icon">📦</div>
              <h4 class="font-semibold text-gray-700">{{ pkg.name || pkg.title }}</h4>
              <p class="text-sm text-gray-600">{{ pkg.description || pkg.desc }}</p>
            </div>
          </div>
          <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
                class="w-full h-48 object-contain rounded-lg bg-[#f9f6f1] border"
                @error="handleImageError"
                @load="handleImageLoad"
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
import { getImageUrl } from '@/utils/imageUtils'

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
      console.log('Fetched recommendations:', response.data.data.recommendations);
      
      // 檢查每個推薦商品的 ID
      response.data.data.recommendations.forEach((rec: any, index: number) => {
        console.log(`Recommendation ${index}:`, { id: rec.id, name: rec.name });
      });
    }
  } catch (error) {
    console.error('Failed to fetch recommendations:', error);
    actualRecommendations.value = [];
  } finally {
    isLoadingRecommendations.value = false;
  }
};

// 取得商品圖片（使用與商品頁面相同的處理方式）
const getProductImage = (product: ProductRecommendItem): string => {
  // 優先使用 primary_image
  if (product.primary_image?.image_path) {
    return getImageUrl(product.primary_image.image_path);
  }
  
  // 使用 image 欄位
  if (product.image) {
    return getImageUrl(product.image);
  }
  
  // 使用 images 陣列第一張
  if (product.images && product.images.length > 0) {
    const firstImage = product.images[0];
    if (typeof firstImage === 'string') {
      return getImageUrl(firstImage);
    }
    if (firstImage.image_path) {
      return getImageUrl(firstImage.image_path);
    }
  }
  
  // 預設圖片
  return getImageUrl('/images/placeholder.jpg');
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

// 取得營養標籤文字
const getNutritionLabel = (key: string): string => {
  const labelMap: Record<string, string> = {
    calories: '熱量',
    protein: '蛋白質', 
    fat: '脂肪',
    carbohydrates: '碳水化合物',
    sodium: '鈉',
    sugar: '糖',
    fiber: '膳食纖維',
    calcium: '鈣',
    iron: '鐵',
    vitaminC: '維生素C'
  };
  return labelMap[key] || key;
};

// 處理圖片載入錯誤（使用與商品頁面相同的處理方式）
const handleImageError = (event: Event) => {
  const img = event.target as HTMLImageElement;
  img.src = getImageUrl('/images/placeholder.jpg');
};

// 處理圖片載入成功
const handleImageLoad = (event: Event) => {
  const img = event.target as HTMLImageElement;
  // 可以在這裡添加載入成功的邏輯，比如移除載入動畫
  console.log('Image loaded successfully:', img.src);
};

// 查看商品詳情
const viewProduct = (productId: number) => {
  console.log('viewProduct called with ID:', productId);
  console.log('Current route:', router.currentRoute.value);
  
  // 確保 productId 是數字類型
  const id = Number(productId);
  console.log('Parsed ID:', id);
  
  if (!id || isNaN(id)) {
    console.error('Invalid product ID:', productId);
    return;
  }
  
  // 嘗試跳轉
  try {
    router.push({ name: 'product-detail', params: { id: id.toString() } });
    console.log('Navigation attempted to product-detail with id:', id);
  } catch (error) {
    console.error('Navigation error:', error);
  }
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