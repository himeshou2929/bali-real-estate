<template>
  <div class="min-h-screen bg-gray-50">
    <NavBar />
    <div class="max-w-6xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">My Dashboard</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Main column -->
      <div class="lg:col-span-2 space-y-4">
        <section class="grid sm:grid-cols-2 gap-4">
          <div class="border rounded-xl p-4">
            <div class="text-sm text-gray-500">Favorites</div>
            <div class="text-3xl font-semibold">{{ stats.favorites }}</div>
            <a class="text-blue-600 underline text-sm" href="/user/favorites">View</a>
          </div>
          <div class="border rounded-xl p-4">
            <div class="text-sm text-gray-500">Inquiries</div>
            <div class="text-3xl font-semibold">{{ stats.inquiries }}</div>
            <a class="text-blue-600 underline text-sm" href="/user/inquiries">View</a>
          </div>
        </section>

        <!-- ここに今後カード追加可（最近見た物件、おすすめ など） -->
        <section class="border rounded-xl p-4">
          <div class="font-semibold mb-2">Tips</div>
          <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
            <li>プロフィールの言語・通貨を設定すると表示が最適化されます。</li>
            <li>Inquiryの返信はメールでも届き、詳細は「My Inquiries」で確認できます。</li>
          </ul>
        </section>
      </div>

      <!-- Right column (sticky profile) -->
      <ProfileCard :me="me" />
    </div>
    </div>
  </div>
</template>
<script setup>
import NavBar from '@/Components/NavBar.vue'
import ProfileCard from '@/Components/User/ProfileCard.vue'
defineProps({ stats: Object, me: Object })
</script>