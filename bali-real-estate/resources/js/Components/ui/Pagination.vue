<template>
  <div class="flex items-center justify-between gap-2 mt-4">
    <button class="px-3 py-1 rounded border" :disabled="!links.prev" @click="$emit('change', page-1)">Prev</button>
    <span class="text-sm">Page {{ page }} / {{ lastPage }}</span>
    <button class="px-3 py-1 rounded border" :disabled="!links.next" @click="$emit('change', page+1)">Next</button>
  </div>
</template>

<script setup>
defineProps({
  page: { type: Number, required: true },
  lastPage: { type: Number, required: true },
  links: { type: Object, default: ()=>({ prev: null, next: null }) }
})
</script>