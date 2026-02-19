<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { 
    Scissors, 
    Package, 
    ArrowRightCircle, 
    Info, 
    X,
    Activity,
    ChevronRight
} from 'lucide-vue-next';

const props = defineProps({
    lotes: Array,
    estacion: Object
});

const showModal = ref(false);
const selectedLote = ref(null);

const form = useForm({
    lote_id: '',
    estacion_destino_id: props.estacion?.id,
    cantidad_entrada: 0,
    usuario_id: 1 // Test Admin
});

const openModal = (lote) => {
    selectedLote.value = lote;
    form.lote_id = lote.id;
    form.cantidad_entrada = lote.cantidad_actual;
    showModal.value = true;
};

const submit = () => {
    form.post(route('corte.move'), {
        onSuccess: () => {
            showModal.value = false;
        },
    });
};
</script>

<template>
    <Head title="Módulo de Corte" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h2 class="text-4xl font-black text-gray-900 tracking-tight mb-2">Estación de Corte</h2>
                    <p class="text-gray-400 font-medium italic">Gestión de piezas y preparación para costura</p>
                </div>
                <div class="flex items-center gap-4 bg-white/50 backdrop-blur-md p-2 rounded-2xl border border-white shadow-sm">
                    <div class="w-12 h-12 bg-amber-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-amber-100">
                        <Scissors class="w-6 h-6" />
                    </div>
                </div>
            </div>
        </template>

        <div class="pb-12 px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl">
                <!-- Errors and Flash -->
                <div v-if="$page.props.errors?.error" class="mb-6 bg-rose-50 border border-rose-100 p-4 rounded-2xl text-rose-600 text-sm font-bold flex items-center gap-3">
                    <X class="w-4 h-4" />
                    {{ $page.props.errors.error }}
                </div>
                <div v-if="$page.props.flash?.success" class="mb-6 bg-emerald-50 border border-emerald-100 p-4 rounded-2xl text-emerald-600 text-sm font-bold flex items-center gap-3">
                    <Activity class="w-4 h-4" />
                    {{ $page.props.flash.success }}
                </div>
                <!-- Info Alert -->
                <div class="mb-8 bg-indigo-900 rounded-3xl p-6 text-white flex items-center gap-6 shadow-2xl relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500 rounded-full blur-[60px] opacity-30"></div>
                    <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center shrink-0 border border-white/10">
                        <Info class="w-6 h-6 text-indigo-300" />
                    </div>
                    <div>
                        <div class="text-sm font-black uppercase tracking-widest mb-1 text-indigo-200">Recordatorio de Operación</div>
                        <p class="text-indigo-100 font-medium italic text-sm">Los lotes procesados aquí se trasladarán automáticamente a la cola de <span class="text-white font-black underline decoration-indigo-400 underline-offset-4">Costura</span>.</p>
                    </div>
                </div>

                <!-- Main Grid -->
                <div class="bg-white overflow-hidden shadow-2xl rounded-[2.5rem] border border-gray-100">
                    <div class="p-8 border-b border-gray-50 flex justify-between items-center">
                        <div>
                            <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight">Cola de Trabajo</h3>
                            <p class="text-xs text-gray-400 font-medium italic mt-1">Lotes pendientes por fraccionar</p>
                        </div>
                        <div class="text-[10px] font-black text-indigo-400 bg-indigo-50 px-3 py-1 rounded-full uppercase tracking-tighter">
                            {{ lotes.length }} items en lista
                        </div>
                    </div>

                    <div class="p-0 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-50">
                            <thead class="bg-indigo-50/30">
                                <tr>
                                    <th class="px-8 py-6 text-left text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em]">Referencia</th>
                                    <th class="px-8 py-6 text-left text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em]">Orden Venta</th>
                                    <th class="px-8 py-6 text-left text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em]">Stock</th>
                                    <th class="px-8 py-6 text-left text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em]">Progreso</th>
                                    <th class="px-8 py-6 text-right"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-50">
                                <tr v-for="lote in lotes" :key="lote.id" class="group hover:bg-indigo-50/20 transition-all duration-300">
                                    <td class="px-8 py-6">
                                        <div class="text-sm font-black text-indigo-900 font-mono tracking-tighter uppercase">{{ lote.codigo }}</div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-2 text-xs font-bold text-gray-500 bg-gray-50 w-max px-3 py-1 rounded-lg border border-gray-100">
                                            <Package class="w-3.5 h-3.5 text-gray-400" />
                                            {{ lote.pedido.codigo }}
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="text-xl font-black text-gray-900">{{ lote.cantidad_actual }}<span class="text-[10px] text-gray-400 font-medium ml-1">pcs</span></div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-3">
                                            <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-xl text-[10px] font-black uppercase tracking-widest border border-emerald-100">
                                                DISPONIBLE
                                            </span>
                                            <ArrowRightCircle class="w-4 h-4 text-emerald-200 group-hover:translate-x-1 transition-transform" />
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <button 
                                            @click="openModal(lote)"
                                            class="bg-indigo-600 hover:bg-indigo-800 text-white px-8 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all shadow-xl shadow-indigo-100 active:scale-95 flex items-center gap-2 ml-auto"
                                        >
                                            INICIAR CORTE
                                            <ChevronRight class="w-4 h-4" />
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="lotes.length === 0">
                                    <td colspan="5" class="px-8 py-24 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="w-20 h-20 bg-gray-50 rounded-[2rem] flex items-center justify-center text-gray-200">
                                                <Scissors class="w-10 h-10" />
                                            </div>
                                            <div class="text-sm text-gray-400 font-medium italic">Sin lotes pendientes por cortar.</div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Modal Redesigned -->
        <div v-if="showModal" class="fixed inset-0 z-[60] overflow-y-auto flex items-center justify-center p-4">
            <div @click="showModal = false" class="fixed inset-0 bg-indigo-900/60 backdrop-blur-md transition-opacity"></div>
            
            <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-2xl transform transition-all max-w-lg w-full p-10 relative z-10 border border-indigo-50">
                <div class="absolute top-0 right-0 p-8">
                    <button @click="showModal = false" class="text-gray-300 hover:text-rose-500 transition-colors">
                        <X class="w-7 h-7" />
                    </button>
                </div>

                <div class="text-center mb-10">
                    <div class="w-20 h-20 bg-amber-50 rounded-3xl flex items-center justify-center mx-auto mb-6 border border-amber-100 relative group">
                        <Scissors class="w-10 h-10 text-amber-600 group-hover:scale-110 transition-transform" />
                        <div class="absolute -top-2 -right-2 w-6 h-6 bg-emerald-500 rounded-full border-4 border-white animate-pulse"></div>
                    </div>
                    <h3 class="text-3xl font-black text-gray-900 tracking-tight">Finalizar Corte</h3>
                    <p class="text-gray-400 font-medium italic mt-2">Lote: <span class="font-black text-indigo-600 uppercase">{{ selectedLote.codigo }}</span></p>
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <div class="bg-gray-50 p-8 rounded-[2.5rem] border border-gray-100 focus-within:border-indigo-200 transition-all text-center">
                        <label class="block text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-4">Ingrese Cantidad Producida</label>
                        <div class="flex items-center justify-center gap-4">
                            <input v-model="form.cantidad_entrada" type="number" class="w-32 bg-transparent border-b-2 border-indigo-100 p-0 text-5xl font-black text-indigo-900 text-center focus:ring-0 focus:border-indigo-600 transition-all" required>
                            <span class="text-xl font-bold text-gray-300 mt-4">/ {{ selectedLote.cantidad_actual }}</span>
                        </div>
                        <p class="text-[10px] text-gray-400 mt-6 font-bold uppercase tracking-widest italic flex items-center justify-center gap-2">
                            <Activity class="w-3.5 h-3.5" /> Confirmará el traslado a costura
                        </p>
                    </div>

                    <button 
                        type="submit" 
                        :disabled="form.processing"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-6 rounded-[2rem] text-lg font-black transition-all shadow-xl shadow-indigo-100 active:scale-95 disabled:opacity-50 mt-4"
                    >
                        CONFIRMAR Y TRASLADAR
                    </button>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
