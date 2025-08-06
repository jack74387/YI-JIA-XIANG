<template>
  <div class="flex min-h-screen">
    <AdminSidebar />
    <main class="flex-1 p-10 bg-gray-50">
      <!-- 頁面標題 -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">商品管理</h1>
        <p class="mt-2 text-gray-600">管理所有商品資訊和狀態</p>
      </div>

      <!-- 載入狀態 -->
      <div v-if="loading" class="flex justify-center items-center py-20">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-amber-600"></div>
      </div>

      <!-- 商品管理內容 -->
      <div v-else>
        <!-- 搜尋和篩選 -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">搜尋商品</label>
              <input 
                v-model="search" 
                type="text" 
                placeholder="輸入商品名稱或描述"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500"
                @input="debounceSearch"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">狀態篩選</label>
              <select 
                v-model="statusFilter" 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500"
                @change="fetchProducts"
              >
                <option value="">全部狀態</option>
                <option value="draft">草稿</option>
                <option value="published">上架</option>
                <option value="notification">通知</option>
                <option value="archived">封存</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">分類篩選</label>
              <select 
                v-model="categoryFilter" 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500"
                @change="fetchProducts"
              >
                <option value="">全部分類</option>
                <option v-for="category in categories" :key="category.id" :value="category.id">
                  {{ category.name }}
                </option>
              </select>
            </div>
            <div class="flex items-end space-x-2">
              <button 
                @click="fetchProducts" 
                class="btn-admin flex-1"
              >
                搜尋
              </button>
              <button 
                @click="exportProducts" 
                class="btn-secondary"
                :disabled="exportLoading"
              >
                {{ exportLoading ? '匯出中...' : '匯出' }}
              </button>
            </div>
          </div>
        </div>

        <!-- 新增商品按鈕 -->
        <div class="mb-6">
          <button 
            @click="showCreateModal = true" 
            class="btn-admin"
          >
            新增商品
          </button>
        </div>

        <!-- 商品列表 -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-72">產品資訊</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">分類</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-40">價格</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">狀態</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">精選</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">建立時間</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">操作</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="product in products" :key="product.id">
                  <td class="px-6 py-4 whitespace-nowrap w-72">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-10 w-10">
                        <img 
                          :src="getImageUrl(product.primary_image?.image_path || (Array.isArray(product.images) && product.images[0]?.image_path) || product.image) || '/images/product-placeholder.jpg'" 
                          :alt="product.name"
                          class="h-10 w-10 rounded-lg object-cover"
                        />
                      </div>
                      <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">{{ product.name }}</div>
                        <div class="text-sm text-gray-500">{{ product.description?.substring(0, 50) }}...</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 w-32">
                    {{ product.category?.name || '未分類' }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 w-40">
                    <div v-if="product.price_large">
                      <div>大包裝: NT$ {{ formatNumber(product.price_large) }}</div>
                      <div v-if="product.price_small">小包裝: NT$ {{ formatNumber(product.price_small) }}</div>
                    </div>
                    <div v-else class="text-gray-500">未設定價格</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap w-24">
                    <span 
                      class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                      :class="getStatusClass(product.status)"
                    >
                      {{ getStatusText(product.status) }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap w-24">
                    <div class="flex items-center space-x-2">
                      <button 
                        @click="toggleFeatured(product)"
                        :class="product.is_featured ? 'text-yellow-600 hover:text-yellow-800' : 'text-gray-400 hover:text-gray-600'"
                        class="text-lg"
                        :title="product.is_featured ? '取消精選' : '設為精選'"
                      >
                        {{ product.is_featured ? '⭐' : '☆' }}
                      </button>
                      <span v-if="product.is_featured && product.featured_order" class="text-xs text-gray-500">
                        #{{ product.featured_order }}
                      </span>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 w-32">
                    {{ formatDate(product.created_at) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium w-24">
                    <div class="flex space-x-2">
                      <button 
                        @click="editProduct(product)" 
                        class="text-amber-600 hover:text-amber-900"
                      >
                        編輯
                      </button>
                      <button 
                        @click="deleteProduct(product.id)" 
                        class="text-red-600 hover:text-red-900"
                      >
                        刪除
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- 分頁 -->
          <div v-if="pagination" class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            <div class="flex items-center justify-between">
              <div class="flex-1 flex justify-between sm:hidden">
                <button 
                  @click="changePage(pagination.current_page - 1)"
                  :disabled="pagination.current_page <= 1"
                  class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  上一頁
                </button>
                <button 
                  @click="changePage(pagination.current_page + 1)"
                  :disabled="pagination.current_page >= pagination.last_page"
                  class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  下一頁
                </button>
              </div>
              <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                  <p class="text-sm text-gray-700">
                    顯示第 <span class="font-medium">{{ pagination.from }}</span> 到 
                    <span class="font-medium">{{ pagination.to }}</span> 筆，共 
                    <span class="font-medium">{{ pagination.total }}</span> 筆資料
                  </p>
                </div>
                <div>
                  <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                                    <button 
                  v-for="page in pagination.links" 
                  :key="page.label"
                  @click="handlePageClick(page)"
                  :disabled="!page.url || page.active"
                  class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                  :class="page.active ? 'z-10 bg-amber-50 border-amber-500 text-amber-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50 disabled:cursor-not-allowed'"
                  v-html="page.label"
                ></button>
                  </nav>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- 新增/編輯商品 Modal -->
      <div v-if="showCreateModal || showEditModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" @click.self="closeModal">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
          <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
              {{ showEditModal ? '編輯商品' : '新增商品' }}
            </h3>
            
            <form @submit.prevent="showEditModal ? updateProduct() : createProduct()">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">商品名稱 *</label>
                  <input 
                    v-model="form.name" 
                    type="text" 
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500"
                  />
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">分類 *</label>
                  <select 
                    v-model="form.category_id" 
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500"
                  >
                    <option value="">選擇分類</option>
                    <option v-for="category in categories" :key="category.id" :value="category.id">
                      {{ category.name }}
                    </option>
                  </select>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">狀態 *</label>
                  <select 
                    v-model="form.status" 
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500"
                  >
                    <option value="draft">草稿</option>
                    <option value="published">上架</option>
                    <option value="notification">通知</option>
                    <option value="archived">封存</option>
                  </select>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">大包裝價格</label>
                  <input 
                    v-model="form.price_large" 
                    type="number" 
                    min="0"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500"
                  />
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">小包裝價格</label>
                  <input 
                    v-model="form.price_small" 
                    type="number" 
                    min="0"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500"
                  />
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">單位</label>
                  <input 
                    v-model="form.unit" 
                    type="text" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500"
                  />
                </div>

                <!-- 精選商品設置 -->
                <div class="md:col-span-2">
                  <div class="flex items-start space-x-6 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                    <div class="flex items-center">
                      <input 
                        id="is_featured"
                        v-model="form.is_featured" 
                        type="checkbox" 
                        class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300 rounded"
                      />
                      <label for="is_featured" class="ml-2 block text-sm font-medium text-gray-700">
                        設為精選商品
                      </label>
                    </div>
                    <div v-if="form.is_featured" class="flex-1">
                      <label class="block text-sm font-medium text-gray-700 mb-2">精選排序</label>
                      <input 
                        v-model="form.featured_order" 
                        type="number" 
                        min="0"
                        placeholder="排序數字（越小越前面）"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500"
                      />
                      <p class="text-xs text-gray-600 mt-1">設定精選商品的顯示順序，數字越小排序越前面</p>
                    </div>
                  </div>
                </div>

                <div class="md:col-span-2">
                  <label class="block text-sm font-medium text-gray-700 mb-2">商品主圖</label>
                  <div class="space-y-4">
                    <!-- 圖片預覽 -->
                    <div v-if="imagePreview || form.image" class="flex items-center space-x-4">
                      <img 
                        :src="imagePreview || getImageUrl(form.image)" 
                        alt="商品圖片預覽"
                        class="w-32 h-32 object-cover rounded-lg border border-gray-300"
                      />
                      <button 
                        type="button"
                        @click="removeImage"
                        class="text-red-600 hover:text-red-800 text-sm"
                      >
                        移除圖片
                      </button>
                    </div>
                    
                    <!-- 檔案上傳 -->
                    <div class="flex items-center space-x-4">
                      <input 
                        ref="fileInput"
                        type="file" 
                        accept="image/*"
                        @change="handleImageUpload"
                        class="hidden"
                      />
                      <button 
                        type="button"
                        @click="$refs.fileInput.click()"
                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-amber-500"
                      >
                        選擇圖片
                      </button>
                      <span class="text-sm text-gray-500">
                        支援 JPG、PNG、GIF 格式，建議尺寸 800x800 像素
                      </span>
                    </div>
                    
                    <!-- 上傳進度 -->
                    <div v-if="uploadProgress > 0 && uploadProgress < 100" class="space-y-2">
                      <div class="bg-gray-200 rounded-full h-2">
                        <div 
                          class="bg-amber-600 rounded-full transition-all duration-300"
                          :style="{ width: uploadProgress + '%' }"
                        ></div>
                      </div>
                      <p class="text-sm text-gray-600">上傳中... {{ uploadProgress }}%</p>
                    </div>
                  </div>
                </div>

                <div class="md:col-span-2">
                  <label class="block text-sm font-medium text-gray-700 mb-2">商品額外圖片（最多10張）</label>
                  <div class="flex flex-wrap gap-4 mb-2">
                    <div v-for="(img, idx) in form.images" :key="img" class="relative">
                      <img :src="getImageUrl(img)" class="w-24 h-24 object-cover rounded border" />
                      <button type="button" class="absolute top-0 right-0 bg-white bg-opacity-80 rounded-full p-1" @click="removeExtraImage(idx)">✕</button>
                    </div>
                    <button
                      v-if="form.images.length < 10"
                      type="button"
                      class="w-24 h-24 flex items-center justify-center border rounded text-gray-400 hover:bg-gray-100"
                      @click="$refs.extraFileInput.click()"
                    >＋</button>
                    <input ref="extraFileInput" type="file" accept="image/*" multiple class="hidden" @change="handleExtraImagesUpload" />
                  </div>
                  <span class="text-sm text-gray-500">支援 JPG、PNG、GIF 格式，建議尺寸 800x800 像素</span>
                </div>

                <div class="md:col-span-2">
                  <label class="block text-sm font-medium text-gray-700 mb-2">規格</label>
                  <input 
                    v-model="form.specs" 
                    type="text" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500"
                  />
                </div>

                <div class="md:col-span-2">
                  <label class="block text-sm font-medium text-gray-700 mb-2">商品描述</label>
                  <textarea 
                    v-model="form.description" 
                    rows="4"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500"
                  ></textarea>
                </div>

                <!-- 營養資訊區域 -->
                <div class="md:col-span-2">
                  <label class="block text-sm font-medium text-gray-700 mb-2">營養資訊</label>
                  <div class="bg-gray-50 p-4 rounded-md space-y-3">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                      <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">熱量</label>
                        <input 
                          v-model="form.nutrition_info.calories" 
                          type="text" 
                          placeholder="320大卡"
                          class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-amber-500"
                        />
                      </div>
                      <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">蛋白質</label>
                        <input 
                          v-model="form.nutrition_info.protein" 
                          type="text" 
                          placeholder="25公克"
                          class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-amber-500"
                        />
                      </div>
                      <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">脂肪</label>
                        <input 
                          v-model="form.nutrition_info.fat" 
                          type="text" 
                          placeholder="18公克"
                          class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-amber-500"
                        />
                      </div>
                      <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">碳水化合物</label>
                        <input 
                          v-model="form.nutrition_info.carbohydrates" 
                          type="text" 
                          placeholder="12公克"
                          class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-amber-500"
                        />
                      </div>
                      <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">鈉</label>
                        <input 
                          v-model="form.nutrition_info.sodium" 
                          type="text" 
                          placeholder="850毫克"
                          class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-amber-500"
                        />
                      </div>
                      <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">糖</label>
                        <input 
                          v-model="form.nutrition_info.sugar" 
                          type="text" 
                          placeholder="5公克"
                          class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-amber-500"
                        />
                      </div>
                    </div>
                    <p class="text-xs text-gray-500">營養成分以每100公克計算</p>
                  </div>
                </div>

                <!-- 成分資訊區域 -->
                <div class="md:col-span-2">
                  <label class="block text-sm font-medium text-gray-700 mb-2">成分資訊</label>
                  <div class="space-y-3">
                    <div>
                      <label class="block text-xs font-medium text-gray-600 mb-1">主要成分</label>
                      <input 
                        v-model="form.ingredients" 
                        type="text" 
                        placeholder="豬肉、糖、鹽、醬油、香料"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500"
                      />
                    </div>
                    <div>
                      <label class="block text-xs font-medium text-gray-600 mb-1">過敏原資訊</label>
                      <input 
                        v-model="form.allergens" 
                        type="text" 
                        placeholder="本產品含有大豆製品"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500"
                      />
                    </div>
                    <div>
                      <label class="block text-xs font-medium text-gray-600 mb-1">產地</label>
                      <input 
                        v-model="form.origin" 
                        type="text" 
                        placeholder="台灣"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500"
                      />
                    </div>
                  </div>
                </div>

                <!-- 保存資訊區域 -->
                <div class="md:col-span-2">
                  <label class="block text-sm font-medium text-gray-700 mb-2">保存資訊</label>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                      <label class="block text-xs font-medium text-gray-600 mb-1">保存期限</label>
                      <input 
                        v-model="form.shelf_life" 
                        type="text" 
                        placeholder="60天（未開封）"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500"
                      />
                    </div>
                    <div>
                      <label class="block text-xs font-medium text-gray-600 mb-1">保存方式</label>
                      <input 
                        v-model="form.storage_instructions" 
                        type="text" 
                        placeholder="常溫保存，開封後請冷藏"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500"
                      />
                    </div>
                  </div>
                </div>
              </div>

              <div class="flex justify-end space-x-3 mt-6">
                <button 
                  type="button" 
                  @click="closeModal"
                  class="btn-secondary"
                >
                  取消
                </button>
                <button 
                  type="submit" 
                  class="btn-admin"
                  :disabled="formLoading"
                >
                  {{ formLoading ? '處理中...' : (showEditModal ? '更新' : '新增') }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AdminSidebar from './AdminSidebar.vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import { useAdminAuthStore } from '@/stores/adminAuth'

const adminAuth = useAdminAuthStore()
const router = useRouter()

// 資料狀態
const products = ref([])
const categories = ref([])
const loading = ref(true)
const formLoading = ref(false)
const exportLoading = ref(false)

// 搜尋和篩選
const search = ref('')
const statusFilter = ref('')
const categoryFilter = ref('')

// 分頁
const pagination = ref(null)

// Modal 狀態
const showCreateModal = ref(false)
const showEditModal = ref(false)
const editingProduct = ref(null)

// 表單資料
const form = ref({
  name: '',
  category_id: '',
  description: '',
  image: '',
  image_public_id: '', // 新增：保存主圖的public_id
  price_large: '',
  price_small: '',
  unit: '',
  specs: '',
  status: 'draft',
  stock: 0, // 預設為 0
  images: [], // 額外圖片陣列
  images_public_ids: [], // 新增：保存額外圖片的public_id陣列
  // 新增的營養和成分資訊欄位
  nutrition_info: {
    calories: '',
    protein: '',
    fat: '',
    carbohydrates: '',
    sodium: '',
    sugar: ''
  },
  ingredients: '',
  allergens: '',
  shelf_life: '',
  storage_instructions: '',
  origin: '',
  // 精選商品相關欄位
  is_featured: false,
  featured_order: null
})

// 圖片上傳相關
const imagePreview = ref('')
const uploadProgress = ref(0)
const selectedFile = ref(null)

// 額外圖片上傳相關
const extraFileInput = ref(null)

// 追蹤新增模式下上傳的圖片，以便取消時刪除
const pendingImages = ref([])

// 刪除Cloudinary上的圖片
async function deleteImageFromCloudinary(imageUrl) {
  try {
    console.log('嘗試刪除Cloudinary圖片:', imageUrl);
    const response = await axios.post('/api/v1/admin/delete-cloudinary-image', {
      image_url: imageUrl
    });
    
    console.log('刪除API響應:', response.data);
    
    if (!response.data.success) {
      throw new Error(response.data.message || '刪除失敗');
    }
    
    console.log('Cloudinary圖片刪除成功');
  } catch (error) {
    console.error('刪除Cloudinary圖片失敗:', error);
    console.error('錯誤詳情:', error.response?.data);
    throw error;
  }
}

// 通過public_id刪除Cloudinary圖片（更可靠的方法）
async function deleteImageByPublicId(publicId) {
  try {
    console.log('嘗試通過public_id刪除Cloudinary圖片:', publicId);
    const response = await axios.post('/api/v1/admin/delete-cloudinary-by-id', {
      public_id: publicId
    });
    
    console.log('刪除API響應:', response.data);
    
    if (!response.data.success) {
      throw new Error(response.data.message || '刪除失敗');
    }
    
    console.log('Cloudinary圖片刪除成功');
    return response.data;
  } catch (error) {
    console.error('通過public_id刪除Cloudinary圖片失敗:', error);
    console.error('錯誤詳情:', error.response?.data);
    throw error;
  }
}

async function handleImageUpload(e) {
  const file = e.target.files[0];
  if (!file) return;

  uploadProgress.value = 0;
  
  try {
    // 使用後端的上傳 API
    const formData = new FormData();
    formData.append('image', file);

    const res = await axios.post('/api/v1/admin/upload-image', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      },
      onUploadProgress: (progressEvent) => {
        if (progressEvent.total) {
          uploadProgress.value = Math.round((progressEvent.loaded * 100) / progressEvent.total);
        }
      }
    });

    if (res.data.success && res.data.url) {
      form.value.image = res.data.url;
      form.value.image_public_id = res.data.public_id; // 保存public_id
      imagePreview.value = res.data.url;
      uploadProgress.value = 100;
      
      console.log('主圖上傳成功:', {
        url: res.data.url,
        public_id: res.data.public_id
      });
      
      // 在新增模式下，追蹤上傳的圖片
      if (!editingProduct.value) {
        pendingImages.value.push({
          url: res.data.url,
          public_id: res.data.public_id,
          type: 'main'
        });
      }
      
      // 3秒後隱藏進度條
      setTimeout(() => {
        uploadProgress.value = 0;
      }, 3000);
    } else {
      throw new Error(res.data.message || '上傳失敗');
    }
  } catch (error) {
    console.error('圖片上傳失敗:', error);
    alert('圖片上傳失敗: ' + (error.response?.data?.message || error.message || '網路錯誤'));
    uploadProgress.value = 0;
  }
}

async function handleExtraImagesUpload(e) {
  const files = Array.from(e.target.files);
  if (!files.length) return;

  for (const file of files) {
    if (form.value.images.length >= 10) break;

    try {
      const formData = new FormData();
      formData.append('image', file);

      const res = await axios.post('/api/v1/admin/upload-image', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      });

      if (res.data.success && res.data.url) {
        // 確保 images_public_ids 陣列存在
        if (!Array.isArray(form.value.images_public_ids)) {
          form.value.images_public_ids = [];
        }
        
        form.value.images.push(res.data.url);
        form.value.images_public_ids.push(res.data.public_id); // 保存public_id
        
        console.log('額外圖片上傳成功:', {
          url: res.data.url,
          public_id: res.data.public_id,
          total_images: form.value.images.length,
          total_public_ids: form.value.images_public_ids.length
        });
        
        // 在新增模式下，追蹤上傳的圖片
        if (!editingProduct.value) {
          pendingImages.value.push({
            url: res.data.url,
            public_id: res.data.public_id,
            type: 'extra'
          });
        }
      } else {
        throw new Error(res.data.message || '上傳失敗');
      }
    } catch (error) {
      console.error('額外圖片上傳失敗:', error);
      alert(`圖片 ${file.name} 上傳失敗: ` + (error.response?.data?.message || error.message || '網路錯誤'));
    }
  }
  e.target.value = ''; // 清空 input
}

async function removeExtraImage(idx) {
  // 如果是編輯模式且商品已保存，調用 API 刪除
  if (editingProduct.value && editingProduct.value.id && form.value.images[idx]) {
    try {
      const imageToDelete = form.value.images[idx]
      const response = await axios.delete(`/api/v1/admin/products/${editingProduct.value.id}/image`, {
        data: {
          image: imageToDelete,
          type: 'extra'
        }
      })
      
      if (response.data.success) {
        form.value.images.splice(idx, 1)
        // 同時移除對應的public_id
        if (form.value.images_public_ids[idx]) {
          form.value.images_public_ids.splice(idx, 1)
        }
        alert('圖片刪除成功')
      } else {
        alert('圖片刪除失敗：' + response.data.message)
      }
    } catch (error) {
      console.error('刪除圖片失敗:', error)
      alert('圖片刪除失敗，請稍後再試')
    }
  } else {
    // 新增模式，使用public_id刪除Cloudinary上的圖片
    const publicIdToDelete = form.value.images_public_ids[idx];
    if (publicIdToDelete) {
      try {
        console.log('準備刪除額外圖片，public_id:', publicIdToDelete);
        // 調用新的刪除圖片API
        await deleteImageByPublicId(publicIdToDelete);
        
        // 從待刪除列表中移除
        pendingImages.value = pendingImages.value.filter(img => img.public_id !== publicIdToDelete);
        
        console.log('額外圖片刪除成功');
      } catch (error) {
        console.error('刪除Cloudinary圖片失敗:', error);
        // 即使刪除失敗也繼續，但提醒用戶
        alert('刪除圖片時發生錯誤：' + (error.message || '未知錯誤'));
      }
    }
    
    // 從陣列移除
    form.value.images.splice(idx, 1);
    form.value.images_public_ids.splice(idx, 1);
  }
}

async function removeImage() {
  // 如果是編輯模式且商品已保存，調用 API 刪除
  if (editingProduct.value && editingProduct.value.id && form.value.image) {
    try {
      const imageToDelete = form.value.image
      const response = await axios.delete(`/api/v1/admin/products/${editingProduct.value.id}/image`, {
        data: {
          image: imageToDelete,
          type: 'main'
        }
      })
      
      if (response.data.success) {
        form.value.image = ''
        form.value.image_public_id = ''
        imagePreview.value = ''
        selectedFile.value = null
        alert('主要圖片刪除成功')
      } else {
        alert('圖片刪除失敗：' + response.data.message)
      }
    } catch (error) {
      console.error('刪除主要圖片失敗:', error)
      alert('圖片刪除失敗，請稍後再試')
    }
  } else {
    // 新增模式，使用public_id刪除Cloudinary上的圖片
    if (form.value.image_public_id) {
      try {
        console.log('準備刪除主圖，public_id:', form.value.image_public_id);
        // 調用新的刪除圖片API
        await deleteImageByPublicId(form.value.image_public_id);
        
        // 從待刪除列表中移除
        pendingImages.value = pendingImages.value.filter(img => img.public_id !== form.value.image_public_id);
        
        console.log('主圖刪除成功');
      } catch (error) {
        console.error('刪除Cloudinary圖片失敗:', error);
        // 即使刪除失敗也繼續，但提醒用戶
        alert('刪除圖片時發生錯誤：' + (error.message || '未知錯誤'));
      }
    }
    
    // 清空
    form.value.image = ''
    form.value.image_public_id = ''
    imagePreview.value = ''
    selectedFile.value = null
  }
}

// 獲取商品列表
const fetchProducts = async (page = 1) => {
  try {
    const params = {
      page,
      search: search.value,
      status: statusFilter.value,
      category_id: categoryFilter.value
    }
    
    const response = await axios.get('/api/v1/admin/products', { params })
    
    if (response.data.success) {
      products.value = response.data.data.data
      pagination.value = response.data.data
    }
  } catch (error) {
    console.error('獲取商品列表失敗:', error)
  } finally {
    loading.value = false
  }
}

// 獲取分類列表
const fetchCategories = async () => {
  try {
    const response = await axios.get('/api/v1/categories')
    if (response.data.success) {
      categories.value = response.data.data
    }
  } catch (error) {
    console.error('獲取分類列表失敗:', error)
  }
}

// 搜尋防抖
let searchTimeout = null
const debounceSearch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    fetchProducts()
  }, 500)
}

