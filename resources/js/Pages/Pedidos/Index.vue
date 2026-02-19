<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { 
    ClipboardList, 
    Plus, 
    Search, 
    MoreVertical, 
    Eye, 
    X,
    Calendar,
    Hash,
    Layers
} from 'lucide-vue-next';

const props = defineProps({
    pedidos: Array
});

const form = useForm({
    codigo: '',
    fecha: new Date().toISOString().split('T')[0],
    cantidad: 100,
    cantidad_lotes: 5
});

const showModal = ref(false);

const submit = () => {
    form.post(route('pedidos.store'), {
        onSuccess: () => {
            showModal.value = false;
            form.reset();
        },
    });
};

const getStatusClass = (status) => {
    const classes = {
        'CREADO': 'bg-blue-50 text-blue-600 border-blue-100',
        'EN_PRODUCCION': 'bg-amber-50 text-amber-600 border-amber-100',
        'EN_CALIDAD': 'bg-purple-50 text-purple-600 border-purple-100',
        'FINALIZADO': 'bg-emerald-50 text-emerald-600 border-emerald-100',
        'CANCELADO': 'bg-rose-50 text-rose-600 border-rose-100',
    };
    return classes[status] || 'bg-gray-50 text-gray-600 border-gray-100';
};
</script>

