<template>
  <div class="p-4 border rounded space-y-4">
    <h2 class="font-semibold">Investment Simulation</h2>
    <div class="grid grid-cols-2 gap-4">
      <div>
        <label class="text-sm">Price (IDR)</label>
        <input v-model.number="price" type="number" class="w-full border rounded px-2 py-1"/>
      </div>
      <div>
        <label class="text-sm">Expected Rent (IDR / month)</label>
        <input v-model.number="rent" type="number" class="w-full border rounded px-2 py-1"/>
      </div>
    </div>
    <div>
      <p>Yield: <span class="font-bold">{{ yieldCalc }} %</span></p>
      <p>≈ {{ yenPrice.toLocaleString() }} 円</p>
    </div>
    <div class="h-64">
      <Line :data="chartData" :options="chartOptions"/>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Line } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement } from 'chart.js'
ChartJS.register(Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement)

const price = ref(1000000000)
const rent = ref(5000000)
const rate = ref(0.009) // IDR->JPY 為替例

const yieldCalc = computed(()=> ((rent.value*12)/price.value*100).toFixed(2) )
const yenPrice = computed(()=> price.value * rate.value )

const chartData = computed(()=>({
  labels: Array.from({length:10},(_,i)=> `Year ${i+1}`),
  datasets:[{
    label:"Cumulative Return (IDR)",
    data:Array.from({length:10},(_,i)=> rent.value*12*(i+1)),
    borderColor:"#2563eb",
    fill:false
  }]
}))
const chartOptions = { responsive:true, maintainAspectRatio:false, plugins:{legend:{display:true}} }
</script>