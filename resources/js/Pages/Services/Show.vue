<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const props = defineProps({
  service: Object,
})

const selectedDate = ref(new Date().toISOString().slice(0, 10))
const slots = ref([])
const name = ref('')
const phone = ref('')
const message = ref(null)

async function loadSlots() {
  try {
    const { data } = await axios.get(`/services/${props.service.id}/slots?date=${selectedDate.value}`)
    slots.value = data
  } catch (e) {
    console.error(e)
  }
}

async function book(time) {
  const startAt = `${selectedDate.value}T${time}`

  try {
    const { data } = await axios.post(`/services/${props.service.id}/book`, {
      client_name: name.value,
      client_phone: phone.value,
      start_at: startAt,
    })

    if (data.success) {
      message.value = '✅ Бронирование успешно!'
      await loadSlots()
    } else {
      message.value = `❌ ${data.message}`
    }
  } catch (e) {
    message.value = 'Ошибка при бронировании'
  }
}

onMounted(loadSlots)
</script>

<template>
  <div class="p-8 space-y-6">
    <h1 class="text-2xl font-bold">{{ service.name }}</h1>

    <div>
      <label class="block mb-1">Дата:</label>
      <input type="date" v-model="selectedDate" @change="loadSlots" class="border px-2 py-1 rounded" />
    </div>

    <div>
      <label class="block mb-1">Имя:</label>
      <input v-model="name" class="border px-2 py-1 rounded w-full" />
    </div>

    <div>
      <label class="block mb-1">Телефон:</label>
      <input v-model="phone" class="border px-2 py-1 rounded w-full" />
    </div>

    <div>
      <h2 class="text-lg font-semibold mb-2">Свободные слоты:</h2>
      <div v-if="slots.length" class="flex flex-wrap gap-2">
        <button
            v-for="t in slots"
            :key="t"
            @click="book(t)"
            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition"
        >
          {{ t }}
        </button>
      </div>
      <p v-else class="text-gray-500">Нет доступных слотов</p>
    </div>

    <div v-if="message" class="mt-4 font-semibold">{{ message }}</div>
  </div>
</template>
