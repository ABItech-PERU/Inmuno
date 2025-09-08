<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';

const props = defineProps({
    user: Object,
});

const form = useForm({
    tipo_documento: props.user?.tipo_documento || '',
    numero_documento: props.user?.numero_documento || '',
    name: props.user?.name || '',
    apellidos: props.user?.apellidos || '',
    fecha_nacimiento: props.user?.fecha_nacimiento || '',
    fecha_emision: props.user?.fecha_emision || '',
    telefono: props.user?.telefono || '',
    genero: props.user?.genero || '',
});

const submit = () => {
    form.post(route('completar.registro'), {
        onSuccess: () => {
            form.reset();
        }
    });
};

const tiposDocumento = [
    { value: 'DNI', label: 'DNI' },
    { value: 'CE', label: 'Carné de Extranjería' },
    { value: 'PAS', label: 'Pasaporte' },
];

const generos = [
    { value: 'M', label: 'Masculino' },
    { value: 'F', label: 'Femenino' },
    { value: 'O', label: 'Otro' },
];
</script>

<template>
    <GuestLayout title="Completar registro" backRoute="/">
        <div class="w-full max-w-3xl mx-auto px-6 py-6">
            <h1 class="text-2xl font-semibold text-cyan-600 mb-8">Completar registro</h1>

            <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <!-- Tipo de documento -->
                <div>
                    <InputLabel for="tipo_documento" value="Tipo de documento" />
                    <SelectInput id="tipo_documento" v-model="form.tipo_documento" :options="tiposDocumento" required />
                    <InputError :message="form.errors.tipo_documento" />
                </div>

                <!-- Número de documento -->
                <div>
                    <InputLabel for="numero_documento" value="Número de documento" />
                    <TextInput id="numero_documento" v-model="form.numero_documento" type="text"
                        class="mt-1 block w-full" required autocomplete="off" />
                    <InputError :message="form.errors.numero_documento" />
                </div>

                <!-- Nombres -->
                <div>
                    <InputLabel for="nombres" value="Nombres completos" />
                    <TextInput id="nombres" v-model="form.name" type="text" autocomplete="off"
                        class="mt-1 block w-full uppercase" required />
                    <InputError :message="form.errors.name" />
                </div>

                <!-- Apellidos -->
                <div>
                    <InputLabel for="apellidos" value="Apellidos completos" />
                    <TextInput id="apellidos" v-model="form.apellidos" type="text" autocomplete="off"
                        class="mt-1 block w-full uppercase" required />
                    <InputError :message="form.errors.apellidos" />
                </div>

                <div>
                    <InputLabel for="telefono" value="Número de telefono" />
                    <TextInput id="telefono" v-model="form.telefono" type="text" autocomplete="off"
                        class="mt-1 block w-full" required />
                    <InputError :message="form.errors.telefono" />
                </div>

                <div>
                    <InputLabel for="genero" value="Genero" />
                    <SelectInput id="genero" v-model="form.genero" :options="generos" required />
                    <InputError :message="form.errors.genero" />
                </div>

                <!-- Fecha de nacimiento -->
                <div>
                    <InputLabel for="fecha_nacimiento" value="Fecha de nacimiento" />
                    <TextInput id="fecha_nacimiento" v-model="form.fecha_nacimiento" type="date"
                        class="mt-1 block w-full" required />
                    <InputError :message="form.errors.fecha_nacimiento" />
                </div>

                <!-- Fecha de emisión DNI -->
                <div>
                    <InputLabel for="fecha_emision" value="Fecha de emisión de DNI" />
                    <TextInput id="fecha_emision" v-model="form.fecha_emision" type="date" class="mt-1 block w-full"
                        required />
                    <InputError :message="form.errors.fecha_emision" />
                </div>

                <!-- Submit Button en full width -->
                <div class="md:col-span-2 flex justify-center mt-6">
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                        class="w-full md:w-1/2 py-4 text-center">
                        Completar registro
                    </PrimaryButton>
                </div>

                <!-- Link a login -->
                <div class="md:col-span-2 flex items-center justify-center mt-6">
                    <p class="text-sm text-gray-600 mr-2">¿Completar despues?</p>
                    <Link :href="route('logout')" method="post" as="button"
                        class="underline text-sm text-gray-600 hover:text-cyan-600 font-semibold">
                    Cerrar sesión
                    </Link>
                </div>

            </form>
        </div>
    </GuestLayout>
</template>
