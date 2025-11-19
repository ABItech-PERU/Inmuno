<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { computed } from 'vue';
import { ArrowLeftIcon, PencilIcon, ClockIcon, UserIcon, CalendarIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    recordatorio: Object,
});

const formatDate = (date) => {
    if (!date) return '-';
    return new Date(date).toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

const formatTime = (time) => {
    if (!time) return '-';
    return time.substring(0, 5);
};

const formatDateTime = (dateTime) => {
    if (!dateTime) return '-';
    return new Date(dateTime).toLocaleString('es-ES', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

// Obtener nombre del usuario
const getUserName = (recordatorio) => {
    if (recordatorio.usuario) {
        return `${recordatorio.usuario.name} ${recordatorio.usuario.apellidos || ''}`.trim();
    }
    return 'Usuario no encontrado';
};

const getStatusBadge = (estado) => {
    const statusMap = {
        'pendiente': 'bg-yellow-100 text-yellow-800',
        'enviado': 'bg-blue-100 text-blue-800',
        'leido': 'bg-green-100 text-green-800',
        'programado': 'bg-purple-100 text-purple-800'
    };
    return statusMap[estado] || 'bg-gray-100 text-gray-800';
};

const getTypeBadge = (tipo) => {
    const typeMap = {
        'vacuna_proxima': 'bg-cyan-100 text-cyan-800',
        'cita_programada': 'bg-purple-100 text-purple-800',
        'refuerzo_pendiente': 'bg-orange-100 text-orange-800',
        'recordatorio_personal': 'bg-indigo-100 text-indigo-800'
    };
    return typeMap[tipo] || 'bg-gray-100 text-gray-800';
};

const getTypeText = (tipo) => {
    const typeTexts = {
        'vacuna_proxima': 'Vacuna Próxima',
        'cita_programada': 'Cita Programada',
        'refuerzo_pendiente': 'Refuerzo Pendiente',
        'recordatorio_personal': 'Recordatorio Personal'
    };
    return typeTexts[tipo] || tipo;
};

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

const getMethodText = (metodo) => {
    const methods = {
        'sistema': 'Sistema',
        'email': 'Email',
        'sms': 'SMS'
    };
    return methods[metodo] || metodo;
};

// Obtener descripción del recordatorio
const getDescription = (recordatorio) => {
    if (recordatorio.titulo) {
        return recordatorio.titulo;
    }
    return getTypeText(recordatorio.tipo);
};

const goBack = () => {
    router.visit('/admin/recordatorios');
};
</script>

<template>
    <AppLayout title="Detalles del Recordatorio">
        <Head title="Detalles del Recordatorio" />

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

                        <div class="flex items-center space-x-3">
                            <Link
                                :href="`/admin/recordatorios/${recordatorio.id}/edit`"
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
                                <ClockIcon class="h-12 w-12 text-cyan-600" />
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">{{ recordatorio.titulo }}</h1>
                                <div class="flex items-center space-x-4 mt-2">
                                    <span
                                        :class="getTypeBadge(recordatorio.tipo)"
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                    >
                                        {{ getTypeText(recordatorio.tipo) }}
                                    </span>
                                    <span
                                        :class="getStatusBadge(recordatorio.estado)"
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                    >
                                        {{ getStatusText(recordatorio.estado) }}
                                    </span>
                                    <span class="text-sm text-gray-500">
                                        #{{ recordatorio.id }} • {{ formatDate(recordatorio.fecha_recordatorio) }} a las {{ formatTime(recordatorio.hora_recordatorio) }}
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
                    <!-- Información del Recordatorio -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Información del Recordatorio</h3>
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tipo</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ getTypeText(recordatorio.tipo) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Estado</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ getStatusText(recordatorio.estado) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Fecha del recordatorio</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ formatDate(recordatorio.fecha_recordatorio) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Hora del recordatorio</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ formatTime(recordatorio.hora_recordatorio) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Método de envío</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ getMethodText(recordatorio.metodo_envio) }}</dd>
                            </div>
                        </dl>

                        <!-- Mensaje -->
                        <div class="mt-6">
                            <dt class="text-sm font-medium text-gray-500 mb-2">Mensaje</dt>
                            <dd class="mt-1 text-sm text-gray-900 bg-gray-50 rounded-md p-4 border">
                                <p class="whitespace-pre-wrap">{{ recordatorio.mensaje }}</p>
                            </dd>
                        </div>
                    </div>

                    <!-- Usuario -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Usuario</h3>
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-cyan-100 flex items-center justify-center">
                                    <span class="text-sm font-medium text-cyan-700">
                                        {{ recordatorio.usuario ? recordatorio.usuario.name.charAt(0).toUpperCase() : '?' }}
                                    </span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">{{ getUserName(recordatorio) }}</p>
                                <p class="text-sm text-gray-500">{{ recordatorio.usuario?.email }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Dependiente (si aplica) -->
                    <div v-if="recordatorio.dependiente" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Dependiente</h3>
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
                                    <span class="text-sm font-medium text-purple-700">
                                        {{ recordatorio.dependiente.nombres ? recordatorio.dependiente.nombres.charAt(0).toUpperCase() : '?' }}
                                    </span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ recordatorio.dependiente.nombres }} {{ recordatorio.dependiente.apellidos }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ recordatorio.dependiente.fecha_nacimiento ? `Nacido el ${formatDate(recordatorio.dependiente.fecha_nacimiento)}` : 'Fecha de nacimiento no especificada' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Vacuna (si aplica) -->
                    <div v-if="recordatorio.vacuna" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Vacuna Relacionada</h3>
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                    <span class="text-sm font-medium text-green-700">💉</span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">{{ recordatorio.vacuna.nombre }}</p>
                                <p class="text-sm text-gray-500">Vacuna</p>
                            </div>
                        </div>
                    </div>

                    <!-- Cita (si aplica) -->
                    <div v-if="recordatorio.cita" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Cita Relacionada</h3>
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <span class="text-sm font-medium text-blue-700">📅</span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">{{ recordatorio.cita.titulo || recordatorio.cita.motivo }}</p>
                                <p class="text-sm text-gray-500">
                                    {{ recordatorio.cita.fecha_hora ? formatDateTime(recordatorio.cita.fecha_hora) : 'Fecha no especificada' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Información del Sistema -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Información del Sistema</h3>
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">ID del Recordatorio</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ recordatorio.id }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Creado el</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ formatDateTime(recordatorio.created_at) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Última actualización</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ formatDateTime(recordatorio.updated_at) }}</dd>
                            </div>
                            <div v-if="recordatorio.enviado_en">
                                <dt class="text-sm font-medium text-gray-500">Enviado el</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ formatDateTime(recordatorio.enviado_en) }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
