<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { 
    Shirt, 
    Cpu, 
    AlertTriangle, 
    ArrowUpRight, 
    Activity, 
    X,
    Settings,
    History,
    CheckCircle2,
    Calendar,
    Clock
} from 'lucide-vue-next';

const props = defineProps({
    lotes: Array,
    maquinas: Array,
    estacion: Object
});

const showProdModal = ref(false);
const showStopModal = ref(false);
const selectedLote = ref(null);
const selectedMaquina = ref(null);

const prodForm = useForm({
    lote_id: '',
    estacion_destino_id: 3, // Calidad es la siguiente
    cantidad_entrada: 0,
    usuario_id: 1,
    maquina_id: ''
});

const stopForm = useForm({
    maquina_id: '',
    tipo: 'Mecánica',
    duracion: 15,
    fecha: new Date().toISOString().split('T')[0]
});

const openProdModal = (lote) => {
    selectedLote.value = lote;
    prodForm.lote_id = lote.id;
    prodForm.cantidad_entrada = lote.cantidad_actual;
    showProdModal.value = true;
};

const openStopModal = (maquina) => {
    selectedMaquina.value = maquina;
    stopForm.maquina_id = maquina.id;
    showStopModal.value = true;
};

const submitProd = () => {
    prodForm.post(route('costura.move'), {
        onSuccess: () => showProdModal.value = false,
    });
};

const submitStop = () => {
    stopForm.post(route('costura.stop'), {
        onSuccess: () => showStopModal.value = false,
    });
};
</script>

