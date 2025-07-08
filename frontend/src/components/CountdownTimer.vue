<template>
  <div class="countdown-timer">
    <span v-if="!ended">活動倒數：{{ timeLeft }}</span>
    <span v-else>活動已結束</span>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
const props = defineProps<{ endTime: string }>()
const timeLeft = ref('')
const ended = ref(false)
let timer: any = null
function update() {
  const end = new Date(props.endTime).getTime()
  const now = Date.now()
  const diff = end - now
  if (diff <= 0) {
    ended.value = true
    timeLeft.value = '00:00:00'
    clearInterval(timer)
    return
  }
  const h = String(Math.floor(diff / 3600000)).padStart(2, '0')
  const m = String(Math.floor((diff % 3600000) / 60000)).padStart(2, '0')
  const s = String(Math.floor((diff % 60000) / 1000)).padStart(2, '0')
  timeLeft.value = `${h}:${m}:${s}`
}
onMounted(() => {
  update()
  timer = setInterval(update, 1000)
})
onUnmounted(() => clearInterval(timer))
</script>

<style scoped>
.countdown-timer {
  background: #fffbe8;
  color: #a67c00;
  border-radius: 1em;
  padding: 0.5em 1.2em;
  font-size: 1.1rem;
  font-weight: 600;
  box-shadow: 0 1px 4px #e0c68a22;
  display: inline-block;
  margin: 1em 0;
  letter-spacing: 1px;
}
</style> 