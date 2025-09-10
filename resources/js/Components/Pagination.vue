<script setup>
import { Link } from '@inertiajs/vue3';
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    links: Array
});

// Función para determinar si un enlace está activo
const isActive = (link) => link.active;

// Función para determinar si es el enlace anterior
const isPrevious = (link) => link.label?.includes('Previous') || link.label?.includes('Anterior');

// Función para determinar si es el enlace siguiente
const isNext = (link) => link.label?.includes('Next') || link.label?.includes('Siguiente');

// Función para obtener el número de página
const getPageNumber = (link) => {
    if (isPrevious(link) || isNext(link)) return null;
    return link.label;
};
</script>

<template>
    <div v-if="links && links.length > 3" class="flex items-center justify-between">
        <!-- Información de páginas en móvil -->
        <div class="flex flex-1 justify-between sm:hidden">
            <Link
                v-if="links.find(link => isPrevious(link))?.url"
                :href="links.find(link => isPrevious(link)).url"
                class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
            >
                Anterior
            </Link>
            <span v-else class="relative inline-flex items-center rounded-md border border-gray-300 bg-gray-100 px-4 py-2 text-sm font-medium text-gray-400 cursor-not-allowed">
                Anterior
            </span>

            <Link
                v-if="links.find(link => isNext(link))?.url"
                :href="links.find(link => isNext(link)).url"
                class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
            >
                Siguiente
            </Link>
            <span v-else class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-gray-100 px-4 py-2 text-sm font-medium text-gray-400 cursor-not-allowed">
                Siguiente
            </span>
        </div>

        <!-- Paginación completa para escritorio -->
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                    <!-- Botón anterior -->
                    <Link
                        v-if="links.find(link => isPrevious(link))?.url"
                        :href="links.find(link => isPrevious(link)).url"
                        class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                    >
                        <span class="sr-only">Anterior</span>
                        <ChevronLeftIcon class="h-5 w-5" aria-hidden="true" />
                    </Link>
                    <span v-else class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-300 ring-1 ring-inset ring-gray-300 bg-gray-50 cursor-not-allowed">
                        <span class="sr-only">Anterior</span>
                        <ChevronLeftIcon class="h-5 w-5" aria-hidden="true" />
                    </span>

                    <!-- Enlaces de páginas -->
                    <template v-for="(link, index) in links" :key="index">
                        <template v-if="!isPrevious(link) && !isNext(link)">
                            <Link
                                v-if="link.url && !isActive(link)"
                                :href="link.url"
                                class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                                v-html="link.label"
                            />
                            <span
                                v-else-if="isActive(link)"
                                class="relative z-10 inline-flex items-center bg-cyan-600 px-4 py-2 text-sm font-semibold text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-cyan-600"
                                v-html="link.label"
                            />
                            <span
                                v-else
                                class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-400 ring-1 ring-inset ring-gray-300 cursor-not-allowed"
                                v-html="link.label"
                            />
                        </template>
                    </template>

                    <!-- Botón siguiente -->
                    <Link
                        v-if="links.find(link => isNext(link))?.url"
                        :href="links.find(link => isNext(link)).url"
                        class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                    >
                        <span class="sr-only">Siguiente</span>
                        <ChevronRightIcon class="h-5 w-5" aria-hidden="true" />
                    </Link>
                    <span v-else class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-300 ring-1 ring-inset ring-gray-300 bg-gray-50 cursor-not-allowed">
                        <span class="sr-only">Siguiente</span>
                        <ChevronRightIcon class="h-5 w-5" aria-hidden="true" />
                    </span>
                </nav>
            </div>
        </div>
    </div>
</template>
