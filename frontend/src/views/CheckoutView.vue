<template>
  <div class="checkout-view">
    <h1>結帳</h1>
    <div v-if="items.length === 0">購物車是空的</div>
    <form v-else @submit.prevent="submitOrder">
      <table>
        <thead>
          <tr>
            <th>商品</th>
            <th>單價</th>
            <th>數量</th>
            <th>小計</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in items" :key="item.product.id">
            <td>{{ item.product.name }}</td>
            <td>NT$ {{ item.product.price }}</td>
            <td>{{ item.quantity }}</td>
            <td>NT$ {{ item.product.price * item.quantity }}</td>
          </tr>
        </tbody>
      </table>
      <div class="cart-total">總計：NT$ {{ total }}</div>
      <div class="form-section">
        <label>收件人姓名 <input v-model="form.name" required /></label>
        <label>電話 <input v-model="form.phone" required /></label>
        <label>地址 <input v-model="form.address" required /></label>
        <label>配送方式
          <select v-model="form.shipping_method" required>
            <option value="宅配">宅配</option>
            <option value="超商取貨">超商取貨</option>
          </select>
        </label>
        <label>付款方式
          <select v-model="form.payment_method" required>
            <option value="貨到付款">貨到付款</option>
            <option value="信用卡">信用卡</option>
            <option value="LINE Pay">LINE Pay</option>
          </select>
        </label>
      </div>
      <button class="submit-order" type="submit">送出訂單</button>
    </form>
  </div>
</template>

<script setup lang="ts">
import { reactive, computed } from 'vue'
import { useCartStore } from '../stores/cart'
const cart = useCartStore()
const items = computed(() => cart.items)
const total = computed(() => items.value.reduce((sum, i) => sum + i.product.price * i.quantity, 0))
const form = reactive({
  name: '',
  phone: '',
  address: '',
  shipping_method: '宅配',
  payment_method: '貨到付款',
})
function submitOrder() {
  // 這裡可串接 API 建立訂單
  alert('訂單已送出！')
  cart.clearCart()
}
</script>

<style scoped>
.checkout-view {
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
  font-size: 1.2rem;
  margin-bottom: 1.5rem;
  text-align: right;
}
.form-section {
  display: flex;
  flex-wrap: wrap;
  gap: 1.5rem;
  margin-bottom: 1.5rem;
}
.form-section label {
  flex: 1 1 220px;
  display: flex;
  flex-direction: column;
  font-size: 1rem;
  gap: 0.25rem;
}
.submit-order {
  background: #b8860b;
  color: #fff;
  border: none;
  border-radius: 0.5rem;
  padding: 0.75rem 2rem;
  font-size: 1.1rem;
  cursor: pointer;
  transition: background 0.2s;
}
.submit-order:hover {
  background: #a0761a;
}
</style> 