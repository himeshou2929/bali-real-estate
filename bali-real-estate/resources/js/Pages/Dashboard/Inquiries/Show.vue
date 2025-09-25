<script setup>
import { useForm } from '@inertiajs/vue3'

const props = defineProps({ inquiry: Object, statusOptions: Array })

const statusForm = useForm({ status: props.inquiry.status })
function updateStatus() {
  statusForm.patch(`/dashboard/inquiries/${props.inquiry.id}/status`, { preserveScroll: true })
}

const replyForm = useForm({ agent_reply: props.inquiry.agent_reply || '' })
function sendReply() {
  if (!replyForm.agent_reply) return
  replyForm.patch(`/dashboard/inquiries/${props.inquiry.id}/reply`, {
    preserveScroll: true
  })
}
</script>

<template>
  <div class="max-w-5xl mx-auto p-6 space-y-6">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold">Inquiry #{{ inquiry.id }}</h1>
      <a href="/dashboard/inquiries" class="text-sm underline">← Back</a>
    </div>

    <!-- Customer & Spec fields -->
    <div class="grid md:grid-cols-3 gap-4">
      <div class="md:col-span-2 bg-white border rounded-lg p-4 space-y-3">
        <div class="grid md:grid-cols-2 gap-2">
          <div><span class="text-gray-500 text-sm">Type:</span> {{ inquiry.type }}</div>
          <div><span class="text-gray-500 text-sm">Contact:</span> {{ inquiry.contact_method }}</div>
          <div class="md:col-span-2"><span class="text-gray-500 text-sm">Subject:</span> {{ inquiry.subject }}</div>
          <div><span class="text-gray-500 text-sm">Preferred Date:</span> {{ inquiry.preferred_date ? new Date(inquiry.preferred_date).toLocaleString() : '-' }}</div>
        </div>
        <div class="pt-2">
          <h2 class="font-semibold mb-1">Customer</h2>
          <div class="text-sm">
            <div>Name: {{ inquiry.name }}</div>
            <div>Email: {{ inquiry.email }}</div>
            <div>Phone: {{ inquiry.phone || '-' }}</div>
          </div>
        </div>
        <div class="pt-2">
          <h2 class="font-semibold mb-1">Message</h2>
          <div class="whitespace-pre-wrap text-sm">{{ inquiry.message || '-' }}</div>
        </div>
      </div>

      <div class="bg-white border rounded-lg p-4">
        <h2 class="font-semibold mb-2">Status</h2>
        <select v-model="statusForm.status" class="w-full border rounded p-2 mb-2">
          <option v-for="s in statusOptions" :key="s.value" :value="s.value">{{ s.label }}</option>
        </select>
        <button @click="updateStatus" class="w-full px-3 py-2 rounded bg-black text-white">Update</button>
      </div>
    </div>

    <!-- Property summary -->
    <div class="bg-white border rounded-lg p-4">
      <h2 class="text-lg font-semibold mb-2">Property</h2>
      <div class="grid md:grid-cols-4 gap-2 text-sm">
        <div class="md:col-span-2">
          <div class="font-medium">{{ inquiry.property?.title }}</div>
          <div class="text-gray-500">{{ inquiry.property?.area?.name }}</div>
        </div>
        <div>Price: {{ new Intl.NumberFormat().format(inquiry.property?.price_usd) }} USD</div>
        <div><a :href="`/properties/${inquiry.property?.id}`" class="underline">Open property</a></div>
      </div>
    </div>

    <!-- Agent Reply -->
    <div class="bg-white border rounded-lg p-4">
      <h2 class="text-lg font-semibold mb-2">Agent Reply</h2>
      <textarea v-model="replyForm.agent_reply" rows="4" class="w-full border rounded p-2" placeholder="Write a reply..."></textarea>
      <div class="text-right mt-2">
        <button @click="sendReply" class="px-3 py-2 rounded bg-black text-white">Save Reply</button>
      </div>
      <div v-if="inquiry.replied_at" class="text-xs text-gray-500 mt-2">
        Replied at: {{ new Date(inquiry.replied_at).toLocaleString() }}
      </div>
    </div>
  </div>
</template>