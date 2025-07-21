import { defineStore } from 'pinia'
import axios from 'axios'
import { useAuthStore } from './auth'

export interface CartItem {
  id: number
  product_id: number
  name: string
  price: number
  quantity: number
  image?: string
  product?: any
  spec?: string
  weight?: string // 新增 weight
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
      if (useAuthStore().isAuthenticated) {
        this.loading = true
        try {
          const res = await axios.get(`/api/v1/cart`)
          if (res.data.success) {
            // 後端回傳 data: [ { id, product_id, quantity, product: {...}, spec, price } ]
            this.items = res.data.data.map((item: any) => ({
              id: item.id,
              product_id: item.product_id,
              name: item.name || item.product?.name || '',
              price: item.price || item.product?.final_price || 0,
              quantity: item.quantity,
              spec: item.spec,
              weight: item.weight, // 新增 weight
              image: item.image || item.product?.primary_image?.image_path,
              product: item.product
            }))
          }
        } catch (e) {
          this.error = '無法取得購物車資料'
          // 如果無法取得購物車資料，清空本地購物車
          this.items = []
        } finally {
          this.loading = false
        }
      } else {
        this.clearCart()
      }
    },
    async addToCart(productId: number, quantity = 1, spec?: string, price?: number, weight?: string, spec_id?: number) {
      if (useAuthStore().isAuthenticated) {
        try {
          await axios.post(`/api/v1/cart`, {
            product_id: productId,
            quantity,
            spec,
            price, // 新增
            weight, // 新增
            spec_id // 新增
          })
          await this.fetchCart()
        } catch (e) {
          this.error = '加入購物車失敗'
        }
      } else {
        this.error = '請先登入以加入購物車'
      }
    },
    async updateQuantity(cartItemId: number, quantity: number) {
      if (useAuthStore().isAuthenticated) {
        try {
          await axios.put(`/api/v1/cart/${cartItemId}`, {
            quantity
          })
          await this.fetchCart()
        } catch (e) {
          this.error = '更新數量失敗'
        }
      } else {
        this.error = '請先登入以更新購物車'
      }
    },
    async removeFromCart(cartItemId: number) {
      if (useAuthStore().isAuthenticated) {
        try {
          await axios.delete(`/api/v1/cart/${cartItemId}`)
          await this.fetchCart()
        } catch (e) {
          this.error = '移除商品失敗'
        }
      } else {
        this.error = '請先登入以移除商品'
      }
    },
    clearCart() {
      this.items = []
    },
    // 監聽認證狀態變化
    handleAuthChange() {
      const authStore = useAuthStore()
      
      if (authStore.isAuthenticated) {
        // 用戶登入，載入購物車
        this.fetchCart()
      } else {
        // 用戶登出，清空購物車
        this.clearCart()
      }
    }
  },
}) 