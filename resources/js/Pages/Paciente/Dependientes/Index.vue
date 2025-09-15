<script setup>
import { computed, ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import DependienteHeader from '@/Components/Dependientes/DependienteHeader.vue';
import DependienteFilters from '@/Components/Dependientes/DependienteFilters.vue';
import DependienteList from '@/Components/Dependientes/DependienteList.vue';
import EmptyState from '@/Components/Dependientes/EmptyState.vue';
import PaginationBar from '@/Components/Dependientes/PaginationBar.vue';
import Toast from '@/Components/Toast.vue';

const props = defineProps({
    dependientes: Object,
    estadisticas: Object,
    parentescos: Object,
    filtros: Object
});

const page = usePage();
const dependienteListRef = ref(null);

// Estado para notificaciones
const showToast = ref(false);
const toastType = ref('success');
const toastTitle = ref('');
const toastMessage = ref('');

// Formulario de filtros reactivo
const searchForm = useForm({
    busqueda: props.filtros.busqueda || '',
    parentesco: props.filtros.parentesco || '',
    estado: props.filtros.estado || 'todos'
});

const hasFilters = computed(() =>
    !!(searchForm.busqueda || searchForm.parentesco || (searchForm.estado && searchForm.estado !== 'todos'))
);

// Función para mostrar notificaciones
const showNotification = (type, title, message = '') => {
    toastType.value = type;
    toastTitle.value = title;
    toastMessage.value = message;
    showToast.value = true;
};

// Verificar si hay mensaje flash en la respuesta
const checkFlashMessages = () => {
    const flashData = page.props.flash;
    if (flashData?.message) {
        showNotification('success', 'Éxito', flashData.message);
    } else if (flashData?.error) {
        showNotification('error', 'Error', flashData.error);
    } else if (flashData?.success) {
        showNotification('success', 'Éxito', flashData.success);
    }
};

// Verificar mensajes flash al cargar el componente
checkFlashMessages();

const search = () => {
    searchForm.get('/paciente/dependientes', {
        preserveState: true,
        replace: true,
        preserveScroll: true,
    });
};

const clearFilters = () => {
    // Reset del formulario
    searchForm.reset('busqueda', 'parentesco', 'estado');
    searchForm.estado = 'todos';

    // Navegación sin filtros para resetear estado
    router.get('/paciente/dependientes', {}, {
        replace: true,
        preserveState: false
    });
};

const deleteDependiente = (dependiente) => {
    router.delete(`/paciente/dependientes/${dependiente.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            // Notificar éxito al componente hijo
            if (dependienteListRef.value) {
                dependienteListRef.value.onDeleteSuccess();
            }
            showNotification('success', 'Dependiente eliminado', `${dependiente.nombres} ${dependiente.apellidos} ha sido eliminado exitosamente.`);
        },
        onError: (errors) => {
            // Notificar error al componente hijo
            if (dependienteListRef.value) {
                dependienteListRef.value.onDeleteError();
            }
            const errorMessage = errors.message || 'Ocurrió un error al eliminar el dependiente.';
            showNotification('error', 'Error al eliminar', errorMessage);
        },
    });
};
</script>

<template>
    <AppLayout title="Mis dependientes">
        <Head title="Mis dependientes" />

        <div class="min-h-screen bg-gray-50">
            <!-- Header Component -->
            <DependienteHeader />

            <!-- Main Content -->
            <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8 py-4 sm:py-6">
                <!-- Estadísticas rápidas -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-cyan-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 515.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-medium text-gray-500">Total</p>
                                <p class="text-lg font-semibold text-gray-900">{{ estadisticas.total_dependientes }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-medium text-gray-500">Activos</p>
                                <p class="text-lg font-semibold text-gray-900">{{ estadisticas.activos }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-medium text-gray-500">Inactivos</p>
                                <p class="text-lg font-semibold text-gray-900">{{ estadisticas.inactivos }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-medium text-gray-500">Menores</p>
                                <p class="text-lg font-semibold text-gray-900">{{ estadisticas.menores }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filtros Component -->
                <DependienteFilters
                    :form="searchForm"
                    :parentescos="parentescos"
                    :has-filters="hasFilters"
                    @search="search"
                    @clear="clearFilters"
                />

                <!-- Lista/Tabla Component -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <!-- Header con contador -->
                    <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-base sm:text-lg font-medium text-gray-900">
                                Dependientes registrados
                            </h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ dependientes.total }} total
                            </span>
                        </div>
                    </div>

                    <!-- Lista de dependientes o estado vacío -->
                    <DependienteList
                        v-if="dependientes.data.length > 0"
                        ref="dependienteListRef"
                        :dependientes="dependientes.data"
                        :parentescos="parentescos"
                        @delete="deleteDependiente"
                    />

                    <EmptyState
                        v-else
                        :has-filters="hasFilters"
                        @clear-filters="clearFilters"
                    />

                    <!-- Paginación -->
                    <PaginationBar
                        v-if="dependientes.data.length > 0"
                        :meta="dependientes"
                    />
                </div>
            </div>
        </div>

        <!-- Toast de notificaciones -->
        <Toast
            :show="showToast"
            :type="toastType"
            :title="toastTitle"
            :message="toastMessage"
            @close="showToast = false"
        />
    </AppLayout>
</template>
