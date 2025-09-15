<script setup>
import { ref, watch } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import {
    ArrowLeftIcon,
    ClipboardDocumentListIcon,
    ExclamationTriangleIcon,
    InformationCircleIcon
} from '@heroicons/vue/24/outline';
import Toast from '@/Components/Toast.vue';

const props = defineProps({
    grupos_edad_predefinidos: Array,
    grupos_edad_completos: Array
});

const page = usePage();

// Estado para notificaciones
const showToast = ref(false);
const toastType = ref('success');
const toastTitle = ref('');
const toastMessage = ref('');

const form = useForm({
    nombre: '',
    descripcion: '',
    grupo_edad: '',
    edad_inicio: null,
    edad_fin: null,
    obligatorio: true,
    activo: true
});

// Watch para actualizar los rangos de edad automáticamente
watch(() => form.grupo_edad, (nuevoGrupo) => {
    if (nuevoGrupo) {
        const grupoCompleto = props.grupos_edad_completos.find(g => g.nombre === nuevoGrupo);
        if (grupoCompleto) {
            form.edad_inicio = grupoCompleto.edad_inicio;
            form.edad_fin = grupoCompleto.edad_fin;
        }
    } else {
        form.edad_inicio = null;
        form.edad_fin = null;
    }
});

// Función para mostrar notificaciones
const showNotification = (type, title, message = '') => {
    toastType.value = type;
    toastTitle.value = title;
    toastMessage.value = message;
    showToast.value = true;
};

const submit = () => {
    form.post('/admin/esquemas', {
        onSuccess: () => {
            // La redirección se maneja en el controlador, pero podemos mostrar mensaje antes
            showNotification('success', 'Esquema creado', 'El esquema de vacunación ha sido registrado exitosamente.');
        },
        onError: (errors) => {
            const errorMessage = errors.message || 'Ocurrió un error al crear el esquema.';
            showNotification('error', 'Error al crear', errorMessage);
        }
    });
};
</script>

