<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';



defineProps({ solicitacoes: Array,condominios:Array,moradores:Array,assuntos:Array })


const remover = (id) => {
    if (confirm("Deseja remover esta solicitação ? ")) {

        router.delete(route('solicitacao.destroy', id), {preserveScroll: true})
    }

}
const concluir = (id) => {
    if (confirm("Essa solicitação entrara para o histórico, deseja concluir ? ")) {

        router.get(route('reabrirSolicitacao', id), {preserveScroll: true})
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
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout >
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Historico  de Finalizados</h2>
        </template>





        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 uppercase font-bold">Lista de Solicitacoes por QRCode</div>

                    <div class="p-10   shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right ">
                            <thead class="text-xs  uppercase 0 ">
                                <tr>
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
                                    class="odd:bg-white   border-b dark:border-gray-200 text-gray-700">

                                    <th scope="row" class="px-6 py-4 font-medium ">
                                        {{ solicitacao.condominio }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ solicitacao.unidade }}
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
                                    <td class="px-6 py-4">
                                        {{ formatarData(solicitacao.created_at) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <a target="&_blank" :href="solicitacao.foto">
                                        <img :src="solicitacao.foto" class="w-12" />
                                    </a>

                                    </td>
                                    <td class="px-6 py-4">
                                        <span v-if="solicitacao.status == null ">Aberto</span>
                                        <span v-if="solicitacao.status == 0 ">Aberto</span>
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
    <a :href="solicitacao.url"
        v-for="solicitacao in solicitacoes.links"
        :key="solicitacao.label"
        class="px-4 py-2 bg-gray-500 text-white rounded disabled:opacity-50"
        :class="{ 'bg-gray-500': solicitacao.active }"
    >
    <span>{{ processarLabel(solicitacao.label) }}</span>
    </a>
</div>









                    </div>


                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
