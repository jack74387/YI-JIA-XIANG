<template>
  <div class="products-page">
    <!-- 頁面標題 -->
    <section class="bg-gray-100 py-8">
      <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-3xl font-bold text-gray-900">商品列表</h1>
        <p class="text-gray-600 mt-2">探索我們精選的香品產品</p>
      </div>
    </section>

    <!-- 篩選與搜尋 -->
    <section class="py-6">
      <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
          <!-- 搜尋框 -->
          <div class="w-full md:w-96">
            <input
              v-model="searchQuery"
              type="text"
              placeholder="搜尋商品..."
              class="input-field"
              @input="handleSearch"
            />
          </div>

          <!-- 分類篩選 -->
          <div class="flex gap-3 items-center">
            <select v-model="selectedCategory" @change="handleCategoryChange" class="input-field w-auto">
              <option value="">所有分類</option>
              <option v-for="category in categories" :key="category.id" :value="category.id">
                {{ category.name }}
              </option>
            </select>
            <select v-model="sortBy" @change="handleSort" class="input-field w-auto">
              <option value="created_at">最新上架</option>
              <option value="hot">熱賣商品</option>
              <option value="views">最多人看</option>
              <option value="price_asc">價格低到高</option>
              <option value="price_desc">價格高到低</option>
            </select>
          </div>
        </div>
      </div>
    </section>

    <!-- 商品列表 -->
    <section class="py-8">
      <div class="max-w-7xl mx-auto px-4">
        <!-- 載入中 -->
        <div v-if="loading" class="text-center py-12">
          <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
          <p class="mt-2 text-gray-600">載入中...</p>
        </div>

        <!-- 商品網格 -->
        <div v-else-if="products.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
          <div
            v-for="product in products"
            :key="product.id"
            class="card hover:shadow-lg transition-shadow cursor-pointer flex flex-col"
          >
            <div class="relative" @click="goToProduct(product.id)">
              <img
                :src="getImageUrl(product.primary_image?.image_path) || '/images/placeholder.jpg'"
                :alt="product.name"
                class="w-full h-48 object-cover rounded-t-lg mx-auto"
                :class="product.status === 'notification' ? 'brightness-75 grayscale' : ''"
              />
              <div v-if="product.status === 'notification'" class="absolute inset-0 flex items-center justify-center z-10">
                <span class="text-base font-bold bg-black bg-opacity-60 text-white px-4 py-1.5 rounded">貨到通知</span>
              </div>
              <div v-if="product.has_discount" class="absolute top-2 left-2 bg-red-500 text-white px-2 py-1 rounded text-sm font-bold">
                -{{ product.discount_percentage }}%
              </div>
            </div>
            <div class="p-4 flex flex-col flex-1">
              <h3 class="text-lg font-semibold mb-2 text-left line-clamp-2" @click="goToProduct(product.id)">{{ product.name }}</h3>
              <div class="text-xl font-bold text-primary-600 mb-4 text-left">
                NT$ {{ selectedSpecs[product.id] === 'large' ? product.price_large : product.price_small }}
              </div>
              <div class="flex flex-row justify-between items-end mt-auto">
                <div class="flex flex-col items-start gap-1">
                  <button
                    v-for="spec in ['large', 'small']"
                    :key="spec"
                    :class="['spec-btn', selectedSpecs[product.id] === spec || (!selectedSpecs[product.id] && spec === 'small') ? 'active' : '', 'spec-btn-small']"
                    @click="selectSpec(product, spec)"
                  >
                    {{ getSpecLabel(spec) }}
                  </button>
                </div>
                <div class="flex flex-row gap-1 items-end self-end pb-1 ml-auto pl-16 -mr-5">
                  <button class="icon-btn icon-btn-small" @click.stop="toggleFav(product)" :title="isFav(product) ? '取消收藏' : '加入收藏'">
                    <svg v-if="isFav(product)" class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                    </svg>
                    <svg v-else class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                    </svg>
                  </button>
                  <button
                    class="icon-btn icon-btn-small"
                    :disabled="!product.can_add_to_cart"
                    @click.stop="openAddToCart(product)"
                    :title="!product.can_add_to_cart ? '此商品僅供參考，無法加入購物車' : '加入購物車'"
                    :class="{
                      'icon-btn-disabled': !product.can_add_to_cart
                    }"
                  >
                    <!-- 正常購物車圖標 -->
                    <svg v-if="product.can_add_to_cart" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#b85c38" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <circle cx="9" cy="20" r="1.5" fill="#b85c38"/>
                      <circle cx="18" cy="20" r="1.5" fill="#b85c38"/>
                      <path d="M6 6h15l-1.5 9h-13z"/>
                      <path d="M6 6L5 2H2"/>
                    </svg>
                    <!-- 禁用狀態：灰色購物車圖標 + 禁止符號 -->
                    <template v-else>
                      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#b0b0b0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="20" r="1.5" fill="#b0b0b0"/>
                        <circle cx="18" cy="20" r="1.5" fill="#b0b0b0"/>
                        <path d="M6 6h15l-1.5 9h-13z"/>
                        <path d="M6 6L5 2H2"/>
                      </svg>
                      <svg class="ban-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" style="position: absolute; left: 0; top: 0; pointer-events: none; opacity: 0; transition: opacity 0.2s;">
                        <circle cx="12" cy="12" r="10" stroke="#d9534f" stroke-width="2" fill="none"/>
                        <line x1="7" y1="7" x2="17" y2="17" stroke="#d9534f" stroke-width="2"/>
                      </svg>
                    </template>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- 無商品 -->
        <div v-else class="text-center py-12">
          <div class="text-6xl mb-4">🔍</div>
          <h3 class="text-xl font-semibold mb-2">找不到相關商品</h3>
          <p class="text-gray-600 mb-4">請嘗試調整搜尋條件或分類篩選</p>
          <button @click="clearFilters" class="btn-primary">清除篩選</button>
        </div>

        <!-- 分頁 -->
        <div v-if="pagination && pagination.last_page > 1" class="mt-8 flex justify-center">
          <nav class="flex items-center gap-2">
            <button
              v-for="page in pagination.last_page"
              :key="page"
              @click="goToPage(page)"
              :class="[
                'px-3 py-2 rounded',
                page === pagination.current_page
                  ? 'bg-primary-600 text-white'
                  : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
              ]"
            >
              {{ page }}
            </button>
          </nav>
        </div>
        <ProductAddToCartModal
          :show="showAddToCart"
          :product="selectedProduct"
          @close="showAddToCart = false"
          @added="onAddedToCart"
        />
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useCartStore } from '@/stores/cart'
import ProductAddToCartModal from '@/components/ProductAddToCartModal.vue'

