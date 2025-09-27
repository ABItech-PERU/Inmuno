<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InfoVacuna from '@/Components/InfoVacuna.vue';
</script>

<template>
  <GuestLayout title="Sobre las vacunas" backRoute="/">
    <InfoVacuna title="Sobre las vacunas" :showImage="true" />
  </GuestLayout>
</template>
