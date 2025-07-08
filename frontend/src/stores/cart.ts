import { defineStore } from 'pinia'
import type { Product } from './products'
import axios from 'axios'

const API_BASE = 'http://127.0.0.1:8000';

export interface CartItem {
  id: number
  name: string
  price: number
  quantity: number
  image?: string
}

export const useCartStore = defineStore('cart', {
  state: () => ({
    items: [] as CartItem[],
    loading: false,
    error: null as string | null,
  }),
  actions: {
    async fetchCart() {
      this.loading = true
      try {
        const res = await axios.get(`${API_BASE}/api/v1/cart`)
        if (res.data.success) {
          this.items = res.data.cart.map((item: any) => ({
            id: item.id,
            name: item.name,
            price: item.price,
            quantity: item.qty,
            image: item.image || undefined
          }))
        }
      } catch (e) {
        this.error = '無法取得購物車資料'
      } finally {
        this.loading = false
      }
    },
    async addToCart(product: any, quantity = 1) {
      try {
        const res = await axios.post(`${API_BASE}/api/v1/cart/add`, {
          product_id: product.id,
          qty: quantity
        })
        if (res.data.success) {
          await this.fetchCart()
        }
      } catch (e) {
        this.error = '加入購物車失敗'
      }
    },
    async updateQuantity(productId: number, quantity: number) {
      try {
        const res = await axios.put(`${API_BASE}/api/v1/cart/update`, {
          product_id: productId,
          qty: quantity
        })
        if (res.data.success) {
          await this.fetchCart()
        }
      } catch (e) {
        this.error = '更新數量失敗'
      }
    },
    async removeFromCart(productId: number) {
      try {
        const res = await axios.delete(`${API_BASE}/api/v1/cart/remove`, {
          data: { product_id: productId }
        })
        if (res.data.success) {
          await this.fetchCart()
        }
      } catch (e) {
        this.error = '移除商品失敗'
      }
    },
    clearCart() {
      this.items = []
    },
  },
}) 