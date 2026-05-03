<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import Bebe from '../../img/bebe.webp'
import GuestLayout from '@/Layouts/GuestLayout.vue'

const page = usePage()
const user = page.props.auth?.user
</script>

<template>
    <GuestLayout title="Inicio">
        <div class="w-full">
            <!-- Sección con fondo celeste curvo -->
            <section class="relative w-full flex flex-col items-center text-center pb-16">
                <div class="absolute top-0 left-0 w-full bg-gradient-to-b from-cyan-400 to-teal-400 z-10"
                    style="height: 400px; clip-path: ellipse(100% 85% at center 15%);">
                </div>

                <div class="relative z-20 pt-8 lg:pt-14">
                    <h2 class="text-lg font-medium text-white mb-1">Te damos la bienvenida a</h2>
                    <h3 class="text-xl font-bold text-white mb-6 lg:mb-8">Carné de Vacunación</h3>

                    <div class="flex justify-center mb-2">
                        <img :src="Bebe" alt="Super Baby" class="w-48 h-48 object-contain drop-shadow-2xl" />
                    </div>
                </div>
            </section>

            <!-- Contenido principal -->
            <main class="flex flex-col items-center justify-center text-center px-6 -mt-8 relative z-20">
                <a
                    href="https://carnetvacunacion.minsa.gob.pe"
                    target="_blank"
                    rel="noopener noreferrer"
                    aria-label="Validación QR - abre en nueva pestaña"
                    class="flex items-center justify-center gap-3 w-56 px-5 py-4 mb-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-lg transition-all duration-200 transform hover:scale-105">
                    <svg class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M3 11h8V3H3v8zm2-6h4v4H5V5zM3 21h8v-8H3v8zm2-6h4v4H5v-4zM13 3v8h8V3h-8zm6 6h-4V5h4v4zM19 13h2v2h-2zM13 13h2v2h-2zM15 15h2v2h-2zM13 17h2v2h-2zM15 19h2v2h-2zM17 15h2v2h-2zM19 17h2v2h-2zM17 19h2v2h-2z" />
                    </svg>
                    <span class="text-sm font-bold">VALIDACIÓN QR</span>
                </a>

                <p class="text-sm text-gray-600 mb-8 font-medium">
                    Verifica la validez de<br>tu carné (MINSA)
                </p>

                <div v-if="!user" class="flex gap-3 w-full max-w-xs mb-4">
                    <Link :href="route('register')"
                        class="flex-1 px-4 py-3 rounded-xl bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold text-center transition-all duration-200">
                    Regístrate
                    </Link>
                    <Link :href="route('login')"
                        class="flex-1 px-4 py-3 rounded-xl bg-teal-500 hover:bg-teal-600 text-white font-semibold text-center shadow-lg transition-all duration-200">
                    Inicia Sesión
                    </Link>
                </div>
                <div v-else>
                    <Link :href="route('dashboard')"
                        class="px-8 py-3 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-semibold shadow-lg transition-all duration-200 transform hover:scale-105">
                    Ir al Panel
                    </Link>
                </div>
            </main>

            <div class="flex-grow"></div>
        </div>
    </GuestLayout>
</template>
