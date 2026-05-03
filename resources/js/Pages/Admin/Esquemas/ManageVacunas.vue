<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import {
    ArrowLeftIcon,
    BeakerIcon,
    PlusIcon,
    PencilIcon,
    TrashIcon,
    ExclamationTriangleIcon
} from '@heroicons/vue/24/outline';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';

const props = defineProps({
    esquema: Object,
    vacunas: Object,
    vacunasDisponibles: Array
});

// Estado para modales
const showAddVaccineModal = ref(false);
const showDeleteModal = ref(false);
const showDetailsModal = ref(false);
const showEditModal = ref(false);
const dosisToDelete = ref(null);
const dosisToView = ref(null);
const dosisToEdit = ref(null);

// Formulario para agregar vacuna
const addVaccineForm = useForm({
    vacuna_id: '',
    numero_dosis: '',
    edad_aplicacion: '',
    dias_despues_anterior: null,
    es_refuerzo: false,
    es_obligatoria: true,
    activo: true,
    observaciones: '',
    justificacion_medica: ''
});

const formatEdad = (meses) => {
    if (!meses && meses !== 0) return 'No especificada';

    if (meses < 12) {
        return `${meses} ${meses === 1 ? 'mes' : 'meses'}`;
    } else {
        const años = Math.floor(meses / 12);
        const mesesRestantes = meses % 12;

        if (mesesRestantes === 0) {
            return `${años} ${años === 1 ? 'año' : 'años'}`;
        } else {
            return `${años} ${años === 1 ? 'año' : 'años'} y ${mesesRestantes} ${mesesRestantes === 1 ? 'mes' : 'meses'}`;
        }
    }
};

const formatEdadAplicacion = (meses) => {
    if (!meses && meses !== 0) return 'No especificada';
    if (meses === 0) return 'Recién nacido';
    return formatEdad(meses);
};

const formatRangoEdad = (edadInicio, edadFin) => {
    if (edadInicio === null && edadFin === null) {
        return 'Sin límite de edad';
    } else if (edadInicio !== null && edadFin === null) {
        return `Desde ${formatEdad(edadInicio)}`;
    } else if (edadInicio === null && edadFin !== null) {
        return `Hasta ${formatEdad(edadFin)}`;
    } else {
        return `${formatEdad(edadInicio)} a ${formatEdad(edadFin)}`;
    }
};

const openAddVaccineModal = () => {
    addVaccineForm.reset();
    showAddVaccineModal.value = true;
};

const submitAddVaccine = () => {
    console.log('Datos del formulario antes de enviar:', addVaccineForm.data());
    addVaccineForm.post(`/admin/esquemas/${props.esquema.id}/vacunas/store`, {
        onSuccess: () => {
            showAddVaccineModal.value = false;
            addVaccineForm.reset();
        }
    });
};

const submitEditDosis = () => {
    if (dosisToEdit.value) {
        // Preparar los datos para el PATCH
        const formData = {
            vacuna_id: dosisToEdit.value.vacuna_id,
            numero_dosis: dosisToEdit.value.numero_dosis,
            edad_aplicacion: dosisToEdit.value.edad_aplicacion,
            dias_despues_anterior: dosisToEdit.value.dias_despues_anterior,
            es_refuerzo: dosisToEdit.value.es_refuerzo || false,
            es_obligatoria: dosisToEdit.value.es_obligatoria || false,
            activo: dosisToEdit.value.activo !== undefined ? dosisToEdit.value.activo : true,
            justificacion_medica: dosisToEdit.value.justificacion_medica,
            observaciones: dosisToEdit.value.observaciones
        };

        console.log('Actualizando dosis:', dosisToEdit.value.id, formData);

        router.patch(`/admin/esquemas/${props.esquema.id}/dosis/${dosisToEdit.value.id}`, formData, {
            onSuccess: () => {
                showEditModal.value = false;
                dosisToEdit.value = null;
            },
            onError: (errors) => {
                console.error('Error al actualizar:', errors);
            }
        });
    }
};

const confirmDeleteDosis = (dosis) => {
    dosisToDelete.value = dosis;
    showDeleteModal.value = true;
};

