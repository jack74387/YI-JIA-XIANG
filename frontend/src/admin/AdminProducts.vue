<template>
  <div class="flex min-h-screen">
    <AdminSidebar />
    <main class="flex-1 p-10 bg-gray-50">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <h1 class="text-2xl font-bold">商品管理</h1>
        <div class="flex gap-2 items-center">
          <input v-model="search" @keyup.enter="fetchProducts(1)" type="text" placeholder="搜尋商品名稱..." class="input-sm" />
          <button class="btn-admin-sm" @click="fetchProducts(1)">搜尋</button>
          <button class="btn-admin-sm" @click="openAddModal">新增商品</button>
        </div>
      </div>
      <div class="bg-white rounded shadow p-6">
        <table class="w-full text-left">
          <thead>
            <tr>
              <th class="py-2">名稱</th>
              <th class="py-2">分類</th>
              <th class="py-2">價格</th>
              <th class="py-2">狀態</th>
              <th class="py-2">操作</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="product in products" :key="product.id">
              <td class="py-2">{{ product.name }}</td>
              <td class="py-2">{{ product.category?.name || '-' }}</td>
              <td class="py-2">NT${{ product.price_large || product.price || '-' }}</td>
              <td class="py-2">
                <span :class="product.active ? 'text-green-600' : 'text-gray-400'">
                  {{ product.active ? '上架' : '下架' }}
                </span>
              </td>
              <td class="py-2">
                <button class="text-blue-600 hover:underline mr-2" @click="openEditModal(product)">編輯</button>
                <button class="text-red-600 hover:underline" @click="deleteProduct(product.id)">刪除</button>
              </td>
            </tr>
          </tbody>
        </table>
        <div v-if="products.length === 0" class="text-center text-gray-400 py-8">尚無商品</div>
        <!-- 分頁按鈕 -->
        <div v-if="pagination.total > pagination.per_page" class="flex justify-center mt-6 gap-2">
          <button
            v-for="page in totalPages"
            :key="page"
            @click="fetchProducts(page)"
            :class="['px-3 py-1 rounded', page === pagination.current_page ? 'bg-amber-600 text-white' : 'bg-gray-100 hover:bg-amber-100']"
          >
            {{ page }}
          </button>
        </div>
      </div>

      <!-- 新增/編輯商品 Modal -->
      <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md relative max-h-[90vh] overflow-y-auto">
          <h2 class="text-lg font-bold mb-3">{{ editingProduct ? '編輯商品' : '新增商品' }}</h2>
          <form @submit.prevent="submitProduct">
            <div class="mb-3">
              <label class="block mb-1 text-sm">名稱</label>
              <input v-model="form.name" type="text" class="input-sm" required />
            </div>
            <div class="mb-3">
              <label class="block mb-1 text-sm">分類</label>
              <select v-model="form.category_id" class="input-sm" required>
                <option value="" disabled>請選擇分類</option>
                <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
              </select>
            </div>
            <div class="grid grid-cols-2 gap-3 mb-3">
              <div>
                <label class="block mb-1 text-sm">大包價格</label>
                <input v-model="form.price_large" type="number" class="input-sm" required />
              </div>
              <div>
                <label class="block mb-1 text-sm">小包價格</label>
                <input v-model="form.price_small" type="number" class="input-sm" required />
              </div>
            </div>
            <div class="mb-3">
              <label class="block mb-1 text-sm">描述</label>
              <textarea v-model="form.description" class="input-sm" rows="2"></textarea>
            </div>
            <div class="mb-3">
              <label class="block mb-1 text-sm">主圖網址</label>
              <input v-model="form.image" type="text" class="input-sm" />
            </div>
            <div class="flex justify-end gap-2 mt-4">
              <button type="button" class="btn-cancel-sm" @click="closeModal">取消</button>
              <button type="submit" class="btn-admin-sm">{{ editingProduct ? '儲存變更' : '新增' }}</button>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import AdminSidebar from './AdminSidebar.vue'

const products = ref<any[]>([])
const pagination = ref({ current_page: 1, last_page: 1, total: 0, per_page: 12 })
const totalPages = computed(() => pagination.value.last_page)
const search = ref('')
const showModal = ref(false)
const editingProduct = ref<any>(null)
const form = ref({ name: '', category_id: '', price_large: '', price_small: '', description: '', image: '' })

// 新增：分類列表
const categories = ref<any[]>([])
const fetchCategories = async () => {
  const res = await axios.get('http://127.0.0.1:8000/api/v1/categories')
  categories.value = res.data.data || []
}

const fetchProducts = async (page = 1) => {
  let url = `http://127.0.0.1:8000/api/v1/products?page=${page}`
  if (search.value) url += `&search=${encodeURIComponent(search.value)}`
  const res = await axios.get(url)
  const pageData = res.data.data
  products.value = pageData.data || []
  pagination.value = {
    current_page: pageData.current_page,
    last_page: pageData.last_page,
    total: pageData.total,
    per_page: pageData.per_page
  }
}

const openAddModal = () => {
  editingProduct.value = null
  form.value = { name: '', category_id: '', price_large: '', price_small: '', description: '', image: '' }
  showModal.value = true
}

const openEditModal = (product: any) => {
  editingProduct.value = product
  form.value = {
    name: product.name,
    category_id: product.category_id,
    price_large: product.price_large,
    price_small: product.price_small,
    description: product.description,
    image: product.image
  }
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
}

const submitProduct = async () => {
  if (!form.value.name || !form.value.category_id || !form.value.price_large || !form.value.price_small) {
    alert('請填寫完整資料')
    return
  }
  if (editingProduct.value) {
    // 編輯
    await axios.put(`http://127.0.0.1:8000/api/v1/products/${editingProduct.value.id}`, form.value)
    alert('商品已更新')
  } else {
    // 新增
    await axios.post('http://127.0.0.1:8000/api/v1/products', form.value)
    alert('商品已新增')
  }
  showModal.value = false
  await fetchProducts(pagination.value.current_page)
}

const deleteProduct = async (id: number) => {
  if (!confirm('確定要刪除這個商品嗎？')) return
  await axios.delete(`http://127.0.0.1:8000/api/v1/products/${id}`)
  alert('商品已刪除')
  await fetchProducts(pagination.value.current_page)
}

onMounted(() => {
  fetchProducts(1)
  fetchCategories()
})
</script>

<style scoped>
.input {
  @apply w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-amber-500 mb-2;
}
.input-sm {
  @apply w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-amber-500;
}
.btn-admin {
  @apply bg-amber-600 text-white font-semibold py-2 px-6 rounded hover:bg-amber-700 transition-colors;
}
.btn-admin-sm {
  @apply bg-amber-600 text-white font-semibold py-1 px-3 text-sm rounded-md shadow hover:bg-amber-700 transition-colors whitespace-nowrap;
}
.btn-cancel {
  @apply bg-gray-300 text-gray-700 font-semibold py-2 px-6 rounded hover:bg-gray-400 transition-colors;
}
.btn-cancel-sm {
  @apply bg-gray-300 text-gray-700 font-semibold py-1 px-4 text-sm rounded hover:bg-gray-400 transition-colors;
}
</style> 