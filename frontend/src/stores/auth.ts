import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import axios from 'axios'

interface User {
  id: number
  name: string
  email: string
  email_verified_at?: string
  created_at: string
  updated_at: string
  phone?: string
  address?: string
  birthday?: string
  gender?: string
  points?: number
  member_level?: string
  avatar?: string
  avatar_url?: string
  email_notifications?: boolean
  last_login_at?: string
  line_user_id?: string
  facebook_user_id?: string
  google_user_id?: string
}

interface LoginForm {
  email: string
  password: string
}

interface RegisterForm {
  name: string
  email: string
  password: string
  password_confirmation: string
}

export const useAuthStore = defineStore('auth', () => {
  const user = ref<User | null>(null)
  const token = ref<string | null>(null)
  const loading = ref(false)
  const error = ref<string | null>(null)

  // 計算屬性
  const isAuthenticated = computed(() => !!user.value && !!token.value)
  const userInitials = computed(() => {
    if (!user.value?.name) return ''
    return user.value.name.split(' ').map(n => n[0]).join('').toUpperCase()
  })

  // 初始化認證狀態
  const initAuth = async () => {
    const savedToken = localStorage.getItem('auth_token')
    const savedUser = localStorage.getItem('auth_user')
    
    if (savedToken && savedUser) {
      token.value = savedToken
      user.value = JSON.parse(savedUser)
      // 設定 axios 預設 headers
      axios.defaults.headers.common['Authorization'] = `Bearer ${savedToken}`
      
      // 通知購物車 store 用戶已登入
      const { useCartStore } = await import('./cart')
      const cartStore = useCartStore()
      cartStore.handleAuthChange()
    }
  }

  // 登入
  const login = async (credentials: LoginForm) => {
    loading.value = true
    error.value = null
    
    try {
      const response = await axios.post('http://127.0.0.1:8000/api/v1/auth/login', credentials)
      
      if (response.data.success) {
        const { user: userData, token: authToken } = response.data
        
        user.value = userData
        token.value = authToken
        
        // 儲存到 localStorage
        localStorage.setItem('auth_token', authToken)
        localStorage.setItem('auth_user', JSON.stringify(userData))
        
        // 設定 axios 預設 headers
        axios.defaults.headers.common['Authorization'] = `Bearer ${authToken}`
        
        // 通知購物車 store 用戶已登入
        const { useCartStore } = await import('./cart')
        const cartStore = useCartStore()
        cartStore.handleAuthChange()
        
        return { success: true }
      } else {
        error.value = response.data.message || '登入失敗'
        return { success: false, error: error.value }
      }
    } catch (err: any) {
      if (err.response?.data?.message) {
        error.value = err.response.data.message
      } else {
        error.value = '登入失敗，請檢查網路連線'
      }
      return { success: false, error: error.value }
    } finally {
      loading.value = false
    }
  }

  // 註冊
  const register = async (userData: RegisterForm) => {
    loading.value = true
    error.value = null
    
    try {
      const response = await axios.post('http://127.0.0.1:8000/api/v1/auth/register', userData)
      
      if (response.data.success) {
        const { user: newUser, token: authToken } = response.data
        
        user.value = newUser
        token.value = authToken
        
        // 儲存到 localStorage
        localStorage.setItem('auth_token', authToken)
        localStorage.setItem('auth_user', JSON.stringify(newUser))
        
        // 設定 axios 預設 headers
        axios.defaults.headers.common['Authorization'] = `Bearer ${authToken}`
        
        // 通知購物車 store 用戶已登入
        const { useCartStore } = await import('./cart')
        const cartStore = useCartStore()
        cartStore.handleAuthChange()
        
        return { success: true }
      } else {
        error.value = response.data.message || '註冊失敗'
        return { success: false, error: error.value }
      }
    } catch (err: any) {
      if (err.response?.data?.errors) {
        const errors = err.response.data.errors
        error.value = Object.values(errors).flat().join(', ')
      } else if (err.response?.data?.message) {
        error.value = err.response.data.message
      } else {
        error.value = '註冊失敗，請檢查網路連線'
      }
      return { success: false, error: error.value }
    } finally {
      loading.value = false
    }
  }

  // 登出
  const logout = async () => {
    loading.value = true
    error.value = null
    
    try {
      // 如果有 token，先呼叫後端登出 API
      if (token.value) {
        await axios.post('http://127.0.0.1:8000/api/v1/auth/logout')
      }
    } catch (err: any) {
      // 即使後端登出失敗，也要清除前端狀態
      console.warn('後端登出失敗，但已清除前端狀態:', err.message)
    } finally {
      // 通知購物車 store 用戶已登出
      const { useCartStore } = await import('./cart')
      const cartStore = useCartStore()
      cartStore.handleAuthChange()
      
      // 清除前端狀態
      user.value = null
      token.value = null
      error.value = null
      
      // 清除 localStorage
      localStorage.removeItem('auth_token')
      localStorage.removeItem('auth_user')
      
      // 清除 axios 預設 headers
      delete axios.defaults.headers.common['Authorization']
      
      loading.value = false
    }
  }

  // 忘記密碼
  const forgotPassword = async (email: string) => {
    loading.value = true
    error.value = null
    
    try {
      const response = await axios.post('http://127.0.0.1:8000/api/v1/auth/forgot-password', { email })
      
      if (response.data.success) {
        return { success: true, message: response.data.message }
      } else {
        error.value = response.data.message || '發送失敗'
        return { success: false, error: error.value }
      }
    } catch (err: any) {
      if (err.response?.data?.message) {
        error.value = err.response.data.message
      } else {
        error.value = '發送失敗，請檢查網路連線'
      }
      return { success: false, error: error.value }
    } finally {
      loading.value = false
    }
  }

  // 檢查認證狀態
  const checkAuth = async () => {
    if (!token.value) return false
    
    try {
      const response = await axios.get('http://127.0.0.1:8000/api/v1/auth/user')
      user.value = response.data.user
      return true
    } catch (err) {
      logout()
      return false
    }
  }

  // 清除錯誤
  const clearError = () => {
    error.value = null
  }

  return {
    // 狀態
    user,
    token,
    loading,
    error,
    
    // 計算屬性
    isAuthenticated,
    userInitials,
    
    // 方法
    initAuth,
    login,
    register,
    logout,
    forgotPassword,
    checkAuth,
    clearError
  }
}) 