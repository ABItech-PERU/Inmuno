<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import {
    ClipboardDocumentListIcon,
    EyeIcon,
    PencilIcon,
    BeakerIcon,
    TrashIcon,
    UserGroupIcon,
    ShieldCheckIcon,
    CalendarIcon
} from '@heroicons/vue/24/outline';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';

const props = defineProps({
    esquemas: Array
});

const emit = defineEmits(['manage-vacunas', 'delete']);

// Estado para el modal de confirmación
const showDeleteModal = ref(false);
const esquemaToDelete = ref(null);
const isDeleting = ref(false);

const formatEdad = (meses) => {
    if (!meses && meses !== 0) return 'No especificada';

    // Para recién nacidos
    if (meses === 0) return 'Recién nacido';

    // Para meses menores a 12
    if (meses < 12) return `${meses} ${meses === 1 ? 'mes' : 'meses'}`;

    // Para años completos
    const años = Math.floor(meses / 12);
    const mesesRestantes = meses % 12;

    if (mesesRestantes === 0) {
        return `${años} ${años === 1 ? 'año' : 'años'}`;
    }

    // Para combinaciones de años y meses (solo hasta 2 años, después solo años)
    if (años <= 2) {
        return `${años}a ${mesesRestantes}m`;
    } else {
        return `${años} años`;
    }
};

const formatRangoEdad = (edadInicio, edadFin) => {
    if (edadInicio === null && edadFin === null) {
        return 'Sin límite de edad';
    } else if (edadInicio !== null && edadFin === null) {
        return `Desde ${formatEdad(edadInicio)}`;
    } else if (edadInicio === null && edadFin !== null) {
        return `Hasta ${formatEdad(edadFin)}`;
    } else if (edadInicio === edadFin) {
        // Si inicio y fin son iguales, mostrar solo uno
        return formatEdad(edadInicio);
    } else {
        return `${formatEdad(edadInicio)} a ${formatEdad(edadFin)}`;
    }
};

const handleManageVacunas = (esquema) => {
    emit('manage-vacunas', esquema);
};

const handleDeleteClick = (esquema) => {
    esquemaToDelete.value = esquema;
    showDeleteModal.value = true;
};

const confirmDelete = () => {
    if (esquemaToDelete.value) {
        isDeleting.value = true;
        emit('delete', esquemaToDelete.value);
    }
};

const closeDeleteModal = () => {
    if (!isDeleting.value) {
        showDeleteModal.value = false;
        esquemaToDelete.value = null;
    }
};

