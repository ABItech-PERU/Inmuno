<script setup>
import { computed } from 'vue';
import { FunnelIcon, MagnifyingGlassIcon, XMarkIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
  form: Object,
  tipos: Array
});

const emit = defineEmits(['search','clear']);

const hasFilters = computed(()=> props.form.search || props.form.tipo || props.form.activa);
</script>

<template>
  <div class="hidden md:block bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
        <div class="relative">
          <input
            v-model="form.search"
            @keyup.enter="$emit('search')"
            type="text"
            placeholder="Buscar por nombre o fabricante..."
            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
          />
          <MagnifyingGlassIcon class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" />
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
        <select
          v-model="form.tipo"
          @change="$emit('search')"
          class="w-full py-2 px-3 border border-gray-300 rounded-md focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
        >
          <option value="">Todos los tipos</option>
          <option v-for="tipo in tipos" :key="tipo" :value="tipo">{{ tipo }}</option>
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
        <select
          v-model="form.activa"
          @change="$emit('search')"
          class="w-full py-2 px-3 border border-gray-300 rounded-md focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
        >
          <option value="">Todas</option>
          <option value="true">Activas</option>
          <option value="false">Inactivas</option>
        </select>
      </div>

      <div class="flex items-end space-x-2">
        <button
          @click="$emit('search')"
          class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm bg-indigo-600 text-sm font-medium text-white hover:bg-indigo-700"
        >
          <FunnelIcon class="h-4 w-4 mr-2" />
          Filtrar
        </button>
        <button
          v-if="hasFilters"
          @click="$emit('clear')"
          class="inline-flex items-center justify-center px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50"
        >
          <XMarkIcon class="h-4 w-4" />
        </button>
      </div>
    </div>
  </div>
</template>
