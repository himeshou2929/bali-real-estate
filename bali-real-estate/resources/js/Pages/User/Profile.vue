<template>
  <div class="max-w-3xl mx-auto p-6 space-y-6">
    <h1 class="text-2xl font-bold">Profile</h1>
    <form @submit.prevent="submitProfile" class="space-y-4" enctype="multipart/form-data">
      <div class="flex items-center gap-4">
        <img v-if="preview || user.avatar" :src="preview || storageUrl(user.avatar)" class="w-16 h-16 rounded-full object-cover border" />
        <div>
          <input type="file" accept="image/*" @change="onFile" />
          <p class="text-xs text-gray-500">JPEG/PNG/WebP, up to 5MB</p>
        </div>
      </div>
      <div>
        <label class="block text-sm">Name</label>
        <input v-model="form.name" class="mt-1 w-full border rounded px-3 py-2" required />
        <p v-if="errors.name" class="text-red-600 text-sm">{{ errors.name }}</p>
      </div>
      <div>
        <label class="block text-sm">Email</label>
        <input v-model="form.email" type="email" class="mt-1 w-full border rounded px-3 py-2" required />
        <p v-if="errors.email" class="text-red-600 text-sm">{{ errors.email }}</p>
      </div>
      <div>
        <label class="block text-sm">Phone</label>
        <input v-model="form.phone" class="mt-1 w-full border rounded px-3 py-2" />
        <p v-if="errors.phone" class="text-red-600 text-sm">{{ errors.phone }}</p>
      </div>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm">Language</label>
          <select v-model="form.language" class="mt-1 w-full border rounded px-3 py-2">
            <option value="ja">日本語</option>
            <option value="en">English</option>
            <option value="id">Bahasa Indonesia</option>
          </select>
        </div>
        <div>
          <label class="block text-sm">Currency</label>
          <select v-model="form.currency" class="mt-1 w-full border rounded px-3 py-2">
            <option value="JPY">JPY</option>
            <option value="USD">USD</option>
            <option value="IDR">IDR</option>
          </select>
        </div>
      </div>

      <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white">Save</button>
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
  avatar: null 
})

const preview = ref('')
const storageUrl = (p)=> p ? `/storage/${p}` : ''

function submitProfile() {
  // アバターがある場合はFormData、ない場合は通常のオブジェクト
  if (form.avatar && form.avatar instanceof File) {
    const data = new FormData()
    data.append('name', form.name || '')
    data.append('email', form.email || '')
    data.append('phone', form.phone || '')
    data.append('language', form.language || 'ja')
    data.append('currency', form.currency || 'USD')
    data.append('avatar', form.avatar)
    data.append('_method', 'PUT')
    
    router.post(route('user.profile.update'), data, { 
      forceFormData: true,
      onSuccess:()=> location.reload(),
      onError: (errors) => console.log('Validation errors:', errors)
    })
  } else {
    // 通常のオブジェクトで送信
    const data = {
      name: form.name || '',
      email: form.email || '',
      phone: form.phone || '',
      language: form.language || 'ja',
      currency: form.currency || 'USD'
    }
    
    router.put(route('user.profile.update'), data, { 
      onSuccess:()=> location.reload(),
      onError: (errors) => console.log('Validation errors:', errors)
    })
  }
}

function onFile(e){
  const f = e.target.files?.[0]
  if(!f) return
  form.avatar = f
  const reader = new FileReader()
  reader.onload = ()=> preview.value = reader.result
  reader.readAsDataURL(f)
}
</script>