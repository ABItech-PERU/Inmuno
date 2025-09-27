<template>
    <Head title="Centros de Salud" />
  <!-- On lg+ use full viewport height and hide page scroll; on smaller screens allow normal scrolling -->
  <div class="p-6 grid grid-cols-12 gap-6 lg:h-screen lg:overflow-hidden">
  <aside class="col-span-12 lg:col-span-4 bg-white rounded-lg shadow-sm border md:border-gray-200 border-transparent p-4 flex flex-col min-h-0 lg:h-full">
      <div class="flex items-center justify-between mb-4">
      <h1 class="text-lg font-bold text-gray-900">Centros de salud</h1>
      <button @click="refresh" class="inline-flex items-center px-3 py-2 bg-cyan-600 text-white text-sm font-medium rounded-md hover:bg-cyan-700 transition-colors">Limpiar</button>
          </div>

  <div class="space-y-3">
        <div>
          <label class="block text-sm font-medium text-gray-500">Departamento</label>
          <select v-model="filters.departamento" @change="onDepartamentoChange" class="mt-1 block w-full rounded-md border border-gray-300 p-2 bg-white focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 text-sm">
            <option value="">Todos</option>
            <option v-for="d in departamentos" :key="d.codigo" :value="d.codigo">{{ d.nombre }}</option>
          </select>
        </div>

        <div v-if="filters.departamento">
          <label class="block text-sm font-medium text-gray-500">Provincia</label>
          <select v-model="filters.provincia" @change="onProvinciaChange" class="mt-1 block w-full rounded-md border border-gray-300 p-2 bg-white focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 text-sm">
            <option value="">Todas</option>
            <option v-for="p in provincias" :key="p.codigo" :value="p.codigo">{{ p.nombre }}</option>
          </select>
        </div>

        <div v-if="filters.provincia">
          <label class="block text-sm font-medium text-gray-500">Distrito</label>
          <select v-model="filters.distrito" @change="loadCentros" class="mt-1 block w-full rounded-md border border-gray-300 p-2 bg-white focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 text-sm">
            <option value="">Todos</option>
            <option v-for="d in distritos" :key="d.codigo_ubigeo" :value="d.codigo_ubigeo">{{ d.nombre }}</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-500">Buscar</label>
          <input v-model="filters.search" @input="debouncedLoad" placeholder="Ej. Hospital, Posta" class="mt-1 block w-full rounded-sm border border-gray-300 p-2 focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 text-sm" />
        </div>
      </div>

  <div class="mt-4 flex-1 overflow-auto">
        <div v-if="loading" class="text-center py-8">
          <svg class="animate-spin h-6 w-6 mx-auto text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path></svg>
        </div>

        <div v-else class="space-y-2 mt-2">
          <div v-if="centros.length===0" class="text-sm text-gray-500">No se encontraron centros para estos filtros.</div>
          <article v-for="c in centros" :key="c.id" class="p-3 bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow cursor-pointer" @click="() => onCardClick(c)">
            <div class="flex items-start md:items-center justify-between">
              <div class="flex-1 min-w-0 pr-4">
                <h3 class="text-sm md:text-md font-semibold text-gray-900">{{ c.nombre }}</h3>
                <div class="flex items-center">
                  <p class="text-sm text-gray-600 truncate flex-1 min-w-0">{{ c.tipo }} · {{ c.nivel_atencion || '' }}</p>
                  <!-- phone inline (always visible) -->
                  <div class="inline-block text-sm text-gray-500 ml-3 flex-shrink-0" v-html="formatTelefono(c.telefono)"></div>
                </div>
                <p class="text-sm text-gray-500 mt-1 break-words whitespace-normal">{{ formatDireccion(c) }}</p>
              </div>
              <!-- card click opens fullscreen map on mobile and centers on the selected centro -->
            </div>
          </article>
        </div>
      </div>
    </aside>

    <main class="col-span-12 lg:col-span-8 h-full min-h-0 lg:h-full">
      <div class="rounded-lg overflow-hidden shadow h-full">
        <!-- Normal map slot: on mobile we give it a fixed height so it is visible while the aside scrolls -->
        <div ref="normalSlot" class="w-full h-64 md:h-full overflow-hidden">
          <div id="map" class="w-full h-full"></div>
        </div>
      </div>

      <!-- Fullscreen overlay for mobile map (moved DOM node when opened) -->
      <div v-if="isMapFullscreen" class="fixed inset-0 z-50 bg-white flex flex-col">
        <div class="p-3 flex items-center justify-between border-b">
          <h2 class="font-semibold">Mapa</h2>
          <div class="space-x-2">
            <button @click="closeMapFullscreen" class="px-3 py-1 bg-gray-100 rounded">Cerrar</button>
          </div>
        </div>
        <div ref="fullSlot" class="flex-1 overflow-hidden">
          <!-- the #map element will be moved here while fullscreen -->
        </div>
      </div>

      <!-- Floating button on small screens to open fullscreen map -->
      <button v-if="!isMapFullscreen" @click="openMapFullscreen" class="md:hidden fixed bottom-6 right-4 z-40 bg-cyan-600 text-white p-3 rounded-full shadow-lg">
        Ver mapa
      </button>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, nextTick } from 'vue';
