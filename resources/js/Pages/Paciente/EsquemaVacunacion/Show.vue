<template>
    <AppLayout title="Esquema de Vacunación Detallado">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header con información de la persona -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="h-16 w-16 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center">
                                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h1 class="text-2xl font-bold text-gray-900">
                                        {{ persona.nombre_completo }}
                                    </h1>
                                    <p class="text-sm text-gray-600">
                                        <span v-if="persona.es_usuario" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Usuario Principal
                                        </span>
                                        <span v-else class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Dependiente
                                        </span>
                                        · {{ persona.edad_años }} años
                                        <span v-if="persona.meses_restantes > 0">
                                            y {{ persona.meses_restantes }} meses
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <Link :href="route('paciente.esquema-vacunacion.index')"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                    Volver
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas generales -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center">
                                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Completado</dt>
                                        <dd class="text-lg font-medium text-gray-900">{{ estadisticas.porcentaje_completo }}%</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-green-500 flex items-center justify-center">
                                        <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Aplicadas</dt>
                                        <dd class="text-lg font-medium text-gray-900">{{ estadisticas.dosis_aplicadas }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-yellow-500 flex items-center justify-center">
                                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Pendientes</dt>
                                        <dd class="text-lg font-medium text-gray-900">{{ estadisticas.dosis_pendientes }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-indigo-500 flex items-center justify-center">
                                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Total</dt>
                                        <dd class="text-lg font-medium text-gray-900">{{ estadisticas.total_dosis }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Próxima vacuna -->
                <div v-if="estadisticas.proxima_vacuna" class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-400 p-4 mb-6 rounded-r-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                <strong>Próxima vacuna:</strong> {{ estadisticas.proxima_vacuna.dosis.vacuna.nombre }} - Dosis {{ estadisticas.proxima_vacuna.dosis.numero_dosis }}
                                ({{ estadisticas.proxima_vacuna.esquema_nombre }})
                                <span v-if="estadisticas.proxima_vacuna.dias_para_aplicacion === 0" class="ml-2 text-green-600 font-semibold">¡Disponible ahora!</span>
                                <span v-else-if="estadisticas.proxima_vacuna.dias_para_aplicacion > 0" class="ml-2 text-yellow-600">
                                    En {{ estadisticas.proxima_vacuna.dias_para_aplicacion }} días
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Timeline de esquemas -->
                <div class="space-y-8">
                    <div v-for="esquema in esquemas" :key="esquema.esquema.id" class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">{{ esquema.esquema.nombre }}</h3>
                                    <p class="text-sm text-gray-500 mt-1">{{ esquema.esquema.descripcion }}</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <!-- Progress Bar -->
                                    <div class="w-32 bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" :style="`width: ${esquema.estadisticas.porcentaje_completo}%`"></div>
                                    </div>
                                    <span class="text-xs text-gray-600">{{ esquema.estadisticas.porcentaje_completo }}%</span>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <!-- Timeline de vacunas -->
                            <div class="flow-root">
                                <ul class="-mb-8">
                                    <li v-for="(dosis, index) in esquema.dosis" :key="dosis.dosis.id" class="relative">
                                        <div v-if="index !== esquema.dosis.length - 1" class="absolute top-10 left-5 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></div>

                                        <div class="relative flex space-x-3">
                                            <!-- Icono de estado -->
                                            <div v-if="dosis.aplicada" class="h-10 w-10 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <div v-else-if="dosis.puede_aplicar" class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                            <div v-else class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center ring-8 ring-white">
                                                <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>

                                            <!-- Contenido -->
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex-1">
                                                        <h4 class="text-sm font-medium text-gray-900">
                                                            {{ dosis.dosis.vacuna.nombre }} - Dosis {{ dosis.dosis.numero_dosis }}
                                                        </h4>
                                                        <p class="text-sm text-gray-500">
                                                            <span v-if="dosis.dosis.edad_aplicacion">
                                                                A partir de {{ dosis.dosis.edad_aplicacion }} meses
                                                            </span>
                                                            <span v-else>Sin edad mínima</span>
                                                        </p>

                                                        <!-- Estado de la dosis -->
                                                        <div class="mt-2 flex items-center space-x-2">
                                                            <span v-if="dosis.aplicada" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                ✅ Aplicada
                                                            </span>
                                                            <span v-else-if="dosis.puede_aplicar && dosis.dias_para_aplicacion <= 0" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                                📅 Disponible
                                                            </span>
                                                            <span v-else-if="dosis.dias_para_aplicacion > 0" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                                ⏳ En {{ dosis.dias_para_aplicacion }} días
                                                            </span>
                                                            <span v-else class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                                ⏸️ No disponible
                                                            </span>
                                                        </div>

                                                        <!-- Información de aplicación -->
                                                        <div v-if="dosis.aplicada && dosis.aplicacion" class="mt-2 text-xs text-gray-600">
                                                            <p>Aplicada el {{ formatDate(dosis.aplicacion.fecha_aplicacion) }}</p>
                                                            <p v-if="dosis.aplicacion.centro_salud">en {{ dosis.aplicacion.centro_salud.nombre }}</p>
                                                        </div>
                                                    </div>

                                                    <!-- Botón de acción -->
                                                    <div v-if="!dosis.aplicada && dosis.puede_aplicar && dosis.dias_para_aplicacion <= 0" class="flex-shrink-0">
                                                        <button @click="abrirModalAplicacion(dosis.dosis)"
                                                            class="inline-flex items-center px-3 py-2 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                                            Marcar como aplicada
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Historial de aplicaciones (si existen) -->
                <div v-if="aplicaciones_historico.length > 0" class="mt-8 bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Historial de Vacunación</h3>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div v-for="aplicacion in aplicaciones_historico" :key="aplicacion.id" class="px-6 py-4 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-gray-900">
                                        {{ aplicacion.vacuna.nombre }} - Dosis {{ aplicacion.numero_dosis }}
                                    </h4>
                                    <p class="text-sm text-gray-500">
                                        {{ formatDate(aplicacion.fecha_aplicacion) }}
                                        <span v-if="aplicacion.centro_salud"> · {{ aplicacion.centro_salud.nombre }}</span>
                                    </p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Aplicada
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para marcar como aplicada -->
        <Modal :show="showModal" @close="cerrarModal" max-width="md">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    Marcar Vacuna como Aplicada
                </h3>

                <div v-if="dosisSeleccionada" class="mb-6">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="font-medium text-blue-900">{{ dosisSeleccionada.vacuna.nombre }}</h4>
                        <p class="text-sm text-blue-700">Dosis {{ dosisSeleccionada.numero_dosis }}</p>
                    </div>
                </div>

                <form @submit.prevent="marcarComoAplicada">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fecha de aplicación</label>
                            <input type="date" v-model="form.fecha_aplicacion" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Centro de Salud</label>
                            <select v-model="form.centro_salud_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Seleccionar centro de salud</option>
                                <option v-for="centro in centrosSalud" :key="centro.id" :value="centro.id">
                                    {{ centro.nombre }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Observaciones (opcional)</label>
                            <textarea v-model="form.observaciones" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Notas adicionales sobre la aplicación..."></textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" @click="cerrarModal"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md">
                            Cancelar
                        </button>
                        <button type="submit" :disabled="processing"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md disabled:opacity-50">
                            <span v-if="processing">Guardando...</span>
                            <span v-else>Guardar</span>
                        </button>
                    </div>
                </form>
            </div>
        </Modal>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
import Modal from '@/Components/Modal.vue'

const props = defineProps({
    persona: Object,
    esquemas: Array,
    aplicaciones_historico: Array,
    estadisticas: Object,
})

const showModal = ref(false)
const dosisSeleccionada = ref(null)
const centrosSalud = ref([])
const processing = ref(false)

const form = useForm({
    dosis_id: '',
    persona_tipo: props.persona.es_usuario ? 'usuario' : 'dependiente',
    persona_id: props.persona.es_usuario ? null : props.persona.datos.id,
    fecha_aplicacion: '',
    centro_salud_id: '',
    observaciones: ''
})

const formatDate = (dateString) => {
    if (!dateString) return ''
    const date = new Date(dateString)
    return date.toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    })
}

const abrirModalAplicacion = (dosis) => {
    dosisSeleccionada.value = dosis
    form.dosis_id = dosis.id
    form.fecha_aplicacion = new Date().toISOString().split('T')[0] // Fecha actual
    showModal.value = true
}

const cerrarModal = () => {
    showModal.value = false
    dosisSeleccionada.value = null
    form.reset()
}

const cargarCentrosSalud = async () => {
    try {
        const response = await fetch(route('paciente.api.centros-salud'))
        const data = await response.json()
        centrosSalud.value = data
    } catch (error) {
        console.error('Error cargando centros de salud:', error)
    }
}

const marcarComoAplicada = () => {
    processing.value = true
    form.post(route('paciente.esquema-vacunacion.marcar-aplicada'), {
        onSuccess: () => {
            cerrarModal()
            // La página se recargará automáticamente con los nuevos datos
        },
        onError: (errors) => {
            console.error('Error:', errors)
        },
        onFinish: () => {
            processing.value = false
        }
    })
}

onMounted(() => {
    cargarCentrosSalud()
})
</script>
