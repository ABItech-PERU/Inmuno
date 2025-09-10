<script setup>
import { ref, watch, onMounted, computed } from 'vue';
import {
    CheckCircleIcon,
    ExclamationTriangleIcon,
    XCircleIcon,
    InformationCircleIcon,
    XMarkIcon
} from '@heroicons/vue/24/outline';

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    type: {
        type: String,
        default: 'success', // success, error, warning, info
        validator: (value) => ['success', 'error', 'warning', 'info'].includes(value)
    },
    title: {
        type: String,
        default: ''
    },
    message: {
        type: String,
        default: ''
    },
    duration: {
        type: Number,
        default: 4000
    },
    closeable: {
        type: Boolean,
        default: true
    }
});

const emit = defineEmits(['close']);

const isVisible = ref(false);
let timeoutId = null;

// Estado interno para mensajes dinámicos
const internalTitle = ref('');
const internalMessage = ref('');
const internalType = ref('success');

// Computed para usar props o estado interno
const currentTitle = computed(() => props.title || internalTitle.value);
const currentMessage = computed(() => props.message || internalMessage.value);
const currentType = computed(() => props.type || internalType.value);

const iconComponents = {
    success: CheckCircleIcon,
    error: XCircleIcon,
    warning: ExclamationTriangleIcon,
    info: InformationCircleIcon
};

const colorClasses = {
    success: {
        container: 'bg-green-50 border-green-200',
        icon: 'text-green-400',
        title: 'text-green-800',
        message: 'text-green-700',
        closeBtn: 'text-green-500 hover:text-green-600'
    },
    error: {
        container: 'bg-red-50 border-red-200',
        icon: 'text-red-400',
        title: 'text-red-800',
        message: 'text-red-700',
        closeBtn: 'text-red-500 hover:text-red-600'
    },
    warning: {
        container: 'bg-yellow-50 border-yellow-200',
        icon: 'text-yellow-400',
        title: 'text-yellow-800',
        message: 'text-yellow-700',
        closeBtn: 'text-yellow-500 hover:text-yellow-600'
    },
    info: {
        container: 'bg-cyan-50 border-cyan-200',
        icon: 'text-cyan-400',
        title: 'text-cyan-800',
        message: 'text-cyan-700',
        closeBtn: 'text-cyan-500 hover:text-cyan-600'
    }
};

const close = () => {
    isVisible.value = false;
    if (timeoutId) {
        clearTimeout(timeoutId);
    }
    setTimeout(() => {
        emit('close');
    }, 300);
};

const startTimeout = () => {
    if (props.duration > 0) {
        timeoutId = setTimeout(() => {
            close();
        }, props.duration);
    }
};

watch(() => props.show, (newValue) => {
    if (newValue) {
        isVisible.value = true;
        startTimeout();
    } else {
        isVisible.value = false;
    }
});

onMounted(() => {
    if (props.show) {
        isVisible.value = true;
        startTimeout();
    }
});

// Método para mostrar toast dinámicamente
const show = (message, type = 'success', title = '') => {
    internalMessage.value = message;
    internalType.value = type;
    internalTitle.value = title;
    isVisible.value = true;
    startTimeout();
};

// Exponer métodos para uso con ref
defineExpose({
    show,
    close
});
</script>

<template>
    <Teleport to="body">
        <transition
            enter-active-class="transform ease-out duration-300 transition"
            enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
            leave-active-class="transition ease-in duration-100"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="isVisible"
                class="fixed top-4 right-4 z-50 max-w-sm w-full"
            >
                <div
                    class="rounded-lg border shadow-lg p-4"
                    :class="colorClasses[currentType].container"
                >
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <component
                                :is="iconComponents[currentType]"
                                class="h-5 w-5"
                                :class="colorClasses[currentType].icon"
                            />
                        </div>
                        <div class="ml-3 flex-1">
                            <h3
                                v-if="currentTitle"
                                class="text-sm font-medium"
                                :class="colorClasses[currentType].title"
                            >
                                {{ currentTitle }}
                            </h3>
                            <p
                                v-if="currentMessage"
                                class="text-sm"
                                :class="[currentTitle ? 'mt-1' : '', colorClasses[currentType].message]"
                            >
                                {{ currentMessage }}
                            </p>
                        </div>
                        <div v-if="closeable" class="ml-4 flex-shrink-0">
                            <button
                                @click="close"
                                class="inline-flex rounded-md p-1.5 focus:outline-none focus:ring-2 focus:ring-offset-2"
                                :class="[colorClasses[currentType].closeBtn, `focus:ring-${currentType === 'info' ? 'cyan' : currentType}-500`]"
                            >
                                <span class="sr-only">Cerrar</span>
                                <XMarkIcon class="h-4 w-4" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </Teleport>
</template>
