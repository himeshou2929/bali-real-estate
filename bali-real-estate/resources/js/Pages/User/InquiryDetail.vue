<template>
  <div class="max-w-3xl mx-auto p-6 space-y-6">
    <h1 class="text-2xl font-bold">Inquiry Detail</h1>
    <div class="border rounded-lg p-4">
      <div class="flex justify-between">
        <div class="font-semibold">{{ inquiry.subject }}</div>
        <span class="text-xs text-gray-500">{{ new Date(inquiry.created_at).toLocaleString() }}</span>
      </div>
      <div class="text-sm mt-2 whitespace-pre-line">{{ inquiry.message }}</div>
      <div class="mt-2 text-xs text-gray-500">
        Status:
        <span :class="badgeCls(inquiry.status)">{{ inquiry.status }}</span>
        <span v-if="inquiry.assigned_user"> / Assignee: {{ inquiry.assigned_user.name }}</span>
      </div>
      <div class="mt-2">
        <a :href="`/properties/${inquiry.property_id}`" class="underline text-blue-600 text-sm">Property page</a>
      </div>
      <div v-if="inquiry.agent_reply" class="mt-4 border-t pt-2">
        <h3 class="font-semibold">Reply</h3>
        <p class="whitespace-pre-line text-sm">{{ inquiry.agent_reply }}</p>
      </div>
    </div>
  </div>
</template>
<script setup>
const props = defineProps({ inquiry:Object })
function badgeCls(s){
  return {
    'new':'bg-blue-100 text-blue-800 px-2 py-0.5 rounded text-xs',
    'replied':'bg-green-100 text-green-800 px-2 py-0.5 rounded text-xs',
    'closed':'bg-gray-100 text-gray-800 px-2 py-0.5 rounded text-xs',
  }[s] || 'bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded text-xs'
}
</script>