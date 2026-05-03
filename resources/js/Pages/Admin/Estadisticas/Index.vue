<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { computed } from "vue";
import {
    ChartBarIcon,
    UserGroupIcon,
    BeakerIcon,
    CalendarDaysIcon,
    MapPinIcon,
    BellIcon,
    ArrowTrendingUpIcon,
    UsersIcon,
    BuildingOfficeIcon,
    ClockIcon,
} from "@heroicons/vue/24/outline";

const props = defineProps({
    estadisticasGenerales: Object,
    estadisticasRoles: Object,
    estadisticasVacunacion: Object,
    estadisticasGeograficas: Object,
    estadisticasCitas: Object,
    estadisticasRecordatorios: Object,
    estadisticasTemporales: Object,
});

// Función para formatear números
const formatNumber = (num) => {
    return new Intl.NumberFormat("es-ES").format(num);
};

// Función para calcular porcentajes
const calculatePercentage = (value, total) => {
    if (total === 0) return 0;
    return Math.round((value / total) * 100);
};
</script>

<template>
    <AppLayout title="Estadísticas y Reportes">
        <div class="min-h-screen bg-gray-50">
            <!-- Header -->
            <div class="bg-white shadow-sm border-b border-gray-200">
                <div
                    class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8 py-4 sm:py-6"
                >
                    <div
                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4"
                    >
                        <div>
                            <h1
                                class="text-xl sm:text-2xl font-bold text-gray-900"
                            >
                                Estadísticas y Reportes
                            </h1>
                            <p class="mt-1 text-xs sm:text-sm text-gray-600">
                                Panel completo de análisis del sistema de
                                vacunación
                            </p>
                        </div>
                        <div class="flex items-center">
                            <div
                                class="flex items-center bg-green-100 text-green-800 px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm"
                            >
                                <div
                                    class="w-2 h-2 bg-green-500 rounded-full mr-2"
                                ></div>
                                <span class="hidden sm:inline"
                                    >Datos Actualizados</span
                                >
                                <span class="sm:hidden">Actualizado</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido principal -->
            <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8 py-6 space-y-6">
                <!-- Estadísticas Generales -->
                <div
                    class="bg-white rounded-lg shadow-sm border border-gray-200"
                >
                    <div
                        class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 border-b border-gray-200"
                    >
                        <h3
                            class="text-base sm:text-lg font-medium text-gray-900 flex items-center"
                        >
                            <ChartBarIcon
                                class="h-4 w-4 sm:h-5 sm:w-5 mr-2 text-cyan-600"
                            />
                            Estadísticas Generales del Sistema
                        </h3>
                    </div>
                    <div class="p-3 sm:p-4 lg:p-6">
                        <div
                            class="grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4"
                        >
                            <div
                                class="bg-gradient-to-r from-cyan-50 to-cyan-100 rounded-lg p-3 sm:p-4"
                            >
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <UsersIcon
                                            class="h-6 w-6 sm:h-8 sm:w-8 text-cyan-600"
                                        />
                                    </div>
                                    <div class="ml-2 sm:ml-3">
                                        <p
                                            class="text-xl sm:text-2xl font-semibold text-gray-900"
                                        >
                                            {{
                                                formatNumber(
                                                    estadisticasGenerales.totalUsuarios
                                                )
                                            }}
                                        </p>
                                        <p
                                            class="text-xs sm:text-sm text-gray-600"
                                        >
                                            Usuarios Registrados
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="bg-gradient-to-r from-indigo-50 to-indigo-100 rounded-lg p-3 sm:p-4"
                            >
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <UserGroupIcon
                                            class="h-6 w-6 sm:h-8 sm:w-8 text-indigo-600"
                                        />
                                    </div>
                                    <div class="ml-2 sm:ml-3">
                                        <p
                                            class="text-xl sm:text-2xl font-semibold text-gray-900"
                                        >
                                            {{
                                                formatNumber(
                                                    estadisticasGenerales.totalDependientes
                                                )
                                            }}
                                        </p>
                                        <p
                                            class="text-xs sm:text-sm text-gray-600"
                                        >
                                            Dependientes
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="bg-gradient-to-r from-green-50 to-green-100 rounded-lg p-3 sm:p-4"
                            >
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <BeakerIcon
                                            class="h-6 w-6 sm:h-8 sm:w-8 text-green-600"
                                        />
                                    </div>
                                    <div class="ml-2 sm:ml-3">
                                        <p
                                            class="text-xl sm:text-2xl font-semibold text-gray-900"
                                        >
                                            {{
                                                formatNumber(
                                                    estadisticasGenerales.totalVacunas
                                                )
                                            }}
                                        </p>
                                        <p
                                            class="text-xs sm:text-sm text-gray-600"
                                        >
                                            Vacunas Disponibles
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg p-3 sm:p-4"
                            >
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <ClockIcon
                                            class="h-6 w-6 sm:h-8 sm:w-8 text-blue-600"
                                        />
                                    </div>
                                    <div class="ml-2 sm:ml-3">
                                        <p
                                            class="text-xl sm:text-2xl font-semibold text-gray-900"
                                        >
                                            {{
                                                formatNumber(
                                                    estadisticasGenerales.totalAplicaciones
                                                )
                                            }}
                                        </p>
                                        <p
                                            class="text-xs sm:text-sm text-gray-600"
                                        >
                                            Aplicaciones Realizadas
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg p-3 sm:p-4"
                            >
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <BuildingOfficeIcon
                                            class="h-6 w-6 sm:h-8 sm:w-8 text-purple-600"
                                        />
                                    </div>
                                    <div class="ml-2 sm:ml-3">
                                        <p
                                            class="text-xl sm:text-2xl font-semibold text-gray-900"
                                        >
                                            {{
                                                formatNumber(
                                                    estadisticasGenerales.totalCentrosSalud
                                                )
                                            }}
                                        </p>
                                        <p
                                            class="text-xs sm:text-sm text-gray-600"
                                        >
                                            Centros de Salud
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas por Roles -->
                <div
                    class="bg-white rounded-lg shadow-sm border border-gray-200"
                >
                    <div
                        class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 border-b border-gray-200"
                    >
                        <h3
                            class="text-base sm:text-lg font-medium text-gray-900 flex items-center"
                        >
                            <UserGroupIcon
                                class="h-4 w-4 sm:h-5 sm:w-5 mr-2 text-cyan-600"
                            />
                            Distribución por Roles
                        </h3>
                    </div>
                    <div class="p-3 sm:p-4 lg:p-6">
                        <div
                            class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4"
                        >
                            <div class="text-center">
                                <div
                                    class="bg-red-100 rounded-full w-12 h-12 sm:w-16 sm:h-16 flex items-center justify-center mx-auto mb-2"
                                >
                                    <span
                                        class="text-lg sm:text-2xl font-bold text-red-600"
                                        >{{
                                            estadisticasRoles.administradores
                                        }}</span
                                    >
                                </div>
                                <p
                                    class="text-xs sm:text-sm font-medium text-gray-900"
                                >
                                    Administradores
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{
                                        calculatePercentage(
                                            estadisticasRoles.administradores,
                                            estadisticasGenerales.totalUsuarios
                                        )
                                    }}%
                                </p>
                            </div>

                            <div class="text-center">
                                <div
                                    class="bg-blue-100 rounded-full w-12 h-12 sm:w-16 sm:h-16 flex items-center justify-center mx-auto mb-2"
                                >
                                    <span
                                        class="text-lg sm:text-2xl font-bold text-blue-600"
                                        >{{ estadisticasRoles.medicos }}</span
                                    >
                                </div>
                                <p
                                    class="text-xs sm:text-sm font-medium text-gray-900"
                                >
                                    Médicos
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{
                                        calculatePercentage(
                                            estadisticasRoles.medicos,
                                            estadisticasGenerales.totalUsuarios
                                        )
                                    }}%
                                </p>
                            </div>

                            <div class="text-center">
                                <div
                                    class="bg-green-100 rounded-full w-12 h-12 sm:w-16 sm:h-16 flex items-center justify-center mx-auto mb-2"
                                >
                                    <span
                                        class="text-lg sm:text-2xl font-bold text-green-600"
                                        >{{ estadisticasRoles.pacientes }}</span
                                    >
                                </div>
                                <p
                                    class="text-xs sm:text-sm font-medium text-gray-900"
                                >
                                    Pacientes
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{
                                        calculatePercentage(
                                            estadisticasRoles.pacientes,
                                            estadisticasGenerales.totalUsuarios
                                        )
                                    }}%
                                </p>
                            </div>

                            <div class="text-center">
                                <div
                                    class="bg-gray-100 rounded-full w-12 h-12 sm:w-16 sm:h-16 flex items-center justify-center mx-auto mb-2"
                                >
                                    <span
                                        class="text-lg sm:text-2xl font-bold text-gray-600"
                                        >{{
                                            estadisticasRoles.usuariosSinRol
                                        }}</span
                                    >
                                </div>
                                <p
                                    class="text-xs sm:text-sm font-medium text-gray-900"
                                >
                                    Sin Rol
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{
                                        calculatePercentage(
                                            estadisticasRoles.usuariosSinRol,
                                            estadisticasGenerales.totalUsuarios
                                        )
                                    }}%
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas de Vacunación -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                    <!-- Vacunas Más Aplicadas -->
                    <div
                        class="bg-white rounded-lg shadow-sm border border-gray-200"
                    >
                        <div
                            class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 border-b border-gray-200"
                        >
                            <h3
                                class="text-base sm:text-lg font-medium text-gray-900 flex items-center"
                            >
                                <BeakerIcon
                                    class="h-4 w-4 sm:h-5 sm:w-5 mr-2 text-cyan-600"
                                />
                                Vacunas Más Aplicadas
                            </h3>
                        </div>
                        <div class="p-3 sm:p-4 lg:p-6">
                            <div
                                v-if="
                                    estadisticasVacunacion.vacunasMasAplicadas
                                        .length > 0
                                "
                                class="space-y-3"
                            >
                                <div
                                    v-for="(
                                        vacuna, index
                                    ) in estadisticasVacunacion.vacunasMasAplicadas.slice(
                                        0,
                                        5
                                    )"
                                    :key="index"
                                    class="flex items-center justify-between py-2"
                                >
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 w-8 h-8 bg-cyan-100 rounded-full flex items-center justify-center"
                                        >
                                            <span
                                                class="text-sm font-medium text-cyan-600"
                                                >{{ index + 1 }}</span
                                            >
                                        </div>
                                        <div class="ml-3">
                                            <p
                                                class="text-sm font-medium text-gray-900"
                                            >
                                                {{ vacuna.nombre }}
                                            </p>
                                        </div>
                                    </div>
                                    <div
                                        class="text-sm font-semibold text-gray-900"
                                    >
                                        {{ formatNumber(vacuna.total) }}
                                    </div>
                                </div>
                            </div>
                            <div v-else class="text-center py-8 text-gray-500">
                                <BeakerIcon
                                    class="h-12 w-12 mx-auto mb-4 text-gray-300"
                                />
                                <p>No hay datos de vacunación disponibles</p>
                            </div>
                        </div>
                    </div>

                    <!-- Estado de Dosis -->
                    <div
                        class="bg-white rounded-lg shadow-sm border border-gray-200"
                    >
                        <div
                            class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 border-b border-gray-200"
                        >
                            <h3
                                class="text-base sm:text-lg font-medium text-gray-900 flex items-center"
                            >
                                <ArrowTrendingUpIcon
                                    class="h-4 w-4 sm:h-5 sm:w-5 mr-2 text-cyan-600"
                                />
                                Distribución por Dosis
                            </h3>
                        </div>
                        <div class="p-4 sm:p-6">
                            <div
                                v-if="
                                    estadisticasVacunacion.estadoDosis.length >
                                    0
                                "
                                class="space-y-3"
                            >
                                <div
                                    v-for="dosis in estadisticasVacunacion.estadoDosis"
                                    :key="dosis.numero_dosis"
                                    class="flex items-center justify-between py-2"
                                >
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center"
                                        >
                                            <span
                                                class="text-sm font-medium text-blue-600"
                                                >{{ dosis.numero_dosis }}</span
                                            >
                                        </div>
                                        <div class="ml-3">
                                            <p
                                                class="text-sm font-medium text-gray-900"
                                            >
                                                {{ dosis.numero_dosis }}ª Dosis
                                            </p>
                                        </div>
                                    </div>
                                    <div
                                        class="text-sm font-semibold text-gray-900"
                                    >
                                        {{ formatNumber(dosis.total) }}
                                    </div>
                                </div>
                            </div>
                            <div v-else class="text-center py-8 text-gray-500">
                                <ArrowTrendingUpIcon
                                    class="h-12 w-12 mx-auto mb-4 text-gray-300"
                                />
                                <p>No hay datos de dosis disponibles</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas Geográficas -->
                <div
                    class="bg-white rounded-lg shadow-sm border border-gray-200"
                >
                    <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                        <h3
                            class="text-lg font-medium text-gray-900 flex items-center"
                        >
                            <MapPinIcon class="h-5 w-5 mr-2 text-cyan-600" />
                            Distribución Geográfica
                        </h3>
                    </div>
                    <div class="p-4 sm:p-6">
                        <div
                            class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6"
                        >
                            <!-- Aplicaciones por Departamento -->
                            <div>
                                <h4
                                    class="text-md font-medium text-gray-900 mb-4"
                                >
                                    Aplicaciones por Departamento
                                </h4>
                                <div
                                    v-if="
                                        estadisticasGeograficas
                                            .aplicacionesPorDepartamento
                                            .length > 0
                                    "
                                    class="space-y-2"
                                >
                                    <div
                                        v-for="(
                                            dept, index
                                        ) in estadisticasGeograficas.aplicacionesPorDepartamento.slice(
                                            0,
                                            8
                                        )"
                                        :key="index"
                                        class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded"
                                    >
                                        <span class="text-sm text-gray-900">{{
                                            dept.departamento
                                        }}</span>
                                        <span
                                            class="text-sm font-semibold text-cyan-600"
                                            >{{
                                                formatNumber(dept.total)
                                            }}</span
                                        >
                                    </div>
                                </div>
                                <div
                                    v-else
                                    class="text-center py-8 text-gray-500"
                                >
                                    <MapPinIcon
                                        class="h-8 w-8 mx-auto mb-2 text-gray-300"
                                    />
                                    <p class="text-sm">
                                        No hay datos geográficos disponibles
                                    </p>
                                </div>
                            </div>

                            <!-- Centros Más Activos -->
                            <div>
                                <h4
                                    class="text-md font-medium text-gray-900 mb-4"
                                >
                                    Centros de Salud Más Activos
                                </h4>
                                <div
                                    v-if="
                                        estadisticasGeograficas
                                            .centrosMasActivos.length > 0
                                    "
                                    class="space-y-2"
                                >
                                    <div
                                        v-for="(
                                            centro, index
                                        ) in estadisticasGeograficas.centrosMasActivos.slice(
                                            0,
                                            8
                                        )"
                                        :key="index"
                                        class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded"
                                    >
                                        <span
                                            class="text-sm text-gray-900 truncate"
                                            >{{ centro.nombre }}</span
                                        >
                                        <span
                                            class="text-sm font-semibold text-green-600"
                                            >{{
                                                formatNumber(centro.total)
                                            }}</span
                                        >
                                    </div>
                                </div>
                                <div
                                    v-else
                                    class="text-center py-8 text-gray-500"
                                >
                                    <BuildingOfficeIcon
                                        class="h-8 w-8 mx-auto mb-2 text-gray-300"
                                    />
                                    <p class="text-sm">
                                        No hay datos de centros disponibles
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas de Citas y Recordatorios -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                    <!-- Estado de Citas -->
                    <div
                        class="bg-white rounded-lg shadow-sm border border-gray-200"
                    >
                        <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                            <h3
                                class="text-lg font-medium text-gray-900 flex items-center"
                            >
                                <CalendarDaysIcon
                                    class="h-5 w-5 mr-2 text-cyan-600"
                                />
                                Estado de Citas
                            </h3>
                        </div>
                        <div class="p-4 sm:p-6">
                            <div
                                v-if="
                                    estadisticasCitas.citasPorEstado.length > 0
                                "
                                class="space-y-3"
                            >
                                <div
                                    v-for="estado in estadisticasCitas.citasPorEstado"
                                    :key="estado.estado"
                                    class="flex items-center justify-between py-3 px-4 rounded-lg border"
                                    :class="estado.color"
                                >
                                    <span class="text-sm font-medium">{{
                                        estado.estado
                                    }}</span>
                                    <span class="text-lg font-bold">{{
                                        formatNumber(estado.total)
                                    }}</span>
                                </div>
                            </div>
                            <div v-else class="text-center py-8 text-gray-500">
                                <CalendarDaysIcon
                                    class="h-12 w-12 mx-auto mb-4 text-gray-300"
                                />
                                <p>No hay datos de citas disponibles</p>
                            </div>
                        </div>
                    </div>

                    <!-- Estado de Recordatorios -->
                    <div
                        class="bg-white rounded-lg shadow-sm border border-gray-200"
                    >
                        <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                            <h3
                                class="text-lg font-medium text-gray-900 flex items-center"
                            >
                                <BellIcon class="h-5 w-5 mr-2 text-cyan-600" />
                                Estado de Recordatorios
                            </h3>
                        </div>
                        <div class="p-4 sm:p-6">
                            <div
                                v-if="
                                    estadisticasRecordatorios
                                        .recordatoriosPorEstado.length > 0
                                "
                                class="space-y-3"
                            >
                                <div
                                    v-for="estado in estadisticasRecordatorios.recordatoriosPorEstado"
                                    :key="estado.estado"
                                    class="flex items-center justify-between py-3 px-4 rounded-lg border"
                                    :class="estado.color"
                                >
                                    <span class="text-sm font-medium">{{
                                        estado.estado
                                    }}</span>
                                    <span class="text-lg font-bold">{{
                                        formatNumber(estado.total)
                                    }}</span>
                                </div>
                            </div>
                            <div v-else class="text-center py-8 text-gray-500">
                                <BellIcon
                                    class="h-12 w-12 mx-auto mb-4 text-gray-300"
                                />
                                <p>No hay datos de recordatorios disponibles</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actividad Reciente -->
                <div
                    class="bg-white rounded-lg shadow-sm border border-gray-200"
                >
                    <div
                        class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 border-b border-gray-200"
                    >
                        <h3
                            class="text-base sm:text-lg font-medium text-gray-900 flex items-center"
                        >
                            <ArrowTrendingUpIcon
                                class="h-4 w-4 sm:h-5 sm:w-5 mr-2 text-cyan-600"
                            />
                            Actividad del Sistema (Últimos 12 Meses)
                        </h3>
                    </div>
                    <div class="p-3 sm:p-4 lg:p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Mes
                                        </th>
                                        <th
                                            class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Nuevos Usuarios
                                        </th>
                                        <th
                                            class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Aplicaciones
                                        </th>
                                        <th
                                            class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Citas
                                        </th>
                                        <th
                                            class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Recordatorios
                                        </th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="bg-white divide-y divide-gray-200"
                                >
                                    <tr
                                        v-for="(
                                            actividad, index
                                        ) in estadisticasTemporales.actividadPorMes"
                                        :key="index"
                                        class="hover:bg-gray-50"
                                    >
                                        <td
                                            class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                        >
                                            {{ actividad.mes }}
                                        </td>
                                        <td
                                            class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                        >
                                            {{
                                                formatNumber(actividad.usuarios)
                                            }}
                                        </td>
                                        <td
                                            class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                        >
                                            {{
                                                formatNumber(
                                                    actividad.aplicaciones
                                                )
                                            }}
                                        </td>
                                        <td
                                            class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                        >
                                            {{ formatNumber(actividad.citas) }}
                                        </td>
                                        <td
                                            class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                        >
                                            {{
                                                formatNumber(
                                                    actividad.recordatorios
                                                )
                                            }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Resumen Ejecutivo -->
                <div
                    class="bg-gradient-to-r from-cyan-600 to-cyan-700 rounded-lg shadow-sm text-white"
                >
                    <div class="px-4 sm:px-6 py-4">
                        <h3 class="text-lg font-medium mb-4 flex items-center">
                            <ChartBarIcon class="h-5 w-5 mr-2" />
                            Resumen Ejecutivo
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="text-center">
                                <div class="text-3xl font-bold mb-1">
                                    {{
                                        formatNumber(
                                            estadisticasVacunacion.totalAplicacionesHoy
                                        )
                                    }}
                                </div>
                                <div class="text-cyan-100 text-sm">
                                    Aplicaciones Hoy
                                </div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold mb-1">
                                    {{
                                        formatNumber(estadisticasCitas.citasHoy)
                                    }}
                                </div>
                                <div class="text-cyan-100 text-sm">
                                    Citas Hoy
                                </div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold mb-1">
                                    {{
                                        formatNumber(
                                            estadisticasRecordatorios.recordatoriosHoy
                                        )
                                    }}
                                </div>
                                <div class="text-cyan-100 text-sm">
                                    Recordatorios Hoy
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
