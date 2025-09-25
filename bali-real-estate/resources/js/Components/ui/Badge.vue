<template>
  <span
    class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
    :class="colorClass"
  >
    <slot />
  </span>
</template>

<script setup>
const props = defineProps({ variant: { type: String, default: 'gray' }})
const map = {
  gray:   'bg-gray-100 text-gray-800',
  green:  'bg-green-100 text-green-800',
  blue:   'bg-blue-100 text-blue-800',
  yellow: 'bg-yellow-100 text-yellow-800',
  red:    'bg-red-100 text-red-800',
}
const colorClass = computed(()=> map[props.variant] ?? map.gray)
</script>