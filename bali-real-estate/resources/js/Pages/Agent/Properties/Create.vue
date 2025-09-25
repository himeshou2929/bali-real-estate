<template>
  <div class="min-h-screen bg-gray-50">
    <NavBar />
    <div class="max-w-2xl mx-auto p-6 space-y-6">
    <h1 class="text-2xl font-bold">New Property</h1>
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
        <label class="block text-sm">メイン画像</label>
        <input type="file" @change="onFile" accept="image/*" class="mt-1" />
        <img v-if="preview" :src="preview" class="mt-2 w-40 h-28 object-cover rounded border" />
        <p v-if="errors.featured_image" class="text-red-600 text-sm">{{ errors.featured_image }}</p>
      </div>

      <div>
        <label class="block text-sm mb-2">追加画像（複数選択可能）</label>
        <input type="file" @change="onAdditionalFiles" accept="image/*" multiple class="mt-1" />
        <div v-if="additionalPreviews.length > 0" class="mt-3 grid grid-cols-3 gap-3">
          <div v-for="(preview, index) in additionalPreviews" :key="index" class="relative">
            <img :src="preview" class="w-full h-24 object-cover rounded border" />
            <button type="button" @click="removeAdditionalImage(index)" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
              ×
            </button>
          </div>
        </div>
        <p v-if="errors.additional_images" class="text-red-600 text-sm mt-1">{{ errors.additional_images }}</p>
      </div>

      <div>
        <label class="block text-sm">住所</label>
        <input v-model="form.address" class="mt-1 w-full border rounded px-3 py-2" placeholder="エリア/住所/ランドマーク"/>
        <p v-if="errors.address" class="text-red-600 text-sm">{{ errors.address }}</p>
      </div>

      <AgentRentalTenurePrices :form="form" />

      <div class="pt-2">
        <button type="submit" @click="console.log('Button clicked!')" class="px-4 py-2 rounded bg-black text-white hover:bg-gray-800">Create Property</button>
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

const props = defineProps({ areas: Array })
const errors = usePage().props?.errors ?? {}
const form = reactive({ 
  title:'', address:'', currency:'USD', status:'available', area_id:null, 
  bedrooms:null, bathrooms:null, yield_percent:null,
  description:'', is_published:true, featured_image:null, additional_images: [],
  monthly_rent: null, yearly_rent: null, leasehold_price: null, freehold_price: null, leasehold_years: null
})

const preview = ref(null)
const additionalPreviews = ref([])
const additionalFiles = ref([])

const onFile = (e)=>{ 
  const f=e.target.files?.[0]; 
  form.featured_image=f || null; 
  if(f){ preview.value=URL.createObjectURL(f) } 
}

const onAdditionalFiles = (e) => {
  const files = Array.from(e.target.files || [])
  additionalFiles.value = files
  form.additional_images = files
  
  additionalPreviews.value = files.map(file => URL.createObjectURL(file))
}

const removeAdditionalImage = (index) => {
  additionalFiles.value.splice(index, 1)
  additionalPreviews.value.splice(index, 1)
  form.additional_images = additionalFiles.value
}

const submit = ()=> {
  console.log('Submit function called!')
  console.log('Form data:', form)
  
  // Required fieldsをチェック
  if (!form.title) {
    alert('Title and Price are required!')
    return
  }
  
  const data = new FormData()
  Object.entries(form).forEach(([k,v])=> {
    if (k === 'is_published') {
      data.append(k, v ? '1' : '0')
    } else if (k === 'additional_images') {
      // 追加画像を個別に追加
      if (Array.isArray(v)) {
        v.forEach((file, index) => {
          data.append(`additional_images[${index}]`, file)
        })
      }
    } else {
      data.append(k, v ?? '')
    }
  })
  
  console.log('About to submit to /agent/properties')
  
  router.post('/agent/properties', data, { 
    forceFormData:true,
    onBefore: () => {
      console.log('Request starting...')
    },
    onSuccess: (response) => {
      console.log('Success!', response)
      alert('Property created successfully!')
      router.visit('/agent/properties')
    },
    onError: (errors) => {
      console.log('Validation errors:', errors)
      alert('Error occurred: ' + JSON.stringify(errors))
    },
    onFinish: () => {
      console.log('Request finished')
    }
  })
}
</script>