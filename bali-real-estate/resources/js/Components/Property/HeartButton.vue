<template>
  <button
    type="button"
    :aria-pressed="active ? 'true' : 'false'"
    class="inline-flex items-center justify-center rounded-xl px-3 py-2 border text-sm font-medium disabled:opacity-50"
    :class="active ? 'border-transparent text-red-600' : 'border-gray-300 text-gray-600 hover:text-red-500'"
    @click="onClick"
    :disabled="loading"
    title="お気に入りに追加/解除"
  >
    <span v-if="!loading">
      <svg v-if="active" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
        <path d="M11.645 20.91l-.007-.003-.022-.01a15.247 15.247 0 01-.383-.18 25.18 25.18 0 01-4.244-2.62C4.688 16.502 2.25 14.061 2.25 10.875 2.25 8.262 4.235 6.3 6.75 6.3c1.63 0 3.052.795 3.99 2.016a4.973 4.973 0 013.99-2.016c2.515 0 4.5 1.962 4.5 4.575 0 3.186-2.438 5.627-4.739 7.223a25.145 25.145 0 01-4.244 2.62 15.247 15.247 0 01-.383.18l-.022.01-.007.003a.75.75 0 01-.582 0z"/>
      </svg>
      <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
              d="M21 8.25c0-2.07-1.68-3.75-3.75-3.75-1.65 0-3.06 1.05-3.54 2.5h-1.42c-.48-1.45-1.89-2.5-3.54-2.5C5.68 4.5 4 6.18 4 8.25c0 3.61 3.2 6.17 6.21 8a32.03 32.03 0 003.58 2.02 32.03 32.03 0 003.58-2.02C17.8 14.42 21 11.86 21 8.25z"/>
      </svg>
    </span>
    <span v-else class="animate-pulse">…</span>
  </button>
</template>

<script setup>
import { ref, watch } from 'vue'
const props = defineProps({
  modelValue: { type: Boolean, default: false },
})
const emit = defineEmits(['update:modelValue','toggle'])
const active = ref(props.modelValue)
const loading = ref(false)

watch(() => props.modelValue, v => active.value = v)

const onClick = async () => {
  if (loading.value) return
  loading.value = true
  try {
    const next = !active.value
    active.value = next
    emit('update:modelValue', next)
    emit('toggle', next)
  } finally {
    loading.value = false
  }
}
</script>