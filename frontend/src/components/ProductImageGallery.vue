<template>
  <div class="gallery">
    <div class="main-img-container main-img-nav-wrap">
      <img v-if="Array.isArray(props.images) && props.images.length > 0" :src="getImageUrl(props.images[activeIndex])" class="main-img" :class="status === 'notification' ? 'opacity-50 grayscale' : ''" @click="openLightbox(activeIndex)" style="cursor: zoom-in;" />
    </div>
    <div v-if="Array.isArray(props.images) && props.images.length > 0" class="thumbs-bar thumbs-bar-abs">
      <button v-if="thumbStart > 0" @click="prevThumbs" class="thumb-nav modern-nav nav-bar nav-bar-abs nav-bar-left" aria-label="上一張">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
          <path d="M15 6l-6 6 6 6" stroke="#b85c38" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </button>
      <div class="thumbs-inner">
        <img
          v-for="(img, i) in visibleThumbs"
          :key="thumbStart + i"
          :src="getImageUrl(img)"
          class="thumb"
          :class="[thumbStart + i === activeIndex ? 'active' : '', status === 'notification' ? 'opacity-50 grayscale' : '']"
          @mouseenter="selectThumb(thumbStart + i)"
          @click="openLightbox(thumbStart + i)"
          style="cursor: zoom-in;"
        />
      </div>
      <button v-if="props.images && thumbStart + THUMB_WINDOW < props.images.length" @click="nextThumbs" class="thumb-nav modern-nav nav-bar nav-bar-abs nav-bar-right" aria-label="下一張">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
          <path d="M9 6l6 6-6 6" stroke="#b85c38" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </button>
    </div>
    <!-- Lightbox -->
    <div v-if="showLightbox" class="lightbox-overlay" @click.self="closeLightbox">
      <button class="lightbox-close modern" @click="closeLightbox" aria-label="關閉">&times;</button>
      <div class="lightbox-content-row modern">
        <div class="lightbox-main-img-wrap modern">
          <button v-if="lightboxIndex > 0" class="lightbox-arrow left modern" @click.stop="prevLightbox" aria-label="上一張">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none"><path d="M15 6l-6 6 6 6" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </button>
          <img :src="getImageUrl(props.images?.[lightboxIndex])" class="lightbox-img modern" />
          <button v-if="props.images && lightboxIndex < props.images.length - 1" class="lightbox-arrow right modern" @click.stop="nextLightbox" aria-label="下一張">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none"><path d="M9 6l6 6-6 6" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </button>
        </div>
        <div class="lightbox-side-info-right modern">
          <div class="lightbox-title modern">{{ props.title }}</div>
          <div class="lightbox-thumb-rows modern">
            <div v-for="(row, rowIdx) in thumbRows" :key="rowIdx" class="lightbox-thumb-row modern">
              <img
                v-for="(img, i) in row"
                :key="i + rowIdx * 3"
                :src="getImageUrl(img)"
                class="lightbox-thumb modern"
                :class="(rowIdx * 3 + i) === lightboxIndex ? 'active' : ''"
                @click.stop="lightboxIndex = rowIdx * 3 + i"
                :alt="'縮圖' + (rowIdx * 3 + i + 1)"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue'
const props = defineProps<{ images: string[]|undefined, weight: string, status?: string, title?: string }>()
const activeIndex = ref(0)

// 圖片路徑處理
function getImageUrl(imagePath: string | undefined): string | undefined {
  if (!imagePath) return undefined
  if (imagePath.startsWith('http')) return imagePath
  if (imagePath.startsWith('/storage')) return import.meta.env.VITE_API_BASE_URL + imagePath
  if (imagePath.startsWith('/')) return import.meta.env.VITE_API_BASE_URL + imagePath
  return imagePath
}

// 縮圖視窗邏輯
const thumbStart = ref(0)
const THUMB_WINDOW = 5
const visibleThumbs = computed(() => {
  if (!Array.isArray(props.images)) return []
  return props.images.slice(thumbStart.value, thumbStart.value + THUMB_WINDOW)
})
function prevThumbs() {
  if (thumbStart.value > 0) thumbStart.value--
}
function nextThumbs() {
  if (props.images && thumbStart.value + THUMB_WINDOW < props.images.length) thumbStart.value++
}
function selectThumb(i: number) {
  activeIndex.value = i
  if (i < thumbStart.value) thumbStart.value = i
  else if (i >= thumbStart.value + THUMB_WINDOW) thumbStart.value = i - THUMB_WINDOW + 1
}

// Lightbox
const showLightbox = ref(false)
const lightboxIndex = ref(0)
function openLightbox(idx: number) {
  lightboxIndex.value = idx
  showLightbox.value = true
}
function closeLightbox() {
  showLightbox.value = false
}
function prevLightbox() {
  if (lightboxIndex.value > 0) lightboxIndex.value--
}
function nextLightbox() {
  if (props.images && lightboxIndex.value < props.images.length - 1) lightboxIndex.value++
}
function handleKey(e: KeyboardEvent) {
  if (!showLightbox.value) return
  if (e.key === 'Escape') closeLightbox()
  if (e.key === 'ArrowLeft') prevLightbox()
  if (e.key === 'ArrowRight') nextLightbox()
}
onMounted(() => { window.addEventListener('keydown', handleKey) })
onUnmounted(() => { window.removeEventListener('keydown', handleKey) })

