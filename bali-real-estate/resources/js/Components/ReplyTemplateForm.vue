<template>
  <div class="space-y-4">
    <label class="block text-sm">Select Template</label>
    <select v-model="selected" class="border rounded px-3 py-2 w-full">
      <option value="">-- Choose --</option>
      <option v-for="tpl in templates" :key="tpl.key" :value="tpl.body">{{ tpl.label }}</option>
    </select>

    <textarea v-model="message" class="w-full border rounded p-2 h-32"></textarea>

    <div class="border rounded p-2 bg-gray-50">
      <h4 class="font-semibold text-sm mb-1">Preview</h4>
      <div class="text-sm whitespace-pre-line">{{ message }}</div>
    </div>

    <button @click="$emit('send', message)" class="bg-blue-600 text-white px-4 py-2 rounded">Send</button>
  </div>
</template>
<script setup>
import { ref, watch } from 'vue'
const props = defineProps({ templates:Array })
const emit = defineEmits(['send'])
const selected = ref('')
const message = ref('')
watch(selected,(val)=>{ if(val) message.value=val })
</script>