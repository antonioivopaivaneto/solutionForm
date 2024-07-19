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
import moment from 'moment';

const props = defineProps({assunto:'',datas:Array, condominios: Object,condominio: Array, canais: Array , status: Array,assuntos: Array,unidades:Array,locais:Array});


const condominios = props.condominios;
const condominio = props.condominio;

const submit = () => {
    form.data = Datas.value
    router.get('/relatorioShow',form, {
        preserveScroll: true,
        onSuccess: () => {

        },
    })
}

const formatDate = (dateString) => {
    return moment(dateString).format('D/M/YYYY');
}

const dataBar = {
    labels:props.locais.map(item => item.local),
    datasets: [
        {
            label: 'Locais',
            backgroundColor: '#f87979',
            data:props.locais.map(item => item.total)
        }
    ]
}
const optionsBar = {
    responsive: true,
    maintainAspectRatio: false
}
ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend)

const data = {
    labels: props.assuntos.map(item => item.assunto),
    datasets: [
        {
            backgroundColor: ['#41B883', '#E46651', '#00D8FF', '#DD1B16', '#ff0'],
            data: props.assuntos.map(item => item.total)
        }
    ]
};
const dataUnidades = {
    labels: props.unidades.map(item => item.unidade_nome),
    datasets: [
        {
            backgroundColor: ['#ff5733', '#c70039', '#900c3f', '#581845', '#28b463'],
            data: props.unidades.map(item => item.total)
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

const form = useForm({
    condominio_id: '',
    data: '',
    assunto: '',

});

const assuntosFormatados = props.assuntos.map(assunto => ({
    value: assunto.assunto, // Usar o ID como valor
    label: assunto.assunto // Usar o nome como texto exibido
}));


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

                    <h1 class="font-bold text-xl mt-2">{{ condominio.nome }}</h1>
                    <h1 class="font-bold text-xl mt-2"> de {{formatDate( datas[0]) }} até {{formatDate(datas[1]) }}</h1>
                    <h1 class="font-bold text-xl mt-2">{{ assunto }}</h1>

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
                                <div class="">{{ status[2] }}</div>
                                <div class="">Andamento</div>
                            </div>
                            <div
                                class="bg-green-700 text-center text-white font-black p-2 rounded-sm flex flex-col justify-center">
                                <div class="">{{ status[1] }}</div>
                                <div class="">Finalizados</div>
                            </div>
                            <div
                                class="bg-red-700 text-center text-white font-black p-2 rounded-sm flex flex-col justify-center">
                                <div class="">{{ status[0] }}</div>
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
                            <Pie :data="dataUnidades" :options="options" />
                        </div>
                    </div>

                    <div class=" bg-white border border-gray-200 rounded-lg shadow-sm ">
                        <div class=" bg-gray-800 text-white p-2 rounded-t mb-2">
                            <h1>Canais de Respostas</h1>
                        </div>
                        <div class="grid grid-cols-3 gap-5 mx-2 h-36 flex-col justify-center item mt-14   ">
                            <div
                                class="bg-yellow-500 text-center text-white font-black p-2 rounded-sm flex flex-col justify-center">
                                <div class="">{{ canais.Email }}</div>
                                <div class="">Email</div>
                            </div>
                            <div
                                class="bg-green-700 text-center text-white font-black p-2 rounded-sm flex flex-col justify-center">
                                <div class="">{{ canais.Whatsapp }}</div>
                                <div class="">Whatsapp</div>
                            </div>
                            <div
                                class="bg-red-700 text-center text-white font-black p-2 rounded-sm flex flex-col justify-center">
                                <div class="">{{ canais.Telefone }}</div>
                                <div class="">Telefone</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
