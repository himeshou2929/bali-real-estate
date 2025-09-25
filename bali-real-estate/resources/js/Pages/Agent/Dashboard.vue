<template>
  <div class="min-h-screen bg-gray-50">
    <NavBar />
    <div class="max-w-6xl mx-auto p-6 space-y-6">
    <h1 class="text-2xl font-bold">Agent Dashboard</h1>
    <div class="grid sm:grid-cols-3 gap-4">
      <div class="border rounded p-4">
        <div class="text-sm text-gray-500">Properties</div>
        <div class="text-2xl font-semibold">{{ stats.total_properties }}</div>
      </div>
      <div class="border rounded p-4">
        <div class="text-sm text-gray-500">Total Views</div>
        <div class="text-2xl font-semibold">{{ stats.total_views || 0 }}</div>
      </div>
      <div class="border rounded p-4">
        <div class="text-sm text-gray-500">Inquiries</div>
        <div class="text-2xl font-semibold">{{ stats.total_inquiries }}</div>
      </div>
    </div>
    <div class="mt-8">
      <h2 class="font-semibold mb-2">Inquiries Trend</h2>
      <div class="bg-white p-4 rounded border">
        <canvas ref="chartCanvas" width="400" height="200"></canvas>
      </div>
    </div>
    <div>
      <h2 class="font-semibold mt-6 mb-2">Recent Inquiries</h2>
      <ul>
        <li v-for="i in recent_inquiries" :key="i.id" class="border-b py-2">
          <div class="flex justify-between">
            <span>{{ i.subject }}</span>
            <span class="text-xs text-gray-500">{{ new Date(i.created_at).toLocaleString() }}</span>
          </div>
        </li>
      </ul>
    </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import NavBar from '@/Components/NavBar.vue'

const props = defineProps({
  stats: Object,
  recent_inquiries: Array,
  chart_data: Array
})

const chartCanvas = ref(null)

onMounted(() => {
  if (chartCanvas.value) {
    const ctx = chartCanvas.value.getContext('2d')
    
    // Simple chart implementation without external library
    const data = props.chart_data || [
      { date: '2025-01', count: 5 },
      { date: '2025-02', count: 8 },
      { date: '2025-03', count: 12 },
      { date: '2025-04', count: 7 },
      { date: '2025-05', count: 15 }
    ]
    
    const canvas = chartCanvas.value
    const width = canvas.width
    const height = canvas.height
    
    ctx.clearRect(0, 0, width, height)
    
    // Draw axes
    ctx.strokeStyle = '#e5e7eb'
    ctx.lineWidth = 1
    
    // Y axis
    ctx.beginPath()
    ctx.moveTo(40, 20)
    ctx.lineTo(40, height - 40)
    ctx.stroke()
    
    // X axis
    ctx.beginPath()
    ctx.moveTo(40, height - 40)
    ctx.lineTo(width - 20, height - 40)
    ctx.stroke()
    
    // Draw data points and line
    if (data.length > 0) {
      const maxValue = Math.max(...data.map(d => d.count))
      const stepX = (width - 60) / (data.length - 1)
      const stepY = (height - 60) / maxValue
      
      ctx.strokeStyle = '#2563eb'
      ctx.fillStyle = '#2563eb'
      ctx.lineWidth = 2
      
      ctx.beginPath()
      data.forEach((point, index) => {
        const x = 40 + index * stepX
        const y = height - 40 - point.count * stepY
        
        if (index === 0) {
          ctx.moveTo(x, y)
        } else {
          ctx.lineTo(x, y)
        }
        
        // Draw point
        ctx.fillRect(x - 2, y - 2, 4, 4)
      })
      ctx.stroke()
    }
  }
})
</script>