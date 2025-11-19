<script setup>
import { ref, computed } from 'vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { ArrowLeftIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    recordatorio: Object,
    users: Array,
    vacunas: Array,
    citas: Array,
    dependientes: Array,
});

// Formatear fecha para input type="date" (YYYY-MM-DD)
const formatDateForInput = (date) => {
    if (!date) return '';

    try {
        // Si ya es un string en formato YYYY-MM-DD, devolverlo
        if (typeof date === 'string' && /^\d{4}-\d{2}-\d{2}$/.test(date)) {
            return date;
        }

        // Crear fecha y formatear
        const d = new Date(date);

        // Verificar que la fecha sea válida
        if (isNaN(d.getTime())) {
            return '';
        }

        return d.toISOString().split('T')[0];
    } catch (error) {
        return '';
    }
};

// Formatear hora para input type="time" (HH:MM)
const formatTimeForInput = (time) => {
    if (!time) return '';

    try {
        // Si ya tiene el formato HH:MM, devolverlo tal cual
        if (typeof time === 'string' && /^\d{2}:\d{2}$/.test(time)) {
            return time;
        }

        // Si viene como HH:MM:SS, tomar solo HH:MM
        if (typeof time === 'string' && /^\d{2}:\d{2}:\d{2}$/.test(time)) {
            return time.substring(0, 5);
        }

        // Si viene como Date o string de tiempo, intentar parsear
        const d = new Date(`1970-01-01T${time}`);
        if (!isNaN(d.getTime())) {
            return d.toTimeString().substring(0, 5);
        }

        // Si no se puede parsear, devolver vacío
        return '';
    } catch (error) {
        return '';
    }
};

const form = useForm({
    titulo: props.recordatorio.titulo || '',
    fecha_recordatorio: formatDateForInput(props.recordatorio.fecha_recordatorio) || '',
    hora_recordatorio: formatTimeForInput(props.recordatorio.hora_recordatorio) || '',
});

// Opciones para tipos (solo para mostrar información)
const tipoOptions = [
    { value: 'vacuna_proxima', label: 'Vacuna Próxima' },
    { value: 'cita_programada', label: 'Cita Programada' },
    { value: 'refuerzo_pendiente', label: 'Refuerzo Pendiente' },
    { value: 'recordatorio_personal', label: 'Recordatorio Personal' }
];

// Obtener texto del estado
const getStatusText = (estado) => {
    const statusTexts = {
        'pendiente': 'Pendiente',
        'enviado': 'Enviado',
        'leido': 'Leído',
        'programado': 'Programado'
    };
    return statusTexts[estado] || estado;
};

const submit = () => {
    form.put(`/admin/recordatorios/${props.recordatorio.id}`, {
        onSuccess: () => {
            router.visit('/admin/recordatorios');
        },
        onError: (errors) => {
            console.error('Errores de validación:', errors);
        }
    });
};

const goBack = () => {
    router.visit('/admin/recordatorios');
};
</script>

<template>
    <AppLayout title="Editar Recordatorio">
        <Head title="Editar Recordatorio" />

        <div class="min-h-screen bg-gray-50">
            <!-- Header -->
            <div class="bg-white shadow-sm border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <Link
                                href="/admin/recordatorios"
                                class="inline-flex items-center text-gray-500 hover:text-gray-700"
                            >
                                <ArrowLeftIcon class="h-5 w-5 mr-2" />
                                Volver a recordatorios
                            </Link>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 mt-4">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">
                                Editar Recordatorio
                            </h1>
                            <p class="text-sm text-gray-600">
                                Actualiza la información del recordatorio
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
                    <!-- Información del Recordatorio (solo lectura) -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            Información del Recordatorio
                        </h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="font-medium text-gray-700">Tipo:</span>
                                    <span class="ml-2 text-gray-900">{{ tipoOptions.find(t => t.value === props.recordatorio.tipo)?.label || props.recordatorio.tipo }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Usuario:</span>
                                    <span class="ml-2 text-gray-900">{{ props.recordatorio.usuario?.name }} {{ props.recordatorio.usuario?.apellidos }}</span>
                                </div>
                                <div v-if="props.recordatorio.dependiente">
                                    <span class="font-medium text-gray-700">Dependiente:</span>
                                    <span class="ml-2 text-gray-900">{{ props.recordatorio.dependiente.nombres }} {{ props.recordatorio.dependiente.apellidos }}</span>
                                </div>
                                <div v-if="props.recordatorio.vacuna">
                                    <span class="font-medium text-gray-700">Vacuna:</span>
                                    <span class="ml-2 text-gray-900">{{ props.recordatorio.vacuna.nombre }}</span>
                                </div>
                                <div v-if="props.recordatorio.cita">
                                    <span class="font-medium text-gray-700">Cita:</span>
                                    <span class="ml-2 text-gray-900">{{ props.recordatorio.cita.titulo || props.recordatorio.cita.motivo }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Estado:</span>
                                    <span class="ml-2 text-gray-900">{{ getStatusText(props.recordatorio.estado) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información Editable -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            Información Editable
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Título *
                                </label>
                                <input
                                    v-model="form.titulo"
                                    type="text"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none"
                                    placeholder="Ingrese el título del recordatorio"
                                />
                                <div
                                    v-if="form.errors.titulo"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.titulo }}
                                </div>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Fecha del recordatorio *
                                </label>
                                <input
                                    v-model="form.fecha_recordatorio"
                                    type="date"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none"
                                />
                                <div
                                    v-if="form.errors.fecha_recordatorio"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.fecha_recordatorio }}
                                </div>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Hora del recordatorio
                                </label>
                                <input
                                    v-model="form.hora_recordatorio"
                                    type="time"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none"
                                />
                                <div
                                    v-if="form.errors.hora_recordatorio"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.hora_recordatorio }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                        <SecondaryButton
                            @click="goBack"
                            type="button"
                        >
                            Cancelar
                        </SecondaryButton>
                        <PrimaryButton
                            :disabled="form.processing"
                            type="submit"
                        >
                            <span v-if="form.processing">Guardando...</span>
                            <span v-else>Guardar Cambios</span>
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
