<template>
  <aside class="category-sidebar">
    <h4>商品分類</h4>
    <ul>
      <li :class="{active: selected === null}" @click="$emit('select', null)">全部分類</li>
      <li v-for="cat in categories" :key="cat.id" :class="{active: selected === cat.id}" @click="$emit('select', cat.id)">
        {{ cat.name }} <span v-if="cat.count" class="count">({{ cat.count }})</span>
      </li>
    </ul>
  </aside>
</template>
<script setup lang="ts">
const props = defineProps<{ categories: any[], selected: number|null }>()
</script>
<style scoped>
.category-sidebar {
  background: #f9f6f1;
  border-radius: 18px;
  box-shadow: 0 2px 12px #e2d6c2;
  padding: 24px 18px 18px 18px;
  min-width: 200px;
  max-width: 260px;
}
h4 {
  color: #a67c52;
  font-size: 1.1em;
  margin-bottom: 18px;
  font-weight: 700;
}
ul {
  list-style: none;
  padding: 0;
  margin: 0;
}
li {
  padding: 8px 12px;
  border-radius: 10px;
  color: #7c6a58;
  cursor: pointer;
  margin-bottom: 4px;
  transition: background .2s, color .2s;
}
li.active, li:hover {
  background: #ede3d0;
  color: #b85c38;
  font-weight: bold;
}
.count {
  color: #b8b8b8;
  font-size: 0.95em;
  margin-left: 4px;
}
.category-sidebar, .sidebar, .sidebar-category, .sidebar-list, .sidebar-item {
  font-size: 0.95em;
}
@media (max-width: 600px) {
  .category-sidebar, .sidebar, .sidebar-category, .sidebar-list, .sidebar-item {
    font-size: 0.85em;
  }
}
</style> 