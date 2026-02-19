<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { 
    Package, 
    Zap, 
    AlertCircle, 
    ArrowUpRight, 
    Activity,
    ChevronRight
} from 'lucide-vue-next';

defineProps({
    metrics: Object
});
</script>

<template>
    <Head title="Panel de Control - MES Textil" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-4xl font-black text-gray-900 tracking-tight mb-2">
                        Resumen de Producción
                    </h2>
                    <p class="text-gray-400 font-medium italic">Monitorización en tiempo real del flujo de planta</p>
                </div>
                <div class="flex items-center gap-2 bg-indigo-50 px-4 py-2 rounded-2xl border border-indigo-100">
                    <Activity class="w-4 h-4 text-indigo-600 animate-pulse" />
                    <span class="text-xs font-black text-indigo-600 uppercase tracking-widest">Sistema Operativo</span>
                </div>
            </div>
        </template>

        <div class="pb-12 px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl">
                <!-- Metrics Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                    <!-- Metric Card: Pedidos -->
                    <div class="relative group">
                        <div class="absolute inset-0 bg-indigo-600 rounded-[2.5rem] blur-2xl opacity-10 group-hover:opacity-20 transition-opacity"></div>
                        <div class="relative bg-white/60 backdrop-blur-xl p-8 rounded-[2.5rem] border border-white shadow-xl hover:shadow-2xl transition-all duration-500 overflow-hidden">
                            <div class="flex justify-between items-start mb-6">
                                <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-100 italic">
                                    <Package class="w-7 h-7 text-white" />
                                </div>
                                <ArrowUpRight class="w-5 h-5 text-gray-300" />
                            </div>
                            <div class="text-sm font-black text-gray-400 uppercase tracking-widest mb-1">Pedidos Activos</div>
                            <div class="text-5xl font-black text-indigo-900 tracking-tighter">{{ metrics.activeOrders }}</div>
                            <div class="mt-6 flex items-center gap-2 text-xs font-bold text-indigo-500 bg-indigo-50 w-max px-3 py-1 rounded-full uppercase tracking-tighter">
                                <Activity class="w-3 h-3" />
                                Seguimiento activo
                            </div>
                            <!-- Decorative Circle -->
                            <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-indigo-50 rounded-full opacity-50"></div>
                        </div>
                    </div>

                    <!-- Metric Card: Lotes -->
                    <div class="relative group">
                        <div class="absolute inset-0 bg-emerald-600 rounded-[2.5rem] blur-2xl opacity-10 group-hover:opacity-20 transition-opacity"></div>
                        <div class="relative bg-white/60 backdrop-blur-xl p-8 rounded-[2.5rem] border border-white shadow-xl hover:shadow-2xl transition-all duration-500 overflow-hidden">
                            <div class="flex justify-between items-start mb-6">
                                <div class="w-14 h-14 bg-emerald-500 rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-100">
                                    <Zap class="w-7 h-7 text-white" />
                                </div>
                                <ArrowUpRight class="w-5 h-5 text-gray-300" />
                            </div>
                            <div class="text-sm font-black text-gray-400 uppercase tracking-widest mb-1">Lotes en Planta</div>
                            <div class="text-5xl font-black text-emerald-900 tracking-tighter">{{ metrics.lotsInPlant }}</div>
                            <div class="mt-6 flex items-center gap-2 text-xs font-bold text-emerald-500 bg-emerald-50 w-max px-3 py-1 rounded-full uppercase tracking-tighter">
                                <Activity class="w-3 h-3" />
                                Carga Optimizada
                            </div>
                            <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-emerald-50 rounded-full opacity-50"></div>
                        </div>
                    </div>

                    <!-- Metric Card: Calidad -->
                    <div class="relative group">
                        <div class="absolute inset-0 bg-rose-600 rounded-[2.5rem] blur-2xl opacity-10 group-hover:opacity-20 transition-opacity"></div>
                        <div class="relative bg-white/60 backdrop-blur-xl p-8 rounded-[2.5rem] border border-white shadow-xl hover:shadow-2xl transition-all duration-500 overflow-hidden">
                            <div class="flex justify-between items-start mb-6">
                                <div class="w-14 h-14 bg-rose-500 rounded-2xl flex items-center justify-center shadow-lg shadow-rose-100">
                                    <AlertCircle class="w-7 h-7 text-white" />
                                </div>
                                <ArrowUpRight class="w-5 h-5 text-gray-300" />
                            </div>
                            <div class="text-sm font-black text-gray-400 uppercase tracking-widest mb-1">% Defectos</div>
                            <div class="text-5xl font-black text-rose-900 tracking-tighter">{{ metrics.defectPercentage }}%</div>
                            <div class="mt-6 flex items-center gap-2 text-xs font-bold text-rose-500 bg-rose-50 w-max px-3 py-1 rounded-full uppercase tracking-tighter">
                                <Activity class="w-3 h-3" />
                                Objetivo: &lt; 2%
                            </div>
                            <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-rose-50 rounded-full opacity-50"></div>
                        </div>
                    </div>
                </div>

                <!-- Action Section -->
                <div class="relative overflow-hidden bg-indigo-900 rounded-[3rem] p-10 md:p-16 shadow-2xl">
                    <!-- Background blobs -->
                    <div class="absolute -top-20 -left-20 w-80 h-80 bg-indigo-500 rounded-full blur-[100px] opacity-20"></div>
                    <div class="absolute -bottom-20 -right-20 w-80 h-80 bg-purple-500 rounded-full blur-[100px] opacity-20"></div>

                    <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-12 text-center md:text-left">
                        <div class="max-w-xl">
                            <h3 class="text-3xl font-black text-white mb-6 uppercase tracking-tight">Flujo de Producción</h3>
                            <p class="text-indigo-200 text-lg leading-relaxed font-medium mb-8 italic">
                                "El sistema mantiene una trazabilidad rígida para garantizar la calidad del producto final."
                            </p>
                            <div class="flex flex-wrap justify-center md:justify-start gap-4">
                                <div v-for="step in ['Corte', 'Costura', 'Calidad']" :key="step" class="flex items-center gap-2">
                                    <div class="px-4 py-2 bg-white/10 backdrop-blur-md rounded-xl text-white font-black text-xs uppercase tracking-widest border border-white/10">
                                        {{ step }}
                                    </div>
                                    <ChevronRight v-if="step !== 'Calidad'" class="w-4 h-4 text-indigo-400" />
                                </div>
                            </div>
                        </div>
                        <div class="bg-white/5 backdrop-blur-xl border border-white/10 p-8 rounded-[2.5rem] flex flex-col items-center gap-4 group hover:bg-white/10 transition-all cursor-default">
                            <div class="w-16 h-16 bg-white rounded-3xl flex items-center justify-center shadow-xl group-hover:rotate-12 transition-transform duration-500">
                                <Zap class="w-8 h-8 text-indigo-600" />
                            </div>
                            <div class="text-center">
                                <div class="text-white font-black text-xl uppercase tracking-tighter">Optimización</div>
                                <div class="text-indigo-300 text-xs font-bold uppercase tracking-widest mt-1">Sugerencias activas</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* No extra styles needed as we use Tailwind with rich presets */
</style>
