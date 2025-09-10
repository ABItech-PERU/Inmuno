<script setup>
import { ref, computed } from 'vue';
import { Head, router, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import CentroSaludHeader from '@/Components/CentrosSalud/CentroSaludHeader.vue';
import CentroSaludFilters from '@/Components/CentrosSalud/CentroSaludFilters.vue';
import CentroSaludList from '@/Components/CentrosSalud/CentroSaludList.vue';
import PaginationBar from '@/Components/Vacunas/PaginationBar.vue';
import Toast from '@/Components/Toast.vue';

const props = defineProps({
    centrosSalud: Object,
    filters: Object,
    departamentos: Array,
    provincias: Array,
    distritos: Array,
});

// Estado para filtros
const form = ref({
    search: props.filters.search || '',
    tipo: props.filters.tipo || '',
    departamento: props.filters.departamento || '',
    provincia: props.filters.provincia || '',
    distrito: props.filters.distrito || '',
    activo: props.filters.activo || '',
});

// Estados para ubicación
const provinciasList = ref(props.provincias || []);
const distritosList = ref(props.distritos || []);

// Referencias para componentes
const centroSaludListRef = ref(null);
const toastRef = ref(null);

// Computadas
const hasFilters = computed(() => {
    return Object.values(form.value).some(value => value && value !== '');
});

// Métodos de filtrado
const search = () => {
    router.get('/admin/centros-salud', form.value, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};

const clearFilters = () => {
    form.value = {
        search: '',
        tipo: '',
        departamento: '',
        provincia: '',
        distrito: '',
        activo: '',
    };
    provinciasList.value = [];
    distritosList.value = [];
    search();
};

// Métodos para actualizar ubicaciones
const updateProvincias = (provincias) => {
    provinciasList.value = provincias;
};

const updateDistritos = (distritos) => {
    distritosList.value = distritos;
};

// Método para eliminar centro de salud
const deleteCentroSalud = (centro) => {
    router.delete(`/admin/centros-salud/${centro.id}`, {
        preserveScroll: true,
        onSuccess: (page) => {
            centroSaludListRef.value?.onDeleteSuccess();
            toastRef.value?.show('Centro de salud eliminado exitosamente', 'success');
        },
        onError: (errors) => {
            centroSaludListRef.value?.onDeleteError();
            toastRef.value?.show('Error al eliminar el centro de salud', 'error');
        }
    });
};

// Método para cambiar estado del centro de salud
const toggleStatus = (centro) => {
    const accion = centro.activo ? 'desactivar' : 'activar';

    router.patch(`/admin/centros-salud/${centro.id}/toggle-status`, {}, {
        preserveScroll: true,
        onSuccess: () => {
            toastRef.value?.show(`Centro de salud ${centro.activo ? 'desactivado' : 'activado'} exitosamente`, 'success');
        },
        onError: (errors) => {
            const errorMessage = errors.message || `Ocurrió un error al ${accion} el centro de salud.`;
            toastRef.value?.show(errorMessage, 'error');
        },
    });
};
</script>

<template>
    <AppLayout title="Centros de Salud">
        <Head title="Centros de Salud" />

        <div class="min-h-screen bg-gray-50">
            <!-- Header -->
            <CentroSaludHeader />

            <!-- Contenido principal -->
            <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8 py-4 sm:py-6">
                <!-- Filtros -->
                <CentroSaludFilters
                    :form="form"
                    :departamentos="departamentos"
                    :provincias="provinciasList"
                    :distritos="distritosList"
                    :has-filters="hasFilters"
                    @search="search"
                    @clear="clearFilters"
                    @update-provincias="updateProvincias"
                    @update-distritos="updateDistritos"
                />

                <!-- Lista/Tabla Component -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <!-- Header con contador -->
                    <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-base sm:text-lg font-medium text-gray-900">
                                Centros de salud
                            </h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ centrosSalud.total }} total
                            </span>
                        </div>
                    </div>

                    <!-- Lista de centros de salud o estado vacío -->
                    <CentroSaludList
                        v-if="centrosSalud.data.length > 0"
                        ref="centroSaludListRef"
                        :centros-salud="centrosSalud.data"
                        @delete="deleteCentroSalud"
                        @toggle="toggleStatus"
                    />

                    <!-- Estado vacío -->
                    <div v-else class="text-center py-12">
                        <svg
                            class="mx-auto h-12 w-12 text-gray-400"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                            />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">
                            No hay centros de salud
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            {{ hasFilters ? 'No se encontraron centros de salud con los filtros aplicados.' : 'Comienza creando tu primer centro de salud.' }}
                        </p>
                        <div class="mt-6" v-if="!hasFilters">
                            <Link
                                href="/admin/centros-salud/create"
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-cyan-600 hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500"
                            >
                                Crear Centro de Salud
                            </Link>
                        </div>
                        <div class="mt-6" v-else>
                            <button
                                @click="clearFilters"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500"
                            >
                                Limpiar filtros
                            </button>
                        </div>
                    </div>

                    <!-- Paginación -->
                    <PaginationBar
                        v-if="centrosSalud.data.length > 0"
                        :meta="centrosSalud"
                    />
                </div>
            </div>
        </div>

        <!-- Toast para notificaciones -->
        <Toast ref="toastRef" />
    </AppLayout>
</template>
