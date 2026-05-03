<script setup>
import { ref, reactive } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { BuildingOffice2Icon, ArrowLeftIcon } from '@heroicons/vue/24/outline';
import axios from 'axios';
import Toast from '@/Components/Toast.vue';

const props = defineProps({
    departamentos: Array,
});

// Estados para ubicación
const provincias = ref([]);
const distritos = ref([]);

// Referencias
const toastRef = ref(null);

// Formulario
const form = useForm({
    nombre: '',
    codigo: '',
    tipo: '',
    nivel: '',
    direccion: '',
    telefono: '',
    email: '',
    distrito_codigo: '',
    latitud: '',
    longitud: '',
    capacidad_atencion: '',
    horario_inicio: '',
    horario_fin: '',
    servicios_disponibles: [],
    director_medico: '',
    activo: true,
});

// Estados para los selects
const selectedDepartamento = ref('');
const selectedProvincia = ref('');

// Opciones
const tipos = [
    { value: 'Hospital', label: 'Hospital' },
    { value: 'Centro de Salud', label: 'Centro de Salud' },
    { value: 'Puesto de Salud', label: 'Puesto de Salud' },
    { value: 'Clínica', label: 'Clínica' },
];

const niveles = [
    { value: 'I-1', label: 'I-1' },
    { value: 'I-2', label: 'I-2' },
    { value: 'I-3', label: 'I-3' },
    { value: 'I-4', label: 'I-4' },
    { value: 'II-1', label: 'II-1' },
    { value: 'II-2', label: 'II-2' },
    { value: 'II-E', label: 'II-E' },
    { value: 'III-1', label: 'III-1' },
    { value: 'III-2', label: 'III-2' },
    { value: 'III-E', label: 'III-E' },
];

const serviciosDisponibles = [
    'Medicina General',
    'Pediatría',
    'Ginecología',
    'Obstetricia',
    'Emergencias',
    'Laboratorio',
    'Rayos X',
    'Farmacia',
    'Vacunación',
    'Planificación Familiar',
    'Salud Mental',
    'Odontología',
    'Nutrición',
    'Fisioterapia',
];

// Métodos para cargar ubicaciones
const onDepartamentoChange = async () => {
    if (selectedDepartamento.value) {
        try {
            const response = await axios.get(`/admin/api/provincias/${selectedDepartamento.value}`);
            provincias.value = response.data;
            selectedProvincia.value = '';
            form.distrito_codigo = '';
            distritos.value = [];
        } catch (error) {
            console.error('Error al cargar provincias:', error);
            toastRef.value?.show('Error al cargar las provincias', 'error');
        }
    } else {
        provincias.value = [];
        selectedProvincia.value = '';
        form.distrito_codigo = '';
        distritos.value = [];
    }
};

const onProvinciaChange = async () => {
    if (selectedProvincia.value) {
        try {
            const response = await axios.get(`/admin/api/distritos/${selectedProvincia.value}`);
            distritos.value = response.data;
            form.distrito_codigo = '';
        } catch (error) {
            console.error('Error al cargar distritos:', error);
            toastRef.value?.show('Error al cargar los distritos', 'error');
        }
    } else {
        distritos.value = [];
        form.distrito_codigo = '';
    }
};

// Método para manejar servicios
const toggleServicio = (servicio) => {
    const index = form.servicios_disponibles.indexOf(servicio);
    if (index > -1) {
        form.servicios_disponibles.splice(index, 1);
    } else {
        form.servicios_disponibles.push(servicio);
    }
};

// Enviar formulario
const submit = () => {
    form.post('/admin/centros-salud', {
        onSuccess: () => {
            toastRef.value?.show('Centro de salud creado exitosamente', 'success');
        },
        onError: (errors) => {
            toastRef.value?.show('Error al crear el centro de salud', 'error');
        }
    });
};
</script>

