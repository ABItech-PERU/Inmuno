<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { UserIcon, EyeIcon, PencilIcon, ExclamationTriangleIcon, CheckCircleIcon, TrashIcon, UserGroupIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    dependientes: Array,
    parentescos: Object
});

const emit = defineEmits(['delete']);

// Estado para el modal de confirmación
const showDeleteModal = ref(false);
const dependienteToDelete = ref(null);
const isDeleting = ref(false);

const formatEdad = (fechaNacimiento) => {
    if (!fechaNacimiento) return 'No especificada';
    const hoy = new Date();
    const nacimiento = new Date(fechaNacimiento);
    let edad = hoy.getFullYear() - nacimiento.getFullYear();
    const diferenciaMes = hoy.getMonth() - nacimiento.getMonth();

    if (diferenciaMes < 0 || (diferenciaMes === 0 && hoy.getDate() < nacimiento.getDate())) {
        edad--;
    }

    return `${edad} ${edad === 1 ? 'año' : 'años'}`;
};

const formatFecha = (fecha) => {
    if (!fecha) return 'No especificada';
    return new Date(fecha).toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};

const handleDeleteClick = (dependiente) => {
    dependienteToDelete.value = dependiente;
    showDeleteModal.value = true;
};

const confirmDelete = () => {
    if (dependienteToDelete.value) {
        isDeleting.value = true;
        emit('delete', dependienteToDelete.value);
        // El componente padre manejará el cierre del modal después de la eliminación exitosa
    }
};

const closeDeleteModal = () => {
    if (!isDeleting.value) {
        showDeleteModal.value = false;
        dependienteToDelete.value = null;
    }
};

