<template>
  <div class="flex flex-wrap items-center gap-3 mb-4">
    <label class="text-sm">Sort:
      <select class="border rounded px-2 py-1 ml-1" v-model="local.sort" @change="emitChange">
        <option value="created_at">Newest</option>
        <option value="price">Price</option>
        <option value="size">Land Size</option>
        <option value="popular">Popular</option>
      </select>
    </label>
    <label class="text-sm">Order:
      <select class="border rounded px-2 py-1 ml-1" v-model="local.order" @change="emitChange">
        <option value="desc">Desc</option>
        <option value="asc">Asc</option>
      </select>
    </label>
    <label class="text-sm">Per page:
      <select class="border rounded px-2 py-1 ml-1" v-model.number="local.per_page" @change="emitChange">
        <option :value="12">12</option>
        <option :value="24">24</option>
        <option :value="48">48</option>
      </select>
    </label>
    <span class="text-xs text-gray-500" v-if="total !== undefined">Total: {{ total }}</span>
  </div>
</template>

<script setup>
import { reactive, watch } from 'vue'
const props = defineProps({
  sort: { type: String, default: 'created_at' },
  order: { type: String, default: 'desc' },
  per_page: { type: Number, default: 12 },
  total: { type: Number, default: undefined }
})
const emit = defineEmits(['change'])
const local = reactive({ sort: props.sort, order: props.order, per_page: props.per_page })
const emitChange = ()=> emit('change', { ...local })
watch(()=>[props.sort, props.order, props.per_page], ([s,o,p])=>{
  local.sort = s; local.order = o; local.per_page = p
})
</script>