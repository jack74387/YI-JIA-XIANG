<template>
  <div v-if="recommendations.length > 0" class="recommend">
    <h2>你可能會喜歡</h2>
    <div class="recommend-list">
      <div 
        v-for="item in recommendations" 
        :key="item.id" 
        class="recommend-card"
        @click="goToProduct(item.id)"
      >
        <div class="image-container">
          <img 
            :src="getProductImage(item)" 
            :alt="item.name" 
            @error="handleImageError"
          />
          <div v-if="!item.can_add_to_cart" class="status-badge">
            {{ getStatusText(item.status) }}
          </div>
        </div>
        <div class="product-info">
          <div class="name">{{ item.name }}</div>
          <div class="category">{{ item.category_name }}</div>
          <div class="price">NT$ {{ formatPrice(item) }}</div>
        </div>
      </div>
    </div>
    <div v-if="isLoading" class="loading">
      載入推薦商品中...
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
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

interface RecommendationResponse {
  success: boolean;
  data: {
    product_id: number;
    product_name: string;
    product_price: number;
    recommendations: ProductRecommendItem[];
    recommendation_count: number;
    strategies_used: {
      category_based: boolean;
      price_based: boolean;
      popular_fallback: boolean;
    };
  };
}

// Props
interface Props {
  productId: number;
  limit?: number;
}

const props = withDefaults(defineProps<Props>(), {
  limit: 6
});

const router = useRouter();
const recommendations = ref<ProductRecommendItem[]>([]);
const isLoading = ref(false);

// 取得推薦商品
const fetchRecommendations = async () => {
  if (!props.productId) return;
  
  isLoading.value = true;
  try {
    const response = await axios.get<RecommendationResponse>(
      `/api/v1/products/${props.productId}/recommendations`,
      { params: { limit: props.limit } }
    );
    
    if (response.data.success) {
      recommendations.value = response.data.data.recommendations;
    }
  } catch (error) {
    console.error('Failed to fetch recommendations:', error);
    recommendations.value = [];
  } finally {
    isLoading.value = false;
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

// 跳轉到商品頁面
const goToProduct = (productId: number) => {
  router.push({ name: 'product-detail', params: { id: productId } });
};

// 監聽 productId 變化
watch(() => props.productId, () => {
  fetchRecommendations();
}, { immediate: true });

onMounted(() => {
  fetchRecommendations();
});
</script>

<style scoped>
.recommend {
  background: linear-gradient(135deg, #fffbe8 0%, #fff8e1 100%);
  border-radius: 1.2rem;
  box-shadow: 0 4px 16px rgba(224, 198, 138, 0.15);
  padding: 1.5rem 1rem;
  margin: 2rem 0;
  font-family: 'Noto Sans TC', 'Segoe UI', Arial, sans-serif;
  position: relative;
  overflow: hidden;
}

.recommend::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 2px;
  background: linear-gradient(90deg, #b8860b, #daa520, #b8860b);
}

.recommend h2 {
  color: #b8860b;
  font-size: 1.3rem;
  font-weight: 700;
  margin-bottom: 1.2rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.recommend h2::before {
  content: '💝';
  font-size: 1.1rem;
}

.recommend-list {
  display: flex;
  gap: 1rem;
  overflow-x: auto;
  padding-bottom: 0.5rem;
  scroll-behavior: smooth;
}

.recommend-list::-webkit-scrollbar {
  height: 6px;
}

.recommend-list::-webkit-scrollbar-track {
  background: rgba(224, 198, 138, 0.1);
  border-radius: 3px;
}

.recommend-list::-webkit-scrollbar-thumb {
  background: rgba(184, 134, 11, 0.3);
  border-radius: 3px;
}

.recommend-list::-webkit-scrollbar-thumb:hover {
  background: rgba(184, 134, 11, 0.5);
}

.recommend-card {
  min-width: 140px;
  background: #fffdfa;
  border-radius: 0.8rem;
  box-shadow: 0 2px 8px rgba(224, 198, 138, 0.15);
  text-align: center;
  padding: 0.8rem 0.6rem;
  transition: all 0.3s ease;
  cursor: pointer;
  border: 1px solid rgba(224, 198, 138, 0.1);
  flex-shrink: 0;
}

.recommend-card:hover {
  box-shadow: 0 6px 20px rgba(184, 134, 11, 0.25);
  transform: translateY(-2px) scale(1.02);
  border-color: rgba(184, 134, 11, 0.3);
}

.image-container {
  position: relative;
  margin-bottom: 0.6rem;
}

.recommend-card img {
  width: 90px;
  height: 90px;
  object-fit: cover;
  border-radius: 0.6rem;
  background: #f8f8f8;
  border: 2px solid rgba(224, 198, 138, 0.1);
  transition: border-color 0.3s ease;
}

.recommend-card:hover img {
  border-color: rgba(184, 134, 11, 0.3);
}

.status-badge {
  position: absolute;
  top: -2px;
  right: -2px;
  background: linear-gradient(135deg, #3b82f6, #1d4ed8);
  color: white;
  font-size: 0.7rem;
  padding: 0.2rem 0.4rem;
  border-radius: 0.3rem;
  font-weight: 600;
  box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);
}

.product-info {
  display: flex;
  flex-direction: column;
  gap: 0.3rem;
}

.name {
  color: #8b5a00;
  font-size: 0.9rem;
  font-weight: 600;
  line-height: 1.3;
  height: 2.6rem;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.category {
  color: #a67c00;
  font-size: 0.75rem;
  opacity: 0.8;
  font-weight: 500;
}

.price {
  color: #d97706;
  font-size: 0.85rem;
  font-weight: 700;
  margin-top: 0.2rem;
}

.loading {
  text-align: center;
  color: #a67c00;
  font-size: 0.9rem;
  padding: 1rem;
  background: rgba(255, 253, 250, 0.8);
  border-radius: 0.6rem;
  margin-top: 1rem;
}

/* 響應式設計 */
@media (max-width: 768px) {
  .recommend {
    margin: 1.5rem 0;
    padding: 1.2rem 0.8rem;
  }
  
  .recommend h2 {
    font-size: 1.1rem;
  }
  
  .recommend-card {
    min-width: 120px;
    padding: 0.6rem 0.5rem;
  }
  
  .recommend-card img {
    width: 75px;
    height: 75px;
  }
  
  .name {
    font-size: 0.85rem;
  }
  
  .price {
    font-size: 0.8rem;
  }
}
</style> 