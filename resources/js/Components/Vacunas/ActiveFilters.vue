<script setup>
import { computed } from 'vue';
import { XMarkIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    filters: Object,
    tipos: Array
});

const emit = defineEmits(['remove-filter', 'clear-all']);

const handleRemoveFilter = (key) => {
    emit('remove-filter', key);
};

const handleClearAll = () => {
    emit('clear-all');
};

const getFilterLabel = (key, value) => {
    switch (key) {
        case 'search':
            return `Buscar: "${value}"`;
        case 'tipo':
            return `Tipo: ${value}`;
        case 'activa':
            return `Estado: ${value === 'true' ? 'Activas' : 'Inactivas'}`;
        default:
            return `${key}: ${value}`;
    }
};

const activeFilters = computed(() => {
    const filters = [];
    if (props.filters.search) {
        filters.push({ key: 'search', value: props.filters.search });
    }
    if (props.filters.tipo) {
        filters.push({ key: 'tipo', value: props.filters.tipo });
    }
    if (props.filters.activa) {
        filters.push({ key: 'activa', value: props.filters.activa });
    }
    return filters;
});
</script>

<template>
    <div v-if="activeFilters.length > 0" class="mb-4">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
            <div class="flex items-center justify-between mb-2">
                <h4 class="text-sm font-medium text-blue-900">Filtros aplicados:</h4>
                <button
                    @click="handleClearAll"
                    class="text-xs text-blue-600 hover:text-blue-800 font-medium"
                >
                    Limpiar todos
                </button>
            </div>
            <div class="flex flex-wrap gap-2">
                <span
                    v-for="filter in activeFilters"
                    :key="filter.key"
                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                >
                    {{ getFilterLabel(filter.key, filter.value) }}
                    <button
                        @click="handleRemoveFilter(filter.key)"
                        class="ml-1.5 inline-flex items-center justify-center h-4 w-4 rounded-full text-blue-400 hover:bg-blue-200 hover:text-blue-600"
                    >
                        <XMarkIcon class="h-3 w-3" />
                    </button>
                </span>
            </div>
        </div>
    </div>
</template>
