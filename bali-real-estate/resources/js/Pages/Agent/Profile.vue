<template>
  <div class="max-w-4xl mx-auto p-6 space-y-6">
    <!-- Flash Message -->
    <div v-if="$page.props.flash?.success" class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md">
      {{ $page.props.flash.success }}
    </div>
    
    <div class="flex justify-between items-center">
      <h1 class="text-2xl font-bold">Agent Profile</h1>
      <a href="/agent/properties" class="px-4 py-2 rounded bg-gray-600 text-white hover:bg-gray-700 flex items-center gap-2">
        ← Back to Properties
      </a>
    </div>
    
    <form @submit.prevent="submitProfile" class="space-y-6" enctype="multipart/form-data">
      <!-- Avatar Section -->
      <div class="bg-white p-6 rounded-lg border">
        <h2 class="text-lg font-semibold mb-4">Profile Photo</h2>
        <div class="flex items-center gap-4">
          <img v-if="preview || user.avatar" :src="preview || storageUrl(user.avatar)" class="w-20 h-20 rounded-full object-cover border" />
          <div>
            <input type="file" accept="image/*" @change="onFile" />
            <p class="text-xs text-gray-500 mt-1">JPEG/PNG/WebP, up to 5MB</p>
          </div>
        </div>
      </div>

      <!-- Basic Information -->
      <div class="bg-white p-6 rounded-lg border space-y-4">
        <h2 class="text-lg font-semibold mb-4">Basic Information</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium">Name *</label>
            <input v-model="form.name" class="mt-1 w-full border rounded px-3 py-2" required />
            <p v-if="errors.name" class="text-red-600 text-sm mt-1">{{ errors.name }}</p>
          </div>
          
          <div>
            <label class="block text-sm font-medium">Email *</label>
            <input v-model="form.email" type="email" class="mt-1 w-full border rounded px-3 py-2" required />
            <p v-if="errors.email" class="text-red-600 text-sm mt-1">{{ errors.email }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium">Phone</label>
            <input v-model="form.phone" class="mt-1 w-full border rounded px-3 py-2" />
            <p v-if="errors.phone" class="text-red-600 text-sm mt-1">{{ errors.phone }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium">Location</label>
            <input v-model="form.location" placeholder="e.g., Canggu, Bali" class="mt-1 w-full border rounded px-3 py-2" />
            <p v-if="errors.location" class="text-red-600 text-sm mt-1">{{ errors.location }}</p>
          </div>
        </div>
      </div>

      <!-- Professional Information -->
      <div class="bg-white p-6 rounded-lg border space-y-4">
        <h2 class="text-lg font-semibold mb-4">Professional Information</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium">Company</label>
            <input v-model="form.company" placeholder="e.g., Bali Real Estate Co." class="mt-1 w-full border rounded px-3 py-2" />
            <p v-if="errors.company" class="text-red-600 text-sm mt-1">{{ errors.company }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium">Title</label>
            <input v-model="form.title" placeholder="e.g., Senior Property Agent" class="mt-1 w-full border rounded px-3 py-2" />
            <p v-if="errors.title" class="text-red-600 text-sm mt-1">{{ errors.title }}</p>
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium">Bio</label>
          <textarea v-model="form.bio" rows="4" placeholder="Tell clients about your experience and expertise..." class="mt-1 w-full border rounded px-3 py-2"></textarea>
          <p v-if="errors.bio" class="text-red-600 text-sm mt-1">{{ errors.bio }}</p>
        </div>
      </div>

      <!-- Contact Information -->
      <div class="bg-white p-6 rounded-lg border space-y-4">
        <h2 class="text-lg font-semibold mb-4">Contact Information</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium">Website</label>
            <input v-model="form.website" placeholder="e.g., balirealestate.com" class="mt-1 w-full border rounded px-3 py-2" />
            <p v-if="errors.website" class="text-red-600 text-sm mt-1">{{ errors.website }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium">WhatsApp</label>
            <input v-model="form.whatsapp" placeholder="e.g., +62-812-3456-7890" class="mt-1 w-full border rounded px-3 py-2" />
            <p v-if="errors.whatsapp" class="text-red-600 text-sm mt-1">{{ errors.whatsapp }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium">LINE ID</label>
            <input v-model="form.line_id" placeholder="e.g., bali_agent" class="mt-1 w-full border rounded px-3 py-2" />
            <p v-if="errors.line_id" class="text-red-600 text-sm mt-1">{{ errors.line_id }}</p>
          </div>
        </div>
      </div>

      <!-- Preferences -->
      <div class="bg-white p-6 rounded-lg border space-y-4">
        <h2 class="text-lg font-semibold mb-4">Preferences</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium">Language</label>
            <select v-model="form.language" class="mt-1 w-full border rounded px-3 py-2">
              <option value="ja">日本語</option>
              <option value="en">English</option>
              <option value="id">Bahasa Indonesia</option>
            </select>
          </div>
          
          <div>
            <label class="block text-sm font-medium">Currency</label>
            <select v-model="form.currency" class="mt-1 w-full border rounded px-3 py-2">
              <option value="JPY">JPY</option>
              <option value="USD">USD</option>
              <option value="IDR">IDR</option>
            </select>
          </div>
        </div>
      </div>

      <div class="flex gap-3">
        <button type="submit" class="px-6 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Save Profile</button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({ user: Object, errors: Object })

const form = reactive({ 
  name: props.user?.name || '',
  email: props.user?.email || '',
  phone: props.user?.phone || '',
  language: props.user?.language || 'ja',
  currency: props.user?.currency || 'USD',
  company: props.user?.company || '',
  title: props.user?.title || '',
  bio: props.user?.bio || '',
  website: props.user?.website || '',
  whatsapp: props.user?.whatsapp || '',
  line_id: props.user?.line_id || '',
  location: props.user?.location || '',
  avatar: null 
})

const preview = ref('')
const storageUrl = (p)=> p ? `/storage/${p}` : ''

function onFile(e){
  const f = e.target.files?.[0]
  if(!f) return
  form.avatar = f
  const reader = new FileReader()
  reader.onload = ()=> preview.value = reader.result
  reader.readAsDataURL(f)
}

function submitProfile() {
  // アバターがある場合はFormData、ない場合は通常のオブジェクト
  if (form.avatar && form.avatar instanceof File) {
    const data = new FormData()
    Object.keys(form).forEach(key => {
      if (form[key] !== null && form[key] !== undefined) {
        data.append(key, form[key])
      }
    })
    data.append('_method', 'PUT')
    
    router.post(route('agent.profile.update'), data, { 
      forceFormData: true,
      onError: (errors) => console.log('Validation errors:', errors)
    })
  } else {
    // 通常のオブジェクトで送信
    const data = {
      name: form.name || '',
      email: form.email || '',
      phone: form.phone || '',
      language: form.language || 'ja',
      currency: form.currency || 'USD',
      company: form.company || '',
      title: form.title || '',
      bio: form.bio || '',
      website: form.website || '',
      whatsapp: form.whatsapp || '',
      line_id: form.line_id || '',
      location: form.location || ''
    }
    
    router.put(route('agent.profile.update'), data, { 
      onError: (errors) => console.log('Validation errors:', errors)
    })
  }
}
</script>