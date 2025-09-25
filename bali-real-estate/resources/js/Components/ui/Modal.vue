<template>
  <div v-if="open" class="fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-black/40" @click="$emit('close')"></div>
    <div class="relative bg-white rounded-lg shadow-lg max-w-2xl w-full p-6">
      <div class="flex items-center justify-between mb-3">
        <h3 class="text-lg font-semibold"><slot name="title">Detail</slot></h3>
        <button class="text-gray-500 hover:text-gray-700" @click="$emit('close')">✕</button>
      </div>
      <div class="max-h-[70vh] overflow-auto">
        <slot />
      </div>
      <div class="mt-4 text-right">
        <button class="px-4 py-2 border rounded" @click="$emit('close')">Close</button>
      </div>
    </div>
  </div>
</template>
<script setup>
defineProps({ open: { type:Boolean, default:false } })
</script>