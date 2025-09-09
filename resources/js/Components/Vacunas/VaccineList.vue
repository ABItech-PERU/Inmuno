<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { BeakerIcon, EyeIcon, PencilIcon, ExclamationTriangleIcon, CheckCircleIcon, TrashIcon } from '@heroicons/vue/24/outline';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';

const props = defineProps({
    vacunas: Array
});

const emit = defineEmits(['toggle', 'delete']);

// Estado para el modal de confirmación
const showDeleteModal = ref(false);
const vacunaToDelete = ref(null);
const isDeleting = ref(false);

const formatEdad = (meses) => {
    if (!meses && meses !== 0) return 'No especificada';
    if (meses < 12) return `${meses} ${meses === 1 ? 'mes' : 'meses'}`;
    const años = Math.floor(meses / 12);
    const mesesRestantes = meses % 12;
    if (mesesRestantes === 0) return `${años} ${años === 1 ? 'año' : 'años'}`;
    return `${años}a ${mesesRestantes}m`;
};

const handleToggle = (vacuna) => {
    emit('toggle', vacuna);
};

const handleDeleteClick = (vacuna) => {
    vacunaToDelete.value = vacuna;
    showDeleteModal.value = true;
};

const confirmDelete = () => {
    if (vacunaToDelete.value) {
        isDeleting.value = true;
        emit('delete', vacunaToDelete.value);
        // El componente padre manejará el cierre del modal después de la eliminación exitosa
    }
};

const closeDeleteModal = () => {
    if (!isDeleting.value) {
        showDeleteModal.value = false;
        vacunaToDelete.value = null;
    }
};

// Función para ser llamada desde el componente padre cuando la eliminación es exitosa
const onDeleteSuccess = () => {
    isDeleting.value = false;
    showDeleteModal.value = false;
    vacunaToDelete.value = null;
};

// Función para ser llamada desde el componente padre cuando la eliminación falla
const onDeleteError = () => {
    isDeleting.value = false;
};

defineExpose({
    onDeleteSuccess,
    onDeleteError
});
</script>

