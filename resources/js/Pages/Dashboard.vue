<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import {
    BeakerIcon,
    CalendarDaysIcon,
    ClipboardDocumentListIcon,
    MapPinIcon,
    IdentificationIcon,
    BellIcon,
    PlusCircleIcon,
    UserGroupIcon,
    ChartBarIcon
} from '@heroicons/vue/24/outline';

const page = usePage();
const user = computed(() => page.props.auth.user);

// Debug temporal - remover después
console.log('Usuario:', user.value);
console.log('Roles del usuario:', user.value?.roles);

// Simulando datos del dashboard - esto vendría del backend
const dashboardStats = computed(() => ({
    totalVacunas: 156,
    pacientesVacunados: 89,
    citasPendientes: 12,
    recordatoriosHoy: 5
}));

const quickActions = computed(() => {
    const userRoles = user.value?.roles || [];
    console.log('Roles encontrados:', userRoles); // Debug temporal

    const isAdmin = userRoles.some(role => role.name === 'ADMINISTRADOR' || role.name === 'administrador');
    const isMedico = userRoles.some(role => role.name === 'MEDICO' || role.name === 'medico');
    const isPaciente = userRoles.some(role => role.name === 'PACIENTE' || role.name === 'paciente');

    console.log('Es Admin:', isAdmin, 'Es Médico:', isMedico, 'Es Paciente:', isPaciente); // Debug temporal

    let actions = [];

    if (isPaciente) {
        actions = [
            {
                title: 'Todo sobre las vacunas',
                subtitle: 'Información completa sobre vacunación',
                icon: BeakerIcon,
                color: 'bg-gradient-to-r from-teal-400 to-cyan-400',
                textColor: 'text-teal-700',
                route: 'vaccines.index'
            },
            {
                title: 'Esquema de vacunación',
                subtitle: 'Tu calendario de vacunas personalizado',
                icon: CalendarDaysIcon,
                color: 'bg-gradient-to-r from-teal-400 to-cyan-400',
                textColor: 'text-teal-700',
                route: 'vaccination-schedule.show'
            },
            {
                title: 'Recordatorio para mi próxima vacuna',
                subtitle: 'Agenda tu siguiente cita de vacunación',
                icon: BellIcon,
                color: 'bg-gradient-to-r from-red-400 to-pink-400',
                textColor: 'text-red-700',
                route: 'appointments.create'
            },
            {
                title: 'Centros de salud más cercano',
                subtitle: 'Encuentra el centro de vacunación más próximo',
                icon: MapPinIcon,
                color: 'bg-gradient-to-r from-red-400 to-pink-400',
                textColor: 'text-red-700',
                route: 'health-centers.index'
            }
        ];
    } else if (isMedico) {
        actions = [
            {
                title: 'Registrar Vacunación',
                subtitle: 'Aplicar y registrar nueva vacuna',
                icon: PlusCircleIcon,
                color: 'bg-gradient-to-r from-blue-400 to-indigo-400',
                textColor: 'text-blue-700',
                route: 'vaccinations.create'
            },
            {
                title: 'Pacientes del Día',
                subtitle: 'Ver citas programadas para hoy',
                icon: UserGroupIcon,
                color: 'bg-gradient-to-r from-green-400 to-emerald-400',
                textColor: 'text-green-700',
                route: 'appointments.today'
            },
            {
                title: 'Esquemas de Vacunación',
                subtitle: 'Gestionar protocolos de vacunación',
                icon: ClipboardDocumentListIcon,
                color: 'bg-gradient-to-r from-purple-400 to-violet-400',
                textColor: 'text-purple-700',
                route: 'vaccination-schemes.index'
            },
            {
                title: 'Recordatorios Pendientes',
                subtitle: 'Gestionar alertas y recordatorios',
                icon: BellIcon,
                color: 'bg-gradient-to-r from-yellow-400 to-orange-400',
                textColor: 'text-yellow-700',
                route: 'reminders.index'
            }
        ];
    } else if (isAdmin) {
        actions = [
            {
                title: 'Gestión de Vacunas',
                subtitle: 'Administrar catálogo de vacunas',
                icon: BeakerIcon,
                color: 'bg-gradient-to-r from-indigo-400 to-purple-400',
                textColor: 'text-indigo-700',
                route: 'admin.vaccines.index'
            },
            {
                title: 'Usuarios del Sistema',
                subtitle: 'Gestionar médicos y pacientes',
                icon: UserGroupIcon,
                color: 'bg-gradient-to-r from-green-400 to-teal-400',
                textColor: 'text-green-700',
                route: 'admin.users.index'
            },
            {
                title: 'Reportes y Estadísticas',
                subtitle: 'Análisis del sistema de vacunación',
                icon: ChartBarIcon,
                color: 'bg-gradient-to-r from-blue-400 to-cyan-400',
                textColor: 'text-blue-700',
                route: 'admin.reports.index'
            },
            {
                title: 'Centros de Salud',
                subtitle: 'Administrar ubicaciones de vacunación',
                icon: MapPinIcon,
                color: 'bg-gradient-to-r from-red-400 to-pink-400',
                textColor: 'text-red-700',
                route: 'admin.health-centers.index'
            }
        ];
    } else {
        // Fallback: si no se detecta ningún rol específico, mostrar acciones básicas
        actions = [
            {
                title: 'Información sobre Vacunas',
                subtitle: 'Consulta información general sobre vacunación',
                icon: BeakerIcon,
                color: 'bg-gradient-to-r from-gray-400 to-gray-500',
                textColor: 'text-gray-700',
                route: 'vaccines.info'
            },
            {
                title: 'Contactar Soporte',
                subtitle: 'Necesitas ayuda con tu cuenta',
                icon: UserGroupIcon,
                color: 'bg-gradient-to-r from-gray-400 to-gray-500',
                textColor: 'text-gray-700',
                route: 'support.contact'
            }
        ];
    }

    return actions;
});

