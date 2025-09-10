<script setup>
import { Link } from '@inertiajs/vue3';
import { PlusIcon, BuildingOffice2Icon } from '@heroicons/vue/24/outline';
</script>

<template>
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8 py-4 sm:py-6">
            <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
                <!-- Logo y título -->
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <BuildingOffice2Icon class="h-7 w-7 sm:h-8 sm:w-8 text-cyan-600" />
                    </div>
                    <div>
                        <h1 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900">
                            Gestión de centros de salud
                        </h1>
                        <p class="text-xs sm:text-sm text-gray-600 hidden sm:block">
                            Administra los centros de salud del sistema
                        </p>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-3">
                    <Link
                        href="/admin/centros-salud/create"
                        class="inline-flex items-center justify-center px-3 sm:px-4 py-2 border border-transparent rounded-md shadow-sm bg-cyan-600 text-sm font-medium text-white hover:bg-cyan-700 focus:outline-none transition-colors"
                    >
                        <PlusIcon class="h-4 w-4 mr-2" />
                        <span class="hidden xs:inline mr-1">Nuevo </span>centro
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
