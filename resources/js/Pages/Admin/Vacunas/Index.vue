<script setup>
import { computed, ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import VaccineHeader from '@/Components/Vacunas/VaccineHeader.vue';
import VaccineFilters from '@/Components/Vacunas/VaccineFilters.vue';
import VaccineList from '@/Components/Vacunas/VaccineList.vue';
import EmptyState from '@/Components/Vacunas/EmptyState.vue';
import PaginationBar from '@/Components/Vacunas/PaginationBar.vue';
import Toast from '@/Components/Toast.vue';

const props = defineProps({
    vacunas: Object,
    tipos: Array,
    filters: Object
});

const page = usePage();
const vaccineListRef = ref(null);

// Estado para notificaciones
const showToast = ref(false);
const toastType = ref('success');
const toastTitle = ref('');
const toastMessage = ref('');

// Formulario de filtros reactivo
const searchForm = useForm({
    search: props.filters.search || '',
    tipo: props.filters.tipo || '',
    activa: props.filters.activa || ''
});

const hasFilters = computed(() =>
    !!(searchForm.search || searchForm.tipo || searchForm.activa)
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
    }
};

// Verificar mensajes flash al cargar el componente
checkFlashMessages();

const search = () => {
    searchForm.get('/admin/vacunas', {
        preserveState: true,
        replace: true,
        preserveScroll: true,
    });
};

const clearFilters = () => {
    // Reset del formulario
    searchForm.reset('search', 'tipo', 'activa');

    // Navegación sin filtros para resetear estado
    router.get('/admin/vacunas', {}, {
        replace: true,
        preserveState: false
    });
};

const deleteVacuna = (vacuna) => {
    router.delete(`/admin/vacunas/${vacuna.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            // Notificar éxito al componente hijo
            if (vaccineListRef.value) {
                vaccineListRef.value.onDeleteSuccess();
            }
            showNotification('success', 'Vacuna eliminada', `La vacuna "${vacuna.nombre}" ha sido eliminada exitosamente.`);
        },
        onError: (errors) => {
            // Notificar error al componente hijo
            if (vaccineListRef.value) {
                vaccineListRef.value.onDeleteError();
            }
            const errorMessage = errors.message || 'Ocurrió un error al eliminar la vacuna.';
            showNotification('error', 'Error al eliminar', errorMessage);
        },
    });
};

const toggleStatus = (vacuna) => {
    const accion = vacuna.activa ? 'desactivar' : 'activar';

    router.patch(`/admin/vacunas/${vacuna.id}/toggle-status`, {}, {
        preserveScroll: true,
        onSuccess: () => {
            showNotification('success', 'Estado actualizado', `La vacuna "${vacuna.nombre}" ha sido ${accion}da exitosamente.`);
        },
        onError: (errors) => {
            const errorMessage = errors.message || `Ocurrió un error al ${accion} la vacuna.`;
            showNotification('error', 'Error al actualizar', errorMessage);
        }
    });
};
</script>

<template>
    <AppLayout title="Gestión de Vacunas">
        <Head title="Gestión de Vacunas" />

        <div class="min-h-screen bg-gray-50">
            <!-- Header Component -->
            <VaccineHeader />

            <!-- Main Content -->
            <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8 py-4 sm:py-6">
                <!-- Filtros Component -->
                <VaccineFilters
                    :form="searchForm"
                    :tipos="tipos"
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
                                Vacunas Registradas
                            </h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ vacunas.total }} total
                            </span>
                        </div>
                    </div>

                    <!-- Lista de vacunas o estado vacío -->
                    <VaccineList
                        v-if="vacunas.data.length > 0"
                        ref="vaccineListRef"
                        :vacunas="vacunas.data"
                        @toggle="toggleStatus"
                        @delete="deleteVacuna"
                    />

                    <EmptyState
                        v-else
                        :has-filters="hasFilters"
                        @clear-filters="clearFilters"
                    />

                    <!-- Paginación -->
                    <PaginationBar
                        v-if="vacunas.data.length > 0"
                        :meta="vacunas"
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
