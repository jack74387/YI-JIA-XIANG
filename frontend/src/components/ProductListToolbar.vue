<template>
  <div class="toolbar">
    <input v-model="search" placeholder="搜尋商品..." class="search" />
    <select v-model="sort" class="sort">
      <option value="relevance">相關度</option>
      <option value="price-asc">價格低到高</option>
      <option value="price-desc">價格高到低</option>
    </select>
    <button :class="{active: view==='grid'}" @click="$emit('change-view', 'grid')"><i class="icon-grid" /></button>
    <button :class="{active: view==='list'}" @click="$emit('change-view', 'list')"><i class="icon-list" /></button>
  </div>
</template>
<script setup lang="ts">
import { ref, watch } from 'vue'
const props = defineProps<{ view: string }>()
const emit = defineEmits(['search', 'sort', 'change-view'])
const search = ref('')
const sort = ref('relevance')
watch(search, v => emit('search', v))
watch(sort, v => emit('sort', v))
</script>
<style scoped>
.toolbar {
  display: flex;
  align-items: center;
  gap: 16px;
  background: #f9f6f1;
  border-radius: 14px;
  box-shadow: 0 2px 8px #e2d6c2;
  padding: 12px 18px;
  margin-bottom: 18px;
}
.search {
  flex: 1;
  border: none;
  border-radius: 8px;
  padding: 8px 12px;
  background: #ede3d0;
  color: #7c6a58;
  font-size: 1em;
}
.sort {
  border: none;
  border-radius: 8px;
  padding: 8px 12px;
  background: #ede3d0;
  color: #a67c52;
  font-size: 1em;
}
button {
  background: #fffbe9;
  border: none;
  border-radius: 8px;
  width: 38px;
  height: 38px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: background .2s, transform .2s;
}
button.active, button:hover {
  background: #f3e2c7;
  transform: scale(1.12);
}
</style> 