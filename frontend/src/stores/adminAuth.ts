import { defineStore } from 'pinia'

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
        this.token = token
        this.isAuthenticated = true
        // 你可以在這裡加載 admin user 資料
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
    },
    logout() {
      this.token = ''
      this.isAuthenticated = false
      this.user = null
      localStorage.removeItem('admin_token')
    }
  }
})