// Función para ser llamada desde el componente padre cuando la eliminación es exitosa
const onDeleteSuccess = () => {
    isDeleting.value = false;
    showDeleteModal.value = false;
    dependienteToDelete.value = null;
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
                            Dependiente
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-cyan-700 uppercase tracking-wider">
                            Documento
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-cyan-700 uppercase tracking-wider">
                            Parentesco
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
                    <tr v-for="dependiente in dependientes" :key="dependiente.id" class="hover:bg-cyan-50 transition-colors">
                        <!-- ID -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ dependiente.id }}
                            </div>
                        </td>

                        <!-- Dependiente -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-cyan-100 flex items-center justify-center">
                                        <UserIcon class="h-5 w-5 text-cyan-600" />
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ dependiente.nombres }} {{ dependiente.apellidos }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ dependiente.genero === 'M' ? 'Masculino' : 'Femenino' }}
                                        <span v-if="dependiente.telefono"> • {{ dependiente.telefono }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Documento -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                <div class="font-medium">{{ dependiente.tipo_documento }}</div>
                                <div class="text-gray-500">{{ dependiente.numero_documento }}</div>
                            </div>
                        </td>

                        <!-- Parentesco -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">
                                {{ parentescos[dependiente.parentesco] || dependiente.parentesco }}
                            </span>
                        </td>

                        <!-- Edad -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="flex items-center">
                                {{ formatEdad(dependiente.fecha_nacimiento) }}
                                <ExclamationTriangleIcon
                                    v-if="new Date().getFullYear() - new Date(dependiente.fecha_nacimiento).getFullYear() < 18"
                                    class="h-4 w-4 ml-2 text-yellow-500"
                                    title="Menor de edad"
                                />
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ formatFecha(dependiente.fecha_nacimiento) }}
                            </div>
                        </td>

                        <!-- Estado -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span :class="[
                                dependiente.activo
                                    ? 'bg-green-100 text-green-800'
                                    : 'bg-red-100 text-red-800',
                                'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium'
                            ]">
                                <CheckCircleIcon v-if="dependiente.activo" class="h-3 w-3 mr-1" />
                                <ExclamationTriangleIcon v-else class="h-3 w-3 mr-1" />
                                {{ dependiente.activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>

                        <!-- Acciones -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-3">
                                <Link
                                    :href="`/paciente/dependientes/${dependiente.id}`"
                                    class="text-cyan-600 hover:text-cyan-800 transition-colors"
                                    title="Ver detalles"
                                >
                                    <EyeIcon class="h-4 w-4" />
                                </Link>
                                <Link
                                    :href="`/paciente/dependientes/${dependiente.id}/edit`"
                                    class="text-gray-600 hover:text-gray-800 transition-colors"
                                    title="Editar"
                                >
                                    <PencilIcon class="h-4 w-4" />
                                </Link>
                                <button
                                    @click="handleDeleteClick(dependiente)"
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
                <div v-for="dependiente in dependientes" :key="dependiente.id" class="p-3 sm:p-4 hover:bg-cyan-50 transition-colors">
                    <div class="flex items-start space-x-3 sm:space-x-4">
                        <!-- Avatar -->
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 sm:h-12 sm:w-12 rounded-full bg-cyan-100 flex items-center justify-center">
                                <UserIcon class="h-5 w-5 sm:h-6 sm:w-6 text-cyan-600" />
                            </div>
                        </div>

                        <!-- Contenido -->
                        <div class="flex-1 min-w-0">
                            <!-- Header con nombre y estado -->
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm sm:text-base font-medium text-gray-900 truncate">
                                        {{ dependiente.nombres }} {{ dependiente.apellidos }}
                                    </h3>
                                    <div class="flex items-center space-x-2 mt-1">
                                        <span class="text-xs sm:text-sm text-cyan-600 font-medium">#{{ dependiente.id }}</span>
                                        <span v-if="dependiente.telefono" class="text-xs sm:text-sm text-gray-500">{{ dependiente.telefono }}</span>
                                    </div>
                                </div>
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ml-2 flex-shrink-0"
                                    :class="dependiente.activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                >
                                    {{ dependiente.activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </div>

                            <!-- Información detallada -->
                            <div class="grid grid-cols-1 xs:grid-cols-2 gap-2 sm:gap-3 text-xs sm:text-sm mb-3">
                                <div>
                                    <span class="font-medium text-gray-500">Parentesco:</span>
                                    <span class="ml-1 text-gray-900">{{ parentescos[dependiente.parentesco] || dependiente.parentesco }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-500">Documento:</span>
                                    <span class="ml-1 text-gray-900">{{ dependiente.tipo_documento }}: {{ dependiente.numero_documento }}</span>
                                </div>
                                <div class="xs:col-span-2">
                                    <span class="font-medium text-gray-500">Edad:</span>
                                    <span class="ml-1 text-gray-900">
                                        {{ formatEdad(dependiente.fecha_nacimiento) }}
                                        <span class="text-gray-500">({{ dependiente.genero === 'M' ? 'Masculino' : 'Femenino' }})</span>
                                    </span>
                                </div>
                            </div>

                            <!-- Acciones -->
                            <div class="flex flex-wrap items-center gap-3 sm:gap-4">
                                <Link
                                    :href="`/paciente/dependientes/${dependiente.id}`"
                                    class="inline-flex items-center text-xs sm:text-sm text-cyan-600 hover:text-cyan-900 transition-colors focus:outline-none"
                                >
                                    <EyeIcon class="h-3 w-3 sm:h-4 sm:w-4 mr-1" />
                                    Ver
                                </Link>
                                <Link
                                    :href="`/paciente/dependientes/${dependiente.id}/edit`"
                                    class="inline-flex items-center text-xs sm:text-sm text-gray-600 hover:text-gray-900 transition-colors focus:outline-none"
                                >
                                    <PencilIcon class="h-3 w-3 sm:h-4 sm:w-4 mr-1" />
                                    Editar
                                </Link>
                                <button
                                    @click="handleDeleteClick(dependiente)"
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
                <!-- Modal de confirmación de eliminación -->
        <div v-if="showDeleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                        <ExclamationTriangleIcon class="h-6 w-6 text-red-600" />
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mt-2 text-center">
                        Eliminar Dependiente
                    </h3>
                    <div class="mt-2 px-7 py-3 text-center">
                        <p class="text-sm text-gray-500 text-center">
                            ¿Estás seguro de que deseas eliminar a <strong>{{ dependienteToDelete?.nombres }} {{ dependienteToDelete?.apellidos }}</strong>?
                        </p>
                        <p class="text-xs text-gray-500 mt-2 text-center">
                            Esta acción no se puede deshacer.
                        </p>
                    </div>
                    <div class="items-center px-4 py-3 flex space-x-4 justify-center">
                        <button
                            @click="closeDeleteModal"
                            type="button"
                            :disabled="isDeleting"
                            class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-cyan-500 w-full disabled:opacity-50"
                        >
                            Cancelar
                        </button>
                        <button
                            @click="confirmDelete"
                            type="button"
                            :disabled="isDeleting"
                            class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm bg-red-600 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-red-500 w-full disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span v-if="isDeleting" class="mr-2">
                                <svg class="animate-spin h-4 w-4 inline" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                            {{ isDeleting ? 'Eliminando...' : 'Eliminar' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
