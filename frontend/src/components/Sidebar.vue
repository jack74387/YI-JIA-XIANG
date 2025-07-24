<template>
  <div>
    <!-- 現代感漢堡按鈕 -->
    <button
      class="fixed top-4 right-4 z-50 p-1.5 rounded-full bg-white/60 shadow-md backdrop-blur-sm text-gray-400 hover:text-amber-600 hover:bg-white/90 focus:outline-none lg:hidden border border-gray-200"
      @click="openSidebar"
      aria-label="開啟選單"
    >
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
      </svg>
    </button>

    <!-- 遮罩 -->
    <transition name="fade">
      <div v-if="isOpen" class="fixed inset-0 z-40 bg-black/40 backdrop-blur-sm lg:hidden" @click="closeSidebar"></div>
    </transition>

    <!-- 現代感側邊欄 -->
    <transition name="slide-right">
      <aside
        v-show="isOpen"
        class="fixed top-0 right-0 h-full w-80 max-w-[90vw] bg-white/80 shadow-2xl z-50 rounded-l-3xl flex flex-col py-0 px-0 transition-transform duration-300 backdrop-blur-xl border-l border-amber-100 lg:hidden"
        :class="{ 'translate-x-0': isOpen, 'translate-x-full': !isOpen }"
        tabindex="-1"
        @keydown.esc="closeSidebar"
      >
        <!-- 頂部品牌LOGO -->
        <div class="flex items-center gap-3 px-7 pt-7 pb-3 border-b border-amber-100">
          <img src="/images/logo.jpg" alt="品牌LOGO" class="h-12 w-12 rounded-full object-cover shadow-md" />
          <div class="flex flex-col">
            <span class="text-2xl font-extrabold text-amber-700 tracking-wide leading-tight">一佳香</span>
            <span class="text-xs font-light uppercase tracking-widest text-amber-500">YI JIA XIANG</span>
          </div>
          <button class="ml-auto p-2 rounded-full hover:bg-amber-50 transition" @click="closeSidebar" aria-label="關閉選單">
            <svg class="w-7 h-7 text-amber-400 hover:text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        <!-- 導航選單 -->
        <nav class="flex-1 flex flex-col gap-2 px-4 py-4">
          <SidebarLink icon="home" to="/" label="首頁" @close="closeSidebar" />
          <SidebarLink icon="shopping-bag" to="/products" label="商品" @close="closeSidebar" />
          <!-- 服務專區展開選單 -->
          <div>
            <button @click="showServiceMenu = !showServiceMenu" class="sidebar-link w-full flex items-center justify-between font-semibold text-amber-700">
              <span class="flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7V6a2 2 0 012-2h14a2 2 0 012 2v1M16 21H8a2 2 0 01-2-2V7h12v12a2 2 0 01-2 2z"/></svg>
                <span>服務專區</span>
              </span>
              <svg :class="{'rotate-90': showServiceMenu}" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
            <div v-if="showServiceMenu" class="pl-8 flex flex-col gap-1 mt-1">
              <router-link to="/line-friend" class="sidebar-link" @click.native="closeSidebar">LINE好友專區</router-link>
              <router-link to="/group-order" class="sidebar-link" @click.native="closeSidebar">團購/企業訂購</router-link>
              <router-link to="/gift" class="sidebar-link" @click.native="closeSidebar">禮盒加值服務</router-link>
              <router-link to="/food-trace" class="sidebar-link" @click.native="closeSidebar">食品履歷查詢</router-link>
              <router-link to="/store-locator" class="sidebar-link" @click.native="closeSidebar">門市據點</router-link>
            </div>
          </div>
          <SidebarLink icon="chat-bubble-left-ellipsis" to="/faq" label="常見問題" @close="closeSidebar" />
          <SidebarLink icon="phone" to="/contact" label="聯絡我們" @close="closeSidebar" />
        </nav>
        <!-- 會員與購物車 -->
        <div class="mt-auto px-7 pb-7 pt-4 border-t border-amber-100 bg-white/70 rounded-b-3xl shadow-inner flex flex-col gap-3">
          <router-link v-if="authStore.isAuthenticated" to="/profile" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-amber-50 transition font-semibold text-amber-700" @click.native="closeSidebar">
            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.75 20.25a8.25 8.25 0 1114.5 0v.25a.75.75 0 01-.75.75H5.5a.75.75 0 01-.75-.75v-.25z"/></svg>
            <span>會員中心</span>
          </router-link>
          <template v-else>
            <router-link to="/login" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-amber-50 transition font-semibold text-amber-700" @click.native="closeSidebar">
              <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.75 20.25a8.25 8.25 0 1114.5 0v.25a.75.75 0 01-.75.75H5.5a.75.75 0 01-.75-.75v-.25z"/></svg>
              <span>登入</span>
            </router-link>
            <router-link to="/register" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-amber-50 transition font-semibold text-amber-700" @click.native="closeSidebar">
              <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M12 11a4 4 0 100-8 4 4 0 000 8z"/></svg>
              <span>註冊</span>
            </router-link>
          </template>
          <router-link to="/cart" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-amber-50 transition font-semibold text-amber-700 relative" @click.native="closeSidebar">
            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.7A1 1 0 007 17h10a1 1 0 00.95-.68L19 13M7 13V6a1 1 0 011-1h5a1 1 0 011 1v7"/></svg>
            <span>購物車</span>
            <span v-if="cartStore.itemCount > 0" class="ml-auto bg-amber-600 text-white rounded-full px-2 py-0.5 text-xs font-bold absolute right-3 top-1">{{ cartStore.itemCount }}</span>
          </router-link>
        </div>
      </aside>
    </transition>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import SidebarLink from './SidebarLink.vue'
