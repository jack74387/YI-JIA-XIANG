<template>
  <div class="cart-view">
    <h1>購物車</h1>
    <div v-if="items.length === 0">購物車是空的</div>
    <table v-else>
      <thead>
        <tr>
          <th>商品</th>
          <th>單價</th>
          <th>數量</th>
          <th>小計</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in items" :key="item.product.id">
          <td>{{ item.product.name }}</td>
          <td>NT$ {{ item.product.price }}</td>
          <td>
            <input type="number" v-model.number="item.quantity" min="1" style="width: 60px;" />
          </td>
          <td>NT$ {{ item.product.price * item.quantity }}</td>
          <td><button @click="remove(item.product.id)">移除</button></td>
        </tr>
      </tbody>
    </table>
    <div v-if="items.length > 0" class="cart-total">
      <span>總計：NT$ {{ total }}</span>
      <button class="checkout">前往結帳</button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useCartStore } from '../stores/cart'
const cart = useCartStore()
const items = computed(() => cart.items)
const total = computed(() => items.value.reduce((sum, i) => sum + i.product.price * i.quantity, 0))
function remove(id: number) {
  cart.removeFromCart(id)
}
</script>

<style scoped>
.cart-view {
  max-width: 900px;
  margin: 2rem auto;
  padding: 2rem 1rem;
  background: #fff8e1;
  border-radius: 1rem;
  box-shadow: 0 2px 8px #e0c68a44;
}
table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 1.5rem;
}
th, td {
  padding: 0.5rem 0.75rem;
  text-align: center;
}
.cart-total {
  display: flex;
  justify-content: flex-end;
  align-items: center;
  gap: 1.5rem;
  font-size: 1.2rem;
}
.checkout {
  background: #b8860b;
  color: #fff;
  border: none;
  border-radius: 0.5rem;
  padding: 0.75rem 2rem;
  font-size: 1.1rem;
  cursor: pointer;
  transition: background 0.2s;
}
.checkout:hover {
  background: #a0761a;
}
</style> 