// Lightbox 縮圖分行
const thumbRows = computed(() => {
  const imgs = props.images ?? []
  const rows = []
  for (let i = 0; i < imgs.length; i += 3) {
    rows.push(imgs.slice(i, i + 3))
  }
  return rows
})
</script>
<style scoped>
.main-img-container {
  width: 320px;
  height: 320px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #f9f6f1;
  border-radius: 8px;
  overflow: hidden;
}
.main-img-nav-wrap { position: relative; }
.main-img { width: 100%; height: 100%; object-fit: contain; border-radius: 8px; background: #f9f6f1; }
.thumbs-bar {
  display: flex;
  align-items: center;
  justify-content: center;
  margin-top: 0.5rem;
  width: 100%;
  max-width: 400px;
  margin-left: auto;
  margin-right: auto;
}
.thumbs-bar-abs {
  position: relative;
}
.thumbs-inner {
  display: flex;
  gap: 8px;
  justify-content: center;
  width: 340px;
}
.thumb-nav.nav-bar-abs {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  z-index: 2;
}
.thumb-nav.nav-bar-left { left: -28px; }
.thumb-nav.nav-bar-right { right: -28px; }
.thumb-nav.modern-nav.nav-bar {
  background: #fff;
  border: none;
  box-shadow: 0 2px 8px rgba(184,92,56,0.10), 0 1.5px 4px #e2d6c2;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  font-size: 1.2em;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: box-shadow 0.2s, background 0.2s, transform 0.15s;
  outline: none;
  margin: 0 8px;
}
.thumb-nav.modern-nav.nav-bar:hover {
  background: #f3e2c7;
  box-shadow: 0 4px 16px rgba(184,92,56,0.18);
  transform: scale(1.08);
}
.thumb-nav.modern-nav.nav-bar svg {
  display: block;
}
.thumb { width: 60px; height: 60px; object-fit: cover; border-radius: 6px; cursor: pointer; border: 2px solid transparent; background: #f9f6f1; display: block; }
.thumb.active { border-color: #b8860b; }
.opacity-50 { opacity: 0.5; }
.grayscale { filter: grayscale(1); }
/* Lightbox Overlay */
.lightbox-overlay {
  position: fixed; left: 0; top: 0; width: 100vw; height: 100vh;
  background: rgba(0,0,0,0.88); z-index: 1000;
  display: flex; align-items: center; justify-content: center;
  transition: background 0.2s;
}
.lightbox-content-row {
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: center;
  background: none;
  min-width: 0;
  min-height: 0;
  gap: 32px;
}
.lightbox-main-img-wrap {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 70vw;
  max-width: 700px;
  height: 70vh;
  max-height: 700px;
  background: #fff;
  border-radius: 14px;
  box-shadow: 0 4px 32px #0007;
  overflow: hidden;
  position: relative;
}
.lightbox-side-info-right {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  min-width: 220px;
  max-width: 320px;
  margin-left: 24px;
  gap: 18px;
}
.lightbox-title {
  font-size: 1.25rem;
  font-weight: bold;
  color: #fff;
  margin-bottom: 0;
  text-align: left;
  word-break: break-all;
}
.lightbox-thumb-rows {
  display: flex;
  flex-direction: column;
  gap: 10px;
  align-items: flex-start;
}
.lightbox-thumb-row {
  display: flex;
  flex-direction: row;
  gap: 10px;
}
.lightbox-thumb {
  width: 64px; height: 64px; object-fit: cover; border-radius: 8px; cursor: pointer; border: 2.5px solid transparent; background: #f9f6f1; display: block;
  transition: border 0.2s, box-shadow 0.2s;
}
.lightbox-thumb.active {
  border-color: #b85c38;
  box-shadow: 0 2px 8px #b85c3840;
}
.lightbox-thumb:hover { border-color: #b85c38; }
.lightbox-img {
  max-width: 100%;
  max-height: 100%;
  object-fit: contain;
  border-radius: 14px;
  background: #fff;
  transition: box-shadow 0.2s;
  margin: 0 48px;
}
.lightbox-arrow {
  position: absolute; top: 50%; transform: translateY(-50%);
  background: rgba(0,0,0,0.18); border: none; border-radius: 50%; cursor: pointer; z-index: 1001;
  width: 56px; height: 56px; display: flex; align-items: center; justify-content: center;
  transition: background 0.2s, transform 0.15s;
  box-shadow: 0 2px 8px #0004;
}
.lightbox-arrow.left { left: 0; }
.lightbox-arrow.right { right: 0; }
.lightbox-arrow:hover { background: #b85c38; transform: scale(1.08); }
.lightbox-close.modern {
  position: absolute;
  top: 24px;
  right: 38px;
  font-size: 2.6rem;
  color: #fff;
  background: rgba(0,0,0,0.18);
  border: none;
  border-radius: 50%;
  width: 54px;
  height: 54px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  z-index: 1100;
  transition: background 0.18s, color 0.18s, transform 0.15s;
  box-shadow: 0 2px 12px #0002;
}
.lightbox-close.modern:hover {
  background: #b85c38;
  color: #fffbe8;
  transform: scale(1.12);
}
@media (max-width: 900px) {
  .lightbox-main-img-wrap { width: 90vw; height: 50vw; max-width: 98vw; max-height: 60vw; }
  .lightbox-side-info-right { min-width: 120px; max-width: 180px; }
  .lightbox-thumb { width: 48px; height: 48px; }
}
@media (max-width: 600px) {
  .lightbox-content-row { flex-direction: column; gap: 12px; }
  .lightbox-side-info-right { align-items: center; margin-left: 0; }
  .lightbox-title { text-align: center; }
  .lightbox-thumb-rows { align-items: center; }
  .lightbox-main-img-wrap { width: 98vw; height: 48vw; max-width: 98vw; max-height: 60vw; }
  .lightbox-img { margin: 0 12px; }
}
.lightbox-content-row.modern {
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: center;
  background: none;
  min-width: 0;
  min-height: 0;
  gap: 56px;
  padding: 32px 32px 32px 48px;
}
.lightbox-main-img-wrap.modern {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 520px;
  height: 520px;
  background: #fff;
  border-radius: 24px;
  box-shadow: 0 6px 32px #0002, 0 1.5px 4px #e2d6c2;
  overflow: hidden;
  position: relative;
  margin-right: 32px;
}
.lightbox-img.modern {
  max-width: 92%;
  max-height: 92%;
  object-fit: contain;
  border-radius: 18px;
  background: #fff;
  box-shadow: 0 2px 12px #0001;
  margin: 0 auto;
  display: block;
}
.lightbox-side-info-right.modern {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  min-width: 240px;
  max-width: 320px;
  gap: 28px;
  padding: 0 8px;
}
.lightbox-title.modern {
  font-size: 1.6rem;
  font-weight: 900;
  color: #fff;
  margin-bottom: 0;
  text-align: left;
  word-break: break-all;
  letter-spacing: 0.02em;
  text-shadow: 0 2px 12px #0008, 0 1.5px 4px #b85c3840;
  padding: 0 0 8px 0;
}
.lightbox-thumb-rows.modern {
  display: flex;
  flex-direction: column;
  gap: 16px;
  align-items: flex-start;
  background: rgba(255,255,255,0.08);
  border-radius: 18px;
  box-shadow: 0 2px 12px #0001;
  padding: 18px 8px 18px 8px;
}
.lightbox-thumb-row.modern {
  display: flex;
  flex-direction: row;
  gap: 16px;
}
.lightbox-thumb.modern {
  width: 68px; height: 68px; object-fit: cover; border-radius: 12px; cursor: pointer; border: 2.5px solid transparent; background: #f9f6f1; display: block;
  transition: border 0.2s, box-shadow 0.2s, transform 0.15s;
  box-shadow: 0 1.5px 6px #0001;
}
.lightbox-thumb.modern.active {
  border-color: #b85c38;
  box-shadow: 0 2px 12px #b85c3840;
  transform: scale(1.08);
}
.lightbox-thumb.modern:hover { border-color: #b85c38; transform: scale(1.06); }
.lightbox-arrow.modern {
  background: rgba(0,0,0,0.13); border: none; border-radius: 50%; cursor: pointer; z-index: 1001;
  width: 64px; height: 64px; display: flex; align-items: center; justify-content: center;
  transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
  box-shadow: 0 2px 12px #0002;
  opacity: 0.85;
}
.lightbox-arrow.modern.left { left: 18px; }
.lightbox-arrow.modern.right { right: 18px; }
.lightbox-arrow.modern:hover {
  background: #b85c38;
  transform: scale(1.13);
  opacity: 1;
}
@media (max-width: 900px) {
  .lightbox-main-img-wrap.modern { width: 90vw; height: 50vw; max-width: 98vw; max-height: 60vw; }
  .lightbox-side-info-right.modern { min-width: 120px; max-width: 180px; }
  .lightbox-thumb.modern { width: 48px; height: 48px; }
  .lightbox-content-row.modern { gap: 18px; padding: 18px 4px 18px 4px; }
}
@media (max-width: 600px) {
  .lightbox-content-row.modern { flex-direction: column; gap: 12px; padding: 8px 0; }
  .lightbox-side-info-right.modern { align-items: center; margin-left: 0; }
  .lightbox-title.modern { text-align: center; }
  .lightbox-thumb-rows.modern { align-items: center; }
  .lightbox-main-img-wrap.modern { width: 98vw; height: 48vw; max-width: 98vw; max-height: 60vw; margin-right: 0; }
  .lightbox-img.modern { margin: 0 12px; }
}
</style> 