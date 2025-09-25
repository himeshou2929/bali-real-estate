<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const page = usePage()
const isAuthenticated = computed(() => !!page.props.auth?.user)
</script>

<template>
  <div v-if="!isAuthenticated" class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-6 mb-6">
    <h2 class="text-lg font-semibold text-gray-800 mb-3 text-center">会員登録してバリ不動産をもっと便利に</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      <Link href="/register/buyer" class="inline-flex items-center justify-center rounded-lg px-6 py-3 bg-blue-600 text-white font-medium hover:bg-blue-700 transition-colors">
        🏠 購入希望者として登録
      </Link>
      <Link href="/register/agent" class="inline-flex items-center justify-center rounded-lg px-6 py-3 bg-green-600 text-white font-medium hover:bg-green-700 transition-colors">
        🏢 エージェントとして登録
      </Link>
    </div>
  </div>
</template>