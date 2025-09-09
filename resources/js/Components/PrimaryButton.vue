<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <button :type="type" class="inline-flex items-center justify-center px-5 py-2
           bg-cyan-500 text-white font-semibold text-sm uppercase tracking-wide
           rounded-lg shadow-md
           hover:bg-cyan-600 active:bg-cyan-700
           focus:outline-none focus:ring-1 focus:ring-cyan-400 focus:ring-offset-2
           disabled:opacity-50 disabled:cursor-not-allowed
           transition-all duration-200 ease-in-out">
        <slot />
    </button>
</template>