const deleteDosis = () => {
    if (dosisToDelete.value) {
        router.delete(`/admin/esquemas/${props.esquema.id}/dosis/${dosisToDelete.value.id}`, {
            onSuccess: () => {
                showDeleteModal.value = false;
                dosisToDelete.value = null;
            }
        });
    }
};

const viewDosisDetails = (dosis) => {
    dosisToView.value = dosis;
    showDetailsModal.value = true;
};

const editDosis = (dosis) => {
    dosisToEdit.value = { ...dosis };
    showEditModal.value = true;
};

const viewVacunaDetails = (nombreVacuna, dosis) => {
    // Mostrar detalles de todas las dosis de esta vacuna
    dosisToView.value = { nombreVacuna, dosis: dosis.sort((a, b) => a.numero_dosis - b.numero_dosis) };
    showDetailsModal.value = true;
};

const editVacuna = (nombreVacuna, dosis) => {
    // Funcionalidad para gestionar todas las dosis de esta vacuna
    console.log('Gestionar vacuna:', nombreVacuna, dosis);
};

const confirmDeleteVacuna = (nombreVacuna, dosis) => {
    // Confirmar eliminación de toda la vacuna (todas sus dosis)
    dosisToDelete.value = { nombreVacuna, dosis };
    showDeleteModal.value = true;
};

const availableVaccines = computed(() => {
    if (!props.vacunasDisponibles) return [];

    // Filtrar vacunas que ya están en el esquema
    const vacunasEnEsquema = Object.keys(props.vacunas || {});
    return props.vacunasDisponibles.filter(vacuna =>
        !vacunasEnEsquema.includes(vacuna.nombre)
    );
});
</script>

