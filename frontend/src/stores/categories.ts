import { defineStore } from 'pinia'
import axios from 'axios'

export interface Category {
  id: number
  name: string
  description?: string
  image?: string
}

export const useCategoriesStore = defineStore('categories', {
  state: () => ({
    categories: [] as Category[],
    loading: false,
    error: null as string | null,
  }),
  actions: {
    async fetchCategories() {
      this.loading = true
      try {
        const API_BASE = 'http://127.0.0.1:8000';
        const res = await axios.get(`${API_BASE}/api/v1/categories`)
        this.categories = res.data
      } catch (e) {
        this.error = '無法取得分類資料'
      } finally {
        this.loading = false
      }
    },
  },
}) 