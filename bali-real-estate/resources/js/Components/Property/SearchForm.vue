<template>
  <form @submit.prevent="apply" class="space-y-4">
    <!-- 基本検索条件 -->
    <div class="grid md:grid-cols-7 gap-3 items-end">
      <div class="md:col-span-2">
        <label class="block text-xs mb-1">キーワード</label>
        <input v-model="state.q" class="w-full border rounded px-3 py-2" placeholder="エリア/住所/説明 など"/>
      </div>
      <div>
        <label class="block text-xs mb-1">エリア</label>
        <select v-model="state.area_id" class="w-full border rounded px-3 py-2">
          <option value="">全エリア</option>
          <option v-for="area in areas" :key="area.id" :value="area.id">{{ area.name }}</option>
        </select>
      </div>
      <div class="grid grid-cols-2 gap-2">
        <div>
          <label class="block text-xs mb-1">ベッド数以上</label>
          <input v-model.number="state.beds" type="number" min="0" class="w-full border rounded px-3 py-2"/>
        </div>
        <div>
          <label class="block text-xs mb-1">バス数以上</label>
          <input v-model.number="state.baths" type="number" min="0" class="w-full border rounded px-3 py-2"/>
        </div>
      </div>
      <div>
        <label class="block text-xs mb-1">最低利回り(%)</label>
        <input v-model.number="state.yield_min" type="number" step="0.1" min="0" class="w-full border rounded px-3 py-2"/>
      </div>
      <div>
        <label class="block text-xs mb-1">並び替え</label>
        <select v-model="state.sortBy" class="w-full border rounded px-3 py-2">
          <option value="">指定なし</option>
          <option value="latest">新着順</option>
          <option value="popular">人気順</option>
          <option value="price_asc">価格(安い順)</option>
          <option value="price_desc">価格(高い順)</option>
        </select>
      </div>
    </div>

    <!-- レンタル & 所有形態価格フィルター -->
    <div class="bg-blue-50 p-4 rounded-lg">
      <h3 class="text-sm font-semibold mb-3 text-blue-900">レンタル & 所有形態で絞り込み</h3>
      <p class="text-xs text-gray-600 mb-3">チェックした項目に金額が設定されている物件のみ表示します</p>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="border border-blue-200 rounded-lg p-3 bg-white">
          <div class="flex items-start gap-2 mb-2">
            <input type="checkbox" v-model="state.has_monthly_rent" id="has_monthly" class="mt-1" />
            <label for="has_monthly" class="text-sm font-medium text-gray-700 cursor-pointer">月額レンタル</label>
          </div>
          <div v-if="state.has_monthly_rent" class="space-y-2">
            <input v-model.number="state.monthly_rent_max" type="number" min="0" class="w-full border border-gray-300 rounded px-2 py-1 text-sm" placeholder="上限金額（円）"/>
            <p class="text-xs text-gray-500">{{ state.monthly_rent_max ? `〜${new Intl.NumberFormat('ja-JP').format(state.monthly_rent_max)}円` : '上限なし' }}</p>
          </div>
          <p v-else class="text-xs text-gray-400">月額レンタル物件を検索</p>
        </div>
        
        <div class="border border-blue-200 rounded-lg p-3 bg-white">
          <div class="flex items-start gap-2 mb-2">
            <input type="checkbox" v-model="state.has_yearly_rent" id="has_yearly" class="mt-1" />
            <label for="has_yearly" class="text-sm font-medium text-gray-700 cursor-pointer">年間レンタル</label>
          </div>
          <div v-if="state.has_yearly_rent" class="space-y-2">
            <input v-model.number="state.yearly_rent_max" type="number" min="0" class="w-full border border-gray-300 rounded px-2 py-1 text-sm" placeholder="上限金額（円）"/>
            <p class="text-xs text-gray-500">{{ state.yearly_rent_max ? `〜${new Intl.NumberFormat('ja-JP').format(state.yearly_rent_max)}円` : '上限なし' }}</p>
          </div>
          <p v-else class="text-xs text-gray-400">年間レンタル物件を検索</p>
        </div>
        
        <div class="border border-blue-200 rounded-lg p-3 bg-white">
          <div class="flex items-start gap-2 mb-2">
            <input type="checkbox" v-model="state.has_leasehold" id="has_leasehold" class="mt-1" />
            <label for="has_leasehold" class="text-sm font-medium text-gray-700 cursor-pointer">LEASEHOLD</label>
          </div>
          <div v-if="state.has_leasehold" class="space-y-2">
            <input v-model.number="state.leasehold_price_max" type="number" min="0" class="w-full border border-gray-300 rounded px-2 py-1 text-sm" placeholder="上限金額（円）"/>
            <p class="text-xs text-gray-500">{{ state.leasehold_price_max ? `〜${new Intl.NumberFormat('ja-JP').format(state.leasehold_price_max)}円` : '上限なし' }}</p>
          </div>
          <p v-else class="text-xs text-gray-400">LEASEHOLD物件を検索</p>
        </div>
        
        <div class="border border-blue-200 rounded-lg p-3 bg-white">
          <div class="flex items-start gap-2 mb-2">
            <input type="checkbox" v-model="state.has_freehold" id="has_freehold" class="mt-1" />
            <label for="has_freehold" class="text-sm font-medium text-gray-700 cursor-pointer">FREEHOLD</label>
          </div>
          <div v-if="state.has_freehold" class="space-y-2">
            <input v-model.number="state.freehold_price_max" type="number" min="0" class="w-full border border-gray-300 rounded px-2 py-1 text-sm" placeholder="上限金額（円）"/>
            <p class="text-xs text-gray-500">{{ state.freehold_price_max ? `〜${new Intl.NumberFormat('ja-JP').format(state.freehold_price_max)}円` : '上限なし' }}</p>
          </div>
          <p v-else class="text-xs text-gray-400">FREEHOLD物件を検索</p>
        </div>
      </div>
    </div>


    <!-- 検索ボタン -->
    <div class="flex gap-2">
      <button class="px-4 py-2 rounded bg-black text-white">検索</button>
      <button type="button" class="px-4 py-2 rounded border" @click="reset">条件クリア</button>
    </div>
  </form>
