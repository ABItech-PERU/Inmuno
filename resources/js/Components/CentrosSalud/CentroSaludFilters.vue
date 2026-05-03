<script setup>
import { ref } from 'vue';
import { FunnelIcon, MagnifyingGlassIcon, XMarkIcon } from '@heroicons/vue/24/outline';
import axios from 'axios';

const props = defineProps({
    form: Object,
    departamentos: Array,
    provincias: Array,
    distritos: Array,
    hasFilters: Boolean
});

const emit = defineEmits(['search', 'clear', 'update-provincias', 'update-distritos']);

// Estado para mostrar/ocultar filtros en móvil
const showMobileFilters = ref(false);

const handleSearch = () => {
    emit('search');
};

const handleClear = () => {
    emit('clear');
};

// Cargar provincias cuando cambia el departamento
const onDepartamentoChange = async () => {
    if (props.form.departamento) {
        try {
            const response = await axios.get(`/admin/api/provincias/${props.form.departamento}`);
            emit('update-provincias', response.data);
            props.form.provincia = '';
            props.form.distrito = '';
            emit('update-distritos', []);
        } catch (error) {
            console.error('Error al cargar provincias:', error);
        }
    } else {
        emit('update-provincias', []);
        props.form.provincia = '';
        props.form.distrito = '';
        emit('update-distritos', []);
    }
    handleSearch();
};

// Cargar distritos cuando cambia la provincia
const onProvinciaChange = async () => {
    if (props.form.provincia) {
        try {
            const response = await axios.get(`/admin/api/distritos/${props.form.provincia}`);
            emit('update-distritos', response.data);
            props.form.distrito = '';
        } catch (error) {
            console.error('Error al cargar distritos:', error);
        }
    } else {
        emit('update-distritos', []);
        props.form.distrito = '';
    }
    handleSearch();
};

const onDistritoChange = () => {
    handleSearch();
};
</script>

