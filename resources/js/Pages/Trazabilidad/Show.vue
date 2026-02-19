<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    lote: Object
});
</script>

<template>
    <Head title="Detalle Lote" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-900 tracking-tighter">Bitácora de Lote: {{ lote.codigo }}</h2>
                <Link :href="route('trazabilidad.index')" class="text-xs font-black text-gray-400 uppercase tracking-widest hover:text-indigo-600 transition-colors">&larr; Volver al Listado</Link>
            </div>
        </template>

        <div class="py-12 bg-gray-50 min-h-screen">
            <div class="mx-auto max-w-5xl sm:px-6 lg:px-8">
                <!-- Lot Summary Card -->
                <div class="bg-white p-10 rounded-[2.5rem] shadow-2xl border border-gray-100 mb-10 flex flex-wrap gap-10">
                    <div class="flex-1">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Pedido de Origen</label>
                        <div class="text-2xl font-black text-indigo-900 tracking-tighter">{{ lote.pedido.codigo }}</div>
                    </div>
                    <div class="flex-1">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Cantidad Producida</label>
                        <div class="text-2xl font-black text-gray-900 tracking-tighter">{{ lote.cantidad_actual }} / {{ lote.cantidad_inicial }}</div>
                    </div>
                     <div class="flex-1">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Ubicación Actual</label>
                        <div class="text-2xl font-black text-emerald-600 tracking-tighter italic underline decoration-emerald-200 underline-offset-8">{{ lote.estacion_actual?.nombre || 'FINALIZADO' }}</div>
                    </div>
                </div>

                <!-- Timeline of Movements -->
                <div class="relative pl-10 border-l-4 border-indigo-100 space-y-12 ml-6">
                    <div v-for="mov in lote.movimientos" :key="mov.id" class="relative">
                        <div class="absolute -left-[3.25rem] w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-xl border-4 border-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" /></svg>
                        </div>
                        <div class="bg-white p-8 rounded-3xl shadow-xl border border-gray-100 transform transition-all hover:scale-[1.02]">
                            <div class="flex justify-between items-start mb-4">
                                <h4 class="text-lg font-black text-gray-900 uppercase">
                                    Traslado a {{ mov.estacion_destino.nombre }}
                                </h4>
                                <span class="text-[10px] font-black text-indigo-400 italic bg-indigo-50 px-3 py-1 rounded-lg">
                                    {{ new Date(mov.created_at).toLocaleString() }}
                                </span>
                            </div>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <p class="text-gray-500 font-medium">Responsable: <span class="text-gray-900 font-black">{{ mov.usuario.name }}</span></p>
                                <p class="text-gray-500 font-medium text-right">Unidades Ingresadas: <span class="text-gray-900 font-black">{{ mov.cantidad_entrada }}</span></p>
                            </div>
                        </div>
                    </div>

                    <!-- Quality Check Log -->
                    <div v-for="ctrl in lote.controles_calidad" :key="ctrl.id" class="relative">
                        <div class="absolute -left-[3.25rem] w-12 h-12 bg-emerald-500 rounded-2xl flex items-center justify-center text-white shadow-xl border-4 border-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div class="bg-emerald-50/30 p-8 rounded-3xl shadow-xl border border-emerald-100">
                             <div class="flex justify-between items-start mb-4">
                                <h4 class="text-lg font-black text-emerald-800 uppercase">InspecciÃ³n de Calidad</h4>
                                <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase text-white shadow-sm" :class="ctrl.resultado === 'LIBERADO' ? 'bg-emerald-500' : 'bg-rose-500'">
                                    {{ ctrl.resultado }}
                                </span>
                            </div>
                            <p v-if="ctrl.cantidad_defectuosa > 0" class="text-sm font-bold text-rose-600 mb-2">
                                Defectos Encontrados: {{ ctrl.cantidad_defectuosa }} - {{ ctrl.defecto?.nombre }}
                            </p>
                            <p class="text-xs text-emerald-700 italic font-medium leading-relaxed">"{{ ctrl.observaciones || 'Sin observaciones adicionales' }}"</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
