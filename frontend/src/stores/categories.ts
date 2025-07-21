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
      if (this.categories.length > 0) return
      
      try {
        this.loading = true
        try {
          const res = await axios.get(`/api/v1/categories`)
          this.categories = res.data
        } catch (e) {
          this.error = '無法取得分類資料'
        } finally {
          this.loading = false
        }
      } catch (e) {
        this.error = '無法取得分類資料'
      } finally {
        this.loading = false
      }
    },
  },
}) 