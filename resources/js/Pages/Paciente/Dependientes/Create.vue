<template>
    <AppLayout title="Nuevo Dependiente">
        <Head title="Nuevo Dependiente" />

        <div class="min-h-screen bg-gray-50">
            <!-- Header -->
            <div class="bg-white shadow-sm border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <Link
                                :href="route('paciente.dependientes.index')"
                                class="inline-flex items-center text-gray-500 hover:text-gray-700"
                            >
                                <ArrowLeftIcon class="h-5 w-5 mr-2" />
                                Volver a dependientes
                            </Link>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 mt-4">
                        <div class="flex-shrink-0">
                            <UserPlusIcon class="h-8 w-8 text-cyan-600" />
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Nuevo dependiente</h1>
                            <p class="text-sm text-gray-600">Registra los datos de tu dependiente</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulario -->
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <form @submit.prevent="submit" class="space-y-8">
                    <!-- Información Personal -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-6">Información personal</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="nombres" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nombres *
                                </label>
                                <input
                                    id="nombres"
                                    v-model="form.nombres"
                                    type="text"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
                                    :class="{ 'border-red-300': form.errors.nombres }"
                                    placeholder="Ingrese los nombres"
                                    autocomplete="off"
                                >
                                <div v-if="form.errors.nombres" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.nombres }}
                                </div>
                            </div>

                            <div>
                                <label for="apellidos" class="block text-sm font-medium text-gray-700 mb-2">
                                    Apellidos *
                                </label>
                                <input
                                    id="apellidos"
                                    v-model="form.apellidos"
                                    type="text"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
                                    :class="{ 'border-red-300': form.errors.apellidos }"
                                    placeholder="Ingrese los apellidos"
                                    autocomplete="off"
                                >
                                <div v-if="form.errors.apellidos" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.apellidos }}
                                </div>
                            </div>

                            <div>
                                <label for="tipo_documento" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tipo de documento *
                                </label>
                                <select
                                    id="tipo_documento"
                                    v-model="form.tipo_documento"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
                                    :class="{ 'border-red-300': form.errors.tipo_documento }"
                                >
                                    <option value="">Seleccione un tipo</option>
                                    <option v-for="(label, value) in tiposDocumento" :key="value" :value="value">
                                        {{ label }}
                                    </option>
                                </select>
                                <div v-if="form.errors.tipo_documento" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.tipo_documento }}
                                </div>
                            </div>

                            <div>
                                <label for="numero_documento" class="block text-sm font-medium text-gray-700 mb-2">
                                    Número de documento *
                                </label>
                                <input
                                    id="numero_documento"
                                    v-model="form.numero_documento"
                                    type="text"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
                                    :class="{ 'border-red-300': form.errors.numero_documento }"
                                    placeholder="Ingrese el número"
                                    autocomplete="off"
                                >
                                <div v-if="form.errors.numero_documento" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.numero_documento }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información Básica -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-6">Información básica</h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-700 mb-2">
                                    Fecha de nacimiento *
                                </label>
                                <input
                                    id="fecha_nacimiento"
                                    v-model="form.fecha_nacimiento"
                                    type="date"
                                    required
                                    :max="fechaMaxima"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
                                    :class="{ 'border-red-300': form.errors.fecha_nacimiento }"
                                >
                                <div v-if="form.errors.fecha_nacimiento" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.fecha_nacimiento }}
                                </div>
                            </div>

                            <div>
                                <label for="genero" class="block text-sm font-medium text-gray-700 mb-2">
                                    Género *
                                </label>
                                <select
                                    id="genero"
                                    v-model="form.genero"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
                                    :class="{ 'border-red-300': form.errors.genero }"
                                >
                                    <option value="">Seleccione un género</option>
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                </select>
                                <div v-if="form.errors.genero" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.genero }}
                                </div>
                            </div>

                            <div>
                                <label for="parentesco" class="block text-sm font-medium text-gray-700 mb-2">
                                    Parentesco *
                                </label>
                                <select
                                    id="parentesco"
                                    v-model="form.parentesco"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
                                    :class="{ 'border-red-300': form.errors.parentesco }"
                                >
                                    <option value="">Seleccione el parentesco</option>
                                    <option v-for="(label, value) in parentescos" :key="value" :value="value">
                                        {{ label }}
                                    </option>
                                </select>
                                <div v-if="form.errors.parentesco" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.parentesco }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información de Contacto -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-6">Información de contacto</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">
                                    Teléfono
                                </label>
                                <input
                                    id="telefono"
                                    v-model="form.telefono"
                                    type="tel"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
                                    :class="{ 'border-red-300': form.errors.telefono }"
                                    placeholder="901231875"
                                >
                                <div v-if="form.errors.telefono" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.telefono }}
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-2">
                                    Observaciones
                                </label>
                                <textarea
                                    id="observaciones"
                                    v-model="form.observaciones"
                                    rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
                                    :class="{ 'border-red-300': form.errors.observaciones }"
                                    placeholder="Información adicional sobre el dependiente..."
                                ></textarea>
                                <div v-if="form.errors.observaciones" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.observaciones }}
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Máximo 500 caracteres</p>
                            </div>
                        </div>
                    </div>

                    <!-- Estado -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-6">Estado</h3>

                        <div class="flex items-center">
                            <input
                                id="activo"
                                v-model="form.activo"
                                type="checkbox"
                                class="h-4 w-4 text-indigo-600 focus:ring-cyan-500 border-gray-300 rounded"
                            >
                            <label for="activo" class="ml-2 block text-sm text-gray-900">
                                Dependiente activo
                            </label>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">
                            Los dependientes inactivos no aparecerán en los esquemas de vacunación
                        </p>
                    </div>

                    <!-- Botones -->
                    <div class="flex items-center justify-end space-x-4">
                        <Link
                            :href="route('paciente.dependientes.index')"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-cyan-500"
                        >
                            Cancelar
                        </Link>

                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm bg-cyan-600 text-sm font-medium text-white hover:bg-cyan-700 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-cyan-500 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span v-if="form.processing">Guardando...</span>
                            <span v-else>Crear dependiente</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Toast de notificaciones -->
        <Toast
            :show="showToast"
            :type="toastType"
            :title="toastTitle"
            :message="toastMessage"
            @close="showToast = false"
        />
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import {
    ArrowLeftIcon,
    UserPlusIcon,
    InformationCircleIcon
} from '@heroicons/vue/24/outline';
import Toast from '@/Components/Toast.vue';

