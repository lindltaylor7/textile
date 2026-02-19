<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    lotes: Array,
    estaciones: Array
});

const showMoveModal = ref(false);
const selectedLote = ref(null);

const moveForm = useForm({
    lote_id: '',
    estacion_destino_id: '',
    cantidad_entrada: 0,
    usuario_id: null,
    maquina_id: null
});

const openMoveModal = (lote) => {
    selectedLote.value = lote;
    moveForm.lote_id = lote.id;
    moveForm.cantidad_entrada = lote.cantidad_actual;
    moveForm.usuario_id = 1; // Testing with Admin/User ID 1
    showMoveModal.value = true;
};

const submitMove = () => {
    moveForm.post(route('lotes.move'), {
        onSuccess: () => {
            showMoveModal.value = false;
            moveForm.reset();
        },
    });
};
</script>

<template>
    <Head title="Trazabilidad de Lotes" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-2xl font-bold text-gray-800">Trazabilidad de Lotes</h2>
        </template>

        <div class="py-12 bg-gray-50 min-h-screen">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lote</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pedido</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad Actual</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">EstaciÃ³n</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="lote in lotes" :key="lote.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap font-mono font-bold text-indigo-700">{{ lote.codigo }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ lote.pedido.codigo }}</td>
                                <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-800">{{ lote.cantidad_actual }} u.</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span v-if="lote.estacion_actual" class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-xs font-bold uppercase tracking-wide border border-indigo-200">
                                        {{ lote.estacion_actual.nombre }}
                                    </span>
                                    <span v-else class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-bold uppercase tracking-wide border border-gray-200">
                                        POR INICIAR
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button 
                                        @click="openMoveModal(lote)"
                                        class="bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white px-3 py-1 rounded-md transition-all font-semibold border border-indigo-100"
                                    >
                                        Mover a EstaciÃ³n
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Move Lote -->
        <div v-if="showMoveModal" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div @click="showMoveModal = false" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Mover Lote: {{ selectedLote.codigo }}</h3>
                    <form @submit.prevent="submitMove" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">EstaciÃ³n Destino</label>
                            <select v-model="moveForm.estacion_destino_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 sm:text-sm" required>
                                <option value="" disabled>Seleccione estaciÃ³n...</option>
                                <option v-for="est in estaciones" :key="est.id" :value="est.id">
                                    {{ est.nombre }} (Orden: {{ est.orden }})
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Cantidad a Procesar</label>
                            <input v-model="moveForm.cantidad_entrada" type="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 sm:text-sm" required>
                        </div>
                        <div class="mt-8 flex justify-end space-x-3">
                            <button @click="showMoveModal = false" type="button" class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Cancelar</button>
                            <button type="submit" :disabled="moveForm.processing" class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-indigo-700 transition-colors">
                                Registrar Movimiento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
