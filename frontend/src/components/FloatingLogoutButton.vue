<template>
  <div v-if="show" class="fixed bottom-6 right-6 z-50">
    <div class="relative">
      <!-- 主按鈕 -->
      <button
        @click="toggleMenu"
        class="bg-red-600 hover:bg-red-700 text-white p-3 rounded-full shadow-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
        :class="{ 'rotate-45': isMenuOpen }"
      >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path 
            stroke-linecap="round" 
            stroke-linejoin="round" 
            stroke-width="2" 
            d="M12 6v6m0 0v6m0-6h6m-6 0H6" 
          />
        </svg>
      </button>

      <!-- 選單 -->
      <div 
        v-if="isMenuOpen" 
        class="absolute bottom-16 right-0 bg-white rounded-lg shadow-xl border border-gray-200 py-2 min-w-48"
      >
        <!-- 用戶資訊 -->
        <div class="px-4 py-3 border-b border-gray-100">
          <div class="flex items-center">
            <div class="h-8 w-8 rounded-full bg-amber-600 flex items-center justify-center text-white font-medium text-sm">
              {{ authStore.userInitials }}
            </div>
            <div class="ml-3">
              <div class="text-sm font-medium text-gray-900">{{ authStore.user?.name }}</div>
              <div class="text-xs text-gray-500">{{ authStore.user?.email }}</div>
            </div>
          </div>
        </div>

        <!-- 選單項目 -->
        <div class="py-1">
          <router-link
            to="/profile"
            class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-150"
            @click="closeMenu"
          >
            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            個人資料
          </router-link>
          
          <router-link
            to="/orders"
            class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-150"
            @click="closeMenu"
          >
            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            我的訂單
          </router-link>

          <div class="border-t border-gray-100 my-1"></div>

          <button
            @click="handleLogout"
            class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors duration-150"
          >
            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            登出
          </button>
        </div>
      </div>
    </div>

    <!-- 登出確認對話框 -->
    <LogoutConfirmDialog 
      :show="showLogoutDialog" 
      @close="showLogoutDialog = false" 
    />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import LogoutConfirmDialog from './LogoutConfirmDialog.vue'

interface Props {
  show?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  show: true
})

const router = useRouter()
const authStore = useAuthStore()

const isMenuOpen = ref(false)
const showLogoutDialog = ref(false)

const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value
}

const closeMenu = () => {
  isMenuOpen.value = false
}

const handleLogout = () => {
  showLogoutDialog.value = true
  closeMenu()
}

// 點擊外部關閉選單
const handleClickOutside = (event: Event) => {
  const target = event.target as HTMLElement
  if (!target.closest('.floating-logout-menu')) {
    closeMenu()
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})
</script>

<style scoped>
/* 使用 Tailwind CSS */
</style> 