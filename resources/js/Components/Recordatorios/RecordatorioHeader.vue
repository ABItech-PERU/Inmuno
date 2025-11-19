<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8 py-4 sm:py-6">
            <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
                <!-- Logo y título -->
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <svg class="h-7 w-7 sm:h-8 sm:w-8 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900">
                            Gestión de recordatorios
                        </h1>
                        <p class="text-xs sm:text-sm text-gray-600 hidden sm:block">
                            Administra los recordatorios del sistema de inmunización
                        </p>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-3">
                    <!-- Sin botones de acción para administradores -->
                </div>
            </div>
        </div>
    </div>
</template>
