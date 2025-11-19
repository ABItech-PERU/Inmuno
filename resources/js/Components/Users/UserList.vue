<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { UserIcon, EyeIcon, PencilIcon, ExclamationTriangleIcon, CheckCircleIcon, TrashIcon, PhoneIcon } from '@heroicons/vue/24/outline';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';

const props = defineProps({
    users: Array
});

const emit = defineEmits(['delete', 'toggle']);

// Estado para el modal de confirmación
const showDeleteModal = ref(false);
const userToDelete = ref(null);
const isDeleting = ref(false);

const handleDeleteClick = (user) => {
    userToDelete.value = user;
    showDeleteModal.value = true;
};

const handleToggle = (user) => {
    emit('toggle', user);
};

const confirmDelete = () => {
    if (userToDelete.value) {
        isDeleting.value = true;
        emit('delete', userToDelete.value);
    }
};

const closeDeleteModal = () => {
    if (!isDeleting.value) {
        showDeleteModal.value = false;
        userToDelete.value = null;
    }
};

// Función para ser llamada desde el componente padre cuando la eliminación es exitosa
const onDeleteSuccess = () => {
    isDeleting.value = false;
    showDeleteModal.value = false;
    userToDelete.value = null;
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
                            Usuario
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-cyan-700 uppercase tracking-wider">
                            Email
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-cyan-700 uppercase tracking-wider">
                            Roles
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
                    <tr v-for="user in users" :key="user.id" class="hover:bg-gray-50 transition-colors">
                        <!-- ID -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            #{{ user.id }}
                        </td>

                        <!-- Usuario -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-cyan-100 flex items-center justify-center">
                                        <UserIcon class="h-5 w-5 text-cyan-600" />
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ user.name }} {{ user.apellidos }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ user.numero_documento }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Email -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ user.email }}
                        </td>

                        <!-- Roles -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-wrap gap-1">
                                <span
                                    v-for="role in user.roles"
                                    :key="role.id"
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800"
                                >
                                    {{ role.name }}
                                </span>
                                <span
                                    v-if="user.roles.length === 0"
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800"
                                >
                                    Sin roles
                                </span>
                            </div>
                        </td>

                        <!-- Estado -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                :class="user.estado ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                            >
                                <CheckCircleIcon v-if="user.estado" class="w-4 h-4 mr-1" />
                                <ExclamationTriangleIcon v-else class="w-4 h-4 mr-1" />
                                {{ user.estado ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>

                        <!-- Acciones -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <Link
                                    :href="`/admin/users/${user.id}`"
                                    class="text-cyan-600 hover:text-cyan-900 transition-colors"
                                    title="Ver detalles"
                                >
                                    <EyeIcon class="h-5 w-5" />
                                </Link>
                                <Link
                                    :href="`/admin/users/${user.id}/edit`"
                                    class="text-indigo-600 hover:text-indigo-900 transition-colors"
                                    title="Editar"
                                >
                                    <PencilIcon class="h-5 w-5" />
                                </Link>
                                <button
                                    @click="handleToggle(user)"
                                    :title="user.estado ? 'Desactivar' : 'Activar'"
                                    class="transition-colors"
                                    :class="user.estado ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900'"
                                >
                                    <CheckCircleIcon v-if="user.estado" class="h-5 w-5" />
                                    <ExclamationTriangleIcon v-else class="h-5 w-5" />
                                </button>
                                <button
                                    @click="handleDeleteClick(user)"
                                    class="text-red-600 hover:text-red-900 transition-colors"
                                    title="Eliminar"
                                >
                                    <TrashIcon class="h-5 w-5" />
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Vista de tarjetas para pantallas pequeñas (menor a lg) -->
        <div class="lg:hidden space-y-4">
            <div
                v-for="user in users"
                :key="user.id"
                class="bg-white border border-gray-200 rounded-lg p-3 sm:p-4 hover:shadow-md transition-shadow"
            >
                <!-- Header de la tarjeta -->
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center space-x-3 flex-1 min-w-0">
                        <div class="flex-shrink-0 h-10 w-10 sm:h-12 sm:w-12">
                            <div class="h-10 w-10 sm:h-12 sm:w-12 rounded-full bg-cyan-100 flex items-center justify-center">
                                <UserIcon class="h-5 w-5 sm:h-6 sm:w-6 text-cyan-600" />
                            </div>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="text-sm sm:text-base font-medium text-gray-900 truncate">
                                {{ user.name }} {{ user.apellidos }}
                            </h3>
                            <p class="text-sm text-gray-500 truncate">
                                #{{ user.id }} • {{ user.numero_documento }}
                            </p>
                        </div>
                    </div>
                    <span
                        :class="user.estado ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium flex-shrink-0 ml-2"
                    >
                        <CheckCircleIcon v-if="user.estado" class="w-3 h-3 mr-1" />
                        <ExclamationTriangleIcon v-else class="w-3 h-3 mr-1" />
                        {{ user.estado ? 'Activo' : 'Inactivo' }}
                    </span>
                </div>

                <!-- Información -->
                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <span class="font-medium w-16 flex-shrink-0">Email:</span>
                        <span class="truncate">{{ user.email }}</span>
                    </div>
                    <div class="flex items-start text-sm text-gray-600">
                        <span class="font-medium w-16 flex-shrink-0">Roles:</span>
                        <div class="flex flex-wrap gap-1 flex-1">
                            <span
                                v-for="role in user.roles"
                                :key="role.id"
                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800"
                            >
                                {{ role.name }}
                            </span>
                            <span
                                v-if="user.roles.length === 0"
                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800"
                            >
                                Sin roles
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Acciones - Responsive con wrap -->
                <div class="flex flex-wrap items-center justify-end gap-2 pt-3 border-t border-gray-200">
                    <Link
                        :href="`/admin/users/${user.id}`"
                        class="inline-flex items-center px-2 sm:px-3 py-2 border border-gray-300 shadow-sm text-xs sm:text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 transition-colors"
                    >
                        <EyeIcon class="h-4 w-4 mr-1 sm:mr-2" />
                        <span class="hidden xs:inline">Ver</span>
                    </Link>
                    <Link
                        :href="`/admin/users/${user.id}/edit`"
                        class="inline-flex items-center px-2 sm:px-3 py-2 border border-transparent text-xs sm:text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
                    >
                        <PencilIcon class="h-4 w-4 mr-1 sm:mr-2" />
                        <span class="hidden xs:inline">Editar</span>
                    </Link>
                    <button
                        @click="handleToggle(user)"
                        :class="user.estado ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900'"
                        class="inline-flex items-center px-2 sm:px-3 py-2 border border-transparent text-xs sm:text-sm leading-4 font-medium rounded-md transition-colors"
                    >
                        <CheckCircleIcon v-if="user.estado" class="h-4 w-4 mr-1 sm:mr-2" />
                        <ExclamationTriangleIcon v-else class="h-4 w-4 mr-1 sm:mr-2" />
                        <span class="hidden xs:inline">{{ user.estado ? 'Desactivar' : 'Activar' }}</span>
                    </button>
                    <button
                        @click="handleDeleteClick(user)"
                        class="inline-flex items-center px-2 sm:px-3 py-2 border border-transparent text-xs sm:text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
                    >
                        <TrashIcon class="h-4 w-4 mr-1 sm:mr-2" />
                        <span class="hidden xs:inline">Eliminar</span>
                    </button>
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
                Eliminar usuario
            </template>
            <template #content>
                <p class="text-sm text-gray-500">
                    ¿Estás seguro de que deseas eliminar al usuario <strong>{{ userToDelete?.name }} {{ userToDelete?.apellidos }}</strong>?
                    Esta acción no se puede deshacer.
                </p>
            </template>
            <template #footer>
                <button
                    @click="closeDeleteModal"
                    :disabled="isDeleting"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 sm:ml-3 sm:w-auto sm:text-sm"
                >
                    Cancelar
                </button>
                <button
                    @click="confirmDelete"
                    :disabled="isDeleting"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50"
                >
                    <span v-if="isDeleting">Eliminando...</span>
                    <span v-else>Eliminar</span>
                </button>
            </template>
        </ConfirmationModal>
    </div>
</template>
