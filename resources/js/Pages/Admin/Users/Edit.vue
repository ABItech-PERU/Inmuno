<script setup>
import { ref } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import { UserIcon, ArrowLeftIcon } from "@heroicons/vue/24/outline";
import Toast from "@/Components/Toast.vue";

const props = defineProps({
    user: Object,
    roles: Array,
});

// Referencias
const toastRef = ref(null);

// Formulario
const form = useForm({
    name: props.user.name || "",
    apellidos: props.user.apellidos || "",
    email: props.user.email || "",
    tipo_documento: props.user.tipo_documento || "",
    numero_documento: props.user.numero_documento || "",
    telefono: props.user.telefono || "",
    direccion: props.user.direccion || "",
    fecha_nacimiento: props.user.fecha_nacimiento_formatted || "",
    genero: props.user.genero || "",
    estado: props.user.estado ? 1 : 0,
    roles: props.user.roles ? props.user.roles.map((role) => role.name) : [],
});

// Opciones
const tiposDocumento = [
    { value: "DNI", label: "DNI" },
    { value: "CE", label: "Carnet de Extranjería" },
    { value: "PASAPORTE", label: "Pasaporte" },
];

const generos = [
    { value: "M", label: "Masculino" },
    { value: "F", label: "Femenino" },
];

// Método para enviar el formulario
const submit = () => {
    form.put(`/admin/users/${props.user.id}`, {
        onSuccess: () => {
            toastRef.value?.show("Usuario actualizado exitosamente", "success");
        },
        onError: (errors) => {
            toastRef.value?.show("Error al actualizar el usuario", "error");
        },
    });
};
</script>

<template>
    <AppLayout title="Editar Usuario">
        <Head title="Editar Usuario" />

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
                    </div>

                    <div class="flex items-center space-x-3 mt-4">
                        <div class="flex-shrink-0">
                            <UserIcon class="h-8 w-8 text-cyan-600" />
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">
                                Editar Usuario
                            </h1>
                            <p class="text-sm text-gray-600">
                                Actualiza la información del usuario
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido principal -->
            <div class="max-w-4xl mx-auto px-3 sm:px-4 lg:px-8 py-4 sm:py-6">
                <form
                    @submit.prevent="submit"
                    class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6"
                >
                    <!-- Información Personal -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            Información Personal
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Nombres *
                                </label>
                                <input
                                    v-model="form.name"
                                    type="text"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none"
                                    placeholder="Ingrese los nombres"
                                />
                                <div
                                    v-if="form.errors.name"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.name }}
                                </div>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Apellidos *
                                </label>
                                <input
                                    v-model="form.apellidos"
                                    type="text"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none"
                                    placeholder="Ingrese los apellidos"
                                />
                                <div
                                    v-if="form.errors.apellidos"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.apellidos }}
                                </div>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Tipo de Documento *
                                </label>
                                <select
                                    v-model="form.tipo_documento"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none"
                                >
                                    <option value="">Seleccionar tipo</option>
                                    <option
                                        v-for="tipo in tiposDocumento"
                                        :key="tipo.value"
                                        :value="tipo.value"
                                    >
                                        {{ tipo.label }}
                                    </option>
                                </select>
                                <div
                                    v-if="form.errors.tipo_documento"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.tipo_documento }}
                                </div>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Número de Documento *
                                </label>
                                <input
                                    v-model="form.numero_documento"
                                    type="text"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none"
                                    placeholder="Ingrese el número de documento"
                                />
                                <div
                                    v-if="form.errors.numero_documento"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.numero_documento }}
                                </div>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Fecha de Nacimiento
                                </label>
                                <input
                                    v-model="form.fecha_nacimiento"
                                    type="date"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none"
                                />
                                <div
                                    v-if="form.errors.fecha_nacimiento"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.fecha_nacimiento }}
                                </div>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Género
                                </label>
                                <select
                                    v-model="form.genero"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none"
                                >
                                    <option value="">Seleccionar género</option>
                                    <option
                                        v-for="genero in generos"
                                        :key="genero.value"
                                        :value="genero.value"
                                    >
                                        {{ genero.label }}
                                    </option>
                                </select>
                                <div
                                    v-if="form.errors.genero"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.genero }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información de Contacto -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            Información de Contacto
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Email *
                                </label>
                                <input
                                    v-model="form.email"
                                    type="email"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none"
                                    placeholder="usuario@ejemplo.com"
                                />
                                <div
                                    v-if="form.errors.email"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.email }}
                                </div>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Teléfono
                                </label>
                                <input
                                    v-model="form.telefono"
                                    type="text"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none"
                                    placeholder="999999999"
                                />
                                <div
                                    v-if="form.errors.telefono"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.telefono }}
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Dirección
                                </label>
                                <textarea
                                    v-model="form.direccion"
                                    rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none"
                                    placeholder="Ingrese la dirección completa"
                                ></textarea>
                                <div
                                    v-if="form.errors.direccion"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.direccion }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Roles y Estado -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            Roles y Estado
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Estado *
                                </label>
                                <select
                                    v-model="form.estado"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none"
                                >
                                    <option value="1">Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
                                <div
                                    v-if="form.errors.estado"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.estado }}
                                </div>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Roles
                                </label>
                                <div
                                    class="space-y-2 max-h-32 overflow-y-auto border border-gray-300 rounded-md p-3"
                                >
                                    <label
                                        v-for="role in roles"
                                        :key="role.id"
                                        class="flex items-center"
                                    >
                                        <input
                                            v-model="form.roles"
                                            :value="role.name"
                                            type="checkbox"
                                            class="h-4 w-4 text-cyan-600 focus:ring-cyan-500 border-gray-300 rounded"
                                        />
                                        <span
                                            class="ml-2 text-sm text-gray-700"
                                            >{{ role.name }}</span
                                        >
                                    </label>
                                </div>
                                <div
                                    v-if="form.errors.roles"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.roles }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div
                        class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200"
                    >
                        <Link
                            href="/admin/users"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 transition-colors"
                        >
                            Cancelar
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-cyan-600 hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 disabled:opacity-50 transition-colors"
                        >
                            <span v-if="form.processing">Actualizando...</span>
                            <span v-else>Actualizar Usuario</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Toast para notificaciones -->
        <Toast ref="toastRef" />
    </AppLayout>
</template>
