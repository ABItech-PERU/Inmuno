<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { BuildingOffice2Icon, EyeIcon, PencilIcon, ExclamationTriangleIcon, CheckCircleIcon, TrashIcon, PhoneIcon } from '@heroicons/vue/24/outline';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';

const props = defineProps({
    centrosSalud: Array
});

const emit = defineEmits(['delete', 'toggle']);

// Estado para el modal de confirmación
const showDeleteModal = ref(false);
const centroToDelete = ref(null);
const isDeleting = ref(false);

const formatTipo = (tipo) => {
    const tipos = {
        'Hospital': 'Hospital',
        'Centro de Salud': 'Centro de Salud',
        'Puesto de Salud': 'Puesto de Salud',
        'Clínica': 'Clínica',
        'hospital': 'Hospital',
        'centro_salud': 'Centro de Salud',
        'puesto_salud': 'Puesto de Salud',
        'clinica': 'Clínica'
    };
    return tipos[tipo] || tipo;
};

const handleDeleteClick = (centro) => {
    centroToDelete.value = centro;
    showDeleteModal.value = true;
};

const handleToggle = (centro) => {
    emit('toggle', centro);
};

const confirmDelete = () => {
    if (centroToDelete.value) {
        isDeleting.value = true;
        emit('delete', centroToDelete.value);
    }
};

const closeDeleteModal = () => {
    if (!isDeleting.value) {
        showDeleteModal.value = false;
        centroToDelete.value = null;
    }
};