<template>
    <Head title="Módulo de Costura" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h2 class="text-4xl font-black text-gray-900 tracking-tight mb-2">Costura / Confección</h2>
                    <p class="text-gray-400 font-medium italic">Ensamblaje final y reporte de maquinaria</p>
                </div>
                <div class="flex items-center gap-4 bg-white/50 backdrop-blur-md p-2 rounded-2xl border border-white shadow-sm">
                    <div class="w-12 h-12 bg-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-100">
                        <Shirt class="w-6 h-6" />
                    </div>
                    <div class="pr-4">
                        <div class="text-[10px] font-black text-indigo-600 uppercase tracking-widest">Estación Activa</div>
                        <div class="text-sm font-black text-gray-900 uppercase tracking-tighter">Producción</div>
                    </div>
                </div>
            </div>
        </template>

        <div class="pb-12 px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl">
                <!-- Maquinas / Paradas Section -->
                <div class="mb-12">
                    <div class="flex items-center gap-3 mb-6">
                        <Settings class="w-5 h-5 text-gray-400" />
                        <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em]">Estado de Maquinaria Industrial</h3>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div v-for="maq in maquinas" :key="maq.id" class="bg-white/60 backdrop-blur-md p-6 rounded-[2rem] border border-white shadow-xl hover:shadow-2xl transition-all duration-500 group relative overflow-hidden">
                            <div class="relative z-10 flex justify-between items-start mb-4">
                                <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 font-black text-xs border border-indigo-100 italic">
                                    {{ maq.codigo }}
                                </div>
                                <span class="flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-black uppercase tracking-tighter border border-emerald-100">
                                    <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></div>
                                    OPERATIVA
                                </span>
                            </div>
                            <h4 class="relative z-10 font-black text-gray-900 text-lg tracking-tight mb-4 uppercase">{{ maq.nombre }}</h4>
                            <button 
                                @click="openStopModal(maq)"
                                class="relative z-10 w-full py-3 bg-rose-50 text-rose-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-rose-600 hover:text-white transition-all shadow-sm flex items-center justify-center gap-2"
                            >
                                <AlertTriangle class="w-3.5 h-3.5" />
                                Reportar Parada
                            </button>
                            <!-- Background accent -->
                            <div class="absolute -bottom-10 -right-10 w-24 h-24 bg-gray-50 rounded-full group-hover:bg-indigo-50 transition-colors"></div>
                        </div>
                    </div>
                </div>

                <!-- Produccion Section -->
                <div class="bg-white overflow-hidden shadow-2xl rounded-[2.5rem] border border-gray-100">
                    <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-white">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-indigo-900 rounded-xl flex items-center justify-center text-white">
                                <Activity class="w-5 h-5" />
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-gray-900 tracking-tight">Parte de Producción en Línea</h3>
                                <p class="text-xs text-gray-400 font-medium italic mt-0.5">Control de avance por lote</p>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-50">
                            <thead class="bg-indigo-50/30">
                                <tr>
                                    <th class="px-8 py-6 text-left text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em]">Lote</th>
                                    <th class="px-8 py-6 text-left text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em]">Orden Pedido</th>
                                    <th class="px-8 py-6 text-left text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em]">Stock Act.</th>
                                    <th class="px-8 py-6 text-center"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                <tr v-for="lote in lotes" :key="lote.id" class="group hover:bg-indigo-50/20 transition-all duration-300">
                                    <td class="px-8 py-6">
                                        <div class="text-sm font-black text-indigo-900 font-mono tracking-tighter uppercase">{{ lote.codigo }}</div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="text-xs font-bold text-gray-500 bg-gray-50 w-max px-3 py-1 rounded-lg border border-gray-100 italic">
                                            {{ lote.pedido.codigo }}
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="text-2xl font-black text-gray-900 flex items-baseline gap-1">
                                            {{ lote.cantidad_actual }}
                                            <span class="text-[10px] text-gray-400 uppercase tracking-widest font-medium">pcs</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        <button 
                                            @click="openProdModal(lote)"
                                            class="bg-indigo-600 text-white px-8 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-indigo-100 hover:bg-indigo-900 hover:scale-95 transition-all flex items-center justify-center gap-2 mx-auto"
                                        >
                                            REGISTRAR AVANCE
                                            <ArrowUpRight class="w-4 h-4" />
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="lotes.length === 0">
                                    <td colspan="4" class="px-8 py-24 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="w-20 h-20 bg-gray-50 rounded-[2rem] flex items-center justify-center text-gray-200 font-bold text-xl italic">
                                                <Shirt class="w-10 h-10" />
                                            </div>
                                            <div class="text-sm text-gray-400 font-medium italic">Sin lotes en línea de costura.</div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Produccion Redesigned -->
        <div v-if="showProdModal" class="fixed inset-0 z-[60] overflow-y-auto flex items-center justify-center p-4">
            <div @click="showProdModal = false" class="fixed inset-0 bg-indigo-900/60 backdrop-blur-md transition-opacity"></div>
            
            <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-2xl transform transition-all max-w-lg w-full p-10 relative z-10 border border-indigo-50">
                <div class="absolute top-0 right-0 p-8">
                    <button @click="showProdModal = false" class="text-gray-300 hover:text-rose-500 transition-colors">
                        <X class="w-7 h-7" />
                    </button>
                </div>

                <div class="text-center mb-10">
                    <div class="w-20 h-20 bg-indigo-50 rounded-3xl flex items-center justify-center mx-auto mb-6 border border-indigo-100">
                        <CheckCircle2 class="w-10 h-10 text-indigo-600" />
                    </div>
                    <h3 class="text-3xl font-black text-gray-900 tracking-tight">Cierre de Costura</h3>
                    <p class="text-gray-400 font-medium italic mt-2">Trasladar lote a <span class="font-black text-indigo-600 uppercase tracking-widest">Calidad</span></p>
                </div>

                <form @submit.prevent="submitProd" class="space-y-6">
                    <div class="space-y-4">
                        <div class="bg-gray-50 p-6 rounded-[2rem] border border-gray-100 focus-within:border-indigo-200 transition-all">
                            <label class="flex items-center gap-2 text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-3">
                                <Cpu class="w-3.5 h-3.5" /> Máquina de Confección
                            </label>
                            <select v-model="prodForm.maquina_id" class="w-full bg-transparent border-none p-0 text-lg font-black text-indigo-900 focus:ring-0 appearance-none hover:cursor-pointer" required>
                                <option value="" disabled>Seleccione una máquina...</option>
                                <option v-for="maq in maquinas" :key="maq.id" :value="maq.id">{{ maq.codigo }} &mdash; {{ maq.nombre }}</option>
                            </select>
                        </div>
                        <div class="bg-gray-50 p-8 rounded-[2rem] border border-gray-100 focus-within:border-indigo-200 transition-all text-center">
                            <label class="block text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-4">Unidades Terminadas</label>
                            <div class="flex items-center justify-center gap-4">
                                <input v-model="prodForm.cantidad_entrada" type="number" class="w-32 bg-transparent border-b-2 border-indigo-100 p-0 text-5xl font-black text-indigo-900 text-center focus:ring-0 focus:border-indigo-600 transition-all">
                                <span class="text-xl font-bold text-gray-300 mt-4 italic">/ {{ selectedLote.cantidad_actual }}</span>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-indigo-600 text-white py-6 rounded-[2rem] text-lg font-black shadow-xl hover:bg-indigo-700 transition-all active:scale-95">ENVIAR A CALIDAD</button>
                </form>
            </div>
        </div>

        <!-- Modal Parada Redesigned -->
        <div v-if="showStopModal" class="fixed inset-0 z-[60] overflow-y-auto flex items-center justify-center p-4">
            <div @click="showStopModal = false" class="fixed inset-0 bg-rose-900/40 backdrop-blur-md transition-opacity"></div>
            
            <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-2xl transform transition-all max-w-lg w-full p-10 relative z-10 border border-rose-50">
                <div class="absolute top-0 right-0 p-8">
                    <button @click="showStopModal = false" class="text-gray-300 hover:text-rose-500 transition-colors">
                        <X class="w-7 h-7" />
                    </button>
                </div>

                <div class="text-center mb-10">
                    <div class="w-20 h-20 bg-rose-50 rounded-3xl flex items-center justify-center mx-auto mb-6 border border-rose-100 relative group">
                        <AlertTriangle class="w-10 h-10 text-rose-600 group-hover:scale-110 transition-transform" />
                        <div class="absolute inset-0 bg-rose-200 blur-2xl opacity-20 -z-10 group-hover:opacity-40 transition-opacity"></div>
                    </div>
                    <h3 class="text-3xl font-black text-gray-900 tracking-tight">Reporte de Parada</h3>
                    <p class="text-gray-400 font-medium italic mt-2">Fallo en Máquina: <span class="font-black text-rose-600 uppercase">{{ selectedMaquina.codigo }}</span></p>
                </div>

                <form @submit.prevent="submitStop" class="space-y-6">
                    <div class="space-y-4">
                        <div class="bg-gray-50 p-6 rounded-[2rem] border border-gray-100 focus-within:border-rose-200 transition-all">
                            <label class="flex items-center gap-2 text-[10px] font-black text-rose-400 uppercase tracking-widest mb-3">
                                <Settings class="w-3.5 h-3.5" /> Naturaleza del Fallo
                            </label>
                            <select v-model="stopForm.tipo" class="w-full bg-transparent border-none p-0 text-lg font-black text-gray-700 focus:ring-0 appearance-none hover:cursor-pointer">
                                <option>Mecánica</option>
                                <option>Eléctrica</option>
                                <option>Falta de Insumos</option>
                                <option>Ajuste de Parámetros</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-6 rounded-[2rem] border border-gray-100 focus-within:border-rose-200 transition-all">
                                <label class="flex items-center gap-2 text-[10px] font-black text-rose-400 uppercase tracking-widest mb-3">
                                    <Clock class="w-3.5 h-3.5" /> Est. (min)
                                </label>
                                <input v-model="stopForm.duracion" type="number" class="w-full bg-transparent border-none p-0 text-xl font-black text-gray-700 focus:ring-0">
                            </div>
                            <div class="bg-gray-50 p-6 rounded-[2rem] border border-gray-100 focus-within:border-rose-200 transition-all opacity-50">
                                <label class="flex items-center gap-2 text-[10px] font-black text-rose-400 uppercase tracking-widest mb-3">
                                    <Calendar class="w-3.5 h-3.5" /> Fecha
                                </label>
                                <input v-model="stopForm.fecha" type="date" disabled class="w-full bg-transparent border-none p-0 text-xs font-black text-gray-400 focus:ring-0">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-rose-600 text-white py-6 rounded-[2rem] text-lg font-black shadow-xl shadow-rose-100 hover:bg-rose-700 transition-all active:scale-95 uppercase tracking-widest">REGISTRAR PARADA CRÍTICA</button>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
