<template>
  <nav class="bg-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex items-center">
          <router-link to="/" class="flex items-center space-x-5">
            <img src="/images/logo.jpg" alt="品牌LOGO" class="h-14 w-14 rounded-full object-cover shadow-lg" />
            <div class="flex flex-col items-start justify-center leading-tight">
              <span
                class="text-[2.2rem] font-black"
                style="font-family: 'Noto Serif TC', 'Microsoft JhengHei', '微軟正黑體', serif; color: #b85c38; line-height: 1;"
              >一佳香</span>
              <span
                class="text-[1rem] font-light uppercase tracking-[0.35em] mt-1"
                style="color: #b85c38; letter-spacing: 0.35em; line-height: 1;"
              >YI JIA XIANG</span>
            </div>
          </router-link>
          <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
            <router-link
              to="/"
              class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
              active-class="border-amber-500 text-gray-900"
            >
              首頁
            </router-link>
            <router-link
              to="/products"
              class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
              active-class="border-amber-500 text-gray-900"
            >
              商品
            </router-link>
            <!-- 服務專區下拉選單 -->
            <div class="relative" @mouseenter="isServiceMenuOpen = true" @mouseleave="isServiceMenuOpen = false">
              <button
                class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium focus:outline-none"
                type="button"
              >
                服務專區
                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
              <div
                v-show="isServiceMenuOpen"
                class="absolute left-0 top-full mt-0 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50 block px-4 py-3 min-w-[220px]"
              >
                  <router-link to="/line-friend" class="service-link block w-full text-left">LINE好友專區</router-link>
                  <router-link to="/group-order" class="service-link block w-full text-left">團購/企業訂購</router-link>
                  <router-link to="/gift" class="service-link block w-full text-left">禮盒加值服務</router-link>
                  <router-link to="/food-trace" class="service-link block w-full text-left">食品履歷查詢</router-link>
                  <router-link to="/store-locator" class="service-link block w-full text-left">門市據點</router-link>
                  <div class="h-px bg-gray-200 my-2"></div>
              </div>
            </div>
            <!-- 主題專欄 -->
            <router-link
              to="/articles"
              class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
              active-class="border-amber-500 text-gray-900"
            >
              主題專欄
            </router-link>
            <!-- 其他主選單 -->
            <router-link
              to="/contact"
              class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
              active-class="border-amber-500 text-gray-900"
            >
              聯絡我們
            </router-link>
            <router-link
              to="/faq"
              class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
              active-class="border-amber-500 text-gray-900"
            >
              常見問題
            </router-link>
          </div>
        </div>
        
        <div class="hidden sm:ml-6 sm:flex sm:items-center">
          <!-- 購物車 -->
          <router-link
            to="/cart"
            class="flex items-center px-3 py-1 bg-[#f7cd81] rounded-full shadow-md hover:bg-[#f5c46a] transition-colors duration-200 group"
            style="min-width:64px; min-height:40px;"
          >
            <svg class="h-6 w-6 text-white mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            <span class="text-white text-base font-bold select-none">{{ cartStore.itemCount }}</span>
          </router-link>

          <!-- 用戶選單 -->
          <div v-if="authStore.isAuthenticated" class="ml-3 relative user-menu">
            <div>
              <button
                @click="toggleUserMenu"
                class="max-w-xs bg-white flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500"
              >
                <div class="h-8 w-8 rounded-full bg-amber-600 flex items-center justify-center text-white font-medium">
                  {{ authStore.userInitials }}
                </div>
                <span class="ml-2 text-gray-700">{{ authStore.user?.name }}</span>
                <svg class="ml-1 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
            </div>
            
            <!-- 下拉選單 -->
            <div
              v-show="isUserMenuOpen"
              class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
              @click.stop
            >
              <router-link
                to="/profile"
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                @click="closeUserMenu"
              >
                個人資料
              </router-link>
              <router-link
                to="/orders"
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                @click="closeUserMenu"
              >
                我的訂單
              </router-link>
              <router-link
                to="/points"
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                @click="closeUserMenu"
              >
                我的點數
              </router-link>
              <router-link
                to="/coupon"
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                @click="closeUserMenu"
              >
                我的優惠券
              </router-link>
              <router-link
                to="/member-center"
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                @click="closeUserMenu"
              >
                會員中心
              </router-link>
              <div class="border-t border-gray-100"></div>
              <button
                @click="handleLogout"
                class="flex items-center w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors duration-200"
              >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                登出
              </button>
            </div>
          </div>

          <!-- 登入/註冊按鈕 -->
          <div v-else class="flex space-x-4">
            <router-link
              to="/login"
              class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium"
            >
              登入
            </router-link>
            <router-link
              to="/register"
              class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-md text-sm font-medium"
            >
              註冊
            </router-link>
          </div>
        </div>

        <!-- 手機版選單按鈕，改為觸發 Sidebar.vue -->
        <Sidebar />
      </div>
    </div>

    <!-- 登出確認對話框 -->
    <LogoutConfirmDialog 
      :show="showLogoutDialog" 
      @close="showLogoutDialog = false" 
    />
  </nav>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useCartStore } from '@/stores/cart'
import LogoutConfirmDialog from './LogoutConfirmDialog.vue'
// 1. 匯入 Sidebar.vue
import Sidebar from './Sidebar.vue'

const router = useRouter()
const authStore = useAuthStore()
const cartStore = useCartStore()

const isUserMenuOpen = ref(false)
const isServiceMenuOpen = ref(false)
const showLogoutDialog = ref(false)

const toggleUserMenu = () => {
  isUserMenuOpen.value = !isUserMenuOpen.value
}

const closeUserMenu = () => {
  isUserMenuOpen.value = false
}

const handleLogout = () => {
  // 顯示登出確認對話框
  showLogoutDialog.value = true
  isUserMenuOpen.value = false
}

// 處理點擊外部關閉用戶選單
const handleClickOutside = (event: Event) => {
  const userMenu = document.querySelector('.user-menu')
  if (userMenu && !userMenu.contains(event.target as Node)) {
    isUserMenuOpen.value = false
  }
}

// 在組件掛載時添加事件監聽器
onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

// 在組件卸載時移除事件監聽器
onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})
</script>

<style scoped>
/* 讓 logo 與品牌文字更貼近，品牌文字更像圖片 */
.service-link {
  color: #7c5a1a;
  font-size: 1rem;
  font-weight: 600;
  border-radius: 0.8em;
  padding: 0.7em 1.1em;
  text-decoration: none;
  transition: color 0.18s, text-decoration 0.18s;
  margin: 0.1em 0;
  display: block;
  letter-spacing: 0.01em;
}
.service-link:hover, .service-link.router-link-active {
  color: #b8860b;
  text-decoration: underline;
  background: none;
  box-shadow: none;
}
.divider {
  height: 1px;
  background: #f3e9d2;
  margin: 0.1em 0;
}
</style> 