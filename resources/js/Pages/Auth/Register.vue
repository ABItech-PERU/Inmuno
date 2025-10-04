<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';

const form = useForm({
    email: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>

    <GuestLayout title="Registrate" backRoute="/">
        <div class="w-full max-w-md mx-auto px-6 py-6">
            <h1 class="text-2xl font-semibold text-cyan-600 mb-10">Registrate</h1>

            <form @submit.prevent="submit">

                <div class="mt-4">
                    <InputLabel for="email" value="Correo electrónico" />
                    <TextInput id="email" v-model="form.email" type="email" class="mt-1 block w-full" required
                        autocomplete="username" />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="mt-4">
                    <InputLabel for="password" value="Contraseña" />
                    <TextInput id="password" v-model="form.password" type="password" class="mt-1 block w-full" required
                        autocomplete="new-password" />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="mt-4">
                    <InputLabel for="password_confirmation" value="Repitir contraseña" />
                    <TextInput id="password_confirmation" v-model="form.password_confirmation" type="password"
                        class="mt-1 block w-full" required autocomplete="new-password" />
                    <InputError :message="form.errors.password_confirmation" />
                </div>

                <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="mt-4">
                    <InputLabel for="terms">
                        <div class="flex items-center">
                            <Checkbox id="terms" v-model:checked="form.terms" name="terms" required />

                            <div class="ms-2">
                                I agree to the <a target="_blank" :href="route('terms.show')"
                                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-indigo-500">Terms
                                    of Service</a> and <a target="_blank" :href="route('policy.show')"
                                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-indigo-500">Privacy
                                    Policy</a>
                            </div>
                        </div>
                        <InputError class="mt-2" :message="form.errors.terms" />
                    </InputLabel>
                </div>

                <div class="flex items-center justify-between mt-6">
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                        class="w-full py-4 text-center">
                        Crear cuenta
                    </PrimaryButton>
                </div>

                <div class="flex items-center justify-center mt-6">
                    <p class="text-sm text-gray-600 mr-2">Ya tienes una cuenta?</p>
                    <Link :href="route('login')"
                        class="underline text-sm text-gray-600 hover:text-cyan-600 font-semibold rounded-md focus:outline-none">
                    Inicia Sesión
                    </Link>
                </div>
            </form>
        </div>

    </GuestLayout>
</template>
