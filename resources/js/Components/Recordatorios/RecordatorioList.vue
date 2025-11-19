<script setup>
import { ref } from 'vue';
import { Link } from "@inertiajs/vue3";
import { PencilIcon, EyeIcon, TrashIcon } from "@heroicons/vue/24/outline";
import ConfirmationModal from '@/Components/ConfirmationModal.vue';

const props = defineProps({
    recordatorios: {
        type: Array,
        default: () => [],
    },
});

const emit = defineEmits(["toggle-status", "delete-recordatorio", "marcar-enviado"]);

// Estado para los modales de confirmación
const showDeleteModal = ref(false);
const showEnviarModal = ref(false);
const recordatorioToDelete = ref(null);
const recordatorioToEnviar = ref(null);
const isDeleting = ref(false);
const isEnviando = ref(false);

// Métodos para manejar feedback de eliminación
const onDeleteSuccess = () => {
    isDeleting.value = false;
    showDeleteModal.value = false;
    recordatorioToDelete.value = null;
};

const onDeleteError = () => {
    isDeleting.value = false;
};

// Métodos para manejar feedback de envío
const onEnviarSuccess = () => {
    isEnviando.value = false;
    showEnviarModal.value = false;
    recordatorioToEnviar.value = null;
};

const onEnviarError = () => {
    isEnviando.value = false;
};

// Exponer métodos para el componente padre
defineExpose({
    onDeleteSuccess,
    onDeleteError,
    onEnviarSuccess,
    onEnviarError
});

// Funciones para manejar clics en botones
const handleDeleteClick = (recordatorio) => {
    recordatorioToDelete.value = recordatorio;
    showDeleteModal.value = true;
};

const handleEnviarClick = (recordatorio) => {
    recordatorioToEnviar.value = recordatorio;
    showEnviarModal.value = true;
};

const confirmDelete = () => {
    if (recordatorioToDelete.value) {
        isDeleting.value = true;
        emit('delete-recordatorio', recordatorioToDelete.value);
    }
};

const confirmEnviar = () => {
    if (recordatorioToEnviar.value) {
        isEnviando.value = true;
        emit('marcar-enviado', recordatorioToEnviar.value);
    }
};

const closeDeleteModal = () => {
    if (!isDeleting.value) {
        showDeleteModal.value = false;
        recordatorioToDelete.value = null;
    }
};

const closeEnviarModal = () => {
    if (!isEnviando.value) {
        showEnviarModal.value = false;
        recordatorioToEnviar.value = null;
    }
};

// Formatear fecha
const formatDate = (date) => {
    if (!date) return "-";
    return new Date(date).toLocaleDateString("es-ES", {
        year: "numeric",
        month: "short",
        day: "numeric",
    });
};

// Formatear hora
const formatTime = (time) => {
    if (!time) return "-";
    return time.substring(0, 5);
};

// Obtener nombre del usuario principal
const getUserName = (recordatorio) => {
    const name = recordatorio.usuario
        ? `${recordatorio.usuario.name} ${
              recordatorio.usuario.apellidos || ""
          }`.trim()
        : "-";
    const edad = getEdadUsuario(recordatorio);
    return edad ? `${name} (${edad})` : name;
};

// Obtener edad del usuario
const getEdadUsuario = (recordatorio) => {
    if (recordatorio.usuario && recordatorio.usuario.fecha_nacimiento) {
        const birthDate = new Date(recordatorio.usuario.fecha_nacimiento);
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        if (
            monthDiff < 0 ||
            (monthDiff === 0 && today.getDate() < birthDate.getDate())
        ) {
            age--;
        }
        if (age > 0) {
            return `${age} años`;
        } else {
            // Calcular en meses
            const months =
                (today.getFullYear() - birthDate.getFullYear()) * 12 +
                (today.getMonth() - birthDate.getMonth());
            if (months > 0) {
                return `${months} meses`;
            } else {
                // Si es menos de un mes, calcular días
                const days = Math.floor(
                    (today - birthDate) / (1000 * 60 * 60 * 24)
                );
                return `${days} días`;
            }
        }
    }
    return "";
};

