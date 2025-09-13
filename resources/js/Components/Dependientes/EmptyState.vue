<script setup>
import { Link } from '@inertiajs/vue3';
import { UserGroupIcon, PlusIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    hasFilters: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['clearFilters']);

const handleClearFilters = () => {
    emit('clearFilters');
};
</script>

<template>
    <div class="text-center py-8 sm:py-12 px-4">
        <!-- Icono -->
        <UserGroupIcon class="mx-auto h-10 w-10 sm:h-12 sm:w-12 text-cyan-400" />

        <!-- Título -->
        <h3 class="mt-2 text-sm sm:text-base font-medium text-gray-900">
            No hay dependientes
        </h3>

        <!-- Descripción -->
        <p class="mt-1 text-xs sm:text-sm text-gray-500 max-w-sm mx-auto">
            {{ hasFilters
                ? 'No se encontraron dependientes con los filtros aplicados.'
                : 'Comienza agregando un nuevo dependiente al sistema.'
            }}
        </p>

        <!-- Botón de acción -->
        <div class="mt-4 sm:mt-6">
            <Link
                v-if="!hasFilters"
                href="/paciente/dependientes/create"
                class="inline-flex items-center px-3 sm:px-4 py-2 border border-transparent shadow-sm text-xs sm:text-sm font-medium rounded-md text-white bg-cyan-600 hover:bg-cyan-700 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-cyan-500 transition-colors"
            >
                <PlusIcon class="h-3 w-3 sm:h-4 sm:w-4 mr-2" />
                Nuevo Dependiente
            </Link>
            <button
                v-else
                @click="handleClearFilters"
                class="inline-flex items-center px-3 sm:px-4 py-2 border border-gray-300 shadow-sm text-xs sm:text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-cyan-500 transition-colors"
            >
                Limpiar filtros
            </button>
        </div>
    </div>
</template>
