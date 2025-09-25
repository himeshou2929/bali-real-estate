<template>
  <div class="min-h-screen bg-gray-50">
    <NavBar />
    
    <div class="max-w-6xl mx-auto px-4 py-6">
    
    <!-- タイトル + 価格 + ハート -->
    <div class="flex items-start justify-between gap-4">
      <div>
        <h1 class="text-2xl font-semibold">{{ property.title ?? property.name ?? ('Property #' + property.id) }}</h1>
        <p class="text-lg opacity-80 mt-1">
          {{ priceText }}
          <span v-if="property.area"> · {{ property.area.name }}</span>
          <span v-if="property.bedrooms"> · {{ property.bedrooms }} BR</span>
          <span v-if="property.bathrooms"> · {{ property.bathrooms }} BA</span>
        </p>
      </div>
      <HeartButton v-model="favorite" @toggle="toggleFavorite" />
    </div>

    <!-- Swiper ギャラリー -->
    <GallerySwiper :images="images" class="mt-6" />

    <!-- 概要/設備 -->
    <div class="mt-8 grid md:grid-cols-3 gap-8">
      <div class="md:col-span-2 space-y-4 leading-relaxed">
        <h2 class="text-xl font-semibold">詳細</h2>
        <p v-if="property.description">{{ property.description }}</p>
        <div class="grid grid-cols-2 gap-2 text-sm mt-2">
          <div v-if="property.size">面積：{{ property.size }} m²</div>
          <div v-if="property.type?.name">タイプ：{{ property.type.name }}</div>
          <div v-if="property.address">住所：{{ property.address }}</div>
        </div>
        <div v-if="amenities.length" class="mt-4">
          <h3 class="font-medium">設備</h3>
          <ul class="list-disc list-inside">
            <li v-for="(a,i) in amenities" :key="i">{{ a }}</li>
          </ul>
        </div>
      </div>

      <aside class="space-y-3">
        <div class="p-4 rounded-2xl border">
          <div class="text-2xl font-bold mb-2">{{ priceText }}</div>
          <button
            class="w-full rounded-xl px-4 py-3 bg-black text-white"
            @click="goInquiry"
          >
            この物件に問い合わせ
          </button>
          <p class="text-xs opacity-70 mt-2">物件ID: {{ property.id }}</p>
        </div>
      </aside>
    </div>

    <div class="mt-6">
      <h3 class="font-semibold mb-2">Location</h3>
      <MapPreview :lat="Number(property.latitude)" :lng="Number(property.longitude)" :zoom="15" />
      <p class="text-xs text-gray-500 mt-1">{{ property.address }}</p>
    </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import HeartButton from '@/Components/Property/HeartButton.vue'
import GallerySwiper from '@/Components/Property/GallerySwiper.vue'
import MapPreview from '@/Components/Map/MapPreview.vue'
import NavBar from '@/Components/NavBar.vue'
import { fetchFavoritesSet, addFavorite, removeFavorite } from '@/lib/favorites'
import { usePage, router } from '@inertiajs/vue3'

const props = defineProps({
  property: { type: Object, required: true }
})

const property = props.property
const priceText = computed(() => {
  if (property.price_usd && property.currency) return new Intl.NumberFormat().format(property.price_usd) + ' ' + property.currency
  if (property.price_usd) return '$' + new Intl.NumberFormat().format(property.price_usd)
  return 'Ask'
})

// 画像配列（最適化された画像オブジェクトをそのまま渡す）
const images = ref(
  (property.images ?? []).filter(Boolean)
)

// お気に入り状態
const favorite = ref(false)
let favSet = new Set()

onMounted(async () => {
  try {
    favSet = await fetchFavoritesSet()
    favorite.value = favSet.has(property.id)
  } catch (e) {
    // 未ログインやAPI未許可の時は無視
    console.debug('favorites init skipped', e?.message)
  }
})

async function toggleFavorite(next) {
  try {
    if (next) {
      await addFavorite(property.id)
      favSet.add(property.id)
    } else {
      await removeFavorite(property.id)
      favSet.delete(property.id)
    }
  } catch (e) {
    // 失敗したらUIを戻す
    favorite.value = !next
    alert('お気に入りの更新に失敗しました。ログイン状態をご確認ください。')
  }
}

const amenities = computed(() => {
  if (Array.isArray(property.amenities)) return property.amenities
  if (typeof property.amenities === 'string') {
    return property.amenities.split(',').map(s => s.trim()).filter(Boolean)
  }
  return []
})

function goInquiry() {
  // 既存の問い合わせ画面にリダイレクト（物件ID付与）
  // 例: /inquiries/create?property_id=xxx
  router.visit(`/inquiries/create?property_id=${property.id}`)
}
</script>