// Obtener información del destinatario (para quién es)
const getDestinatarioInfo = (recordatorio) => {
    const edad = getEdadDestinatario(recordatorio);
    if (recordatorio.dependiente) {
        // Usar solo el nombre ya que parece incluir apellidos completos
        const depName = recordatorio.dependiente.nombre || "Sin nombre";
        return edad
            ? `Dependiente: ${depName} (${edad})`
            : `Dependiente: ${depName}`;
    }
    return "Para sí mismo";
};

// Obtener edad del destinatario
const getEdadDestinatario = (recordatorio) => {
    if (recordatorio.dependiente && recordatorio.dependiente.fecha_nacimiento) {
        const birthDate = new Date(recordatorio.dependiente.fecha_nacimiento);
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        if (
            monthDiff < 0 ||
            (monthDiff === 0 && today.getDate() < birthDate.getDate())
        ) {
            age--;
        }
        if (age > 0) {
            return `${age} años`;
        } else {
            // Calcular en meses
            const months =
                (today.getFullYear() - birthDate.getFullYear()) * 12 +
                (today.getMonth() - birthDate.getMonth());
            if (months > 0) {
                return `${months} meses`;
            } else {
                // Si es menos de un mes, calcular días
                const days = Math.floor(
                    (today - birthDate) / (1000 * 60 * 60 * 24)
                );
                return `${days} días`;
            }
        }
    }
    return "";
};

// Obtener descripción del recordatorio
const getDescription = (recordatorio) => {
    return recordatorio.titulo || recordatorio.mensaje || "Sin título";
};

// Obtener badge de estado
const getStatusBadge = (estado) => {
    const statusMap = {
        pendiente: "bg-yellow-100 text-yellow-800",
        enviado: "bg-blue-100 text-blue-800",
        leido: "bg-green-100 text-green-800",
        programado: "bg-purple-100 text-purple-800",
    };
    return statusMap[estado] || "bg-gray-100 text-gray-800";
};

// Obtener badge de tipo
const getTypeBadge = (tipo) => {
    const typeMap = {
        vacuna_proxima: "bg-cyan-100 text-cyan-800",
        cita_programada: "bg-purple-100 text-purple-800",
        refuerzo_pendiente: "bg-orange-100 text-orange-800",
        recordatorio_personal: "bg-indigo-100 text-indigo-800",
    };
    return typeMap[tipo] || "bg-gray-100 text-gray-800";
};

// Obtener texto del tipo
const getTypeText = (tipo) => {
    const typeTexts = {
        vacuna_proxima: "Vacuna Próxima",
        cita_programada: "Cita Programada",
        refuerzo_pendiente: "Refuerzo Pendiente",
        recordatorio_personal: "Recordatorio Personal",
    };
    return typeTexts[tipo] || tipo;
};

// Obtener texto del estado
const getStatusText = (estado) => {
    const statusTexts = {
        pendiente: "Pendiente",
        enviado: "Enviado",
        leido: "Leído",
        programado: "Programado",
    };
    return statusTexts[estado] || estado;
};
</script>

