<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { computed, ref } from 'vue';
import { usePage, Link, router } from '@inertiajs/vue3';
import {
    BellIcon,
    CalendarDaysIcon,
    ClockIcon,
    PlusIcon,
    UserGroupIcon,
    CheckCircleIcon,
    ExclamationTriangleIcon,
    EyeIcon,
    TrashIcon,
    PencilIcon,
    XMarkIcon,
    MagnifyingGlassIcon,
    FunnelIcon
} from '@heroicons/vue/24/outline';
import {
    BellIcon as BellIconSolid,
    CalendarDaysIcon as CalendarIconSolid,
    CheckCircleIcon as CheckIconSolid
} from '@heroicons/vue/24/solid';

const page = usePage();
const props = defineProps({
    recordatorios: Object,
    dependientes: Array,
    estadisticas: Object,
    proximosRecordatorios: Array,
    filtros: Object
});

// Estados locales
const mostrarModalNuevo = ref(false);
const mostrarModalEditar = ref(false);
const recordatorioSeleccionado = ref(null);
const mostrarModalDependientes = ref(false);
const showMobileFilters = ref(false);

// Estado para el modal de confirmación de eliminación
const showDeleteModal = ref(false);
const recordatorioToDelete = ref(null);
const isDeleting = ref(false);

// Formulario para nuevo recordatorio
const formularioNuevo = ref({
    dependiente_id: '',
    vacuna_id: '',
    tipo: 'recordatorio_personal',
    titulo: '',
    mensaje: '',
    fecha_recordatorio: '',
    hora_recordatorio: ''
});

// Filtros
const filtros = ref({
    busqueda: props.filtros.busqueda || '',
    tipo: props.filtros.tipo || '',
    estado: props.filtros.estado || 'todos'
});

// Computed
const user = computed(() => page.props.auth.user);

const hasFilters = computed(() => {
    return filtros.value.busqueda ||
           filtros.value.tipo ||
           (filtros.value.estado && filtros.value.estado !== 'todos');
});

const estadoColors = {
    'pendiente': 'bg-yellow-100 text-yellow-800 border-yellow-200',
    'enviado': 'bg-blue-100 text-blue-800 border-blue-200',
    'leido': 'bg-green-100 text-green-800 border-green-200'
};

const tipoIcons = {
    'vacuna_proxima': BellIconSolid,
    'cita_programada': CalendarIconSolid,
    'refuerzo_pendiente': ExclamationTriangleIcon,
    'recordatorio_personal': CheckIconSolid
};

// Métodos
const aplicarFiltros = () => {
    router.get(route('paciente.recordatorios.index'), {
        estado: filtroEstado.value,
        tipo: filtroTipo.value
    }, {
        preserveState: true,
        preserveScroll: true
    });
};

const abrirModalNuevo = () => {
    formularioNuevo.value = {
        dependiente_id: '',
        vacuna_id: '',
        tipo: 'recordatorio_personal',
        titulo: '',
        mensaje: '',
        fecha_recordatorio: '',
        hora_recordatorio: ''
    };
    mostrarModalNuevo.value = true;
};

const cerrarModales = () => {
    mostrarModalNuevo.value = false;
    mostrarModalEditar.value = false;
    mostrarModalDependientes.value = false;
    recordatorioSeleccionado.value = null;
};

const crearRecordatorio = () => {
    console.log('Función crearRecordatorio ejecutada');
    console.log('Datos del formulario:', formularioNuevo.value);

    // Validar que los campos requeridos estén llenos
    if (!formularioNuevo.value.tipo || !formularioNuevo.value.titulo || !formularioNuevo.value.fecha_recordatorio) {
        alert('Por favor, completa todos los campos requeridos.');
        return;
    }

    // Preparar datos para enviar
    const datos = {
        ...formularioNuevo.value,
        // Convertir string vacío a null para dependiente_id
        dependiente_id: formularioNuevo.value.dependiente_id === '' ? null : formularioNuevo.value.dependiente_id,
        // Asegurar que vacuna_id sea null si está vacío
        vacuna_id: formularioNuevo.value.vacuna_id || null,
        // Convertir string vacío a null para mensaje
        mensaje: formularioNuevo.value.mensaje === '' ? null : formularioNuevo.value.mensaje
    };

    console.log('Enviando datos:', datos); // Para debug

    router.post(route('paciente.recordatorios.store'), datos, {
        onSuccess: () => {
            // Resetear formulario
            formularioNuevo.value = {
                dependiente_id: '',
                vacuna_id: '',
                tipo: 'recordatorio_personal',
                titulo: '',
                mensaje: '',
                fecha_recordatorio: '',
                hora_recordatorio: ''
            };
            cerrarModales();
        },
        onError: (errors) => {
            console.error('Error al crear recordatorio:', errors);
            // Mostrar errores al usuario
            if (errors.message) {
                alert('Error: ' + errors.message);
            } else {
                alert('Error al crear el recordatorio. Revisa la consola para más detalles.');
            }
        }
    });
};

const marcarComoLeido = (recordatorio) => {
    router.patch(route('paciente.recordatorios.marcar-leido', recordatorio.id), {}, {
        preserveScroll: true
    });
};

const eliminarRecordatorio = (recordatorio) => {
    recordatorioToDelete.value = recordatorio;
    showDeleteModal.value = true;
};

