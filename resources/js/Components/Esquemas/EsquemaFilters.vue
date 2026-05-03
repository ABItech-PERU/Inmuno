<script setup>
import { ref } from 'vue';
import { FunnelIcon, MagnifyingGlassIcon, XMarkIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    form: Object,
    gruposEdad: Array,
    hasFilters: Boolean
});

const emit = defineEmits(['search', 'clear']);

// Estado para mostrar/ocultar filtros en móvil
const showMobileFilters = ref(false);

const handleSearch = () => {
    emit('search');
};

const handleClear = () => {
    emit('clear');
};
</script>

<template>
    <div class="mb-4 sm:mb-6">
        <!-- Filtros para Desktop -->
        <div class="hidden md:block bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
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
                            placeholder="Nombre del esquema..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none text-sm"
                        />
                        <MagnifyingGlassIcon class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" />
                    </div>
                </div>

                <!-- Grupo de Edad -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Grupo de Edad
                    </label>
                    <select
                        v-model="form.grupo_edad"
                        @change="handleSearch"
                        class="w-full py-2 px-3 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none text-sm"
                    >
                        <option value="">Todos los grupos</option>
                        <option v-for="grupo in gruposEdad" :key="grupo" :value="grupo">
                            {{ grupo }}
                        </option>
                    </select>
                </div>

                <!-- Obligatorio -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Obligatorio
                    </label>
                    <select
                        v-model="form.obligatorio"
                        @change="handleSearch"
                        class="w-full py-2 px-3 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none text-sm"
                    >
                        <option value="">Todos</option>
                        <option value="true">Obligatorios</option>
                        <option value="false">Opcionales</option>
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
                    placeholder="Buscar esquemas..."
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none text-sm"
                />
                <MagnifyingGlassIcon class="absolute left-3 top-3 h-5 w-5 text-gray-400" />
            </div>

            <!-- Filtros adicionales (colapsables) -->
            <div v-show="showMobileFilters" class="space-y-3">
                <!-- Grupo de Edad -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Grupo de Edad
                    </label>
                    <select
                        v-model="form.grupo_edad"
                        @change="handleSearch"
                        class="w-full py-2.5 px-3 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none text-sm"
                    >
                        <option value="">Todos los grupos</option>
                        <option v-for="grupo in gruposEdad" :key="grupo" :value="grupo">
                            {{ grupo }}
                        </option>
                    </select>
                </div>

                <!-- Obligatorio -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Obligatorio
                    </label>
                    <select
                        v-model="form.obligatorio"
                        @change="handleSearch"
                        class="w-full py-2.5 px-3 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none text-sm"
                    >
                        <option value="">Todos</option>
                        <option value="true">Obligatorios</option>
                        <option value="false">Opcionales</option>
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
                        class="w-full py-2.5 px-3 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none text-sm"
                    >
                        <option value="">Todos</option>
                        <option value="true">Activos</option>
                        <option value="false">Inactivos</option>
                    </select>
                </div>
            </div>

            <!-- Botones de acción (móvil) -->
            <div class="flex items-center space-x-2 pt-3 border-t border-gray-200">
                <button
                    @click="handleSearch"
                    class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm bg-cyan-600 text-sm font-medium text-white hover:bg-cyan-700 focus:outline-none transition-colors"
                >
                    <FunnelIcon class="h-4 w-4 mr-2" />
                    Aplicar Filtros
                </button>
                <button
                    v-if="hasFilters"
                    @click="handleClear"
                    class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none transition-colors"
                >
                    <XMarkIcon class="h-4 w-4 mr-1" />
                    Limpiar
                </button>
            </div>
        </div>
    </div>
</template>
