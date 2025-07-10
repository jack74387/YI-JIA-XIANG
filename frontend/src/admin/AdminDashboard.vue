<template>
  <div class="flex min-h-screen">
    <AdminSidebar />
    <main class="flex-1 p-10 bg-gray-50">
      <h1 class="text-3xl font-bold mb-6">管理員儀表板</h1>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded shadow p-6">
          <div class="text-gray-500 mb-2">商品數量</div>
          <div class="text-2xl font-bold">--</div>
        </div>
        <div class="bg-white rounded shadow p-6">
          <div class="text-gray-500 mb-2">訂單數量</div>
          <div class="text-2xl font-bold">--</div>
        </div>
        <div class="bg-white rounded shadow p-6">
          <div class="text-gray-500 mb-2">會員數量</div>
          <div class="text-2xl font-bold">--</div>
        </div>
      </div>
      <div class="mt-10 text-gray-400">（你可以在左側選單切換管理功能）</div>
    </main>
  </div>
</template>
<script setup>
import AdminSidebar from './AdminSidebar.vue'
</script>

<style scoped>
.btn-admin {
  @apply bg-amber-600 text-white font-semibold py-2 px-6 rounded hover:bg-amber-700 transition-colors;
}
</style> 