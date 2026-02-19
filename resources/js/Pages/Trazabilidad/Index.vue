<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { 
    MapPin, 
    Activity, 
    Package, 
    ChevronRight, 
    Clock, 
    User, 
    Factory,
    CheckCircle2,
    Calendar,
    ArrowRight,
    Search
} from 'lucide-vue-next';

const props = defineProps({
    lotes: Array
});

const getStatusClass = (status) => {
    switch (status) {
        case 'Corte': return 'text-amber-600 bg-amber-50 border-amber-100';
        case 'Costura': return 'text-indigo-600 bg-indigo-50 border-indigo-100';
        case 'Control de Calidad': return 'text-purple-600 bg-purple-50 border-purple-100';
        default: return 'text-gray-400 bg-gray-50 border-gray-100';
    }
};

const getStationColor = (station) => {
    const stations = {
        'Corte': 'bg-amber-500',
        'Costura': 'bg-indigo-500',
        'Control de Calidad': 'bg-purple-500',
        'Despacho': 'bg-emerald-500'
    };
    return stations[station] || 'bg-gray-400';
};
</script>

<template>
    <Head title="Trazabilidad de Lotes" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h2 class="text-4xl font-black text-gray-900 tracking-tight mb-2">Trazabilidad Integral</h2>
                    <p class="text-gray-400 font-medium italic">Seguimiento histórico y ubicación en tiempo real</p>
                </div>
                <div class="flex items-center gap-4 bg-white/50 backdrop-blur-md p-2 rounded-2xl border border-white shadow-sm">
                    <div class="w-12 h-12 bg-emerald-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-emerald-100">
                        <MapPin class="w-6 h-6" />
                    </div>
                    <div class="pr-4">
                        <div class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Localización</div>
                        <div class="text-sm font-black text-gray-900 uppercase tracking-tighter">Activa</div>
                    </div>
                </div>
            </div>
        </template>

        <div class="pb-12 px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl">
                <!-- Search Bar Mockup -->
                <div class="mb-10 relative">
                    <div class="absolute inset-y-0 left-6 flex items-center pointer-events-none">
                        <Search class="h-5 w-5 text-indigo-400" />
                    </div>
                    <input type="text" placeholder="Escanee el QR o escriba el código del lote..." class="block w-full pl-16 pr-6 py-6 bg-white/60 backdrop-blur-md border-2 border-white rounded-[2rem] text-lg font-black text-indigo-900 placeholder:text-gray-300 focus:ring-4 focus:ring-indigo-50 focus:border-indigo-100 transition-all shadow-xl">
                    <button class="absolute top-1/2 -translate-y-1/2 right-6 px-8 py-3 bg-indigo-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-indigo-100 hover:bg-indigo-900 transition-all">Buscar</button>
                </div>

                <div class="space-y-8">
                    <div v-for="lote in lotes" :key="lote.id" class="bg-white/60 backdrop-blur-md rounded-[3rem] border border-white shadow-2xl p-10 group hover:bg-white/80 transition-all duration-500 overflow-hidden relative">
                        <!-- Top Decoration -->
                        <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-50 rounded-full blur-[80px] opacity-30 -z-10 group-hover:scale-125 transition-transform duration-700"></div>
                        
                        <div class="flex flex-col lg:flex-row justify-between gap-12">
                            <!-- Left: Lote Info -->
                            <div class="lg:w-1/3">
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="w-16 h-16 bg-indigo-900 rounded-[1.5rem] flex items-center justify-center text-white shadow-xl shadow-indigo-100 rotate-3 group-hover:rotate-0 transition-transform">
                                        <Package class="w-8 h-8" />
                                    </div>
                                    <div>
                                        <div class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em] mb-1">Identificador Único</div>
                                        <h3 class="text-3xl font-black text-indigo-900 font-mono tracking-tighter uppercase">{{ lote.codigo }}</h3>
                                    </div>
                                </div>
                                <div class="space-y-4 bg-white/50 p-6 rounded-[2rem] border border-white/50">
                                    <div class="flex items-center justify-between text-xs font-bold text-gray-500 italic">
                                        <span>Orden:</span>
                                        <span class="text-indigo-600 font-black tracking-widest uppercase">{{ lote.pedido.codigo }}</span>
                                    </div>
                                    <div class="flex items-center justify-between text-xs font-bold text-gray-500 italic">
                                        <span>Cantidad:</span>
                                        <span class="text-gray-900 font-black tracking-tighter text-lg">{{ lote.cantidad_actual }} pcs</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span :class="['px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest border', getStatusClass(lote.estacion_actual?.nombre)]">
                                            {{ lote.estacion_actual?.nombre || 'Sin asignación' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Timeline Visualization -->
                            <div class="lg:w-2/3">
                                <h4 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-8 flex items-center gap-2">
                                    <Activity class="w-4 h-4" /> Línea de Tiempo del Proceso
                                </h4>
                                <div class="relative flex items-center justify-between px-6">
                                    <!-- Progress Line -->
                                    <div class="absolute left-10 top-6 right-10 h-1 bg-gray-100 rounded-full"></div>
                                    
                                    <div v-for="step in ['Corte', 'Costura', 'Calidad', 'Despacho']" :key="step" class="relative z-10 flex flex-col items-center">
                                        <div :class="['w-12 h-12 rounded-2xl border-4 border-white flex items-center justify-center text-white shadow-lg transition-all delay-200', 
                                                    lote.estacion_actual?.nombre === step ? 'bg-indigo-600 scale-125 ring-8 ring-indigo-50' : 
                                                    (step === 'Corte' || (step === 'Costura' && lote.estacion_actual?.nombre !== 'Corte')) ? 'bg-emerald-500' : 'bg-gray-100 text-gray-300']">
                                            <CheckCircle2 v-if="step === 'Corte' || (step === 'Costura' && lote.estacion_actual?.nombre !== 'Corte' && lote.estacion_actual?.nombre !== 'Costura')" class="w-6 h-6" />
                                            <Clock v-else-if="lote.estacion_actual?.nombre === step" class="w-6 h-6 animate-pulse" />
                                            <div v-else class="w-2 h-2 bg-gray-300 rounded-full"></div>
                                        </div>
                                        <span :class="['mt-4 text-[10px] font-black uppercase tracking-widest italic', lote.estacion_actual?.nombre === step ? 'text-indigo-600' : 'text-gray-300']">
                                            {{ step }}
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-12 bg-indigo-50/50 p-6 rounded-[2rem] flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm">
                                            <User class="w-5 h-5 text-indigo-400" />
                                        </div>
                                        <div>
                                            <div class="text-[8px] font-black text-indigo-300 uppercase tracking-widest">Último Responsable</div>
                                            <div class="text-xs font-black text-indigo-900 uppercase italic">Admin Sistema</div>
                                        </div>
                                    </div>
                                    <Link :href="route('trazabilidad.show', lote.id)" class="px-6 py-2 bg-white text-indigo-600 rounded-xl text-[10px] font-black uppercase tracking-widest border border-indigo-100 hover:bg-indigo-600 hover:text-white transition-all shadow-sm flex items-center gap-2">
                                        EXPEDIENTE COMPLETO
                                        <ArrowRight class="w-3.5 h-3.5" />
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