<template>
    <AppLayout :title="`Gestionar Vacunas - ${esquema.nombre}`">
        <Head :title="`Gestionar Vacunas - ${esquema.nombre}`" />

        <div class="min-h-screen bg-gray-50">
            <!-- Header -->
            <div class="bg-white shadow-sm border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <Link
                                href="/admin/esquemas"
                                class="inline-flex items-center text-gray-500 hover:text-gray-700"
                            >
                                <ArrowLeftIcon class="h-5 w-5 mr-2" />
                                Volver a esquemas
                            </Link>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 mt-4">
                        <div class="flex-shrink-0">
                            <BeakerIcon class="h-8 w-8 text-cyan-600" />
                        </div>
                        <div class="flex-1">
                            <h1 class="text-2xl font-bold text-gray-900">Gestionar Vacunas</h1>
                            <p class="text-sm text-gray-600">Administra las vacunas del esquema: {{ esquema.nombre }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <!-- Info del esquema -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                    <div class="px-6 py-4">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-3 space-y-2 sm:space-y-0">
                            <div class="flex-1">
                                <h2 class="text-lg sm:text-xl font-semibold text-gray-900 break-words">
                                    Esquema: {{ esquema.nombre }}
                                </h2>
                                <p class="text-xs sm:text-sm text-gray-600 mt-1 break-words">
                                    {{ esquema.grupo_edad }} - {{ formatRangoEdad(esquema.edad_inicio, esquema.edad_fin) }}
                                </p>
                            </div>
                            <div class="flex items-center space-x-2 flex-shrink-0">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium"
                                      :class="esquema.activo ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700'">
                                    {{ esquema.activo ? 'Activo' : 'Inactivo' }}
                                </span>
                                <span v-if="esquema.obligatorio" class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-orange-100 text-orange-700">
                                    Obligatorio
                                </span>
                            </div>
                        </div>
                        <div v-if="esquema.descripcion" class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg">
                            {{ esquema.descripcion }}
                        </div>
                    </div>
                </div>

                <!-- Contenido principal - Gestión de Vacunas -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                            <div class="flex items-center space-x-3">
                                <BeakerIcon class="h-6 w-6 text-cyan-600" />
                                <h3 class="text-lg font-medium text-gray-900">
                                    Vacunas del Esquema
                                </h3>
                            </div>
                            <button
                                @click="openAddVaccineModal"
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-cyan-700 bg-cyan-100 hover:bg-cyan-200 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-cyan-500 w-full sm:w-auto justify-center sm:justify-start"
                            >
                                <PlusIcon class="h-4 w-4 mr-2" />
                                Agregar Vacuna
                            </button>
                        </div>
                    </div>

                    <div class="p-4 sm:p-6">
                        <div v-if="vacunas && Object.keys(vacunas).length > 0" class="space-y-4">
                            <!-- Card horizontal por cada dosis individual -->
                            <div v-for="(dosis, nombreVacuna) in vacunas" :key="nombreVacuna">
                                <div v-for="dosisItem in dosis.sort((a, b) => a.numero_dosis - b.numero_dosis)" :key="dosisItem.id" class="bg-white rounded-lg p-4 sm:p-6 border border-gray-200 hover:border-cyan-300 hover:shadow-md transition-all duration-200">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                                        <!-- Información principal de la dosis -->
                                        <div class="flex items-start sm:items-center space-x-3 sm:space-x-4 flex-1">
                                            <div class="flex-shrink-0">
                                                <div class="h-10 w-10 sm:h-12 sm:w-12 rounded-full bg-gray-100 flex items-center justify-center shadow-sm border border-gray-200">
                                                    <svg class="h-5 w-5 sm:h-6 sm:w-6 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M7 2a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2h2v1H7a1 1 0 0 0 0 2h2v11a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2V11h2a1 1 0 1 0 0-2h-2V8h2a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zm6 2v2H7V4zM4 15a1 1 0 0 1 1-1h3v2H5a1 1 0 0 1-1-1zm16 0a1 1 0 0 1-1 1h-3v-2h3a1 1 0 0 1 1 1z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-sm sm:text-base font-semibold text-gray-900 break-words">
                                                    {{ nombreVacuna }} - Dosis {{ dosisItem.numero_dosis }}
                                                </h4>
                                                <div class="flex flex-wrap items-center gap-2 sm:gap-4 mt-1">
                                                    <span class="text-xs sm:text-sm font-medium text-cyan-600">
                                                        Edad: {{ dosisItem.edad_aplicacion !== null ? formatEdadAplicacion(dosisItem.edad_aplicacion) : 'Sin especificar' }}
                                                    </span>
                                                    <span v-if="dosisItem.dias_despues_anterior" class="text-xs sm:text-sm text-gray-600">
                                                        {{ dosisItem.dias_despues_anterior }} días después
                                                    </span>
                                                    <!-- Indicadores de estado -->
                                                    <div class="flex flex-wrap gap-1 sm:gap-2">
                                                        <span v-if="dosisItem.es_obligatoria" class="inline-flex items-center px-1.5 sm:px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                            <span class="hidden sm:inline">Obligatoria</span>
                                                            <span class="sm:hidden">Oblig.</span>
                                                        </span>
                                                        <span v-else class="inline-flex items-center px-1.5 sm:px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            <span class="hidden sm:inline">Opcional</span>
                                                            <span class="sm:hidden">Opc.</span>
                                                        </span>
                                                        <span v-if="dosisItem.es_refuerzo" class="inline-flex items-center px-1.5 sm:px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            <span class="hidden sm:inline">Refuerzo</span>
                                                            <span class="sm:hidden">Ref.</span>
                                                        </span>
                                                        <span v-else class="inline-flex items-center px-1.5 sm:px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            <span class="hidden sm:inline">Principal</span>
                                                            <span class="sm:hidden">Prin.</span>
                                                        </span>
                                                        <!-- Indicador de estado activo/inactivo - siempre visible -->
                                                        <span v-if="dosisItem.activo !== false" class="inline-flex items-center px-1.5 sm:px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                                            <span class="hidden sm:inline">Activa</span>
                                                            <span class="sm:hidden">✓</span>
                                                        </span>
                                                        <span v-else class="inline-flex items-center px-1.5 sm:px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                            <span class="hidden sm:inline">Inactiva</span>
                                                            <span class="sm:hidden">✗</span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Acciones -->
                                        <div class="flex items-center space-x-2">
                                            <button
                                                @click="viewDosisDetails(dosisItem)"
                                                class="p-2 text-cyan-600 hover:text-cyan-800 hover:bg-cyan-100 rounded-md transition-colors duration-150"
                                                title="Ver detalles completos"
                                            >
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                            <button
                                                @click="editDosis(dosisItem)"
                                                class="p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-md transition-colors duration-150"
                                                title="Editar dosis"
                                            >
                                                <PencilIcon class="h-5 w-5" />
                                            </button>
                                            <button
                                                @click="confirmDeleteDosis(dosisItem)"
                                                class="p-2 text-red-600 hover:text-red-800 hover:bg-red-100 rounded-md transition-colors duration-150"
                                                title="Eliminar dosis"
                                            >
                                                <TrashIcon class="h-5 w-5" />
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-else class="text-center py-12">
                            <BeakerIcon class="mx-auto h-12 w-12 text-gray-400" />
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Sin vacunas asignadas</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Comienza agregando vacunas a este esquema de vacunación.
                            </p>
                            <div class="mt-6">
                                <button
                                    @click="openAddVaccineModal"
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-cyan-600 hover:bg-cyan-700 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-cyan-500"
                                >
                                    <BeakerIcon class="h-4 w-4 mr-2" />
                                    Agregar Primera Vacuna
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para agregar vacuna -->
        <div v-if="showAddVaccineModal" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-screen items-center justify-center px-4 py-6">
                <div class="fixed inset-0 bg-black bg-opacity-25 transition-opacity"></div>

                <div class="relative bg-white rounded-lg shadow-xl max-w-4xl w-full">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Agregar Dosis</h3>
                        <p class="text-sm text-gray-600">Configure los detalles médicos de la nueva dosis</p>
                    </div>

                    <form @submit.prevent="submitAddVaccine">
                        <div class="px-6 py-6">
                            <!-- Primera fila: Selección de vacuna y número de dosis -->
                            <div class="mb-6">
                                <h4 class="text-sm font-medium text-gray-900 mb-4">Información de la Vacuna</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Seleccionar Vacuna *
                                        </label>
                                        <select
                                            v-model="addVaccineForm.vacuna_id"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
                                            required
                                        >
                                            <option value="">Seleccione una vacuna...</option>
                                            <option v-for="vacuna in vacunasDisponibles" :key="vacuna.id" :value="vacuna.id">
                                                {{ vacuna.nombre }} ({{ vacuna.tipo }})
                                            </option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Número de Dosis *
                                        </label>
                                        <select
                                            v-model.number="addVaccineForm.numero_dosis"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
                                            required
                                        >
                                            <option value="" disabled>Seleccione el número de dosis</option>
                                            <option v-for="n in 10" :key="n" :value="n">{{ n }}° Dosis</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Segunda fila: Campos editables en dos columnas -->
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                                <!-- Columna izquierda -->
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Edad de aplicación (meses)
                                        </label>
                                        <input
                                            v-model.number="addVaccineForm.edad_aplicacion"
                                            type="number"
                                            min="0"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
                                            placeholder="0 = recién nacido"
                                        />
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Días después de dosis anterior
                                        </label>
                                        <input
                                            v-model.number="addVaccineForm.dias_despues_anterior"
                                            type="number"
                                            min="0"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
                                            placeholder="Intervalo en días"
                                        />
                                    </div>
                                </div>

                                <!-- Columna derecha -->
                                <div class="space-y-4">
                                    <!-- Checkboxes de configuración -->
                                    <div class="space-y-3">
                                        <label class="block text-sm font-medium text-gray-700 mb-3">
                                            Configuración médica
                                        </label>

                                        <div class="flex items-center">
                                            <input
                                                id="add-es-refuerzo"
                                                v-model="addVaccineForm.es_refuerzo"
                                                type="checkbox"
                                                class="h-4 w-4 text-cyan-600 focus:ring-cyan-500 border-gray-300 rounded"
                                            />
                                            <label for="add-es-refuerzo" class="ml-2 block text-sm text-gray-900">
                                                Es dosis de refuerzo
                                            </label>
                                        </div>

                                        <div class="flex items-center">
                                            <input
                                                id="add-es-obligatoria"
                                                v-model="addVaccineForm.es_obligatoria"
                                                type="checkbox"
                                                class="h-4 w-4 text-cyan-600 focus:ring-cyan-500 border-gray-300 rounded"
                                            />
                                            <label for="add-es-obligatoria" class="ml-2 block text-sm text-gray-900">
                                                Aplicación obligatoria
                                            </label>
                                        </div>

                                        <div class="flex items-center">
                                            <input
                                                id="add-activo"
                                                v-model="addVaccineForm.activo"
                                                type="checkbox"
                                                class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                                            />
                                            <label for="add-activo" class="ml-2 block text-sm text-gray-900">
                                                Dosis activa
                                            </label>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">Si está desmarcado, la dosis estará inactiva y no será considerada en el esquema</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Tercera fila: Campos de texto largo -->
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Justificación médica
                                    </label>
                                    <textarea
                                        v-model="addVaccineForm.justificacion_medica"
                                        rows="4"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 resize-none"
                                        placeholder="Descripción médica de la necesidad de esta dosis..."
                                    ></textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Observaciones
                                    </label>
                                    <textarea
                                        v-model="addVaccineForm.observaciones"
                                        rows="4"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 resize-none"
                                        placeholder="Contraindicaciones, efectos secundarios, recomendaciones..."
                                    ></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3 rounded-b-lg">
                            <button
                                type="button"
                                @click="showAddVaccineModal = false"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-cyan-500"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                :disabled="addVaccineForm.processing"
                                class="px-4 py-2 text-sm font-medium text-white bg-cyan-600 border border-transparent rounded-md hover:bg-cyan-700 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-cyan-500"
                            >
                                {{ addVaccineForm.processing ? 'Guardando...' : 'Crear Dosis' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal para editar dosis -->
        <div v-if="showEditModal" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-screen items-center justify-center px-4 py-6">
                <div class="fixed inset-0 bg-black bg-opacity-25 transition-opacity"></div>

                <div class="relative bg-white rounded-lg shadow-xl max-w-4xl w-full">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Editar Dosis</h3>
                        <p class="text-sm text-gray-600">Modifique los detalles médicos de la dosis</p>
                    </div>

                    <form @submit.prevent="submitEditDosis">
                        <div class="px-6 py-6">
                            <!-- Primera fila: Selección de vacuna y número de dosis -->
                            <div class="mb-6">
                                <h4 class="text-sm font-medium text-gray-900 mb-4">Información de la Vacuna</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Seleccionar Vacuna *
                                        </label>
                                        <select
                                            v-model="dosisToEdit.vacuna_id"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
                                            required
                                        >
                                            <option value="">Seleccione una vacuna...</option>
                                            <option v-for="vacuna in vacunasDisponibles" :key="vacuna.id" :value="vacuna.id">
                                                {{ vacuna.nombre }} ({{ vacuna.tipo }})
                                            </option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Número de Dosis *
                                        </label>
                                        <select
                                            v-model.number="dosisToEdit.numero_dosis"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
                                            required
                                        >
                                            <option value="" disabled>Seleccione el número de dosis</option>
                                            <option v-for="n in 10" :key="n" :value="n">{{ n }}° Dosis</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Segunda fila: Campos editables en dos columnas -->
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                                <!-- Columna izquierda -->
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Edad de aplicación (meses)
                                        </label>
                                        <input
                                            v-model.number="dosisToEdit.edad_aplicacion"
                                            type="number"
                                            min="0"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
                                            placeholder="0 = recién nacido"
                                        />
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Días después de dosis anterior
                                        </label>
                                        <input
                                            v-model.number="dosisToEdit.dias_despues_anterior"
                                            type="number"
                                            min="0"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500"
                                            placeholder="Intervalo en días"
                                        />
                                    </div>
                                </div>

                                <!-- Columna derecha -->
                                <div class="space-y-4">
                                    <!-- Checkboxes de configuración -->
                                    <div class="space-y-3">
                                        <label class="block text-sm font-medium text-gray-700 mb-3">
                                            Configuración médica
                                        </label>

                                        <div class="flex items-center">
                                            <input
                                                id="edit-es-refuerzo"
                                                v-model="dosisToEdit.es_refuerzo"
                                                type="checkbox"
                                                class="h-4 w-4 text-cyan-600 focus:ring-cyan-500 border-gray-300 rounded"
                                            />
                                            <label for="edit-es-refuerzo" class="ml-2 block text-sm text-gray-900">
                                                Es dosis de refuerzo
                                            </label>
                                        </div>

                                        <div class="flex items-center">
                                            <input
                                                id="edit-es-obligatoria"
                                                v-model="dosisToEdit.es_obligatoria"
                                                type="checkbox"
                                                class="h-4 w-4 text-cyan-600 focus:ring-cyan-500 border-gray-300 rounded"
                                            />
                                            <label for="edit-es-obligatoria" class="ml-2 block text-sm text-gray-900">
                                                Aplicación obligatoria
                                            </label>
                                        </div>

                                        <div class="flex items-center">
                                            <input
                                                id="edit-activo"
                                                v-model="dosisToEdit.activo"
                                                type="checkbox"
                                                class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                                            />
                                            <label for="edit-activo" class="ml-2 block text-sm text-gray-900">
                                                Dosis activa
                                            </label>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">Si está desmarcado, la dosis estará inactiva y no será considerada en el esquema</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Tercera fila: Campos de texto largo -->
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Justificación médica
                                    </label>
                                    <textarea
                                        v-model="dosisToEdit.justificacion_medica"
                                        rows="4"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 resize-none"
                                        placeholder="Descripción médica de la necesidad de esta dosis..."
                                    ></textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Observaciones adicionales
                                    </label>
                                    <textarea
                                        v-model="dosisToEdit.observaciones"
                                        rows="4"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 resize-none"
                                        placeholder="Contraindicaciones, efectos secundarios, recomendaciones..."
                                    ></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3 rounded-b-lg">
                            <button
                                type="button"
                                @click="showEditModal = false; dosisToEdit = null"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-cyan-500"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-cyan-600 border border-transparent rounded-md hover:bg-cyan-700 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-cyan-500"
                            >
                                Actualizar Dosis
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal de confirmación para eliminar -->
        <div v-if="showDeleteModal" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-screen items-center justify-center px-4 py-6">
                <div class="fixed inset-0 bg-black bg-opacity-25 transition-opacity"></div>

                <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full">
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-red-100 rounded-lg">
                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Eliminar Dosis</h3>
                            </div>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="px-6 py-4">
                        <p class="text-gray-600">
                            ¿Estás seguro de eliminar la <strong>Dosis {{ dosisToDelete?.numero_dosis }}</strong> de
                            <strong>{{ dosisToDelete?.vacuna?.nombre }}</strong>?
                        </p>
                        <p class="text-sm text-red-600 mt-2">Esta acción no se puede deshacer.</p>
                    </div>

                    <!-- Actions -->
                    <div class="px-6 py-4 bg-gray-50 rounded-b-lg border-t border-gray-200">
                        <div class="flex justify-end space-x-3">
                            <button
                                @click="showDeleteModal = false; dosisToDelete = null"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 transition-colors"
                            >
                                Cancelar
                            </button>
                            <button
                                @click="deleteDosis"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors"
                            >
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1-1v3M4 7h16"/>
                                </svg>
                                Eliminar Dosis
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de detalles de dosis -->
        <div v-if="showDetailsModal" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-screen items-center justify-center px-4 py-6">
                <div class="fixed inset-0 bg-black bg-opacity-25 transition-opacity"></div>

                <div class="relative bg-white rounded-lg shadow-xl max-w-3xl w-full">
                    <!-- Header simplificado -->
                    <div class="px-6 py-4 border-b border-gray-200 rounded-t-lg">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-gray-100 rounded-lg">
                                    <svg class="h-6 w-6 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M7 2a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2h2v1H7a1 1 0 0 0 0 2h2v11a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2V11h2a1 1 0 1 0 0-2h-2V8h2a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zm6 2v2H7V4zM4 15a1 1 0 0 1 1-1h3v2H5a1 1 0 0 1-1-1zm16 0a1 1 0 0 1-1 1h-3v-2h3a1 1 0 0 1 1 1z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900">Detalles de la Dosis</h3>
                                    <p class="text-sm text-gray-600">{{ dosisToView?.vacuna?.nombre }} - Dosis {{ dosisToView?.numero_dosis }}</p>
                                </div>
                            </div>
                            <button
                                @click="showDetailsModal = false; dosisToView = null"
                                class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                            >
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="px-6 py-6">
                        <div v-if="dosisToView" class="space-y-6">
                            <!-- Información principal en cards -->
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                <!-- Card: Información Básica -->
                                <div class="bg-gray-50 rounded-lg border border-gray-200 p-5">
                                    <h4 class="font-semibold text-gray-900 mb-4">Información Básica</h4>
                                    <div class="space-y-3">
                                        <div class="flex justify-between">
                                            <span class="text-sm text-gray-600">Vacuna:</span>
                                            <span class="text-sm font-medium text-gray-900">{{ dosisToView.vacuna?.nombre }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-sm text-gray-600">Dosis N°:</span>
                                            <span class="text-sm font-medium text-gray-900">{{ dosisToView.numero_dosis }}</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-600">Tipo:</span>
                                            <span v-if="dosisToView.es_refuerzo" class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                Refuerzo
                                            </span>
                                            <span v-else class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                                                Principal
                                            </span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-600">Estado:</span>
                                            <span v-if="dosisToView.es_obligatoria" class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-800">
                                                Obligatoria
                                            </span>
                                            <span v-else class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Opcional
                                            </span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-600">Estado:</span>
                                            <span v-if="dosisToView.activo !== false" class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                                                Activa
                                            </span>
                                            <span v-else class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                Inactiva
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card: Programación -->
                                <div class="bg-gray-50 rounded-lg border border-gray-200 p-5">
                                    <h4 class="font-semibold text-gray-900 mb-4">Programación</h4>
                                    <div class="space-y-3">
                                        <div>
                                            <span class="text-sm text-gray-600 block mb-1">Edad de Aplicación:</span>
                                            <div class="bg-cyan-50 px-3 py-2 rounded border border-cyan-200">
                                                <span class="text-sm font-medium text-cyan-800">
                                                    {{ dosisToView.edad_aplicacion !== null ? formatEdadAplicacion(dosisToView.edad_aplicacion) : 'No especificada' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div v-if="dosisToView.dias_despues_anterior">
                                            <span class="text-sm text-gray-600 block mb-1">Intervalo:</span>
                                            <div class="bg-white px-3 py-2 rounded border">
                                                <span class="text-sm font-medium text-gray-900">{{ dosisToView.dias_despues_anterior }} días después</span>
                                            </div>
                                        </div>
                                        <div v-else>
                                            <span class="text-sm text-gray-600 block mb-1">Intervalo:</span>
                                            <div class="bg-white px-3 py-2 rounded border">
                                                <span class="text-sm text-gray-500">Sin intervalo específico</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card: Resumen -->
                                <div class="bg-gray-50 rounded-lg border border-gray-200 p-5">
                                    <h4 class="font-semibold text-gray-900 mb-4">Resumen</h4>
                                    <div class="space-y-3 text-sm">
                                        <div class="bg-white p-3 rounded border">
                                            <p class="text-gray-700 font-medium mb-2">Características:</p>
                                            <ul class="space-y-1 text-gray-600">
                                                <li>• {{ dosisToView.es_refuerzo ? 'Dosis de refuerzo' : 'Dosis principal' }}</li>
                                                <li>• {{ dosisToView.es_obligatoria ? 'Aplicación obligatoria' : 'Aplicación opcional' }}</li>
                                                <li>• {{ dosisToView.activo !== false ? 'Estado: Activa' : 'Estado: Inactiva' }}</li>
                                                <li v-if="dosisToView.dias_despues_anterior">• Intervalo de {{ dosisToView.dias_despues_anterior }} días</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sección de textos largos -->
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- Justificación Médica -->
                                <div v-if="dosisToView.justificacion_medica">
                                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-5">
                                        <h4 class="font-semibold text-gray-900 mb-3">🩺 Justificación médica</h4>
                                        <div class="bg-blue-50 p-4 rounded border border-blue-200">
                                            <p class="text-sm text-blue-900 leading-relaxed">{{ dosisToView.justificacion_medica }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Observaciones adicionales -->
                                <div v-if="dosisToView.observaciones">
                                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-5">
                                        <h4 class="font-semibold text-gray-900 mb-3">⚠️ Observaciones adicionales</h4>
                                        <div class="bg-amber-50 p-4 rounded border border-amber-200">
                                            <p class="text-sm text-amber-900 leading-relaxed">{{ dosisToView.observaciones }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 rounded-b-lg border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <div class="text-xs text-gray-500">
                                <span>Última actualización: {{ dosisToView ? new Date(dosisToView.updated_at).toLocaleDateString('es-ES') : '' }}</span>
                            </div>
                            <button
                                @click="showDetailsModal = false; dosisToView = null"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 transition-colors"
                            >
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Cerrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
