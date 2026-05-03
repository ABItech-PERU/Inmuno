<script setup>
import { Link } from '@inertiajs/vue3';
import { BeakerIcon, EyeIcon, PencilIcon, ExclamationTriangleIcon, CheckCircleIcon, TrashIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
  vacunas: Array
});
const emit = defineEmits(['toggle','delete']);

const formatEdad = (meses) => {
  if (!meses && meses !== 0) return 'No especificada';
  if (meses < 12) return `${meses} ${meses === 1 ? 'mes' : 'meses'}`;
  const años = Math.floor(meses / 12);
  const mesesRestantes = meses % 12;
  if (mesesRestantes === 0) return `${años} ${años === 1 ? 'año' : 'años'}`;
  return `${años}a ${mesesRestantes}m`;
};
</script>

<template>
  <div class="hidden lg:block overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vacuna</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dosis</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Edad</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        <tr v-for="vacuna in vacunas" :key="vacuna.id" class="hover:bg-gray-50">
          <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
              <div class="flex-shrink-0 h-10 w-10">
                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                  <BeakerIcon class="h-5 w-5 text-indigo-600" />
                </div>
              </div>
              <div class="ml-4">
                <div class="text-sm font-medium text-gray-900">{{ vacuna.nombre }}</div>
                <div class="text-sm text-gray-500" v-if="vacuna.fabricante">{{ vacuna.fabricante }}</div>
              </div>
            </div>
          </td>
          <td class="px-6 py-4 whitespace-nowrap">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">{{ vacuna.tipo }}</span>
          </td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            {{ vacuna.dosis_requeridas }} {{ vacuna.dosis_requeridas === 1 ? 'dosis' : 'dosis' }}
          </td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            <div>
              <div v-if="vacuna.edad_minima !== null">Desde: {{ formatEdad(vacuna.edad_minima) }}</div>
              <div v-if="vacuna.edad_maxima !== null" class="text-gray-500">Hasta: {{ formatEdad(vacuna.edad_maxima) }}</div>
            </div>
          </td>
          <td class="px-6 py-4 whitespace-nowrap">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" :class="vacuna.activa ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">{{ vacuna.activa ? 'Activa' : 'Inactiva' }}</span>
          </td>
          <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
            <div class="flex items-center space-x-3">
              <Link :href="'/admin/vacunas/' + vacuna.id" class="text-indigo-600 hover:text-indigo-900" title="Ver detalles">
                <EyeIcon class="h-4 w-4" />
              </Link>
              <Link :href="'/admin/vacunas/' + vacuna.id + '/edit'" class="text-gray-600 hover:text-gray-900" title="Editar">
                <PencilIcon class="h-4 w-4" />
              </Link>
              <button @click="$emit('toggle', vacuna)" class="hover:text-yellow-900" :class="vacuna.activa ? 'text-yellow-600' : 'text-green-600'" :title="vacuna.activa ? 'Desactivar' : 'Activar'">
                <component :is="vacuna.activa ? ExclamationTriangleIcon : CheckCircleIcon" class="h-4 w-4" />
              </button>
              <button @click="$emit('delete', vacuna)" class="text-red-600 hover:text-red-900" title="Eliminar">
                <TrashIcon class="h-4 w-4" />
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
