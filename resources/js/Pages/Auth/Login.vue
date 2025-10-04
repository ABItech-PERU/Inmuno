<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form
        .transform(data => ({
            ...data,
            remember: form.remember ? 'on' : '',
        }))
        .post(route('login'), {
            onFinish: () => form.reset('password'),
        });
};
</script>

<template>
    <GuestLayout title="Iniciar sesión" backRoute="/">
        <div class="w-full max-w-md mx-auto px-6 py-6">
            <h1 class="text-2xl font-semibold text-cyan-600 mb-10">Iniciar sesión</h1>

            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <InputLabel for="email" value="Correo electrónico" />
                    <TextInput id="email" v-model="form.email" type="email" class="mt-1 block w-full" required autofocus
                        autocomplete="username" />
                    <InputError :message="form.errors.email" />
                </div>

                <div>
                    <InputLabel for="password" value="Contraseña" />
                    <TextInput id="password" v-model="form.password" type="password" class="mt-1 block w-full" required
                        autocomplete="current-password" />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="text-right">
                    <Link v-if="canResetPassword" :href="route('password.request')"
                        class="underline text-sm font-medium text-gray-600 hover:text-cyan-600 rounded-md focus:outline-none">
                    ¿Olvidaste tu contraseña?
                    </Link>
                </div>

                <label class="flex items-center select-none">
                    <Checkbox v-model:checked="form.remember" name="remember" />
                    <span class="ms-2 text-sm text-gray-600">Recuérdame</span>
                </label>

                <div class="flex items-center justify-between">
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                        class="w-full py-4 text-center">
                        Iniciar sesión
                    </PrimaryButton>
                </div>

                <!-- Continuar con Google -->
                <div>
                    <div class="flex items-center justify-center mt-10">
                        <a :href="route('google')"
                            class="flex items-center justify-center gap-3 w-full max-w-xs px-5 py-2 bg-white border border-gray-300 rounded-full shadow-md hover:shadow-lg transition duration-200 ease-in-out">
                            <!-- Ícono de Google -->
                            <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google"
                                class="w-5 h-5" />
                            <span class="text-gray-700 font-medium">Continuar con Google</span>
                        </a>
                    </div>
                </div>

            </form>
        </div>
    </GuestLayout>
</template>
