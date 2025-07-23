<template>
  <router-link :to="to" class="sidebar-link" active-class="active" @click.native="$emit('close')">
    <span class="icon">
      <component :is="iconComponent" class="w-6 h-6" />
    </span>
    <span class="label">{{ label }}</span>
  </router-link>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { HomeIcon, ShoppingBagIcon, SparklesIcon, ChatBubbleLeftEllipsisIcon, PhoneIcon, MapIcon, GiftIcon, UsersIcon, ChatBubbleLeftRightIcon } from '@heroicons/vue/24/outline'

const props = defineProps<{ icon: string, to: string, label: string }>()

const iconMap: Record<string, any> = {
  home: HomeIcon,
  'shopping-bag': ShoppingBagIcon,
  sparkles: SparklesIcon,
  'chat-bubble-left-ellipsis': ChatBubbleLeftEllipsisIcon,
  phone: PhoneIcon,
  map: MapIcon,
  gift: GiftIcon,
  users: UsersIcon,
  'chat-bubble-left-right': ChatBubbleLeftRightIcon,
}
const iconComponent = computed(() => iconMap[props.icon] || HomeIcon)
</script>

<style scoped>
.sidebar-link {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 0.75rem 1.2rem;
  border-radius: 1em;
  color: #a67c00;
  font-weight: 600;
  font-size: 1.08rem;
  transition: background 0.18s, color 0.18s, box-shadow 0.18s;
  text-decoration: none;
}
.sidebar-link:hover, .sidebar-link.active {
  background: #fffbe9;
  color: #b8860b;
  box-shadow: 0 2px 8px #ffe9b2aa;
}
.icon {
  display: flex;
  align-items: center;
  justify-content: center;
}
.label {
  flex: 1;
}
</style> 