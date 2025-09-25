<template>
  <div class="min-h-screen bg-gray-50">
    <NavBar />
    
    <div class="max-w-3xl mx-auto px-4 py-8 space-y-6">
    
    <h1 class="text-2xl font-semibold">お問い合わせ</h1>

    <div v-if="property" class="p-4 border rounded-2xl bg-gray-50">
      <div class="text-sm opacity-70 mb-1">物件へのお問い合わせ</div>
      <div class="flex items-center justify-between gap-3">
        <div>
          <div class="font-medium">
            {{ property.title || ('Property #' + property.id) }}
          </div>
          <div class="text-sm opacity-70">
            <span v-if="property.area">エリア：{{ property.area.name }}</span>
            <span v-if="property.price_usd"> · 価格：${{ new Intl.NumberFormat().format(property.price_usd) }}</span>
          </div>
        </div>
        <a :href="`/properties/${property.id}`" class="text-sm underline">物件を見る</a>
      </div>
    </div>

    <form method="POST" action="/inquiries" class="space-y-4">
      <input type="hidden" name="_token" :value="csrf" />
      <input type="hidden" name="property_id" :value="form.property_id" />

      <div class="grid md:grid-cols-2 gap-4">
        <div>
          <label class="block text-xs mb-1">お名前</label>
          <input name="name" v-model="form.name" class="w-full border rounded px-3 py-2" required />
        </div>
        <div>
          <label class="block text-xs mb-1">メール</label>
          <input name="email" v-model="form.email" type="email" class="w-full border rounded px-3 py-2" required />
        </div>
        <div>
          <label class="block text-xs mb-1">電話番号</label>
          <input name="phone" v-model="form.phone" class="w-full border rounded px-3 py-2" />
        </div>
        <div>
          <label class="block text-xs mb-1">希望連絡方法</label>
          <select name="contact_method" v-model="form.contact_method" class="w-full border rounded px-3 py-2">
            <option value="email">メール</option>
            <option value="phone">電話</option>
            <option value="both">両方</option>
          </select>
        </div>
      </div>

      <div class="grid md:grid-cols-2 gap-4">
        <div>
          <label class="block text-xs mb-1">種別</label>
          <select name="type" v-model="form.type" class="w-full border rounded px-3 py-2">
            <option value="info">一般問い合わせ</option>
            <option value="viewing">内見予約</option>
            <option value="offer">購入相談</option>
            <option value="other">その他</option>
          </select>
        </div>
        <div>
          <label class="block text-xs mb-1">希望日時</label>
          <input name="preferred_date" v-model="form.preferred_date" type="datetime-local" class="w-full border rounded px-3 py-2" />
        </div>
      </div>

      <div>
        <label class="block text-xs mb-1">件名</label>
        <input name="subject" v-model="form.subject" class="w-full border rounded px-3 py-2" placeholder="例：この物件の内見を希望します" required />
      </div>

      <div>
        <label class="block text-xs mb-1">メッセージ</label>
        <textarea name="message" v-model="form.message" rows="5" class="w-full border rounded px-3 py-2" required></textarea>
      </div>

      <div class="flex items-center gap-3">
        <button class="px-4 py-2 rounded bg-black text-white">送信</button>
        <span class="text-sm opacity-70">物件ID: {{ form.property_id || 'なし' }}</span>
      </div>
    </form>
    </div>
  </div>
</template>

<script setup>
import { reactive, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import NavBar from '@/Components/NavBar.vue'

const page = usePage()
const csrf = computed(() => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '')

const props = defineProps({
  property: { type: Object, default: null },
  defaults: { type: Object, default: () => ({}) }
})

const form = reactive({
  name: props.defaults.name || '',
  email: props.defaults.email || '',
  phone: props.defaults.phone || '',
  type: props.defaults.type || 'info',
  contact_method: props.defaults.contact_method || 'email',
  preferred_date: props.defaults.preferred_date || '',
  subject: props.defaults.subject || '',
  message: props.defaults.message || '',
  property_id: props.defaults.property_id || (props.property?.id ?? 0),
})
</script>