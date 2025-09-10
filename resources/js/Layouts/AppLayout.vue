<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Banner from '@/Components/Banner.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

defineProps({
    title: String,
});

const showingNavigationDropdown = ref(false);

const switchToTeam = (team) => {
    router.put(route('current-team.update'), {
        team_id: team.id,
    }, {
        preserveState: false,
    });
};

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <div>
        <Head :title="title" />

        <Banner />

        <div class="min-h-screen bg-gray-100 flex flex-col">
            <nav class="sticky top-0 z-50 bg-white border-b border-gray-100 shadow-sm">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('dashboard')">
                                    <ApplicationMark class="block h-9 w-auto" />
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                    Panel
                                </NavLink>

                                <!-- Enlaces para Administradores -->
                                <template v-if="$page.props.auth.user.roles?.some(role => ['ADMINISTRADOR', 'administrador'].includes(role.name))">
                                    <NavLink :href="'/admin/vacunas'" :active="$page.url.startsWith('/admin/vacunas')">
                                        Vacunas
                                    </NavLink>
                                    <NavLink :href="'/admin/users'" :active="$page.url.startsWith('/admin/users')">
                                        Usuarios
                                    </NavLink>
                                </template>

                                <!-- Enlaces para Médicos -->
                                <template v-if="$page.props.auth.user.roles?.some(role => ['MEDICO', 'medico', 'ADMINISTRADOR', 'administrador'].includes(role.name))">
                                    <NavLink :href="'/citas'" :active="$page.url.startsWith('/citas')">
                                        Citas
                                    </NavLink>
                                </template>

                                <!-- Enlaces para Pacientes -->
                                <template v-if="$page.props.auth.user.roles?.some(role => ['PACIENTE', 'paciente'].includes(role.name))">
                                    <NavLink :href="'/mi-carnet'" :active="$page.url.startsWith('/mi-carnet')">
                                        Mi Carnet
                                    </NavLink>
                                </template>
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <div class="ms-3 relative">
                                <!-- Teams Dropdown -->
                                <Dropdown v-if="$page.props.jetstream.hasTeamFeatures" align="right" width="60">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                                {{ $page.props.auth.user.current_team.name }}

                                                <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <div class="w-60">
                                            <!-- Team Management -->
                                            <div class="block px-4 py-2 text-xs text-gray-400">
                                                Gestión de Equipo
                                            </div>

                                            <!-- Team Settings -->
                                            <DropdownLink :href="route('teams.show', $page.props.auth.user.current_team)">
                                                Configuración del Equipo
                                            </DropdownLink>

                                            <DropdownLink v-if="$page.props.jetstream.canCreateTeams" :href="route('teams.create')">
                                                Crear Nuevo Equipo
                                            </DropdownLink>

                                            <!-- Team Switcher -->
                                            <template v-if="$page.props.auth.user.all_teams.length > 1">
                                                <div class="border-t border-gray-200" />

                                                <div class="block px-4 py-2 text-xs text-gray-400">
                                                    Cambiar Equipo
                                                </div>

                                                <template v-for="team in $page.props.auth.user.all_teams" :key="team.id">
                                                    <form @submit.prevent="switchToTeam(team)">
                                                        <DropdownLink as="button">
                                                            <div class="flex items-center">
                                                                <svg v-if="team.id == $page.props.auth.user.current_team_id" class="me-2 size-5 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>

                                                                <div>{{ team.name }}</div>
                                                            </div>
                                                        </DropdownLink>
                                                    </form>
                                                </template>
                                            </template>
                                        </div>
                                    </template>
                                </Dropdown>
                            </div>

                            <!-- Settings Dropdown -->
                            <div class="ms-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <button v-if="$page.props.jetstream.managesProfilePhotos" class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                            <img class="size-8 rounded-full object-cover" :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name">
                                        </button>

                                        <span v-else class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                                {{ $page.props.auth.user.name }}

                                                <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <!-- Account Management -->
                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            Gestión de Cuenta
                                        </div>

                                        <DropdownLink :href="route('profile.show')">
                                            Perfil
                                        </DropdownLink>

                                        <DropdownLink v-if="$page.props.jetstream.hasApiFeatures" :href="route('api-tokens.index')">
                                            Tokens API
                                        </DropdownLink>

                                        <div class="border-t border-gray-200" />

                                        <!-- Authentication -->
                                        <form @submit.prevent="logout">
                                            <DropdownLink as="button">
                                                Cerrar Sesión
                                            </DropdownLink>
                                        </form>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out" @click="showingNavigationDropdown = ! showingNavigationDropdown">
                                <svg
                                    class="size-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{'hidden': showingNavigationDropdown, 'inline-flex': ! showingNavigationDropdown }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{'hidden': ! showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}" class="sm:hidden">
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Panel
                        </ResponsiveNavLink>

                        <!-- Enlaces para Administradores -->
                        <template v-if="$page.props.auth.user.roles?.some(role => ['ADMINISTRADOR', 'administrador'].includes(role.name))">
                            <ResponsiveNavLink :href="'/admin/vacunas'" :active="$page.url.startsWith('/admin/vacunas')">
                                Vacunas
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="'/admin/users'" :active="$page.url.startsWith('/admin/users')">
                                Usuarios
                            </ResponsiveNavLink>
                        </template>

                        <!-- Enlaces para Médicos -->
                        <template v-if="$page.props.auth.user.roles?.some(role => ['MEDICO', 'medico', 'ADMINISTRADOR', 'administrador'].includes(role.name))">
                            <ResponsiveNavLink :href="'/citas'" :active="$page.url.startsWith('/citas')">
                                Citas
                            </ResponsiveNavLink>
                        </template>

                        <!-- Enlaces para Pacientes -->
                        <template v-if="$page.props.auth.user.roles?.some(role => ['PACIENTE', 'paciente'].includes(role.name))">
                            <ResponsiveNavLink :href="'/mi-carnet'" :active="$page.url.startsWith('/mi-carnet')">
                                Mi Carnet
                            </ResponsiveNavLink>
                        </template>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="flex items-center px-4">
                            <div v-if="$page.props.jetstream.managesProfilePhotos" class="shrink-0 me-3">
                                <img class="size-10 rounded-full object-cover" :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name">
                            </div>

                            <div>
                                <div class="font-medium text-base text-gray-800">
                                    {{ $page.props.auth.user.name }}
                                </div>
                                <div class="font-medium text-sm text-gray-500">
                                    {{ $page.props.auth.user.email }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.show')" :active="route().current('profile.show')">
                                Perfil
                            </ResponsiveNavLink>

                            <ResponsiveNavLink v-if="$page.props.jetstream.hasApiFeatures" :href="route('api-tokens.index')" :active="route().current('api-tokens.index')">
                                Tokens API
                            </ResponsiveNavLink>

                            <!-- Authentication -->
                            <form method="POST" @submit.prevent="logout">
                                <ResponsiveNavLink as="button">
                                    Cerrar Sesión
                                </ResponsiveNavLink>
                            </form>

                            <!-- Team Management -->
                            <template v-if="$page.props.jetstream.hasTeamFeatures">
                                <div class="border-t border-gray-200" />

                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    Gestión de Equipo
                                </div>

                                <!-- Team Settings -->
                                <ResponsiveNavLink :href="route('teams.show', $page.props.auth.user.current_team)" :active="route().current('teams.show')">
                                    Configuración del Equipo
                                </ResponsiveNavLink>

                                <ResponsiveNavLink v-if="$page.props.jetstream.canCreateTeams" :href="route('teams.create')" :active="route().current('teams.create')">
                                    Crear Nuevo Equipo
                                </ResponsiveNavLink>

                                <!-- Team Switcher -->
                                <template v-if="$page.props.auth.user.all_teams.length > 1">
                                    <div class="border-t border-gray-200" />

                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        Cambiar Equipo
                                    </div>

                                    <template v-for="team in $page.props.auth.user.all_teams" :key="team.id">
                                        <form @submit.prevent="switchToTeam(team)">
                                            <ResponsiveNavLink as="button">
                                                <div class="flex items-center">
                                                    <svg v-if="team.id == $page.props.auth.user.current_team_id" class="me-2 size-5 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <div>{{ team.name }}</div>
                                                </div>
                                            </ResponsiveNavLink>
                                        </form>
                                    </template>
                                </template>
                            </template>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header v-if="$slots.header" class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1">
                <slot />
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 mt-auto">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                        <!-- Sobre el sistema -->
                        <div class="col-span-1 md:col-span-2">
                            <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                                <svg class="h-4 w-4 mr-2 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                Sistema de Inmunización
                            </h3>
                            <p class="text-xs text-gray-600 leading-relaxed">
                                Plataforma integral para la gestión y control de vacunas, diseñada para
                                garantizar un seguimiento eficiente de la inmunización y el cuidado de la salud pública.
                            </p>
                        </div>

                        <!-- Enlaces rápidos -->
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 mb-3">Enlaces Rápidos</h3>
                            <ul class="space-y-2">
                                <li>
                                    <Link
                                        :href="route('dashboard')"
                                        class="text-xs text-gray-600 hover:text-cyan-600 transition-colors"
                                    >
                                        Dashboard
                                    </Link>
                                </li>
                                <li>
                                    <Link
                                        href="/admin/vacunas"
                                        class="text-xs text-gray-600 hover:text-cyan-600 transition-colors"
                                    >
                                        Gestión de Vacunas
                                    </Link>
                                </li>
                                <li>
                                    <a
                                        href="#"
                                        class="text-xs text-gray-600 hover:text-cyan-600 transition-colors"
                                    >
                                        Ayuda y Soporte
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- Estado del sistema -->
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 mb-3">Estado</h3>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                    <span class="text-xs text-gray-600">Sistema Operativo</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-cyan-500 rounded-full mr-2"></div>
                                    <span class="text-xs text-gray-600">Base de Datos Activa</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                                    <span class="text-xs text-gray-600">Respaldos Automáticos</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Copyright -->
                    <div class="border-t border-gray-200 pt-4">
                        <div class="flex flex-col sm:flex-row justify-between items-center space-y-2 sm:space-y-0">
                            <div class="flex items-center">
                                <p class="text-xs text-gray-500">
                                    © {{ new Date().getFullYear() }} Sistema de Inmunización.
                                </p>
                                <svg class="h-3 w-3 mx-1 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                </svg>
                                <p class="text-xs text-gray-500">
                                    Desarrollado por
                                    <a
                                        href="https://abitech.com.pe"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="font-semibold hover:opacity-80 transition-opacity"
                                    >
                                        <span style="color: #FBBE1B;">ABI</span><span style="color: #1C6CCD;">tech</span>
                                        <span style="color: #A6A6A6;" class="ml-1">PERÚ</span>
                                    </a>
                                </p>
                            </div>
                            <div class="flex items-center space-x-4">
                                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                    v1.0.0
                                </span>
                                <span class="text-xs text-gray-500">
                                    Laravel • Vue.js • Inertia.js
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</template>