<template>
    <div>
        <!-- Vista de tabla para pantallas grandes (lg y superiores) -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-cyan-50 to-cyan-100">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-cyan-700 uppercase tracking-wider"
                        >
                            ID
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-cyan-700 uppercase tracking-wider"
                        >
                            Usuario / Destinatario
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-cyan-700 uppercase tracking-wider"
                        >
                            Tipo
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-cyan-700 uppercase tracking-wider"
                        >
                            Estado
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-cyan-700 uppercase tracking-wider"
                        >
                            Fecha y Hora
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-cyan-700 uppercase tracking-wider"
                        >
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr
                        v-for="recordatorio in recordatorios"
                        :key="recordatorio.id"
                        class="hover:bg-gray-50 transition-colors"
                    >
                        <!-- ID -->
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                        >
                            #{{ recordatorio.id }}
                        </td>

                        <!-- Usuario / Destinatario -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div
                                        class="h-10 w-10 rounded-full bg-cyan-100 flex items-center justify-center"
                                    >
                                        <span
                                            class="text-sm font-medium text-cyan-700"
                                        >
                                            {{
                                                recordatorio.usuario
                                                    ? recordatorio.usuario.name
                                                          .charAt(0)
                                                          .toUpperCase()
                                                    : "?"
                                            }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        {{ getUserName(recordatorio) }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ getDestinatarioInfo(recordatorio) }}
                                    </div>
                                    <div class="text-sm text-gray-400">
                                        {{ getDescription(recordatorio) }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Tipo -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                :class="getTypeBadge(recordatorio.tipo)"
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                            >
                                {{ getTypeText(recordatorio.tipo) }}
                            </span>
                        </td>

                        <!-- Estado -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                :class="getStatusBadge(recordatorio.estado)"
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                            >
                                {{ getStatusText(recordatorio.estado) }}
                            </span>
                        </td>

                        <!-- Fecha y Hora -->
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                        >
                            <div class="flex items-center">
                                <svg
                                    class="flex-shrink-0 mr-2 h-4 w-4 text-gray-400"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                    />
                                </svg>
                                {{
                                    formatDate(recordatorio.fecha_recordatorio)
                                }}
                                a las
                                {{ formatTime(recordatorio.hora_recordatorio) }}
                            </div>
                        </td>

                        <!-- Acciones -->
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm font-medium"
                        >
                            <div class="flex items-center space-x-2">
                                <!-- Ver -->
                                <Link
                                    :href="`/admin/recordatorios/${recordatorio.id}`"
                                    class="text-cyan-600 hover:text-cyan-900 transition-colors"
                                    title="Ver detalles"
                                >
                                    <EyeIcon class="h-5 w-5" />
                                </Link>

                                <!-- Editar -->
                                <Link
                                    :href="`/admin/recordatorios/${recordatorio.id}/edit`"
                                    class="text-indigo-600 hover:text-indigo-900 transition-colors"
                                    title="Editar"
                                >
                                    <PencilIcon class="h-5 w-5" />
                                </Link>

                                <!-- Toggle Status (solo para admins) -->
                                <button
                                    v-if="recordatorio.estado === 'pendiente'"
                                    @click="handleEnviarClick(recordatorio)"
                                    class="text-blue-600 hover:text-blue-900 transition-colors"
                                    title="Marcar como enviado"
                                >
                                    <svg
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"
                                        />
                                    </svg>
                                </button>

                                <!-- Eliminar -->
                                <button
                                    @click="handleDeleteClick(recordatorio)"
                                    class="text-red-600 hover:text-red-900 transition-colors"
                                    title="Eliminar"
                                >
                                    <TrashIcon class="h-5 w-5" />
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Vista de tarjetas para pantallas pequeñas (menor a lg) -->
        <div class="lg:hidden space-y-4">
            <div
                v-for="recordatorio in recordatorios"
                :key="recordatorio.id"
                class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow"
            >
                <!-- Header de la tarjeta -->
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center space-x-3 flex-1 min-w-0">
                        <div class="flex-shrink-0 h-10 w-10">
                            <div
                                class="h-10 w-10 rounded-full bg-cyan-100 flex items-center justify-center"
                            >
                                <span class="text-sm font-medium text-cyan-700">
                                    {{
                                        recordatorio.usuario
                                            ? recordatorio.usuario.name
                                                  .charAt(0)
                                                  .toUpperCase()
                                            : "?"
                                    }}
                                </span>
                            </div>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="text-sm font-medium text-gray-900 truncate">
                                {{ getUserName(recordatorio) }}
                            </div>
                            <div class="text-sm text-gray-500 truncate">
                                {{ getDestinatarioInfo(recordatorio) }}
                            </div>
                            <div class="text-sm text-gray-400 truncate">
                                {{ getDescription(recordatorio) }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fecha y Hora -->
                <div class="flex items-center text-sm text-gray-600 mb-3">
                    <svg
                        class="flex-shrink-0 mr-2 h-4 w-4 text-gray-400"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                        />
                    </svg>
                    <span class="truncate">
                        {{ formatDate(recordatorio.fecha_recordatorio) }} a las
                        {{ formatTime(recordatorio.hora_recordatorio) }}
                    </span>
                </div>

                <!-- Tipo y Estado -->
                <div class="flex flex-wrap gap-2 mb-4">
                    <span
                        :class="getTypeBadge(recordatorio.tipo)"
                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium"
                    >
                        {{ getTypeText(recordatorio.tipo) }}
                    </span>
                    <span
                        :class="getStatusBadge(recordatorio.estado)"
                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium"
                    >
                        {{ getStatusText(recordatorio.estado) }}
                    </span>
                </div>

                <!-- Acciones - Responsive con wrap -->
                <div class="flex flex-wrap items-center justify-end gap-2 pt-2 border-t border-gray-100">
                    <Link
                        :href="`/admin/recordatorios/${recordatorio.id}`"
                        class="text-cyan-600 hover:text-cyan-900 transition-colors text-sm font-medium px-2 py-1 rounded hover:bg-cyan-50"
                    >
                        Ver
                    </Link>
                    <Link
                        :href="`/admin/recordatorios/${recordatorio.id}/edit`"
                        class="text-indigo-600 hover:text-indigo-900 transition-colors text-sm font-medium px-2 py-1 rounded hover:bg-indigo-50"
                    >
                        Editar
                    </Link>
                    <button
                        v-if="recordatorio.estado === 'pendiente'"
                        @click="handleEnviarClick(recordatorio)"
                        class="text-blue-600 hover:text-blue-900 transition-colors text-sm font-medium px-2 py-1 rounded hover:bg-blue-50"
                    >
                        Enviar
                    </button>
                    <button
                        @click="handleDeleteClick(recordatorio)"
                        class="text-red-600 hover:text-red-900 transition-colors text-sm font-medium px-2 py-1 rounded hover:bg-red-50"
                    >
                        Eliminar
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal de confirmación de eliminación -->
        <ConfirmationModal
            :show="showDeleteModal"
            @close="closeDeleteModal"
            :closeable="!isDeleting"
            @confirm="confirmDelete"
            :processing="isDeleting"
            confirm-text="Eliminar"
            processing-text="Eliminando..."
        >
            <template #title>
                Eliminar Recordatorio
            </template>

            <template #content>
                <p>¿Estás seguro de que deseas eliminar el recordatorio <strong>"{{ recordatorioToDelete?.titulo }}"</strong>?</p>
                <p class="mt-2 text-xs text-gray-500">Esta acción no se puede deshacer.</p>
            </template>
        </ConfirmationModal>

        <!-- Modal de confirmación de envío -->
        <ConfirmationModal
            :show="showEnviarModal"
            @close="closeEnviarModal"
            :closeable="!isEnviando"
            @confirm="confirmEnviar"
            :processing="isEnviando"
            confirm-text="Marcar como Enviado"
            processing-text="Enviando..."
            confirm-button-class="bg-blue-600 hover:bg-blue-700 focus:ring-blue-500"
        >
            <template #title>
                Marcar como Enviado
            </template>

            <template #content>
                <p>¿Estás seguro de que deseas marcar como enviado el recordatorio <strong>"{{ recordatorioToEnviar?.titulo }}"</strong>?</p>
                <p class="mt-2 text-xs text-gray-500">El estado del recordatorio cambiará a "Enviado".</p>
            </template>
        </ConfirmationModal>
    </div>
</template>
