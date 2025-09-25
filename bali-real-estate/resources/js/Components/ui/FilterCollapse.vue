<template>
  <div class="relative">
    <!-- Mobile: Show toggle button -->
    <button 
      @click="toggleExpanded"
      class="md:hidden w-full flex items-center justify-between p-3 border rounded-md bg-white hover:bg-gray-50"
    >
      <span class="text-sm font-medium">{{ title }}</span>
      <svg 
        :class="{ 'rotate-180': isExpanded }"
        class="w-4 h-4 transition-transform duration-200"
        fill="none" 
        stroke="currentColor" 
        viewBox="0 0 24 24"
      >
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
      </svg>
    </button>

    <!-- Content: Always visible on desktop, collapsible on mobile -->
    <div 
      :class="[
        'md:block overflow-hidden transition-all duration-300',
        isExpanded ? 'block' : 'hidden'
      ]"
    >
      <div class="md:mt-0 mt-3">
        <slot />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
  title: { type: String, default: 'Filters' },
  defaultExpanded: { type: Boolean, default: false }
})

const isExpanded = ref(props.defaultExpanded)

const toggleExpanded = () => {
  isExpanded.value = !isExpanded.value
}
</script>