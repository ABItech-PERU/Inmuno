<script setup>
import { ref, computed } from 'vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import RecordatorioHeader from '@/Components/Recordatorios/RecordatorioHeader.vue';
import RecordatorioFilters from '@/Components/Recordatorios/RecordatorioFilters.vue';
import RecordatorioList from '@/Components/Recordatorios/RecordatorioList.vue';
import EmptyState from '@/Components/Recordatorios/EmptyState.vue';
import PaginationBar from '@/Components/Recordatorios/PaginationBar.vue';
import Toast from '@/Components/Toast.vue';

const props = defineProps({
    recordatorios: Object,
    filters: Object,
    tipos: Object,
    estados: Object,
});

const page = usePage();
const recordatorioListRef = ref(null);

// Estado para notificaciones
const showToast = ref(false);
const toastType = ref('success');
const toastTitle = ref('');
const toastMessage = ref('');

// Formulario de filtros reactivo
const searchForm = useForm({
    search: props.filters.search || '',
    estado: props.filters.estado || '',
    tipo: props.filters.tipo || '',
    fecha_desde: props.filters.fecha_desde || '',
    fecha_hasta: props.filters.fecha_hasta || '',
});

const hasFilters = computed(() =>
    !!(searchForm.search || searchForm.estado || searchForm.tipo || searchForm.fecha_desde || searchForm.fecha_hasta)
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
    searchForm.get('/admin/recordatorios', {
        preserveState: true,
        replace: true,
        preserveScroll: true,
    });
};

const clearFilters = () => {
    // Reset del formulario
    searchForm.reset('search', 'estado', 'tipo', 'fecha_desde', 'fecha_hasta');

    // Navegación sin filtros para resetear estado
    router.get('/admin/recordatorios', {}, {
        replace: true,
        preserveState: false
    });
};

// Método para eliminar recordatorio
const deleteRecordatorio = (recordatorio) => {
    // Usar fetch directamente para evitar el modal de Inertia
    fetch(`/admin/recordatorios/${recordatorio.id}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest',
        },
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        return response.json();
    })
    .then(data => {
        // Notificar éxito al componente hijo
        if (recordatorioListRef.value) {
            recordatorioListRef.value.onDeleteSuccess();
        }
        showNotification('success', 'Recordatorio eliminado', data.message || `El recordatorio "${recordatorio.titulo}" ha sido eliminado exitosamente.`);

        // Recargar la página para actualizar la lista
        setTimeout(() => {
            router.reload({ preserveScroll: true });
        }, 1000);
    })
    .catch(error => {
        // Notificar error al componente hijo
        if (recordatorioListRef.value) {
            recordatorioListRef.value.onDeleteError();
        }
        const errorMessage = error.message || 'Ocurrió un error al eliminar el recordatorio.';
        showNotification('error', 'Error al eliminar', errorMessage);
    });
};

// Método para marcar como enviado
const marcarComoEnviado = (recordatorio) => {
    // Usar fetch directamente para evitar problemas con Inertia
    fetch(`/admin/recordatorios/${recordatorio.id}/marcar-enviado`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest',
        },
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        return response.json();
    })
    .then(data => {
        // Notificar éxito al componente hijo
        if (recordatorioListRef.value) {
            recordatorioListRef.value.onEnviarSuccess();
        }
        showNotification('success', 'Recordatorio actualizado', data.message || 'Recordatorio marcado como enviado exitosamente.');

        // Recargar la página para actualizar la lista
        setTimeout(() => {
            router.reload({ preserveScroll: true });
        }, 1000);
    })
    .catch(error => {
        // Notificar error al componente hijo
        if (recordatorioListRef.value) {
            recordatorioListRef.value.onEnviarError();
        }
        const errorMessage = error.message || 'Ocurrió un error al actualizar el recordatorio.';
        showNotification('error', 'Error al actualizar', errorMessage);
    });
};
</script>

<template>
    <AppLayout title="Recordatorios">
        <Head title="Recordatorios" />

        <div class="min-h-screen bg-gray-50">
            <!-- Header -->
            <RecordatorioHeader />

            <!-- Contenido principal -->
            <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8 py-4 sm:py-6">
                <!-- Filtros -->
                <RecordatorioFilters
                    :form="searchForm"
                    :tipos="tipos"
                    :estados="estados"
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
                                Recordatorios
                            </h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ recordatorios.total }} total
                            </span>
                        </div>
                    </div>

                    <!-- Lista de recordatorios o estado vacío -->
                    <RecordatorioList
                        v-if="recordatorios.data.length > 0"
                        ref="recordatorioListRef"
                        :recordatorios="recordatorios.data"
                        @delete-recordatorio="deleteRecordatorio"
                        @marcar-enviado="marcarComoEnviado"
                    />

                    <EmptyState
                        v-else
                        :has-filters="hasFilters"
                        @clear-filters="clearFilters"
                    />

                    <!-- Paginación -->
                    <PaginationBar
                        :meta="recordatorios"
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
