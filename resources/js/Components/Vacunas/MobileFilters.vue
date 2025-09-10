<script setup>
import { ref, computed } from 'vue';
import { FunnelIcon, MagnifyingGlassIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
  form: Object,
  tipos: Array
});

const emit = defineEmits(['search','clear']);
const open = ref(false);

const hasFilters = computed(()=> props.form.search || props.form.tipo || props.form.activa);
</script>

<template>
  <div class="md:hidden mb-4">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-sm font-medium text-gray-900">Filtros</h3>
        <button
          @click="open = !open"
          class="inline-flex items-center text-sm text-cyan-600 hover:text-cyan-900"
        >
          <FunnelIcon class="h-4 w-4 mr-1" />
          {{ open ? 'Ocultar' : 'Mostrar' }}
        </button>
      </div>

      <div class="relative mb-4">
        <input
          v-model="form.search"
          @keyup.enter="$emit('search')"
          type="text"
          placeholder="Buscar vacunas..."
          class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
        />
        <MagnifyingGlassIcon class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" />
      </div>

      <div v-show="open" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
          <select
            v-model="form.tipo"
            @change="$emit('search')"
            class="w-full py-2 px-3 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
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
            class="w-full py-2 px-3 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
          >
            <option value="">Todas</option>
            <option value="true">Activas</option>
            <option value="false">Inactivas</option>
          </select>
        </div>

        <div class="flex space-x-2">
          <button
            @click="$emit('search')"
            class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm bg-indigo-600 text-sm font-medium text-white hover:bg-indigo-700"
          >
            Aplicar
          </button>
          <button
            v-if="hasFilters"
            @click="$emit('clear')"
            class="inline-flex items-center justify-center px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50"
          >
            Limpiar
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