// 分頁切換
const changePage = (page) => {
  if (page && page > 0) {
    fetchProducts(parseInt(page))
  }
}

// 處理分頁按鈕點擊
const handlePageClick = (page) => {
  if (page.url && !page.active) {
    const url = new URL(page.url)
    const pageNumber = url.searchParams.get('page')
    if (pageNumber) {
      changePage(parseInt(pageNumber))
    }
  }
}

// 新增商品
const createProduct = async () => {
  formLoading.value = true
  // 確保 stock 一定是數字
  if (!form.value.stock || isNaN(form.value.stock)) {
    form.value.stock = 0
  } else {
    form.value.stock = Number(form.value.stock)
  }
  try {
    const response = await axios.post('/api/v1/admin/products', form.value)
    
    if (response.data.success) {
      // 成功創建後清空待刪除列表
      pendingImages.value = []
      closeModal()
      fetchProducts(pagination.value?.current_page || 1)
      alert('產品新增成功')
    }
  } catch (error) {
    console.error('新增產品失敗:', error)
    alert('新增產品失敗: ' + (error.response?.data?.message || error.message))
  } finally {
    formLoading.value = false
  }
}

// 編輯商品
const editProduct = (product) => {
  editingProduct.value = product
  
  // 確保 images 和 images_public_ids 是陣列
  const productImages = Array.isArray(product.images) ? product.images : [];
  const productImagesPublicIds = Array.isArray(product.images_public_ids) ? product.images_public_ids : [];
  
  form.value = {
    name: product.name,
    category_id: product.category_id,
    description: product.description || '',
    image: product.image || '',
    image_public_id: product.image_public_id || '',
    price_large: product.price_large || '',
    price_small: product.price_small || '',
    unit: product.unit || '',
    specs: product.specs || '',
    status: product.status,
    stock: product.stock ?? 0, // 確保有庫存值
    images: productImages,
    images_public_ids: productImagesPublicIds,
    // 新增的營養和成分資訊欄位
    nutrition_info: product.nutrition_info ? {
      calories: product.nutrition_info.calories || '',
      protein: product.nutrition_info.protein || '',
      fat: product.nutrition_info.fat || '',
      carbohydrates: product.nutrition_info.carbohydrates || '',
      sodium: product.nutrition_info.sodium || '',
      sugar: product.nutrition_info.sugar || ''
    } : {
      calories: '',
      protein: '',
      fat: '',
      carbohydrates: '',
      sodium: '',
      sugar: ''
    },
    ingredients: product.ingredients || '',
    allergens: product.allergens || '',
    shelf_life: product.shelf_life || '',
    storage_instructions: product.storage_instructions || '',
    origin: product.origin || ''
  }
  
  console.log('編輯產品，初始化表單:', {
    images_count: form.value.images.length,
    public_ids_count: form.value.images_public_ids.length,
    product_id: product.id
  });
  
  showEditModal.value = true
}

