<script setup>
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import {
    ArrowLeftIcon,
    BeakerIcon,
    ExclamationTriangleIcon,
    CalendarIcon,
    UserIcon
} from '@heroicons/vue/24/outline';
import Toast from '@/Components/Toast.vue';

const page = usePage();

const props = defineProps({
    vacuna: Object,
});

// Estado para notificaciones
const showToast = ref(false);
const toastType = ref('success');
const toastTitle = ref('');
const toastMessage = ref('');

const form = useForm({
    nombre: props.vacuna.nombre,
    descripcion: props.vacuna.descripcion || '',
    fabricante: props.vacuna.fabricante || '',
    tipo: props.vacuna.tipo,
    dosis_requeridas: props.vacuna.dosis_requeridas,
    intervalo_entre_dosis: props.vacuna.intervalo_entre_dosis,
    edad_minima: props.vacuna.edad_minima,
    edad_maxima: props.vacuna.edad_maxima,
    contraindicaciones: props.vacuna.contraindicaciones || '',
    efectos_secundarios: props.vacuna.efectos_secundarios || '',
    activa: props.vacuna.activa
});

const tiposVacuna = [
    'Bacteria atenuada',
    'Virus atenuado',
    'Virus inactivado',
    'Subunidad viral',
    'Conjugada',
    'ARNm',
    'Combinada',
    'Toxoide',
    'Polisacárida',
    'Recombinante'
];

// Función para mostrar notificaciones
const showNotification = (type, title, message = '') => {
    toastType.value = type;
    toastTitle.value = title;
    toastMessage.value = message;
    showToast.value = true;
};

const submit = () => {
    form.put(`/admin/vacunas/${props.vacuna.id}`, {
        onSuccess: () => {
            showNotification('success', 'Vacuna actualizada', 'Los cambios han sido guardados exitosamente.');
        },
        onError: (errors) => {
            const errorMessage = errors.message || 'Ocurrió un error al actualizar la vacuna.';
            showNotification('error', 'Error al actualizar', errorMessage);
        }
    });
};
</script>

