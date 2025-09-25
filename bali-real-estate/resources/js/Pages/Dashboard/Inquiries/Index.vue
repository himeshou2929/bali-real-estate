<script setup>
import { router, useForm } from '@inertiajs/vue3'
import { reactive } from 'vue'

const props = defineProps({
  inquiries: Object,
  filters: Object
})

const statusOptions = [
  { value: 'new', label: 'New' },
  { value: 'contacted', label: 'Contacted' },
  { value: 'replied', label: 'Replied' },
  { value: 'closed', label: 'Closed' }
]

const form = reactive({
  status: props.filters.status || '',
  keyword: props.filters.keyword || '',
  from: props.filters.from || '',
  to: props.filters.to || ''
})

function search() {
  router.get('/admin/inquiries', { ...form }, { preserveState: true, replace: true })
}

function updateStatus(id, status) {
  const f = useForm({ status })
  f.patch(`/admin/inquiries/${id}`, {
    preserveScroll: true
  })
}
</script>

<template>
  <div class="max-w-7xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Inquiries</h1>

    <!-- Filters -->
    <div class="bg-gray-100 p-4 rounded-lg mb-6 grid grid-cols-1 md:grid-cols-6 gap-3">
      <div>
        <label class="text-sm text-gray-600">Status</label>
        <select v-model="form.status" class="w-full border rounded p-2">
          <option value="">All</option>
          <option v-for="s in statusOptions" :key="s.value" :value="s.value">{{ s.label }}</option>
        </select>
      </div>
      <div class="md:col-span-2">
        <label class="text-sm text-gray-600">Keyword</label>
        <input v-model="form.keyword" class="w-full border rounded p-2" placeholder="name/email/message/property" />
      </div>
      <div>
        <label class="text-sm text-gray-600">From</label>
        <input v-model="form.from" type="date" class="w-full border rounded p-2" />
      </div>
      <div>
        <label class="text-sm text-gray-600">To</label>
        <input v-model="form.to" type="date" class="w-full border rounded p-2" />
      </div>
      <div class="flex items-end">
        <button @click="search" class="px-4 py-2 rounded bg-black text-white w-full">Search</button>
      </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto bg-white rounded-lg border">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-50">
          <tr>
            <th class="p-3 text-left">ID</th>
            <th class="p-3 text-left">Created</th>
            <th class="p-3 text-left">Property</th>
            <th class="p-3 text-left">Name / Email</th>
            <th class="p-3 text-left">Message</th>
            <th class="p-3 text-left">Status</th>
            <th class="p-3 text-left">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="inq in inquiries.data" :key="inq.id" class="border-t">
            <td class="p-3">{{ inq.id }}</td>
            <td class="p-3 whitespace-nowrap">{{ new Date(inq.created_at).toLocaleString() }}</td>
            <td class="p-3">
              <div class="font-medium">
                <a :href="`/admin/inquiries/${inq.id}`" class="text-blue-600 underline">
                  {{ inq.property?.title || '-' }}
                </a>
              </div>
              <div class="text-gray-500 text-xs">{{ inq.property?.area?.name || '' }}</div>
            </td>
            <td class="p-3">
              <div class="font-medium">{{ inq.name }}</div>
              <div class="text-gray-500 text-xs">{{ inq.email }}</div>
            </td>
            <td class="p-3 max-w-[320px] truncate" :title="inq.message">{{ inq.message || '-' }}</td>
            <td class="p-3">
              <span class="px-2 py-1 rounded text-xs border"
                    :class="{
                      'bg-yellow-50 border-yellow-300': inq.status==='new',
                      'bg-blue-50 border-blue-300': inq.status==='contacted',
                      'bg-green-50 border-green-300': inq.status==='closed'
                    }">
                {{ inq.status }}
              </span>
            </td>
            <td class="p-3 flex gap-2">
              <select :value="inq.status" @change="updateStatus(inq.id, $event.target.value)"
                      class="border rounded p-1">
                <option value="new">Mark New</option>
                <option value="contacted">Mark Contacted</option>
                <option value="closed">Mark Closed</option>
              </select>
              <a :href="`/admin/inquiries/${inq.id}`" class="px-2 py-1 border rounded">View</a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="flex gap-2 mt-6">
      <a v-for="link in inquiries.links" :key="(link.url || '') + (link.label || '')"
         :href="link.url || '#'" v-html="link.label"
         class="px-3 py-1 rounded border"
         :class="{'bg-black text-white': link.active, 'opacity-50 pointer-events-none': !link.url}" />
    </div>
  </div>
</template>