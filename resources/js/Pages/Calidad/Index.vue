<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { 
    FlaskConical, 
    CheckCircle2, 
    AlertCircle, 
    History, 
    X, 
    Search,
    ShieldCheck,
    AlertTriangle,
    FileText,
    Activity,
    ChevronRight,
    ClipboardCheck
} from 'lucide-vue-next';

const props = defineProps({
    controles: Array,
    defectos: Array,
    lotesDisponibles: Array
});

const showInspectModal = ref(false);
const selectedLote = ref(null);

const inspectForm = useForm({
    lote_id: '',
    resultado: 'LIBERADO',
    cantidad_defectuosa: 0,
    defecto_id: '',
    observaciones: '',
    usuario_id: 1 // Test Admin
});

const openInspectModal = (lote) => {
    selectedLote.value = lote;
    inspectForm.lote_id = lote.id;
    showInspectModal.value = true;
};

const submitInspection = () => {
    inspectForm.post(route('calidad.store'), {
        onSuccess: () => {
            showInspectModal.value = false;
            inspectForm.reset();
        },
    });
};

const getResultClass = (res) => {
    const classes = {
        'LIBERADO': 'bg-emerald-50 text-emerald-600 border-emerald-100',
        'RECHAZADO': 'bg-rose-50 text-rose-600 border-rose-100',
        'REPROCESO': 'bg-amber-50 text-amber-600 border-amber-100',
    };
    return classes[res] || 'bg-gray-50 text-gray-600 border-gray-100';
};
</script>

