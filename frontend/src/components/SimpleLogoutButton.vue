<template>
  <button
    @click="handleClick"
    :disabled="loading"
    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200"
  >
    <svg 
      v-if="!loading" 
      class="w-4 h-4 mr-1.5" 
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
      class="animate-spin w-4 h-4 mr-1.5" 
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
    
    {{ loading ? '登出中...' : text }}
  </button>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useAuthStore } from '@/stores/auth'

interface Props {
  text?: string
  showConfirm?: boolean
}

interface Emits {
  (e: 'logout'): void
  (e: 'error', error: any): void
}

const props = withDefaults(defineProps<Props>(), {
  text: '登出',
  showConfirm: false
})

const emit = defineEmits<Emits>()

const authStore = useAuthStore()
const loading = computed(() => authStore.loading)

const handleClick = async () => {
  if (props.showConfirm) {
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