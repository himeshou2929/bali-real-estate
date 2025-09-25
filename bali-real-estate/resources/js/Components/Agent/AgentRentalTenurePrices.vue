<script setup>
const props = defineProps({
  form: { type: Object, required: true }
})

for (const k of ['monthly_rent','yearly_rent','leasehold_price','freehold_price','leasehold_years']) {
  if (props.form[k] === undefined) props.form[k] = null
}

function yen(n){
  if(n===null || n===undefined || n==='') return ''
  try { return new Intl.NumberFormat('ja-JP').format(Number(n)) + ' 円' } catch(e){ return n }
}
</script>

<template>
  <div class="mt-6 p-4 border rounded-xl space-y-4">
    <h3 class="font-semibold">レンタル & 所有形態 価格</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <div>
        <label class="block text-sm text-gray-600 mb-1">月額レンタル（円）</label>
        <input type="number" min="0" v-model.number="form.monthly_rent" class="w-full border rounded-md px-3 py-2" placeholder="例: 250000">
        <p class="text-xs text-gray-500 mt-1">{{ yen(form.monthly_rent) }}</p>
      </div>
      <div>
        <label class="block text-sm text-gray-600 mb-1">年間レンタル（円）</label>
        <input type="number" min="0" v-model.number="form.yearly_rent" class="w-full border rounded-md px-3 py-2" placeholder="例: 3000000">
        <p class="text-xs text-gray-500 mt-1">{{ yen(form.yearly_rent) }}</p>
      </div>
      <div>
        <div class="mb-3">
          <label class="block text-sm text-gray-600 mb-1">LEASEHOLD年数</label>
          <input type="number" min="1" max="99" v-model.number="form.leasehold_years" class="w-full border rounded-md px-3 py-2" placeholder="例: 25">
          <p class="text-xs text-gray-500 mt-1">{{ form.leasehold_years ? form.leasehold_years + '年' : '' }}</p>
        </div>
        <label class="block text-sm text-gray-600 mb-1">LEASEHOLD（円）</label>
        <input type="number" min="0" v-model.number="form.leasehold_price" class="w-full border rounded-md px-3 py-2" placeholder="例: 180000000">
        <p class="text-xs text-gray-500 mt-1">{{ yen(form.leasehold_price) }}</p>
      </div>
      <div>
        <label class="block text-sm text-gray-600 mb-1">FREEHOLD（円）</label>
        <input type="number" min="0" v-model.number="form.freehold_price" class="w-full border rounded-md px-3 py-2" placeholder="例: 320000000">
        <p class="text-xs text-gray-500 mt-1">{{ yen(form.freehold_price) }}</p>
      </div>
    </div>
  </div>
</template>