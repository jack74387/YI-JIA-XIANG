<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- 頁面標題 -->
      <div class="text-center mb-12">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">常見問題</h1>
        <p class="text-lg text-gray-600">找到您需要的答案</p>
      </div>

      <!-- 搜尋框 -->
      <div class="mb-8">
        <div class="relative">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="搜尋問題..."
            class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
          />
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>
        </div>
      </div>

      <!-- FAQ 分類標籤 -->
      <div class="mb-8">
        <div class="flex flex-wrap gap-2">
          <button
            v-for="category in categories"
            :key="category.id"
            @click="selectedCategory = category.id"
            :class="[
              selectedCategory === category.id
                ? 'bg-amber-600 text-white'
                : 'bg-white text-gray-700 hover:bg-gray-50',
              'px-4 py-2 rounded-full text-sm font-medium border border-gray-300 transition-colors duration-200'
            ]"
          >
            {{ category.name }}
          </button>
        </div>
      </div>

      <!-- FAQ 列表 -->
      <div class="space-y-4">
        <div
          v-for="faq in filteredFAQs"
          :key="faq.id"
          class="bg-white rounded-lg shadow-sm border border-gray-200"
        >
          <button
            @click="toggleFAQ(faq.id)"
            class="w-full px-6 py-4 text-left focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-inset"
          >
            <div class="flex items-center justify-between">
              <h3 class="text-lg font-medium text-gray-900">{{ faq.question }}</h3>
              <svg
                :class="[
                  openFAQs.includes(faq.id) ? 'rotate-180' : '',
                  'h-5 w-5 text-gray-500 transition-transform duration-200'
                ]"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </div>
          </button>
          
          <div
            v-show="openFAQs.includes(faq.id)"
            class="px-6 pb-4"
          >
            <div class="prose max-w-none">
              <p class="text-gray-600 leading-relaxed">{{ faq.answer }}</p>
              <div v-if="faq.additionalInfo" class="mt-4 p-4 bg-amber-50 rounded-lg">
                <h4 class="text-sm font-medium text-amber-800 mb-2">補充資訊：</h4>
                <p class="text-sm text-amber-700">{{ faq.additionalInfo }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- 沒有找到結果 -->
      <div v-if="filteredFAQs.length === 0" class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.47-.881-6.08-2.33" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">沒有找到相關問題</h3>
        <p class="mt-1 text-sm text-gray-500">請嘗試使用不同的關鍵字搜尋</p>
      </div>

      <!-- 聯絡我們 -->
      <div class="mt-12 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="text-center">
          <h3 class="text-lg font-medium text-gray-900 mb-2">還有其他問題？</h3>
          <p class="text-gray-600 mb-4">如果這裡沒有您要找的答案，歡迎聯絡我們的客服團隊</p>
          <div class="flex justify-center space-x-4">
            <router-link
              to="/contact"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-amber-600 hover:bg-amber-700"
            >
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
              </svg>
              聯絡客服
            </router-link>
            <a
              href="tel:089357996"
              class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
            >
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
              </svg>
              撥打電話
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'

const searchQuery = ref('')
const selectedCategory = ref('all')
const openFAQs = ref<number[]>([])

// FAQ 分類
const categories = ref([
  { id: 'all', name: '全部' },
  { id: 'shipping', name: '配送相關' },
  { id: 'payment', name: '付款相關' },
  { id: 'product', name: '商品相關' },
  { id: 'return', name: '退換貨' },
  { id: 'account', name: '帳戶相關' }
])

// FAQ 資料
const faqs = ref([
  {
    id: 1,
    category: 'shipping',
    question: '訂單多久會出貨？',
    answer: '我們會在收到訂單後1-2個工作天內出貨。如遇假日或特殊情況，可能會延遲1-2天。',
    additionalInfo: '出貨後會發送簡訊通知，您也可以透過訂單查詢功能追蹤包裹狀態。'
  },
  {
    id: 2,
    category: 'shipping',
    question: '運費如何計算？',
    answer: '單筆訂單滿2000元免運費，未滿2000元收取運費150元。',
    additionalInfo: '離島地區運費另計，詳情請參考配送說明。'
  },
  {
    id: 3,
    category: 'payment',
    question: '支援哪些付款方式？',
    answer: '我們支援信用卡、銀行轉帳、貨到付款等多種付款方式。',
    // additionalInfo: '信用卡付款支援分期付款，最多可分12期。'
  },
  {
    id: 4,
    category: 'product',
    question: '商品有保存期限嗎？',
    answer: '我們的肉乾產品在未開封的情況下，常溫可保存6個月，開封後請冷藏保存並在7天內食用完畢。',
    additionalInfo: '請注意包裝上的保存期限標示。'
  },
  {
    id: 5,
    category: 'return',
    question: '可以退換貨嗎？',
    answer: '商品到貨後7天內，如發現商品有瑕疵或不符合訂單內容，可以申請退換貨。',
    additionalInfo: '退換貨請保持商品原包裝完整，並附上發票或訂單明細。'
  },
  {
    id: 6,
    category: 'account',
    question: '如何修改會員資料？',
    answer: '登入後前往「個人資料」頁面，即可修改姓名、電話等基本資料。',
    additionalInfo: '電子郵件地址無法修改，如需更改請聯絡客服。'
  },
  {
    id: 7,
    category: 'account',
    question: '忘記密碼怎麼辦？',
    answer: '在登入頁面點擊「忘記密碼」，輸入您的電子郵件地址，我們會發送重設密碼連結給您。',
    additionalInfo: '重設密碼連結有效期限為24小時。'
  },
  {
    id: 8,
    category: 'product',
    question: '商品都是現貨嗎？',
    answer: '大部分商品都是現貨供應，但部分商品可能需要預訂。',
    additionalInfo: '商品頁面會標示庫存狀態，缺貨商品會顯示預計到貨時間。'
  }
])

// 篩選 FAQ
const filteredFAQs = computed(() => {
  let filtered = faqs.value

  // 按分類篩選
  if (selectedCategory.value !== 'all') {
    filtered = filtered.filter(faq => faq.category === selectedCategory.value)
  }

  // 按搜尋關鍵字篩選
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(faq => 
      faq.question.toLowerCase().includes(query) ||
      faq.answer.toLowerCase().includes(query)
    )
  }

  return filtered
})

// 切換 FAQ 展開/收合
const toggleFAQ = (id: number) => {
  const index = openFAQs.value.indexOf(id)
  if (index > -1) {
    openFAQs.value.splice(index, 1)
  } else {
    openFAQs.value.push(id)
  }
}
</script>

<style scoped>
/* 使用 Tailwind CSS */
@media (max-width: 600px) {
  .max-w-4xl, .px-4, .sm\:px-6, .lg\:px-8 {
    max-width: 100% !important;
    padding-left: 0.2rem !important;
    padding-right: 0.2rem !important;
  }
  .text-3xl, .text-lg {
    font-size: 1.1rem !important;
  }
  .mb-12, .mb-8 {
    margin-bottom: 0.5rem !important;
  }
  .rounded-lg, .shadow-sm, .border {
    border-radius: 8px !important;
    box-shadow: none !important;
  }
  .px-6, .py-4, .p-6 {
    padding: 0.5rem !important;
  }
}
</style> 