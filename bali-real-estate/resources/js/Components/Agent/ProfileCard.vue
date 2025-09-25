<template>
  <aside class="border rounded-xl p-4 bg-white sticky top-6">
    <div class="flex items-center gap-3">
      <img v-if="avatarUrl" :src="avatarUrl" class="w-14 h-14 rounded-full object-cover border" />
      <div>
        <div class="font-semibold leading-tight">{{ agent.name }}</div>
        <div class="text-xs text-gray-500">{{ agent.title || 'Agent' }} <span v-if="agent.company"> @ {{ agent.company }}</span></div>
      </div>
    </div>

    <div v-if="agent.location" class="mt-3 text-sm text-gray-600">
      📍 {{ agent.location }}
    </div>

    <div class="mt-3 space-y-1 text-sm">
      <div v-if="agent.phone">📞 {{ agent.phone }}</div>
      <div class="truncate">✉️ {{ agent.email }}</div>
      <div v-if="agent.website">🔗 <a :href="normalizeUrl(agent.website)" target="_blank" class="underline text-blue-600">Website</a></div>
      <div v-if="agent.whatsapp">💬 WhatsApp: {{ agent.whatsapp }}</div>
      <div v-if="agent.line_id">💬 LINE: {{ agent.line_id }}</div>
    </div>

    <p v-if="agent.bio" class="mt-3 text-sm text-gray-700 whitespace-pre-line">{{ agent.bio }}</p>

    <div class="mt-4 space-y-2">
      <a v-if="contactUrl" :href="contactUrl" class="inline-flex items-center justify-center w-full px-3 py-2 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700">
        Contact Agent
      </a>
      
      <a v-if="showEditButton" href="/agent/profile" class="inline-flex items-center justify-center w-full px-3 py-2 text-sm border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
        ✏️ Edit Profile
      </a>
    </div>
  </aside>
</template>

<script setup>
const props = defineProps({
  agent: { type: Object, required: true },
  contactUrl: { type: String, default: '' }, // 例: /inquiries/create?property_id=...
  showEditButton: { type: Boolean, default: false }
})
const avatarUrl = props.agent?.avatar ? `/storage/${props.agent.avatar}` : ''
const normalizeUrl = (u)=> u && !/^https?:/i.test(u) ? `https://${u}` : u
</script>