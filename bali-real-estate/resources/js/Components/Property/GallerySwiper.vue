<template>
  <div>
    <div v-if="imgs.length === 0" class="w-full aspect-[3/2] bg-gray-200 rounded-2xl flex items-center justify-center">
      <p class="text-gray-500">画像がありません</p>
    </div>
    
    <div v-else class="relative">
      <div ref="swiperContainer" class="swiper rounded-2xl overflow-hidden shadow">
        <div class="swiper-wrapper">
          <div class="swiper-slide" v-for="(img,i) in imgs" :key="i">
            <img
              :src="getImageUrl(img, 'large')"
              :alt="`image ${i+1}`"
              class="w-full aspect-[3/2] object-cover"
              @error="handleImageError"
            />
          </div>
        </div>
        <div class="swiper-pagination" v-if="imgs.length > 1"></div>
        <div class="swiper-button-prev" v-if="imgs.length > 1"></div>
        <div class="swiper-button-next" v-if="imgs.length > 1"></div>
      </div>
    </div>

    <!-- サムネ（クリックで移動） -->
    <div v-if="imgs.length > 1" class="grid grid-cols-5 gap-2 mt-3">
      <button
        v-for="(img,i) in imgs"
        :key="'thumb-'+i"
        class="border rounded-lg overflow-hidden"
        :class="i===active ? 'ring-2 ring-offset-2' : ''"
        @click="goToSlide(i)"
      >
        <img
          :src="getImageUrl(img, 'thumb')"
          :alt="`thumb ${i+1}`"
          class="w-full aspect-video object-cover"
        />
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, nextTick, computed } from 'vue'
import { Swiper } from 'swiper'
import { Navigation, Pagination, Keyboard } from 'swiper/modules'
import PictureImage from '@/Components/Property/PictureImage.vue'
import 'swiper/css'
import 'swiper/css/navigation'
import 'swiper/css/pagination'

const props = defineProps({
  images: { type: Array, default: () => [] }
})

const swiperContainer = ref(null)
const active = ref(0)
let swiperInstance = null

const imgs = computed(() => (props.images || []).filter(Boolean))

function getImageUrl(img, size = 'large', format = 'jpg') {
  console.log('getImageUrl called with:', { img, size, format })
  
  // If img is a string (legacy), return as-is for jpg
  if (typeof img === 'string') {
    console.log('Returning string:', img)
    return format === 'jpg' ? img : null
  }
  
  // If img is an object with variants
  if (img && img.variants && img.variants[size] && img.variants[size][format]) {
    console.log('Using variants:', img.variants[size][format])
    return img.variants[size][format]
  }
  
  // Fallback to direct URL properties (for featured_image and PropertyImage)
  if (img?.url && format === 'jpg') {
    console.log('Using img.url:', img.url)
    // storage/ から始まる場合は /storage/ を付ける
    if (img.url.startsWith('properties/') || img.url.startsWith('storage/properties/')) {
      return `/storage/${img.url.replace('storage/', '')}`
    }
    return img.url.startsWith('http') ? img.url : `/storage/${img.url}`
  }
  if (img?.path && format === 'jpg') {
    console.log('Using img.path:', img.path)
    // storage/ から始まる場合は /storage/ を付ける
    if (img.path.startsWith('properties/') || img.path.startsWith('storage/properties/')) {
      return `/storage/${img.path.replace('storage/', '')}`
    }
    return img.path.startsWith('http') ? img.path : `/storage/${img.path}`
  }
  
  // Final fallback
  const fallback = img?.large_url || 'https://via.placeholder.com/1280x800?text=No+Image'
  console.log('Using fallback:', fallback)
  return fallback
}

function handleImageError(event) {
  console.log('Image failed to load:', event.target.src)
  event.target.src = 'https://via.placeholder.com/1280x800?text=Failed+to+Load'
}

onMounted(async () => {
  await nextTick()
  initSwiper()
})

onBeforeUnmount(() => {
  destroySwiper()
})

function initSwiper() {
  if (imgs.value.length === 0 || !swiperContainer.value) return
  
  destroySwiper()
  
  swiperInstance = new Swiper(swiperContainer.value, {
    modules: [Navigation, Pagination, Keyboard],
    slidesPerView: 1,
    spaceBetween: 0,
    loop: imgs.value.length > 1,
    pagination: { 
      el: '.swiper-pagination', 
      clickable: true,
      dynamicBullets: true
    },
    navigation: { 
      nextEl: '.swiper-button-next', 
      prevEl: '.swiper-button-prev' 
    },
    keyboard: { 
      enabled: true,
      onlyInViewport: true
    },
    on: { 
      slideChange: (swiper) => {
        active.value = swiper.realIndex || swiper.activeIndex
      }
    }
  })
}

function destroySwiper() {
  if (swiperInstance) {
    swiperInstance.destroy(true, true)
    swiperInstance = null
  }
}

function goToSlide(index) { 
  if (swiperInstance && imgs.value.length > index) {
    if (imgs.value.length > 1 && swiperInstance.loopCreate) {
      swiperInstance.slideToLoop(index, 300)
    } else {
      swiperInstance.slideTo(index, 300)
    }
  }
}
</script>