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
                                :href="route('paciente.recordatorios.index')"
                                class="inline-flex items-center text-gray-500 hover:text-gray-700"
                            >
                                <ArrowLeftIcon class="h-5 w-5 mr-2" />
                                Volver a recordatorios
                            </Link>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 mt-4">
                        <div class="flex-shrink-0">
                            <PencilSquareIcon class="h-8 w-8 text-cyan-600" />
                        </div>
                        <div class="flex-1">
                            <h1 class="text-2xl font-bold text-gray-900">Editar recordatorio</h1>
                            <p class="text-sm text-gray-600">Modifica la información del recordatorio "{{ recordatorio.titulo }}"</p>
                        </div>

                        <!-- Info adicional -->
                        <div class="hidden md:flex items-center space-x-6 text-sm text-gray-500">
                            <div class="flex items-center">
                                <CalendarIcon class="h-4 w-4 mr-1" />
                                Creado: {{ new Date(recordatorio.created_at).toLocaleDateString() }}
                            </div>
                            <div class="flex items-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                      :class="getEstadoClass(recordatorio.estado)">
                                    {{ getEstadoLabel(recordatorio.estado) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulario -->
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <form @submit.prevent="submit" class="space-y-8">
                    <!-- Información del Recordatorio -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-6">Información del recordatorio</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="titulo" class="block text-sm font-medium text-gray-700 mb-2">
                                    Título *
                                </label>
                                <input
                                    id="titulo"
                                    v-model="form.titulo"
                                    type="text"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
                                    :class="{ 'border-red-300': form.errors.titulo }"
                                    placeholder="Ingrese el título del recordatorio"
                                >
                                <div v-if="form.errors.titulo" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.titulo }}
                                </div>
                            </div>

                            <div>
                                <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tipo de recordatorio *
                                </label>
                                <select
                                    id="tipo"
                                    v-model="form.tipo"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
                                    :class="{ 'border-red-300': form.errors.tipo }"
                                >
                                    <option value="">Seleccione un tipo</option>
                                    <option value="recordatorio_personal">Personal</option>
                                    <option value="vacuna_proxima">Vacuna próxima</option>
                                    <option value="cita_programada">Cita programada</option>
                                    <option value="refuerzo_pendiente">Refuerzo pendiente</option>
                                </select>
                                <div v-if="form.errors.tipo" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.tipo }}
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <label for="mensaje" class="block text-sm font-medium text-gray-700 mb-2">
                                    Mensaje
                                </label>
                                <textarea
                                    id="mensaje"
                                    v-model="form.mensaje"
                                    rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
                                    :class="{ 'border-red-300': form.errors.mensaje }"
                                    placeholder="Mensaje adicional del recordatorio..."
                                ></textarea>
                                <div v-if="form.errors.mensaje" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.mensaje }}
                                </div>
                                <p class="mt-1 text-xs text-gray-500">
                                    Máximo 500 caracteres. Restantes: {{ 500 - (form.mensaje?.length || 0) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Fecha y Hora -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-6">Fecha y hora del recordatorio</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="fecha_recordatorio" class="block text-sm font-medium text-gray-700 mb-2">
                                    Fecha del recordatorio *
                                </label>
                                <input
                                    id="fecha_recordatorio"
                                    v-model="form.fecha_recordatorio"
                                    type="date"
                                    required
                                    :min="fechaMinima"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
                                    :class="{ 'border-red-300': form.errors.fecha_recordatorio }"
                                >
                                <div v-if="form.errors.fecha_recordatorio" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.fecha_recordatorio }}
                                </div>
                                <p class="mt-1 text-xs text-gray-500">
                                    Días restantes: {{ diasRestantes }}
                                </p>
                            </div>

                            <div>
                                <label for="hora_recordatorio" class="block text-sm font-medium text-gray-700 mb-2">
                                    Hora del recordatorio
                                </label>
                                <input
                                    id="hora_recordatorio"
                                    v-model="form.hora_recordatorio"
                                    type="time"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
                                    :class="{ 'border-red-300': form.errors.hora_recordatorio }"
                                >
                                <div v-if="form.errors.hora_recordatorio" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.hora_recordatorio }}
                                </div>
                                <p class="mt-1 text-xs text-gray-500">
                                    Opcional. Si no se especifica, se enviará a las 09:00
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Asociaciones -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-6">Asociaciones</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="dependiente_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Para (dependiente)
                                </label>
                                <select
                                    id="dependiente_id"
                                    v-model="form.dependiente_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
                                    :class="{ 'border-red-300': form.errors.dependiente_id }"
                                >
                                    <option value="">Para mí (personal)</option>
                                    <option v-for="dependiente in dependientes" :key="dependiente.id" :value="dependiente.id">
                                        {{ dependiente.nombre_completo }}
                                    </option>
                                </select>
                                <div v-if="form.errors.dependiente_id" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.dependiente_id }}
                                </div>
                                <p class="mt-1 text-xs text-gray-500">
                                    Selecciona si el recordatorio es para algún dependiente específico
                                </p>
                            </div>

                            <div>
                                <label for="vacuna_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Vacuna relacionada
                                </label>
                                <select
                                    id="vacuna_id"
                                    v-model="form.vacuna_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
                                    :class="{ 'border-red-300': form.errors.vacuna_id }"
                                >
                                    <option value="">Sin vacuna específica</option>
                                    <option v-for="vacuna in vacunas" :key="vacuna.id" :value="vacuna.id">
                                        {{ vacuna.nombre }}
                                    </option>
                                </select>
                                <div v-if="form.errors.vacuna_id" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.vacuna_id }}
                                </div>
                                <p class="mt-1 text-xs text-gray-500">
                                    Opcional. Selecciona una vacuna si el recordatorio está relacionado
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Estado -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-6">Estado del recordatorio</h3>

                        <div>
                            <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">
                                Estado *
                            </label>
                            <select
                                id="estado"
                                v-model="form.estado"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
                                :class="{ 'border-red-300': form.errors.estado }"
                            >
                                <option value="programado">📅 Programado</option>
                                <option value="es_hoy">⏰ Es hoy</option>
                                <option value="hecho">✅ Ya lo hice</option>
                                <option value="no_hice">❌ No lo hice a tiempo</option>
                                <option value="no_aplica">🚫 Ya no aplica</option>
                            </select>
                            <div v-if="form.errors.estado" class="mt-1 text-sm text-red-600">
                                {{ form.errors.estado }}
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                <strong>📅 Programado:</strong> El recordatorio está agendado para su fecha<br>
                                <strong>⏰ Es hoy:</strong> ¡Hoy es el día! Debes hacer esta acción<br>
                                <strong>✅ Ya lo hice:</strong> Completaste exitosamente la acción<br>
                                <strong>❌ No lo hice:</strong> Se pasó la fecha y no se realizó<br>
                                <strong>🚫 Ya no aplica:</strong> El recordatorio ya no es necesario
                            </p>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex items-center justify-end space-x-4">
                        <Link
                            :href="route('paciente.recordatorios.index')"
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
                            <span v-else>Actualizar Recordatorio</span>
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
    PencilSquareIcon,
    CalendarIcon
} from '@heroicons/vue/24/outline';
import Toast from '@/Components/Toast.vue';

