<template>
  <div class="min-h-screen bg-gray-50">
    <NavBar />
    <div class="max-w-2xl mx-auto p-6 space-y-6">
    <h1 class="text-2xl font-bold">Edit Property</h1>
    <form @submit.prevent="submit" class="space-y-4" enctype="multipart/form-data">
      <div>
        <label class="block text-sm">Title</label>
        <input v-model="form.title" class="mt-1 w-full border rounded px-3 py-2" required />
        <p v-if="errors.title" class="text-red-600 text-sm">{{ errors.title }}</p>
      </div>
      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-sm">Currency</label>
          <select v-model="form.currency" class="mt-1 w-full border rounded px-3 py-2">
            <option value="USD">USD</option><option value="JPY">JPY</option><option value="IDR">IDR</option>
          </select>
        </div>
        <div>
          <label class="block text-sm">Status</label>
          <select v-model="form.status" class="mt-1 w-full border rounded px-3 py-2">
            <option value="available">available</option>
            <option value="pending">pending</option>
            <option value="unavailable">unavailable</option>
          </select>
        </div>
      </div>

      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-sm">Area</label>
          <select v-model="form.area_id" class="mt-1 w-full border rounded px-3 py-2">
            <option :value="null">-</option>
            <option v-for="a in areas" :key="a.id" :value="a.id">{{ a.name }}</option>
          </select>
        </div>
        <div>
          <label class="block text-sm">Bedrooms</label>
          <input v-model.number="form.bedrooms" type="number" class="mt-1 w-full border rounded px-3 py-2" />
        </div>
      </div>

      <div class="grid grid-cols-3 gap-3">
        <div>
          <label class="block text-sm">Bathrooms</label>
          <input v-model.number="form.bathrooms" type="number" class="mt-1 w-full border rounded px-3 py-2" />
        </div>
        <div>
          <label class="block text-sm">最低利回り (%)</label>
          <input v-model.number="form.yield_percent" type="number" step="0.1" min="0" class="mt-1 w-full border rounded px-3 py-2" placeholder="例: 8.5" />
        </div>
        <div>
          <label class="flex items-center gap-2">
            <input v-model="form.is_published" type="checkbox" class="mt-1" />
            <span class="text-sm">公開する (Published)</span>
          </label>
          <p class="text-xs text-gray-500 mt-1">チェックを入れると物件一覧に表示されます</p>
        </div>
      </div>

      <div>
        <label class="block text-sm">Description</label>
        <textarea v-model="form.description" class="mt-1 w-full border rounded px-3 py-2" rows="3"></textarea>
      </div>

      <div>
        <label class="block text-sm">Featured image</label>
        <div v-if="property.featured_image_url && !preview" class="mb-2">
          <p class="text-sm text-gray-600 mb-1">Current image:</p>
          <img :src="property.featured_image_url" class="w-40 h-28 object-cover rounded border" />
        </div>
        <input type="file" @change="onFile" accept="image/*" class="mt-1" />
        <img v-if="preview" :src="preview" class="mt-2 w-40 h-28 object-cover rounded border" />
        <p v-if="errors.featured_image" class="text-red-600 text-sm">{{ errors.featured_image }}</p>
      </div>

      <div>
        <label class="block text-sm">住所</label>
        <input v-model="form.address" class="mt-1 w-full border rounded px-3 py-2" placeholder="エリア/住所/ランドマーク"/>
        <p v-if="errors.address" class="text-red-600 text-sm">{{ errors.address }}</p>
      </div>

      <AgentRentalTenurePrices :form="form" />

      <div class="pt-2">
        <button class="px-4 py-2 rounded bg-black text-white">Update</button>
        <a href="/agent/properties" class="ml-3 text-sm underline">Cancel</a>
      </div>
    </form>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import NavBar from '@/Components/NavBar.vue'
import AgentRentalTenurePrices from '@/Components/Agent/AgentRentalTenurePrices.vue'
const props = defineProps({ property: Object, areas: Array })
const errors = usePage().props?.errors ?? {}
const form = reactive({ 
  title: props.property.title || '',
  address: props.property.address || '',
  currency: props.property.currency || 'USD',
  status: props.property.status || 'available',
  area_id: props.property.area_id || null,
  bedrooms: props.property.bedrooms || null,
  bathrooms: props.property.bathrooms || null,
  yield_percent: props.property.yield_percent || null,
  description: props.property.description || '',
  is_published: props.property.is_published || false,
  featured_image: null,
  monthly_rent: props.property.monthly_rent || null,
  yearly_rent: props.property.yearly_rent || null,
  leasehold_price: props.property.leasehold_price || null,
  freehold_price: props.property.freehold_price || null,
  leasehold_years: props.property.leasehold_years || null
})

const preview = ref(null)
const onFile = (e)=>{ const f=e.target.files?.[0]; form.featured_image=f || null; if(f){ preview.value=URL.createObjectURL(f) } }
const submit = ()=> {
  const data = new FormData()
  Object.entries(form).forEach(([k,v])=> {
    if (k === 'is_published') {
      data.append(k, v ? '1' : '0')
    } else {
      data.append(k, v ?? '')
    }
  })
  data.append('_method', 'PATCH')
  router.post(route('agent.properties.update', {property: props.property.id}), data, { 
    forceFormData:true,
    onSuccess: () => {
      alert('物件情報を更新しました')
    },
    onError: (errors) => {
      console.log('Validation errors:', errors)
    }
  })
}
</script>