<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale,ArcElement,
} from 'chart.js';
import { Pie, Bar } from 'vue-chartjs';


 const dataBar = {
  labels: [
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July',
    'August',
    'September',
    'October',
    'November',
    'December'
  ],
  datasets: [
    {
      label: 'Data One',
      backgroundColor: '#f87979',
      data: [40, 20, 12, 39, 10, 40, 39, 80, 40, 20, 12, 11]
    }
  ]
}

 const optionsBar = {
  responsive: true,
  maintainAspectRatio: false
}
ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend)


const data = {
    labels: ['VueJs', 'EmberJs', 'ReactJs', 'AngularJs'],
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

// Initialize showMore for each solicitacao
onMounted(() => {
    solicitacoes.data.forEach(solicitacao => {
        solicitacao.showMore = false;
    });
});

const msgSucesso = ref(false);

// Função para formatar o número de telefone
function formatarNumero(telefone) {
    // Remove os traços e parênteses do número de telefone
    return telefone.replace(/[-()\s]/g, '');
}

</script>

<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <div class="py-1">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <div class="w-80">
                    <div class=" bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                        Filtro:

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
                        <div class="grid grid-cols-3 gap-5 mx-2 h-36 flex-col justify-center   ">
                            <div class="bg-yellow-500 text-center text-white font-black p-2 rounded-sm flex flex-col justify-center">
                                <div class="">6</div>
                                <div class="">Andamento</div>
                            </div>
                            <div class="bg-green-700 text-center text-white font-black p-2 rounded-sm flex flex-col justify-center">
                                <div class="">6</div>
                                <div class="">Finalizados</div>
                            </div>
                            <div class="bg-red-700 text-center text-white font-black p-2 rounded-sm flex flex-col justify-center">
                                <div class="">6</div>
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




                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
