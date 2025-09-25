<template>
  <transition name="fade">
    <div v-if="message" class="fixed top-4 right-4 z-50 max-w-md">
      <div :class="cls" class="rounded-lg shadow px-4 py-3 text-sm">
        <strong class="mr-2">{{ title }}</strong>{{ message }}
      </div>
    </div>
  </transition>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
const props = defineProps({ type: { default: 'success' }, message: { default: '' } })
const title = computed(()=> ({success:'Success',error:'Error',warning:'Warning',info:'Info'})[props.type]||'Info')
const cls = computed(()=>({
  success:'bg-green-50 text-green-800 border border-green-200',
  error:'bg-red-50 text-red-800 border border-red-200',
  warning:'bg-yellow-50 text-yellow-800 border border-yellow-200',
  info:'bg-blue-50 text-blue-800 border border-blue-200'
}[props.type]||'bg-gray-50 text-gray-800 border'))
const message = ref(props.message)
onMounted(()=>{ if(message.value){ setTimeout(()=>{ message.value='' }, 3500) }})
</script>

<style>
.fade-enter-active,.fade-leave-active{ transition: opacity .2s }
.fade-enter-from,.fade-leave-to{ opacity:0 }
</style>