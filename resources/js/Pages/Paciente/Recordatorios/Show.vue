<template>
    <AppLayout :title="`Recordatorio: ${recordatorio.titulo}`">
        <Head :title="`Recordatorio: ${recordatorio.titulo}`" />

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

                        <div class="flex items-center space-x-3">
                            <Link
                                v-if="puedeEditar"
                                :href="route('paciente.recordatorios.edit', recordatorio.id)"
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
                                <BellIcon v-if="recordatorio.tipo === 'recordatorio_personal'" class="h-12 w-12 text-cyan-600" />
                                <CalendarDaysIcon v-else-if="recordatorio.tipo === 'vacuna_proxima'" class="h-12 w-12 text-cyan-600" />
                                <ClockIcon v-else-if="recordatorio.tipo === 'cita_programada'" class="h-12 w-12 text-cyan-600" />
                                <ExclamationTriangleIcon v-else class="h-12 w-12 text-cyan-600" />
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">{{ recordatorio.titulo }}</h1>
                                <div class="flex items-center space-x-4 mt-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                          :class="getEstadoColors(recordatorio.estado)">
                                        <span :class="getEstadoDotColor(recordatorio.estado)" class="w-2 h-2 rounded-full mr-2"></span>
                                        {{ formatearEstado(recordatorio.estado) }}
                                    </span>
                                    <span class="text-sm text-gray-500">
                                        {{ formatearTipoRecordatorio(recordatorio.tipo) }}
                                    </span>
                                    <span class="text-sm font-medium"
                                          :class="getDiasRestantesTextColor(recordatorio.estado, estadisticas)">
                                        {{ estadisticas.dias_restantes }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido Principal -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Columna Principal -->
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Información del Recordatorio -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-6">Detalles del recordatorio</h2>

                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Título</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ recordatorio.titulo }}
                                </dd>
                            </div>

                            <div v-if="recordatorio.mensaje" class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Mensaje</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ recordatorio.mensaje }}
                                </dd>
                            </div>

                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Fecha programada</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ formatearFechaCompleta(recordatorio.fecha_recordatorio, recordatorio.hora_recordatorio) }}
                                </dd>
                            </div>

                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Tipo</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ formatearTipoRecordatorio(recordatorio.tipo) }}
                                </dd>
                            </div>

                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Estado actual</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                          :class="getEstadoColors(recordatorio.estado)">
                                        <span :class="getEstadoDotColor(recordatorio.estado)" class="w-2 h-2 rounded-full mr-1.5"></span>
                                        {{ formatearEstado(recordatorio.estado) }}
                                    </span>
                                </dd>
                            </div>

                            <!-- Información de Vacuna si existe -->
                            <div v-if="recordatorio.vacuna" class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Vacuna</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <div class="flex items-center space-x-2">
                                        <span class="font-medium">{{ recordatorio.vacuna.nombre }}</span>
                                        <span v-if="recordatorio.vacuna.descripcion" class="text-gray-500">-</span>
                                        <span v-if="recordatorio.vacuna.descripcion" class="bg-cyan-100 text-cyan-800 px-2 py-1 rounded-full text-xs font-medium">
                                            {{ recordatorio.vacuna.descripcion }}
                                        </span>
                                    </div>
                                </dd>
                            </div>

                            <!-- Información de Dependiente si existe -->
                            <div v-if="recordatorio.dependiente" class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Para</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <Link :href="route('paciente.dependientes.show', recordatorio.dependiente.id)"
                                          class="text-cyan-600 hover:text-cyan-800 font-medium">
                                        {{ recordatorio.dependiente.nombre }} {{ recordatorio.dependiente.apellido }}
                                    </Link>
                                    <span class="text-gray-500 ml-2">({{ recordatorio.dependiente.parentesco }})</span>
                                </dd>
                            </div>
                        </div>

                        <!-- Información de Registro -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-6">Información de registro</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de creación</label>
                                    <p class="text-sm text-gray-900">{{ formatearFecha(recordatorio.created_at) }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Última actualización</label>
                                    <p class="text-sm text-gray-900">{{ formatearFecha(recordatorio.updated_at) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="lg:col-span-1 space-y-6">
                        <!-- Estadísticas del Recordatorio -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <InformationCircleIcon class="h-5 w-5 inline mr-2 text-cyan-600" />
                                Estado del recordatorio
                            </h3>
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Días restantes</span>
                                    <span class="text-sm font-medium"
                                          :class="{
                                              'text-red-600': estadisticas.es_vencido,
                                              'text-orange-600': estadisticas.es_urgente && !estadisticas.es_vencido,
                                              'text-gray-900': !estadisticas.es_urgente && !estadisticas.es_vencido
                                          }">
                                        {{ estadisticas.dias_restantes }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Es urgente</span>
                                    <span :class="estadisticas.es_urgente ? 'text-orange-600' : 'text-green-600'" class="text-sm font-medium">
                                        {{ estadisticas.es_urgente ? 'Sí' : 'No' }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Esta semana</span>
                                    <span :class="estadisticas.semana_actual ? 'text-blue-600' : 'text-gray-600'" class="text-sm font-medium">
                                        {{ estadisticas.semana_actual ? 'Sí' : 'No' }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Puede completar</span>
                                    <span :class="estadisticas.puede_completar ? 'text-green-600' : 'text-gray-600'" class="text-sm font-medium">
                                        {{ estadisticas.puede_completar ? 'Sí' : 'No' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Acciones Rápidas -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <CogIcon class="h-5 w-5 inline mr-2 text-cyan-600" />
                                Acciones
                            </h3>
                            <div class="space-y-3">
                                <!-- Marcar como completado - solo para estados completables -->
                                <button
                                    v-if="estadisticas.puede_completar"
                                    @click="marcarComoCompletado"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm bg-green-600 text-sm font-medium text-white hover:bg-green-700 transition-colors"
                                >
                                    <CheckCircleIcon class="h-4 w-4 mr-2" />
                                    Marcar como hecho
                                </button>

                                <!-- Editar - solo para estados editables -->
                                <Link
                                    v-if="puedeEditar"
                                    :href="route('paciente.recordatorios.edit', recordatorio.id)"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 border border-cyan-300 rounded-md shadow-sm bg-cyan-50 text-sm font-medium text-cyan-700 hover:bg-cyan-100 transition-colors"
                                >
                                    <PencilIcon class="h-4 w-4 mr-2" />
                                    Editar recordatorio
                                </Link>

                                <!-- Estado informativo para no editables -->
                                <div v-if="!puedeEditar && !estadisticas.puede_completar"
                                     class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-200 rounded-md bg-gray-50 text-sm font-medium text-gray-500 cursor-not-allowed">
                                    <span class="inline-flex items-center">
                                        <span :class="getEstadoDotColor(recordatorio.estado)" class="w-2 h-2 rounded-full mr-2"></span>
                                        {{ getEstadoTexto(recordatorio.estado) }} - No modificable
                                    </span>
                                </div>

                                <!-- Eliminar - siempre disponible -->
                                <button
                                    @click="eliminarRecordatorio"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 border border-red-300 rounded-md shadow-sm bg-red-50 text-sm font-medium text-red-700 hover:bg-red-100 transition-colors"
                                >
                                    <TrashIcon class="h-4 w-4 mr-2" />
                                    Eliminar
                                </button>
                            </div>
                        </div>

                        <!-- Recordatorios Relacionados -->
                        <div v-if="recordatoriosRelacionados && recordatoriosRelacionados.length > 0"
                             class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <BellIcon class="h-5 w-5 inline mr-2 text-cyan-600" />
                                Recordatorios Relacionados
                            </h3>
                            <div class="space-y-3">
                                <div v-for="relacionado in recordatoriosRelacionados" :key="relacionado.id"
                                     class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ relacionado.vacuna ? relacionado.vacuna.nombre : relacionado.titulo }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ formatearFecha(relacionado.fecha_recordatorio) }}
                                        </p>
                                    </div>
                                    <Link :href="route('paciente.recordatorios.show', relacionado.id)"
                                          class="text-cyan-600 hover:text-cyan-800">
                                        <EyeIcon class="h-4 w-4" />
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alertas informativas -->
                <div v-if="estadisticas.es_urgente" class="mt-6 bg-orange-50 border border-orange-200 rounded-lg p-4">
                    <div class="flex">
                        <ExclamationTriangleIcon class="h-5 w-5 text-orange-400 flex-shrink-0" />
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-orange-800">Recordatorio Urgente</h3>
                            <div class="mt-2 text-sm text-orange-700">
                                <p v-if="estadisticas.es_hoy">Este recordatorio es para hoy. No olvides completarlo.</p>
                                <p v-else-if="estadisticas.es_manana">Este recordatorio es para mañana. Prepárate con anticipación.</p>
                                <p v-else-if="estadisticas.es_vencido">Este recordatorio ya ha pasado su fecha. Considera actualizarlo o marcarlo como no aplicable.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="estadisticas.requiere_atencion" class="mt-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex">
                        <ExclamationTriangleIcon class="h-5 w-5 text-red-400 flex-shrink-0" />
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Requiere Atención</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <p>Este recordatorio necesita que tomes alguna acción para actualizarlo.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de confirmación de eliminación -->
        <div v-if="showDeleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                        <ExclamationTriangleIcon class="h-6 w-6 text-red-600" />
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mt-2 text-center">
                        Eliminar Recordatorio
                    </h3>
                    <div class="mt-2 px-7 py-3 text-center">
                        <p class="text-sm text-gray-500 text-center">
                            ¿Estás seguro de que deseas eliminar el recordatorio <strong>"{{ recordatorio.titulo }}"</strong>?
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
    </AppLayout>
</template>

<script setup>
import { computed, ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import {
    CalendarIcon,
    ClockIcon,
    CogIcon,
    CheckCircleIcon,
    PencilIcon,
    TrashIcon,
    UserIcon,
    BeakerIcon,
    ArrowLeftIcon,
    BellIcon,
    CalendarDaysIcon,
    ExclamationTriangleIcon,
    EyeIcon
} from '@heroicons/vue/24/outline'
import { parseDateLocal, formatDateShort, formatDateLong } from '@/Utils/date';

const props = defineProps({
    recordatorio: Object,
    estadisticas: Object,
    recordatoriosRelacionados: Array,
});

// Estado para el modal de confirmación de eliminación
const showDeleteModal = ref(false);
const isDeleting = ref(false);

// Métodos
// Usar util compartido
// DEBUG TEMPORAL: exponer parse y valor original en template para depuración
// Usar util compartido
const formatearFecha = (fecha) => formatDateLong(fecha);
const formatearFechaCompleta = (fecha, hora) => formatDateLong(fecha, hora);

const formatearTipoRecordatorio = (tipo) => {
    const tipos = {
        'recordatorio_personal': 'Recordatorio Personal',
        'vacuna_proxima': 'Vacuna Próxima',
        'cita_programada': 'Cita Programada',
        'refuerzo_pendiente': 'Refuerzo Pendiente'
    };
    return tipos[tipo] || tipo;
};

const formatearEstado = (estado) => {
    const estados = {
        'programado': 'Programado',
        'es_hoy': 'Es hoy',
        'hecho': 'Hecho',
        'no_hice': 'No lo hice',
        'no_aplica': 'No aplica'
    };
    return estados[estado] || estado;
};

const getEstadoColors = (estado) => {
    const colores = {
        'programado': 'bg-blue-100 text-blue-800',
        'es_hoy': 'bg-orange-100 text-orange-800',
        'hecho': 'bg-green-100 text-green-800',
        'no_hice': 'bg-red-100 text-red-800',
        'no_aplica': 'bg-gray-100 text-gray-800'
    };
    return colores[estado] || 'bg-gray-100 text-gray-800';
};

const getEstadoDotColor = (estado) => {
    const colores = {
        'programado': 'bg-blue-400',
        'es_hoy': 'bg-orange-400',
        'hecho': 'bg-green-400',
        'no_hice': 'bg-red-400',
        'no_aplica': 'bg-gray-400'
    };
    return colores[estado] || 'bg-gray-400';
};

// Colores para el header con fondo oscuro
const getEstadoDotColorWhite = (estado) => {
    const colores = {
        'programado': 'bg-blue-200',
        'es_hoy': 'bg-orange-200',
        'hecho': 'bg-green-200',
        'no_hice': 'bg-red-200',
        'no_aplica': 'bg-gray-200'
    };
    return colores[estado] || 'bg-gray-200';
};

// Colores para los días restantes según urgencia
const getDiasRestantesColors = (estado, estadisticas) => {
    // Estados completados tienen colores especiales
    if (estado === 'hecho') {
        return 'bg-green-100 text-green-800 border-green-300';
    }
    if (estado === 'no_aplica') {
        return 'bg-gray-100 text-gray-800 border-gray-300';
    }

    // Para estados activos, usar lógica de urgencia
    if (estadisticas.es_vencido) {
        return 'bg-red-100 text-red-800 border-red-300';
    }
    if (estadisticas.es_hoy) {
        return 'bg-orange-100 text-orange-900 border-orange-300';
    }
    if (estadisticas.es_manana) {
        return 'bg-yellow-100 text-yellow-900 border-yellow-300';
    }
    if (estadisticas.es_urgente) {
        return 'bg-amber-100 text-amber-900 border-amber-300';
    }
    return 'bg-blue-100 text-blue-900 border-blue-300';
};

// Determinar si el recordatorio puede editarse
const puedeEditar = computed(() => {
    const estadosEditables = ['programado', 'es_hoy', 'no_hice'];
    return estadosEditables.includes(props.recordatorio.estado);
});

// Texto del estado para botón no editable
const getEstadoTexto = (estado) => {
    const textos = {
        'hecho': 'Completado',
        'no_aplica': 'No aplica'
    };
    return textos[estado] || formatearEstado(estado);
};

// Colores para texto de días restantes en header
const getDiasRestantesTextColor = (estado, estadisticas) => {
    // Estados completados tienen colores especiales
    if (estado === 'hecho') {
        return 'text-green-600';
    }
    if (estado === 'no_aplica') {
        return 'text-gray-600';
    }

    // Para estados activos, usar lógica de urgencia
    if (estadisticas.es_vencido) {
        return 'text-red-600';
    }
    if (estadisticas.es_hoy) {
        return 'text-orange-600';
    }
    if (estadisticas.es_manana) {
        return 'text-yellow-600';
    }
    if (estadisticas.es_urgente) {
        return 'text-amber-600';
    }
    return 'text-blue-600';
};

const marcarComoCompletado = () => {
    if (confirm('¿Marcar este recordatorio como "Ya lo hice"?')) {
        router.patch(route('paciente.recordatorios.marcar-completado', props.recordatorio.id), {}, {
            preserveScroll: true,
            onSuccess: () => {
                // Refrescar la página para mostrar el nuevo estado
                router.reload({ only: ['recordatorio', 'estadisticas'] });
            }
        });
    }
};

const eliminarRecordatorio = () => {
    showDeleteModal.value = true;
};

const confirmDelete = () => {
    isDeleting.value = true;
    router.delete(route('paciente.recordatorios.destroy', props.recordatorio.id), {
        onSuccess: () => {
            // Redirigir al índice después de eliminar
            router.visit(route('paciente.recordatorios.index'));
        },
        onError: () => {
            isDeleting.value = false;
        }
    });
};

const closeDeleteModal = () => {
    if (!isDeleting.value) {
        showDeleteModal.value = false;
    }
};
</script>
