<template>
  <div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
      <div class="flex justify-between items-start mb-6">
        <div>
          <h1 class="text-3xl font-bold mb-2">{{ event.name }}</h1>
          <p class="text-gray-600">{{ event.description }}</p>
        </div>
        <div class="flex space-x-4">
          <Link :href="`/events/${event.id}/edit`" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Düzenle
          </Link>
          <button @click="deleteEvent" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
            Sil
          </button>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
          <div class="mb-6">
            <h2 class="text-xl font-semibold mb-4">Etkinlik Bilgileri</h2>
            <div class="space-y-3">
              <p class="flex items-center text-gray-600">
                <i class="fas fa-calendar-alt w-6"></i>
                {{ new Date(event.event_date).toLocaleDateString() }}
              </p>
              <p class="flex items-center text-gray-600">
                <i class="fas fa-map-marker-alt w-6"></i>
                {{ event.location }}
              </p>
              <p class="flex items-center text-gray-600">
                <i class="fas fa-users w-6"></i>
                {{ event.participants.length }} / {{ event.max_participants }}
              </p>
              <p class="flex items-center text-gray-600">
                <i class="fas fa-toggle-on w-6"></i>
                {{ event.is_active ? 'Aktif' : 'Pasif' }}
              </p>
            </div>
          </div>

          <div>
            <h2 class="text-xl font-semibold mb-4">QR Kod</h2>
            <div class="bg-gray-100 p-4 rounded-lg">
              <QRCode :value="qrCodeUrl" :size="200" level="H" />
            </div>
          </div>
        </div>

        <div>
          <h2 class="text-xl font-semibold mb-4">Katılımcılar</h2>
          <div class="space-y-4">
            <div v-for="participant in event.participants" :key="participant.id" 
                 class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
              <div>
                <p class="font-medium">{{ participant.name }}</p>
                <p class="text-sm text-gray-500">{{ participant.email }}</p>
              </div>
              <div class="flex items-center space-x-4">
                <span :class="[
                  'px-2 py-1 rounded text-sm',
                  participant.checked_in ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'
                ]">
                  {{ participant.checked_in ? 'Check-in Yapıldı' : 'Bekleniyor' }}
                </span>
                <button @click="deleteParticipant(participant.id)" 
                        class="text-red-500 hover:text-red-600">
                  <i class="fas fa-trash"></i>
                </button>
              </div>
            </div>
          </div>

          <form @submit.prevent="addParticipant" class="mt-6">
            <h3 class="text-lg font-medium mb-4">Yeni Katılımcı Ekle</h3>
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700">İsim</label>
                <input v-model="form.name" type="text" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">E-posta</label>
                <input v-model="form.email" type="email" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Telefon</label>
                <input v-model="form.phone" type="tel"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
              </div>
              <button type="submit" 
                      class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Ekle
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'
import QRCode from 'qrcode.vue'

const props = defineProps({
  event: {
    type: Object,
    required: true
  }
})

const qrCodeUrl = `${window.location.origin}/events/${props.event.id}/check-in/${props.event.qr_code}`

const form = useForm({
  name: '',
  email: '',
  phone: ''
})

const addParticipant = () => {
  form.post(`/events/${props.event.id}/participants`, {
    onSuccess: () => form.reset()
  })
}

const deleteParticipant = (participantId) => {
  if (confirm('Bu katılımcıyı silmek istediğinizden emin misiniz?')) {
    router.delete(`/events/${props.event.id}/participants/${participantId}`)
  }
}

const deleteEvent = () => {
  if (confirm('Bu etkinliği silmek istediğinizden emin misiniz?')) {
    router.delete(`/events/${props.event.id}`)
  }
}
</script> 