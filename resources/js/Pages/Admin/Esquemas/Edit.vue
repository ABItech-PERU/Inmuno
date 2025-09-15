<script setup>
import { ref, watch } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import {
    ArrowLeftIcon,
    CalendarIcon,
    ClipboardDocumentListIcon,
    ExclamationTriangleIcon,
    InformationCircleIcon
} from '@heroicons/vue/24/outline';
import Toast from '@/Components/Toast.vue';

const props = defineProps({
    esquema: Object,
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
    nombre: props.esquema.nombre,
    descripcion: props.esquema.descripcion,
    grupo_edad: props.esquema.grupo_edad,
    edad_inicio: props.esquema.edad_inicio,
    edad_fin: props.esquema.edad_fin,
    obligatorio: props.esquema.obligatorio,
    activo: props.esquema.activo
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
    form.patch(`/admin/esquemas/${props.esquema.id}`, {
        onSuccess: () => {
            // La redirección se maneja en el controlador, pero podemos mostrar mensaje antes
            showNotification('success', 'Esquema actualizado', 'El esquema de vacunación ha sido actualizado exitosamente.');
        },
        onError: (errors) => {
            const errorMessage = errors.message || 'Ocurrió un error al actualizar el esquema.';
            showNotification('error', 'Error al actualizar', errorMessage);
        }
    });
};
</script>

<template>
    <AppLayout title="Editar Esquema de Vacunación">
        <Head title="Editar Esquema de Vacunación" />

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
                        <div class="flex-1">
                            <h1 class="text-2xl font-bold text-gray-900">Editar Esquema de Vacunación</h1>
                            <p class="text-sm text-gray-600">Modifica la información de {{ esquema.nombre }}</p>
                        </div>

                        <!-- Info adicional -->
                        <div class="hidden md:flex items-center space-x-6 text-sm text-gray-500">
                            <div class="flex items-center">
                                <CalendarIcon class="h-4 w-4 mr-1" />
                                Creado: {{ new Date(esquema.created_at).toLocaleDateString() }}
                            </div>
                            <div class="flex items-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                      :class="esquema.activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                                    {{ esquema.activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </div>
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
                                    placeholder="Ej: Esquema Nacional 0-2 meses, Vacunación Gestantes, Adultos Mayores"
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
                        <h3 class="text-lg font-medium text-gray-900 mb-6">Rango de Edad (Opcional)</h3>
                        <p class="text-sm text-gray-600 mb-4">
                            Define el rango específico de edad en meses para este esquema. Esto ayuda a determinar automáticamente qué esquemas aplican a cada paciente.
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
                                    autocomplete="off"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
                                    placeholder="Ej: 0, 2, 12, 240"
                                >
                                <p class="mt-1 text-xs text-gray-500">0 = recién nacido, 12 = 1 año, 24 = 2 años</p>
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
                                    autocomplete="off"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
                                    :class="{ 'border-red-300': form.errors.edad_fin }"
                                    placeholder="Ej: 2, 12, 60, 720"
                                >
                                <div v-if="form.errors.edad_fin" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.edad_fin }}
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Dejar vacío si no hay límite superior</p>
                            </div>
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
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <InformationCircleIcon class="h-5 w-5 text-amber-400" />
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-amber-800">
                                    Gestión de vacunas del esquema
                                </h3>
                                <div class="mt-2 text-sm text-amber-700">
                                    <p>Para agregar o modificar las vacunas de este esquema, guarda estos cambios y luego:</p>
                                    <ul class="list-disc ml-5 mt-1">
                                        <li>Ve a la vista detalle del esquema</li>
                                        <li>Usa la sección de gestión de vacunas</li>
                                        <li>Configura dosis, horarios y recordatorios</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex items-center justify-end space-x-4">
                        <Link
                            href="/admin/esquemas"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-cyan-500"
                        >
                            Cancelar
                        </Link>

                        <Link
                            :href="`/admin/esquemas/${esquema.id}`"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-cyan-500"
                        >
                            Ver esquema
                        </Link>

                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm bg-cyan-600 text-sm font-medium text-white hover:bg-cyan-700 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-cyan-500 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span v-if="form.processing">Guardando...</span>
                            <span v-else>Actualizar Esquema</span>
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
