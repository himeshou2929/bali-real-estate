<template>
  <div class="max-w-6xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">My Favorites</h1>
    <div v-if="favorites?.data?.length" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="p in favorites.data" :key="p.id" class="border rounded-lg overflow-hidden hover:shadow">
        <img v-if="p.featured_image_url || p.primaryImage?.url" 
             :src="p.featured_image_url || p.primaryImage?.url" 
             class="w-full h-40 object-cover" 
             :alt="p.title" />
        <div v-else class="w-full h-40 bg-gray-200 flex items-center justify-center">
          <span class="text-gray-500 text-sm">No image</span>
        </div>
        <div class="p-4 space-y-2">
          <h3 class="font-semibold line-clamp-1">{{ p.title_en ?? p.title }}</h3>
          <div class="text-sm text-gray-600">
            {{ new Intl.NumberFormat('en-US',{style:'currency',currency:p.currency||'USD'}).format(p.price) }}
          </div>
          <div class="text-xs text-gray-500" v-if="p.area?.name">{{ p.area.name }}</div>
          <a :href="`/properties/${p.id}`" class="text-blue-600 underline text-sm">View detail</a>
          <button class="mt-2 text-xs px-3 py-1 border rounded hover:bg-gray-50"
                  @click.prevent="unfavorite(p.id)">Unfavorite</button>
        </div>
      </div>
    </div>
    <div v-else class="text-center py-12 text-gray-500">No favorites yet.</div>
  </div>
</template>
<script setup>
import { router } from '@inertiajs/vue3'

defineProps({ favorites: Object })

function unfavorite(id){
  router.delete(route('user.favorites.destroy',{ property:id }), { preserveScroll:true })
}
</script>