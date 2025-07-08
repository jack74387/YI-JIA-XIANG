<template>
  <div class="product-list-page">
    <div class="main-layout">
      <CategorySidebar :categories="categories" :selected="selectedCategory" @select="onSelectCategory" />
      <div class="content">
        <ProductListToolbar :view="view" @search="onSearch" @sort="onSort" @change-view="onChangeView" />
        <div v-if="loading" class="loading">載入中...</div>
        <div v-else-if="filteredProducts.length === 0" class="empty">查無商品</div>
        <div v-else :class="['products', view]">
          <ProductCard v-for="p in filteredProducts" :key="p.id" :product="p" @add-to-cart="addToCart" />
        </div>
      </div>
    </div>
    <SocialFloatingButtons />
  </div>
</template>
<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useProductsStore } from '../stores/products'
import { useCategoriesStore } from '../stores/categories'
import { useCartStore } from '../stores/cart'
import CategorySidebar from '../components/CategorySidebar.vue'
import ProductListToolbar from '../components/ProductListToolbar.vue'
import ProductCard from '../components/ProductCard.vue'
import SocialFloatingButtons from '../components/SocialFloatingButtons.vue'

const productsStore = useProductsStore()
const categoriesStore = useCategoriesStore()
const cartStore = useCartStore()

const selectedCategory = ref<number|null>(null)
const search = ref('')
const sort = ref('relevance')
const view = ref('grid')

const loading = computed(() => productsStore.loading || categoriesStore.loading)
const categories = computed(() => categoriesStore.categories)
const products = computed(() => productsStore.products)

const filteredProducts = computed(() => {
  let list = products.value
  if (selectedCategory.value) {
    list = list.filter(p => p.category_id === selectedCategory.value)
  }
  if (search.value) {
    list = list.filter(p => p.name.includes(search.value) || (p.description && p.description.includes(search.value)))
  }
  if (sort.value === 'price-asc') {
    list = [...list].sort((a, b) => (a.price_large || a.price || 0) - (b.price_large || b.price || 0))
  } else if (sort.value === 'price-desc') {
    list = [...list].sort((a, b) => (b.price_large || b.price || 0) - (a.price_large || a.price || 0))
  }
  return list
})

function onSelectCategory(id: number|null) {
  selectedCategory.value = id
}
function onSearch(val: string) {
  search.value = val
}
function onSort(val: string) {
  sort.value = val
}
function onChangeView(val: string) {
  view.value = val
}
function addToCart(product: any) {
  cartStore.addToCart(product, 1)
}
onMounted(() => {
  productsStore.fetchProducts()
  categoriesStore.fetchCategories()
  cartStore.fetchCart()
})
</script>
<style scoped>
.product-list-page {
  background: #f5efe6;
  min-height: 100vh;
  padding: 32px 0 0 0;
}
.main-layout {
  display: flex;
  gap: 32px;
  max-width: 1280px;
  margin: 0 auto;
  align-items: flex-start;
}
.content {
  flex: 1;
  display: flex;
  flex-direction: column;
}
.products.grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
  gap: 28px;
}
.products.list {
  display: flex;
  flex-direction: column;
  gap: 18px;
}
.loading, .empty {
  color: #b85c38;
  font-size: 1.2em;
  text-align: center;
  margin: 48px 0;
}
@media (max-width: 900px) {
  .main-layout {
    flex-direction: column;
    gap: 0;
  }
  .content {
    margin-top: 18px;
  }
}
</style> 