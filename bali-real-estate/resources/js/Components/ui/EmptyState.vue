<template>
  <div class="text-center py-16 border rounded-lg">
    <div class="text-xl font-semibold mb-2">{{ title }}</div>
    <p class="text-gray-500 mb-4">{{ description }}</p>
    <slot />
  </div>
</template>
<script setup>
defineProps({
  title: { type: String, default: 'No results' },
  description: { type: String, default: 'Try changing the filters or search query.' }
})
</script>