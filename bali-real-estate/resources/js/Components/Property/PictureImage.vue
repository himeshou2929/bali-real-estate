<template>
  <picture>
    <source v-if="avif" :srcset="avif" type="image/avif" />
    <source v-if="webp" :srcset="webp" type="image/webp" />
    <img :src="jpg || fallback" :alt="alt" loading="lazy" class="w-full h-full object-cover" />
  </picture>
</template>

<script setup>
const props = defineProps({
  avif: String,
  webp: String,
  jpg: String,
  fallback: { type: String, default: 'https://via.placeholder.com/1280x800?text=No+Image' },
  alt: { type: String, default: 'image' },
})
</script>