// 更新商品
const updateProduct = async () => {
  formLoading.value = true
  // 確保 stock 一定是數字
  if (!form.value.stock || isNaN(form.value.stock)) {
    form.value.stock = 0
  } else {
    form.value.stock = Number(form.value.stock)
  }
  try {
    const response = await axios.put(`/api/v1/admin/products/${editingProduct.value.id}`, form.value)
    
    if (response.data.success) {
      closeModal()
      fetchProducts(pagination.value?.current_page || 1)
      alert('商品更新成功')
    }
  } catch (error) {
    console.error('更新商品失敗:', error)
    alert('更新商品失敗: ' + (error.response?.data?.message || error.message))
  } finally {
    formLoading.value = false
  }
}

// 刪除商品
const deleteProduct = async (id) => {
  if (!confirm('確定要刪除這個商品嗎？')) return
  
  try {
    const response = await axios.delete(`/api/v1/admin/products/${id}`)
    
    if (response.data.success) {
      fetchProducts(pagination.value?.current_page || 1)
      alert('商品刪除成功')
    }
  } catch (error) {
    console.error('刪除商品失敗:', error)
    alert('刪除商品失敗: ' + (error.response?.data?.message || error.message))
  }
}

// 匯出商品
const exportProducts = async () => {
  exportLoading.value = true
  try {
    const response = await axios.get('/api/v1/admin/products/export')
    
    if (response.data.success) {
      window.open(response.data.download_url, '_blank')
      alert('商品資料匯出成功')
    }
  } catch (error) {
    console.error('匯出商品失敗:', error)
    alert('匯出商品失敗: ' + (error.response?.data?.message || error.message))
  } finally {
    exportLoading.value = false
  }
}

