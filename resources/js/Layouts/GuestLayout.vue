<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="flex min-h-screen items-center justify-center bg-gradient-to-br from-teal-400 via-cyan-400 to-blue-500 p-6"
    >
        <div class="w-full max-w-5xl overflow-hidden bg-white shadow-2xl rounded-[2.5rem]">
            <slot />
        </div>
    </div>
</template>