const router = useRouter()
const cartStore = useCartStore()

// 響應式資料
const products = ref<any[]>([])
const categories = ref<any[]>([])
const loading = ref(false)
const searchQuery = ref('')
const selectedCategory = ref('')
const sortBy = ref('created_at')
const pagination = ref<any>(null)

// 彈窗相關
const showAddToCart = ref(false)
const selectedProduct = ref<any>(null)

const selectedSpecs = ref<Record<number, string>>({}) // productId -> 'large' | 'small'
function getSpecPrice(product: any, spec: string) {
  if (spec === 'large') return product.price_large
  if (spec === 'small') return product.price_small
  return product.price_small
}
function getSpecLabel(spec: string) {
  if (spec === 'large') return '600g'
  if (spec === 'small') return '300g'
  return ''
}
function selectSpec(product: any, spec: string) {
  selectedSpecs.value[product.id] = spec
}

// 處理圖片 URL
function getImageUrl(imagePath: string | undefined) {
  if (!imagePath) return null
  if (imagePath.startsWith('http')) return imagePath
  // 只要是 /storage 開頭就加 API_BASE
  if (imagePath.startsWith('/storage')) return `/api${imagePath}`
  if (imagePath.startsWith('/')) return `/api${imagePath}`
  return imagePath
}

// 搜尋防抖
let searchTimeout: NodeJS.Timeout

// 載入商品
const loadProducts = async (page = 1) => {
  loading.value = true
  try {
    let sort_field = 'created_at', sort_order = 'desc'
    if (sortBy.value === 'price_asc') {
      sort_field = 'price_small'; sort_order = 'asc'
    } else if (sortBy.value === 'price_desc') {
      sort_field = 'price_small'; sort_order = 'desc'
    } else if (sortBy.value === 'hot') {
      sort_field = 'hot'; sort_order = 'desc'
    } else if (sortBy.value === 'views') {
      sort_field = 'views'; sort_order = 'desc'
    } else if (sortBy.value === 'created_at') {
      sort_field = 'created_at'; sort_order = 'desc'
    }
    const params = new URLSearchParams({
      page: page.toString(),
      search: searchQuery.value,
      category_id: selectedCategory.value,
      sort_by: sort_field,
      sort_order
    })

    const response = await fetch(`/api/v1/products?${params}`)
    const data = await response.json()

   
    if (data.success) {
      products.value = data.data.data
      pagination.value = data.data
    }
  } catch (error) {
    console.error('載入商品失敗:', error)
  } finally {
    loading.value = false
  }
}

// 載入分類
const loadCategories = async () => {
  try {
    const response = await fetch(`/api/v1/categories`)
    const data = await response.json()

    if (data.success) {
      categories.value = data.data
    }
  } catch (error) {
    console.error('載入分類失敗:', error)
  }
}

