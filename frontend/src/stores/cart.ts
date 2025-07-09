import { defineStore } from 'pinia'
import axios from 'axios'

const API_BASE = 'http://127.0.0.1:8000';

export interface CartItem {
  id: number
  product_id: number
  name: string
  price: number
  quantity: number
  image?: string
  product?: any
}

export const useCartStore = defineStore('cart', {
  state: () => ({
    items: [] as CartItem[],
    loading: false,
    error: null as string | null,
  }),
  getters: {
    itemCount: (state) => {
      return state.items.reduce((total, item) => total + item.quantity, 0)
    },
    totalPrice: (state) => {
      return state.items.reduce((total, item) => total + (item.price * item.quantity), 0)
    }
  },
  actions: {
    async fetchCart() {
      this.loading = true
      try {
        const res = await axios.get(`${API_BASE}/api/v1/cart`)
        if (res.data.success) {
          // 後端回傳 data: [ { id, product_id, quantity, product: {...} } ]
          this.items = res.data.data.map((item: any) => ({
            id: item.id,
            product_id: item.product_id,
            name: item.product?.name || '',
            price: item.product?.final_price || 0,
            quantity: item.quantity,
            image: item.product?.primary_image?.image_path,
            product: item.product
          }))
        }
      } catch (e) {
        this.error = '無法取得購物車資料'
      } finally {
        this.loading = false
      }
    },
    async addToCart(productId: number, quantity = 1) {
      try {
        await axios.post(`${API_BASE}/api/v1/cart`, {
          product_id: productId,
          quantity
        })
        await this.fetchCart()
      } catch (e) {
        this.error = '加入購物車失敗'
      }
    },
    async updateQuantity(cartItemId: number, quantity: number) {
      try {
        await axios.put(`${API_BASE}/api/v1/cart/${cartItemId}`, {
          quantity
        })
        await this.fetchCart()
      } catch (e) {
        this.error = '更新數量失敗'
      }
    },
    async removeFromCart(cartItemId: number) {
      try {
        await axios.delete(`${API_BASE}/api/v1/cart/${cartItemId}`)
        await this.fetchCart()
      } catch (e) {
        this.error = '移除商品失敗'
      }
    },
    clearCart() {
      this.items = []
    },
  },
}) 