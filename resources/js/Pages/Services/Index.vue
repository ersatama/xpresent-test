<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  services: Array
})
</script>

<template>
  <div class="p-8">
    <h1 class="text-2xl font-bold mb-6">Список услуг</h1>

    <div class="grid gap-4">
      <Link
          v-for="service in services"
          :key="service.id"
          :href="`/services/${service.id}`"
          class="block border rounded-lg p-4 hover:bg-gray-100 transition"
      >
        <h2 class="text-lg font-semibold">{{ service.name }}</h2>
        <p class="text-sm text-gray-500">Длительность: {{ service.duration_minutes }} минут</p>
      </Link>
    </div>
  </div>
</template>
