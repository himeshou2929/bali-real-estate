<script setup>
const props = defineProps({
  property: { type: Object, required: true }
})
function yen(n){
  if(n===null || n===undefined || n==='') return null
  try{ return new Intl.NumberFormat('ja-JP').format(Number(n)) + ' 円' }catch(e){ return n }
}
</script>

<template>
  <div class="mt-2 text-sm text-gray-700 space-y-1">
    <div v-if="property.monthly_rent">月額レンタル：<strong>{{ yen(property.monthly_rent) }}</strong></div>
    <div v-if="property.yearly_rent">年間レンタル：<strong>{{ yen(property.yearly_rent) }}</strong></div>
    <div v-if="property.leasehold_price">
      LEASEHOLD<span v-if="property.leasehold_years"> {{ property.leasehold_years }}年</span>：<strong>{{ yen(property.leasehold_price) }}</strong>
    </div>
    <div v-if="property.freehold_price">FREEHOLD：<strong>{{ yen(property.freehold_price) }}</strong></div>
  </div>
</template>