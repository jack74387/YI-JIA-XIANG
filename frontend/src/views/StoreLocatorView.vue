<template>
  <div class="store-locator">
    <h1>門市據點</h1>
    <div class="store-list">
      <div v-for="store in stores" :key="store.id" class="store-item" :class="{ active: selectedStore && selectedStore.id === store.id }" @click="selectStore(store)" style="cursor:pointer">
        <div class="store-info">
          <div class="name">{{ store.name }}</div>
          <div class="address">{{ store.address }}</div>
          <div class="phone">{{ store.phone }}</div>
        </div>
        <div class="flex gap-2 mt-2">
          <a :href="store.map_link" target="_blank" class="btn-main flex items-center">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5z"/></svg>
            導航至門市
          </a>
          <a :href="`tel:${store.phone}`" class="btn-sub flex items-center">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M22 16.92v3a2 2 0 0 1-2.18 2A19.72 19.72 0 0 1 3.08 5.18 2 2 0 0 1 5 3h3a2 2 0 0 1 2 1.72c.13.81.36 1.6.68 2.34a2 2 0 0 1-.45 2.11l-1.27 1.27a16 16 0 0 0 6.29 6.29l1.27-1.27a2 2 0 0 1 2.11-.45c.74.32 1.53.55 2.34.68A2 2 0 0 1 22 16.92z"/></svg>
            撥打電話
          </a>
        </div>
      </div>
    </div>
    <iframe
      class="gmap"
      width="100%"
      height="300"
      frameborder="0"
      style="border:0"
      :src="selectedStore?.map"
      allowfullscreen
    ></iframe>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'
interface Store {
  id: number
  name: string
  address: string
  phone: string
  map: string
  map_link: string // 新增
}
const stores = ref<Store[]>([])
const selectedStore = ref<Store | null>(null)

onMounted(async () => {
  try {
    const res = await axios.get('/api/v1/stores')
    if (res.data.success) {
      stores.value = res.data.stores
      selectedStore.value = stores.value[0] // 預設選第一家
    }
  } catch {}
})

function selectStore(store: Store) {
  selectedStore.value = store
}
</script>

<style scoped>
.store-locator {
  max-width: 600px;
  margin: 2rem auto;
  background: #fffbe8;
  border-radius: 1.2rem;
  box-shadow: 0 4px 24px #e0c68a33;
  padding: 2.5rem 2rem 2rem 2rem;
  font-family: 'Noto Sans TC', 'Segoe UI', Arial, sans-serif;
  color: #a67c00;
}
.store-locator h1 {
  text-align: center;
  font-size: 1.5rem;
  margin-bottom: 1.5rem;
  color: #b8860b;
}
.store-list {
  display: flex;
  flex-direction: column;
  gap: 1.2rem;
  margin-bottom: 1.5rem;
}
.store-item {
  background: #fffdfa;
  border-radius: 0.7rem;
  box-shadow: 0 1px 4px #e0c68a22;
  padding: 1rem 1.2rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  transition: box-shadow 0.2s;
}
.store-item:hover {
  box-shadow: 0 4px 16px #b8860b33;
}
.store-item.active {
  border: 2px solid #b8860b;
  box-shadow: 0 4px 16px #b8860b33;
}
.store-info .name {
  font-size: 1.08rem;
  font-weight: 600;
  color: #b8860b;
}
.store-info .address, .store-info .phone {
  font-size: 0.98rem;
  color: #a67c00;
}
.map-link {
  color: #fff;
  background: #b8860b;
  border-radius: 1em;
  padding: 0.4em 1em;
  text-decoration: none;
  font-size: 0.98rem;
  font-weight: 600;
  transition: background 0.2s;
}
.map-link:hover {
  background: #a67c00;
}
.gmap {
  border-radius: 1em;
  margin-top: 1.5rem;
  box-shadow: 0 1px 4px #e0c68a22;
}
.btn-main {
  background: #b8860b;
  color: #fff;
  border-radius: 2em;
  padding: 0.6em 1.6em;
  font-size: 1rem;
  font-weight: 700;
  box-shadow: 0 2px 8px #e0c68a22;
  border: none;
  transition: background 0.2s, color 0.2s, transform 0.2s;
  display: flex;
  align-items: center;
}
.btn-main:hover {
  background: #a67c00;
  color: #fffbe8;
  transform: scale(1.04);
}
.btn-sub {
  background: #fff;
  color: #a67c00;
  border: 1.5px solid #e0c68a;
  border-radius: 2em;
  padding: 0.6em 1.6em;
  font-size: 1rem;
  font-weight: 700;
  box-shadow: 0 2px 8px #e0c68a22;
  transition: background 0.2s, color 0.2s, transform 0.2s;
  display: flex;
  align-items: center;
}
.btn-sub:hover {
  background: #ffe9b2;
  color: #b8860b;
  transform: scale(1.04);
}
</style> 