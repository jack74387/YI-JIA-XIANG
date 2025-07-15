<template>
  <div class="cart-page max-w-4xl mx-auto py-10 px-4">
    <h1 class="text-2xl font-bold mb-8">購物車</h1>
    <div v-if="cart.loading" class="text-center py-10">載入中...</div>
    <div v-else>
      <div v-if="cart.items.length === 0" class="text-center text-gray-500 py-16">
        購物車是空的。
      </div>
      <div v-else>
        <table class="w-full mb-8 border-separate border-spacing-y-3">
          <thead>
            <tr class="text-left text-gray-600 text-sm">
              <th></th>
              <th>商品</th>
              <th>規格</th>
              <th>單價</th>
              <th>數量</th>
              <th>小計</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in cart.items" :key="item.id" class="bg-white rounded-lg shadow-sm">
              <td class="py-2">
                <img :src="item.image || '/images/placeholder.jpg'" alt="商品圖" class="w-16 h-16 object-cover rounded" />
              </td>
              <td class="font-semibold">{{ item.name }}</td>
              <td class="text-sm text-gray-500">
                <template v-if="item.weight">
                  {{ item.weight }}
                </template>
                <template v-else>
                  {{ getSpecLabel(item.spec || '') || '-' }}
                </template>
              </td>
              <td class="text-primary-600 font-bold">NT${{ item.price }}</td>
              <td>
                <div class="flex items-center gap-2">
                  <button @click="updateQty(item, item.quantity-1)" :disabled="item.quantity<=1" class="qty-btn">-</button>
                  <span class="w-8 text-center">{{ item.quantity }}</span>
                  <button @click="updateQty(item, item.quantity+1)" class="qty-btn">+</button>
                </div>
              </td>
              <td class="font-bold">NT${{ item.price * item.quantity }}</td>
              <td>
                <button @click="remove(item)" class="text-red-500 hover:underline">移除</button>
              </td>
            </tr>
          </tbody>
        </table>
        <div class="flex justify-between items-center mb-8">
          <div></div>
          <div class="text-xl font-bold">總金額：<span class="text-primary-600">NT${{ cart.totalPrice }}</span></div>
        </div>
        <div class="flex justify-end">
          <button class="checkout-btn" @click="checkout" :disabled="cart.items.length===0">前往結帳</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import { useCartStore } from '@/stores/cart'
import { useRouter } from 'vue-router'

const cart = useCartStore()
const router = useRouter()

onMounted(() => {
  cart.fetchCart()
})

function updateQty(item: any, qty: number) {
  if (qty < 1) return
  cart.updateQuantity(item.id, qty)
}
function remove(item: any) {
  cart.removeFromCart(item.id)
}
function checkout() {
  // 這裡可以導向結帳頁或呼叫API
  router.push('/checkout')
}

function getSpecLabel(spec: string) {
  if (spec === 'large') return '600g'
  if (spec === 'small') return '300g'
  if (spec === 'sample') return '隨手包'
  return '-'
}
</script>

<style scoped>
.cart-page { background: #f8f6f2; min-height: 80vh; border-radius: 16px; }
table { background: transparent; }
th, td { padding: 0.5em 0.7em; }
.qty-btn {
  background: #eee;
  border: none;
  border-radius: 50%;
  width: 2.2em;
  height: 2.2em;
  font-size: 1.3em;
  font-weight: bold;
  color: #b85c38;
  cursor: pointer;
  box-shadow: 0 2px 8px rgba(184,92,56,0.08);
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background 0.2s, color 0.2s, box-shadow 0.2s, transform 0.15s;
}
.qty-btn:hover:not(:disabled) {
  background: #b85c38;
  color: #fff;
  box-shadow: 0 4px 16px rgba(184,92,56,0.18);
  transform: scale(1.08);
}
.qty-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
  background: #eee;
  color: #bbb;
  box-shadow: none;
}
.checkout-btn {
  background: linear-gradient(90deg, #cb6a43 0%, #b85c38 100%);
  color: #fff;
  font-size: 1.1em;
  font-weight: 700;
  border: none;
  border-radius: 25px;
  padding: 0.8em 2.5em;
  box-shadow: 0 4px 16px rgba(200, 106, 67, 0.18);
  cursor: pointer;
  transition: background 0.2s, box-shadow 0.2s, transform 0.15s;
}
.checkout-btn:hover:enabled {
  background: linear-gradient(90deg, #e07a4a 0%, #a04a2e 100%);
  box-shadow: 0 8px 24px rgba(200, 106, 67, 0.28);
  transform: translateY(-2px) scale(1.04);
}
.checkout-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
</style> 