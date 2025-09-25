<template>
  <div class="min-h-screen bg-gray-50">
    <NavBar />
    <div class="max-w-6xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">My Inquiries</h1>
    <div v-if="inquiries?.data?.length" class="space-y-4">
      <div v-for="i in inquiries.data" :key="i.id" class="border rounded-lg p-4">
        <div class="flex items-center justify-between">
          <div class="font-semibold">{{ i.subject }}</div>
          <span class="text-xs text-gray-500">{{ new Date(i.created_at).toLocaleString() }}</span>
        </div>
        <div class="text-sm text-gray-600 line-clamp-2 mt-1">{{ i.message }}</div>
        <div class="text-xs text-gray-500 mt-2">
          Status: <strong>{{ i.status }}</strong>
          <span v-if="i.assigned_user"> / Assignee: {{ i.assigned_user.name }}</span>
        </div>
        <div class="mt-3 flex gap-3">
          <button class="text-blue-600 underline text-sm" @click="openInquiry(i)">Open detail</button>
          <a :href="`/user/inquiries/${i.id}`" class="text-blue-600 underline text-sm">Detail page</a>
        </div>
      </div>
    </div>
    <div v-else class="text-center py-12 text-gray-500">No inquiries yet.</div>
    </div>
  </div>

  <Modal :open="open" @close="open=false">
    <template #title>
      {{ selected?.subject }}
    </template>
    <div v-if="selected">
      <div class="text-sm text-gray-500">{{ new Date(selected.created_at).toLocaleString() }}</div>
      <div class="mt-3">
        <div class="text-xs text-gray-500">Status</div>
        <div class="font-medium">{{ selected.status }}</div>
      </div>
      <div class="mt-3">
        <div class="text-xs text-gray-500">Message</div>
        <p class="whitespace-pre-line">{{ selected.message }}</p>
      </div>
      <div class="mt-3" v-if="selected.agent_reply">
        <div class="text-xs text-gray-500">Agent Reply</div>
        <p class="whitespace-pre-line">{{ selected.agent_reply }}</p>
      </div>
      <div class="mt-3">
        <a class="text-blue-600 underline text-sm" :href="`/properties/${selected.property_id}`">Property page</a>
      </div>
    </div>
  </Modal>
</template>
<script setup>
import { ref } from 'vue'
import NavBar from '@/Components/NavBar.vue'
import Modal from '@/Components/UI/Modal.vue'

defineProps({ inquiries: Object })
const open = ref(false)
const selected = ref(null)
function openInquiry(i){ selected.value = i; open.value = true }
</script>