const props = defineProps({
    parentescos: Object,
    tiposDocumento: Object
});

// Estado para notificaciones
const showToast = ref(false);
const toastType = ref('success');
const toastTitle = ref('');
const toastMessage = ref('');

// Formulario reactivo
const form = useForm({
    nombres: '',
    apellidos: '',
    tipo_documento: '',
    numero_documento: '',
    fecha_nacimiento: '',
    genero: '',
    parentesco: '',
    telefono: '',
    observaciones: '',
    activo: true
});

// Fecha máxima (hoy)
const fechaMaxima = computed(() => {
    const hoy = new Date();
    return hoy.toISOString().split('T')[0];
});

// Función para mostrar notificaciones
const showNotification = (type, title, message = '') => {
    toastType.value = type;
    toastTitle.value = title;
    toastMessage.value = message;
    showToast.value = true;
};

// Método para enviar formulario
const submit = () => {
    form.post(route('paciente.dependientes.store'), {
        onSuccess: () => {
            // La redirección se maneja desde el controlador, pero podemos mostrar mensaje antes
            showNotification('success', 'Dependiente creado', 'El dependiente ha sido registrado exitosamente.');
        },
        onError: (errors) => {
            const errorMessage = errors.message || 'Ocurrió un error al crear el dependiente.';
            showNotification('error', 'Error al crear', errorMessage);
        }
    });
};
</script>
