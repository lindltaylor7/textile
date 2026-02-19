<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import { 
    LayoutDashboard, 
    ClipboardList, 
    Scissors, 
    Shirt, 
    ShieldCheck, 
    History, 
    User, 
    LogOut, 
    Menu, 
    X,
    ChevronDown
} from 'lucide-vue-next';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div>
        <!-- Modern Gradient Background -->
        <div class="min-h-screen bg-[radial-gradient(ellipse_at_top_left,_var(--tw-gradient-stops))] from-indigo-50 via-gray-50 to-white">
            <nav class="bg-white/80 backdrop-blur-md border-b border-indigo-50 sticky top-0 z-50">
                <!-- Primary Navigation Menu -->
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-20 justify-between">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="flex shrink-0 items-center">
                                <Link :href="route('dashboard')" class="flex items-center gap-2 group">
                                    <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-100 group-hover:scale-110 transition-transform">
                                        <Shirt class="w-6 h-6 text-white" />
                                    </div>
                                    <span class="text-xl font-black text-gray-900 tracking-tighter">Textile MES</span>
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-4 sm:-my-px sm:ms-10 sm:flex items-center text-sm">
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')" class="flex items-center gap-2 px-3 py-2 rounded-xl transition-all font-bold">
                                    <LayoutDashboard class="w-4 h-4" />
                                    Panel
                                </NavLink>
                                <NavLink :href="route('pedidos.index')" :active="route().current('pedidos.*')" class="flex items-center gap-2 px-3 py-2 rounded-xl transition-all font-bold">
                                    <ClipboardList class="w-4 h-4" />
                                    Pedidos
                                </NavLink>
                                <NavLink :href="route('corte.index')" :active="route().current('corte.*')" class="flex items-center gap-2 px-3 py-2 rounded-xl transition-all font-bold">
                                    <Scissors class="w-4 h-4" />
                                    Corte
                                </NavLink>
                                <NavLink :href="route('costura.index')" :active="route().current('costura.*')" class="flex items-center gap-2 px-3 py-2 rounded-xl transition-all font-bold">
                                    <Shirt class="w-4 h-4" />
                                    Costura
                                </NavLink>
                                <NavLink :href="route('calidad.index')" :active="route().current('calidad.*')" class="flex items-center gap-2 px-3 py-2 rounded-xl transition-all font-bold">
                                    <ShieldCheck class="w-4 h-4" />
                                    Calidad
                                </NavLink>
                                <NavLink :href="route('trazabilidad.index')" :active="route().current('trazabilidad.*')" class="flex items-center gap-2 px-3 py-2 rounded-xl transition-all font-bold">
                                    <History class="w-4 h-4" />
                                    Trazabilidad
                                </NavLink>
                            </div>
                        </div>

                        <div class="hidden sm:ms-6 sm:flex sm:items-center">
                            <!-- Settings Dropdown -->
                            <div class="relative ms-3">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center rounded-xl border border-gray-100 bg-white px-4 py-2 text-sm font-bold text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-none"
                                            >
                                                {{ $page.props.auth.user.name }}
                                                <ChevronDown class="ms-2 h-4 w-4 text-gray-400" />
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <div class="px-4 py-2 text-xs font-black text-indigo-400 uppercase tracking-widest border-b border-gray-50">Gestionar Cuenta</div>
                                        <DropdownLink :href="route('profile.edit')" class="flex items-center gap-2 py-3 font-bold">
                                            <User class="w-4 h-4" />
                                            Mi Perfil
                                        </DropdownLink>
                                        <DropdownLink :href="route('logout')" method="post" as="button" class="flex items-center gap-2 py-3 text-rose-600 font-bold">
                                            <LogOut class="w-4 h-4" />
                                            Cerrar Sesión
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center rounded-xl p-2 text-gray-400 transition hover:bg-indigo-50 hover:text-indigo-600 focus:outline-none"
                            >
                                <Menu v-if="!showingNavigationDropdown" class="h-6 w-6" />
                                <X v-else class="h-6 w-6" />
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <transition
                    enter-active-class="transition duration-200 ease-out"
                    enter-from-class="translate-y-1 opacity-0"
                    enter-to-class="translate-y-0 opacity-100"
                    leave-active-class="transition duration-150 ease-in"
                    leave-from-class="translate-y-0 opacity-100"
                    leave-to-class="translate-y-1 opacity-0"
                >
                    <div v-show="showingNavigationDropdown" class="sm:hidden bg-white border-t border-indigo-50 px-4 py-4 space-y-2 shadow-xl">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')" class="rounded-xl font-bold flex items-center gap-3">
                            <LayoutDashboard class="w-5 h-5" /> Panel Principal
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('pedidos.index')" :active="route().current('pedidos.*')" class="rounded-xl font-bold flex items-center gap-3">
                            <ClipboardList class="w-5 h-5" /> Pedidos
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('corte.index')" :active="route().current('corte.*')" class="rounded-xl font-bold flex items-center gap-3">
                            <Scissors class="w-5 h-5" /> Corte
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('costura.index')" :active="route().current('costura.*')" class="rounded-xl font-bold flex items-center gap-3">
                            <Shirt class="w-5 h-5" /> Costura
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('calidad.index')" :active="route().current('calidad.*')" class="rounded-xl font-bold flex items-center gap-3">
                            <ShieldCheck class="w-5 h-5" /> Calidad
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('trazabilidad.index')" :active="route().current('trazabilidad.*')" class="rounded-xl font-bold flex items-center gap-3">
                            <History class="w-5 h-5" /> Trazabilidad
                        </ResponsiveNavLink>

                        <div class="pt-4 border-t border-gray-100 mt-4">
                            <div class="flex items-center gap-3 px-4">
                                <div class="w-10 h-10 bg-indigo-50 rounded-full flex items-center justify-center text-indigo-600 font-bold">
                                    {{ $page.props.auth.user.name.charAt(0) }}
                                </div>
                                <div>
                                    <div class="text-sm font-black text-gray-900">{{ $page.props.auth.user.name }}</div>
                                    <div class="text-xs text-gray-400">{{ $page.props.auth.user.email }}</div>
                                </div>
                            </div>
                            <div class="mt-4 space-y-1">
                                <ResponsiveNavLink :href="route('profile.edit')" class="rounded-xl font-bold">Mi Perfil</ResponsiveNavLink>
                                <ResponsiveNavLink :href="route('logout')" method="post" as="button" class="rounded-xl font-bold text-rose-600">Cerrar Sesión</ResponsiveNavLink>
                            </div>
                        </div>
                    </div>
                </transition>
            </nav>

            <!-- Page Heading -->
            <header class="py-10" v-if="$slots.header">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