const confirmDelete = () => {
    if (recordatorioToDelete.value) {
        isDeleting.value = true;
        router.delete(route('paciente.recordatorios.destroy', recordatorioToDelete.value.id), {
            preserveScroll: true,
            onSuccess: () => {
                isDeleting.value = false;
                showDeleteModal.value = false;
                recordatorioToDelete.value = null;
            },
            onError: () => {
                isDeleting.value = false;
            }
        });
    }
};

const closeDeleteModal = () => {
    if (!isDeleting.value) {
        showDeleteModal.value = false;
        recordatorioToDelete.value = null;
    }
};

const formatearFecha = (fecha) => {
    return new Date(fecha).toLocaleDateString('es-PE', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
};

const formatearFechaCompleta = (fecha, hora) => {
    const fechaObj = new Date(fecha + (hora ? 'T' + hora : ''));
    return fechaObj.toLocaleDateString('es-PE', {
        weekday: 'long',
        day: '2-digit',
        month: 'long',
        year: 'numeric'
    }) + (hora ? ` a las ${hora}` : '');
};

const esVencido = (fecha) => {
    return new Date(fecha) < new Date().setHours(0,0,0,0);
};

const esHoy = (fecha) => {
    const hoy = new Date().toDateString();
    return new Date(fecha).toDateString() === hoy;
};

// Métodos adicionales para la tabla
const formatearTipoRecordatorio = (tipo) => {
    const tipos = {
        'recordatorio_personal': 'Personal',
        'vacuna_proxima': 'Vacuna próxima',
        'cita_programada': 'Cita programada',
        'refuerzo_pendiente': 'Refuerzo pendiente'
    };
    return tipos[tipo] || tipo;
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

const verRecordatorio = (recordatorio) => {
    router.get(route('paciente.recordatorios.show', recordatorio.id));
};

const editarRecordatorio = (recordatorio) => {
    router.get(route('paciente.recordatorios.edit', recordatorio.id));
};

const marcarCompletado = (recordatorio) => {
    if (confirm('¿Marcar este recordatorio como "Ya lo hice"?')) {
        router.patch(route('paciente.recordatorios.marcar-completado', recordatorio.id), {}, {
            preserveScroll: true
        });
    }
};

const aplicarFiltrosTabla = () => {
    router.get(route('paciente.recordatorios.index'), filtros.value, {
        preserveState: true,
        preserveScroll: true
    });
};

const limpiarFiltros = () => {
    filtros.value = {
        busqueda: '',
        tipo: '',
        estado: 'todos'
    };
    aplicarFiltrosTabla();
};

// Método para calcular días restantes
const calcularDiasRestantes = (fecha) => {
    const hoy = new Date();
    const fechaRecordatorio = new Date(fecha);
    const diferencia = Math.ceil((fechaRecordatorio - hoy) / (1000 * 60 * 60 * 24));

    if (diferencia === 0) return 'Hoy';
    if (diferencia === 1) return 'Mañana';
    if (diferencia === -1) return 'Ayer';
    if (diferencia > 1) return `En ${diferencia} días`;
    if (diferencia < -1) return `Hace ${Math.abs(diferencia)} días`;

    return diferencia;
};

// Método para obtener la clase de urgencia por fecha
const getClaseUrgencia = (fecha) => {
    const dias = calcularDiasRestantes(fecha);
    if (dias === 'Hoy') return 'border-l-4 border-orange-400 bg-orange-50';
    if (dias === 'Mañana') return 'border-l-4 border-yellow-400 bg-yellow-50';
    return '';
};
</script>

<template>
    <AppLayout title="Recordatorios - Inmuno Alerta">
        <div class="min-h-screen bg-gray-50">
            <!-- Header -->
            <div class="bg-white shadow-sm border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8 py-4 sm:py-6">
                    <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
                        <!-- Logo y título -->
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <BellIcon class="h-7 w-7 sm:h-8 sm:w-8 text-cyan-600" />
                            </div>
                            <div>
                                <h1 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900">
                                    Mis recordatorios de Vacunación
                                </h1>
                                <p class="text-xs sm:text-sm text-gray-600 hidden sm:block">
                                    Gestiona tus recordatorios de vacunación y los de tu familia
                                </p>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-3">
                            <button
                                @click="mostrarModalDependientes = true"
                                class="inline-flex items-center justify-center px-3 sm:px-4 py-2 border border-cyan-300 text-cyan-700 bg-cyan-50 hover:bg-cyan-100 rounded-md text-sm font-medium transition-colors"
                            >
                                <UserGroupIcon class="h-4 w-4 mr-2" />
                                <span class="hidden xs:inline mr-1">Gestionar </span>dependientes
                            </button>
                            <button
                                @click="abrirModalNuevo"
                                class="inline-flex items-center justify-center px-3 sm:px-4 py-2 border border-transparent rounded-md shadow-sm bg-cyan-600 text-sm font-medium text-white hover:bg-cyan-700 focus:outline-none transition-colors"
                            >
                                <PlusIcon class="h-4 w-4 mr-2" />
                                <span class="hidden xs:inline mr-1">Nuevo </span>Recordatorio
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dashboard de Estadísticas - Móvil Optimizado -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
                <!-- Versión móvil compacta -->
                <div class="block md:hidden mb-3">
                    <!-- Resumen compacto superior -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-2">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-cyan-100 rounded-full flex items-center justify-center">
                                    <BellIcon class="w-4 h-4 text-cyan-600" />
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900">Estado de hoy</h3>
                                    <p class="text-xs text-gray-600">
                                        <span v-if="estadisticas.para_hoy > 0" class="text-orange-600 font-medium">
                                            {{ estadisticas.para_hoy }} pendiente{{ estadisticas.para_hoy > 1 ? 's' : '' }}
                                        </span>
                                        <span v-else class="text-green-600">✓ Al día</span>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <!-- Para hoy -->
                                <div v-if="estadisticas.para_hoy > 0" class="text-center">
                                    <div class="text-lg font-bold text-orange-600">{{ estadisticas.para_hoy }}</div>
                                    <div class="text-xs text-gray-500">Hoy</div>
                                </div>
                                <!-- Próximos -->
                                <div class="text-center">
                                    <div class="text-lg font-bold text-blue-600">{{ estadisticas.proximos || 0 }}</div>
                                    <div class="text-xs text-gray-500">Próximos</div>
                                </div>
                                <!-- Hechos -->
                                <div class="text-center">
                                    <div class="text-lg font-bold text-green-600">{{ estadisticas.completados || 0 }}</div>
                                    <div class="text-xs text-gray-500">Hechos</div>
                                </div>
                            </div>
                        </div>

                        <!-- Alertas importantes -->
                        <div v-if="estadisticas.pendientes_atencion > 0" class="mt-3 pt-3 border-t border-gray-100">
                            <div class="flex items-center justify-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <ExclamationTriangleIcon class="w-3 h-3 mr-1" />
                                    {{ estadisticas.pendientes_atencion }} requieren tu atención
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Versión tablet compacta (2x2) -->
                <div class="hidden md:block lg:hidden mb-3">
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <!-- Para Hoy (Urgente) -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-5 h-5 bg-orange-100 rounded-lg flex items-center justify-center">
                                        <ClockIcon class="w-3 h-3 text-orange-600" />
                                    </div>
                                </div>
                                <div class="ml-2">
                                    <p class="text-xs font-medium text-gray-500">Para Hoy</p>
                                    <p class="text-lg font-bold text-orange-600">{{ estadisticas.para_hoy || 0 }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Completados (Hechos) -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-5 h-5 bg-green-100 rounded-lg flex items-center justify-center">
                                        <CheckCircleIcon class="w-3 h-3 text-green-600" />
                                    </div>
                                </div>
                                <div class="ml-2">
                                    <p class="text-xs font-medium text-gray-500">Hechos</p>
                                    <p class="text-lg font-bold text-green-600">{{ estadisticas.completados || 0 }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Próximos -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-5 h-5 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <CalendarDaysIcon class="w-3 h-3 text-blue-600" />
                                    </div>
                                </div>
                                <div class="ml-2">
                                    <p class="text-xs font-medium text-gray-500">Próximos</p>
                                    <p class="text-lg font-bold text-blue-600">{{ estadisticas.proximos || 0 }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Requieren Atención -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-5 h-5 bg-red-100 rounded-lg flex items-center justify-center">
                                        <ExclamationTriangleIcon class="w-3 h-3 text-red-600" />
                                    </div>
                                </div>
                                <div class="ml-2">
                                    <p class="text-xs font-medium text-gray-500">Requieren Atención</p>
                                    <p class="text-lg font-bold text-red-600">{{ estadisticas.pendientes_atencion || 0 }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Resumen tablet -->
                    <div v-if="estadisticas.pendientes_atencion > 0" class="bg-red-50 border border-red-200 rounded-lg p-3 mb-2">
                        <div class="flex items-center justify-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                <ExclamationTriangleIcon class="w-4 h-4 mr-1" />
                                {{ estadisticas.pendientes_atencion }} recordatorios requieren tu atención
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Versión desktop completa -->
                <div class="hidden lg:block">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 mb-3">
                        <!-- Para Hoy (Urgente) -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-6 h-6 bg-orange-100 rounded-lg flex items-center justify-center">
                                        <ClockIcon class="w-4 h-4 text-orange-600" />
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-xs font-medium text-gray-500">Para Hoy</p>
                                    <p class="text-xl font-bold text-orange-600">{{ estadisticas.para_hoy || 0 }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Completados (Hechos) -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-6 h-6 bg-green-100 rounded-lg flex items-center justify-center">
                                        <CheckCircleIcon class="w-4 h-4 text-green-600" />
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-xs font-medium text-gray-500">Hechos</p>
                                    <p class="text-xl font-bold text-green-600">{{ estadisticas.completados || 0 }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Próximos -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-6 h-6 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <CalendarDaysIcon class="w-4 h-4 text-blue-600" />
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-xs font-medium text-gray-500">Próximos</p>
                                    <p class="text-xl font-bold text-blue-600">{{ estadisticas.proximos || 0 }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Requieren Atención -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-6 h-6 bg-red-100 rounded-lg flex items-center justify-center">
                                        <ExclamationTriangleIcon class="w-4 h-4 text-red-600" />
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-xs font-medium text-gray-500">Requieren Atención</p>
                                    <p class="text-xl font-bold text-red-600">{{ estadisticas.pendientes_atencion || 0 }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Resumen Rápido de Estado - Solo Desktop -->
                    <div class="bg-gradient-to-r from-cyan-50 to-blue-50 rounded-lg p-4 mb-3 border border-cyan-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-cyan-100 rounded-full flex items-center justify-center">
                                        <BellIcon class="w-5 h-5 text-cyan-600" />
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900">Estado general</h3>
                                    <p class="text-xs text-gray-600">
                                        <span v-if="estadisticas.para_hoy > 0" class="text-orange-600 font-medium">
                                            {{ estadisticas.para_hoy }} para hoy
                                        </span>
                                        <span v-else class="text-green-600">
                                            ✓ Al día
                                        </span>
                                        •
                                        <span class="text-blue-600">{{ estadisticas.esta_semana || 0 }} esta semana</span>
                                        <span v-if="estadisticas.vencidos && estadisticas.vencidos > 0" class="text-red-600">
                                            • {{ estadisticas.vencidos }} vencido{{ estadisticas.vencidos > 1 ? 's' : '' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div v-if="estadisticas.pendientes_atencion > 0" class="mb-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <ExclamationTriangleIcon class="w-3 h-3 mr-1" />
                                        {{ estadisticas.pendientes_atencion }} requieren atención
                                    </span>
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ estadisticas.completados || 0 }} completados de {{ estadisticas.total_recordatorios || 0 }} total
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Próximos Recordatorios -->
                <div v-if="proximosRecordatorios && proximosRecordatorios.length > 0" class="bg-white rounded-lg shadow-sm border border-gray-200 mb-2">
                    <div class="px-4 py-3 border-b border-gray-200">
                        <h3 class="text-base font-medium text-gray-900 flex items-center">
                            <CalendarDaysIcon class="w-4 h-4 mr-2 text-cyan-600" />
                            Próximos Recordatorios (7 días)
                        </h3>
                    </div>
                    <div class="p-3">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            <div v-for="recordatorio in proximosRecordatorios" :key="recordatorio.id"
                                 class="bg-gray-50 rounded-lg p-3 border border-gray-100 hover:bg-gray-100 transition-colors">
                                <div class="flex items-start justify-between mb-1">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-5 w-5">
                                            <div class="h-5 w-5 rounded-full flex items-center justify-center"
                                                 :class="{
                                                     'bg-blue-100 text-blue-600': recordatorio.tipo === 'recordatorio_personal',
                                                     'bg-green-100 text-green-600': recordatorio.tipo === 'vacuna_proxima',
                                                     'bg-purple-100 text-purple-600': recordatorio.tipo === 'cita_programada',
                                                     'bg-orange-100 text-orange-600': recordatorio.tipo === 'refuerzo_pendiente'
                                                 }">
                                                <BellIcon v-if="recordatorio.tipo === 'recordatorio_personal'" class="h-3 w-3" />
                                                <CalendarDaysIcon v-else-if="recordatorio.tipo === 'vacuna_proxima'" class="h-3 w-3" />
                                                <ClockIcon v-else-if="recordatorio.tipo === 'cita_programada'" class="h-3 w-3" />
                                                <ExclamationTriangleIcon v-else class="h-3 w-3" />
                                            </div>
                                        </div>
                                        <span class="ml-2 text-xs font-medium text-gray-600">
                                            {{ formatearTipoRecordatorio(recordatorio.tipo) }}
                                        </span>
                                    </div>
                                    <span class="text-xs px-2 py-0.5 bg-yellow-100 text-yellow-800 rounded-full">
                                        {{ calcularDiasRestantes(recordatorio.fecha_recordatorio) }}
                                    </span>
                                </div>
                                <h4 class="text-sm font-medium text-gray-900 mb-1">{{ recordatorio.titulo }}</h4>
                                <p class="text-xs text-gray-600 mb-2">{{ formatearFecha(recordatorio.fecha_recordatorio) }}</p>
                                <div v-if="recordatorio.dependiente" class="flex items-center text-xs text-purple-600 mb-2">
                                    <UserGroupIcon class="w-3 h-3 mr-1" />
                                    Para: {{ recordatorio.dependiente.nombres }} {{ recordatorio.dependiente.apellidos }}
                                </div>
                                <div class="flex justify-end">
                                    <button @click="verRecordatorio(recordatorio)"
                                            class="text-xs text-cyan-600 hover:text-cyan-800 font-medium">
                                        Ver detalles →
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido principal -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2">
                <!-- Filtros -->
                <div class="mb-4 sm:mb-6">
                    <!-- Filtros para Desktop -->
                    <div class="hidden md:block bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                            <!-- Campo de búsqueda -->
                            <div class="lg:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Buscar
                                </label>
                                <div class="relative">
                                    <input
                                        v-model="filtros.busqueda"
                                        @keyup.enter="aplicarFiltrosTabla"
                                        type="text"
                                        placeholder="Buscar recordatorios..."
                                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none text-sm"
                                    />
                                    <MagnifyingGlassIcon class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" />
                                </div>
                            </div>

                            <!-- Filtro por tipo -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tipo
                                </label>
                                <select
                                    v-model="filtros.tipo"
                                    @change="aplicarFiltrosTabla"
                                    class="w-full py-2 px-3 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none text-sm"
                                >
                                    <option value="">Todos los tipos</option>
                                    <option value="recordatorio_personal">Personal</option>
                                    <option value="vacuna_proxima">Vacuna próxima</option>
                                    <option value="cita_programada">Cita programada</option>
                                    <option value="refuerzo_pendiente">Refuerzo pendiente</option>
                                </select>
                            </div>

                            <!-- Filtro por estado -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Estado
                                </label>
                                <select
                                    v-model="filtros.estado"
                                    @change="aplicarFiltrosTabla"
                                    class="w-full py-2 px-3 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none text-sm"
                                >
                                    <option value="todos">Todos</option>
                                    <option value="programado">📅 Programados</option>
                                    <option value="es_hoy">⏰ Para hoy</option>
                                    <option value="hecho">✅ Ya hechos</option>
                                    <option value="no_hice">❌ No hechos</option>
                                    <option value="no_aplica">🚫 Ya no aplican</option>
                                </select>
                            </div>

                            <!-- Botones -->
                            <div class="flex items-end space-x-2">
                                <button
                                    @click="aplicarFiltrosTabla"
                                    class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm bg-cyan-600 text-sm font-medium text-white hover:bg-cyan-700 focus:outline-none transition-colors"
                                >
                                    <FunnelIcon class="h-4 w-4 mr-2" />
                                    Filtrar
                                </button>
                                <button
                                    v-if="hasFilters"
                                    @click="limpiarFiltros"
                                    class="inline-flex items-center justify-center px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none transition-colors"
                                    title="Limpiar filtros"
                                >
                                    <XMarkIcon class="h-4 w-4" />
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Filtros para Móvil -->
                    <div class="md:hidden bg-white rounded-lg shadow-sm border border-gray-200 p-3 sm:p-4">
                        <!-- Botón toggle y título -->
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-sm font-medium text-gray-900">Filtros</h3>
                            <button
                                @click="showMobileFilters = !showMobileFilters"
                                class="inline-flex items-center text-sm text-cyan-600 hover:text-cyan-800 transition-colors"
                            >
                                <FunnelIcon class="h-4 w-4 mr-1" />
                                {{ showMobileFilters ? 'Ocultar' : 'Mostrar' }}
                            </button>
                        </div>

                        <!-- Búsqueda principal (siempre visible en móvil) -->
                        <div class="relative mb-3">
                            <input
                                v-model="filtros.busqueda"
                                @keyup.enter="aplicarFiltrosTabla"
                                type="text"
                                placeholder="Buscar recordatorios..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none text-sm"
                            />
                            <MagnifyingGlassIcon class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" />
                        </div>

                        <!-- Filtros adicionales (colapsables) -->
                        <div v-show="showMobileFilters" class="space-y-3">
                            <!-- Tipo móvil -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tipo
                                </label>
                                <select
                                    v-model="filtros.tipo"
                                    @change="aplicarFiltrosTabla"
                                    class="w-full py-2 px-3 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none text-sm"
                                >
                                    <option value="">Todos los tipos</option>
                                    <option value="recordatorio_personal">Personal</option>
                                    <option value="vacuna_proxima">Vacuna próxima</option>
                                    <option value="cita_programada">Cita programada</option>
                                    <option value="refuerzo_pendiente">Refuerzo pendiente</option>
                                </select>
                            </div>

                            <!-- Estado móvil -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Estado
                                </label>
                                <select
                                    v-model="filtros.estado"
                                    @change="aplicarFiltrosTabla"
                                    class="w-full py-2 px-3 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none text-sm"
                                >
                                    <option value="todos">Todos</option>
                                    <option value="programado">📅 Programados</option>
                                    <option value="es_hoy">⏰ Para hoy</option>
                                    <option value="hecho">✅ Ya hechos</option>
                                    <option value="no_hice">❌ No hechos</option>
                                    <option value="no_aplica">🚫 Ya no aplican</option>
                                </select>
                            </div>

                            <!-- Botones móvil -->
                            <div class="flex space-x-2 pt-2">
                                <button
                                    @click="aplicarFiltrosTabla"
                                    class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm bg-cyan-600 text-sm font-medium text-white hover:bg-cyan-700 focus:outline-none transition-colors"
                                >
                                    <FunnelIcon class="h-4 w-4 mr-2" />
                                    Filtrar
                                </button>
                                <button
                                    v-if="hasFilters"
                                    @click="limpiarFiltros"
                                    class="inline-flex items-center justify-center px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none transition-colors"
                                    title="Limpiar filtros"
                                >
                                    <XMarkIcon class="h-4 w-4" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla de recordatorios -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <!-- Vista desktop -->
                    <div class="hidden md:block">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tipo
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Recordatorio
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Para quién
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Fecha
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Estado
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="recordatorio in recordatorios.data" :key="recordatorio.id"
                                    :class="`hover:bg-gray-50 transition-colors duration-150 ${getClaseUrgencia(recordatorio.fecha_recordatorio)}`">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8">
                                                <div class="h-8 w-8 rounded-full flex items-center justify-center"
                                                     :class="{
                                                         'bg-blue-100 text-blue-600': recordatorio.tipo === 'recordatorio_personal',
                                                         'bg-green-100 text-green-600': recordatorio.tipo === 'vacuna_proxima',
                                                         'bg-purple-100 text-purple-600': recordatorio.tipo === 'cita_programada',
                                                         'bg-orange-100 text-orange-600': recordatorio.tipo === 'refuerzo_pendiente'
                                                     }">
                                                    <BellIcon v-if="recordatorio.tipo === 'recordatorio_personal'" class="h-4 w-4" />
                                                    <CalendarDaysIcon v-else-if="recordatorio.tipo === 'vacuna_proxima'" class="h-4 w-4" />
                                                    <ClockIcon v-else-if="recordatorio.tipo === 'cita_programada'" class="h-4 w-4" />
                                                    <ExclamationTriangleIcon v-else class="h-4 w-4" />
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ formatearTipoRecordatorio(recordatorio.tipo) }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="max-w-xs">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ recordatorio.titulo }}</p>
                                            <p v-if="recordatorio.mensaje" class="text-sm text-gray-500 truncate mt-1">
                                                {{ recordatorio.mensaje }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div v-if="recordatorio.dependiente" class="flex items-center text-sm">
                                                <div class="flex-shrink-0 h-6 w-6">
                                                    <div class="h-6 w-6 bg-purple-100 rounded-full flex items-center justify-center">
                                                        <UserGroupIcon class="h-3 w-3 text-purple-600" />
                                                    </div>
                                                </div>
                                                <div class="ml-2">
                                                    <p class="text-sm font-medium text-gray-900">{{ recordatorio.dependiente.nombres }} {{ recordatorio.dependiente.apellidos }}</p>
                                                    <p class="text-xs text-gray-500">{{ recordatorio.dependiente.parentesco }}</p>
                                                </div>
                                            </div>
                                            <div v-else class="flex items-center text-sm">
                                                <div class="flex-shrink-0 h-6 w-6">
                                                    <div class="h-6 w-6 bg-cyan-100 rounded-full flex items-center justify-center">
                                                        <UserGroupIcon class="h-3 w-3 text-cyan-600" />
                                                    </div>
                                                </div>
                                                <div class="ml-2">
                                                    <p class="text-sm font-medium text-gray-900">Para mí</p>
                                                    <p class="text-xs text-gray-500">Personal</p>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ formatearFecha(recordatorio.fecha_recordatorio) }}
                                        </div>
                                        <div v-if="recordatorio.hora_recordatorio" class="text-sm text-gray-500">
                                            {{ recordatorio.hora_recordatorio }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                                              :class="getEstadoColors(recordatorio.estado)">
                                            {{ recordatorio.estado.charAt(0).toUpperCase() + recordatorio.estado.slice(1) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-3">
                                            <button @click="verRecordatorio(recordatorio)"
                                                    class="text-cyan-600 hover:text-cyan-900 transition-colors duration-150"
                                                    title="Ver detalles">
                                                <EyeIcon class="h-4 w-4" />
                                            </button>
                                            <button v-if="['programado', 'es_hoy'].includes(recordatorio.estado)"
                                                    @click="editarRecordatorio(recordatorio)"
                                                    class="text-indigo-600 hover:text-indigo-900 transition-colors duration-150"
                                                    title="Editar">
                                                <PencilIcon class="h-4 w-4" />
                                            </button>
                                            <button v-if="['programado', 'es_hoy'].includes(recordatorio.estado)"
                                                    @click="marcarCompletado(recordatorio)"
                                                    class="text-green-600 hover:text-green-900 transition-colors duration-150"
                                                    title="Marcar como hecho">
                                                <CheckCircleIcon class="h-4 w-4" />
                                            </button>
                                            <button @click="eliminarRecordatorio(recordatorio)"
                                                    class="text-red-600 hover:text-red-900 transition-colors duration-150"
                                                    title="Eliminar">
                                                <TrashIcon class="h-4 w-4" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Estado vacío -->
                                <tr v-if="recordatorios.data.length === 0">
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <BellIcon class="h-12 w-12 text-gray-400 mb-4" />
                                            <h3 class="text-sm font-medium text-gray-900 mb-2">No hay recordatorios</h3>
                                            <p class="text-sm text-gray-500 mb-4">Comienza creando tu primer recordatorio</p>
                                            <button @click="mostrarModalNuevo = true"
                                                    class="inline-flex items-center px-4 py-2 bg-cyan-600 text-white text-sm font-medium rounded-lg hover:bg-cyan-700 transition-colors duration-150">
                                                <PlusIcon class="h-4 w-4 mr-2" />
                                                Crear recordatorio
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Vista móvil -->
                    <div class="md:hidden">
                        <div class="divide-y divide-gray-200">
                            <div v-for="recordatorio in recordatorios.data" :key="recordatorio.id"
                                 :class="`p-4 hover:bg-gray-50 transition-colors duration-150 ${getClaseUrgencia(recordatorio.fecha_recordatorio)}`">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-start space-x-3 flex-1">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div class="h-8 w-8 rounded-full flex items-center justify-center"
                                                 :class="{
                                                     'bg-blue-100 text-blue-600': recordatorio.tipo === 'recordatorio_personal',
                                                     'bg-green-100 text-green-600': recordatorio.tipo === 'vacuna_proxima',
                                                     'bg-purple-100 text-purple-600': recordatorio.tipo === 'cita_programada',
                                                     'bg-orange-100 text-orange-600': recordatorio.tipo === 'refuerzo_pendiente'
                                                 }">
                                                <BellIcon v-if="recordatorio.tipo === 'recordatorio_personal'" class="h-4 w-4" />
                                                <CalendarDaysIcon v-else-if="recordatorio.tipo === 'vacuna_proxima'" class="h-4 w-4" />
                                                <ClockIcon v-else-if="recordatorio.tipo === 'cita_programada'" class="h-4 w-4" />
                                                <ExclamationTriangleIcon v-else class="h-4 w-4" />
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between mb-1">
                                                <p class="text-sm font-medium text-gray-900 truncate">{{ recordatorio.titulo }}</p>
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ml-2"
                                                      :class="getEstadoColors(recordatorio.estado)">
                                                    {{ recordatorio.estado.charAt(0).toUpperCase() + recordatorio.estado.slice(1) }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-500 mb-2">{{ formatearTipoRecordatorio(recordatorio.tipo) }}</p>
                                            <p v-if="recordatorio.mensaje" class="text-sm text-gray-600 mb-2 line-clamp-2">
                                                {{ recordatorio.mensaje }}
                                            </p>
                                            <div class="flex items-center justify-between mb-2">
                                                <div class="flex items-center text-sm text-gray-500">
                                                    <CalendarDaysIcon class="h-4 w-4 mr-1" />
                                                    {{ formatearFecha(recordatorio.fecha_recordatorio) }}
                                                    <span v-if="recordatorio.hora_recordatorio" class="ml-2">
                                                        {{ recordatorio.hora_recordatorio }}
                                                    </span>
                                                </div>
                                                <div v-if="recordatorio.dependiente" class="flex items-center text-xs text-purple-600">
                                                    <UserGroupIcon class="w-3 h-3 mr-1" />
                                                    {{ recordatorio.dependiente.nombres }}
                                                </div>
                                                <div v-else class="flex items-center text-xs text-cyan-600">
                                                    <UserGroupIcon class="w-3 h-3 mr-1" />
                                                    Para mí
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 flex items-center justify-end space-x-3">
                                    <button @click="verRecordatorio(recordatorio)"
                                            class="p-2 text-cyan-600 hover:bg-cyan-50 rounded-lg transition-colors duration-150"
                                            title="Ver detalles">
                                        <EyeIcon class="h-4 w-4" />
                                    </button>
                                    <button v-if="['programado', 'es_hoy'].includes(recordatorio.estado)"
                                            @click="editarRecordatorio(recordatorio)"
                                            class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors duration-150"
                                            title="Editar">
                                        <PencilIcon class="h-4 w-4" />
                                    </button>
                                    <button v-if="['programado', 'es_hoy'].includes(recordatorio.estado)"
                                            @click="marcarCompletado(recordatorio)"
                                            class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors duration-150"
                                            title="Marcar como hecho">
                                        <CheckCircleIcon class="h-4 w-4" />
                                    </button>
                                    <button @click="eliminarRecordatorio(recordatorio)"
                                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-150"
                                            title="Eliminar">
                                        <TrashIcon class="h-4 w-4" />
                                    </button>
                                </div>
                            </div>
                            <!-- Estado vacío móvil -->
                            <div v-if="recordatorios.data.length === 0" class="p-8 text-center">
                                <BellIcon class="h-12 w-12 text-gray-400 mx-auto mb-4" />
                                <h3 class="text-sm font-medium text-gray-900 mb-2">No hay recordatorios</h3>
                                <p class="text-sm text-gray-500 mb-4">Comienza creando tu primer recordatorio</p>
                                <button @click="mostrarModalNuevo = true"
                                        class="inline-flex items-center px-4 py-2 bg-cyan-600 text-white text-sm font-medium rounded-lg hover:bg-cyan-700 transition-colors duration-150">
                                    <PlusIcon class="h-4 w-4 mr-2" />
                                    Crear recordatorio
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Paginación -->
                <div v-if="recordatorios.data.length > 0 && (recordatorios.prev_page_url || recordatorios.next_page_url)"
                     class="mt-6 flex items-center justify-between">
                    <div class="text-sm text-gray-500">
                        Mostrando {{ recordatorios.from }} a {{ recordatorios.to }} de {{ recordatorios.total }} resultados
                    </div>
                    <div class="flex items-center space-x-2">
                        <Link v-if="recordatorios.prev_page_url"
                              :href="recordatorios.prev_page_url"
                              class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-700 transition-colors duration-150">
                            Anterior
                        </Link>
                        <Link v-if="recordatorios.next_page_url"
                              :href="recordatorios.next_page_url"
                              class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-700 transition-colors duration-150">
                            Siguiente
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Nuevo Recordatorio -->
        <div v-if="mostrarModalNuevo" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Nuevo recordatorio</h3>
                    <button @click="cerrarModales" class="text-gray-400 hover:text-gray-600">
                        <XMarkIcon class="w-6 h-6" />
                    </button>
                </div>
                <form @submit.prevent="crearRecordatorio" class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 min-h-[400px]">
                        <!-- Columna Izquierda - Destinatario -->
                        <div class="flex flex-col h-full">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    <UserGroupIcon class="w-4 h-4 inline mr-2 text-cyan-600" />
                                    ¿Para quién es este recordatorio?
                                </label>
                                <div class="space-y-3">
                                    <!-- Opción para mí mismo -->
                                    <div class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50"
                                         :class="{ 'border-cyan-500 bg-cyan-50': !formularioNuevo.dependiente_id }"
                                         @click="formularioNuevo.dependiente_id = ''">
                                        <input type="radio"
                                               :checked="!formularioNuevo.dependiente_id"
                                               @change="formularioNuevo.dependiente_id = ''"
                                               class="h-4 w-4 text-cyan-600 focus:ring-cyan-500 border-gray-300">
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">Para mí</div>
                                            <div class="text-xs text-gray-500">{{ user.name }}</div>
                                        </div>
                                    </div>

                                    <!-- Opciones para dependientes -->
                                    <template v-if="dependientes.length > 0">
                                        <div v-for="dependiente in dependientes"
                                             :key="dependiente.id"
                                             class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50"
                                             :class="{ 'border-cyan-500 bg-cyan-50': formularioNuevo.dependiente_id == dependiente.id }"
                                             @click="formularioNuevo.dependiente_id = dependiente.id">
                                            <input type="radio"
                                                   :checked="formularioNuevo.dependiente_id == dependiente.id"
                                                   @change="formularioNuevo.dependiente_id = dependiente.id"
                                                   class="h-4 w-4 text-cyan-600 focus:ring-cyan-500 border-gray-300">
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">{{ dependiente.nombres }} {{ dependiente.apellidos }}</div>
                                                <div class="text-xs text-gray-500">{{ dependiente.parentesco }} - {{ dependiente.edad }} años</div>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- Mensaje si no hay dependientes -->
                                    <div v-if="dependientes.length === 0" class="text-center py-4">
                                        <p class="text-sm text-gray-500 mb-3">No tienes dependientes registrados</p>
                                        <Link href="/paciente/dependientes"
                                              class="inline-flex items-center text-sm text-cyan-600 hover:text-cyan-700 font-medium">
                                            <PlusIcon class="w-4 h-4 mr-1" />
                                            Agregar dependiente
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Columna Derecha - Formulario -->
                        <div class="flex flex-col h-full space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tipo de recordatorio <span class="text-red-500">*</span>
                                </label>
                                <select v-model="formularioNuevo.tipo" required
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
                                    <option value="recordatorio_personal">Recordatorio personal</option>
                                    <option value="vacuna_proxima">Próxima vacuna</option>
                                    <option value="refuerzo_pendiente">Refuerzo pendiente</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Título <span class="text-red-500">*</span>
                                </label>
                                <input v-model="formularioNuevo.titulo" type="text" required
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500"
                                       placeholder="Título del recordatorio">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Descripción
                                </label>
                                <textarea v-model="formularioNuevo.mensaje" rows="3"
                                          class="w-full rounded-lg border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500"
                                          placeholder="Información adicional sobre el recordatorio"></textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Fecha <span class="text-red-500">*</span>
                                    </label>
                                    <input v-model="formularioNuevo.fecha_recordatorio" type="date" required
                                           :min="new Date().toISOString().split('T')[0]"
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Hora (opcional)</label>
                                    <input v-model="formularioNuevo.hora_recordatorio" type="time"
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 mt-6">
                        <button type="button" @click="cerrarModales"
                                class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">
                            Cancelar
                        </button>
                        <button type="submit"
                                class="px-6 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 font-medium">
                            Crear recordatorio
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Gestión de Dependientes -->
        <div v-if="mostrarModalDependientes" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Gestión de dependientes</h3>
                    <button @click="cerrarModales" class="text-gray-400 hover:text-gray-600">
                        <XMarkIcon class="w-6 h-6" />
                    </button>
                </div>
                <div class="p-6">
                    <div class="text-center mb-6">
                        <Link :href="route('paciente.dependientes.index')"
                              class="inline-flex items-center px-4 py-2 bg-cyan-600 text-white hover:bg-cyan-700 rounded-lg text-sm font-medium">
                            <PlusIcon class="w-4 h-4 mr-2" />
                            Gestionar dependientes
                        </Link>
                    </div>

                    <div v-if="dependientes.length === 0" class="text-center py-8">
                        <UserGroupIcon class="mx-auto h-12 w-12 text-gray-400" />
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay dependientes registrados</h3>
                        <p class="mt-1 text-sm text-gray-500">Registra a tus hijos o personas a tu cargo para gestionar sus recordatorios de vacunación.</p>
                    </div>

                    <div v-else class="space-y-3">
                        <div v-for="dependiente in dependientes" :key="dependiente.id"
                             class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                            <div>
                                <h4 class="font-medium text-gray-900">{{ dependiente.nombre_completo }}</h4>
                                <p class="text-sm text-gray-500">
                                    {{ dependiente.parentesco }} - {{ dependiente.edad }} años
                                </p>
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
                            ¿Estás seguro de que deseas eliminar el recordatorio <strong>"{{ recordatorioToDelete?.titulo }}"</strong>?
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

<style scoped>
.line-clamp-2 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 2;
    line-clamp: 2;
}

.transition-colors {
    transition-property: color, background-color, border-color;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}

/* Optimizaciones móviles adicionales */
@media (max-width: 640px) {
    .mobile-stats-compact {
        max-height: 120px;
        overflow: hidden;
    }

    .mobile-stat-number {
        font-size: 1.125rem;
        line-height: 1.3;
    }
}

/* Optimización para tablets */
@media (min-width: 641px) and (max-width: 1023px) {
    .tablet-stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
    }
}
</style>