const props = defineProps({
    recordatorio: Object,
    dependientes: Array,
    vacunas: Array
});

// Estado para notificaciones
const showToast = ref(false);
const toastType = ref('success');
const toastTitle = ref('');
const toastMessage = ref('');

// Función para formatear fecha para input date
const formatearFechaParaInput = (fecha) => {
    if (!fecha) return '';
    const date = new Date(fecha);
    if (isNaN(date.getTime())) return '';
    return date.toISOString().split('T')[0];
};

// Función para formatear hora para input time
const formatearHoraParaInput = (hora) => {
    if (!hora) return '';
    // Si viene en formato HH:MM:SS, extraer solo HH:MM
    return hora.length > 5 ? hora.substring(0, 5) : hora;
};

// Formulario reactivo con datos existentes
const form = useForm({
    titulo: props.recordatorio.titulo,
    tipo: props.recordatorio.tipo,
    mensaje: props.recordatorio.mensaje || '',
    fecha_recordatorio: formatearFechaParaInput(props.recordatorio.fecha_recordatorio),
    hora_recordatorio: formatearHoraParaInput(props.recordatorio.hora_recordatorio),
    dependiente_id: props.recordatorio.dependiente_id || '',
    vacuna_id: props.recordatorio.vacuna_id || '',
    estado: props.recordatorio.estado
});

// Fecha mínima (hoy)
const fechaMinima = computed(() => {
    const hoy = new Date();
    return hoy.toISOString().split('T')[0];
});

// Calcular días restantes
const diasRestantes = computed(() => {
    if (!form.fecha_recordatorio) return 0;

    const hoy = new Date();
    const fechaRecordatorio = new Date(form.fecha_recordatorio);
    const diferencia = Math.ceil((fechaRecordatorio - hoy) / (1000 * 60 * 60 * 24));

    return diferencia;
});

// Funciones para el estado
const getEstadoClass = (estado) => {
    const classes = {
        'programado': 'bg-blue-100 text-blue-800',
        'es_hoy': 'bg-orange-100 text-orange-800',
        'hecho': 'bg-green-100 text-green-800',
        'no_hice': 'bg-red-100 text-red-800',
        'no_aplica': 'bg-gray-100 text-gray-800'
    };
    return classes[estado] || 'bg-gray-100 text-gray-800';
};

const getEstadoLabel = (estado) => {
    const labels = {
        'programado': '📅 Programado',
        'es_hoy': '⏰ Es hoy',
        'hecho': '✅ Ya lo hice',
        'no_hice': '❌ No lo hice',
        'no_aplica': '🚫 Ya no aplica'
    };
    return labels[estado] || estado;
};

// Función para mostrar notificaciones
const showNotification = (type, title, message = '') => {
    toastType.value = type;
    toastTitle.value = title;
    toastMessage.value = message;
    showToast.value = true;
};

// Métodos
const submit = () => {
    form.put(route('paciente.recordatorios.update', props.recordatorio.id), {
        onSuccess: () => {
            showNotification('success', 'Recordatorio actualizado', 'Los cambios han sido guardados exitosamente.');
        },
        onError: (errors) => {
            const errorMessage = errors.message || 'Ocurrió un error al actualizar el recordatorio.';
            showNotification('error', 'Error al actualizar', errorMessage);
        }
    });
};
</script>