<template>
    <AppLayout title="Nuevo Esquema de Vacunación">
        <Head title="Nuevo Esquema de Vacunación" />

        <div class="min-h-screen bg-gray-50">
            <!-- Header -->
            <div class="bg-white shadow-sm border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <Link
                                href="/admin/esquemas"
                                class="inline-flex items-center text-gray-500 hover:text-gray-700"
                            >
                                <ArrowLeftIcon class="h-5 w-5 mr-2" />
                                Volver a esquemas
                            </Link>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 mt-4">
                        <div class="flex-shrink-0">
                            <ClipboardDocumentListIcon class="h-8 w-8 text-cyan-600" />
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Nuevo Esquema de Vacunación</h1>
                            <p class="text-sm text-gray-600">Crea un nuevo esquema basado en el protocolo nacional de vacunación</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulario -->
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

                <form @submit.prevent="submit" class="space-y-8">
                    <!-- Información Básica -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-6">Información Básica</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nombre del Esquema *
                                </label>
                                <input
                                    id="nombre"
                                    v-model="form.nombre"
                                    type="text"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
                                    :class="{ 'border-red-300': form.errors.nombre }"
                                    placeholder="Vacunación Gestantes, Adultos Mayores"
                                    autocomplete="off"
                                >
                                <div v-if="form.errors.nombre" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.nombre }}
                                </div>
                            </div>

                            <div>
                                <label for="grupo_edad" class="block text-sm font-medium text-gray-700 mb-2">
                                    Grupo de Edad *
                                </label>
                                <select
                                    id="grupo_edad"
                                    v-model="form.grupo_edad"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
                                    :class="{ 'border-red-300': form.errors.grupo_edad }"
                                >
                                    <option value="">Seleccione un grupo de edad</option>
                                    <option v-for="grupo in grupos_edad_predefinidos" :key="grupo" :value="grupo">
                                        {{ grupo }}
                                    </option>
                                </select>
                                <div v-if="form.errors.grupo_edad" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.grupo_edad }}
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                                    Descripción
                                </label>
                                <textarea
                                    id="descripcion"
                                    v-model="form.descripcion"
                                    rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
                                    placeholder="Describe el propósito y contenido de este esquema de vacunación"
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Rango de Edad -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-6">Rango de Edad</h3>
                        <p class="text-sm text-gray-600 mb-4">
                            El rango de edad se determina automáticamente según el grupo seleccionado. Estos rangos están predefinidos según el protocolo nacional de vacunación del Perú.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="edad_inicio" class="block text-sm font-medium text-gray-700 mb-2">
                                    Edad Inicio (meses)
                                </label>
                                <input
                                    id="edad_inicio"
                                    v-model.number="form.edad_inicio"
                                    type="number"
                                    min="0"
                                    readonly
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-700 cursor-not-allowed"
                                    placeholder="Se llena automáticamente"
                                >
                                <p class="mt-1 text-xs text-gray-500">Se establece automáticamente según el grupo de edad seleccionado</p>
                            </div>

                            <div>
                                <label for="edad_fin" class="block text-sm font-medium text-gray-700 mb-2">
                                    Edad Fin (meses)
                                </label>
                                <input
                                    id="edad_fin"
                                    v-model.number="form.edad_fin"
                                    type="number"
                                    min="0"
                                    readonly
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-700 cursor-not-allowed"
                                    :class="{ 'border-red-300': form.errors.edad_fin }"
                                    placeholder="Se llena automáticamente"
                                >
                                <div v-if="form.errors.edad_fin" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.edad_fin }}
                                </div>
                                <p class="mt-1 text-xs text-gray-500">
                                    <span v-if="form.edad_fin">Hasta {{ form.edad_fin }} meses</span>
                                    <span v-else-if="form.grupo_edad">Sin límite superior</span>
                                    <span v-else>Se establece según el grupo de edad</span>
                                </p>
                            </div>
                        </div>

                        <!-- Mostrar información del grupo seleccionado -->
                        <div v-if="form.grupo_edad" class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-md">
                            <h4 class="text-sm font-medium text-blue-800 mb-1">Información del Grupo: {{ form.grupo_edad }}</h4>
                            <template v-for="grupo in grupos_edad_completos" :key="grupo.nombre">
                                <p v-if="grupo.nombre === form.grupo_edad" class="text-sm text-blue-700">
                                    {{ grupo.descripcion }}
                                </p>
                            </template>
                        </div>
                    </div>

                    <!-- Configuración -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-6">Configuración</h3>

                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input
                                    id="obligatorio"
                                    v-model="form.obligatorio"
                                    type="checkbox"
                                    class="h-4 w-4 text-cyan-600 focus:ring-cyan-500 border-gray-300 rounded"
                                >
                                <label for="obligatorio" class="ml-2 block text-sm text-gray-900">
                                    Esquema obligatorio
                                </label>
                            </div>
                            <p class="ml-6 text-xs text-gray-500">
                                Los esquemas obligatorios son parte del protocolo nacional y deben cumplirse
                            </p>

                            <div class="flex items-center">
                                <input
                                    id="activo"
                                    v-model="form.activo"
                                    type="checkbox"
                                    class="h-4 w-4 text-cyan-600 focus:ring-cyan-500 border-gray-300 rounded"
                                >
                                <label for="activo" class="ml-2 block text-sm text-gray-900">
                                    Esquema activo
                                </label>
                            </div>
                            <p class="ml-6 text-xs text-gray-500">
                                Los esquemas inactivos no aparecerán disponibles para asignar a pacientes
                            </p>
                        </div>
                    </div>

                    <!-- Información sobre vacunas -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <ExclamationTriangleIcon class="h-5 w-5 text-yellow-400" />
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">
                                    Próximo paso: Asignar vacunas
                                </h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>Después de crear este esquema, podrás:</p>
                                    <ul class="list-disc ml-5 mt-1">
                                        <li>Agregar las vacunas específicas que incluye este esquema</li>
                                        <li>Definir las dosis y horarios de aplicación</li>
                                        <li>Establecer intervalos entre vacunas</li>
                                        <li>Configurar recordatorios automáticos</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                        <Link
                            href="/admin/esquemas"
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
                            <span v-else>Crear Esquema</span>
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