<template>
    <AppLayout title="Crear Centro de Salud">

        <Head title="Crear Centro de Salud" />

        <div class="min-h-screen bg-gray-50">
            <!-- Header -->
            <div class="bg-white shadow-sm border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <Link href="/admin/centros-salud"
                                class="inline-flex items-center text-gray-500 hover:text-gray-700">
                            <ArrowLeftIcon class="h-5 w-5 mr-2" />
                            Volver a centros de salud
                            </Link>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 mt-4">
                        <div class="flex-shrink-0">
                            <BuildingOffice2Icon class="h-8 w-8 text-cyan-600" />
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Nuevo Centro de Salud</h1>
                            <p class="text-sm text-gray-600">Agrega un nuevo centro de salud al sistema</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulario -->
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Información básica -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Información Básica</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nombre *
                                </label>
                                <input v-model="form.nombre" type="text"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none"
                                    :class="{ 'border-red-500': form.errors.nombre }"
                                    placeholder="Hospital Nacional Dos de Mayo" />
                                <p v-if="form.errors.nombre" class="mt-1 text-sm text-red-600">{{ form.errors.nombre }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Código *
                                </label>
                                <input v-model="form.codigo" type="text"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none"
                                    :class="{ 'border-red-500': form.errors.codigo }" placeholder="HNDDM001" />
                                <p v-if="form.errors.codigo" class="mt-1 text-sm text-red-600">{{ form.errors.codigo }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tipo *
                                </label>
                                <select v-model="form.tipo"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none"
                                    :class="{ 'border-red-500': form.errors.tipo }">
                                    <option value="">Seleccionar tipo</option>
                                    <option v-for="tipo in tipos" :key="tipo.value" :value="tipo.value">
                                        {{ tipo.label }}
                                    </option>
                                </select>
                                <p v-if="form.errors.tipo" class="mt-1 text-sm text-red-600">{{ form.errors.tipo }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nivel *
                                </label>
                                <select v-model="form.nivel"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none"
                                    :class="{ 'border-red-500': form.errors.nivel }">
                                    <option value="">Seleccionar nivel</option>
                                    <option v-for="nivel in niveles" :key="nivel.value" :value="nivel.value">
                                        {{ nivel.label }}
                                    </option>
                                </select>
                                <p v-if="form.errors.nivel" class="mt-1 text-sm text-red-600">{{ form.errors.nivel }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Director Médico
                                </label>
                                <input v-model="form.director_medico" type="text"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none"
                                    :class="{ 'border-red-500': form.errors.director_medico }"
                                    placeholder="Dr. Juan Pérez García" />
                                <p v-if="form.errors.director_medico" class="mt-1 text-sm text-red-600">{{
                                    form.errors.director_medico }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Capacidad de Atención
                                </label>
                                <input v-model="form.capacidad_atencion" type="number" min="1"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none"
                                    :class="{ 'border-red-500': form.errors.capacidad_atencion }" placeholder="100" />
                                <p v-if="form.errors.capacidad_atencion" class="mt-1 text-sm text-red-600">{{
                                    form.errors.capacidad_atencion }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Ubicación -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Ubicación</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Departamento *
                                </label>
                                <select v-model="selectedDepartamento" @change="onDepartamentoChange"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none">
                                    <option value="">Seleccionar departamento</option>
                                    <option v-for="departamento in departamentos" :key="departamento.codigo_ubigeo"
                                        :value="departamento.codigo_ubigeo">
                                        {{ departamento.nombre }}
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Provincia *
                                </label>
                                <select v-model="selectedProvincia" @change="onProvinciaChange"
                                    :disabled="!selectedDepartamento"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none disabled:bg-gray-100">
                                    <option value="">Seleccionar provincia</option>
                                    <option v-for="provincia in provincias" :key="provincia.codigo_ubigeo"
                                        :value="provincia.codigo_ubigeo">
                                        {{ provincia.nombre }}
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Distrito *
                                </label>
                                <select v-model="form.distrito_codigo" :disabled="!selectedProvincia"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none disabled:bg-gray-100"
                                    :class="{ 'border-red-500': form.errors.distrito_codigo }">
                                    <option value="">Seleccionar distrito</option>
                                    <option v-for="distrito in distritos" :key="distrito.codigo_ubigeo"
                                        :value="distrito.codigo_ubigeo">
                                        {{ distrito.nombre }}
                                    </option>
                                </select>
                                <p v-if="form.errors.distrito_codigo" class="mt-1 text-sm text-red-600">{{
                                    form.errors.distrito_codigo }}</p>
                            </div>
                        </div>

                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Dirección *
                            </label>
                            <textarea v-model="form.direccion" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none"
                                :class="{ 'border-red-500': form.errors.direccion }"
                                placeholder="Av. Grau 13, Cercado de Lima"></textarea>
                            <p v-if="form.errors.direccion" class="mt-1 text-sm text-red-600">{{ form.errors.direccion
                                }}</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Latitud
                                </label>
                                <input v-model="form.latitud" type="number" step="any"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none"
                                    :class="{ 'border-red-500': form.errors.latitud }" placeholder="-12.0464" />
                                <p v-if="form.errors.latitud" class="mt-1 text-sm text-red-600">{{ form.errors.latitud
                                    }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Longitud
                                </label>
                                <input v-model="form.longitud" type="number" step="any"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none"
                                    :class="{ 'border-red-500': form.errors.longitud }" placeholder="-77.0428" />
                                <p v-if="form.errors.longitud" class="mt-1 text-sm text-red-600">{{ form.errors.longitud
                                    }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Contacto -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Información de Contacto y Horarios</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Teléfono
                                </label>
                                <input v-model="form.telefono" type="text"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none"
                                    :class="{ 'border-red-500': form.errors.telefono }" placeholder="01-4265566" />
                                <p v-if="form.errors.telefono" class="mt-1 text-sm text-red-600">{{ form.errors.telefono
                                    }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Email
                                </label>
                                <input v-model="form.email" type="email"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none"
                                    :class="{ 'border-red-500': form.errors.email }"
                                    placeholder="contacto@hospital.gob.pe" />
                                <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}
                                </p>
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Horario de Inicio
                                </label>
                                <input v-model="form.horario_inicio" type="time"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none"
                                    :class="{ 'border-red-500': form.errors.horario_inicio }" />
                                <p v-if="form.errors.horario_inicio" class="mt-1 text-sm text-red-600">{{ form.errors.horario_inicio }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Horario de Fin
                                </label>
                                <input v-model="form.horario_fin" type="time"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none"
                                    :class="{ 'border-red-500': form.errors.horario_fin }" />
                                <p v-if="form.errors.horario_fin" class="mt-1 text-sm text-red-600">{{ form.errors.horario_fin }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Servicios -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Servicios Disponibles</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                            <label v-for="servicio in serviciosDisponibles" :key="servicio"
                                class="relative flex items-start py-2 px-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" :checked="form.servicios_disponibles.includes(servicio)"
                                    @change="toggleServicio(servicio)" class="sr-only" />
                                <div class="flex items-center h-5">
                                    <div :class="[
                                        'w-4 h-4 rounded border border-gray-300 flex items-center justify-center',
                                        form.servicios_disponibles.includes(servicio)
                                            ? 'bg-cyan-600 border-cyan-600'
                                            : 'bg-white'
                                    ]">
                                        <svg v-if="form.servicios_disponibles.includes(servicio)"
                                            class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <span class="text-sm text-gray-700">{{ servicio }}</span>
                                </div>
                            </label>
                        </div>
                        <p v-if="form.errors.servicios_disponibles" class="mt-3 text-sm text-red-600">{{
                            form.errors.servicios_disponibles }}</p>
                    </div>

                    <!-- Estado -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-6">Estado</h3>

                        <div class="flex items-center">
                            <input id="activa" v-model="form.activo" type="checkbox"
                                class="h-4 w-4 text-indigo-600 focus:ring-cyan-500 border-gray-300 rounded">
                            <label for="activa" class="ml-2 block text-sm text-gray-900">
                                Centro de salud activa
                            </label>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">
                            Los centros de salud inactivos no aparecerán en las búsquedas ni en los listados.
                        </p>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end space-x-3 pt-6">
                        <Link href="/admin/centros-salud"
                            class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500">
                        Cancelar
                        </Link>
                        <button type="submit" :disabled="form.processing"
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-cyan-600 hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 disabled:opacity-50">
                            <span v-if="form.processing">Creando...</span>
                            <span v-else>Crear Centro de Salud</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Toast para notificaciones -->
        <Toast ref="toastRef" />
    </AppLayout>
</template>
