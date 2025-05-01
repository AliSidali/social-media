<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import {EyeSlashIcon, EyeIcon, EnvelopeIcon} from '@heroicons/vue/24/outline';
import {ref} from 'vue'

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const passwordType = ref('password');
const page = usePage();
const translations = page.props.translations;
const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

const changePasswordType = ()=>{
    passwordType.value = passwordType.value == 'password' ? 'text' : 'password';
}
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>
        <form @submit.prevent="submit">
            <div>
                <InputLabel class="capitalize" for="email" :value="translations.email_label" />
                <div class="flex items-center">
                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full rounded-r-none"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                    />
                        <div @click="changePasswordType" class="bg-gray-200 p-3 rounded-r-md mt-1 hover:cursor-pointer">
                            <EnvelopeIcon class="w-4"/>
                        </div>
                    </div>
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

            <div class="mt-4">
                <InputLabel class="capitalize" for="password" :value="translations.password_label" />
                <div class="flex items-center">
                    <TextInput
                        id="password"
                        :type="passwordType"
                        class="mt-1 block w-full rounded-r-none"
                        v-model="form.password"
                        required
                        autocomplete="current-password"
                    />
                    <div @click="changePasswordType" class="bg-gray-200 p-3 rounded-r-md mt-1 hover:cursor-pointer">
                        <EyeIcon class="w-4" v-if="passwordType == 'password'" />
                        <EyeSlashIcon class="w-4"  v-else/>
                    </div>
                </div>
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="block mt-4">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400 capitalize">{{ translations.remember_me }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                >
                    {{ translations.forget_password }}
                </Link>

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    {{ translations.login_button }}
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