import { useCartStore } from '@/stores/cart'
import { useAuthStore } from '@/stores/auth'
const cartStore = useCartStore()
const authStore = useAuthStore()
const showServiceMenu = ref(false)

const isOpen = ref(false)
const openSidebar = () => { isOpen.value = true }
const closeSidebar = () => { isOpen.value = false }

const handleEsc = (e: KeyboardEvent) => {
  if (e.key === 'Escape') closeSidebar()
}
onMounted(() => {
  window.addEventListener('keydown', handleEsc)
})
onUnmounted(() => {
  window.removeEventListener('keydown', handleEsc)
})
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.2s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
.slide-right-enter-active, .slide-right-leave-active {
  transition: transform 0.3s cubic-bezier(.4,0,.2,1);
}
.slide-right-enter-from, .slide-right-leave-to {
  transform: translateX(100%);
}
.slide-right-enter-to, .slide-right-leave-from {
  transform: translateX(0);
}
/* 現代感側邊欄樣式 */
.sidebar-link {
  display: flex;
  align-items: center;
  gap: 1.1rem;
  padding: 0.85rem 1.3rem;
  border-radius: 1.2em;
  color: #a67c00;
  font-weight: 600;
  font-size: 1.13rem;
  transition: background 0.18s, color 0.18s, box-shadow 0.18s;
  text-decoration: none;
  letter-spacing: 0.01em;
}
.sidebar-link:hover, .sidebar-link.active {
  background: #fffbe9;
  color: #b8860b;
  box-shadow: 0 2px 12px #ffe9b2aa;
}
@media (max-width: 600px) {
  .sidebar-link,
  .flex.flex-col.gap-2.px-4.py-4,
  .pl-8.flex.flex-col.gap-1.mt-1,
  .mt-auto.px-7.pb-7.pt-4.border-t.border-amber-100.bg-white\/70.rounded-b-3xl.shadow-inner.flex.flex-col.gap-3,
  .flex.items-center.gap-3.px-3.py-2.rounded-xl,
  .text-xs.font-light.uppercase.tracking-widest.text-amber-500,
  .text-2xl.font-extrabold.text-amber-700.tracking-wide.leading-tight {
    font-size: 0.9em !important;
  }
  .sidebar-link svg,
  .flex.items-center.gap-3.px-3.py-2.rounded-xl svg,
  .flex.items-center.gap-2 svg,
  .w-6.h-6,
  .w-7.h-7 {
    width: 1.1rem !important;
    height: 1.1rem !important;
  }
}
</style> 