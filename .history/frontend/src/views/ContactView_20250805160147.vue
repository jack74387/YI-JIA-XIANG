<template>
  <div class="contact-us max-w-2xl mx-auto py-10 px-4">
    <h1 class="text-3xl font-bold text-amber-700 mb-6">聯絡我們</h1>
    <section class="mb-8">
      <h2 class="text-xl font-semibold text-amber-600 mb-2">門市資訊</h2>
      <ul class="text-gray-700">
        <li>地址：台東縣台東市廣東路265號</li>
        <li>電話：(089) 357-996</li>
        <li>營業時間：09:00-20:00</li>
        <li>
          <a href="https://www.facebook.com/yiijiaxiang/?locale=zh_TW" target="_blank" class="text-blue-600 hover:underline">Facebook 粉絲專頁</a>
        </li>
      </ul>
      <div class="mt-4 flex justify-center">
        <img src="/images/contact-placeholder.jpg" alt="門市外觀" class="w-64 h-40 object-cover rounded shadow" />
      </div>
    </section>
    <section class="mb-8">
      <h2 class="text-xl font-semibold text-amber-600 mb-2">線上聯絡表單</h2>
      <form @submit.prevent="submitForm" class="space-y-4">
        <input 
          v-model="form.name"
          type="text" 
          placeholder="您的姓名" 
          class="w-full border rounded px-3 py-2" 
          required 
        />
        <input 
          v-model="form.email"
          type="email" 
          placeholder="您的Email" 
          class="w-full border rounded px-3 py-2" 
          required 
        />
        <textarea 
          v-model="form.message"
          placeholder="請輸入您的訊息" 
          class="w-full border rounded px-3 py-2 h-32" 
          required
        ></textarea>
        <button 
          type="submit" 
          :disabled="isSubmitting"
          class="bg-amber-600 text-white px-6 py-2 rounded hover:bg-amber-700 disabled:bg-gray-400 disabled:cursor-not-allowed"
        >
          {{ isSubmitting ? '送出中...' : '送出' }}
        </button>
      </form>
      
      <!-- 成功訊息 -->
      <div v-if="showSuccess" class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
        ✅ 訊息已成功送出！我們會盡快回覆您。
      </div>
      
      <!-- 錯誤訊息 -->
      <div v-if="showError" class="mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
        ❌ 送出失敗，請稍後再試或直接致電 (089) 357-996。
      </div>
    </section>
    <section>
      <h2 class="text-xl font-semibold text-amber-600 mb-2">地圖位置</h2>
      <iframe
        class="w-full rounded"
        height="250"
        frameborder="0"
        style="border:0"
        src="https://www.google.com/maps?q=台東縣台東市廣東路269號&output=embed"
        allowfullscreen
      ></iframe>
    </section>
  </div>
</template>

<script setup lang="ts">
// 無需邏輯
</script>

<style scoped>
.contact-us img {
  background: #f3e9d2;
  object-fit: cover;
}
</style> 