<template>
  <div class="product-card">
    <div class="image-wrapper">
      <img :src="product.image || placeholder" alt="商品圖片" />
      <span v-if="product.has_discount" class="badge">{{ product.discount_percentage }}% OFF</span>
    </div>
    <div class="info">
      <h3>{{ product.name }}</h3>
      <p class="desc">{{ product.description || '商品描述佔位' }}</p>
      <div class="price-row">
        <span v-if="product.price_large" class="price">NT${{ product.price_large }}</span>
        <span v-if="product.price_small" class="price small">/ 小包 NT${{ product.price_small }}</span>
        <span v-if="product.price && !product.price_large" class="price">NT${{ product.price }}</span>
        <span v-if="product.has_discount" class="old">NT${{ product.original_price }}</span>
      </div>
      <div class="actions">
        <button 
          v-if="product.can_add_to_cart !== false" 
          class="cart" 
          @click="$emit('add-to-cart', product)"
        >
          <i class="icon-cart"></i>
        </button>
        <button 
          v-else 
          class="cart disabled" 
          title="此商品僅供參考，無法加入購物車"
        >
          <i class="icon-info"></i>
        </button>
        <button class="fav" @click="toggleFav"><i :class="isFav ? 'icon-heart-fill' : 'icon-heart'" /></button>
        <button class="share" @click="showShare = !showShare"><i class="icon-share" /></button>
        <div v-if="showShare" class="share-popup">
          <button><i class="icon-line" /></button>
          <button><i class="icon-fb" /></button>
          <button><i class="icon-link" /></button>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import { ref } from 'vue'
const props = defineProps<{ product: any }>()
const placeholder = 'https://via.placeholder.com/300x300?text=商品圖片'
const showShare = ref(false)
const isFav = ref(false)
function toggleFav() { isFav.value = !isFav.value }
</script>
<style scoped>
.product-card {
  background: #f9f6f1;
  border-radius: 18px;
  box-shadow: 0 2px 12px #e2d6c2;
  overflow: hidden;
  transition: box-shadow .2s, transform .2s;
  display: flex;
  flex-direction: column;
  position: relative;
  min-width: 240px;
  max-width: 320px;
  margin: 0 auto;
}
.product-card:hover {
  box-shadow: 0 6px 24px #d6c3a1;
  transform: translateY(-4px) scale(1.03);
}
.image-wrapper {
  position: relative;
  width: 100%;
  height: 220px;
  background: #ede3d0;
  display: flex;
  align-items: center;
  justify-content: center;
}
.image-wrapper img {
  width: 80%;
  height: 80%;
  object-fit: contain;
  border-radius: 12px;
}
.badge {
  position: absolute;
  top: 12px;
  left: 12px;
  background: #c49a6c;
  color: #fff;
  border-radius: 8px;
  padding: 2px 10px;
  font-size: 0.9em;
  font-weight: bold;
}
.info {
  padding: 18px 16px 12px 16px;
  flex: 1;
  display: flex;
  flex-direction: column;
}
.info h3 {
  font-size: 1.2em;
  color: #a67c52;
  margin: 0 0 6px 0;
  font-weight: 700;
}
.desc {
  color: #7c6a58;
  font-size: 0.98em;
  margin-bottom: 10px;
  min-height: 2.5em;
  line-height: 1.3;
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
}
.price-row {
  display: flex;
  align-items: baseline;
  gap: 8px;
  margin-bottom: 10px;
}
.price {
  color: #b85c38;
  font-size: 1.15em;
  font-weight: bold;
}
.price.small {
  font-size: 0.95em;
  color: #a67c52;
}
.old {
  color: #b8b8b8;
  text-decoration: line-through;
  font-size: 0.95em;
}
.actions {
  display: flex;
  gap: 10px;
  margin-top: 8px;
}
.actions button {
  background: #fffbe9;
  border: none;
  border-radius: 50%;
  width: 38px;
  height: 38px;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 1px 4px #e2d6c2;
  cursor: pointer;
  transition: background .2s, transform .2s;
}
.actions button:hover {
  background: #f3e2c7;
  transform: scale(1.12);
}
.actions button.disabled {
  background: #f0f0f0;
  color: #999;
  cursor: not-allowed;
}
.actions button.disabled:hover {
  background: #f0f0f0;
  transform: none;
}
.share-popup {
  position: absolute;
  top: -48px;
  right: 0;
  background: #fffbe9;
  border-radius: 12px;
  box-shadow: 0 2px 8px #e2d6c2;
  display: flex;
  gap: 6px;
  padding: 6px 10px;
  z-index: 10;
}
</style> 