<script setup>
import { computed, ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import EsquemaHeader from '@/Components/Esquemas/EsquemaHeader.vue';
import EsquemaFilters from '@/Components/Esquemas/EsquemaFilters.vue';
import EsquemaList from '@/Components/Esquemas/EsquemaList.vue';
import EmptyState from '@/Components/Esquemas/EmptyState.vue';
import PaginationBar from '@/Components/Esquemas/PaginationBar.vue';
import Toast from '@/Components/Toast.vue';

const props = defineProps({
    esquemas: Object,
    grupos_edad: Array,
    filters: Object
});

const page = usePage();
const esquemaListRef = ref(null);

// Estado para notificaciones
const showToast = ref(false);
const toastType = ref('success');
const toastTitle = ref('');
const toastMessage = ref('');

// Formulario de filtros reactivo
const searchForm = useForm({
    search: props.filters.search || '',
    grupo_edad: props.filters.grupo_edad || '',
    obligatorio: props.filters.obligatorio || '',
    activo: props.filters.activo || ''
});

const hasFilters = computed(() =>
    !!(searchForm.search || searchForm.grupo_edad || searchForm.obligatorio || searchForm.activo)
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
    searchForm.get('/admin/esquemas', {
        preserveState: true,
        replace: true,
        preserveScroll: true,
    });
};

const clearFilters = () => {
    // Reset del formulario
    searchForm.reset('search', 'grupo_edad', 'obligatorio', 'activo');

    // Navegación sin filtros para resetear estado
    router.get('/admin/esquemas', {}, {
        replace: true,
        preserveState: false
    });
};

const deleteEsquema = (esquema) => {
    router.delete(`/admin/esquemas/${esquema.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            // Notificar éxito al componente hijo
            if (esquemaListRef.value) {
                esquemaListRef.value.onDeleteSuccess();
            }
            showNotification('success', 'Esquema eliminado', `El esquema "${esquema.nombre}" ha sido eliminado exitosamente.`);
        },
        onError: (errors) => {
            // Notificar error al componente hijo
            if (esquemaListRef.value) {
                esquemaListRef.value.onDeleteError();
            }
            const errorMessage = errors.message || 'Ocurrió un error al eliminar el esquema.';
            showNotification('error', 'Error al eliminar', errorMessage);
        },
    });
};

const manageVacunas = (esquema) => {
    // Redirigir a la gestión de vacunas del esquema
    router.visit(`/admin/esquemas/${esquema.id}/vacunas`);
};
</script>

<template>
    <AppLayout title="Esquemas de vacunación">
        <Head title="Esquemas de vacunación" />

        <div class="min-h-screen bg-gray-50">
            <!-- Header Component -->
            <EsquemaHeader />

            <!-- Main Content -->
            <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8 py-4 sm:py-6">
                <!-- Filtros Component -->
                <EsquemaFilters
                    :form="searchForm"
                    :grupos-edad="grupos_edad"
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
                                Esquemas de vacunación
                            </h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ esquemas.total }} total
                            </span>
                        </div>
                    </div>

                    <!-- Lista de esquemas o estado vacío -->
                    <EsquemaList
                        v-if="esquemas.data.length > 0"
                        ref="esquemaListRef"
                        :esquemas="esquemas.data"
                        @manage-vacunas="manageVacunas"
                        @delete="deleteEsquema"
                    />

                    <EmptyState
                        v-else
                        :has-filters="hasFilters"
                        @clear-filters="clearFilters"
                    />

                    <!-- Paginación -->
                    <PaginationBar
                        v-if="esquemas.data.length > 0"
                        :meta="esquemas"
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