// 處理搜尋
const handleSearch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    loadProducts(1)
  }, 500)
}

// 處理分類變更
const handleCategoryChange = () => {
  loadProducts(1)
}

// 處理排序
const handleSort = () => {
  loadProducts(1)
}

// 清除篩選
const clearFilters = () => {
  searchQuery.value = ''
  selectedCategory.value = ''
  sortBy.value = 'created_at'
  loadProducts(1)
}

// 前往商品詳情
const goToProduct = (productId: number) => {
  router.push(`/products/${productId}`)
}

// 前往指定頁面
const goToPage = (page: number) => {
  loadProducts(page)
}

// 加入購物車
const openAddToCart = (product: any) => {
  // 檢查商品是否可以加入購物車
  if (!product.can_add_to_cart) {
    return
  }
  
  const spec = selectedSpecs.value[product.id] || 'small'
  let price = getSpecPrice(product, spec)
  selectedProduct.value = {
    id: product.id,
    name: product.name + '（' + getSpecLabel(spec) + '）',
    price,
    spec,
    image: product.primary_image?.image_path // Assuming product.image is the primary image path
  }
  showAddToCart.value = true
}

function onAddedToCart() {
  cartStore.fetchCart()
}

// 收藏狀態
const favSet = ref<Set<number>>(new Set())
function toggleFav(product: any) {
  if (favSet.value.has(product.id)) {
    favSet.value.delete(product.id)
  } else {
    favSet.value.add(product.id)
  }
}
function isFav(product: any) {
  return favSet.value.has(product.id)
}

// 頁面載入時執行
onMounted(() => {
  loadProducts()
  loadCategories()
})
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
.icon-btn {
  background: #fffbe9;
  border: none;
  border-radius: 50%;
  width: 38px;
  height: 38px;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 1px 4px #e2d6c2;
  cursor: pointer;
  transition: background .2s, transform .2s;
  padding: 0;
}
.icon-btn:hover {
  background: #f3e2c7;
  transform: scale(1.12);
}
.spec-row { margin-bottom: 0; }
.spec-btn {
  background: #f3e2c7;
  color: #b85c38;
  border: none;
  border-radius: 1em;
  padding: 0.2em 1.1em;
  font-size: 0.98em;
  font-weight: 600;
  cursor: pointer;
  transition: background .2s, color .2s;
}
.spec-btn.active, .spec-btn:hover {
  background: #b85c38;
  color: #fffbe8;
}
.price-row { font-size: 1.15em; color: #b85c38; font-weight: bold; }
.card {
  background: #f9f6f1;
  border-radius: 18px;
  box-shadow: 0 2px 12px #e2d6c2;
  overflow: hidden;
  transition: box-shadow .2s, transform .2s;
  display: flex;
  flex-direction: column;
  position: relative;
  min-width: 240px;
  max-width: 320px;
  margin: 0 auto;
  height: 100%;
}
.card:hover {
  box-shadow: 0 6px 24px #d6c3a1;
  transform: translateY(-4px) scale(1.03);
}
.spec-btn {
  background: #f3e2c7;
  color: #b85c38;
  border: none;
  border-radius: 1em;
  padding: 0.4em 1.3em;
  font-size: 1em;
  font-weight: 600;
  cursor: pointer;
  transition: background .2s, color .2s;
  margin-bottom: 0.5em;
}
.spec-btn-small {
  padding: 0.18em 0.9em;
  font-size: 0.92em;
  margin-bottom: 0.18em;
}
.spec-btn.active, .spec-btn:hover {
  background: #b85c38;
  color: #fffbe8;
}
.icon-btn {
  background: #fffbe9;
  border: none;
  border-radius: 50%;
  width: 38px;
  height: 38px;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 1px 4px #e2d6c2;
  cursor: pointer;
  transition: background .2s, transform .2s;
  padding: 0;
}
.icon-btn:hover {
  background: #f3e2c7;
  transform: scale(1.12);
}
.icon-btn-small {
  width: 30px;
  height: 30px;
}
.brightness-75 {
  filter: brightness(0.75);
}
.grayscale {
  filter: grayscale(1);
}
/* 禁用狀態的按鈕樣式 */
.icon-btn,
.icon-btn-disabled {
  position: relative;
}
.icon-btn-disabled {
  background: #f8d7da !important; /* 明顯的淡紅色 */
  color: #999;
  cursor: not-allowed !important;
  box-shadow: 0 1px 4px #e0e0e0;
  opacity: 1;
}
.icon-btn-disabled:hover {
  background: #f5c6cb !important;
}
.icon-btn-disabled .ban-icon {
  opacity: 0;
}
.icon-btn-disabled:hover .ban-icon {
  opacity: 1;
}
.icon-btn-disabled svg {
  stroke: #999 !important;
}
</style> 