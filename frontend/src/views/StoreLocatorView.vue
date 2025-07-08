<template>
  <div class="store-locator">
    <h1>門市據點</h1>
    <div class="store-list">
      <div v-for="store in stores" :key="store.id" class="store-item">
        <div class="store-info">
          <div class="name">{{ store.name }}</div>
          <div class="address">{{ store.address }}</div>
          <div class="phone">{{ store.phone }}</div>
        </div>
        <a :href="store.map" target="_blank" class="map-link">查看地圖</a>
      </div>
    </div>
    <iframe
      class="gmap"
      width="100%"
      height="300"
      frameborder="0"
      style="border:0"
      :src="gmapUrl"
      allowfullscreen
    ></iframe>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'
const stores = ref([])
const gmapUrl = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3651.902...';
onMounted(async () => {
  try {
    const res = await axios.get('http://127.0.0.1:8000/api/v1/stores')
    if (res.data.success) {
      stores.value = res.data.stores
    }
  } catch {}
})
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
</style> 