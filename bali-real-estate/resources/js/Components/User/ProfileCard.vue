<template>
  <aside class="border rounded-xl p-4 sticky top-6 bg-white">
    <div class="flex items-center gap-3">
      <img v-if="avatarUrl" :src="avatarUrl" class="w-14 h-14 rounded-full object-cover border" />
      <div>
        <div class="font-semibold leading-tight">{{ me.name }}</div>
        <div class="text-xs text-gray-500">{{ me.email }}</div>
      </div>
    </div>

    <dl class="mt-4 space-y-2 text-sm">
      <div class="flex justify-between">
        <dt class="text-gray-500">Phone</dt><dd>{{ me.phone || '-' }}</dd>
      </div>
      <div class="flex justify-between">
        <dt class="text-gray-500">Language</dt><dd class="uppercase">{{ me.language }}</dd>
      </div>
      <div class="flex justify-between">
        <dt class="text-gray-500">Currency</dt><dd class="uppercase">{{ me.currency }}</dd>
      </div>
    </dl>

    <a href="/user/profile" class="mt-4 inline-flex items-center justify-center w-full px-3 py-2 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700">
      Edit profile
    </a>
  </aside>
</template>

<script setup>
const props = defineProps({ me: { type: Object, required: true } })
const avatarUrl = props.me?.avatar ? `/storage/${props.me.avatar}` : ''
</script>