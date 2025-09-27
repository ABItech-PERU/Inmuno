<script setup>
import InfoVacuna from '@/Components/InfoVacuna.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout title="Todo Sobre Vacunas">
        <InfoVacuna :showImage="true" />
    </AppLayout>
</template>
