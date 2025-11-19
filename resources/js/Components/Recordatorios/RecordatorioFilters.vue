<script setup>
import { ref } from 'vue';
import { FunnelIcon, MagnifyingGlassIcon, XMarkIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    form: Object,
    tipos: Object,
    estados: Object,
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
            <div class="grid grid-cols-1 lg:grid-cols-6 gap-4">
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
                            placeholder="Nombre, apellidos, email..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none text-sm"
                        />
                        <MagnifyingGlassIcon class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" />
                    </div>
                </div>

                <!-- Estado -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Estado
                    </label>
                    <select
                        v-model="form.estado"
                        @change="handleSearch"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none text-sm"
                    >
                        <option value="">Todos</option>
                        <option v-for="(label, value) in estados" :key="value" :value="value">
                            {{ label }}
                        </option>
                    </select>
                </div>

                <!-- Tipo -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tipo
                    </label>
                    <select
                        v-model="form.tipo"
                        @change="handleSearch"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none text-sm"
                    >
                        <option value="">Todos</option>
                        <option v-for="(label, value) in tipos" :key="value" :value="value">
                            {{ label }}
                        </option>
                    </select>
                </div>

                <!-- Fecha desde -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Desde
                    </label>
                    <input
                        v-model="form.fecha_desde"
                        @change="handleSearch"
                        type="date"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none text-sm"
                    />
                </div>

                <!-- Fecha hasta -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Hasta
                    </label>
                    <input
                        v-model="form.fecha_hasta"
                        @change="handleSearch"
                        type="date"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none text-sm"
                    />
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
        <div class="md:hidden">
            <button
                @click="showMobileFilters = !showMobileFilters"
                class="w-full flex items-center justify-center px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500"
            >
                <FunnelIcon class="h-5 w-5 mr-2" />
                Filtros
                <svg
                    :class="showMobileFilters ? 'rotate-180' : ''"
                    class="ml-2 h-5 w-5 text-gray-400 transition-transform"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div v-show="showMobileFilters" class="mt-3 bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="space-y-4">
                    <!-- Campo de búsqueda -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Buscar
                        </label>
                        <div class="relative">
                            <input
                                v-model="form.search"
                                @keyup.enter="handleSearch"
                                type="text"
                                placeholder="Nombre, apellidos, email..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none text-sm"
                            />
                            <MagnifyingGlassIcon class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" />
                        </div>
                    </div>

                    <!-- Estado -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Estado
                        </label>
                        <select
                            v-model="form.estado"
                            @change="handleSearch"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none text-sm"
                        >
                            <option value="">Todos</option>
                            <option v-for="(label, value) in estados" :key="value" :value="value">
                                {{ label }}
                            </option>
                        </select>
                    </div>

                    <!-- Tipo -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tipo
                        </label>
                        <select
                            v-model="form.tipo"
                            @change="handleSearch"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none text-sm"
                        >
                            <option value="">Todos</option>
                            <option v-for="(label, value) in tipos" :key="value" :value="value">
                                {{ label }}
                            </option>
                        </select>
                    </div>

                    <!-- Fecha desde -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Desde
                        </label>
                        <input
                            v-model="form.fecha_desde"
                            @change="handleSearch"
                            type="date"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none text-sm"
                        />
                    </div>

                    <!-- Fecha hasta -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Hasta
                        </label>
                        <input
                            v-model="form.fecha_hasta"
                            @change="handleSearch"
                            type="date"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none text-sm"
                        />
                    </div>

                    <!-- Botones -->
                    <div class="flex space-x-3 pt-2">
                        <button
                            @click="handleSearch"
                            class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-cyan-600 hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 transition-colors"
                        >
                            <MagnifyingGlassIcon class="h-4 w-4 mr-2" />
                            Buscar
                        </button>
                        <button
                            v-if="hasFilters"
                            @click="handleClear"
                            class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 transition-colors"
                        >
                            <XMarkIcon class="h-4 w-4 mr-2" />
                            Limpiar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
