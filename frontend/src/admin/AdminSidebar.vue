<template>
  <aside class="w-16 lg:w-72 bg-amber-50 h-full shadow flex flex-col transition-all duration-300">
    <!-- Logo/Title -->
    <div class="p-4 border-b border-amber-200">
      <div class="font-bold text-lg lg:text-xl text-amber-700 truncate">
        <span class="sidebar-label hidden lg:inline">後台管理</span>
      </div>
    </div>
    
    <!-- Navigation Links -->
    <nav class="flex-1 p-2">
      <router-link 
        to="/admin" 
        class="flex items-center p-3 mb-2 rounded-lg hover:bg-amber-100 transition-colors group"
        active-class="bg-amber-200 text-amber-800 font-semibold"
      >
        <svg class="w-5 h-5 mr-3 lg:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
        </svg>
        <span class="sidebar-label hidden lg:inline">儀表板</span>
      </router-link>
      
      <router-link 
        to="/admin/products" 
        class="flex items-center p-3 mb-2 rounded-lg hover:bg-amber-100 transition-colors group"
        active-class="bg-amber-200 text-amber-800 font-semibold"
      >
        <svg class="w-5 h-5 mr-3 lg:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
        </svg>
        <span class="sidebar-label hidden lg:inline">商品管理</span>
      </router-link>
      
      <router-link 
        to="/admin/orders" 
        class="flex items-center p-3 mb-2 rounded-lg hover:bg-amber-100 transition-colors group"
        active-class="bg-amber-200 text-amber-800 font-semibold"
      >
        <svg class="w-5 h-5 mr-3 lg:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        <span class="sidebar-label hidden lg:inline">訂單管理</span>
      </router-link>
      
      <router-link 
        to="/admin/members" 
        class="flex items-center p-3 mb-2 rounded-lg hover:bg-amber-100 transition-colors group"
        active-class="bg-amber-200 text-amber-800 font-semibold"
      >
        <svg class="w-5 h-5 mr-3 lg:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
        </svg>
        <span class="sidebar-label hidden lg:inline">會員管理</span>
      </router-link>
      
      <router-link 
        to="/admin/coupons" 
        class="flex items-center p-3 mb-2 rounded-lg hover:bg-amber-100 transition-colors group"
        active-class="bg-amber-200 text-amber-800 font-semibold"
      >
        <svg class="w-5 h-5 mr-3 lg:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
        </svg>
        <span class="sidebar-label hidden lg:inline">優惠券管理</span>
      </router-link>
      
      <router-link 
        to="/admin/admins" 
        class="flex items-center p-3 mb-2 rounded-lg hover:bg-amber-100 transition-colors group"
        active-class="bg-amber-200 text-amber-800 font-semibold"
      >
        <svg class="w-5 h-5 mr-3 lg:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
        </svg>
        <span class="sidebar-label hidden lg:inline">管理員管理</span>
      </router-link>
      
      <router-link 
        to="/admin/operation-logs" 
        class="flex items-center p-3 mb-2 rounded-lg hover:bg-amber-100 transition-colors group"
        active-class="bg-amber-200 text-amber-800 font-semibold"
      >
        <svg class="w-5 h-5 mr-3 lg:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
        </svg>
        <span class="sidebar-label hidden lg:inline">操作日誌</span>
      </router-link>
    </nav>
    
    <!-- Logout Button -->
    <div class="p-2 border-t border-amber-200">
      <button 
        @click="logout" 
        class="flex items-center w-full p-3 rounded-lg text-red-600 hover:bg-red-50 transition-colors group"
      >
        <svg class="w-5 h-5 mr-3 lg:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
        </svg>
        <span class="sidebar-label hidden lg:inline">登出</span>
      </button>
    </div>
  </aside>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router'
import { useAdminAuthStore } from '@/stores/adminAuth'

const router = useRouter()
const adminAuthStore = useAdminAuthStore()

function logout() {
  adminAuthStore.logout()
  router.push('/admin/login')
}
</script>

<style scoped>
aside { 
  min-height: 100vh; 
}

/* Tooltip for mobile */
@media (max-width: 1024px) {
  .group:hover::after {
    content: attr(data-tooltip);
    position: absolute;
    left: 100%;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    white-space: nowrap;
    z-index: 1000;
    margin-left: 8px;
  }
}

.sidebar-label {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  vertical-align: middle;
  max-width: 180px;
}
</style> 