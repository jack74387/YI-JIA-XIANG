import { defineStore } from 'pinia'
import axios from 'axios'

export interface Product {
  id: number
  name: string
  category?: string
  category_id?: number
  short_description?: string
  description?: string
  price?: number
  price_large?: number | null
  price_small?: number | null
  final_price?: number
  has_discount?: boolean
  discount_percentage?: number
  stock_quantity?: number
  primary_image?: {
    image_path: string
  }
  image?: string | null
  unit?: string | null
  specs?: string
}

const API_BASE = 'http://127.0.0.1:8000';

export const useProductsStore = defineStore('products', {
  state: () => ({
    products: [] as Product[],
    product: null as Product | null,
    loading: false,
    error: null as string | null,
  }),
  actions: {
    async fetchProducts() {
      this.loading = true
      try {
        const res = await axios.get(`${API_BASE}/api/v1/products`)
        if (res.data.success) {
          this.products = res.data.data.data
        }
      } catch (e) {
        this.error = '無法取得商品資料'
      } finally {
        this.loading = false
      }
    },
    async fetchProduct(id: number) {
      this.loading = true
      try {
        const res = await axios.get(`${API_BASE}/api/v1/products/${id}`)
        if (res.data.success) {
          this.product = res.data.data
        }
      } catch (e) {
        this.error = '無法取得商品詳情'
      } finally {
        this.loading = false
      }
    },
  },
}) 