</template>

<script setup>
import { reactive, computed } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({ 
  initial: { type: Object, default: () => ({}) },
  areas: { type: Array, default: () => ([]) }
})
// Ensure sortBy defaults to empty string (avoid conflict with Array.sort)

const state = reactive({
  q: props.initial.q ?? '',
  area_id: props.initial.area_id ?? '',
  min_price: props.initial.min_price ?? 0,
  max_price: props.initial.max_price ?? 0,
  beds: props.initial.beds ?? 0,
  baths: props.initial.baths ?? 0,
  yield_min: props.initial.yield_min ?? 0,
  sortBy: (props.initial && typeof props.initial === 'object' && !Array.isArray(props.initial)) ? (props.initial.sort || '') : '',
  has_monthly_rent: props.initial.has_monthly_rent === '1' || false,
  monthly_rent_max: props.initial.monthly_rent_max ?? 0,
  has_yearly_rent: props.initial.has_yearly_rent === '1' || false,
  yearly_rent_max: props.initial.yearly_rent_max ?? 0,
  has_leasehold: props.initial.has_leasehold === '1' || false,
  leasehold_price_max: props.initial.leasehold_price_max ?? 0,
  has_freehold: props.initial.has_freehold === '1' || false,
  freehold_price_max: props.initial.freehold_price_max ?? 0,
})



function apply() {
  const params = new URLSearchParams()
  Object.entries(state).forEach(([k,v]) => {
    // sortBy は sort として送信
    const paramKey = k === 'sortBy' ? 'sort' : k
    
    // チェックボックスは true の時のみ送信
    if (k.startsWith('has_')) {
      if (v === true) params.set(k, '1')
    }
    // その他の値は 0 以外を送信
    else if (v !== '' && v !== 0 && v !== null && v !== undefined) {
      params.set(paramKey, v)
    }
  })
  router.visit(`/properties?${params.toString()}`)
}

function reset() {
  Object.keys(state).forEach(k => {
    if (k === 'sortBy') state[k] = ''
    else if (k.startsWith('has_')) state[k] = false
    else if (typeof state[k] === 'number') state[k] = 0
    else state[k] = ''
  })
  router.visit('/properties')
}
</script>