<template>
  <article class="border rounded-2xl overflow-hidden hover:shadow transition">
    <div class="relative">
      <img
        :src="thumbnail"
        class="w-full aspect-[4/3] object-cover"
        :alt="item.title || ('property '+item.id)"
        loading="lazy"
      />
      <div class="absolute top-2 right-2">
        <HeartButton v-model="fav" @toggle="toggle" />
      </div>
    </div>
    <div class="p-3">
      <h3 class="font-medium line-clamp-1">{{ item.title || item.name || ('Property #'+item.id) }}</h3>
      <p class="text-sm opacity-70 line-clamp-1">
        <span v-if="item.area">{{ item.area.name }}</span>
        <span v-if="item.bedrooms"> · {{ item.bedrooms }} BR</span>
        <span v-if="item.bathrooms"> · {{ item.bathrooms }} BA</span>
      </p>
      <PriceSummary :property="item" />
      <a :href="`/properties/${item.id}`" class="inline-block mt-2 text-sm underline">
        詳細を見る
      </a>
    </div>
  </article>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import HeartButton from '@/Components/Property/HeartButton.vue'
import PriceSummary from '@/Components/Property/PriceSummary.vue'
import { addFavorite, removeFavorite } from '@/lib/favorites'

const props = defineProps({
  item: { type: Object, required: true },
  isFavorite: { type: Boolean, default: false },
  onToggled: { type: Function, default: null },
})

const item = props.item
const fav = ref(props.isFavorite)
watch(() => props.isFavorite, v => fav.value = v)


const thumbnail = computed(() => {
  // featured_imageがある場合はそれを使用
  if (item.featured_image) {
    // ストレージパスをフルURLに変換
    if (item.featured_image.startsWith('http')) {
      return item.featured_image
    }
    return `/storage/${item.featured_image}`
  }
  
  // featured_image_url がある場合（既にフォーマット済み）
  if (item.featured_image_url) {
    return item.featured_image_url
  }
  
  // imagesがある場合は最初の画像を使用
  const imgs = item.images || []
  const first = imgs[0] || {}
  if (first.thumbnail_url || first.thumb || first.url || first.path) {
    return first.thumbnail_url || first.thumb || first.url || first.path
  }
  
  // フォールバック
  return 'https://via.placeholder.com/800x600?text=No+Image'
})

async function toggle(next) {
  try {
    if (next) {
      await addFavorite(item.id)
    } else {
      await removeFavorite(item.id)
    }
    props.onToggled && props.onToggled(item.id, next)
  } catch (e) {
    // 失敗時はUI戻す
    fav.value = !next
    alert('お気に入りの更新に失敗しました。ログイン状態をご確認ください。')
  }
}
</script>