<template>
  <div class="min-h-screen bg-gray-50">
    <NavBar />
    
    <div class="max-w-7xl mx-auto px-4 py-6">
      <h1 class="text-2xl font-semibold mb-6">レポート・統計</h1>
      
      <!-- 物件統計 -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg p-6 shadow">
          <h3 class="text-sm font-medium text-gray-500">総物件数</h3>
          <p class="text-2xl font-bold text-gray-900">{{ propertyStats.total }}</p>
        </div>
        <div class="bg-white rounded-lg p-6 shadow">
          <h3 class="text-sm font-medium text-gray-500">公開中</h3>
          <p class="text-2xl font-bold text-green-600">{{ propertyStats.published }}</p>
        </div>
        <div class="bg-white rounded-lg p-6 shadow">
          <h3 class="text-sm font-medium text-gray-500">販売可能</h3>
          <p class="text-2xl font-bold text-blue-600">{{ propertyStats.available }}</p>
        </div>
        <div class="bg-white rounded-lg p-6 shadow">
          <h3 class="text-sm font-medium text-gray-500">交渉中</h3>
          <p class="text-2xl font-bold text-orange-600">{{ propertyStats.pending }}</p>
        </div>
      </div>
      
      <!-- 問い合わせ統計 -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-lg p-6 shadow">
          <h3 class="text-sm font-medium text-gray-500">総問い合わせ数</h3>
          <p class="text-2xl font-bold text-gray-900">{{ inquiryStats.total }}</p>
        </div>
        <div class="bg-white rounded-lg p-6 shadow">
          <h3 class="text-sm font-medium text-gray-500">今月の問い合わせ</h3>
          <p class="text-2xl font-bold text-green-600">{{ inquiryStats.this_month }}</p>
        </div>
        <div class="bg-white rounded-lg p-6 shadow">
          <h3 class="text-sm font-medium text-gray-500">今週の問い合わせ</h3>
          <p class="text-2xl font-bold text-blue-600">{{ inquiryStats.this_week }}</p>
        </div>
      </div>
      
      <!-- チャート・分析 -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- エリア別物件数 -->
        <div class="bg-white rounded-lg p-6 shadow">
          <h3 class="text-lg font-semibold mb-4">エリア別物件数</h3>
          <div class="space-y-3">
            <div v-for="area in propertiesByArea" :key="area.name" class="flex justify-between items-center">
              <span class="text-sm text-gray-600">{{ area.name }}</span>
              <div class="flex items-center">
                <div class="w-20 bg-gray-200 rounded-full h-2 mr-3">
                  <div class="bg-blue-600 h-2 rounded-full" :style="{ width: (area.count / Math.max(...propertiesByArea.map(a => a.count))) * 100 + '%' }"></div>
                </div>
                <span class="text-sm font-medium">{{ area.count }}</span>
              </div>
            </div>
          </div>
        </div>
        
        <!-- 価格帯別物件数 -->
        <div class="bg-white rounded-lg p-6 shadow">
          <h3 class="text-lg font-semibold mb-4">価格帯別物件数</h3>
          <div class="space-y-3">
            <div v-for="price in propertiesByPriceRange" :key="price.price_range" class="flex justify-between items-center">
              <span class="text-sm text-gray-600">{{ price.price_range }}</span>
              <div class="flex items-center">
                <div class="w-20 bg-gray-200 rounded-full h-2 mr-3">
                  <div class="bg-green-600 h-2 rounded-full" :style="{ width: (price.count / Math.max(...propertiesByPriceRange.map(p => p.count))) * 100 + '%' }"></div>
                </div>
                <span class="text-sm font-medium">{{ price.count }}</span>
              </div>
            </div>
          </div>
        </div>
        
        <!-- 月別問い合わせ推移 -->
        <div class="bg-white rounded-lg p-6 shadow md:col-span-2">
          <h3 class="text-lg font-semibold mb-4">月別問い合わせ推移（過去6ヶ月）</h3>
          <div class="flex items-end space-x-2 h-40">
            <div v-for="month in monthlyInquiries" :key="month.month" class="flex-1 flex flex-col items-center">
              <div class="bg-blue-500 w-full rounded-t" :style="{ height: Math.max(month.count / Math.max(...monthlyInquiries.map(m => m.count)) * 120, 10) + 'px' }"></div>
              <span class="text-xs text-gray-600 mt-2 text-center">{{ month.month }}</span>
              <span class="text-xs font-medium">{{ month.count }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import NavBar from '@/Components/NavBar.vue'

defineProps({
  propertyStats: { type: Object, required: true },
  inquiryStats: { type: Object, required: true },
  propertiesByArea: { type: Array, required: true },
  propertiesByPriceRange: { type: Array, required: true },
  monthlyInquiries: { type: Array, required: true },
})
</script>