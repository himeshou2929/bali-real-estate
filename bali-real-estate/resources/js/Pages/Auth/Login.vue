<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    userType: {
        type: String,
        default: 'buyer'
    },
    title: {
        type: String,
        default: 'ログイン'
    },
});

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
</script>

<template>
    <GuestLayout>
        <Head :title="props.title" />

        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-gray-900">{{ props.title }}</h1>
            <p class="mt-2 text-sm text-gray-600">
                {{ props.userType === 'buyer' ? '🏠 購入希望者の方はこちらからログインしてください' : '🏢 エージェントの方はこちらからログインしてください' }}
            </p>
        </div>

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4 block">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-gray-600"
                        >Remember me</span
                    >
                </label>
            </div>

            <div class="mt-4 flex items-center justify-end">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Forgot your password?
                </Link>

                <PrimaryButton
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    ログイン
                </PrimaryButton>
            </div>
        </form>

        <div class="mt-6 text-center text-sm text-gray-600">
            <p>アカウントをお持ちでない方は</p>
            <div class="mt-2 flex justify-center gap-4">
                <Link 
                    :href="route('register.buyer')" 
                    class="text-blue-600 hover:text-blue-800 underline"
                >
                    🏠 購入希望者登録
                </Link>
                <Link 
                    :href="route('register.agent')" 
                    class="text-green-600 hover:text-green-800 underline"
                >
                    🏢 エージェント登録
                </Link>
            </div>
        </div>
    </GuestLayout>
</template>
