<template>
    <AppLayout :title="`${dependiente.nombres} ${dependiente.apellidos}`">
        <Head :title="`${dependiente.nombres} ${dependiente.apellidos}`" />

        <div class="min-h-screen bg-gray-50">
            <!-- Header -->
            <div class="bg-white shadow-sm border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <Link
                                :href="route('paciente.dependientes.index')"
                                class="inline-flex items-center text-gray-500 hover:text-gray-700"
                            >
                                <ArrowLeftIcon class="h-5 w-5 mr-2" />
                                Volver a dependientes
                            </Link>
                        </div>

                        <div class="flex items-center space-x-3">
                            <Link
                                :href="route('paciente.dependientes.edit', dependiente.id)"
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
                                <UserIcon class="h-12 w-12 text-cyan-600" />
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">{{ dependiente.nombres }} {{ dependiente.apellidos }}</h1>
                                <div class="flex items-center space-x-4 mt-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                          :class="[statusBgColor, statusColor]">
                                        <CheckCircleIcon v-if="dependiente.activo" class="h-4 w-4 mr-1" />
                                        <XCircleIcon v-else class="h-4 w-4 mr-1" />
                                        {{ dependiente.activo ? 'Activo' : 'Inactivo' }}
                                    </span>
                                    <span class="text-sm text-gray-500">{{ dependiente.genero === 'M' ? 'Masculino' : 'Femenino' }}</span>
                                    <span class="text-sm text-gray-500">
                                        {{ estadisticas.edad_años }} años
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
                        <!-- Información Personal -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-6">Información Personal</h2>

                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Nombres completos</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ dependiente.nombres }} {{ dependiente.apellidos }}
                                </dd>
                            </div>

                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Documento de identidad</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ dependiente.tipo_documento }}: {{ dependiente.numero_documento }}
                                </dd>
                            </div>

                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Fecha de nacimiento</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ formatearFecha(dependiente.fecha_nacimiento) }}
                                    <span class="text-gray-500 ml-2">
                                        ({{ estadisticas.edad_años }} años{{ mesesRestantes > 0 ? `, ${mesesRestantes} meses` : '' }})
                                    </span>
                                </dd>
                            </div>

                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Género</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ dependiente.genero === 'M' ? 'Masculino' : 'Femenino' }}
                                </dd>
                            </div>

                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Parentesco</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ dependiente.parentesco }}
                                </dd>
                            </div>

                            <div v-if="dependiente.telefono" class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <a :href="`tel:${dependiente.telefono}`" class="text-blue-600 hover:text-blue-800">
                                        {{ dependiente.telefono }}
                                    </a>
                                </dd>
                            </div>

                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Estado</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <span :class="dependiente.activo
                                        ? 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800'
                                        : 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800'
                                    ">
                                        <span :class="dependiente.activo ? 'text-green-400 mr-1.5' : 'text-red-400 mr-1.5'">
                                            <svg class="w-2 h-2 fill-current" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3" />
                                            </svg>
                                        </span>
                                        {{ dependiente.activo ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </dd>
                            </div>

                            <div v-if="dependiente.observaciones" class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Observaciones</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ dependiente.observaciones }}
                                </dd>
                            </div>
                        </div>

                        <!-- Información de Registro -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-6">Información de registro</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de registro</label>
                                    <p class="text-sm text-gray-900">{{ formatearFecha(dependiente.created_at) }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Última actualización</label>
                                    <p class="text-sm text-gray-900">{{ formatearFecha(dependiente.updated_at) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="lg:col-span-1 space-y-6">
                        <!-- Estadísticas -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <InformationCircleIcon class="h-5 w-5 inline mr-2 text-cyan-600" />
                                Estadísticas
                            </h3>
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Edad</span>
                                    <span class="text-sm font-medium text-gray-900">{{ estadisticas.edad_años }} años</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Meses adicionales</span>
                                    <span class="text-sm font-medium text-gray-900">{{ mesesRestantes }} meses</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Menor de edad</span>
                                    <span :class="estadisticas.es_menor ? 'text-orange-600' : 'text-green-600'" class="text-sm font-medium">
                                        {{ estadisticas.es_menor ? 'Sí' : 'No' }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Usuario asociado</span>
                                    <span :class="estadisticas.tiene_usuario ? 'text-green-600' : 'text-gray-600'" class="text-sm font-medium">
                                        {{ estadisticas.tiene_usuario ? 'Sí' : 'No' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Información del tutor -->
                        <div v-if="dependiente.tutor" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <UserIcon class="h-5 w-5 inline mr-2 text-cyan-600" />
                                Tutor
                            </h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nombre completo</label>
                                    <p class="text-sm text-gray-900">{{ dependiente.tutor.nombres }} {{ dependiente.tutor.apellidos }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Email</label>
                                    <p class="text-sm text-gray-900">{{ dependiente.tutor.email }}</p>
                                </div>
                                <div v-if="dependiente.tutor.telefono">
                                    <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                                    <p class="text-sm text-gray-900">{{ dependiente.tutor.telefono }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alertas informativas -->
                <div v-if="estadisticas.es_menor" class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex">
                        <InformationCircleIcon class="h-5 w-5 text-blue-400 flex-shrink-0" />
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Dependiente menor de edad</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p>Este dependiente es menor de edad y requiere supervisión especial.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="!dependiente.activo" class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex">
                        <InformationCircleIcon class="h-5 w-5 text-yellow-400 flex-shrink-0" />
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Dependiente inactivo</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>Este dependiente está marcado como inactivo.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import {
    ArrowLeftIcon,
    UserIcon,
    PencilIcon,
    InformationCircleIcon,
    CheckCircleIcon,
    XCircleIcon
} from '@heroicons/vue/24/outline';

const props = defineProps({
    dependiente: Object,
    estadisticas: Object,
});

const statusColor = computed(() => {
    return props.dependiente.activo ? 'text-green-600' : 'text-red-600';
});

const statusBgColor = computed(() => {
    return props.dependiente.activo ? 'bg-green-100' : 'bg-red-100';
});

const mesesRestantes = computed(() => {
    if (!props.estadisticas.edad_meses) return '0';
    // Calcular solo los meses restantes después de los años completos
    const mesesTotales = Math.floor(props.estadisticas.edad_meses);
    const mesesRestantes = mesesTotales % 12;
    return mesesRestantes;
});

const formatearFecha = (fecha) => {
    if (!fecha) return 'No especificada';
    const date = new Date(fecha);
    return date.toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};
</script>