<template>
    <Head title="Control de Calidad" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h2 class="text-4xl font-black text-gray-900 tracking-tight mb-2">Control de Calidad</h2>
                    <p class="text-gray-400 font-medium italic">Inspección final y liberación de producto</p>
                </div>
                <div class="flex items-center gap-4 bg-white/50 backdrop-blur-md p-2 rounded-2xl border border-white shadow-sm">
                    <div class="w-12 h-12 bg-purple-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-purple-100">
                        <ShieldCheck class="w-6 h-6" />
                    </div>
                    <div class="pr-4">
                        <div class="text-[10px] font-black text-purple-600 uppercase tracking-widest">Estación</div>
                        <div class="text-sm font-black text-gray-900 uppercase tracking-tighter">Auditoría</div>
                    </div>
                </div>
            </div>
        </template>

        <div class="pb-12 px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl">
                <!-- Inspection Queue Section -->
                <div class="mb-12">
                    <div class="flex items-center gap-3 mb-6">
                        <Activity class="w-5 h-5 text-gray-400" />
                        <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em]">Cola de Inspección Inmediata</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div v-for="lote in lotesDisponibles" :key="lote.id" class="bg-white/60 backdrop-blur-md p-8 rounded-[2.5rem] border border-white shadow-xl hover:shadow-2xl transition-all duration-500 overflow-hidden relative group">
                            <div class="relative z-10 flex justify-between items-start mb-6">
                                <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600">
                                    <FlaskConical class="w-7 h-7" />
                                </div>
                                <div class="text-right">
                                    <div class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-1">Unidades</div>
                                    <div class="text-2xl font-black text-gray-900">{{ lote.cantidad_actual }}</div>
                                </div>
                            </div>
                            <div class="relative z-10 space-y-2 mb-8">
                                <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest">Identificador de Lote</h4>
                                <div class="text-xl font-black text-indigo-900 font-mono tracking-tighter uppercase">{{ lote.codigo }}</div>
                                <div class="text-xs font-medium text-gray-400 italic">Pedido: {{ lote.pedido?.codigo }}</div>
                            </div>
                            <button 
                                @click="openInspectModal(lote)"
                                class="relative z-10 w-full bg-indigo-600 text-white py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-indigo-100 hover:bg-indigo-900 transition-all flex items-center justify-center gap-2 active:scale-95"
                            >
                                <CheckCircle2 class="w-4 h-4" />
                                INICIAR INSPECCIÓN
                            </button>
                            <!-- Background decoration -->
                            <div class="absolute -bottom-8 -right-8 w-32 h-32 bg-indigo-50 rounded-full opacity-50 group-hover:scale-110 transition-transform"></div>
                        </div>
                        <div v-if="lotesDisponibles.length === 0" class="col-span-full py-16 text-center bg-gray-50/50 rounded-[2.5rem] border-2 border-dashed border-gray-100">
                            <div class="w-16 h-16 bg-white rounded-3xl flex items-center justify-center mx-auto mb-4 shadow-sm text-gray-200">
                                <FlaskConical class="w-8 h-8" />
                            </div>
                            <div class="text-sm font-medium text-gray-400 italic">No hay lotes esperando inspección.</div>
                        </div>
                    </div>
                </div>

                <!-- History Section -->
                <div class="bg-white overflow-hidden shadow-2xl rounded-[2.5rem] border border-gray-100">
                    <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-white">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-indigo-900 rounded-xl flex items-center justify-center text-white">
                                <History class="w-5 h-5" />
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-gray-900 tracking-tight">Historial de Auditoría</h3>
                                <p class="text-xs text-gray-400 font-medium italic mt-0.5">Últimas 50 revisiones registradas</p>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-50">
                            <thead class="bg-indigo-50/30">
                                <tr>
                                    <th class="px-8 py-6 text-left text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em]">Lote Auditado</th>
                                    <th class="px-8 py-6 text-left text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em]">Dictamen Final</th>
                                    <th class="px-8 py-6 text-left text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em]">No Conformidades</th>
                                    <th class="px-8 py-6 text-left text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em]">Fecha Auditoría</th>
                                    <th class="px-8 py-6 text-right"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                <tr v-for="control in controles" :key="control.id" class="group hover:bg-gray-50/50 transition-all duration-300">
                                    <td class="px-8 py-6">
                                        <div class="text-sm font-black text-indigo-900 font-mono tracking-tighter uppercase">{{ control.lote?.codigo }}</div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <span :class="['px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest border', getResultClass(control.resultado)]">
                                            {{ control.resultado }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div v-if="control.resultado !== 'LIBERADO'" class="flex items-center gap-2 text-rose-500 font-bold text-xs uppercase tracking-tighter">
                                            <AlertTriangle class="w-3.5 h-3.5" />
                                            {{ control.cantidad_defectuosa }} pcs defectuosas
                                        </div>
                                        <div v-else class="text-xs font-bold text-gray-300 uppercase italic">Ninguna</div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="text-xs font-bold text-gray-500 bg-gray-50 w-max px-3 py-1 rounded-lg border border-gray-100">
                                            {{ control.fecha_inspeccion }}
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <button class="p-2.5 bg-white rounded-xl border border-gray-100 text-gray-400 hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                                            <FileText class="w-4 h-4" />
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="controles.length === 0">
                                    <td colspan="5" class="px-8 py-20 text-center text-gray-400 italic">Sin registros de auditoría aún.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inspection Modal Redesigned -->
        <div v-if="showInspectModal" class="fixed inset-0 z-[60] overflow-y-auto flex items-center justify-center p-4">
            <div @click="showInspectModal = false" class="fixed inset-0 bg-indigo-900/60 backdrop-blur-md transition-opacity"></div>
            
            <div class="bg-white rounded-[3rem] overflow-hidden shadow-2xl transform transition-all max-w-2xl w-full p-12 relative z-10 border border-indigo-50">
                <div class="absolute top-0 right-0 p-10">
                    <button @click="showInspectModal = false" class="text-gray-300 hover:text-rose-500 transition-colors">
                        <X class="w-8 h-8" />
                    </button>
                </div>

                <div class="flex items-start gap-8 mb-12">
                    <div class="w-24 h-24 bg-indigo-50 rounded-[2rem] flex items-center justify-center text-indigo-600 border border-indigo-100 shrink-0">
                        <FlaskConical class="w-12 h-12" />
                    </div>
                    <div>
                        <h3 class="text-4xl font-black text-gray-900 tracking-tight mb-2">Dictamen Técnico</h3>
                        <p class="text-gray-400 font-medium italic">Evaluación de lote: <span class="text-indigo-600 font-black uppercase tracking-widest">{{ selectedLote?.codigo }}</span></p>
                    </div>
                </div>

                <form @submit.prevent="submitInspection" class="space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Result Select -->
                        <div class="space-y-4">
                            <label class="block text-[10px] font-black text-indigo-400 uppercase tracking-widest">Resultado de Auditoría</label>
                            <div class="grid grid-cols-1 gap-2">
                                <button v-for="res in ['LIBERADO', 'REPROCESO', 'RECHAZADO']" 
                                        :key="res"
                                        type="button"
                                        @click="inspectForm.resultado = res"
                                        :class="['p-4 rounded-2xl border-2 text-xs font-black uppercase tracking-widest transition-all text-left flex items-center justify-between', 
                                                inspectForm.resultado === res ? 'bg-indigo-600 border-indigo-600 text-white shadow-lg shadow-indigo-100' : 'bg-white border-gray-100 text-gray-400 hover:border-indigo-200']">
                                    {{ res }}
                                    <ClipboardCheck v-if="inspectForm.resultado === res" class="w-4 h-4" />
                                </button>
                            </div>
                        </div>

                        <!-- Defect Details -->
                        <div class="space-y-6">
                            <div v-if="inspectForm.resultado !== 'LIBERADO'">
                                <div class="bg-rose-50 p-6 rounded-3xl border border-rose-100">
                                    <label class="block text-[10px] font-black text-rose-400 uppercase tracking-widest mb-4">Unidades con Defecto</label>
                                    <input v-model="inspectForm.cantidad_defectuosa" type="number" class="w-full bg-transparent border-none p-0 text-4xl font-black text-rose-600 focus:ring-0">
                                    <div class="mt-6">
                                        <label class="block text-[10px] font-black text-rose-400 uppercase tracking-widest mb-2">Tipo de Defecto</label>
                                        <select v-model="inspectForm.defecto_id" class="w-full bg-white border-none rounded-xl text-xs font-black text-gray-600 focus:ring-0 p-3 shadow-sm appearance-none">
                                            <option value="" disabled>Seleccione...</option>
                                            <option v-for="def in defectos" :key="def.id" :value="def.id">{{ def.nombre }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="flex flex-col items-center justify-center h-full bg-emerald-50 rounded-3xl border border-emerald-100 p-8 text-center animate-pulse">
                                <ShieldCheck class="w-12 h-12 text-emerald-500 mb-4" />
                                <div class="text-xs font-black text-emerald-600 uppercase tracking-widest">Lote Conformado</div>
                                <p class="text-[10px] text-emerald-500 mt-2 font-medium italic">Listo para despacho final</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-3xl border border-gray-100 focus-within:border-indigo-200 transition-all">
                        <label class="block text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-3">Observaciones Técnicas</label>
                        <textarea v-model="inspectForm.observaciones" rows="3" class="w-full bg-transparent border-none p-0 text-sm font-medium text-gray-600 placeholder:text-gray-200 focus:ring-0 resize-none" placeholder="Escriba aquí cualquier detalle adicional..."></textarea>
                    </div>

                    <button 
                        type="submit" 
                        :disabled="inspectForm.processing"
                        class="w-full bg-indigo-600 text-white py-6 rounded-2xl text-lg font-black shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition-all active:scale-95 flex items-center justify-center gap-3"
                    >
                        APLICAR DICTAMEN FINAL
                        <ChevronRight class="w-6 h-6" />
                    </button>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