import { Head } from '@inertiajs/vue3';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
// Fix Leaflet default icon paths when bundling with Vite/webpack
import markerIcon from 'leaflet/dist/images/marker-icon.png';
import markerIcon2x from 'leaflet/dist/images/marker-icon-2x.png';
import markerShadow from 'leaflet/dist/images/marker-shadow.png';

L.Icon.Default.mergeOptions({
  iconUrl: markerIcon,
  iconRetinaUrl: markerIcon2x,
  shadowUrl: markerShadow,
});

// pequeño debounce local para evitar dependencia extra
function debounce(fn, wait = 300) {
  let t;
  return (...args) => {
    clearTimeout(t);
    t = setTimeout(() => fn(...args), wait);
  };
}

const map = ref(null);
// map of centro id -> marker
const markers = ref({});
const centros = ref([]);
const departamentos = ref([]);
const provincias = ref([]);
const distritos = ref([]);
const loading = ref(false);

const filters = ref({ departamento: '', provincia: '', distrito: '', search: '' });

const isMapFullscreen = ref(false);
const normalSlot = ref(null);
const fullSlot = ref(null);
let mapPlaceholder = null;
let intersectionObserver = null;

function normalizePhone(raw) {
  if (!raw) return null;
  // quitar espacios, paréntesis, guiones y caracteres no numéricos
  const digits = raw.replace(/[^0-9+]/g, '');
  if (!digits) return null;
  // si no tiene prefijo y empieza con 0 o 9 (número local peruano), añadimos +51
  if (!digits.startsWith('+')) {
    // heurística simple: si tiene 9 dígitos o comienza con 9 -> celular; si 7-8 -> fijo
    const nd = digits.replace(/^0+/, '');
    if (nd.length === 9 || nd.startsWith('9')) return '+51' + nd;
    if (nd.length >= 6 && nd.length <= 8) return '+51' + nd; // asumir prefijo nacional
  }
  return digits;
}

function formatTelefono(raw) {
  const phone = normalizePhone(raw);
  if (!phone) return '<span class="text-gray-400">Sin teléfono</span>';
  // mostrar número legible: insertar espacios
  const display = phone.replace(/(\+?\d{2})(\d{3})(\d+)/, '$1 $2 $3');
  return `<a href="tel:${phone}" class="text-blue-600 hover:underline">${display}</a>`;
}

function formatDireccion(c) {
  if (!c) return '';
  const parts = [];
  if (c.direccion) parts.push(c.direccion.trim());
  // If both district and province exist, join them with ' - '
  if (c.distrito_nombre && c.provincia_nombre) {
    parts.push(`${c.distrito_nombre.trim()} - ${c.provincia_nombre.trim()}`);
  } else {
    if (c.distrito_nombre) parts.push(c.distrito_nombre.trim());
    if (c.provincia_nombre) parts.push(c.provincia_nombre.trim());
  }
  return parts.join(', ');
}