<template>
    <div>
        <!-- Vista de tabla para pantallas grandes (lg y superiores) -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-cyan-50 to-cyan-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-cyan-700 uppercase tracking-wider">
                            Vacuna
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-cyan-700 uppercase tracking-wider">
                            Tipo
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-cyan-700 uppercase tracking-wider">
                            Dosis
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-cyan-700 uppercase tracking-wider">
                            Edad
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-cyan-700 uppercase tracking-wider">
                            Estado
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-cyan-700 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="vacuna in vacunas" :key="vacuna.id" class="hover:bg-cyan-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-cyan-100 flex items-center justify-center">
                                        <BeakerIcon class="h-5 w-5 text-cyan-600" />
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ vacuna.nombre }}</div>
                                    <div class="text-sm text-gray-500" v-if="vacuna.fabricante">{{ vacuna.fabricante }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">
                                {{ vacuna.tipo }}
                            </span>
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
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                :class="vacuna.activa ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                            >
                                {{ vacuna.activa ? 'Activa' : 'Inactiva' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-3">
                                <Link
                                    :href="'/admin/vacunas/' + vacuna.id"
                                    class="text-cyan-600 hover:text-cyan-800 transition-colors"
                                    title="Ver detalles"
                                >
                                    <EyeIcon class="h-4 w-4" />
                                </Link>
                                <Link
                                    :href="'/admin/vacunas/' + vacuna.id + '/edit'"
                                    class="text-gray-600 hover:text-gray-800 transition-colors"
                                    title="Editar"
                                >
                                    <PencilIcon class="h-4 w-4" />
                                </Link>
                                <button
                                    @click="handleToggle(vacuna)"
                                    class="hover:text-yellow-900 transition-colors"
                                    :class="vacuna.activa ? 'text-yellow-600' : 'text-green-600'"
                                    :title="vacuna.activa ? 'Desactivar' : 'Activar'"
                                >
                                    <component :is="vacuna.activa ? ExclamationTriangleIcon : CheckCircleIcon" class="h-4 w-4" />
                                </button>
                                <button
                                    @click="handleDeleteClick(vacuna)"
                                    class="text-red-600 hover:text-red-900 transition-colors"
                                    title="Eliminar"
                                >
                                    <TrashIcon class="h-4 w-4" />
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Vista de cards para pantallas pequeñas y medianas -->
        <div class="lg:hidden">
            <div class="divide-y divide-gray-200">
                <div v-for="vacuna in vacunas" :key="vacuna.id" class="p-3 sm:p-4 hover:bg-cyan-50 transition-colors">
                    <div class="flex items-start space-x-3 sm:space-x-4">
                        <!-- Avatar -->
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 sm:h-12 sm:w-12 rounded-full bg-cyan-100 flex items-center justify-center">
                                <BeakerIcon class="h-5 w-5 sm:h-6 sm:w-6 text-cyan-600" />
                            </div>
                        </div>

                        <!-- Contenido -->
                        <div class="flex-1 min-w-0">
                            <!-- Header con nombre y estado -->
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm sm:text-base font-medium text-gray-900 truncate">
                                        {{ vacuna.nombre }}
                                    </h3>
                                    <p class="text-xs sm:text-sm text-gray-500 mt-1" v-if="vacuna.fabricante">
                                        {{ vacuna.fabricante }}
                                    </p>
                                </div>
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ml-2 flex-shrink-0"
                                    :class="vacuna.activa ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                >
                                    {{ vacuna.activa ? 'Activa' : 'Inactiva' }}
                                </span>
                            </div>

                            <!-- Información detallada -->
                            <div class="grid grid-cols-1 xs:grid-cols-2 gap-2 sm:gap-3 text-xs sm:text-sm mb-3">
                                <div>
                                    <span class="font-medium text-gray-500">Tipo:</span>
                                    <span class="ml-1 text-gray-900">{{ vacuna.tipo }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-500">Dosis:</span>
                                    <span class="ml-1 text-gray-900">{{ vacuna.dosis_requeridas }}</span>
                                </div>
                                <div v-if="vacuna.edad_minima !== null" class="xs:col-span-2">
                                    <span class="font-medium text-gray-500">Edad:</span>
                                    <span class="ml-1 text-gray-900">
                                        Desde {{ formatEdad(vacuna.edad_minima) }}
                                        <span v-if="vacuna.edad_maxima !== null"> hasta {{ formatEdad(vacuna.edad_maxima) }}</span>
                                    </span>
                                </div>
                            </div>

                            <!-- Acciones -->
                            <div class="flex flex-wrap items-center gap-3 sm:gap-4">
                                <Link
                                    :href="'/admin/vacunas/' + vacuna.id"
                                    class="inline-flex items-center text-xs sm:text-sm text-cyan-600 hover:text-cyan-900 transition-colors focus:outline-none"
                                >
                                    <EyeIcon class="h-3 w-3 sm:h-4 sm:w-4 mr-1" />
                                    Ver
                                </Link>
                                <Link
                                    :href="'/admin/vacunas/' + vacuna.id + '/edit'"
                                    class="inline-flex items-center text-xs sm:text-sm text-gray-600 hover:text-gray-900 transition-colors focus:outline-none"
                                >
                                    <PencilIcon class="h-3 w-3 sm:h-4 sm:w-4 mr-1" />
                                    Editar
                                </Link>
                                <button
                                    @click="handleToggle(vacuna)"
                                    class="inline-flex items-center text-xs sm:text-sm hover:text-yellow-900 transition-colors focus:outline-none"
                                    :class="vacuna.activa ? 'text-yellow-600' : 'text-green-600'"
                                >
                                    <component :is="vacuna.activa ? ExclamationTriangleIcon : CheckCircleIcon" class="h-3 w-3 sm:h-4 sm:w-4 mr-1" />
                                    {{ vacuna.activa ? 'Desactivar' : 'Activar' }}
                                </button>
                                <button
                                    @click="handleDeleteClick(vacuna)"
                                    class="inline-flex items-center text-xs sm:text-sm text-red-600 hover:text-red-900 transition-colors focus:outline-none"
                                >
                                    <TrashIcon class="h-3 w-3 sm:h-4 sm:w-4 mr-1" />
                                    Eliminar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de confirmación de eliminación -->
        <ConfirmationModal
            :show="showDeleteModal"
            @close="closeDeleteModal"
            :closeable="!isDeleting"
        >
            <template #title>
                Eliminar Vacuna
            </template>

            <template #content>
                <p>¿Estás seguro de que deseas eliminar la vacuna <strong>{{ vacunaToDelete?.nombre }}</strong>?</p>
                <p class="mt-2 text-xs text-gray-500">Esta acción no se puede deshacer.</p>
            </template>

            <template #footer>
                <button
                    type="button"
                    @click="closeDeleteModal"
                    :disabled="isDeleting"
                    class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed sm:text-sm"
                >
                    Cancelar
                </button>

                <button
                    type="button"
                    @click="confirmDelete"
                    :disabled="isDeleting"
                    class="ml-3 inline-flex justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed sm:text-sm"
                >
                    <span v-if="isDeleting" class="mr-2">
                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                    {{ isDeleting ? 'Eliminando...' : 'Eliminar' }}
                </button>
            </template>
        </ConfirmationModal>
    </div>
</template>
