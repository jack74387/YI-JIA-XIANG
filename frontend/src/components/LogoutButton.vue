<template>
  <button
    @click="handleClick"
    :disabled="loading"
    :class="[
      'flex items-center justify-center transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2',
      size === 'sm' ? 'px-3 py-2 text-sm' : 'px-4 py-2 text-base',
      variant === 'outline' 
        ? 'border border-red-300 text-red-600 hover:bg-red-50 hover:border-red-400' 
        : 'bg-red-600 text-white hover:bg-red-700 shadow-sm',
      'disabled:opacity-50 disabled:cursor-not-allowed'
    ]"
  >
    <svg 
      v-if="!loading" 
      class="w-4 h-4 mr-2" 
      fill="none" 
      stroke="currentColor" 
      viewBox="0 0 24 24"
    >
      <path 
        stroke-linecap="round" 
        stroke-linejoin="round" 
        stroke-width="2" 
        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" 
      />
    </svg>
    
    <svg 
      v-else 
      class="animate-spin w-4 h-4 mr-2" 
      fill="none" 
      viewBox="0 0 24 24"
    >
      <circle 
        class="opacity-25" 
        cx="12" 
        cy="12" 
        r="10" 
        stroke="currentColor" 
        stroke-width="4"
      ></circle>
      <path 
        class="opacity-75" 
        fill="currentColor" 
        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
      ></path>
    </svg>
    
    <span>{{ loading ? '登出中...' : text }}</span>
  </button>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useAuthStore } from '@/stores/auth'

interface Props {
  variant?: 'solid' | 'outline'
  size?: 'sm' | 'md'
  text?: string
  showConfirm?: boolean
}

interface Emits {
  (e: 'logout'): void
  (e: 'error', error: any): void
}

const props = withDefaults(defineProps<Props>(), {
  variant: 'outline',
  size: 'md',
  text: '登出',
  showConfirm: false
})

const emit = defineEmits<Emits>()

const authStore = useAuthStore()
const loading = computed(() => authStore.loading)

const handleClick = async () => {
  if (props.showConfirm) {
    // 如果啟用確認對話框，觸發事件讓父組件處理
    emit('logout')
    return
  }
  
  try {
    await authStore.logout()
    emit('logout')
  } catch (error) {
    emit('error', error)
  }
}
</script>

<style scoped>
/* 使用 Tailwind CSS */
</style> 