const additionalInfo = computed(() => [
    {
        title: 'Carné de Vacunación General',
        subtitle: 'Descarga tu certificado de vacunación completo',
        icon: IdentificationIcon,
        color: 'bg-gradient-to-r from-teal-400 to-cyan-400',
        textColor: 'text-teal-700',
        route: 'vaccination-card.download'
    }
]);
</script>

<template>
    <AppLayout title="Dashboard - Inmuno Alerta">
        <div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-50">
            <!-- Header Section -->
            <div class="bg-white shadow-sm border-b border-gray-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">
                                Bienvenido a tu plataforma de vacunación
                            </h1>
                            <p class="text-gray-600 text-sm sm:text-base">
                                Hola {{ user.name }}, mantén al día tu esquema de vacunación
                            </p>
                        </div>
                        <div class="mt-4 sm:mt-0 flex items-center space-x-2">
                            <div class="flex items-center bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                Sistema Activo
                            </div>
                            <!-- Debug temporal - mostrar rol actual -->
                            <div v-if="user.roles && user.roles.length > 0" class="flex items-center bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                                Rol: {{ user.roles[0].name }}
                            </div>
                            <div v-else class="flex items-center bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm">
                                Sin roles
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Section (solo para médicos y administradores) -->
            <div v-if="user.roles?.some(role => ['MEDICO', 'ADMINISTRADOR', 'medico', 'administrador'].includes(role.name))"
                 class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <BeakerIcon class="w-5 h-5 text-blue-600" />
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-2xl font-semibold text-gray-900">{{ dashboardStats.totalVacunas }}</p>
                                <p class="text-sm text-gray-500">Vacunas Aplicadas</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                    <UserGroupIcon class="w-5 h-5 text-green-600" />
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-2xl font-semibold text-gray-900">{{ dashboardStats.pacientesVacunados }}</p>
                                <p class="text-sm text-gray-500">Pacientes Vacunados</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                                    <CalendarDaysIcon class="w-5 h-5 text-yellow-600" />
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-2xl font-semibold text-gray-900">{{ dashboardStats.citasPendientes }}</p>
                                <p class="text-sm text-gray-500">Citas Pendientes</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                    <BellIcon class="w-5 h-5 text-red-600" />
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-2xl font-semibold text-gray-900">{{ dashboardStats.recordatoriosHoy }}</p>
                                <p class="text-sm text-gray-500">Recordatorios Hoy</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Quick Actions -->
                    <div class="space-y-4">
                        <div v-for="action in quickActions"
                             :key="action.title"
                             class="group cursor-pointer transform transition-all duration-200 hover:scale-[1.02] hover:shadow-lg">
                            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                                <div class="p-6">
                                    <div class="flex items-center space-x-4">
                                        <div :class="[action.color, 'w-14 h-14 rounded-xl flex items-center justify-center shadow-lg transform transition-transform group-hover:scale-110']">
                                            <component :is="action.icon" class="w-7 h-7 text-white" />
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 :class="[action.textColor, 'text-lg font-semibold mb-1 group-hover:text-gray-900 transition-colors']">
                                                {{ action.title }}
                                            </h3>
                                            <p class="text-gray-500 text-sm leading-relaxed">
                                                {{ action.subtitle }}
                                            </p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 transition-colors"
                                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information & Recent Activity -->
                    <div class="space-y-6">
                        <!-- Quick Info Section -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Información Adicional</h3>
                            <div class="space-y-4">
                                <div v-for="info in additionalInfo"
                                     :key="info.title"
                                     class="group cursor-pointer transform transition-all duration-200 hover:bg-gray-50 rounded-xl p-4 -m-4">
                                    <div class="flex items-center space-x-4">
                                        <div :class="[info.color, 'w-12 h-12 rounded-lg flex items-center justify-center shadow-md']">
                                            <component :is="info.icon" class="w-6 h-6 text-white" />
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 :class="[info.textColor, 'font-medium group-hover:text-gray-900 transition-colors']">
                                                {{ info.title }}
                                            </h4>
                                            <p class="text-gray-500 text-sm mt-1">
                                                {{ info.subtitle }}
                                            </p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-600 transition-colors"
                                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tips Section -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl border border-blue-100 p-6">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-blue-900 mb-2">Consejos Importantes</h4>
                                    <ul class="text-blue-800 text-sm space-y-1">
                                        <li>• Mantén tu carné de vacunación actualizado</li>
                                        <li>• Programa tus citas con anticipación</li>
                                        <li>• Consulta el esquema de vacunación recomendado</li>
                                        <li>• No olvides las dosis de refuerzo</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-white border-t border-gray-100 mt-12">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                    <div class="text-center">
                        <p class="text-sm text-gray-500">
                            Copyright © 2025 - Política de Privacidad y Términos y Condiciones
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
/* Animaciones adicionales para mejorar la UX */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.group:hover .transform {
    animation: fadeInUp 0.3s ease-out;
}
</style>
