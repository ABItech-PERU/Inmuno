<script setup>
import { ref, computed } from "vue";
import { Head, router, useForm, usePage } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import UserHeader from "@/Components/Users/UserHeader.vue";
import UserFilters from "@/Components/Users/UserFilters.vue";
import UserList from "@/Components/Users/UserList.vue";
import EmptyState from "@/Components/Users/EmptyState.vue";
import PaginationBar from "@/Components/Users/PaginationBar.vue";
import Toast from "@/Components/Toast.vue";

const props = defineProps({
    users: Object,
    filters: Object,
    roles: Array,
});

const page = usePage();
const userListRef = ref(null);

// Estado para notificaciones
const showToast = ref(false);
const toastType = ref("success");
const toastTitle = ref("");
const toastMessage = ref("");

// Formulario de filtros reactivo
const searchForm = useForm({
    search: props.filters.search || "",
    activo: props.filters.activo || "",
    role: props.filters.role || "",
});

const hasFilters = computed(
    () => !!(searchForm.search || searchForm.activo || searchForm.role)
);

// Función para mostrar notificaciones
const showNotification = (type, title, message = "") => {
    toastType.value = type;
    toastTitle.value = title;
    toastMessage.value = message;
    showToast.value = true;
};

// Verificar si hay mensaje flash en la respuesta
const checkFlashMessages = () => {
    const flashData = page.props.flash;
    if (flashData?.message) {
        showNotification("success", "Éxito", flashData.message);
    } else if (flashData?.error) {
        showNotification("error", "Error", flashData.error);
    }
};

// Verificar mensajes flash al cargar el componente
checkFlashMessages();

const search = () => {
    searchForm.get("/admin/users", {
        preserveState: true,
        replace: true,
        preserveScroll: true,
    });
};

const clearFilters = () => {
    // Reset del formulario
    searchForm.reset("search", "activo", "role");

    // Navegación sin filtros para resetear estado
    router.get(
        "/admin/users",
        {},
        {
            replace: true,
            preserveState: false,
        }
    );
};

// Método para eliminar usuario
const deleteUser = (user) => {
    router.delete(`/admin/users/${user.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            // Notificar éxito al componente hijo
            if (userListRef.value) {
                userListRef.value.onDeleteSuccess();
            }
            showNotification(
                "success",
                "Usuario eliminado",
                `El usuario "${user.name} ${user.apellidos}" ha sido eliminado exitosamente.`
            );
        },
        onError: (errors) => {
            // Notificar error al componente hijo
            if (userListRef.value) {
                userListRef.value.onDeleteError();
            }
            const errorMessage =
                errors.message || "Ocurrió un error al eliminar el usuario.";
            showNotification("error", "Error al eliminar", errorMessage);
        },
    });
};

// Método para cambiar estado del usuario
const toggleStatus = (user) => {
    const accion = user.estado ? "desactivar" : "activar";

    router.patch(
        `/admin/users/${user.id}/toggle-status`,
        {},
        {
            preserveScroll: true,
            onSuccess: () => {
                showNotification(
                    "success",
                    "Estado actualizado",
                    `Usuario ${
                        user.estado ? "desactivado" : "activado"
                    } exitosamente.`
                );
            },
            onError: (errors) => {
                const errorMessage =
                    errors.message ||
                    `Ocurrió un error al ${accion} el usuario.`;
                showNotification(
                    "error",
                    "Error al cambiar estado",
                    errorMessage
                );
            },
        }
    );
};
</script>

<template>
    <AppLayout title="Usuarios">
        <Head title="Usuarios" />

        <div class="min-h-screen bg-gray-50">
            <!-- Header -->
            <UserHeader />

            <!-- Contenido principal -->
            <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8 py-4 sm:py-6">
                <!-- Filtros -->
                <UserFilters
                    :form="searchForm"
                    :roles="roles"
                    :has-filters="hasFilters"
                    @search="search"
                    @clear="clearFilters"
                />

                <!-- Lista/Tabla Component -->
                <div
                    class="bg-white rounded-lg shadow-sm border border-gray-200"
                >
                    <!-- Header con contador -->
                    <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3
                                class="text-base sm:text-lg font-medium text-gray-900"
                            >
                                Usuarios
                            </h3>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800"
                            >
                                {{ users.total }} total
                            </span>
                        </div>
                    </div>

                    <!-- Lista de usuarios o estado vacío -->
                    <UserList
                        v-if="users.data.length > 0"
                        ref="userListRef"
                        :users="users.data"
                        @delete="deleteUser"
                        @toggle="toggleStatus"
                    />

                    <EmptyState
                        v-else
                        :has-filters="hasFilters"
                        @clear-filters="clearFilters"
                    />

                    <!-- Paginación -->
                    <PaginationBar :meta="users" />
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
