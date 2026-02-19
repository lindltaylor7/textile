<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Shirt } from 'lucide-vue-next';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
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
        <Head title="Log in" />

        <div class="flex flex-col md:flex-row min-h-[600px]">
            <!-- Left Side: Form -->
            <div class="w-full md:w-[45%] p-10 md:p-14 flex flex-col justify-between">
                <div>
                    <!-- Logo & Brand -->
                    <div class="flex items-center gap-3 mb-10">
                        <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200">
                            <Shirt class="w-6 h-6 fill-white" />
                        </div>
                        <span class="text-xl font-black text-gray-900 tracking-tight">Textile MES</span>
                    </div>

                    <!-- Header -->
                    <div class="mb-10">
                        <h1 class="text-4xl font-black text-gray-900 mb-4 tracking-tight">Inicia sesión.</h1>
                        <p class="text-gray-400 font-medium text-sm leading-relaxed">
                            Inicia sesión con los datos que ingresaste <br>
                            durante tu registro
                        </p>
                    </div>

                    <div v-if="status" class="mb-6 text-sm font-medium text-green-600">
                        {{ status }}
                    </div>

                    <form @submit.prevent="submit" class="space-y-6">
                        <div>
                            <InputLabel for="email" value="Ingresa tu dirección de correo electrónico" class="text-xs font-black text-gray-400 uppercase tracking-widest mb-2" />
                            <TextInput
                                id="email"
                                type="email"
                                class="mt-1 block w-full bg-gray-50 border-gray-100 rounded-xl focus:ring-indigo-500 py-3 px-4 font-bold text-gray-800"
                                v-model="form.email"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="name@example.com"
                            />
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>

                        <div>
                            <InputLabel for="password" value="Ingresa tu contraseña" class="text-xs font-black text-gray-400 uppercase tracking-widest mb-2" />
                            <TextInput
                                id="password"
                                type="password"
                                class="mt-1 block w-full bg-gray-50 border-gray-100 rounded-xl focus:ring-indigo-500 py-3 px-4 font-bold text-gray-800"
                                v-model="form.password"
                                required
                                autocomplete="current-password"
                                placeholder="minimo 8 caracteres"
                            />
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>

                        <div class="flex items-center justify-between mt-2">
                            <!-- <label class="flex items-center cursor-pointer">
                                <Checkbox name="remember" v-model:checked="form.remember" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" />
                                <span class="ms-2 text-xs font-black text-gray-400 uppercase tracking-widest">Remember me</span>
                            </label> -->

                           <!--  <Link
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="text-xs font-black text-indigo-600 uppercase tracking-widest hover:text-indigo-800 transition-colors"
                            >
                                Forget password?
                            </Link> -->
                        </div>

                        <button
                            type="submit"
                            class="w-full bg-indigo-600 text-white font-black py-4 rounded-xl text-sm uppercase tracking-widest shadow-xl shadow-indigo-200 hover:bg-indigo-700 active:scale-[0.98] transition-all disabled:opacity-50"
                            :disabled="form.processing"
                        >
                            Iniciar sesión
                        </button>
                    </form>

                    
                </div>

                <!-- Footer -->
               <!--  <div class="mt-10 text-center text-xs font-bold text-gray-400 uppercase tracking-widest">
                    Don't have an account? 
                    <Link :href="route('register')" class="text-indigo-600 hover:text-indigo-800 ml-1">Register</Link>
                </div> -->
            </div>

            <!-- Right Side: Illustration -->
            <div class="hidden md:flex w-[55%] bg-indigo-50/50 items-center justify-center p-12 relative overflow-hidden group">
                <!-- Decorative background elements -->
                <div class="absolute top-10 right-10 w-32 h-32 bg-indigo-100 rounded-full blur-3xl opacity-50 group-hover:scale-110 transition-transform duration-1000"></div>
                <div class="absolute bottom-20 left-20 w-48 h-48 bg-purple-100 rounded-full blur-3xl opacity-50 group-hover:scale-110 transition-transform duration-1000"></div>
                
                <div class="relative z-10 text-center max-w-sm">
                    <p class="text-gray-500 font-medium text-base mb-2 italic">Es un gusto verte de nuevo</p>
                    <h2 class="text-5xl font-black text-indigo-700 mb-8 tracking-tighter">Bienvenido</h2>
                    
                    
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
