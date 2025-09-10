<script setup>
import { Head, Link } from '@inertiajs/vue3'

defineProps({
    title: String,
    backRoute: {
        type: String,
        default: null,
    },
})
</script>

<template>

    <Head :title="title" />
    <div
        class="min-h-screen bg-gray-50 bg-[radial-gradient(circle,_#d1d5db_1px,_transparent_1px)] [background-size:40px_40px]">
        <div
            class="flex flex-col min-h-screen max-w-6xl mx-auto bg-white shadow-xl shadow-gray-200/70 rounded-2xl text-gray-800">
            <!-- HEADER -->
            <header
                class="z-20 flex items-center justify-between px-4 py-4 bg-cyan-500 shadow-lg shadow-cyan-200/50 lg:rounded-t-2xl sm:px-6">
                <!-- Botón de retroceso -->
                <div>
                    <Link v-if="backRoute" :href="backRoute"
                        class="flex items-center gap-2 px-2 py-1.5 text-white text-sm font-semibold hover:bg-cyan-600 rounded-lg transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                    <span>Volver</span>
                    </Link>
                </div>

                <!-- Logo -->
                <h1 class="text-xl font-extrabold text-white sm:text-2xl md:text-3xl py-1">
                    <span class="text-blue-700 mr-2">Inmuno</span>
                    <span class="text-white">Alerta</span>
                </h1>

                <!-- Espaciador para alinear el logo al centro -->
                <div></div>
            </header>

            <!-- CONTENIDO -->
            <main class="flex-1 flex items-center justify-center">
                <slot />
            </main>

            <!-- FOOTER -->
            <footer class="py-4 bg-gray-100 text-center text-xs text-gray-600 rounded-b-2xl">
                <p class="mb-1">Copyright © 2025</p>
                <div class="space-x-2">
                    <a href="#" class="underline hover:text-teal-600 transition">Política de Privacidad</a>
                    <span>y</span>
                    <a href="#" class="underline hover:text-teal-600 transition">Términos y Condiciones</a>
                </div>
            </footer>
        </div>
    </div>
</template>
