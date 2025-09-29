<template>
    <AppLayout title="Esquema de Vacunación Detallado">
        <div class="pb-12 md:pt-12">
            <div class="max-w-7xl mx-auto">
                <!-- Header con información de la persona -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                    <div class="p-6">
                        <div class="grid grid-cols-1 sm:flex sm:items-center sm:justify-between gap-4">
                            <div class="flex items-center min-w-0">
                                <div class="flex-shrink-0">
                                    <div class="h-16 w-16 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center">
                                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4 min-w-0 break-words">
                                    <h1 class="text-2xl font-bold text-gray-900 whitespace-normal break-words">
                                        {{ persona.nombre_completo }}
                                    </h1>
                                    <p class="text-sm text-gray-600">
                                        <span v-if="persona.es_usuario" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Usuario Principal
                                        </span>
                                        <span v-else class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Dependiente
                                        </span>
                                        · {{ persona.edad_años }} años
                                        <span v-if="persona.meses_restantes > 0">
                                            y {{ persona.meses_restantes }} meses
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4 justify-end">
                                <Link :href="route('paciente.esquema-vacunacion.index')"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                    Volver
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- (Debug removido) -->

                <!-- Estadísticas generales -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8 px-4 sm:px-6 lg:px-0">
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-3 sm:p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="h-8 w-8 sm:h-10 sm:w-10 rounded-full bg-blue-500 flex items-center justify-center">
                                            <svg class="h-4 w-4 sm:h-5 sm:w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                </div>
                                    <div class="ml-4 sm:ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-xs sm:text-sm font-medium text-gray-500">Completado</dt>
                                            <dd class="text-base sm:text-lg font-medium text-gray-900">{{ estadisticas.porcentaje_completo }}%</dd>
                                        </dl>
                                    </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-3 sm:p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="h-8 w-8 sm:h-10 sm:w-10 rounded-full bg-green-500 flex items-center justify-center">
                                        <svg class="h-4 w-4 sm:h-5 sm:w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4 sm:ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-xs sm:text-sm font-medium text-gray-500">Aplicadas</dt>
                                        <dd class="text-base sm:text-lg font-medium text-gray-900">{{ estadisticas.dosis_aplicadas }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-3 sm:p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="h-8 w-8 sm:h-10 sm:w-10 rounded-full bg-yellow-500 flex items-center justify-center">
                                        <svg class="h-4 w-4 sm:h-5 sm:w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4 sm:ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-xs sm:text-sm font-medium text-gray-500">Pendientes</dt>
                                        <dd class="text-base sm:text-lg font-medium text-gray-900">{{ estadisticas.dosis_pendientes }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-3 sm:p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="h-8 w-8 sm:h-10 sm:w-10 rounded-full bg-indigo-500 flex items-center justify-center">
                                        <svg class="h-4 w-4 sm:h-5 sm:w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4 sm:ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-xs sm:text-sm font-medium text-gray-500">Total</dt>
                                        <dd class="text-base sm:text-lg font-medium text-gray-900">{{ estadisticas.total_dosis }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Próxima vacuna -->
                <div v-if="estadisticas.proxima_vacuna" class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-400 p-4 mb-6 rounded-r-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                <strong>Próxima vacuna:</strong> {{ estadisticas.proxima_vacuna.dosis.vacuna.nombre }} - Dosis {{ estadisticas.proxima_vacuna.dosis.numero_dosis }}
                                ({{ estadisticas.proxima_vacuna.esquema_nombre }})
                                <span v-if="estadisticas.proxima_vacuna.dias_para_aplicacion === 0" class="ml-2 text-green-600 font-semibold">¡Disponible ahora!</span>
                                <span v-else-if="estadisticas.proxima_vacuna.dias_para_aplicacion > 0" class="ml-2 text-yellow-600">
                                    En {{ formatDaysToLabel(estadisticas.proxima_vacuna.dias_para_aplicacion) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Timeline de esquemas -->
                <div class="space-y-8 px-4 sm:px-6 lg:px-0">
                    <div v-for="esquema in sortedEsquemas" :key="esquema.esquema.id || esquema.esquema?.id" :id="'esquema-' + (esquema.esquema.id || esquema.esquema?.id)" class="bg-white shadow rounded-lg overflow-visible">
                <div class="px-4 py-3 sm:px-6 sm:py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">{{ esquema.esquema.nombre }}</h3>
                                    <p class="text-sm text-gray-500 mt-1">{{ esquema.esquema.descripcion }}</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <!-- Progress Bar -->
                                    <div class="w-24 sm:w-32 bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" :style="`width: ${esquema.estadisticas.porcentaje_completo}%`"></div>
                                    </div>
                                    <span class="text-xs text-gray-600">{{ esquema.estadisticas.porcentaje_completo }}%</span>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 sm:p-6">
                            <!-- Timeline de vacunas -->
                            <div class="flow-root">
                                <ul class="-mb-8">
                                    <li v-for="(dosis, index) in esquema.dosis" :key="dosis.dosis.id || dosis.dosis.numero_dosis || index" :id="'dosis-' + (dosis.dosis.id || dosis.dosis.numero_dosis || index)" class="relative pb-6">
                                        <div v-if="index !== esquema.dosis.length - 1" class="absolute left-4 sm:left-5 -ml-px top-9 sm:top-10 bottom-0 w-0.5 bg-gray-200 z-0" aria-hidden="true"></div>

                                        <div class="relative flex space-x-2 sm:space-x-3">
                                            <!-- Icono de estado -->
                                            <div v-if="dosis.aplicada" class="h-8 w-8 sm:h-10 sm:w-10 rounded-full bg-green-500 flex items-center justify-center ring-4 sm:ring-8 ring-white relative z-10">
                                                <svg class="h-4 w-4 sm:h-5 sm:w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <div v-else-if="dosis.puede_aplicar" class="h-8 w-8 sm:h-10 sm:w-10 rounded-full bg-blue-500 flex items-center justify-center ring-4 sm:ring-8 ring-white relative z-10">
                                                <svg class="h-4 w-4 sm:h-5 sm:w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                            <div v-else class="h-8 w-8 sm:h-10 sm:w-10 rounded-full bg-gray-300 flex items-center justify-center ring-4 sm:ring-8 ring-white relative z-10">
                                                <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>

                                            <!-- Contenido -->
                                            <div class="flex-1 min-w-0 break-words">
                                                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                                                    <div class="flex-1">
                                                        <h4 class="text-sm font-medium text-gray-900">
                                                            {{ dosis.dosis.vacuna.nombre }} - Dosis {{ dosis.dosis.numero_dosis }}
                                                        </h4>
                                                        <p class="text-sm text-gray-500">
                                                            <span v-if="dosis.dosis.edad_aplicacion">
                                                                A partir de {{ formatMonthsToLabel(dosis.dosis.edad_aplicacion) }}
                                                            </span>
                                                            <span v-else>Sin edad mínima</span>
                                                        </p>
                                                        <p class="text-xs text-gray-600 mt-1">
                                                            Paciente: {{ Math.floor(personaAgeMonths/12) }} años {{ personaAgeMonths % 12 }} meses
                                                        </p>

                                                        <div class="mt-2">
                                                            <span v-if="isOverdueByAge(dosis)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                                ⚠️ Retrasada por edad
                                                            </span>
                                                            <span v-else-if="!dosis.dosis.edad_aplicacion" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                                ℹ️ Sin edad mínima
                                                            </span>
                                                        </div>

                                                        <!-- Estado de la dosis (apilado verticalmente en pantallas grandes para mejor legibilidad) -->
                                                        <div class="mt-2 flex flex-col lg:flex-col items-start gap-2">
                                                            <span v-if="dosis.aplicada" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                ✅ Aplicada
                                                            </span>
                                                            <span v-else-if="dosis.puede_aplicar && dosis.dias_para_aplicacion <= 0" class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-900 border border-green-100">
                                                                <svg class="-ml-0.5 mr-1 h-3 w-3 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg>
                                                                📅 Disponible
                                                            </span>
                                                            <span v-else-if="dosis.dias_para_aplicacion > 0" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                                ⏳ En {{ formatDaysToLabel(dosis.dias_para_aplicacion) }}
                                                                <span v-if="dosis.fecha_estimada_aplicacion" class="ml-2 text-xs text-yellow-800">(estimado: {{ formatDateShort(dosis.fecha_estimada_aplicacion) }})</span>
                                                                <span v-if="dosis.fuente_calculo && dosis.fuente_calculo !== 'edad'" class="ml-2 text-xs text-yellow-700 flex items-center gap-1">
                                                                    · calculado desde: <strong class="ml-1">{{ translateFuenteCalculo(dosis.fuente_calculo) }}</strong>
                                                                    <span class="ml-1 text-yellow-800" :title="tooltipFuente(dosis.fuente_calculo)" aria-hidden="true">🛈</span>
                                                                </span>
                                                            </span>
                                                            <span v-else class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-900 border border-red-100">
                                                                <svg class="-ml-0.5 mr-1 h-3 w-3 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                                ⏸️ No disponible
                                                            </span>
                                                        </div>

                                                        <!-- Información de aplicación -->
                                                        <div v-if="dosis.aplicada && dosis.aplicacion" class="mt-2 text-xs text-gray-600">
                                                            <p>Aplicada el {{ formatDate(dosis.aplicacion.fecha_aplicacion) }}</p>
                                                            <p v-if="dosis.aplicacion.centro_salud">en {{ dosis.aplicacion.centro_salud.nombre }}</p>
                                                        </div>
                                                    </div>

                                                    <!-- Botones de acción agrupados con separación -->
                                                        <div class="flex flex-row flex-wrap lg:flex-nowrap lg:items-center lg:space-x-2 w-full lg:w-auto mt-3 lg:mt-0 -mx-1 justify-end gap-2">
                                                        <div v-if="dosis.aplicada" class="px-1 w-full lg:w-auto">
                                                            <span class="inline-flex items-center px-3 py-2 text-xs font-medium rounded-md bg-green-100 text-green-800 border border-green-200">
                                                                ✅ Completado
                                                            </span>
                                                        </div>
                            <div v-else-if="shouldShowMarkApplied(dosis)" class="px-1 w-full lg:w-auto">
                                                            <button @click="abrirModalAplicacion(dosis.dosis)"
                                class="inline-flex items-center px-3 py-2 border border-transparent text-xs sm:text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 w-full justify-center">
                                                                Marcar como aplicada
                                                            </button>
                                                        </div>
                                                        <div v-if="hasRecordatorio(dosis) && isFirstPendingForVaccine(esquema, dosis)" class="px-1 w-full lg:w-auto flex flex-row flex-wrap items-center gap-2 justify-center sm:justify-end">
                                                            <span class="inline-flex items-center px-3 py-2 text-xs font-medium rounded-md bg-gray-100 text-gray-800 border border-gray-200 max-w-full flex-1">
                                                                <svg class="-ml-0.5 mr-2 h-4 w-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                                </svg>
                                                                Agendado
                                                            </span>
                                                            <a :href="route('paciente.recordatorios.index')" class="inline-flex items-center px-3 py-2 text-xs font-medium rounded-md text-white bg-cyan-600 hover:bg-cyan-700 active:bg-cyan-800 sm:w-auto justify-center">
                                                                Ver
                                                            </a>
                                                        </div>
                                                        <div class="px-1 w-1/2 lg:w-auto">
                                                            <div v-if="shouldShowAgendar(dosis) && !(hasRecordatorio(dosis) && isFirstPendingForVaccine(esquema, dosis))" class="w-full px-1">
                                                                <button @click.prevent="abrirModalAgendar(dosis.dosis)"
                                                                    class="inline-flex items-center px-3 py-2 border border-transparent text-xs sm:text-xs font-medium rounded-md text-white bg-cyan-600 hover:bg-cyan-700 w-full sm:w-auto justify-center">
                                                                    <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                    </svg>
                                                                    Agendar
                                                                </button>
                                                            </div>
                                                            <!-- acciones -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Historial de aplicaciones (si existen) -->
                <div v-if="aplicaciones_historico.length > 0" class="mt-8 bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Historial de Vacunación</h3>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div v-for="aplicacion in aplicaciones_historico" :key="aplicacion.id" class="px-6 py-4 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-gray-900">
                                        {{ aplicacion.vacuna.nombre }} - Dosis {{ aplicacion.numero_dosis }}
                                    </h4>
                                    <p class="text-sm text-gray-500">
                                        {{ formatDate(aplicacion.fecha_aplicacion) }}
                                        <span v-if="aplicacion.centro_salud"> · {{ aplicacion.centro_salud.nombre }}</span>
                                    </p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Aplicada
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para marcar como aplicada -->
    <!-- Modal para agendar cita (usar diseño similar a Nuevo Recordatorio) -->
        <Modal :show="showAgendarModal" @close="cerrarModalAgendar" :max-width="'2xl'">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Agendar cita para vacuna</h3>
                </div>

                <!-- Destinatario informativo (ahora en una sola columna) -->
                <div v-if="agendarSeleccionada" class="mb-4">
                    <div class="bg-cyan-50 border border-cyan-200 rounded-lg p-4">
                        <h4 class="font-medium text-cyan-900">{{ agendarSeleccionada.vacuna.nombre }} - Dosis {{ agendarSeleccionada.numero_dosis }}</h4>
                        <p class="text-sm text-cyan-700">Tipo: {{ formAgendar.tipo === 'vacuna_proxima' ? 'Próxima vacuna' : formAgendar.tipo === 'refuerzo_pendiente' ? 'Refuerzo pendiente' : formAgendar.tipo }}</p>
                    </div>
                </div>

                <form @submit.prevent="enviarAgendado" class="">
                    <!-- Un sola columna: destinatario informativo arriba y formulario debajo -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Para</label>
                            <div class="p-3 border border-gray-200 rounded-lg bg-white">
                                <div class="text-sm font-medium text-gray-900">
                                    <span v-if="persona.es_usuario">Tú (Usuario Principal)</span>
                                    <span v-else>{{ persona.datos.nombres }} {{ persona.datos.apellidos }}</span>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">Edad: {{ persona.edad_años }} años {{ persona.meses_restantes }} meses</div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Título</label>
                            <input v-model="formAgendar.titulo" type="text" required
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500"
                                placeholder="Título del recordatorio">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Descripción (opcional)</label>
                            <textarea v-model="formAgendar.observaciones" rows="3"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500"
                                placeholder="Notas para la cita o centro de salud"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha <span class="text-red-500">*</span></label>
                                <input v-model="formAgendar.fecha" type="date" required :min="formAgendar.min_fecha || todayLocal" @change="validarFechaHoraAgendar"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Hora <span class="text-gray-400">(opcional)</span></label>
                                <input v-model="formAgendar.hora" type="time" :min="minHoraParaFechaAgendar || undefined" @input="validarFechaHoraAgendar"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
                                <InputError :message="errorHoraAgendar" class="mt-1" />
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 mt-6">
                        <button type="button" @click="cerrarModalAgendar"
                            class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">Cancelar</button>
                        <button type="submit" :disabled="processingAgendar"
                            class="px-6 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 font-medium">
                            <span v-if="processingAgendar">Enviando...</span>
                            <span v-else>Crear cita / Recordatorio</span>
                        </button>
                    </div>
                </form>
            </div>
        </Modal>
        <!-- Toast simple -->
        <div v-if="toast.show" class="fixed bottom-6 right-6 z-50 sm:right-6 sm:bottom-6 left-1/2 transform -translate-x-1/2 sm:translate-x-0 sm:left-auto">
            <div class="bg-gray-800 text-white px-4 py-2 rounded-lg shadow-lg">{{ toast.message }}</div>
        </div>
        <Modal :show="showModal" @close="cerrarModal" max-width="md">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    Marcar Vacuna como Aplicada
                </h3>

                <div v-if="dosisSeleccionada" class="mb-6">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="font-medium text-blue-900">{{ dosisSeleccionada.vacuna.nombre }}</h4>
                        <p class="text-sm text-blue-700">Dosis {{ dosisSeleccionada.numero_dosis }}</p>
                    </div>
                </div>

                <form @submit.prevent="marcarComoAplicada">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fecha de aplicación</label>
                            <input type="date" v-model="form.fecha_aplicacion" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Centro de Salud</label>
                            <select v-model="form.centro_salud_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Seleccionar centro de salud</option>
                                <option v-for="centro in centrosSalud" :key="centro.id" :value="centro.id">
                                    {{ centro.nombre }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Observaciones (opcional)</label>
                            <textarea v-model="form.observaciones" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Notas adicionales sobre la aplicación..."></textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" @click="cerrarModal"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md">
                            Cancelar
                        </button>
                        <button type="submit" :disabled="processing"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md disabled:opacity-50">
                            <span v-if="processing">Guardando...</span>
                            <span v-else>Guardar</span>
                        </button>
                    </div>
                </form>
            </div>
        </Modal>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import InputError from '@/Components/InputError.vue'
import { validateDateTime } from '@/Utils/validateDateTime.js'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
import Modal from '@/Components/Modal.vue'

const props = defineProps({
    persona: Object,
    esquemas: [Array, Object],
    aplicaciones_historico: Array,
    estadisticas: Object,
})

const normalizedEsquemas = computed(() => {
    // Acepta que props.esquemas sea array u objeto
    return Array.isArray(props.esquemas) ? props.esquemas : Object.values(props.esquemas || {})
})

const sortedEsquemas = computed(() => {
    // Copiar estructuras para no mutar props
    const esquemasCopy = normalizedEsquemas.value.map(e => {
        // Defensive copy
        const dosises = Array.isArray(e.dosis) ? e.dosis.slice() : []

        // Ordenar dosis por edad_aplicacion (null/undefined -> large number) y luego por numero_dosis
        dosises.sort((a, b) => {
            const aEdad = (a.dosis && (a.dosis.edad_aplicacion !== null && a.dosis.edad_aplicacion !== undefined)) ? Number(a.dosis.edad_aplicacion) : Number.MAX_SAFE_INTEGER
            const bEdad = (b.dosis && (b.dosis.edad_aplicacion !== null && b.dosis.edad_aplicacion !== undefined)) ? Number(b.dosis.edad_aplicacion) : Number.MAX_SAFE_INTEGER
            if (aEdad !== bEdad) return aEdad - bEdad
            // fallback a numero_dosis
            const aNum = (a.dosis && a.dosis.numero_dosis) ? Number(a.dosis.numero_dosis) : 0
            const bNum = (b.dosis && b.dosis.numero_dosis) ? Number(b.dosis.numero_dosis) : 0
            return aNum - bNum
        })

        return {
            ...e,
            dosis: dosises
        }
    })

    // Para ordenar esquemas, usamos la edad mínima de sus dosis (si no tiene, la ponemos muy alta)
    esquemasCopy.sort((ea, eb) => {
        const minEdadA = ea.dosis.reduce((min, item) => {
            const val = item.dosis && (item.dosis.edad_aplicacion !== null && item.dosis.edad_aplicacion !== undefined) ? Number(item.dosis.edad_aplicacion) : Number.MAX_SAFE_INTEGER
            return Math.min(min, val)
        }, Number.MAX_SAFE_INTEGER)
        const minEdadB = eb.dosis.reduce((min, item) => {
            const val = item.dosis && (item.dosis.edad_aplicacion !== null && item.dosis.edad_aplicacion !== undefined) ? Number(item.dosis.edad_aplicacion) : Number.MAX_SAFE_INTEGER
            return Math.min(min, val)
        }, Number.MAX_SAFE_INTEGER)

        if (minEdadA !== minEdadB) return minEdadA - minEdadB
        // Si igual, fallback por nombre de esquema
        const nameA = (ea.esquema && ea.esquema.nombre) ? String(ea.esquema.nombre).toLowerCase() : ''
        const nameB = (eb.esquema && eb.esquema.nombre) ? String(eb.esquema.nombre).toLowerCase() : ''
        return nameA.localeCompare(nameB)
    })

    return esquemasCopy
})

const showModal = ref(false)
const dosisSeleccionada = ref(null)
const centrosSalud = ref([])
const processing = ref(false)

// Estado para agendar cita
const showAgendarModal = ref(false)
const agendarSeleccionada = ref(null)
const processingAgendar = ref(false)
const formAgendar = ref({
    dosis_id: '',
    fecha: '',
    hora: '',
    centro_salud_id: '',
    observaciones: '',
    titulo: '',
    tipo: 'vacuna_proxima'
})

// Set local para trackear dosis recientemente agendadas (no persistente)
const agendadosLocales = ref(new Set())

// Toast simple
const toast = ref({ show: false, message: '' })

// Mostrar Agendar sólo si falta menos de este número de días (salvo override del backend)

// Error local para hora/fecha en modal Agendar
const errorHoraAgendar = ref('')

// Computed minHora para el input time (si fecha == hoy)
const minHoraParaFechaAgendar = computed(() => {
    const fecha = formAgendar.value.fecha
    if (!fecha) return null
    const hoy = new Date()
    const d = new Date(fecha + 'T00:00:00')
    if (d.getFullYear() === hoy.getFullYear() && d.getMonth() === hoy.getMonth() && d.getDate() === hoy.getDate()) {
        const min = new Date(hoy.getTime() + 1 * 60 * 1000)
        const hh = String(min.getHours()).padStart(2, '0')
        const mm = String(min.getMinutes()).padStart(2, '0')
        return `${hh}:${mm}`
    }
    return null
})

// Fecha mínima local en formato YYYY-MM-DD
const todayLocal = computed(() => {
    const now = new Date();
    const offset = now.getTimezoneOffset();
    const local = new Date(now.getTime() - offset * 60 * 1000);
    return local.toISOString().split('T')[0];
});

const form = useForm({
    dosis_id: '',
    persona_tipo: props.persona.es_usuario ? 'usuario' : 'dependiente',
    persona_id: props.persona.es_usuario ? null : props.persona.datos.id,
    fecha_aplicacion: '',
    centro_salud_id: '',
    observaciones: ''
})

// (Se permite mostrar Agendar; la restricción se aplica en el modal al elegir fecha)

const formatDate = (dateString) => {
    if (!dateString) return ''
    const date = new Date(dateString)
    return date.toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    })
}

const formatDateShort = (dateString) => {
    if (!dateString) return ''
    const d = new Date(dateString)
    const dd = String(d.getDate()).padStart(2, '0')
    const mm = String(d.getMonth() + 1).padStart(2, '0')
    const yyyy = d.getFullYear()
    return `${dd}/${mm}/${yyyy}`
}

// Formatea meses a 'N años M meses' o 'N meses'
const formatMonthsToLabel = (meses) => {
    if (meses === null || meses === undefined) return ''
    const m = Number(meses)
    if (isNaN(m)) return ''
    const años = Math.floor(m / 12)
    const remMeses = m % 12
    const añoLabel = (años === 1) ? '1 año' : (años > 1 ? `${años} años` : '')
    const mesLabel = (remMeses === 1) ? '1 mes' : (remMeses > 1 ? `${remMeses} meses` : '')

    if (añoLabel && mesLabel) return `${añoLabel} ${mesLabel}`
    if (añoLabel) return añoLabel
    if (mesLabel) return mesLabel
    return '0 meses'
}

// Formatea días en etiqueta legible: 'X días' o 'Y meses Z días' o 'N años M meses'
const formatDaysToLabel = (dias) => {
    if (dias === null || dias === undefined) return ''
    let d = Number(dias)
    if (isNaN(d)) return ''
    d = Math.round(d) // redondear
    if (d < 30) return `${d} ${d === 1 ? 'día' : 'días'}`
    const meses = Math.floor(d / 30)
    const remDias = d % 30
    if (meses < 12) {
        if (remDias > 0) return `${meses} ${meses === 1 ? 'mes' : 'meses'} ${remDias} ${remDias === 1 ? 'día' : 'días'}`
        return `${meses} ${meses === 1 ? 'mes' : 'meses'}`
    }
    const años = Math.floor(meses / 12)
    const remMeses = meses % 12
    if (remMeses > 0) return `${años} ${años === 1 ? 'año' : 'años'} ${remMeses} ${remMeses === 1 ? 'mes' : 'meses'}`
    return `${años} ${años === 1 ? 'año' : 'años'}`
}

// Traduce la clave fuente_calculo a una etiqueta legible
const translateFuenteCalculo = (fuente) => {
    if (!fuente) return ''
    switch (fuente) {
        case 'intervalo_prev': return 'aplicación previa'
        case 'edad': return 'edad mínima'
        case 'inmediato': return 'disponible ahora'
        case 'manual': return 'fecha manual'
        default: return fuente
    }
}

// Texto explicativo para tooltip según la fuente
const tooltipFuente = (fuente) => {
    switch (fuente) {
        case 'intervalo_prev': return 'Fecha calculada desde la fecha de aplicación previa + intervalo definido por la vacuna.'
        case 'edad': return 'Fecha calculada a partir de la edad mínima de aplicación definida en el esquema.'
        case 'inmediato': return 'La dosis puede aplicarse en cualquier momento.'
        case 'manual': return 'Fecha fijada manualmente por el usuario o profesional.'
        default: return ''
    }
}

// --- Lógica de edad y visibilidad de botones ---
// Edad de la persona en meses (unwrapped en template)
const personaAgeMonths = computed(() => {
    const años = props.persona?.edad_años ? Number(props.persona.edad_años) : 0
    const meses = props.persona?.meses_restantes ? Number(props.persona.meses_restantes) : 0
    return años * 12 + meses
})

// Helper: dentro de un esquema, determinar si la dosis dada es la primera pendiente para su vacuna
const isFirstPendingForVaccine = (esquema, dosisObj) => {
    try {
        const vacunaId = dosisObj.dosis.vacuna_id || dosisObj.dosis.vacuna?.id
        if (!vacunaId) return false
        // Buscar las dosis del mismo esquema que correspondan a la misma vacuna
        const mismas = (esquema.dosis || []).filter(d => {
            const v = d.dosis.vacuna_id || d.dosis.vacuna?.id
            return v === vacunaId && !d.aplicada
        })
        if (!mismas || mismas.length === 0) return false
        // Obtener la menor numero_dosis entre pendientes
        const minNumero = mismas.reduce((min, cur) => {
            const num = cur.dosis.numero_dosis || 0
            return (min === null || num < min) ? num : min
        }, null)
        return Number(dosisObj.dosis.numero_dosis) === Number(minNumero)
    } catch (e) {
        return false
    }
}

// Devuelve true si la persona ya cumplió la edad mínima requerida para la dosis
const puedeAplicarPorEdad = (dosisObj) => {
    const edadReqRaw = dosisObj?.dosis && (dosisObj.dosis.edad_aplicacion !== null && dosisObj.dosis.edad_aplicacion !== undefined) ? dosisObj.dosis.edad_aplicacion : null
    const edadReq = (edadReqRaw === null) ? null : Number(edadReqRaw)
    // Considerar edad_aplicacion === 0 como sin edad mínima (aplicable desde nacimiento)
    if (edadReq === null || edadReq === 0) return true
    return personaAgeMonths.value >= edadReq
}

// Si la dosis está retrasada respecto a la edad del paciente
const isOverdueByAge = (dosisObj) => {
    const edadReqRaw = dosisObj?.dosis && (dosisObj.dosis.edad_aplicacion !== null && dosisObj.dosis.edad_aplicacion !== undefined) ? dosisObj.dosis.edad_aplicacion : null
    const edadReq = (edadReqRaw === null) ? null : Number(edadReqRaw)
    // Si no hay edad mínima o es 0, no puede estar retrasada por edad
    if (edadReq === null || edadReq === 0) return false
    return !dosisObj.aplicada && personaAgeMonths.value > edadReq
}

// Mostrar el botón "Marcar como aplicada" si no aplicada, disponible por tiempo y cumple edad
const shouldShowMarkApplied = (dosisObj) => {
    return (!dosisObj.aplicada) && Boolean(dosisObj.puede_aplicar) && Number(dosisObj.dias_para_aplicacion) <= 0 && puedeAplicarPorEdad(dosisObj)
}

// Mostrar "Agendar" en estos casos:
// - backend lo indica explícitamente (puede_agendar === true)
// - no puede aplicar ahora (puede_aplicar === false) => permitir agendar
// - la dosis está retrasada por edad (persona ya pasó la edad mínima)
const shouldShowAgendar = (dosisObj) => {
    if (dosisObj.aplicada) return false
    // Si el backend indicó explícitamente que se puede agendar
    if (dosisObj.puede_agendar === true) return true
    if (dosisObj.puede_agendar === false) return false

    // Si la dosis no tiene edad mínima (puede aplicarse en cualquier momento), permitir agendar
    const edadReqRaw = dosisObj.dosis && (dosisObj.dosis.edad_aplicacion !== null && dosisObj.dosis.edad_aplicacion !== undefined) ? dosisObj.dosis.edad_aplicacion : null
    const edadReq = (edadReqRaw === null) ? null : Number(edadReqRaw)
    if (edadReq === null || edadReq === 0) {
        // sólo si no está aplicada ni ya tiene recordatorio
        return !dosisObj.aplicada && !dosisObj.tiene_recordatorio
    }

    // Si no cumple condiciones de aplicación ahora, permitir agendar
    if (!dosisObj.puede_aplicar) return true
    if (isOverdueByAge(dosisObj)) return true
    return false
}

// Helper para comprobar si una dosis tiene recordatorio (considera estado servidor y local)
const hasRecordatorio = (dosisObj) => {
    try {
        // si el backend indica recordatorio
        if (dosisObj.tiene_recordatorio) return true
        // si la hemos marcado localmente
        const id = dosisObj.dosis ? (dosisObj.dosis.id || dosisObj.dosis.numero_dosis) : (dosisObj.id || dosisObj.numero_dosis)
        return agendadosLocales.value.has(String(id))
    } catch (e) {
        return false
    }
}

const abrirModalAplicacion = (dosis) => {
    dosisSeleccionada.value = dosis
    form.dosis_id = dosis.id
    // Asegurar que el formulario tenga el destinatario correcto (usuario o dependiente)
    form.persona_tipo = props.persona.es_usuario ? 'usuario' : 'dependiente'
    form.persona_id = props.persona.es_usuario ? null : props.persona.datos.id
    form.fecha_aplicacion = new Date().toISOString().split('T')[0] // Fecha actual
    showModal.value = true
}

const addDaysToDateString = (baseDate, days) => {
    const d = new Date(baseDate)
    d.setDate(d.getDate() + Number(days))
    const yyyy = d.getFullYear()
    const mm = String(d.getMonth() + 1).padStart(2, '0')
    const dd = String(d.getDate()).padStart(2, '0')
    return `${yyyy}-${mm}-${dd}`
}

const abrirModalAgendar = (dosisWrapperOrInner) => {
    // soportar pasar tanto el wrapper (con dias_para_aplicacion) como el objeto dosis interno
    const inner = dosisWrapperOrInner.dosis ? dosisWrapperOrInner.dosis : dosisWrapperOrInner
    agendarSeleccionada.value = inner
    formAgendar.value.dosis_id = inner.id
    formAgendar.value.centro_salud_id = ''
    // Prefill title con formato 'Vacuna - Dosis N'
    try {
        formAgendar.value.titulo = `${inner.vacuna.nombre} - Dosis ${inner.numero_dosis}`
    } catch (e) {
        formAgendar.value.titulo = ''
    }
    // Tipo por defecto para el recordatorio en este modal
    formAgendar.value.tipo = 'vacuna_proxima'
    // Asegurar que el recordatorio se asocie correctamente al usuario o dependiente
    formAgendar.value.dependiente_id = props.persona.es_usuario ? null : props.persona.datos.id
    formAgendar.value.persona_tipo = props.persona.es_usuario ? 'usuario' : 'dependiente'
    formAgendar.value.persona_id = props.persona.es_usuario ? null : props.persona.datos.id

    // Determinar dias_para_aplicacion: preferir wrapper, si no existe intentar encontrar el wrapper en normalizedEsquemas
    let dias = null
    if (dosisWrapperOrInner.dias_para_aplicacion !== undefined && dosisWrapperOrInner.dias_para_aplicacion !== null) {
        dias = Number(dosisWrapperOrInner.dias_para_aplicacion)
    } else {
        const match = normalizedEsquemas.value.flatMap(es => es.dosis || []).find(dd => dd.dosis && (dd.dosis.id === inner.id || (dd.dosis.numero_dosis == inner.numero_dosis && dd.dosis.vacuna && inner.vacuna && dd.dosis.vacuna.id === inner.vacuna.id)))
        if (match) dias = Number(match.dias_para_aplicacion)
    }

    const hoy = new Date()
    let minFecha = todayLocal.value
    if (typeof dias === 'number' && dias > 0) {
        const diasEnteros = Math.ceil(dias)
        minFecha = addDaysToDateString(hoy, diasEnteros)
    }
    formAgendar.value.min_fecha = minFecha
    // Prefill la fecha con la mínima permitida si no hay fecha elegida
    formAgendar.value.fecha = formAgendar.value.fecha || minFecha

    showAgendarModal.value = true
}

const cerrarModalAgendar = () => {
    showAgendarModal.value = false
    agendarSeleccionada.value = null
    formAgendar.value = {
        dosis_id: '',
        fecha: '',
        hora: '',
        centro_salud_id: '',
        observaciones: '',
        titulo: '',
        tipo: 'vacuna_proxima'
    }
}

const validarFechaHoraAgendar = () => {
    const res = validateDateTime(formAgendar.value.fecha, formAgendar.value.hora)
    if (!res.valid) {
        errorHoraAgendar.value = res.error || 'Hora inválida'
        return false
    }
    // Validar que la fecha no sea anterior a la mínima permitida por la dosis
    if (formAgendar.value.min_fecha) {
        try {
            const selected = new Date(formAgendar.value.fecha + 'T00:00:00')
            const minD = new Date(formAgendar.value.min_fecha + 'T00:00:00')
            if (selected < minD) {
                errorHoraAgendar.value = 'La fecha no puede ser anterior a la fecha mínima recomendada para esta dosis.'
                return false
            }
        } catch (e) {
            // ignore parse errors
        }
    }
    // Do not auto-assign suggested time; just clear error
    errorHoraAgendar.value = ''
    return true
}

const enviarAgendado = async () => {
    processingAgendar.value = true
    try {
        // Intentar crear una cita en backend (ruta sugerida). Si no existe, fallback a crear recordatorio.
        const payload = {
            dosis_id: formAgendar.value.dosis_id,
            fecha: formAgendar.value.fecha,
            hora: formAgendar.value.hora,
            centro_salud_id: formAgendar.value.centro_salud_id,
            observaciones: formAgendar.value.observaciones,
            persona_tipo: props.persona.es_usuario ? 'usuario' : 'dependiente',
            persona_id: props.persona.es_usuario ? null : props.persona.datos.id,
        }

        // En esta implementación creamos directamente un recordatorio asociado a la cita
    const datosRecordatorio = {
            titulo: formAgendar.value.titulo || `Cita - ${agendarSeleccionada.value.vacuna.nombre}`,
            mensaje: formAgendar.value.observaciones || null,
            fecha_recordatorio: formAgendar.value.fecha,
            hora_recordatorio: formAgendar.value.hora || null,
            tipo: formAgendar.value.tipo || 'vacuna_proxima',
            vacuna_id: agendarSeleccionada.value.vacuna.id,
            // Relacionar con la dosis exacta (clave nueva que guardamos en backend)
            dosis_vacuna_id: formAgendar.value.dosis_id || null,
            // Opcionales: relacionar persona (usuario o dependiente)
            dependiente_id: formAgendar.value.dependiente_id ?? (payload.persona_tipo === 'dependiente' ? payload.persona_id : null),
            // Mantener claves antiguas por compatibilidad (si se usan en frontend)
            persona_tipo: formAgendar.value.persona_tipo ?? payload.persona_tipo,
            persona_id: formAgendar.value.persona_id ?? payload.persona_id,
            // Antiqua referencia por compatibilidad
            dosis_id: formAgendar.value.dosis_id
        }

        // Validar fecha/hora antes de enviar
        const validFH = validateDateTime(formAgendar.value.fecha, formAgendar.value.hora)
        if (!validFH.valid) {
            errorHoraAgendar.value = validFH.error || 'Fecha u hora inválida'
            processingAgendar.value = false
            return
        }

        // Usar fetch para evitar que Inertia siga redirecciones del servidor
        try {
            // Intentar obtener token CSRF desde <meta name="csrf-token"> o desde cookie XSRF-TOKEN
            const tokenMeta = document.querySelector('meta[name="csrf-token"]')
            let csrf = tokenMeta ? tokenMeta.getAttribute('content') : ''
            // Si no hay meta, buscar cookie XSRF-TOKEN (Laravel sets it by default)
            if (!csrf) {
                const match = document.cookie.match(new RegExp('(^|; )XSRF-TOKEN=([^;]+)'))
                if (match) {
                    // Cookie está urlencoded
                    csrf = decodeURIComponent(match[2])
                }
            }

            const headers = {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
            if (csrf) {
                // Laravel acepta X-XSRF-TOKEN a partir de la cookie
                headers['X-XSRF-TOKEN'] = csrf
            }

            const resp = await fetch(route('paciente.recordatorios.store'), {
                method: 'POST',
                credentials: 'same-origin', // incluir cookies para que Laravel valide la sesión/CSRF
                headers,
                body: JSON.stringify(datosRecordatorio)
            })

            if (resp.ok) {
                // Intentar leer json, pero no es estrictamente necesario
                // const data = await resp.json()
                try {
                    const id = String(formAgendar.value.dosis_id || (agendarSeleccionada.value && (agendarSeleccionada.value.id || agendarSeleccionada.value.numero_dosis)))
                    if (id) agendadosLocales.value.add(id)
                } catch (e) {
                    // ignore
                }
                cerrarModalAgendar()
                toast.value = { show: true, message: 'Agendado ✓' }
                setTimeout(() => { toast.value.show = false }, 3000)
            } else {
                let errMsg = 'Error al agendar'
                try {
                    const err = await resp.json()
                    // intentar extraer mensaje legible
                    if (err && err.message) errMsg = err.message
                } catch (e) {
                    // ignore
                }
                console.error('Error creando recordatorio:', resp.status, resp.statusText)
                toast.value = { show: true, message: errMsg }
                setTimeout(() => { toast.value.show = false }, 3000)
            }
        } catch (fetchErr) {
            console.error('Error en fetch al crear recordatorio:', fetchErr)
            toast.value = { show: true, message: 'Error al agendar' }
            setTimeout(() => { toast.value.show = false }, 3000)
        } finally {
            processingAgendar.value = false
        }
    } catch (error) {
        console.error('Error agendando cita:', error)
    } finally {
        processingAgendar.value = false
    }
}

const cerrarModal = () => {
    showModal.value = false
    dosisSeleccionada.value = null
    form.reset()
}

const cargarCentrosSalud = async () => {
    try {
        const response = await fetch(route('paciente.api.centros-salud'))
        const data = await response.json()
        centrosSalud.value = data
    } catch (error) {
        console.error('Error cargando centros de salud:', error)
    }
}

const marcarComoAplicada = () => {
    processing.value = true
    // Asegurar que el form tiene el destinatario correcto
    form.persona_tipo = props.persona.es_usuario ? 'usuario' : 'dependiente'
    form.persona_id = props.persona.es_usuario ? null : props.persona.datos.id
    // Añadir opciones para preservar scroll y evitar que Inertia haga scroll arriba
    form.post(route('paciente.esquema-vacunacion.marcar-aplicada'), {
        preserveScroll: true,
        scroll: false,
        onSuccess: (page) => {
            // Capturar la dosis que acabamos de aplicar antes de resetear el modal
            const appliedDose = dosisSeleccionada.value ? { ...dosisSeleccionada.value } : null
            // Cerrar modal localmente. La página puede ser actualizada por Inertia pero sin mover el scroll.
            cerrarModal()

            // Si hay una siguiente dosis en los esquemas, abrir el modal de agendar prellenado
            try {
                if (appliedDose) {
                    // Buscar en normalizedEsquemas la próxima dosis para la misma vacuna
                    const vacunaId = appliedDose.vacuna_id || (appliedDose.vacuna && appliedDose.vacuna.id)
                    const numeroActual = appliedDose.numero_dosis || (appliedDose.dosis && appliedDose.dosis.numero_dosis) || null
                    if (vacunaId != null && numeroActual != null) {
                        // Recolectar todos los wrappers de dosis
                        const allWrappers = normalizedEsquemas.value.flatMap(es => (es.dosis || []).map(d => ({ wrapper: d, esquema: es })))
                        // Filtrar por misma vacuna y numero mayor al aplicado
                        const siguientes = allWrappers.filter(item => {
                            const inner = item.wrapper
                            const innerVacId = inner.dosis ? (inner.dosis.vacuna_id || (inner.dosis.vacuna && inner.dosis.vacuna.id)) : (inner.vacuna_id || (inner.vacuna && inner.vacuna.id))
                            const innerNum = inner.dosis ? (inner.dosis.numero_dosis || null) : (inner.numero_dosis || null)
                            return innerVacId === vacunaId && innerNum !== null && Number(innerNum) > Number(numeroActual) && !inner.aplicada
                        })
                        if (siguientes && siguientes.length > 0) {
                            // Elegir la siguiente inmediata (menor numero_dosis)
                            siguientes.sort((a, b) => {
                                const na = Number(a.wrapper.dosis ? a.wrapper.dosis.numero_dosis : a.wrapper.numero_dosis || 0)
                                const nb = Number(b.wrapper.dosis ? b.wrapper.dosis.numero_dosis : b.wrapper.numero_dosis || 0)
                                return na - nb
                            })
                            const siguiente = siguientes[0].wrapper
                            // Abrir modal de agendar para la siguiente dosis
                            abrirModalAgendar(siguiente)
                        }
                    }
                }
            } catch (e) {
                // ignore errores de búsqueda
            }

            // Si el servidor devuelve un hash o información para enfocar una dosis, intentar mantener foco en ella
            try {
                const hash = window.location.hash
                if (hash) {
                    const target = document.querySelector(hash)
                    if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' })
                }
            } catch (e) {
                // ignore
            }
        },
        onError: (errors) => {
            console.error('Error:', errors)
        },
        onFinish: () => {
            processing.value = false
        }
    })
}

onMounted(() => {
    cargarCentrosSalud()

    // Si la URL contiene un hash como #dosis-<id> o #esquema-<id>, hacer scroll suave al elemento
    try {
        const hash = window.location.hash
        if (hash) {
            const target = document.querySelector(hash)
            if (target) {
                setTimeout(() => {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' })
                }, 80)
                // si el hash es de dosis, abrir modal de agendar opcionalmente (no abrir por defecto)
            }
        }
        // También soportamos query param ?dosis=123 por si el enlace lo usa
        const params = new URLSearchParams(window.location.search)
        const dosisParam = params.get('dosis')
        if (!hash && dosisParam) {
            const el = document.getElementById('dosis-' + dosisParam)
            if (el) {
                setTimeout(() => el.scrollIntoView({ behavior: 'smooth', block: 'start' }), 80)
            }
        }
    } catch (e) {
        // ignore
    }

    // no debug runtime
})
</script>