<template>
    <div class="mb-4 sm:mb-6">
        <!-- Filtros para Desktop -->
        <div class="hidden md:block bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <!-- Fila principal de filtros -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-4">
                <!-- Campo de búsqueda -->
                <div class="lg:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Buscar
                    </label>
                    <div class="relative">
                        <input
                            v-model="form.search"
                            @keyup.enter="handleSearch"
                            type="text"
                            placeholder="Nombre o código..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none text-sm"
                        />
                        <MagnifyingGlassIcon class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" />
                    </div>
                </div>

                <!-- Tipo -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tipo
                    </label>
                    <select
                        v-model="form.tipo"
                        @change="handleSearch"
                        class="w-full py-2 px-3 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none text-sm"
                    >
                        <option value="">Todos los tipos</option>
                        <option value="Hospital">Hospital</option>
                        <option value="Centro de Salud">Centro de Salud</option>
                        <option value="Puesto de Salud">Puesto de Salud</option>
                        <option value="Clínica">Clínica</option>
                    </select>
                </div>

                <!-- Estado -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Estado
                    </label>
                    <select
                        v-model="form.activo"
                        @change="handleSearch"
                        class="w-full py-2 px-3 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none text-sm"
                    >
                        <option value="">Todos</option>
                        <option value="true">Activos</option>
                        <option value="false">Inactivos</option>
                    </select>
                </div>

                <!-- Botones -->
                <div class="flex items-end space-x-2">
                    <button
                        @click="handleSearch"
                        class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm bg-cyan-600 text-sm font-medium text-white hover:bg-cyan-700 focus:outline-none transition-colors"
                    >
                        <FunnelIcon class="h-4 w-4 mr-2" />
                        Filtrar
                    </button>
                    <button
                        v-if="hasFilters"
                        @click="handleClear"
                        class="inline-flex items-center justify-center px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none transition-colors"
                        title="Limpiar filtros"
                    >
                        <XMarkIcon class="h-4 w-4" />
                    </button>
                </div>
            </div>

            <!-- Fila de filtros de ubicación -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 pt-4 border-t border-gray-200">
                <!-- Departamento -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Departamento
                    </label>
                    <select
                        v-model="form.departamento"
                        @change="onDepartamentoChange"
                        class="w-full py-2 px-3 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none text-sm"
                    >
                        <option value="">Todos los departamentos</option>
                        <option v-for="departamento in departamentos" :key="departamento.codigo_ubigeo" :value="departamento.codigo_ubigeo">
                            {{ departamento.nombre }}
                        </option>
                    </select>
                </div>

                <!-- Provincia -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Provincia
                    </label>
                    <select
                        v-model="form.provincia"
                        @change="onProvinciaChange"
                        :disabled="!form.departamento"
                        class="w-full py-2 px-3 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none text-sm disabled:bg-gray-100"
                    >
                        <option value="">Todas las provincias</option>
                        <option v-for="provincia in provincias" :key="provincia.codigo_ubigeo" :value="provincia.codigo_ubigeo">
                            {{ provincia.nombre }}
                        </option>
                    </select>
                </div>

                <!-- Distrito -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Distrito
                    </label>
                    <select
                        v-model="form.distrito"
                        @change="onDistritoChange"
                        :disabled="!form.provincia"
                        class="w-full py-2 px-3 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none text-sm disabled:bg-gray-100"
                    >
                        <option value="">Todos los distritos</option>
                        <option v-for="distrito in distritos" :key="distrito.codigo_ubigeo" :value="distrito.codigo_ubigeo">
                            {{ distrito.nombre }}
                        </option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Filtros para Móvil -->
        <div class="md:hidden bg-white rounded-lg shadow-sm border border-gray-200 p-3 sm:p-4">
            <!-- Botón toggle y búsqueda principal -->
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-medium text-gray-900">Filtros</h3>
                <button
                    @click="showMobileFilters = !showMobileFilters"
                    class="inline-flex items-center text-sm text-cyan-600 hover:text-cyan-800 transition-colors"
                >
                    <FunnelIcon class="h-4 w-4 mr-1" />
                    {{ showMobileFilters ? 'Ocultar' : 'Mostrar' }}
                </button>
            </div>

            <!-- Búsqueda principal (siempre visible en móvil) -->
            <div class="relative mb-3">
                <input
                    v-model="form.search"
                    @keyup.enter="handleSearch"
                    type="text"
                    placeholder="Buscar centros de salud..."
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none text-sm"
                />
                <MagnifyingGlassIcon class="absolute left-3 top-3 h-5 w-5 text-gray-400" />
            </div>

            <!-- Filtros adicionales (colapsables) -->
            <div v-show="showMobileFilters" class="space-y-3">
                <!-- Tipo y Estado -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tipo
                        </label>
                        <select
                            v-model="form.tipo"
                            @change="handleSearch"
                            class="w-full py-2.5 px-3 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none text-sm"
                        >
                            <option value="">Todos</option>
                            <option value="Hospital">Hospital</option>
                            <option value="Centro de Salud">Centro de Salud</option>
                            <option value="Puesto de Salud">Puesto de Salud</option>
                            <option value="Clínica">Clínica</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Estado
                        </label>
                        <select
                            v-model="form.activo"
                            @change="handleSearch"
                            class="w-full py-2.5 px-3 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none text-sm"
                        >
                            <option value="">Todos</option>
                            <option value="true">Activos</option>
                            <option value="false">Inactivos</option>
                        </select>
                    </div>
                </div>

                <!-- Filtros de ubicación -->
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Departamento</label>
                        <select
                            v-model="form.departamento"
                            @change="onDepartamentoChange"
                            class="w-full py-2.5 px-3 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none text-sm"
                        >
                            <option value="">Todos</option>
                            <option v-for="departamento in departamentos" :key="departamento.codigo_ubigeo" :value="departamento.codigo_ubigeo">
                                {{ departamento.nombre }}
                            </option>
                        </select>
                    </div>

                    <div v-if="form.departamento">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Provincia</label>
                        <select
                            v-model="form.provincia"
                            @change="onProvinciaChange"
                            class="w-full py-2.5 px-3 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none text-sm"
                        >
                            <option value="">Todas</option>
                            <option v-for="provincia in provincias" :key="provincia.codigo_ubigeo" :value="provincia.codigo_ubigeo">
                                {{ provincia.nombre }}
                            </option>
                        </select>
                    </div>

                    <div v-if="form.provincia">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Distrito</label>
                        <select
                            v-model="form.distrito"
                            @change="onDistritoChange"
                            class="w-full py-2.5 px-3 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none text-sm"
                        >
                            <option value="">Todos</option>
                            <option v-for="distrito in distritos" :key="distrito.codigo_ubigeo" :value="distrito.codigo_ubigeo">
                                {{ distrito.nombre }}
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex flex-col xs:flex-row space-y-2 xs:space-y-0 xs:space-x-2 pt-1">
                    <button
                        @click="handleSearch"
                        class="flex-1 inline-flex items-center justify-center px-4 py-2.5 border border-transparent rounded-md shadow-sm bg-cyan-600 text-sm font-medium text-white hover:bg-cyan-700 focus:outline-none transition-colors"
                    >
                        <FunnelIcon class="h-4 w-4 mr-2" />
                        Aplicar
                    </button>
                    <button
                        v-if="hasFilters"
                        @click="handleClear"
                        class="inline-flex items-center justify-center px-4 py-2.5 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none transition-colors"
                    >
                        <XMarkIcon class="h-4 w-4 mr-2" />
                        Limpiar
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
