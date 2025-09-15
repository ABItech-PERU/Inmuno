<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import {
    BeakerIcon,
    ClockIcon,
    XCircleIcon,
    UserIcon,
    UserGroupIcon,
    EyeIcon,
    CalendarDaysIcon,
    PlayIcon,
    CheckCircleIcon,
    PencilSquareIcon,
    Cog6ToothIcon
} from '@heroicons/vue/24/outline';

const props = defineProps({
    usuario: Object,
    dependientes: Array,
    error: String
});

// Funciones para el formato mostrado en la imagen
const formatEdadSimple = () => {
    if (!props.usuario?.edad_meses) return 'No especificada';

    const meses = Math.floor(props.usuario.edad_meses);
    if (meses < 12) return `${meses} meses`;

    const años = Math.floor(meses / 12);
    return `${años} años`;
};

const formatEdadDependiente = (fechaNacimiento) => {
    if (!fechaNacimiento) return 'No especificada';

    const hoy = new Date();
    const nacimiento = new Date(fechaNacimiento);
    const totalMeses = (hoy.getFullYear() - nacimiento.getFullYear()) * 12 +
                       (hoy.getMonth() - nacimiento.getMonth());

    if (totalMeses < 12) return `${totalMeses} meses`;

    const años = Math.floor(totalMeses / 12);
    const mesesRestantes = totalMeses % 12;

    if (mesesRestantes === 0) return `${años} años`;
    return `${años} años y ${mesesRestantes} meses`;
};

const calcularProgresoUsuario = () => {
    if (!props.usuario?.esquemas) return 0;
    return calcularProgresoTotal(props.usuario.esquemas);
};

const calcularVacunasDisponibles = () => {
    let total = 0;

    // Contar vacunas del usuario principal
    if (props.usuario?.esquemas) {
        Object.values(props.usuario.esquemas).forEach(esquema => {
            if (esquema.estadisticas?.pendientes) {
                total += esquema.estadisticas.pendientes;
            }
        });
    }

    // Contar vacunas de dependientes
    if (props.dependientes?.length > 0) {
        props.dependientes.forEach(item => {
            if (item.esquemas) {
                Object.values(item.esquemas).forEach(esquema => {
                    if (esquema.estadisticas?.pendientes) {
                        total += esquema.estadisticas.pendientes;
                    }
                });
            }
        });
    }

    return total;
};

const calcularEsquemasActivos = () => {
    let total = 0;

    // Contar esquemas del usuario principal
    if (props.usuario?.esquemas) {
        total += Object.keys(props.usuario.esquemas).length;
    }

    // Contar esquemas de dependientes
    if (props.dependientes?.length > 0) {
        props.dependientes.forEach(item => {
            if (item.esquemas) {
                total += Object.keys(item.esquemas).length;
            }
        });
    }

    return total;
};

const calcularProgresoTotal = (esquemas) => {
    if (!esquemas || !Array.isArray(esquemas) || esquemas.length === 0) return 0;

    let totalDosis = 0;
    let dosisAplicadas = 0;

    esquemas.forEach(esquema => {
        if (esquema.estadisticas) {
            totalDosis += esquema.estadisticas.total || 0;
            dosisAplicadas += esquema.estadisticas.aplicadas || 0;
        }
    });

    return totalDosis > 0 ? Math.round((dosisAplicadas / totalDosis) * 100) : 0;
};

// Función para determinar el botón de acción apropiado para cada esquema
const getBotonAccion = (estadisticas) => {
    const porcentaje = estadisticas.porcentaje_completo || 0;
    const pendientes = estadisticas.pendientes || 0;

    if (porcentaje === 0) {
        // No ha empezado el esquema
        return {
            texto: 'Agendar cita',
            icono: CalendarDaysIcon,
            color: 'from-cyan-600 to-cyan-700 hover:from-cyan-700 hover:to-cyan-800',
            bgColor: 'bg-cyan-50',
            textColor: 'text-cyan-700'
        };
    } else if (porcentaje === 100) {
        // Esquema completado
        return {
            texto: 'Completado',
            icono: CheckCircleIcon,
            color: 'from-cyan-600 to-cyan-700 hover:from-cyan-700 hover:to-cyan-800',
            bgColor: 'bg-cyan-50',
            textColor: 'text-cyan-700'
        };
    } else if (pendientes > 0) {
        // Esquema en progreso con dosis pendientes
        return {
            texto: 'Agendar cita',
            icono: CalendarDaysIcon,
            color: 'from-cyan-600 to-cyan-700 hover:from-cyan-700 hover:to-cyan-800',
            bgColor: 'bg-cyan-50',
            textColor: 'text-cyan-700'
        };
    } else {
        // Estado por defecto - continuar esquema
        return {
            texto: 'Continuar',
            icono: PlayIcon,
            color: 'from-cyan-600 to-cyan-700 hover:from-cyan-700 hover:to-cyan-800',
            bgColor: 'bg-cyan-50',
            textColor: 'text-cyan-700'
        };
    }
};
</script>

