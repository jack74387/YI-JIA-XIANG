<template>
  <div class="tabs mt-8">
    <button v-for="tab in tabs" :key="tab" @click="active=tab" :class="{active:active===tab}">{{ tab }}</button>
    <div v-if="active==='商品說明'" class="mt-4">{{ product.description }}</div>
    <div v-if="active==='商品規格'" class="mt-4"><pre>{{ product.spec }}</pre></div>
    <div v-if="active==='相關推薦'" class="mt-4">
      <div v-for="id in product.recommend_ids" :key="id">推薦商品 #{{ id }}</div>
    </div>
  </div>
</template>
<script setup lang="ts">
import { ref } from 'vue'
const props = defineProps<{ product: any }>()
const tabs = ['商品說明', '商品規格', '相關推薦']
const active = ref('商品說明')
</script>
<style scoped>
.tabs button { margin-right: 1rem; padding: 0.5rem 1.2rem; border: none; background: #f5f5f5; border-radius: 6px 6px 0 0; cursor: pointer; }
.tabs button.active { background: #b8860b; color: #fff; }
</style> 