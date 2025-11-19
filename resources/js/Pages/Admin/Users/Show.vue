<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { UserIcon, ArrowLeftIcon, PencilIcon, CheckCircleIcon, ExclamationTriangleIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    user: Object,
});
</script>

<template>
    <AppLayout title="Detalles del Usuario">
        <Head title="Detalles del Usuario" />

        <div class="min-h-screen bg-gray-50">
            <!-- Header -->
            <div class="bg-white shadow-sm border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <Link
                                href="/admin/users"
                                class="inline-flex items-center text-gray-500 hover:text-gray-700"
                            >
                                <ArrowLeftIcon class="h-5 w-5 mr-2" />
                                Volver a usuarios
                            </Link>
                        </div>

                        <div class="flex items-center space-x-3">
                            <Link
                                :href="`/admin/users/${user.id}/edit`"
                                class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-cyan-500"
                            >
                                <PencilIcon class="h-4 w-4 mr-2" />
                                Editar
                            </Link>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <UserIcon class="h-12 w-12 text-cyan-600" />
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">{{ user.name }} {{ user.apellidos }}</h1>
                                <div class="flex items-center space-x-4 mt-2">
                                    <span
                                        :class="user.estado ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                    >
                                        <CheckCircleIcon v-if="user.estado" class="w-4 h-4 mr-1" />
                                        <ExclamationTriangleIcon v-else class="w-4 h-4 mr-1" />
                                        {{ user.estado ? 'Activo' : 'Inactivo' }}
                                    </span>
                                    <span class="text-sm text-gray-500">
                                        #{{ user.id }} • {{ user.email }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido principal -->
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="space-y-6">
                    <!-- Información Personal -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Información Personal</h3>
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nombres</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ user.name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Apellidos</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ user.apellidos }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tipo de Documento</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ user.tipo_documento }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Número de Documento</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ user.numero_documento }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Fecha de Nacimiento</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ user.fecha_nacimiento ? new Date(user.fecha_nacimiento).toLocaleDateString('es-ES') : 'No especificada' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Género</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ user.genero === 'M' ? 'Masculino' : user.genero === 'F' ? 'Femenino' : 'No especificado' }}
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Información de Contacto -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Información de Contacto</h3>
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ user.email }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ user.telefono || 'No especificado' }}</dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Dirección</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ user.direccion || 'No especificada' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Roles -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Roles</h3>
                        <div class="flex flex-wrap gap-2">
                            <span
                                v-for="role in user.roles"
                                :key="role.id"
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-cyan-100 text-cyan-800"
                            >
                                {{ role.name }}
                            </span>
                            <span
                                v-if="user.roles.length === 0"
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800"
                            >
                                Sin roles asignados
                            </span>
                        </div>
                    </div>

                    <!-- Información del Sistema -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Información del Sistema</h3>
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">ID de Usuario</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ user.id }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Estado</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <span
                                        :class="user.estado ? 'text-green-600' : 'text-red-600'"
                                        class="font-medium"
                                    >
                                        {{ user.estado ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Fecha de Registro</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ user.se_registro ? new Date(user.se_registro).toLocaleDateString('es-ES', {
                                        year: 'numeric',
                                        month: 'long',
                                        day: 'numeric',
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    }) : 'No disponible' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Última Actualización</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ user.updated_at ? new Date(user.updated_at).toLocaleDateString('es-ES', {
                                        year: 'numeric',
                                        month: 'long',
                                        day: 'numeric',
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    }) : 'No disponible' }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
