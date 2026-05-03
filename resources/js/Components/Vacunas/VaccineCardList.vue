<script setup>
import { Link } from '@inertiajs/vue3';
import { BeakerIcon, EyeIcon, PencilIcon, ExclamationTriangleIcon, CheckCircleIcon, TrashIcon } from '@heroicons/vue/24/outline';

const props = defineProps({ vacunas: Array });
const emit = defineEmits(['toggle','delete']);

const formatEdad = (m) => {
  if (!m && m !== 0) return 'No especificada';
  if (m < 12) return `${m} ${m === 1 ? 'mes' : 'meses'}`;
  const y = Math.floor(m/12); const r = m % 12;
  return r === 0 ? `${y} ${y===1?'año':'años'}` : `${y}a ${r}m`;
};
</script>

<template>
  <div class="lg:hidden">
    <div class="divide-y divide-gray-200">
      <div v-for="vacuna in vacunas" :key="vacuna.id" class="p-4 sm:p-6">
        <div class="flex items-start space-x-4">
          <div class="flex-shrink-0">
            <div class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center">
              <BeakerIcon class="h-6 w-6 text-indigo-600" />
            </div>
          </div>
          <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between">
              <div class="flex-1">
                <h3 class="text-base font-medium text-gray-900 truncate">{{ vacuna.nombre }}</h3>
                <p class="text-sm text-gray-500 mt-1" v-if="vacuna.fabricante">{{ vacuna.fabricante }}</p>
              </div>
              <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ml-2" :class="vacuna.activa ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">{{ vacuna.activa ? 'Activa' : 'Inactiva' }}</span>
            </div>
            <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
              <div><span class="font-medium text-gray-500">Tipo:</span><span class="ml-1 text-gray-900">{{ vacuna.tipo }}</span></div>
              <div><span class="font-medium text-gray-500">Dosis:</span><span class="ml-1 text-gray-900">{{ vacuna.dosis_requeridas }}</span></div>
              <div v-if="vacuna.edad_minima !== null" class="sm:col-span-2">
                <span class="font-medium text-gray-500">Edad:</span>
                <span class="ml-1 text-gray-900">Desde {{ formatEdad(vacuna.edad_minima) }}<span v-if="vacuna.edad_maxima !== null"> hasta {{ formatEdad(vacuna.edad_maxima) }}</span></span>
              </div>
            </div>
            <div class="mt-4 flex flex-wrap items-center gap-3">
              <Link :href="'/admin/vacunas/' + vacuna.id" class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-900"><EyeIcon class="h-4 w-4 mr-1" />Ver</Link>
              <Link :href="'/admin/vacunas/' + vacuna.id + '/edit'" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900"><PencilIcon class="h-4 w-4 mr-1" />Editar</Link>
              <button @click="$emit('toggle', vacuna)" class="inline-flex items-center text-sm hover:text-yellow-900" :class="vacuna.activa ? 'text-yellow-600' : 'text-green-600'">
                <component :is="vacuna.activa ? ExclamationTriangleIcon : CheckCircleIcon" class="h-4 w-4 mr-1" />
                {{ vacuna.activa ? 'Desactivar' : 'Activar' }}
              </button>
              <button @click="$emit('delete', vacuna)" class="inline-flex items-center text-sm text-red-600 hover:text-red-900">
                <TrashIcon class="h-4 w-4 mr-1" />Eliminar
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