// 關閉 Modal
const closeModal = async () => {
  // 如果是新增模式且有待刪除的圖片，先刪除它們
  if (!editingProduct.value && pendingImages.value.length > 0) {
    const shouldCleanup = confirm('您上傳了圖片但尚未保存商品，是否要刪除這些圖片？');
    if (shouldCleanup) {
      for (const image of pendingImages.value) {
        try {
          console.log('清理圖片，public_id:', image.public_id);
          await deleteImageByPublicId(image.public_id);
        } catch (error) {
          console.error('清理圖片失敗:', error);
        }
      }
    }
  }
  
  // 清空狀態
  showCreateModal.value = false
  showEditModal.value = false
  editingProduct.value = null
  pendingImages.value = []
  imagePreview.value = ''
  form.value = {
    name: '',
    category_id: '',
    description: '',
    image: '',
    image_public_id: '',
    price_large: '',
    price_small: '',
    unit: '',
    specs: '',
    status: 'active',
    stock: 0,
    images: [],
    images_public_ids: [],
    // 重置營養和成分資訊欄位
    nutrition_info: {
      calories: '',
      protein: '',
      fat: '',
      carbohydrates: '',
      sodium: '',
      sugar: ''
    },
    ingredients: '',
    allergens: '',
    shelf_life: '',
    storage_instructions: '',
    origin: ''
  }
}

