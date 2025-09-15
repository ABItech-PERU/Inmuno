<script setup>
import { ref, computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import {
    ArrowLeftIcon,
    PencilIcon,
    ShieldCheckIcon,
    CalendarIcon,
    CheckCircleIcon,
    InformationCircleIcon,
    BeakerIcon
} from '@heroicons/vue/24/outline';

const props = defineProps({
    esquema: Object,
    estadisticas: Object
});

const statusColor = computed(() => {
    return props.esquema.activo ? 'text-green-600' : 'text-red-600';
});

const statusBgColor = computed(() => {
    return props.esquema.activo ? 'bg-green-100' : 'bg-red-100';
});

const formatEdad = (meses) => {
    if (!meses && meses !== 0) return 'No especificada';

    if (meses < 12) {
        return `${meses} ${meses === 1 ? 'mes' : 'meses'}`;
    } else {
        const años = Math.floor(meses / 12);
        const mesesRestantes = meses % 12;

        if (mesesRestantes === 0) {
            return `${años} ${años === 1 ? 'año' : 'años'}`;
        } else {
            return `${años} ${años === 1 ? 'año' : 'años'} y ${mesesRestantes} ${mesesRestantes === 1 ? 'mes' : 'meses'}`;
        }
    }
};

const formatRangoEdad = (edadInicio, edadFin) => {
    if (edadInicio === null && edadFin === null) {
        return 'Sin límite de edad';
    } else if (edadInicio !== null && edadFin === null) {
        return `Desde ${formatEdad(edadInicio)}`;
    } else if (edadInicio === null && edadFin !== null) {
        return `Hasta ${formatEdad(edadFin)}`;
    } else {
        return `${formatEdad(edadInicio)} a ${formatEdad(edadFin)}`;
    }
};
</script>

<template>
    <AppLayout :title="esquema.nombre">
        <Head :title="esquema.nombre" />

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

                        <div class="flex items-center space-x-3">
                            <Link
                                :href="`/admin/esquemas/${esquema.id}/edit`"
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
                                <ClipboardDocumentListIcon class="h-8 w-8 text-cyan-600" />
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">{{ esquema.nombre }}</h1>
                                <div class="flex items-center space-x-4 mt-1">
                                    <span :class="[
                                        'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                        statusBgColor,
                                        statusColor
                                    ]">
                                        <CheckCircleIcon v-if="esquema.activo" class="h-3 w-3 mr-1" />
                                        <XCircleIcon v-else class="h-3 w-3 mr-1" />
                                        {{ esquema.activo ? 'Activo' : 'Inactivo' }}
                                    </span>
                                    <span v-if="esquema.obligatorio" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        <ShieldCheckIcon class="h-3 w-3 mr-1" />
                                        Obligatorio
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <UserGroupIcon class="h-3 w-3 mr-1" />
                                        {{ esquema.grupo_edad }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
                <!-- Estadísticas -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <BeakerIcon class="h-8 w-8 text-cyan-600" />
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Total Vacunas</dt>
                                        <dd class="text-2xl font-semibold text-gray-900">{{ estadisticas.total_vacunas || 0 }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <ChartBarIcon class="h-8 w-8 text-blue-600" />
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Total Dosis</dt>
                                        <dd class="text-2xl font-semibold text-gray-900">{{ estadisticas.total_dosis || 0 }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <ShieldCheckIcon class="h-8 w-8 text-orange-600" />
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Esquema Obligatorio</dt>
                                        <dd class="text-xl font-semibold">
                                            <span v-if="estadisticas.es_obligatorio" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                                                ✓ Obligatorio
                                            </span>
                                            <span v-else class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                                Opcional
                                            </span>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <CalendarIcon class="h-8 w-8 text-purple-600" />
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Promedio Edad</dt>
                                        <dd class="text-2xl font-semibold text-gray-900">
                                            {{ estadisticas.promedio_edad ? formatEdad(Math.round(estadisticas.promedio_edad)) : 'N/A' }}
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información del Esquema -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Detalles Básicos -->
                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-6">Información del Esquema</h3>

                        <div class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Descripción</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ esquema.descripcion || 'Sin descripción proporcionada' }}
                                </dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500">Grupo de Edad</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ esquema.grupo_edad }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500">Rango de Edad</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ formatRangoEdad(esquema.edad_inicio, esquema.edad_fin) }}
                                </dd>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Obligatorio</dt>
                                    <dd class="mt-1">
                                        <span v-if="esquema.obligatorio" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                            <CheckCircleIcon class="h-3 w-3 mr-1" />
                                            Sí
                                        </span>
                                        <span v-else class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <XCircleIcon class="h-3 w-3 mr-1" />
                                            No
                                        </span>
                                    </dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Estado</dt>
                                    <dd class="mt-1">
                                        <span :class="[
                                            'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium',
                                            esquema.activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                                        ]">
                                            <CheckCircleIcon v-if="esquema.activo" class="h-3 w-3 mr-1" />
                                            <XCircleIcon v-else class="h-3 w-3 mr-1" />
                                            {{ esquema.activo ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </dd>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-gray-200">
                                <dt class="text-sm font-medium text-gray-500">Fechas</dt>
                                <dd class="mt-1 text-xs text-gray-600">
                                    <div>Creado: {{ new Date(esquema.created_at).toLocaleDateString('es-ES') }}</div>
                                    <div>Actualizado: {{ new Date(esquema.updated_at).toLocaleDateString('es-ES') }}</div>
                                </dd>
                            </div>
                        </div>
                    </div>

                    <!-- Resumen Rápido -->
                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Resumen del Esquema</h3>

                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="text-gray-500 text-xs font-medium">VACUNAS INCLUIDAS</div>
                                <div class="text-lg font-semibold text-gray-900">{{ estadisticas.total_vacunas || 0 }}</div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="text-gray-500 text-xs font-medium">TOTAL DOSIS</div>
                                <div class="text-lg font-semibold text-gray-900">{{ estadisticas.total_dosis || 0 }}</div>
                            </div>
                        </div>

                        <div v-if="estadisticas.total_vacunas > 0" class="mt-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center text-sm text-green-800">
                                <CheckCircleIcon class="h-4 w-4 mr-2" />
                                <span>Esquema configurado con {{ estadisticas.total_vacunas }} vacuna(s)</span>
                            </div>
                        </div>
                        <div v-else class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                            <div class="flex items-center text-sm text-amber-800">
                                <InformationCircleIcon class="h-4 w-4 mr-2" />
                                <span>Listo para agregar vacunas - Haz clic en "Gestionar Vacunas" abajo</span>
                            </div>
                        </div>
                    </div>

                    <!-- Acción Principal -->
                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Gestión de Vacunas</h3>
                        <p class="text-sm text-gray-600 mb-6">
                            <span v-if="estadisticas.total_vacunas > 0">
                                Administra las {{ estadisticas.total_vacunas }} vacuna(s) y {{ estadisticas.total_dosis }} dosis de este esquema.
                            </span>
                            <span v-else>
                                Comienza agregando las vacunas y dosis para este esquema de vacunación.
                            </span>
                        </p>

                        <Link
                            :href="`/admin/esquemas/${esquema.id}/vacunas`"
                            class="inline-flex items-center justify-center w-full px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-cyan-600 hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 transition-colors duration-200"
                        >
                            <BeakerIcon class="h-5 w-5 mr-2" />
                            <span v-if="estadisticas.total_vacunas > 0">Gestionar Vacunas y Dosis</span>
                            <span v-else>Agregar Primera Vacuna</span>
                        </Link>

                        <div class="mt-4 text-xs text-gray-500 text-center">
                            <span v-if="estadisticas.total_vacunas > 0">
                                Editar vacunas, dosis, edades e intervalos existentes
                            </span>
                            <span v-else>
                                Configurar vacunas, dosis, edades de aplicación e intervalos
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