<template>
    <Head title="Gestión de Pedidos" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h2 class="text-4xl font-black text-gray-900 tracking-tight mb-2">Pedidos de Venta</h2>
                    <p class="text-gray-400 font-medium italic">Alta y seguimiento de órdenes de producción</p>
                </div>
                <button 
                    @click="showModal = true"
                    class="group flex items-center gap-3 bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-4 rounded-2xl transition-all font-black shadow-xl shadow-indigo-100 active:scale-95"
                >
                    <Plus class="w-5 h-5 group-hover:rotate-90 transition-transform" />
                    NUEVO PEDIDO
                </button>
            </div>
        </template>

        <div class="pb-12 px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl">
                <!-- Search & Filters Mockup -->
                <div class="mb-8 bg-white/50 backdrop-blur-md p-4 rounded-[2rem] border border-white flex flex-col md:flex-row gap-4 items-center">
                    <div class="flex-1 relative w-full">
                        <Search class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
                        <input type="text" placeholder="Buscar pedidos por código o cliente..." class="w-full bg-white border-none rounded-xl pl-12 pr-4 py-3 text-sm font-bold text-gray-700 focus:ring-2 focus:ring-indigo-100 transition-all shadow-sm">
                    </div>
                    <div class="flex gap-2 w-full md:w-auto">
                        <button class="flex-1 md:flex-none px-6 py-3 bg-white rounded-xl text-xs font-black text-gray-400 uppercase tracking-widest border border-gray-50 hover:bg-gray-50 transition-all font-bold shadow-sm">Filtrar</button>
                        <button class="flex-1 md:flex-none px-6 py-3 bg-white rounded-xl text-xs font-black text-gray-400 uppercase tracking-widest border border-gray-100 hover:bg-gray-50 transition-all font-bold shadow-sm">Exportar</button>
                    </div>
                </div>

                <!-- Main Table Container -->
                <div class="bg-white overflow-hidden shadow-2xl rounded-[2.5rem] border border-gray-100">
                    <div class="p-0 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-50">
                            <thead class="bg-indigo-50/30">
                                <tr>
                                    <th class="px-8 py-6 text-left text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em]">Código de Orden</th>
                                    <th class="px-8 py-6 text-left text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em]">Registro</th>
                                    <th class="px-8 py-6 text-left text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em]">Volumen</th>
                                    <th class="px-8 py-6 text-left text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em]">Distribución</th>
                                    <th class="px-8 py-6 text-left text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em]">Estado Actual</th>
                                    <th class="px-8 py-6 text-right"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-50">
                                <tr v-for="pedido in pedidos" :key="pedido.id" class="group hover:bg-indigo-50/20 transition-all duration-300">
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                                                <ClipboardList class="w-5 h-5" />
                                            </div>
                                            <div class="text-sm font-black text-indigo-900 font-mono tracking-tighter uppercase">{{ pedido.codigo }}</div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="text-sm text-gray-600 font-bold flex items-center gap-2">
                                            <Calendar class="w-4 h-4 text-gray-300" />
                                            {{ pedido.fecha }}
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="text-lg font-black text-gray-900 flex items-baseline gap-1">
                                            {{ pedido.cantidad }}
                                            <span class="text-[10px] text-gray-400 uppercase tracking-widest">unidades</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="flex items-center gap-2 px-3 py-1 bg-gray-50 rounded-lg w-max border border-gray-100">
                                            <Layers class="w-3.5 h-3.5 text-gray-400" />
                                            <span class="text-xs font-black text-gray-600 tracking-tighter">{{ pedido.lotes_count }} Lotes</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <span :class="['px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest border shadow-sm', getStatusClass(pedido.estado)]">
                                            {{ pedido.estado }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button class="p-2 bg-white rounded-xl border border-gray-100 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                                                <Eye class="w-4 h-4" />
                                            </button>
                                            <button class="p-2 bg-white rounded-xl border border-gray-100 text-gray-400 hover:bg-gray-50 transition-all shadow-sm">
                                                <MoreVertical class="w-4 h-4" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="pedidos.length === 0">
                                    <td colspan="6" class="px-8 py-24 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="w-20 h-20 bg-gray-50 rounded-[2rem] flex items-center justify-center text-gray-200">
                                                <ClipboardList class="w-10 h-10" />
                                            </div>
                                            <div class="text-sm text-gray-400 font-medium italic">No se han encontrado pedidos registrados.</div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal New Pedido Redesigned -->
        <div v-if="showModal" class="fixed inset-0 z-[60] overflow-y-auto flex items-center justify-center p-4">
            <div @click="showModal = false" class="fixed inset-0 bg-indigo-900/60 backdrop-blur-md transition-opacity"></div>
            
            <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-2xl transform transition-all max-w-lg w-full p-10 relative z-10 border border-indigo-50">
                <div class="absolute top-0 right-0 p-8">
                    <button @click="showModal = false" class="text-gray-300 hover:text-rose-500 transition-colors">
                        <X class="w-7 h-7" />
                    </button>
                </div>

                <div class="text-center mb-10">
                    <div class="w-20 h-20 bg-indigo-50 rounded-3xl flex items-center justify-center mx-auto mb-6 border border-indigo-100">
                        <ClipboardList class="w-10 h-10 text-indigo-600" />
                    </div>
                    <h3 class="text-3xl font-black text-gray-900 tracking-tight">Nuevo Pedido</h3>
                    <p class="text-gray-400 font-medium italic mt-2">Cree una nueva orden de fabricación</p>
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid grid-cols-1 gap-6">
                        <div class="bg-gray-50 p-6 rounded-[2rem] border border-gray-100 focus-within:border-indigo-200 transition-all">
                            <label class="flex items-center gap-2 text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-2">
                                <Hash class="w-3 h-3" /> Código de Identificación
                            </label>
                            <input v-model="form.codigo" type="text" class="w-full bg-transparent border-none p-0 text-xl font-black text-indigo-900 placeholder:text-gray-200 focus:ring-0" placeholder="P-0000" required>
                        </div>

                        <div class="bg-gray-50 p-6 rounded-[2rem] border border-gray-100 focus-within:border-indigo-200 transition-all">
                            <label class="flex items-center gap-2 text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-2">
                                <Calendar class="w-3 h-3" /> Fecha de Entrega
                            </label>
                            <input v-model="form.fecha" type="date" class="w-full bg-transparent border-none p-0 text-lg font-black text-indigo-900 focus:ring-0" required>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-6 rounded-[2rem] border border-gray-100 focus-within:border-indigo-200 transition-all">
                                <label class="block text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-2">Cantidad</label>
                                <input v-model="form.cantidad" type="number" class="w-full bg-transparent border-none p-0 text-xl font-black text-indigo-900 focus:ring-0" required>
                            </div>
                            <div class="bg-gray-50 p-6 rounded-[2rem] border border-gray-100 focus-within:border-indigo-200 transition-all">
                                <label class="block text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-2">Lotes</label>
                                <input v-model="form.cantidad_lotes" type="number" class="w-full bg-transparent border-none p-0 text-xl font-black text-indigo-900 focus:ring-0" required>
                            </div>
                        </div>
                    </div>

                    <button 
                        type="submit" 
                        :disabled="form.processing"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-6 rounded-[2rem] text-lg font-black transition-all shadow-xl shadow-indigo-100 active:scale-95 disabled:opacity-50 mt-4"
                    >
                        GENERAR ORDEN DE PRODUCCIÓN
                    </button>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
