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
                                    En {{ estadisticas.proxima_vacuna.dias_para_aplicacion }} días
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
                                                        <div class="flex flex-row flex-wrap lg:flex-nowrap lg:items-center lg:space-x-2 w-full lg:w-auto mt-3 lg:mt-0 -mx-1 justify-end">
                                                        <div v-if="shouldShowMarkApplied(dosis)" class="px-1 w-1/2 lg:w-auto">
                                                            <button @click="abrirModalAplicacion(dosis.dosis)"
                                                                class="inline-flex items-center px-3 py-2 border border-transparent text-xs sm:text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 w-full lg:w-auto justify-center">
                                                                Marcar como aplicada
                                                            </button>
                                                        </div>
                                                        <div v-if="dosis.tiene_recordatorio && isFirstPendingForVaccine(esquema, dosis)" class="px-1 w-1/2 lg:w-auto">
                                                            <button disabled
                                                                class="inline-flex items-center px-3 py-2 border border-transparent text-xs sm:text-xs font-medium rounded-md text-white bg-gray-400 w-full lg:w-auto justify-center opacity-80 cursor-not-allowed">
                                                                <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                                </svg>
                                                                Agendado
                                                            </button>
                                                        </div>
                                                        <div v-else-if="shouldShowAgendar(dosis)" class="px-1 w-1/2 lg:w-auto">
                                                            <button @click.prevent="abrirModalAgendar(dosis.dosis)"
                                                                class="inline-flex items-center px-3 py-2 border border-transparent text-xs sm:text-xs font-medium rounded-md text-white bg-cyan-600 hover:bg-cyan-700 w-full lg:w-auto justify-center">
                                                                <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                </svg>
                                                                Agendar
                                                            </button>
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
                                <input v-model="formAgendar.fecha" type="date" required :min="todayLocal" @change="validarFechaHoraAgendar"
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

const formatDate = (dateString) => {
    if (!dateString) return ''
    const date = new Date(dateString)
    return date.toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    })
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
    const edadReq = dosisObj?.dosis && (dosisObj.dosis.edad_aplicacion !== null && dosisObj.dosis.edad_aplicacion !== undefined) ? Number(dosisObj.dosis.edad_aplicacion) : null
    if (edadReq === null) return true // sin edad mínima -> aplicable en cualquier momento
    return personaAgeMonths.value >= edadReq
}

// Si la dosis está retrasada respecto a la edad del paciente
const isOverdueByAge = (dosisObj) => {
    const edadReq = dosisObj?.dosis && (dosisObj.dosis.edad_aplicacion !== null && dosisObj.dosis.edad_aplicacion !== undefined) ? Number(dosisObj.dosis.edad_aplicacion) : null
    if (edadReq === null) return false
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
    if (dosisObj.dosis && (dosisObj.dosis.edad_aplicacion === null || dosisObj.dosis.edad_aplicacion === undefined)) {
        // sólo si no está aplicada ni ya tiene recordatorio
        return !dosisObj.aplicada && !dosisObj.tiene_recordatorio
    }

    // Si no cumple condiciones de aplicación ahora, permitir agendar
    if (!dosisObj.puede_aplicar) return true
    if (isOverdueByAge(dosisObj)) return true
    return false
}

const abrirModalAplicacion = (dosis) => {
    dosisSeleccionada.value = dosis
    form.dosis_id = dosis.id
    form.fecha_aplicacion = new Date().toISOString().split('T')[0] // Fecha actual
    showModal.value = true
}

const abrirModalAgendar = (dosis) => {
    agendarSeleccionada.value = dosis
    formAgendar.value.dosis_id = dosis.id
    formAgendar.value.centro_salud_id = ''
    // Prefill title con formato 'Vacuna - Dosis N'
    try {
        formAgendar.value.titulo = `${dosis.vacuna.nombre} - Dosis ${dosis.numero_dosis}`
    } catch (e) {
        formAgendar.value.titulo = ''
    }
    // Tipo por defecto para el recordatorio en este modal
    formAgendar.value.tipo = 'vacuna_proxima'
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
            dependiente_id: payload.persona_tipo === 'dependiente' ? payload.persona_id : null,
            // Mantener claves antiguas por compatibilidad (si se usan en frontend)
            persona_tipo: payload.persona_tipo,
            persona_id: payload.persona_id,
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

        router.post(route('paciente.recordatorios.store'), datosRecordatorio, {
            onSuccess: () => {
                cerrarModalAgendar()
                // Recargar la página de show de la persona actual para ver cambios
                // Usamos la ruta presente en el servidor: paciente.esquema-vacunacion.show
                router.reload()
                alert('Recordatorio (cita) creado correctamente.');
            },
            onError: (errors) => {
                console.error('Error creando recordatorio:', errors)
                alert('Error al crear recordatorio. Revisa la consola para más detalles.');
            },
            onFinish: () => {
                processingAgendar.value = false
            }
        })
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
    form.post(route('paciente.esquema-vacunacion.marcar-aplicada'), {
        onSuccess: () => {
            cerrarModal()
            // La página se recargará automáticamente con los nuevos datos
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
})
</script>
