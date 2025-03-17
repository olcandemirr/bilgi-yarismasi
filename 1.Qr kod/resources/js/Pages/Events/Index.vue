<template>
  <div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold">Etkinlikler</h1>
      <Link href="/events/create" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
        Yeni Etkinlik
      </Link>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="event in events.data" :key="event.id" class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-2">{{ event.name }}</h2>
        <p class="text-gray-600 mb-4">{{ event.description }}</p>
        <div class="flex flex-col space-y-2 text-sm text-gray-500">
          <p>
            <i class="fas fa-calendar-alt mr-2"></i>
            {{ new Date(event.event_date).toLocaleDateString() }}
          </p>
          <p>
            <i class="fas fa-map-marker-alt mr-2"></i>
            {{ event.location }}
          </p>
          <p>
            <i class="fas fa-users mr-2"></i>
            {{ event.participants_count || 0 }} / {{ event.max_participants }}
          </p>
        </div>
        <div class="mt-4 flex justify-between items-center">
          <Link :href="`/events/${event.id}`" class="text-blue-500 hover:text-blue-600">
            Detaylar
          </Link>
          <span :class="[
            'px-2 py-1 rounded text-sm',
            event.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
          ]">
            {{ event.is_active ? 'Aktif' : 'Pasif' }}
          </span>
        </div>
      </div>
    </div>

    <div class="mt-6">
      <Pagination :links="events.links" />
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import Pagination from '@/Components/Pagination.vue'

defineProps({
  events: {
    type: Object,
    required: true
  }
})
</script> 