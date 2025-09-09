<script setup>
import { Link } from '@inertiajs/vue3';
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    meta: Object
});
</script>

<template>
    <div v-if="meta.last_page > 1" class="bg-gray-50 px-3 sm:px-4 lg:px-6 py-3 border-t border-gray-200">
        <div class="flex items-center justify-between">
            <!-- Navegación móvil -->
            <div class="flex-1 flex justify-between sm:hidden">
                <Link
                    v-if="meta.prev_page_url"
                    :href="meta.prev_page_url"
                    class="relative inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
                >
                    <ChevronLeftIcon class="h-4 w-4 mr-1" />
                    Anterior
                </Link>
                <div v-else class="invisible">
                    <span class="relative inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md">
                        <ChevronLeftIcon class="h-4 w-4 mr-1" />
                        Anterior
                    </span>
                </div>

                <Link
                    v-if="meta.next_page_url"
                    :href="meta.next_page_url"
                    class="relative inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
                >
                    Siguiente
                    <ChevronRightIcon class="h-4 w-4 ml-1" />
                </Link>
                <div v-else class="invisible">
                    <span class="relative inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md">
                        Siguiente
                        <ChevronRightIcon class="h-4 w-4 ml-1" />
                    </span>
                </div>
            </div>

            <!-- Navegación desktop -->
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-gray-700">
                        Mostrando
                        <span class="font-medium">{{ meta.from || 0 }}</span>
                        a
                        <span class="font-medium">{{ meta.to || 0 }}</span>
                        de
                        <span class="font-medium">{{ meta.total }}</span>
                        resultados
                    </p>
                </div>

                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                        <Link
                            v-if="meta.prev_page_url"
                            :href="meta.prev_page_url"
                            class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
                            title="Página anterior"
                        >
                            <ChevronLeftIcon class="h-4 w-4 mr-1" />
                            <span class="hidden lg:inline">Anterior</span>
                        </Link>

                        <!-- Indicador de página actual -->
                        <span class="relative inline-flex items-center px-3 py-2 border border-gray-300 bg-indigo-50 text-sm font-medium text-indigo-600">
                            {{ meta.current_page }} de {{ meta.last_page }}
                        </span>

                        <Link
                            v-if="meta.next_page_url"
                            :href="meta.next_page_url"
                            class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
                            title="Página siguiente"
                        >
                            <span class="hidden lg:inline">Siguiente</span>
                            <ChevronRightIcon class="h-4 w-4 ml-1" />
                        </Link>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</template>