// Funciones para ser llamadas desde el componente padre
const onDeleteSuccess = () => {
    isDeleting.value = false;
    showDeleteModal.value = false;
    esquemaToDelete.value = null;
};

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
                            ID
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-cyan-700 uppercase tracking-wider">
                            Esquema
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-cyan-700 uppercase tracking-wider">
                            Grupo de Edad
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-cyan-700 uppercase tracking-wider">
                            Rango Edad
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-cyan-700 uppercase tracking-wider">
                            Configuración
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
                    <tr v-for="esquema in esquemas" :key="esquema.id" class="hover:bg-cyan-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ esquema.id }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-cyan-100 flex items-center justify-center">
                                        <ClipboardDocumentListIcon class="h-5 w-5 text-cyan-600" />
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ esquema.nombre }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <UserGroupIcon class="h-3 w-3 mr-1" />
                                {{ esquema.grupo_edad }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="flex items-center">
                                <CalendarIcon class="h-4 w-4 text-gray-400 mr-1" />
                                {{ formatRangoEdad(esquema.edad_inicio, esquema.edad_fin) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col space-y-1">
                                <span v-if="esquema.obligatorio" class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                    <ShieldCheckIcon class="h-3 w-3 mr-1" />
                                    Obligatorio
                                </span>
                                <span v-else class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                    Opcional
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                :class="esquema.activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                            >
                                <CheckCircleIcon v-if="esquema.activo" class="h-3 w-3 mr-1" />
                                <XCircleIcon v-else class="h-3 w-3 mr-1" />
                                {{ esquema.activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-3">
                                <Link
                                    :href="`/admin/esquemas/${esquema.id}`"
                                    class="text-cyan-600 hover:text-cyan-800 transition-colors"
                                    title="Ver detalles"
                                >
                                    <EyeIcon class="h-4 w-4" />
                                </Link>
                                <Link
                                    :href="`/admin/esquemas/${esquema.id}/edit`"
                                    class="text-blue-600 hover:text-blue-800 transition-colors"
                                    title="Editar esquema"
                                >
                                    <PencilIcon class="h-4 w-4" />
                                </Link>
                                <button
                                    @click="handleManageVacunas(esquema)"
                                    class="text-cyan-600 hover:text-cyan-800 transition-colors"
                                    title="Gestionar vacunas del esquema"
                                >
                                    <BeakerIcon class="h-4 w-4" />
                                </button>
                                <button
                                    @click="handleDeleteClick(esquema)"
                                    class="text-red-600 hover:text-red-800 transition-colors"
                                    title="Eliminar esquema"
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
        <div class="lg:hidden space-y-4 p-4">
            <div v-for="esquema in esquemas" :key="esquema.id"
                 class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow">

                <!-- Header del card -->
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-full bg-cyan-100 flex items-center justify-center">
                                <ClipboardDocumentListIcon class="h-5 w-5 text-cyan-600" />
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-sm font-medium text-gray-900 truncate">
                                {{ esquema.nombre }}
                            </h3>
                            <div class="flex items-center mt-1 space-x-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ esquema.grupo_edad }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-1">
                        <span
                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium"
                            :class="esquema.activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                        >
                            {{ esquema.activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>
                </div>

                <!-- Información adicional -->
                <div class="mb-3 space-y-2">
                    <div v-if="esquema.descripcion" class="text-xs text-gray-600">
                        {{ esquema.descripcion }}
                    </div>
                    <div class="flex items-center justify-between text-xs text-gray-500">
                        <span class="flex items-center">
                            <CalendarIcon class="h-3 w-3 mr-1" />
                            {{ formatRangoEdad(esquema.edad_inicio, esquema.edad_fin) }}
                        </span>
                        <span v-if="esquema.obligatorio" class="inline-flex items-center px-1.5 py-0.5 rounded-full bg-orange-100 text-orange-800">
                            <ShieldCheckIcon class="h-3 w-3 mr-1" />
                            Obligatorio
                        </span>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="flex items-center justify-end space-x-3 pt-3 border-t border-gray-100">
                    <Link
                        :href="`/admin/esquemas/${esquema.id}`"
                        class="text-cyan-600 hover:text-cyan-800 transition-colors"
                        title="Ver detalles"
                    >
                        <EyeIcon class="h-4 w-4" />
                    </Link>
                    <Link
                        :href="`/admin/esquemas/${esquema.id}/edit`"
                        class="text-blue-600 hover:text-blue-800 transition-colors"
                        title="Editar"
                    >
                        <PencilIcon class="h-4 w-4" />
                    </Link>
                    <button
                        @click="handleManageVacunas(esquema)"
                        class="text-cyan-600 hover:text-cyan-800 transition-colors"
                        title="Gestionar vacunas del esquema"
                    >
                        <BeakerIcon class="h-4 w-4" />
                    </button>
                    <button
                        @click="handleDeleteClick(esquema)"
                        class="text-red-600 hover:text-red-800 transition-colors"
                        title="Eliminar"
                    >
                        <TrashIcon class="h-4 w-4" />
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal de confirmación para eliminación -->
        <ConfirmationModal
            :show="showDeleteModal"
            @close="closeDeleteModal"
            @confirm="confirmDelete"
            :processing="isDeleting"
        >
            <template #title>
                Eliminar Esquema de Vacunación
            </template>
            <template #content>
                <div class="space-y-3">
                    <p class="text-sm text-gray-600">
                        ¿Estás seguro de que deseas eliminar el esquema de vacunación
                        <strong class="font-medium text-gray-900">{{ esquemaToDelete?.nombre }}</strong>?
                    </p>
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                        <div class="flex items-start">
                            <div class="text-amber-600 mr-2 mt-0.5">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-amber-800">Advertencia</p>
                                <p class="text-sm text-amber-700 mt-1">
                                    Esta acción no se puede deshacer. Si el esquema tiene vacunas asociadas, no podrá ser eliminado.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </ConfirmationModal>
    </div>
</template>
