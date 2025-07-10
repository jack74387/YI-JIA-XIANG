<template>
  <aside class="w-56 bg-amber-50 h-full p-6 shadow flex flex-col">
    <div class="font-bold text-xl mb-8 text-amber-700">後台管理</div>
    <router-link to="/admin" class="mb-4" active-class="font-bold text-amber-700">儀表板</router-link>
    <router-link to="/admin/products" class="mb-4" active-class="font-bold text-amber-700">商品管理</router-link>
    <router-link to="/admin/orders" class="mb-4" active-class="font-bold text-amber-700">訂單管理</router-link>
    <router-link to="/admin/members" class="mb-4" active-class="font-bold text-amber-700">會員管理</router-link>
    <router-link to="/admin/coupons" class="mb-4" active-class="font-bold text-amber-700">優惠券管理</router-link>
    <button @click="logout" class="mt-auto text-red-600 hover:underline">登出</button>
  </aside>
</template>
<script setup lang="ts">
import { useRouter } from 'vue-router'
const router = useRouter()
function logout() {
  localStorage.removeItem('admin_token')
  router.push('/admin/login')
}
</script>
<style scoped>
aside { min-height: 100vh; }
</style> 