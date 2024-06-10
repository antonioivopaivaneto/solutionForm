<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';



defineProps({ solicitacoes: Array, condominios: Array, moradores: Array, assuntos: Array })


const remover = (id) => {
    if (confirm("Deseja remover esta solicitação ? ")) {

        router.delete(route('solicitacao.destroy', id), { preserveScroll: true })
    }

}
const concluir = (id) => {
    if (confirm("Essa solicitação entrara para o histórico, deseja concluir ? ")) {

        router.get(route('reabrirSolicitacao', id), { preserveScroll: true })
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

const Back = () => {
    window.history.back()
}
</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>





        <div class="py-12">

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex mb-3">
                    <a @click="Back"
                        class="px-4 py-2 cursor-pointer  bg-gray-500 hover:bg-gray-600 text-white rounded disabled:opacity-50 flex">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            style="fill: rgba(255, 255, 255, 0.8);transform: ;msFilter:;">
                            <path
                                d="M21 11H6.414l5.293-5.293-1.414-1.414L2.586 12l7.707 7.707 1.414-1.414L6.414 13H21z">
                            </path>
                        </svg>
                    </a>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 uppercase font-bold">Histórico  de Solicitacoes  Finalizada</div>

                    <div class="p-10   shadow-md sm:rounded-lg">
                        <table class="w-full   rounded text-sm text-center text-gray-800 border-2 dark:border-gray-400 ">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b-2 border-gray-500">
                                <tr class="bg-gray-500 text-white text-nowrap">
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
                                    <th scope="col" class="px-6 py-3">
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
                                class="odd:bg-gray-200 border-b border-gray-00 text-gray-700 text-nowrap ">

                                    <th scope="row" class="px-6 py-4 font-medium ">
                                        <a class="hover:text-blue-600 underline "
                                            :href="'/condominios/' + solicitacao.condominio.id">

                                            {{ solicitacao.condominio.nome }}
                                        </a>
                                    </th>
                                    <td class="px-6 py-4 text-nowrap">
                                        {{ solicitacao.unidade.nome }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ solicitacao.nome }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ solicitacao.assunto }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ solicitacao.solicitacao }}
                                    </td>
                                    <td class="px-6 py-4 text-nowrap">
                                        {{ formatarData(solicitacao.created_at) }}
                                    </td>
                                    <td class="w-full border text-center">
                                        <div class="flex flex-row justify-center p-1">
                                            <span v-for="fotos in solicitacao.fotos.slice(0, 3)" :key="fotos.id"
                                                class="flex-shrink-0 mx-2">
                                                <a class="cursor-pointer" :href="fotos.foto" target="_blank">
                                                    <img :src="fotos.foto" class="rounded-sm w-10 h-10" alt="">
                                                </a>
                                            </span>
                                            <span v-if="solicitacao.fotos.length > 3"
                                                class="flex items-center justify-center w-12">
                                                <box-icon name='plus-medical' color="#0072bb" size="sx"></box-icon>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span v-if="solicitacao.status == null">Aberto</span>
                                        <span v-if="solicitacao.status == 0">Aberto</span>
                                        <span v-if="solicitacao.status == 1">Concluido</span>
                                    </td>

                                    <td class="w-full py-4 ">
                                        <div class="flex gap-2">
                                            <a @click="concluir(solicitacao.id)"
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline mx-4 cursor-pointer">↩️Reabrir</a>

                                            <a @click="remover(solicitacao.id)"
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline cursor-pointer">🗑️Remover</a>
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
