<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';


defineProps({ solicitacoes: Object })
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">Lista de Solicitacoes por QRCode</div>



<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
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
                    foto
                </th>
                <th scope="col" class="px-6 py-3">
                    Action
                </th>
            </tr>
        </thead>
        <tbody>


            <tr v-for="solicitacao in solicitacoes" :key="solicitacao.id" class="odd:bg-white   border-b dark:border-gray-700">

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
                    {{ solicitacao.foto }}
                    <img :src="solicitacao.foto" width="200" alt="">
                </td>
                <td class="px-6 py-4">
                    <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>


                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
