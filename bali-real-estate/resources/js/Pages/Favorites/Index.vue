<template>
  <div class="min-h-screen bg-gray-50">
    <NavBar />
    <div class="max-w-6xl mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-4">
      <h1 class="text-2xl font-semibold">お気に入り</h1>
      <p class="text-sm opacity-70">全 {{ total }} 件</p>
    </div>

    <div v-if="items.length === 0" class="p-8 text-center border rounded-2xl">
      <p class="mb-2">まだお気に入りがありません。</p>
      <a href="/properties" class="underline">物件を探しにいく</a>
    </div>

    <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
      <PropertyGridItem
        v-for="p in items"
        :key="p.id"
        :item="p"
        :isFavorite="true"
        :onToggled="onToggled"
      />
    </div>

    <div v-if="items.length && favorites.last_page > 1" class="flex items-center gap-2 mt-6">
      <button
        class="px-3 py-2 rounded border"
        :disabled="!favorites.prev_page_url"
        @click="go(favorites.prev_page_url)"
      >前へ</button>

      <span class="text-sm">Page {{ favorites.current_page }} / {{ favorites.last_page }}</span>

      <button
        class="px-3 py-2 rounded border"
        :disabled="!favorites.next_page_url"
        @click="go(favorites.next_page_url)"
      >次へ</button>
    </div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import { router } from '@inertiajs/vue3'
import NavBar from '@/Components/NavBar.vue'
import PropertyGridItem from '@/Components/Property/PropertyGridItem.vue'

const props = defineProps({
  favorites: { type: Object, required: true },
})

const favorites = props.favorites
const items = ref([...favorites.data])

const total = computed(() => favorites.total ?? items.value.length)

function onToggled(id, next) {
  // お気に入り解除されたら一覧から即座に消す（再取得不要の楽観更新）
  if (!next) {
    items.value = items.value.filter(p => p.id !== id)
  }
}

function go(url) {
  if (!url) return
  const qs = new URL(url, window.location.origin).search
  router.visit(`/favorites${qs}`)
}
</script>