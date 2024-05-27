<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';



defineProps({ solicitacoes: Array, condominios: Array, moradores: Array, assuntos: Array })


const remover = (id) => {
    if (confirm("Deseja remover esta solicitação ? ")) {

        router.delete(route('solicitacao.destroy', id), { preserveScroll: true })
    }

}
const concluir = (id) => {
    if (confirm("Essa solicitação entrara para o histórico, deseja concluir ? ")) {

        router.get(route('concluirSolicitacao', id), { preserveScroll: true })
    }

}
const formatarData = (data) => {
    const options = {
        day: 'numeric',
        month: 'numeric',
        year: 'numeric',
        hour: 'numeric',
        minute: 'numeric',
        hour12: false
    };

    return new Date(data).toLocaleDateString('pt-BR', options);
};


const processarLabel = (label) => {
    // Substitua '&laquo;' por 'Previous' e '&raquo;' por 'Next'
    return label.replace(/&laquo;/g, '').replace('Previous', 'Voltar').replace('Next', 'Proximo').replace(/&raquo;/g, '');
};

const showMore = ref(false)

const toggleShow = (solicitacao) => {
    solicitacao.showMore = !solicitacao.showMore;

}

// Initialize showMore for each solicitacao
onMounted(() => {
    solicitacoes.data.forEach(solicitacao => {
        solicitacao.showMore = false;
    });
});



