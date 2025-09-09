<script setup>
import { ref, computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import {
    ArrowLeftIcon,
    BeakerIcon,
    PencilIcon,
    ChartBarIcon,
    ExclamationTriangleIcon,
    ShieldCheckIcon,
    CalendarIcon,
    UserIcon,
    CheckCircleIcon,
    XCircleIcon,
    InformationCircleIcon
} from '@heroicons/vue/24/outline';

const props = defineProps({
    vacuna: Object,
    estadisticas: Object,
});

const statusColor = computed(() => {
    return props.vacuna.activa ? 'text-green-600' : 'text-red-600';
});

const statusBgColor = computed(() => {
    return props.vacuna.activa ? 'bg-green-100' : 'bg-red-100';
});

const formatEdad = (meses) => {
    if (!meses) return 'No especificada';

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

const formatIntervalo = (dias) => {
    if (!dias) return 'No aplica';

    if (dias < 7) {
        return `${dias} ${dias === 1 ? 'día' : 'días'}`;
    } else if (dias < 30) {
        const semanas = Math.floor(dias / 7);
        const diasRestantes = dias % 7;

        if (diasRestantes === 0) {
            return `${semanas} ${semanas === 1 ? 'semana' : 'semanas'}`;
        } else {
            return `${semanas} ${semanas === 1 ? 'semana' : 'semanas'} y ${diasRestantes} ${diasRestantes === 1 ? 'día' : 'días'}`;
        }
    } else {
        const meses = Math.floor(dias / 30);
        return `${meses} ${meses === 1 ? 'mes' : 'meses'} (aprox.)`;
    }
};
</script>

<template>
    <AppLayout :title="vacuna.nombre">
        <Head :title="vacuna.nombre" />

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

                        <div class="flex items-center space-x-3">
                            <Link
                                :href="`/admin/vacunas/${vacuna.id}/edit`"
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
                                <BeakerIcon class="h-12 w-12 text-cyan-600" />
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900">{{ vacuna.nombre }}</h1>
                                <div class="flex items-center space-x-4 mt-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                          :class="[statusBgColor, statusColor]">
                                        <CheckCircleIcon v-if="vacuna.activa" class="h-4 w-4 mr-1" />
                                        <XCircleIcon v-else class="h-4 w-4 mr-1" />
                                        {{ vacuna.activa ? 'Activa' : 'Inactiva' }}
                                    </span>
                                    <span class="text-sm text-gray-500">{{ vacuna.tipo }}</span>
                                    <span v-if="vacuna.fabricante" class="text-sm text-gray-500">
                                        por {{ vacuna.fabricante }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Columna Principal -->
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Información General -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-6">Información General</h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Nombre</dt>
                                    <dd class="mt-1 text-lg text-gray-900">{{ vacuna.nombre }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tipo de Vacuna</dt>
                                    <dd class="mt-1 text-lg text-gray-900">{{ vacuna.tipo }}</dd>
                                </div>

                                <div v-if="vacuna.fabricante">
                                    <dt class="text-sm font-medium text-gray-500">Fabricante</dt>
                                    <dd class="mt-1 text-lg text-gray-900">{{ vacuna.fabricante }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Estado</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium"
                                              :class="[statusBgColor, statusColor]">
                                            {{ vacuna.activa ? 'Activa' : 'Inactiva' }}
                                        </span>
                                    </dd>
                                </div>

                                <div v-if="vacuna.descripcion" class="md:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Descripción</dt>
                                    <dd class="mt-1 text-gray-900">{{ vacuna.descripcion }}</dd>
                                </div>
                            </div>
                        </div>

                        <!-- Esquema de Vacunación -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                                <CalendarIcon class="h-5 w-5 mr-2 text-cyan-600" />
                                Esquema de Vacunación
                            </h2>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="bg-indigo-50 rounded-lg p-4">
                                    <div class="text-center">
                                        <div class="text-3xl font-bold text-cyan-600">{{ vacuna.dosis_requeridas }}</div>
                                        <div class="text-sm text-indigo-700 font-medium">
                                            {{ vacuna.dosis_requeridas === 1 ? 'Dosis' : 'Dosis' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-green-50 rounded-lg p-4">
                                    <div class="text-center">
                                        <div class="text-sm font-medium text-green-700 mb-1">Edad Mínima</div>
                                        <div class="text-lg font-semibold text-green-600">
                                            {{ formatEdad(vacuna.edad_minima) }}
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-orange-50 rounded-lg p-4">
                                    <div class="text-center">
                                        <div class="text-sm font-medium text-orange-700 mb-1">Edad Máxima</div>
                                        <div class="text-lg font-semibold text-orange-600">
                                            {{ formatEdad(vacuna.edad_maxima) }}
                                        </div>
                                    </div>
                                </div>

                                <div v-if="vacuna.intervalo_entre_dosis && vacuna.dosis_requeridas > 1" class="md:col-span-3">
                                    <div class="bg-cyan-50 rounded-lg p-4">
                                        <div class="text-center">
                                            <div class="text-sm font-medium text-cyan-700 mb-1">Intervalo entre Dosis</div>
                                            <div class="text-lg font-semibold text-cyan-600">
                                                {{ formatIntervalo(vacuna.intervalo_entre_dosis) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información Médica -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                                <ShieldCheckIcon class="h-5 w-5 mr-2 text-cyan-600" />
                                Información Médica
                            </h2>

                            <div class="space-y-6">
                                <div v-if="vacuna.contraindicaciones">
                                    <h3 class="text-lg font-medium text-gray-900 mb-3 flex items-center">
                                        <ExclamationTriangleIcon class="h-5 w-5 mr-2 text-red-600" />
                                        Contraindicaciones
                                    </h3>
                                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                        <p class="text-red-900">{{ vacuna.contraindicaciones }}</p>
                                    </div>
                                </div>

                                <div v-if="vacuna.efectos_secundarios">
                                    <h3 class="text-lg font-medium text-gray-900 mb-3 flex items-center">
                                        <InformationCircleIcon class="h-5 w-5 mr-2 text-yellow-600" />
                                        Efectos Secundarios
                                    </h3>
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                        <p class="text-yellow-900">{{ vacuna.efectos_secundarios }}</p>
                                    </div>
                                </div>

                                <div v-if="!vacuna.contraindicaciones && !vacuna.efectos_secundarios">
                                    <p class="text-gray-500 text-center py-8">
                                        No se ha registrado información médica adicional para esta vacuna.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="space-y-8">
                        <!-- Estadísticas de Uso -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <ChartBarIcon class="h-5 w-5 mr-2 text-cyan-600" />
                                Estadísticas de Uso
                            </h3>

                            <div class="space-y-4">
                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                    <span class="text-sm font-medium text-gray-700">Total Aplicaciones</span>
                                    <span class="text-lg font-bold text-cyan-600">
                                        {{ estadisticas?.total_aplicaciones || 0 }}
                                    </span>
                                </div>

                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                    <span class="text-sm font-medium text-gray-700">Este Mes</span>
                                    <span class="text-lg font-bold text-green-600">
                                        {{ estadisticas?.aplicaciones_ultimo_mes || 0 }}
                                    </span>
                                </div>

                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                    <span class="text-sm font-medium text-gray-700">Esquemas que la incluyen</span>
                                    <span class="text-lg font-bold text-orange-600">
                                        {{ estadisticas?.esquemas_incluidos || 0 }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Información de Auditoría -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Auditoría</h3>

                            <div class="space-y-3 text-sm">
                                <div>
                                    <span class="font-medium text-gray-700">Creada:</span>
                                    <p class="text-gray-600">{{ new Date(vacuna.created_at).toLocaleString() }}</p>
                                </div>

                                <div>
                                    <span class="font-medium text-gray-700">Última actualización:</span>
                                    <p class="text-gray-600">{{ new Date(vacuna.updated_at).toLocaleString() }}</p>
                                </div>

                                <div>
                                    <span class="font-medium text-gray-700">ID del sistema:</span>
                                    <p class="text-gray-600 font-mono">{{ vacuna.id }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Acciones Rápidas -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Acciones</h3>

                            <div class="space-y-3">
                                <Link
                                    :href="`/admin/vacunas/${vacuna.id}/edit`"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-cyan-500"
                                >
                                    <PencilIcon class="h-4 w-4 mr-2" />
                                    Editar Vacuna
                                </Link>

                                <button
                                    type="button"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-cyan-500"
                                    disabled
                                >
                                    <ChartBarIcon class="h-4 w-4 mr-2" />
                                    Ver Reportes
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
