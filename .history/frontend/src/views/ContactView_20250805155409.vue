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
      <form class="space-y-4">
        <input type="text" placeholder="您的姓名" class="w-full border rounded px-3 py-2" />
        <input type="email" placeholder="您的Email" class="w-full border rounded px-3 py-2" />
        <textarea placeholder="請輸入您的訊息" class="w-full border rounded px-3 py-2"></textarea>
        <button type="submit" class="bg-amber-600 text-white px-6 py-2 rounded hover:bg-amber-700">送出</button>
      </form>
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