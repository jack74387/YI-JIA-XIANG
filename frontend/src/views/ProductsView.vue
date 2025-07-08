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
          <div class="flex gap-2">
            <select v-model="selectedCategory" @change="handleCategoryChange" class="input-field w-auto">
              <option value="">所有分類</option>
              <option v-for="category in categories" :key="category.id" :value="category.id">
                {{ category.name }}
              </option>
            </select>

            <!-- 排序 -->
            <select v-model="sortBy" @change="handleSort" class="input-field w-auto">
              <option value="created_at">最新上架</option>
              <option value="price">價格低到高</option>
              <option value="price_desc">價格高到低</option>
              <option value="name">名稱 A-Z</option>
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
            class="card hover:shadow-lg transition-shadow cursor-pointer"
            @click="goToProduct(product.id)"
          >
            <!-- 商品圖片 -->
            <div class="relative">
              <img
                :src="product.primary_image?.image_path || '/images/placeholder.jpg'"
                :alt="product.name"
                class="w-full h-48 object-cover rounded-lg"
              />
              <!-- 特價標籤 -->
              <div v-if="product.has_discount" class="absolute top-2 left-2 bg-red-500 text-white px-2 py-1 rounded text-sm font-bold">
                -{{ product.discount_percentage }}%
              </div>
            </div>

            <!-- 商品資訊 -->
            <div class="p-4">
              <h3 class="text-lg font-semibold mb-2 line-clamp-2">{{ product.name }}</h3>
              <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ product.short_description }}</p>
              
              <!-- 價格 -->
              <div class="flex items-center gap-2 mb-3">
                <span class="text-xl font-bold text-primary-600">NT$ {{ product.final_price }}</span>
                <span v-if="product.has_discount" class="text-sm text-gray-500 line-through">NT$ {{ product.price }}</span>
              </div>

              <!-- 庫存狀態 -->
              <div class="flex items-center justify-between">
                <span class="text-sm text-gray-500">
                  {{ product.stock_quantity > 0 ? `庫存: ${product.stock_quantity}` : '缺貨中' }}
                </span>
                <button
                  class="btn-primary text-sm"
                  :disabled="product.stock_quantity <= 0"
                  @click.stop="addToCart(product)"
                >
                  {{ product.stock_quantity > 0 ? '加入購物車' : '缺貨' }}
                </button>
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
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useCartStore } from '@/stores/cart'

const router = useRouter()
const cartStore = useCartStore()

const API_BASE = 'http://127.0.0.1:8000';

// 響應式資料
const products = ref<any[]>([])
const categories = ref<any[]>([])
const loading = ref(false)
const searchQuery = ref('')
const selectedCategory = ref('')
const sortBy = ref('created_at')
const pagination = ref<any>(null)

// 搜尋防抖
let searchTimeout: NodeJS.Timeout

// 載入商品
const loadProducts = async (page = 1) => {
  loading.value = true
  try {
    const params = new URLSearchParams({
      page: page.toString(),
      search: searchQuery.value,
      category_id: selectedCategory.value,
      sort_by: sortBy.value,
      sort_order: sortBy.value === 'price_desc' ? 'desc' : 'asc'
    })

    const response = await fetch(`${API_BASE}/api/v1/products?${params}`)
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
    const response = await fetch(`${API_BASE}/api/v1/categories`)
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

// 加入購物車
const addToCart = (product: any) => {
  cartStore.addToCart({
    id: product.id,
    name: product.name,
    price: product.final_price,
    quantity: 1,
    image: product.primary_image?.image_path
  })
}

// 前往指定頁面
const goToPage = (page: number) => {
  loadProducts(page)
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
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style> 