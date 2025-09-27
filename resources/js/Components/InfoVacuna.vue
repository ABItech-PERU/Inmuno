<script setup>
import { ref, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import { CalendarDaysIcon, LightBulbIcon, CheckIcon, QuestionMarkCircleIcon, ShieldCheckIcon, MapPinIcon } from '@heroicons/vue/24/outline';

defineProps({
    title: { type: String, default: 'Sobre las vacunas' },
    showImage: { type: Boolean, default: true },
});

// track which FAQ entries are open; open first by default for discoverability
const faqsOpen = ref({ preg1: true, preg2: false, preg3: false });
function toggleFaq(key) {
    faqsOpen.value[key] = !faqsOpen.value[key];
}

function setAll(open) {
    faqsOpen.value = { preg1: open, preg2: open, preg3: open };
}

onMounted(() => {
    // On small screens, expand all FAQs so users that don't see the dropdown know the content
    try {
        if (window.innerWidth && window.innerWidth < 768) {
            setAll(true);
        }
    } catch (e) {
        // ignore (SSR)
    }
});
</script>

<template>
    <div class="max-w-5xl mx-auto px-4 py-6 sm:px-6 text-gray-800">
        <!-- Hero -->
        <div class="rounded-lg overflow-hidden bg-white shadow mb-6">
            <div class="md:flex">
                <div v-if="showImage"
                    class="md:w-1/2 bg-[url('/img/vacuna.webp')] bg-cover bg-center h-48 md:h-auto"></div>
                <div class="p-4 sm:p-6 md:w-1/2">
                    <h1 class="text-2xl font-extrabold text-gray-900">{{ title }}</h1>
                    <p class="mt-2 text-gray-600">Todo lo que necesitas saber para vacunarte: beneficios, seguridad,
                        contraindicaciones y cómo empezar.</p>
                    <div class="mt-4 flex flex-col sm:flex-row sm:items-center gap-2">
                        <Link href="/register"
                            class="inline-flex justify-center w-full sm:w-auto items-center px-4 py-2 bg-cyan-600 text-white rounded-md" aria-label="Iniciar mis vacunaciones">Iniciar mis
                        vacunaciones</Link>
                        <a href="#video" class="inline-flex justify-center w-full sm:w-auto items-center px-4 py-2 border rounded-md" aria-label="Ver video">Ver video</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Highlights -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="p-4 bg-white rounded-lg shadow text-center">
                <h3 class="font-semibold text-cyan-600">Protección</h3>
                <p class="text-sm text-gray-600 mt-2">Reduce riesgo de enfermedades graves.</p>
            </div>
            <div class="p-4 bg-white rounded-lg shadow text-center">
                <h3 class="font-semibold text-cyan-600">Seguridad</h3>
                <p class="text-sm text-gray-600 mt-2">Ensayos rigurosos y monitoreo constante.</p>
            </div>
            <div class="p-4 bg-white rounded-lg shadow text-center">
                <h3 class="font-semibold text-cyan-600">Comunidad</h3>
                <p class="text-sm text-gray-600 mt-2">Contribuyes a la inmunidad colectiva.</p>
            </div>
        </div>

        <!-- Sección: ¿Qué es el esquema de vacunación? (mover arriba de 'Cómo funcionan') -->
    <section class="mb-6 bg-white rounded-lg shadow p-4 sm:p-6">
            <h2 class="text-xl font-semibold flex items-center gap-2">
                <CalendarDaysIcon class="h-5 w-5 text-cyan-600" />¿Qué es el esquema de vacunación?
            </h2>
            <p class="mt-2 text-gray-600">El esquema de vacunación es el calendario de dosis recomendado para proteger a
                cada persona según su edad.</p>
            <div class="mt-4">
                <Link href="/paciente/esquema-vacunacion"
                    class="inline-flex items-center px-3 py-2 bg-cyan-600 text-white rounded-md text-sm">
                <CheckIcon class="h-4 w-4 mr-2" />
                Ver mis esquemas
                </Link>
                <p class="text-xs text-gray-500 mt-2">En esta sección podrás ver tu esquema completo y el de tus
                    dependientes (si aplica).</p>
            </div>
        </section>

        <!-- Educational sections inspired by UNICEF (paraphrased) -->
        <section class="mb-6 bg-white rounded-lg shadow p-4 sm:p-6">
            <h2 class="text-xl font-semibold flex items-center gap-2"><LightBulbIcon class="h-5 w-5 text-cyan-600" />Cómo funcionan las vacunas</h2>
            <p class="mt-2 text-gray-600">Las vacunas enseñan al sistema inmunitario a reconocer y combatir gérmenes sin
                causar la enfermedad grave. Pueden contener virus inactivados, proteínas o instrucciones seguras que
                provocan una respuesta protectora.</p>
        </section>

    <section class="mb-6 bg-white rounded-lg shadow p-4 sm:p-6">
            <h2 class="text-xl font-semibold flex items-center gap-2"><CheckIcon class="h-5 w-5 text-cyan-600" />Consejos para padres</h2>
            <ul class="list-disc list-inside text-gray-600 mt-2">
                <li>Lleva siempre el carnet de vacunación a las consultas.</li>
                <li>Informa al personal de salud sobre alergias o reacciones anteriores.</li>
                <li>No retrases las vacunas programadas salvo indicación médica.</li>
                <li>Si el niño presenta fiebre alta, consulta antes de vacunar.</li>
            </ul>
        </section>

    <section class="mb-6 bg-white rounded-lg shadow p-4 sm:p-6">
            <h2 class="text-xl font-semibold flex items-center gap-2"><QuestionMarkCircleIcon class="h-5 w-5 text-cyan-600" />Mitos y realidades</h2>
            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-2 text-left text-gray-700">Mito</th>
                            <th class="px-4 py-2 text-left text-gray-700">Realidad</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100 text-gray-600">
                        <tr>
                            <td class="px-4 py-3 align-top"><strong>Las vacunas son más peligrosas que las
                                    enfermedades</strong></td>
                            <td class="px-4 py-3">Las vacunas salvan millones de vidas; los riesgos serios son raros y
                                son monitorizados por las autoridades de salud.</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 align-top"><strong>No hace falta vacunar si la enfermedad ya no se
                                    ve</strong></td>
                            <td class="px-4 py-3">La vacunación mantiene las enfermedades controladas; si se detiene la
                                vacunación, las enfermedades pueden reaparecer.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Interactive FAQ (accordion) -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-xl font-semibold">Preguntas frecuentes</h2>
                <div class="text-sm">
                    <button class="text-cyan-600 underline mr-3" @click.prevent="setAll(true)"
                        aria-label="Expandir todas">Expandir todo</button>
                    <button class="text-cyan-600 underline" @click.prevent="setAll(false)"
                        aria-label="Contraer todas">Contraer todo</button>
                </div>
            </div>
            <div class="text-xs text-gray-500 mb-2">Toca una pregunta para ver la respuesta. En dispositivos móviles las
                respuestas se mostrarán por defecto.</div>
            <div class="space-y-2" role="list">
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <button class="w-full text-left p-4 flex justify-between items-center" @click="toggleFaq('preg1')"
                        :aria-expanded="!!faqsOpen.preg1" aria-controls="faq-preg1">
                        <span class="font-medium">¿Por qué debo vacunarme?</span>
                        <span v-if="faqsOpen.preg1">-</span>
                        <span v-else>+</span>
                    </button>
                    <div id="faq-preg1" v-show="faqsOpen.preg1" class="p-4 border-t text-sm text-gray-600">Las vacunas
                        protegen contra enfermedades graves, previenen hospitalizaciones y salvan vidas. También reducen
                        la transmisión.</div>
                </div>

                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <button class="w-full text-left p-4 flex justify-between items-center" @click="toggleFaq('preg2')"
                        :aria-expanded="!!faqsOpen.preg2" aria-controls="faq-preg2">
                        <span class="font-medium">¿Qué efectos secundarios pueden aparecer?</span>
                        <span v-if="faqsOpen.preg2">-</span>
                        <span v-else>+</span>
                    </button>
                    <div id="faq-preg2" v-show="faqsOpen.preg2" class="p-4 border-t text-sm text-gray-600">Los más
                        comunes son dolor en el sitio de la inyección y fiebre leve por 1-2 días. Reacciones graves son
                        raras.</div>
                </div>

                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <button class="w-full text-left p-4 flex justify-between items-center" @click="toggleFaq('preg3')"
                        :aria-expanded="!!faqsOpen.preg3" aria-controls="faq-preg3">
                        <span class="font-medium">¿Dónde puedo vacunarme?</span>
                        <span v-if="faqsOpen.preg3">-</span>
                        <span v-else>+</span>
                    </button>
                    <div id="faq-preg3" v-show="faqsOpen.preg3" class="p-4 border-t text-sm text-gray-600">
                        En centros de salud, hospitales y campañas comunitarias.
                        <div class="mt-3">
                            <Link href="/centros-de-salud"
                                class="inline-flex items-center gap-2 px-3 py-2 bg-cyan-600 text-white rounded-md shadow-sm text-sm">
                            <MapPinIcon class="h-4 w-4" />
                            Abrir buscador de centros
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Prominent CTA: buscar centros de salud -->
        <div class="mb-6">
            <div
                class="rounded-lg overflow-hidden bg-gradient-to-r from-cyan-50 to-white border border-cyan-100 p-4 sm:p-6 flex flex-col md:flex-row items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-cyan-700">¿Buscas un lugar para vacunarte?</h3>
                    <p class="text-sm text-gray-600 mt-1">Encuentra centros de salud, hospitales y campañas cercanas en
                        el mapa y filtra por departamento, provincia o distrito.</p>
                </div>
                <div class="flex-shrink-0">
                    <Link href="/centros-de-salud"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-cyan-600 text-white rounded-md shadow-md">
                    <MapPinIcon class="h-4 w-4" />
                    Buscar centros cercanos
                    </Link>
                </div>
            </div>
        </div>

        <!-- Videos en dos columnas -->
        <div id="video" class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white rounded-lg shadow p-4">
                <h3 class="text-sm font-semibold text-gray-800 mb-2">Importancia de las vacunas</h3>
                <div class="aspect-video">
                    <iframe class="w-full h-full" src="https://www.youtube.com/embed/bABC5ULXs_s"
                        title="Importancia de las Vacunas" frameborder="0" allowfullscreen></iframe>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4">
                <h3 class="text-sm font-semibold text-gray-800 mb-2">Conoce el Esquema Nacional de Vacunación —
                    ¡#PonteAlDía!</h3>
                <div class="aspect-video">
                    <iframe class="w-full h-full" src="https://www.youtube.com/embed/RW13j5QeYYE"
                        title="Conoce el Esquema Nacional de Vacunación" frameborder="0" allowfullscreen></iframe>
                </div>
            </div>
        </div>



        <!-- removed duplicate educational blocks -->

        <!-- CTA: mover al final para llamar a la acción después de la información -->
    <div class="mb-6 bg-white rounded-lg shadow p-4 sm:p-6 text-center">
            <h3 class="text-lg font-semibold">Comienza tu camino hacia la protección</h3>
            <p class="text-sm text-gray-600 mt-2">Regístrate y completa tu esquema de vacunación. Es rápido y te ayuda a
                protegerte a ti y a tus seres queridos.</p>
            <div class="mt-4">
                <Link href="/register"
                    class="inline-flex items-center px-6 py-3 bg-cyan-600 text-white rounded-md text-sm">Iniciar mis
                vacunaciones</Link>
            </div>
        </div>

        <footer class="text-sm text-gray-500 mt-6 text-center">
            <p>Fuentes: Ministerio de Salud, OMS y UNICEF (contenido parafraseado). Información orientativa; consulta
                siempre a tu profesional de salud.</p>
        </footer>
    </div>
</template>