function escapeHtml(unsafe) {
  return String(unsafe)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');
}

function initMap() {
  map.value = L.map('map').setView([-9.19, -75.02], 5);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map.value);
}

async function loadCatalogs() {
  try {
    const res = await fetch('/api/departamentos');
    if (res.ok) departamentos.value = await res.json();
  } catch (e) {}
}

async function onDepartamentoChange() {
  filters.value.provincia = '';
  filters.value.distrito = '';
  provincias.value = [];
  distritos.value = [];
  if (!filters.value.departamento) return loadCentros();
  try {
    const res = await fetch(`/api/provincias/${filters.value.departamento}`);
    if (res.ok) provincias.value = await res.json();
  } catch (e) {}
  loadCentros();
}

async function onProvinciaChange() {
  filters.value.distrito = '';
  distritos.value = [];
  if (!filters.value.provincia) return loadCentros();
  try {
    const res = await fetch(`/api/distritos/${filters.value.provincia}`);
    if (res.ok) distritos.value = await res.json();
  } catch (e) {}
  loadCentros();
}

const debouncedLoad = debounce(() => loadCentros(), 450);

async function loadCentros() {
  loading.value = true;
  const params = new URLSearchParams();
  if (filters.value.departamento) params.append('departamento', filters.value.departamento);
  if (filters.value.provincia) params.append('provincia', filters.value.provincia);
  if (filters.value.distrito) params.append('distrito', filters.value.distrito);
  if (filters.value.search) params.append('search', filters.value.search);
  try {
    const res = await fetch(`/api/centros-de-salud/list?${params.toString()}`);
    if (!res.ok) return;
    const json = await res.json();
    centros.value = json.data || [];
    renderMarkers();
  } finally {
    loading.value = false;
  }
}

function clearMarkers() {
  Object.values(markers.value).forEach(m => m.remove());
  markers.value = {};
}

function renderMarkers() {
  clearMarkers();
  const group = L.featureGroup();
  for (const c of centros.value) {
    if (c.latitud && c.longitud) {
      const marker = L.marker([parseFloat(c.latitud), parseFloat(c.longitud)]).addTo(map.value);
  const popupHtml = `<div style="min-width:220px"><div style="font-weight:600;color:#111827">${escapeHtml(c.nombre)}</div><div style="font-size:12px;color:#4b5563;margin-top:2px;display:flex;align-items:center;gap:8px">${escapeHtml(c.tipo + ' · ' + (c.nivel_atencion || ''))}<span style="font-size:12px;color:#374151">${formatTelefono(c.telefono)}</span></div><div style="font-size:12px;color:#6b7280;margin-top:6px">${escapeHtml(formatDireccion(c))}</div></div>`;
      marker.bindPopup(popupHtml);
      markers.value[c.id] = marker;
      group.addLayer(marker);
    }
  }
  if (group.getLayers().length) {
    map.value.fitBounds(group.getBounds().pad(0.2));
  }
  // Forzar recalculo del tamaño del mapa (evita scroll/recortes en layouts flex)
  try {
    // small timeout to let DOM updates settle
    setTimeout(() => {
      if (map.value && typeof map.value.invalidateSize === 'function') map.value.invalidateSize();
    }, 120);
  } catch (e) {
    // noop
  }
}

function zoomToCentro(c) {
  if (c.latitud && c.longitud) {
    map.value.setView([parseFloat(c.latitud), parseFloat(c.longitud)], 16);
  }
}

function openPopupForCentro(c) {
  try {
    const marker = markers.value[c.id];
    if (!marker) return;
    marker.openPopup();
    if (c.latitud && c.longitud) {
      map.value.setView([parseFloat(c.latitud), parseFloat(c.longitud)], 16);
    }
  } catch (e) {}
}

function handleSelectCentro(c) {
  // center and open popup
  zoomToCentro(c);
  // small delay to ensure map recenters before opening popup
  setTimeout(() => openPopupForCentro(c), 150);
}