</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>


        <div class="py-12">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-3 gap-5 ">


                    <div
                        class="w-full max-w-md p-4 bg-white border border-gray-200 rounded-lg shadow- sm:p-8 dark:bg-gray-800 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <h5 class="text-xl font-bold leading-none text-gray-900 dark:text-white"><span
                                    class="text-4xl">Assuntos</span><br> mais
                                Solicitados</h5>

                        </div>
                        <div class="flow-root">
                            <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                                <li class="py-3 sm:py-4">
                                    <div class="flex items-center" v-for="condominio in condominios"
                                        :key="condominio.id">

                                        <div class="flex-1 min-w-0 ms-4">
                                            <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                                {{ condominio.condominio }}
                                            </p>

                                        </div>
                                        <div
                                            class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                            {{ condominio.total }}
                                        </div>
                                    </div>
                                </li>

                            </ul>
                        </div>
                    </div>


                    <!-- outro-->

                    <div
                        class="w-full max-w-md p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <h5 class="text-xl font-bold leading-none text-white-900 dark:text-white"><span
                                    class="text-4xl">condominios</span><br> com maior requisicao</h5>

                        </div>
                        <div class="flow-root">
                            <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                                <li class="py-3 sm:py-4">
                                    <div class="flex items-center" v-for="assunto in assuntos" :key="assunto.id">

                                        <div class="flex-1 min-w-0 ms-4">
                                            <p class="text-sm font-medium text-white truncate dark:text-white">
                                                {{ assunto.assunto }}
                                            </p>

                                        </div>
                                        <div
                                            class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                            {{ assunto.total }}

                                        </div>
                                    </div>
                                </li>

                            </ul>
                        </div>
                    </div>
                    <!-- outro-->

                    <!-- outro-->

                    <div
                        class="w-full max-w-md p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <h5 class="text-xl font-bold leading-none text-gray-900 dark:text-white"><span
                                    class="text-4xl">condominios</span><br> com maior requisicao</h5>

                        </div>
                        <div class="flow-root">
                            <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                                <li class="py-3 sm:py-4">
                                    <div class="flex items-center" v-for="morador in moradores" :key="morador.id">

                                        <div class="flex-1 min-w-0 ms-4">
                                            <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                                {{ morador.nome }}
                                            </p>

                                        </div>
                                        <div
                                            class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                            {{ morador.total }}

                                        </div>
                                    </div>
                                </li>

                            </ul>
                        </div>
                    </div>
                    <!-- outro-->

                </div>
            </div>
        </div>



        <div class="py-12">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 uppercase font-bold">Lista de Solicitacoes por QRCode</div>
                    <div class=" overflow-x-auto sm:rounded-lg p-5 ">
                        <table class="w-full rounded text-sm text-left text-gray-800 border-2 dark:border-gray-400 ">
                            <thead class="text-xs  uppercase 0 ">
                                <tr class="bg-gray-500 text-white">
                                    <th scope="col" class="px-6 py-3">

                                        condominio


                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Unidade
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Morador
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Assunto
                                    </th>
                                    <th scope="col" class="px-44 py-3">
                                        solicitacao
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        data e hora
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        foto
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Status
                                    </th>
                                    <th scope="col" class="px-16 py-3 ">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>






                                <tr v-for="solicitacao in solicitacoes.data" :key="solicitacao.id"
                                    class="odd:bg-gray-200   border-b border-gray-00 text-gray-700">

                                    <th scope="row" class="px-6 py-4 font-medium ">
                                        <a class="hover:text-blue-600 underline "
                                            :href="'/condominios/' + solicitacao.condominio.id">

                                        {{ solicitacao.condominio.nome }}
                                        </a>
                                    </th>
                                    <td class="px-6 py-4">
                                        <a class="hover:text-blue-600 underline "
                                            :href="'/unidades/' + solicitacao.unidade.id">

                                        {{ solicitacao.unidade.nome }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ solicitacao.nome }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ solicitacao.assunto }}
                                    </td>
                                    <td class="text-center py-4">
                                        <div v-if="solicitacao.solicitacao.length < 10">
                                            {{ solicitacao.solicitacao }}
                                        </div>
                                        <div v-else>
                                            {{ solicitacao.showMore ? solicitacao.solicitacao :
                                        solicitacao.solicitacao.substring(0, 10) + ' ...' }}
                                            <div class="cursor-pointer text-blue-500" @click="toggleShow(solicitacao)">
                                                <span v-if="solicitacao.showMore" class="flex justify-center">

                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24"
                                                        style="fill: rgba(0, 0, 0, 0.7);transform: ;msFilter:;">
                                                        <path
                                                            d="M7.707 14.707 12 10.414l4.293 4.293 1.414-1.414L12 7.586l-5.707 5.707z">
                                                        </path>
                                                    </svg>


                                                  </span>
                                                <span v-else class="flex justify-center">


                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24"
                                                        style="fill: rgba(0, 0, 0, 0.7);transform: ;msFilter:;">
                                                        <path
                                                            d="M16.293 9.293 12 13.586 7.707 9.293l-1.414 1.414L12 16.414l5.707-5.707z">
                                                        </path>
                                                    </svg>


                                                </span>


                                            </div>
                                        </div>


                                    </td>
                                    <td class="px-6 py-4">
                                        {{ formatarData(solicitacao.created_at) }}
                                    </td>
                                    <td class="w-full border text-center  ">
                                        <div class="flex flex row  p-1">
                                            <span v-for="fotos in solicitacao.fotos.slice(0, 3)" :key="fotos.id">
                                                <a class="cursor-pointer" :href="fotos.foto" target="&_blank">
                                                    <img :src="fotos.foto" class="rounded-sm mx-0.5 w-8 h-10 mx-2 "
                                                        alt="">
                                                </a>
                                            </span>
                                            <span v-if="solicitacao.fotos.length > 3"
                                                class="w-3 flex items-center justify-center">
                                                <box-icon name='plus-medical' color="#0072bb" size="sx"></box-icon>
                                            </span>

                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <span v-if="solicitacao.status == null">Aberto</span>
                                        <span v-if="solicitacao.status == 0">Aberto</span>
                                        <span v-if="solicitacao.status == 1">Concluido</span>
                                    </td>

                                    <td class="w-full py-4 mx-auto text-center ">
                                        <div class="flex gap-2 justify-center">
                                            <a @click="concluir(solicitacao.id)"
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline mx-4 cursor-pointer"><svg
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24"
                                                    style="fill: rgba(0, 0, 0, 0.7);transform: ;msFilter:;">
                                                    <path
                                                        d="m10 15.586-3.293-3.293-1.414 1.414L10 18.414l9.707-9.707-1.414-1.414z">
                                                    </path>
                                                </svg></a>

                                            <a @click="remover(solicitacao.id)"
                                                class="font-medium text-blue-600  dark:text-blue-500 hover:underline  cursor-pointer"><svg
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24"
                                                    style="fill: rgba(0, 0, 0, 0.7);transform: ;msFilter:;">
                                                    <path
                                                        d="M5 20a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8h2V6h-4V4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v2H3v2h2zM9 4h6v2H9zM8 8h9v12H7V8z">
                                                    </path>
                                                    <path d="M9 10h2v8H9zm4 0h2v8h-2z"></path>
                                                </svg></a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="mt-4 flex gap-2 items-center">
                            <a :href="solicitacao.url" v-for="solicitacao in solicitacoes.links"
                                :key="solicitacao.label"
                                class="px-4 py-2 bg-gray-500 text-white rounded disabled:opacity-50"
                                :class="{ 'bg-gray-500': solicitacao.active }">
                                <span>{{ processarLabel(solicitacao.label) }}</span>
                            </a>
                        </div>









                    </div>


                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