<template>
    <AppLayout title="Mi esquema">
        <Head title="Mi esquema" />

        <div class="min-h-screen bg-gray-50">
            <!-- Header con Título Principal -->
            <div class="bg-white shadow-sm border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
                    <div class="flex items-center space-y-3 sm:space-y-0 sm:space-x-3 mb-4 sm:mb-2">
                        <div class="flex items-center space-x-3">
                            <div class="bg-cyan-100 p-2 rounded-lg flex-shrink-0">
                                <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Mi Esquema de Vacunación</h1>
                                <p class="text-gray-600 text-xs sm:text-sm">Mantén al día tu calendario de vacunas y el de tu familia</p>
                            </div>
                        </div>
                    </div>

                    <!-- Métricas Resumidas -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mt-4 sm:mt-6">
                        <div class="flex items-center space-x-3 bg-blue-50 p-3 rounded-lg">
                            <div class="bg-blue-100 p-2 rounded-lg flex-shrink-0">
                                <UserIcon class="h-4 w-4 sm:h-5 sm:w-5 text-blue-600" />
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Tu progreso</p>
                                <p class="text-sm font-semibold text-gray-900">{{ calcularProgresoUsuario() }}% completado</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3 bg-green-50 p-3 rounded-lg">
                            <div class="bg-green-100 p-2 rounded-lg flex-shrink-0">
                                <UserGroupIcon class="h-4 w-4 sm:h-5 sm:w-5 text-green-600" />
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Dependientes</p>
                                <p class="text-sm font-semibold text-gray-900">{{ (dependientes?.length || 0) }} personas</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3 bg-yellow-50 p-3 rounded-lg">
                            <div class="bg-yellow-100 p-2 rounded-lg flex-shrink-0">
                                <ClockIcon class="h-4 w-4 sm:h-5 sm:w-5 text-yellow-600" />
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Disponibles</p>
                                <p class="text-sm font-semibold text-gray-900">{{ calcularVacunasDisponibles() }} vacunas</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3 bg-purple-50 p-3 rounded-lg">
                            <div class="bg-purple-100 p-2 rounded-lg flex-shrink-0">
                                <BeakerIcon class="h-4 w-4 sm:h-5 sm:w-5 text-purple-600" />
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Esquemas activos</p>
                                <p class="text-sm font-semibold text-gray-900">{{ calcularEsquemasActivos() }} esquemas</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido Principal -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div v-if="error" class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex">
                        <XCircleIcon class="h-5 w-5 text-red-400" />
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Error</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <p>{{ error }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección Usuario Principal (PACIENTE) -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                    <div class="p-4 sm:p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 space-y-3 sm:space-y-0">
                            <div class="flex items-center space-x-3">
                                <div class="bg-blue-100 p-2 sm:p-3 rounded-lg flex-shrink-0">
                                    <UserIcon class="h-5 w-5 sm:h-6 sm:w-6 text-blue-600" />
                                </div>
                                <div>
                                    <h2 class="text-base sm:text-lg font-semibold text-gray-900">Paciente Principal</h2>
                                    <p class="text-xs sm:text-sm text-gray-600">Tu esquema de vacunación personal</p>
                                </div>
                            </div>
                            <Link :href="route('profile.show')"
                                  class="inline-flex items-center justify-center bg-blue-100 text-blue-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-200 transition-colors w-full sm:w-auto">
                                <PencilSquareIcon class="h-4 w-4 mr-2" />
                                Actualizar Datos
                            </Link>
                        </div>

                        <!-- Usuario Principal como tarjeta individual -->
                        <div class="border border-gray-200 rounded-lg p-3 sm:p-4">
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between mb-4 space-y-3 sm:space-y-0">
                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-900 text-sm sm:text-base">
                                        {{ usuario.datos.name  }} {{ usuario.datos.apellidos }}
                                    </h3>
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4 mt-1 text-xs sm:text-sm text-gray-600 space-y-1 sm:space-y-0">
                                        <span>Edad: {{ formatEdadSimple() }}</span>
                                    </div>
                                </div>
                                <Link :href="route('paciente.esquema-vacunacion.show', { persona_tipo: 'paciente' })"
                                      class="inline-flex items-center justify-center px-3 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-xs font-semibold rounded-lg shadow-md hover:from-blue-700 hover:to-blue-800 transform hover:scale-105 transition-all duration-200 w-full sm:w-auto">
                                    <EyeIcon class="h-3 w-3 mr-2" />
                                    Ver Esquema Completo
                                </Link>
                            </div>

                        <!-- Esquemas disponibles para el usuario principal -->
                        <div v-if="usuario?.esquemas && Object.keys(usuario.esquemas).length > 0" class="space-y-3">
                            <div v-for="(esquemaData, esquemaId) in usuario.esquemas" :key="esquemaId"
                                 class="bg-gray-100 rounded-lg p-3">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-2 space-y-1 sm:space-y-0">
                                    <h4 class="font-medium text-xs sm:text-sm text-gray-800">{{ esquemaData.esquema.nombre }}</h4>
                                    <span class="text-xs text-gray-500 self-start sm:self-center">
                                        {{ esquemaData.estadisticas.aplicadas }}/{{ esquemaData.estadisticas.total }} dosis
                                    </span>
                                </div>
                                <p class="text-xs text-gray-600 mb-2">{{ esquemaData.esquema.descripcion }}</p>

                                <!-- Progreso -->
                                <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                                         :style="`width: ${esquemaData.estadisticas.porcentaje_completo}%`"></div>
                                </div>
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
                                    <div class="flex justify-between text-xs text-gray-500 sm:flex-1">
                                        <span>{{ esquemaData.estadisticas.porcentaje_completo }}% completado</span>
                                        <span>{{ esquemaData.estadisticas.pendientes }} pendientes</span>
                                    </div>
                                    <!-- Botón de Acción Contextual -->
                                    <Link :href="route('paciente.esquema-vacunacion.show', { persona_tipo: 'paciente' })"
                                          :class="['inline-flex items-center justify-center px-3 py-2 text-white text-xs font-semibold rounded-lg shadow-md transform hover:scale-105 transition-all duration-200 ml-0 sm:ml-2 w-full sm:w-auto bg-gradient-to-r', getBotonAccion(esquemaData.estadisticas).color]">
                                        <component :is="getBotonAccion(esquemaData.estadisticas).icono" class="h-3 w-3 mr-2" />
                                        {{ getBotonAccion(esquemaData.estadisticas).texto }}
                                    </Link>
                                </div>
                            </div>
                        </div>

                        <!-- Estado Sin Esquemas -->
                        <div v-else class="mt-4 text-center py-6">
                            <div class="bg-gray-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <p class="text-gray-600 text-sm">Sin esquemas disponibles para esta edad</p>
                        </div>
                    </div>
                </div>

                <!-- Sección Dependientes -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-4 sm:p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 space-y-3 sm:space-y-0">
                            <div class="flex items-center space-x-3">
                                <div class="bg-teal-100 p-2 sm:p-3 rounded-lg flex-shrink-0">
                                    <UserGroupIcon class="h-5 w-5 sm:h-6 sm:w-6 text-teal-600" />
                                </div>
                                <div>
                                    <h2 class="text-base sm:text-lg font-semibold text-gray-900">Dependientes</h2>
                                    <p class="text-xs sm:text-sm text-gray-600">Esquemas de vacunación de tu familia</p>
                                </div>
                            </div>
                            <Link :href="route('paciente.dependientes.index')"
                                  class="inline-flex items-center justify-center bg-teal-100 text-teal-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-teal-200 transition-colors w-full sm:w-auto">
                                <Cog6ToothIcon class="h-4 w-4 mr-2" />
                                Gestionar Dependientes
                            </Link>
                        </div>

                        <!-- Lista de Dependientes -->
                        <div v-if="dependientes && dependientes.length > 0" class="space-y-4">
                            <div v-for="item in dependientes" :key="item.dependiente.id"
                                 class="border border-gray-200 rounded-lg p-3 sm:p-4">
                                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between mb-4 space-y-3 sm:space-y-0">
                                    <div class="flex-1">
                                        <h3 class="font-medium text-gray-900 text-sm sm:text-base">
                                            {{ item.dependiente.nombre }}
                                        </h3>
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4 mt-1 text-xs sm:text-sm text-gray-600 space-y-1 sm:space-y-0">
                                            <span>Edad: {{ formatEdadDependiente(item.dependiente.fecha_nacimiento) }}</span>
                                            <span>DNI: {{ item.dependiente.numero_documento }}</span>
                                        </div>
                                    </div>
                                    <Link :href="route('paciente.esquema-vacunacion.show', {
                                        persona_tipo: 'dependiente',
                                        persona_id: item.dependiente.id
                                    })"
                                          class="inline-flex items-center justify-center px-3 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-xs font-semibold rounded-lg shadow-md hover:from-blue-700 hover:to-blue-800 transform hover:scale-105 transition-all duration-200 w-full sm:w-auto">
                                        <EyeIcon class="h-3 w-3 mr-2" />
                                        Ver Esquema Completo
                                    </Link>
                                </div>

                                <!-- Esquemas disponibles para el dependiente -->
                                <div v-if="item.esquemas && Object.keys(item.esquemas).length > 0" class="space-y-3">
                                    <div v-for="(esquemaData, esquemaId) in item.esquemas" :key="esquemaId"
                                         class="bg-gray-100 rounded-lg p-3">
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-2 space-y-1 sm:space-y-0">
                                            <h4 class="font-medium text-xs sm:text-sm text-gray-800">{{ esquemaData.esquema.nombre }}</h4>
                                            <span class="text-xs text-gray-500 self-start sm:self-center">
                                                {{ esquemaData.estadisticas.aplicadas }}/{{ esquemaData.estadisticas.total }} dosis
                                            </span>
                                        </div>
                                        <p class="text-xs text-gray-600 mb-2">{{ esquemaData.esquema.descripcion }}</p>

                                        <!-- Progreso -->
                                        <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                                            <div class="bg-teal-600 h-2 rounded-full transition-all duration-300"
                                                 :style="`width: ${esquemaData.estadisticas.porcentaje_completo}%`"></div>
                                        </div>
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
                                            <div class="flex justify-between text-xs text-gray-500 sm:flex-1">
                                                <span>{{ esquemaData.estadisticas.porcentaje_completo }}% completado</span>
                                                <span>{{ esquemaData.estadisticas.pendientes }} pendientes</span>
                                            </div>
                                            <!-- Botón de Acción Contextual para Dependiente -->
                                            <Link :href="route('paciente.esquema-vacunacion.show', {
                                                persona_tipo: 'dependiente',
                                                persona_id: item.dependiente.id
                                            })"
                                                  :class="['inline-flex items-center justify-center px-3 py-2 text-white text-xs font-semibold rounded-lg shadow-md transform hover:scale-105 transition-all duration-200 ml-0 sm:ml-2 w-full sm:w-auto bg-gradient-to-r', getBotonAccion(esquemaData.estadisticas).color]">
                                                <component :is="getBotonAccion(esquemaData.estadisticas).icono" class="h-3 w-3 mr-2" />
                                                {{ getBotonAccion(esquemaData.estadisticas).texto }}
                                            </Link>
                                        </div>
                                    </div>
                                </div>

                                <!-- Estado Sin Esquemas para Dependiente -->
                                <div v-else class="mt-4 text-center py-6">
                                    <div class="bg-gray-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <p class="text-gray-600 text-sm">Sin esquemas disponibles para esta edad</p>
                                </div>
                            </div>
                        </div>

                        <!-- Estado sin dependientes -->
                        <div v-else class="text-center py-6 sm:py-8">
                            <div class="bg-gray-100 w-12 h-12 sm:w-16 sm:h-16 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                                <UserGroupIcon class="w-6 h-6 sm:w-8 sm:h-8 text-gray-400" />
                            </div>
                            <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">No tienes dependientes registrados</h3>
                            <p class="text-gray-600 text-xs sm:text-sm px-4">Agrega dependientes para gestionar sus esquemas de vacunación</p>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