// Función para ser llamada desde el componente padre cuando la eliminación es exitosa
const onDeleteSuccess = () => {
    isDeleting.value = false;
    showDeleteModal.value = false;
    centroToDelete.value = null;
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
                            ID
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-cyan-700 uppercase tracking-wider">
                            Centro de Salud
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-cyan-700 uppercase tracking-wider">
                            Tipo
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-cyan-700 uppercase tracking-wider">
                            Nivel
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-cyan-700 uppercase tracking-wider">
                            Contacto
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
                    <tr v-for="centro in centrosSalud" :key="centro.id" class="hover:bg-cyan-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ centro.id }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-cyan-100 flex items-center justify-center">
                                        <BuildingOffice2Icon class="h-5 w-5 text-cyan-600" />
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ centro.nombre }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        Código: {{ centro.codigo }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ formatTipo(centro.tipo) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ centro.nivel_atencion }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="space-y-1">
                                <div v-if="centro.telefono" class="text-sm text-gray-900 flex items-center">
                                    <PhoneIcon class="h-4 w-4 text-gray-400 mr-2" />
                                    {{ centro.telefono }}
                                </div>
                                <div v-if="centro.email" class="text-sm text-gray-500">
                                    {{ centro.email }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                :class="[
                                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                    centro.activo
                                        ? 'bg-green-100 text-green-800'
                                        : 'bg-red-100 text-red-800'
                                ]"
                            >
                                <CheckCircleIcon v-if="centro.activo" class="h-4 w-4 mr-1" />
                                <ExclamationTriangleIcon v-else class="h-4 w-4 mr-1" />
                                {{ centro.activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <Link
                                    :href="`/admin/centros-salud/${centro.id}`"
                                    class="text-cyan-600 hover:text-cyan-900 p-1 rounded-md hover:bg-cyan-50 transition-colors"
                                    title="Ver detalles"
                                >
                                    <EyeIcon class="h-4 w-4" />
                                </Link>
                                <Link
                                    :href="`/admin/centros-salud/${centro.id}/edit`"
                                    class="text-indigo-600 hover:text-indigo-900 p-1 rounded-md hover:bg-indigo-50 transition-colors"
                                    title="Editar"
                                >
                                    <PencilIcon class="h-4 w-4" />
                                </Link>
                                <button
                                    @click="handleDeleteClick(centro)"
                                    class="text-red-600 hover:text-red-900 p-1 rounded-md hover:bg-red-50 transition-colors"
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

        <!-- Vista de tarjetas para pantallas medianas (md) -->
        <div class="hidden md:block lg:hidden space-y-4">
            <div
                v-for="centro in centrosSalud"
                :key="centro.id"
                class="p-6 hover:bg-cyan-50 transition-colors"
            >
                <div class="flex items-start justify-between">
                    <div class="flex items-center space-x-3 flex-1">
                        <div class="flex-shrink-0">
                            <div class="h-12 w-12 rounded-full bg-cyan-100 flex items-center justify-center">
                                <BuildingOffice2Icon class="h-6 w-6 text-cyan-600" />
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-medium text-gray-900 truncate">
                                {{ centro.nombre }}
                            </h3>
                            <div class="flex items-center space-x-4 mt-1">
                                <span class="text-sm text-cyan-600 font-medium">#{{ centro.id }}</span>
                                <span class="text-sm text-gray-500">{{ centro.codigo }}</span>
                                <span class="text-sm text-gray-500">{{ formatTipo(centro.tipo) }}</span>
                                <span class="text-sm text-gray-500">Nivel {{ centro.nivel_atencion }}</span>
                            </div>
                            <div v-if="centro.telefono" class="mt-2 flex items-center">
                                <PhoneIcon class="h-4 w-4 text-gray-400 mr-2" />
                                <span class="text-sm text-gray-600">{{ centro.telefono }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span
                            :class="[
                                'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                centro.activo
                                    ? 'bg-green-100 text-green-800'
                                    : 'bg-red-100 text-red-800'
                            ]"
                        >
                            <CheckCircleIcon v-if="centro.activo" class="h-4 w-4 mr-1" />
                            <ExclamationTriangleIcon v-else class="h-4 w-4 mr-1" />
                            {{ centro.activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>
                </div>

                <div class="mt-4 flex items-center justify-between">
                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                        <div v-if="centro.telefono" class="flex items-center">
                            <PhoneIcon class="h-4 w-4 mr-1" />
                            {{ centro.telefono }}
                        </div>
                        <div v-if="centro.director_medico" class="truncate">
                            Dir: {{ centro.director_medico }}
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <Link
                            :href="`/admin/centros-salud/${centro.id}`"
                            class="text-cyan-600 hover:text-cyan-900 p-1 rounded-md hover:bg-cyan-50 transition-colors"
                            title="Ver detalles"
                        >
                            <EyeIcon class="h-5 w-5" />
                        </Link>
                        <Link
                            :href="`/admin/centros-salud/${centro.id}/edit`"
                            class="text-indigo-600 hover:text-indigo-900 p-1 rounded-md hover:bg-indigo-50 transition-colors"
                            title="Editar"
                        >
                            <PencilIcon class="h-5 w-5" />
                        </Link>
                        <button
                            @click="handleDeleteClick(centro)"
                            class="text-red-600 hover:text-red-900 p-1 rounded-md hover:bg-red-50 transition-colors"
                            title="Eliminar"
                        >
                            <TrashIcon class="h-5 w-5" />
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vista de cards para pantallas pequeñas y medianas -->
        <div class="lg:hidden">
            <div class="divide-y divide-gray-200">
                <div v-for="centro in centrosSalud" :key="centro.id" class="p-3 sm:p-4 hover:bg-cyan-50 transition-colors">
                    <div class="flex items-start space-x-3 sm:space-x-4">
                        <!-- Avatar -->
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 sm:h-12 sm:w-12 rounded-full bg-cyan-100 flex items-center justify-center">
                                <BuildingOffice2Icon class="h-5 w-5 sm:h-6 sm:w-6 text-cyan-600" />
                            </div>
                        </div>

                        <!-- Contenido -->
                        <div class="flex-1 min-w-0">
                            <!-- Header con nombre y estado -->
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm sm:text-base font-medium text-gray-900 truncate">
                                        {{ centro.nombre }}
                                    </h3>
                                    <div class="flex items-center space-x-2 mt-1">
                                        <span class="text-xs sm:text-sm text-cyan-600 font-medium">#{{ centro.id }}</span>
                                        <span class="text-xs sm:text-sm text-gray-500">{{ centro.codigo }}</span>
                                    </div>
                                </div>
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ml-2 flex-shrink-0"
                                    :class="centro.activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                >
                                    {{ centro.activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </div>

                            <!-- Información detallada -->
                            <div class="grid grid-cols-1 xs:grid-cols-2 gap-2 sm:gap-3 text-xs sm:text-sm mb-3">
                                <div>
                                    <span class="font-medium text-gray-500">Tipo:</span>
                                    <span class="ml-1 text-gray-900">{{ formatTipo(centro.tipo) }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-500">Nivel:</span>
                                    <span class="ml-1 text-gray-900">{{ centro.nivel_atencion }}</span>
                                </div>
                                <div v-if="centro.telefono" class="xs:col-span-2">
                                    <span class="font-medium text-gray-500">Teléfono:</span>
                                    <span class="ml-1 text-gray-900">{{ centro.telefono }}</span>
                                </div>
                            </div>

                            <!-- Acciones -->
                            <div class="flex flex-wrap items-center gap-3 sm:gap-4">
                                <Link
                                    :href="`/admin/centros-salud/${centro.id}`"
                                    class="inline-flex items-center text-xs sm:text-sm text-cyan-600 hover:text-cyan-900 transition-colors focus:outline-none"
                                >
                                    <EyeIcon class="h-3 w-3 sm:h-4 sm:w-4 mr-1" />
                                    Ver
                                </Link>
                                <Link
                                    :href="`/admin/centros-salud/${centro.id}/edit`"
                                    class="inline-flex items-center text-xs sm:text-sm text-gray-600 hover:text-gray-900 transition-colors focus:outline-none"
                                >
                                    <PencilIcon class="h-3 w-3 sm:h-4 sm:w-4 mr-1" />
                                    Editar
                                </Link>
                                <button
                                    @click="handleToggle(centro)"
                                    class="inline-flex items-center text-xs sm:text-sm hover:text-yellow-900 transition-colors focus:outline-none"
                                    :class="centro.activo ? 'text-yellow-600' : 'text-green-600'"
                                >
                                    <component :is="centro.activo ? ExclamationTriangleIcon : CheckCircleIcon" class="h-3 w-3 sm:h-4 sm:w-4 mr-1" />
                                    {{ centro.activo ? 'Desactivar' : 'Activar' }}
                                </button>
                                <button
                                    @click="handleDeleteClick(centro)"
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

        <!-- Modal de confirmación para eliminar -->
        <ConfirmationModal
            :show="showDeleteModal"
            @close="closeDeleteModal"
            @confirm="confirmDelete"
            :loading="isDeleting"
        >
            <template #title>
                Eliminar Centro de Salud
            </template>
            <template #content>
                ¿Estás seguro que deseas eliminar el centro de salud <strong>{{ centroToDelete?.nombre }}</strong>?
                <br><br>
                Esta acción no se puede deshacer.
            </template>
            <template #footer>
                <button
                    @click="closeDeleteModal"
                    :disabled="isDeleting"
                    class="mr-3 inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 sm:text-sm disabled:opacity-50"
                >
                    Cancelar
                </button>
                <button
                    @click="confirmDelete"
                    :disabled="isDeleting"
                    class="inline-flex justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:text-sm disabled:opacity-50"
                >
                    <span v-if="isDeleting">Eliminando...</span>
                    <span v-else>Eliminar</span>
                </button>
            </template>
        </ConfirmationModal>
    </div>
</template>
