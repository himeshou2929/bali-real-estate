<template>
  <div class="min-h-screen bg-gray-50">
    <NavBar />
    
    <div class="max-w-7xl mx-auto px-4 py-6">
      <div class="flex items-center justify-between mb-4">
      <h1 class="text-2xl font-semibold">物件一覧</h1>
      <p class="text-sm opacity-70">全 {{ properties.total }} 件中 {{ properties.from }}–{{ properties.to }} 件を表示</p>
    </div>

    <!-- Enhanced Search Form with Responsive Collapse -->
    <div class="mb-6">
      <FilterCollapse title="検索・絞り込み">
        <div class="bg-gray-100 p-4 rounded-lg">
          <SearchForm :initial="query" :areas="areas" />
        </div>
      </FilterCollapse>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
      <PropertyGridItem
        v-for="p in properties.data"
        :key="p.id"
        :item="p"
        :isFavorite="favoriteIds.has(p.id)"
        :onToggled="onToggled"
      />
    </div>

    <div class="flex items-center gap-2 mt-6">
      <button
        class="px-3 py-2 rounded border"
        :disabled="!properties.prev_page_url"
        @click="go(properties.prev_page_url)"
      >前へ</button>

      <span class="text-sm">Page {{ properties.current_page }} / {{ properties.last_page }}</span>

      <button
        class="px-3 py-2 rounded border"
        :disabled="!properties.next_page_url"
        @click="go(properties.next_page_url)"
      >次へ</button>
    </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import PropertyGridItem from '@/Components/Property/PropertyGridItem.vue'
import SearchForm from '@/Components/Property/SearchForm.vue'
import FilterCollapse from '@/Components/UI/FilterCollapse.vue'
import NavBar from '@/Components/NavBar.vue'
import { fetchFavoritesSet } from '@/lib/favorites'

const props = defineProps({
  properties: { type: Object, required: true },
  areas: { type: Array, required: true },
  filters: { type: Object, required: true },
  query: { type: Object, required: true }
})


const properties = props.properties
const favoriteIds = ref(new Set())

onMounted(async () => {
  try {
    favoriteIds.value = await fetchFavoritesSet()
  } catch (e) {
    // 未ログイン時などはスキップ
    console.debug('favorites init skipped', e?.message)
  }
})

function onToggled(id, next) {
  if (next) favoriteIds.value.add(id)
  else favoriteIds.value.delete(id)
}

function go(url) {
  if (!url) return
  // Inertia 経由で遷移（クエリ維持）
  const qs = new URL(url, window.location.origin).search
  router.visit(`/properties${qs}`)
}
</script>