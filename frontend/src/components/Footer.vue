<template>
  <footer class="bg-[#232f3e] text-gray-100 pt-10 pb-4 px-4">
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-8 text-sm">
      <!-- 公司簡介 -->
      <div>
        <div class="text-2xl font-bold mb-2">一佳香</div>
        <div class="leading-relaxed text-gray-200">
          專業的肉乾製造商，提供高品質的肉乾產品。
        </div>
      </div>
      <!-- 關於我們 -->
      <div>
        <div class="font-bold text-lg mb-2">關於我們</div>
        <ul class="space-y-1">
          <li><router-link to="/brand-story" class="hover:underline">品牌故事</router-link></li>
          <li><router-link to="/artisan-craft" class="hover:underline">職人工藝</router-link></li>
          <li><router-link to="/store-locator" class="hover:underline">門市據點</router-link></li>
          <li><router-link to="/contact" class="hover:underline">聯絡我們</router-link></li>
        </ul>
      </div>
      <!-- 客戶服務 -->
      <div>
        <div class="font-bold text-lg mb-2">客戶服務</div>
        <ul class="space-y-1">
          <li><router-link to="/shopping-notice" class="hover:underline">購物須知</router-link></li>
          <li><router-link to="/return-policy" class="hover:underline">退換貨政策</router-link></li>
          <li><router-link to="/delivery-info" class="hover:underline">配送說明</router-link></li>
          <li><router-link to="/faq" class="hover:underline">常見問題</router-link></li>
        </ul>
      </div>
      <!-- 聯絡資訊 -->
      <div>
        <div class="font-bold text-lg mb-2">聯絡資訊</div>
        <div class="space-y-1 text-gray-200">
          <div>電話：(089) 357-996</div>
          <div>信箱：yijiaxiang88@gmail.com</div>
          <div>地址：台東縣台東市廣東路269號</div>
        </div>
      </div>
    </div>
    <div class="border-t border-gray-700 mt-8 pt-4 text-center text-gray-400 text-xs">
      © 2025 一佳香. All rights reserved.
    </div>
  </footer>
</template>

<script setup lang="ts">
import { RouterLink } from 'vue-router'
</script>

<style scoped>
footer {
  font-family: 'Noto Sans TC', 'Inter', Arial, sans-serif;
}
@media (max-width: 768px) {
  footer .grid {
    grid-template-columns: 1fr !important;
    text-align: left;
  }
}
</style> 