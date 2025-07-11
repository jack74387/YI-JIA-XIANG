import { defineStore } from 'pinia'
import axios from 'axios'

export const useAdminAuthStore = defineStore('adminAuth', {
  state: () => ({
    isAuthenticated: false,
    token: '',
    user: null as null | { id: number; name: string; email: string },
  }),
  actions: {
    async initAuth() {
      // 嘗試從 localStorage 讀取 admin token
      const token = localStorage.getItem('admin_token')
      if (token) {
        try {
          // 驗證 token 是否有效
          const response = await axios.get('http://127.0.0.1:8000/api/v1/auth/user', {
            headers: {
              'Authorization': `Bearer ${token}`
            }
          })
          
          if (response.data.success) {
            this.token = token
            this.isAuthenticated = true
            this.user = response.data.user
            
            // 設置 axios 默認 header
            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
          } else {
            // Token 無效，清除
            this.logout()
          }
        } catch (error) {
          console.error('Token validation failed:', error)
          // Token 無效，清除
          this.logout()
        }
      } else {
        this.isAuthenticated = false
        this.token = ''
        this.user = null
      }
    },
    setAuth(token: string, user: any) {
      this.token = token
      this.isAuthenticated = true
      this.user = user
      localStorage.setItem('admin_token', token)
      
      // 設置 axios 默認 header
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
    },
    logout() {
      this.token = ''
      this.isAuthenticated = false
      this.user = null
      localStorage.removeItem('admin_token')
      
      // 清除 axios 默認 header
      delete axios.defaults.headers.common['Authorization']
    }
  }
})