function refresh() {
  // reset filters
  filters.value.departamento = '';
  filters.value.provincia = '';
  filters.value.distrito = '';
  filters.value.search = '';
  provincias.value = [];
  distritos.value = [];
  loadCentros();
}

onMounted(() => {
  initMap();
  loadCatalogs();
  loadCentros();
  // Force Leaflet to recalc size after initial render
  setTimeout(() => {
    try {
      if (map.value && typeof map.value.invalidateSize === 'function') map.value.invalidateSize();
    } catch (e) {}
  }, 150);
  // Recalculate on window resize
  window.addEventListener('resize', () => {
    try { if (map.value && typeof map.value.invalidateSize === 'function') map.value.invalidateSize(); } catch (e) {}
  });
  // IntersectionObserver to invalidate size when #map becomes visible (helps mobile when scrolled into view)
  try {
    intersectionObserver = new IntersectionObserver(entries => {
      for (const entry of entries) {
        if (entry.isIntersecting) {
          try { if (map.value && typeof map.value.invalidateSize === 'function') map.value.invalidateSize(); } catch (e) {}
        }
      }
    }, { threshold: 0.1 });
    // observe the container that holds the map
    nextTick(() => {
      const node = normalSlot.value && normalSlot.value.querySelector ? normalSlot.value.querySelector('#map') : document.getElementById('map');
      if (node && intersectionObserver) intersectionObserver.observe(node);
    });
  } catch (e) {}
});

onBeforeUnmount(() => {
  try { window.removeEventListener('resize', () => {}); } catch (e) {}
  try { if (intersectionObserver) intersectionObserver.disconnect(); } catch (e) {}
});

async function openMapFullscreen() {
  // move the #map node into the fullscreen slot
  if (isMapFullscreen.value) return;
  const mapEl = document.getElementById('map');
  if (!mapEl) return;
  // create placeholder where the map was
  mapPlaceholder = document.createElement('div');
  mapPlaceholder.style.width = '100%';
  mapPlaceholder.style.height = '100%';
  mapEl.parentNode.replaceChild(mapPlaceholder, mapEl);
  // show overlay first so fullSlot is mounted
  isMapFullscreen.value = true;
  await nextTick();
  // now append the map element into the fullscreen slot
  if (fullSlot.value) fullSlot.value.appendChild(mapEl);
  // allow DOM to update then invalidate size
  setTimeout(() => { try { if (map.value && typeof map.value.invalidateSize === 'function') map.value.invalidateSize(); } catch (e) {} }, 120);
}

function closeMapFullscreen() {
  if (!isMapFullscreen.value) return;
  // try to find the map element either by id or inside the full slot
  let mapEl = document.getElementById('map');
  if (!mapEl && fullSlot.value) mapEl = fullSlot.value.querySelector('#map');
  if (!mapEl) {
    // nothing to restore
    isMapFullscreen.value = false;
    return;
  }
  // restore placeholder back to original container
  if (mapPlaceholder && mapPlaceholder.parentNode) {
    mapPlaceholder.parentNode.replaceChild(mapEl, mapPlaceholder);
  } else if (normalSlot.value) {
    // fallback: append to normalSlot
    normalSlot.value.appendChild(mapEl);
  }
  isMapFullscreen.value = false;
  setTimeout(() => { try { if (map.value && typeof map.value.invalidateSize === 'function') map.value.invalidateSize(); } catch (e) {} }, 120);
}

async function openMapForCentro(c) {
  // Move map to fullscreen and center on the centro, then open popup
  await openMapFullscreen();
  // after map moved, center and open popup
  setTimeout(() => {
    try {
      zoomToCentro(c);
      openPopupForCentro(c);
    } catch (e) {}
  }, 200);
}

function onCardClick(c) {
  // if viewport is desktop (md and up) open popup inline, else open fullscreen modal
  if (window.matchMedia && window.matchMedia('(min-width: 768px)').matches) {
    // desktop/tablet: center and open popup in the inline map
    handleSelectCentro(c);
  } else {
    // mobile: open fullscreen with map centered on centro
    openMapForCentro(c);
  }
}
</script>

<style scoped>
#map { border: 1px solid #e5e7eb; }

</style>
