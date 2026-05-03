<script setup>
import { ref, onMounted } from 'vue';

defineProps({
    modelValue: String,
    options: {
        type: Array,
        default: () => [], // [{ value: '', label: '' }]
    },
});

defineEmits(['update:modelValue']);

const select = ref(null);

onMounted(() => {
    if (select.value.hasAttribute('autofocus')) {
        select.value.focus();
    }
});

defineExpose({ focus: () => select.value.focus() });
</script>

<template>
    <select
        ref="select"
        class="border-gray-300 mt-1 focus:border-cyan-500 py-3 focus:ring-cyan-500 rounded-sm shadow-sm w-full"
        :value="modelValue"
        @change="$emit('update:modelValue', $event.target.value)"
    >
        <option value="" disabled>Seleccione...</option>
        <option v-for="option in options" :key="option.value" :value="option.value">
            {{ option.label }}
        </option>
    </select>
</template>
