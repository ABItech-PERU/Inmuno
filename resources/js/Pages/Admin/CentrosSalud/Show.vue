<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { BuildingOffice2Icon, ArrowLeftIcon, PencilIcon, MapPinIcon, PhoneIcon, EnvelopeIcon, ClockIcon, CheckCircleIcon, ExclamationTriangleIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    centroSalud: Object,
});

const formatTipo = (tipo) => {
    const tipos = {
        'Hospital': 'Hospital',
        'Centro de Salud': 'Centro de Salud',
        'Puesto de Salud': 'Puesto de Salud',
        'Clínica': 'Clínica'
    };
    return tipos[tipo] || tipo;
};

const formatHorario = (inicio, fin) => {
    if (!inicio || !fin) return 'No especificado';
    return `${inicio} - ${fin}`;
};

const getUbicacion = (centro) => {
    const distrito = centro.distrito?.nombre || '';
    const provincia = centro.distrito?.provincia?.nombre || '';
    const departamento = centro.distrito?.provincia?.departamento?.nombre || '';

    return [distrito, provincia, departamento].filter(Boolean).join(', ');
};

const getServicios = (servicios) => {
    if (!servicios) return [];

    // Si es un array, devolverlo tal como está
    if (Array.isArray(servicios)) {
        return servicios;
    }

    // Si es un string (JSON), parsearlo
    if (typeof servicios === 'string') {
        try {
            const parsed = JSON.parse(servicios);
            return Array.isArray(parsed) ? parsed : [];
        } catch (e) {
            console.error('Error parsing servicios:', e);
            return [];
        }
    }

    return [];
};
</script>

<template>
    <AppLayout :title="centroSalud.nombre">
        <Head :title="centroSalud.nombre" />

        <div class="min-h-screen bg-gray-50">
            <!-- Header -->
            <div class="bg-white shadow-sm border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <Link
                                href="/admin/centros-salud"
                                class="inline-flex items-center text-gray-500 hover:text-gray-700"
                            >
                                <ArrowLeftIcon class="h-5 w-5 mr-2" />
                                Volver a centros de salud
                            </Link>
                        </div>

                        <div class="flex items-center space-x-3">
                            <Link
                                :href="`/admin/centros-salud/${centroSalud.id}/edit`"
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
                                <BuildingOffice2Icon class="h-12 w-12 text-cyan-600" />
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">{{ centroSalud.nombre }}</h1>
                                <div class="flex items-center space-x-4 mt-2">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                        :class="centroSalud.activo ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'"
                                    >
                                        <CheckCircleIcon v-if="centroSalud.activo" class="h-4 w-4 mr-1" />
                                        <ExclamationTriangleIcon v-else class="h-4 w-4 mr-1" />
                                        {{ centroSalud.activo ? 'Activo' : 'Inactivo' }}
                                    </span>
                                    <span class="text-sm text-gray-500">{{ formatTipo(centroSalud.tipo) }}</span>
                                    <span class="text-sm text-gray-500">
                                        Nivel {{ centroSalud.nivel_atencion }}
                                    </span>
                                    <span v-if="centroSalud.codigo" class="text-sm text-gray-500">
                                        Código: {{ centroSalud.codigo }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido principal -->
            <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8 py-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Información principal -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Detalles básicos -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Información General</h3>
                            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Código</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ centroSalud.codigo }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tipo</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ formatTipo(centroSalud.tipo) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Nivel de Atención</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ centroSalud.nivel_atencion }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Capacidad</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ centroSalud.capacidad || 'No especificada' }} pacientes</dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Ubicación -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Ubicación</h3>
                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <MapPinIcon class="h-5 w-5 text-gray-400 mr-3 mt-0.5" />
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ getUbicacion(centroSalud) }}</p>
                                        <p class="text-sm text-gray-600 mt-1">{{ centroSalud.direccion }}</p>
                                    </div>
                                </div>
                                <div v-if="centroSalud.latitud && centroSalud.longitud" class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Latitud</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ centroSalud.latitud }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Longitud</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ centroSalud.longitud }}</dd>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Servicios -->
                        <div v-if="getServicios(centroSalud.servicios).length > 0" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Servicios Disponibles</h3>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                <span
                                    v-for="servicio in getServicios(centroSalud.servicios)"
                                    :key="servicio"
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800"
                                >
                                    {{ servicio }}
                                </span>
                            </div>
                        </div>

                        <!-- Mostrar mensaje si no hay servicios -->
                        <div v-else-if="centroSalud.servicios" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Servicios Disponibles</h3>
                            <p class="text-gray-500 text-sm">No se han configurado servicios para este centro de salud.</p>
                        </div>
                    </div>

                    <!-- Panel lateral -->
                    <div class="space-y-6">
                        <!-- Información de contacto -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Contacto</h3>
                            <div class="space-y-4">
                                <div v-if="centroSalud.telefono" class="flex items-center">
                                    <PhoneIcon class="h-5 w-5 text-gray-400 mr-3" />
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Teléfono</p>
                                        <p class="text-sm text-gray-600">{{ centroSalud.telefono }}</p>
                                    </div>
                                </div>
                                <div v-if="centroSalud.email" class="flex items-center">
                                    <EnvelopeIcon class="h-5 w-5 text-gray-400 mr-3" />
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Email</p>
                                        <p class="text-sm text-gray-600">{{ centroSalud.email }}</p>
                                    </div>
                                </div>
                                <div v-if="centroSalud.horario_inicio && centroSalud.horario_fin" class="flex items-center">
                                    <ClockIcon class="h-5 w-5 text-gray-400 mr-3" />
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Horario</p>
                                        <p class="text-sm text-gray-600">{{ formatHorario(centroSalud.horario_inicio, centroSalud.horario_fin) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Estadísticas -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Estadísticas</h3>
                            <div class="space-y-4">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Citas registradas</span>
                                    <span class="text-sm font-medium text-gray-900">{{ centroSalud.citas?.length || 0 }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Aplicaciones de vacunas</span>
                                    <span class="text-sm font-medium text-gray-900">{{ centroSalud.aplicaciones?.length || 0 }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Servicios disponibles</span>
                                    <span class="text-sm font-medium text-gray-900">{{ getServicios(centroSalud.servicios).length }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Información del sistema -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Información del Sistema</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Fecha de registro</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ new Date(centroSalud.created_at).toLocaleDateString('es-PE', {
                                            year: 'numeric',
                                            month: 'long',
                                            day: 'numeric'
                                        }) }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Última actualización</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ new Date(centroSalud.updated_at).toLocaleDateString('es-PE', {
                                            year: 'numeric',
                                            month: 'long',
                                            day: 'numeric'
                                        }) }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
