<template>
  <div class="min-h-screen bg-gray-50">
    <NavBar />
    <div class="max-w-6xl mx-auto p-6">
    <header class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold">My Properties</h1>
      <a href="/agent/properties/create" class="px-3 py-2 rounded bg-black text-white text-sm">New Property</a>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
      <!-- Main content -->
      <div class="lg:col-span-3 space-y-6">
        <div v-if="properties.data.length" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-6">
          <div v-for="p in properties.data" :key="p.id" class="border rounded-md overflow-hidden">
            <img v-if="p.featured_image_url" :src="p.featured_image_url" class="w-full h-40 object-cover" />
            <div class="p-4 space-y-1">
              <div class="font-semibold line-clamp-1">{{ p.title }}</div>
              <div class="text-xs text-gray-500">{{ p.area?.name ?? '-' }} ・ {{ p.status }}</div>
              <div class="pt-2 flex gap-2">
                <a :href="`/agent/properties/${p.id}/edit`" class="text-sm underline">Edit</a>
                <button class="text-sm text-red-600 underline" @click="del(p.id)">Delete</button>
              </div>
            </div>
          </div>
        </div>

        <div v-else class="text-center py-16 border rounded">No properties yet.</div>

        <div class="flex items-center justify-between mt-4" v-if="properties.last_page>1">
          <button class="px-3 py-1 rounded border" :disabled="!properties.prev_page_url" @click="go(properties.current_page-1)">Prev</button>
          <span class="text-sm">Page {{ properties.current_page }} / {{ properties.last_page }}</span>
          <button class="px-3 py-1 rounded border" :disabled="!properties.next_page_url" @click="go(properties.current_page+1)">Next</button>
        </div>
      </div>

      <!-- Right sidebar -->
      <div class="lg:col-span-1">
        <AgentProfileCard :agent="agent" :showEditButton="true" />
      </div>
    </div>
    </div>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import NavBar from '@/Components/NavBar.vue'
import AgentProfileCard from '@/Components/Agent/ProfileCard.vue'
const props = defineProps({ properties: Object, filters: Object, agent: Object })
const go = (page)=> router.get(route('agent.properties.index'), { page: page, per_page: props.filters?.per_page ?? 20 }, { preserveScroll:true, preserveState:true })
const del = (id)=> { if(confirm('Delete this property?')) router.delete(route('agent.properties.destroy', {property:id})) }
</script>