<template><div ref="el" class="h-56 w-full rounded border" /></template>
<script setup>
import { ref, watch, onMounted } from 'vue'
import { Loader } from '@googlemaps/js-api-loader'
const props = defineProps({ lat:Number, lng:Number, zoom:{type:Number,default:14} })
const el = ref(null); let map, marker
const load = async()=> {
  if(!props.lat || !props.lng) return
  const loader = new Loader({ apiKey: import.meta.env.VITE_GOOGLE_MAPS_API_KEY || window.APP_GOOGLE_MAPS_API_KEY })
  const google = await loader.load()
  map = new google.maps.Map(el.value, { center:{lat:props.lat,lng:props.lng}, zoom:props.zoom })
  marker = new google.maps.Marker({ position:{lat:props.lat,lng:props.lng}, map })
}
onMounted(load); watch(()=>[props.lat,props.lng], load)
</script>