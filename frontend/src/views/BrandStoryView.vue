<template>
  <div class="brand-story-page max-w-3xl mx-auto py-10 px-4">
    <h1 class="text-3xl font-bold text-amber-700 mb-6">品牌故事</h1>
    <div class="mb-6 text-lg text-gray-800 leading-relaxed">
      一佳香，源自台灣台東，傳承數十載的肉乾工藝，堅持選用在地優質原料，結合現代化設備與傳統手藝，打造出最純粹、最美味的肉乾產品。<br>
      我們相信，好的肉乾來自對品質的堅持與對顧客的用心，讓每一口都能品嚐到家的溫度與職人的精神。
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
      <img src="/images/about-placeholder.jpg" alt="品牌故事1" class="rounded-xl shadow-md w-full h-56 object-cover">
      <img src="/images/about-placeholder2.jpg" alt="品牌故事2" class="rounded-xl shadow-md w-full h-56 object-cover">
    </div>
    <div class="text-gray-700">
      <h2 class="text-xl font-semibold mb-2">品牌理念</h2>
      <p class="mb-4">我們堅持「品質第一、誠信經營」，每一批肉乾都經過嚴格把關，從原料、製程到包裝，層層檢驗，確保送到顧客手中的每一份產品都能安心享用。</p>
      <h2 class="text-xl font-semibold mb-2">歷史沿革</h2>
      <p>自創立以來，一佳香始終秉持著對美味的追求與對顧客的承諾，從小型家庭作坊成長為台灣知名的肉乾品牌，感謝每一位顧客的支持與信賴。</p>
    </div>
  </div>
</template>

<style scoped>
.brand-story-page {
  font-family: 'Noto Sans TC', 'Inter', Arial, sans-serif;
}
@media (max-width: 600px) {
  .brand-story-page {
    padding: 1rem 0.2rem;
  }
  .brand-story-page h1 {
    font-size: 2rem;
  }
  .brand-story-page img {
    height: 140px !important;
  }
}
</style> 