// 格式化數字
const formatNumber = (num) => {
  if (num === null || num === undefined) return '0'
  return Number(num).toLocaleString()
}

// 格式化日期
const formatDate = (date) => {
  if (!date) return ''
  return new Date(date).toLocaleDateString('zh-TW')
}

// 取得狀態文字
const getStatusText = (status) => {
  const statusMap = {
    'draft': '草稿',
    'published': '上架',
    'notification': '通知',
    'archived': '封存'
  }
  return statusMap[status] || status
}

// 取得狀態樣式
const getStatusClass = (status) => {
  const classMap = {
    'draft': 'bg-gray-100 text-gray-800',
    'published': 'bg-green-100 text-green-800',
    'notification': 'bg-blue-100 text-blue-800',
    'archived': 'bg-red-100 text-red-800'
  }
  return classMap[status] || 'bg-gray-100 text-gray-800'
}

// 1. 在 <script setup> 中加入 getImageUrl 函數
import { getImageUrl } from '@/utils/imageUtils'

onMounted(async () => {
  await adminAuth.initAuth()
  if (!adminAuth.isAuthenticated) {
    router.push('/admin/login')
    return
  }
  
  await Promise.all([
    fetchProducts(),
    fetchCategories()
  ])
})
</script>

<style scoped>
.btn-admin {
  background-color: #d97706;
  color: white;
  font-weight: 600;
  padding: 0.5rem 1rem;
  border-radius: 0.375rem;
  transition: background-color 0.15s ease-in-out;
}

.btn-admin:hover {
  background-color: #b45309;
}

.btn-secondary {
  background-color: #4b5563;
  color: white;
  font-weight: 600;
  padding: 0.5rem 1rem;
  border-radius: 0.375rem;
  transition: background-color 0.15s ease-in-out;
}

.btn-secondary:hover {
  background-color: #374151;
}
</style> 