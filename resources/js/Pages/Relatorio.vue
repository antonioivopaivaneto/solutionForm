<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale, ArcElement,
} from 'chart.js';
import { Pie, Bar } from 'vue-chartjs';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import Multiselect from '@vueform/multiselect';
import '@vueform/multiselect/themes/default.css';

const props = defineProps({ condominios: Object, canais: Array,assuntos:Array });


const condominios = props.condominios;

const submit = () => {
    form.data = Datas.value
    router.post('/relatorioShow',form, {
        preserveScroll: true,
        onSuccess: () => {

        },
    })
}

const dataBar = {
    labels: [

    ],
    datasets: [
        {
            label: 'Locais',
            backgroundColor: '#f87979',
            data: []
        }
    ]
}
const optionsBar = {
    responsive: true,
    maintainAspectRatio: false
}
ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend)

const data = {
    labels: [],
    datasets: [
        {
            backgroundColor: ['#41B883', '#E46651', '#00D8FF', '#DD1B16'],
            data: [40, 20, 80, 10]
        }
    ]
};

const options = {
    responsive: true,
    maintainAspectRatio: false
};

ChartJS.register(ArcElement, Tooltip, Legend);

const msgSucesso = ref(false);

function formatarNumero(telefone) {
    // Remove os traços e parênteses do número de telefone
    return telefone.replace(/[-()\s]/g, '');
}

const Datas = ref();

const onSelectUnidade = (value) => {
    form.unidade = value;
};
const condominiosFormatados = condominios.map(condominio => ({
    value: condominio.id, // Usar o ID como valor
    label: condominio.nome // Usar o nome como texto exibido
}));
const assuntosFormatados = props.assuntos.map(assunto => ({
    value: assunto.assunto, // Usar o ID como valor
    label: assunto.assunto // Usar o nome como texto exibido
}));

const form = useForm({
    condominio_id: '',
    data: '',
    assunto: '',

});

</script>

<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <div class="py-1">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <div class="">
                    <div class=" flex gap-5 bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                        <div class="w-64">
                            Condominio: <multiselect v-model="form.condominio_id" :searchable="true"
                                :options="condominiosFormatados" placeholder="Selecione uma opção"
                                :custom-label="customLabel" @update:modelValue="onSelectUnidade">
                            </multiselect>
                        </div>
                        <div class="w-64">
                            Assunto: <multiselect v-model="form.assunto" :searchable="true"
                                :options="assuntosFormatados" placeholder="Selecione uma opção"
                                :custom-label="customLabel" @update:modelValue="onSelectUnidade">
                            </multiselect>
                        </div>
                        <div class="">
                            Data: <VueDatePicker format="dd/MM/yyyy" locale="pt-br" v-model="Datas" range>
                            </VueDatePicker>
                        </div>
                        <div class="mt-6">
                            <PrimaryButton @click="submit()">Buscar</PrimaryButton>
                        </div>

                    </div>

                </div>
                <div class="grid grid-cols-3 gap-5 mt-5">
                    <div class=" bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class=" bg-gray-800 text-white p-2 rounded-t">
                            <h1>Assuntos</h1>
                        </div>
                        <div>
                            <Pie :data="data" :options="options" />
                        </div>
                    </div>
                    <div class=" bg-white border border-gray-200 rounded-lg shadow-sm ">
                        <div class=" bg-gray-800 text-white p-2 rounded-t mb-2">
                            <h1>Andamentos</h1>
                        </div>
                        <div class="grid grid-cols-3 gap-5 mx-2 h-36 flex-col justify-center item mt-14   ">
                            <div
                                class="bg-yellow-500 text-center text-white font-black p-2 rounded-sm flex flex-col justify-center">
                                <div class="">0</div>
                                <div class="">Andamento</div>
                            </div>
                            <div
                                class="bg-green-700 text-center text-white font-black p-2 rounded-sm flex flex-col justify-center">
                                <div class="">0</div>
                                <div class="">Finalizados</div>
                            </div>
                            <div
                                class="bg-red-700 text-center text-white font-black p-2 rounded-sm flex flex-col justify-center">
                                <div class="">0</div>
                                <div class="">Aberto</div>
                            </div>
                        </div>
                    </div>

                    <div class=" bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class=" bg-gray-800 text-white p-2 rounded-t">
                            <h1>Local</h1>
                        </div>
                        <div>
                            <Bar :data="dataBar" :options="optionsBar" />
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-5 mt-5">
                    <div class=" bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class=" bg-gray-800 text-white p-2 rounded-t">
                            <h1>Unidades </h1>
                        </div>
                        <div>
                            <Pie :data="data" :options="options" />
                        </div>
                    </div>

                    <div class=" bg-white border border-gray-200 rounded-lg shadow-sm ">
                        <div class=" bg-gray-800 text-white p-2 rounded-t mb-2">
                            <h1>Canais de Respostas</h1>
                        </div>
                        <div class="grid grid-cols-3 gap-5 mx-2 h-36 flex-col justify-center item mt-14   ">
                            <div
                                class="bg-yellow-500 text-center text-white font-black p-2 rounded-sm flex flex-col justify-center">
                                <div class="">0</div>
                                <div class="">Email</div>
                            </div>
                            <div
                                class="bg-green-700 text-center text-white font-black p-2 rounded-sm flex flex-col justify-center">
                                <div class="">0</div>
                                <div class="">Whatsapp</div>
                            </div>
                            <div
                                class="bg-red-700 text-center text-white font-black p-2 rounded-sm flex flex-col justify-center">
                                <div class="">0</div>
                                <div class="">Telefone</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