<template>
    <AppLayout :title="`Editar ${vacuna.nombre}`">
        <Head :title="`Editar ${vacuna.nombre}`" />

        <div class="min-h-screen bg-gray-50">
            <!-- Header -->
            <div class="bg-white shadow-sm border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <Link
                                href="/admin/vacunas"
                                class="inline-flex items-center text-gray-500 hover:text-gray-700"
                            >
                                <ArrowLeftIcon class="h-5 w-5 mr-2" />
                                Volver a vacunas
                            </Link>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 mt-4">
                        <div class="flex-shrink-0">
                            <BeakerIcon class="h-8 w-8 text-cyan-600" />
                        </div>
                        <div class="flex-1">
                            <h1 class="text-2xl font-bold text-gray-900">Editar Vacuna</h1>
                            <p class="text-sm text-gray-600">Modifica la información de {{ vacuna.nombre }}</p>
                        </div>

                        <!-- Info adicional -->
                        <div class="hidden md:flex items-center space-x-6 text-sm text-gray-500">
                            <div class="flex items-center">
                                <CalendarIcon class="h-4 w-4 mr-1" />
                                Creada: {{ new Date(vacuna.created_at).toLocaleDateString() }}
                            </div>
                            <div class="flex items-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                      :class="vacuna.activa ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                                    {{ vacuna.activa ? 'Activa' : 'Inactiva' }}
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
                            <div>
                                <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nombre de la Vacuna *
                                </label>
                                <input
                                    id="nombre"
                                    v-model="form.nombre"
                                    type="text"
                                    autocomplete="off"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
                                    :class="{ 'border-red-300': form.errors.nombre }"
                                    placeholder="Ej: BCG, Hepatitis B, COVID-19"
                                >
                                <div v-if="form.errors.nombre" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.nombre }}
                                </div>
                            </div>

                            <div>
                                <label for="fabricante" class="block text-sm font-medium text-gray-700 mb-2">
                                    Fabricante
                                </label>
                                <input
                                    id="fabricante"
                                    v-model="form.fabricante"
                                    type="text"
                                    autocomplete="off"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
                                    placeholder="Ej: Pfizer, GSK, Instituto Butantan"
                                >
                            </div>

                            <div>
                                <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tipo de Vacuna *
                                </label>
                                <select
                                    id="tipo"
                                    v-model="form.tipo"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
                                    :class="{ 'border-red-300': form.errors.tipo }"
                                >
                                    <option value="">Seleccione un tipo</option>
                                    <option v-for="tipo in tiposVacuna" :key="tipo" :value="tipo">
                                        {{ tipo }}
                                    </option>
                                </select>
                                <div v-if="form.errors.tipo" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.tipo }}
                                </div>
                            </div>

                            <div>
                                <label for="dosis_requeridas" class="block text-sm font-medium text-gray-700 mb-2">
                                    Dosis Requeridas *
                                </label>
                                <input
                                    id="dosis_requeridas"
                                    v-model.number="form.dosis_requeridas"
                                    type="number"
                                    min="1"
                                    max="10"
                                    autocomplete="off"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
                                    :class="{ 'border-red-300': form.errors.dosis_requeridas }"
                                >
                                <div v-if="form.errors.dosis_requeridas" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.dosis_requeridas }}
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
                                    autocomplete="off"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
                                    placeholder="Descripción detallada de la vacuna y su función"
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Información de Aplicación -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-6">Información de Aplicación</h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="intervalo_entre_dosis" class="block text-sm font-medium text-gray-700 mb-2">
                                    Intervalo entre Dosis (días)
                                </label>
                                <input
                                    id="intervalo_entre_dosis"
                                    v-model.number="form.intervalo_entre_dosis"
                                    type="number"
                                    min="1"
                                    autocomplete="off"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
                                    placeholder="Ej: 30, 60, 90"
                                >
                                <p class="mt-1 text-xs text-gray-500">Solo si requiere múltiples dosis</p>
                            </div>

                            <div>
                                <label for="edad_minima" class="block text-sm font-medium text-gray-700 mb-2">
                                    Edad Mínima (meses)
                                </label>
                                <input
                                    id="edad_minima"
                                    v-model.number="form.edad_minima"
                                    type="number"
                                    min="0"
                                    autocomplete="off"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
                                    placeholder="Ej: 0, 2, 6, 12"
                                >
                                <p class="mt-1 text-xs text-gray-500">0 = recién nacido</p>
                            </div>

                            <div>
                                <label for="edad_maxima" class="block text-sm font-medium text-gray-700 mb-2">
                                    Edad Máxima (meses)
                                </label>
                                <input
                                    id="edad_maxima"
                                    v-model.number="form.edad_maxima"
                                    type="number"
                                    min="0"
                                    autocomplete="off"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
                                    :class="{ 'border-red-300': form.errors.edad_maxima }"
                                    placeholder="Ej: 24, 72, 240"
                                >
                                <div v-if="form.errors.edad_maxima" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.edad_maxima }}
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Dejar vacío si no hay límite</p>
                            </div>
                        </div>
                    </div>

                    <!-- Información Médica -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-6">Información Médica</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="contraindicaciones" class="block text-sm font-medium text-gray-700 mb-2">
                                    Contraindicaciones
                                </label>
                                <textarea
                                    id="contraindicaciones"
                                    v-model="form.contraindicaciones"
                                    rows="4"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
                                    placeholder="Condiciones o situaciones en las que no se debe administrar la vacuna"
                                ></textarea>
                            </div>

                            <div>
                                <label for="efectos_secundarios" class="block text-sm font-medium text-gray-700 mb-2">
                                    Efectos Secundarios
                                </label>
                                <textarea
                                    id="efectos_secundarios"
                                    v-model="form.efectos_secundarios"
                                    rows="4"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
                                    placeholder="Efectos secundarios comunes o esperados"
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Estado y Auditoría -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-6">Estado y Auditoría</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="flex items-center">
                                    <input
                                        id="activa"
                                        v-model="form.activa"
                                        type="checkbox"
                                        class="h-4 w-4 text-cyan-600 focus:ring-cyan-500 border-gray-300 rounded"
                                    >
                                    <label for="activa" class="ml-2 block text-sm text-gray-900">
                                        Vacuna activa
                                    </label>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">
                                    Las vacunas inactivas no aparecerán en los esquemas de vacunación
                                </p>
                            </div>

                            <div class="space-y-2 text-sm text-gray-600">
                                <div>
                                    <span class="font-medium">Creada:</span>
                                    {{ new Date(vacuna.created_at).toLocaleString() }}
                                </div>
                                <div>
                                    <span class="font-medium">Última actualización:</span>
                                    {{ new Date(vacuna.updated_at).toLocaleString() }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                        <Link
                            href="/admin/vacunas"
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
                            <span v-else>Actualizar Vacuna</span>
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
