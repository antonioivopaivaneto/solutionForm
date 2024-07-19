<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { onMounted, reactive, ref } from 'vue';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const showModal = ref(false);
const solicitacaoSelected = ref();



defineProps({ solicitacoes: Array, users: Array, condominios: Array, moradores: Array, assuntos: Array, totalPorStatus: Array, })
const form = useForm({
    descricao: '',
    canal: '',
    responsavel: '',
    data: '',
    solicitacao_id: '',
});


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

const msgSucesso = ref(false)

const submit = () => {
    form.solicitacao_id = solicitacaoSelected.value;
    router.post('/retorno', form, {
        onFinish: () => {
            msgSucesso.value = true;
            closeModal(); // Chama a função para fechar o modal
            router.reload(); // Recarrega a página
        }
    });
}
const atualizarStatus = (solicitacaoId, novoStatus) => {
    solicitacaoSelected.value = solicitacaoId
    if (novoStatus === '1') {
        openModal()

    } else if (confirm("tem certeza de que deseja alterar o status desta solicitação? ")) {
        router.put(route('atualizarStatus', { id: solicitacaoId }), { status: novoStatus }, { preserveScroll: true })
        msgSucesso.value = true

    }


}


// Função para formatar o número de telefone
function formatarNumero(telefone) {
    // Remove os traços e parênteses do número de telefone
    return telefone.replace(/[-()\s]/g, '');
}


const getStatusLabel = (status) => {
    switch (status) {
        case 0:
            return 'Aberto';
        case 1:
            return 'Concluído';
        case 2:
            return 'Andamento';
        default:
            return '';
    }
}

const orderArrow = ref(true);

const filtro = (filtro, order) => {

    router.get(route('dashboard', { 'filtro': filtro, 'order': order }))

    if (order === 'asc') {
        orderArrow.value = true;
    } else {
        orderArrow.value = false;
    }
}

const pesquisaText = ref();


const pesquisa = () => {



    router.get(route('dashboard', { 'pesquisa': pesquisaText.value }), { preserveScroll: true })



}


const closeModal = () => {
    showModal.value = false;
    console.log(showModal.value)

};

const openModal = () => {
    showModal.value = true;
    console.log(showModal.value)

}

</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>



        <div class="py-2">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-4 gap-5 ">


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
                                    <div class="flex items-center" v-for="assunto in assuntos" :key="assunto.id">

                                        <div class="flex-1 min-w-0 ms-4">
                                            <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
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

                    <div
                        class="w-full max-w-md p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <h5 class="text-xl font-bold leading-none text-white-900 dark:text-white"><span
                                    class="text-4xl">Condominios</span><br> com maior requisicao</h5>

                        </div>
                        <div class="flow-root">
                            <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                                <li class="py-3 sm:py-4">
                                    <div class="flex items-center" v-for="condominio in condominios"
                                        :key="condominio.id">

                                        <div class="flex-1 min-w-0 ms-4">
                                            <p class="text-sm font-medium text-white truncate dark:text-white">
                                                {{ condominio.nome }}
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

                    <!-- outro-->

                    <div
                        class="w-full max-w-md p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <h5 class="text-xl font-bold leading-none text-gray-900 dark:text-white"><span
                                    class="text-4xl">Unidade</span><br> com maior requisicao</h5>

                        </div>
                        <div class="flow-root">
                            <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                                <li class="py-3 sm:py-4">
                                    <div class="flex items-center" v-for="morador in moradores" :key="morador.id">

                                        <div class="flex-1 min-w-0 ms-4">
                                            <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                                {{ morador.unidade_nome }}
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
                    <!-- outro-->

                    <div
                        class="w-full max-w-md p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <h5 class="text-xl font-bold leading-none text-gray-900 dark:text-white"><span
                                    class="text-4xl">Resumo</span><br>Andamento por Status</h5>

                        </div>
                        <div class="flow-root">
                            <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                                <li class="py-3 sm:py-4">
                                    <div class="flex items-center" v-for="total in totalPorStatus" :key="total.id">

                                        <div class="flex-1 min-w-0 ms-4">
                                            <p class="text-sm font-medium text-gray-900 truncate dark:text-white">

                                                {{ getStatusLabel(parseInt(total.status)) }}
                                            </p>
                                        </div>
                                        <div
                                            class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                            {{ total.total }}

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



        <div class="py-1">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 uppercase font-bold">Lista de Solicitacoes por QRCode</div>


                    <div class="flex flex-row-reverse mx-5 fixed right-12">
                        <div v-if="msgSucesso"
                            class="bg-green-100  border mb-5 w-96 border-green-400 text-green-700 px-4 py-3 rounded relative"
                            role="alert">
                            <strong class="font-bold">Sucesso! </strong>
                            <span class="block sm:inline">
                            </span>
                            <span @click="msgSucesso = false" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                                <svg class="fill-current h-6 w-6 text-green-500" role="button"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <title>Close</title>
                                    <path
                                        d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                                </svg>
                            </span>
                        </div>
                    </div>
                    <div class=" overflow-x-auto sm:rounded-lg p-5 mr-5 ">
                        <div class="mb-5 w-80 ">
                            <label class="block text-gray-700 ml-1 text-sm font-bold mb-2" for="username">
                                Pesquisar por Morador
                            </label>
                            <input type="text" @keyup.enter="pesquisa()" v-model="pesquisaText" placeholder="Seu Nome "
                                class="bg-gray-50 border w text-gray-700  border-gray-300 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500">

                        </div>
                        <table
                            class="w-full   rounded text-sm text-center text-gray-800 border-2 dark:border-gray-400 ">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b-2 border-gray-500">
                                <tr class="bg-gray-500 text-white text-nowrap">
                                    <th scope="col" class="px-4 py-3">

                                        condominio


                                    </th>
                                    <th scope="col" class="px-4 py-3">
                                        Unidade
                                    </th>
                                    <th scope="col" class="px-4 py-3">
                                        Local
                                    </th>
                                    <th scope="col" class="px-4 py-3 flex gap-2">

                                        <a> Morador</a>

                                        <svg v-if="orderArrow" @click="filtro('nome', 'asc')"
                                            xmlns="http://www.w3.org/2000/svg" width="10" height="10"
                                            viewBox="0 0 24 24"
                                            style="fill: rgba(255, 255, 255, 1);transform: ;msFilter:;">
                                            <path
                                                d="M3 19h18a1.002 1.002 0 0 0 .823-1.569l-9-13c-.373-.539-1.271-.539-1.645 0l-9 13A.999.999 0 0 0 3 19z">
                                            </path>
                                        </svg>
                                        <svg v-else @click="filtro('nome', 'desc')" xmlns="http://www.w3.org/2000/svg"
                                            width="10" height="10" viewBox="0 0 24 24"
                                            style="fill: rgba(255, 255, 255, 1);transform: ;msFilter:;">
                                            <path
                                                d="M11.178 19.569a.998.998 0 0 0 1.644 0l9-13A.999.999 0 0 0 21 5H3a1.002 1.002 0 0 0-.822 1.569l9 13z">
                                            </path>
                                        </svg>
                                    </th>
                                    <th scope="col" class="px-4 py-3">
                                        Email
                                    </th>
                                    <th scope="col" class="px-4 py-3">
                                        Telefone
                                    </th>
                                    <th scope="col" class="px-6 py-3 flex justify-center gap-2 cursor-pointer ">

                                        <a @click="filtro('assunto')">Assunto</a>
                                        <svg v-if="true" xmlns="http://www.w3.org/2000/svg" width="10" height="10"
                                            viewBox="0 0 24 24"
                                            style="fill: rgba(255, 255, 255, 1);transform: ;msFilter:;">
                                            <path
                                                d="M3 19h18a1.002 1.002 0 0 0 .823-1.569l-9-13c-.373-.539-1.271-.539-1.645 0l-9 13A.999.999 0 0 0 3 19z">
                                            </path>
                                        </svg>
                                        <svg v-else xmlns="http://www.w3.org/2000/svg" width="10" height="10"
                                            viewBox="0 0 24 24"
                                            style="fill: rgba(255, 255, 255, 1);transform: ;msFilter:;">
                                            <path
                                                d="M11.178 19.569a.998.998 0 0 0 1.644 0l9-13A.999.999 0 0 0 21 5H3a1.002 1.002 0 0 0-.822 1.569l9 13z">
                                            </path>
                                        </svg>

                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        solicitacao
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        data e hora
                                    </th>
                                    <th scope="col" class="px-16">
                                        foto
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Status
                                    </th>
                                    <th scope="col" class="px-4 py-3 ">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>






                                <tr v-for="solicitacao in solicitacoes.data" :key="solicitacao.id"
                                    class="odd:bg-gray-200 border-b border-gray-00 text-gray-700 text-nowrap "
                                    :class="{ 'font-extrabold ': solicitacao.status == 0, 'font-normal ': solicitacao.status == 1 }">

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


                                        {{ solicitacao.local }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ solicitacao.nome }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <a class="cursor-pointer hover:underline"
                                            :href="'mailto:' + solicitacao.email + '?subject=Referente a ' + solicitacao.assunto">{{
                                        solicitacao.email }}</a>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a class="cursor-pointer hover:underline text-green-700" target="&_blank"
                                            :href="'https://wa.me/' + formatarNumero(solicitacao.telefone) + '?text=Referente ao assunto ' + solicitacao.assunto">{{
                                        solicitacao.telefone }}</a>
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ solicitacao.assunto }}
                                    </td>

                                    <td class="text-center py-2">

                                        <a class="hover:underline text-blue-800"
                                            :href="'/solicitacao/' + solicitacao.id">Ver</a>

                                    </td>
                                    <td class="px-4 py-2">
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
                                        <select v-model="solicitacao.status"
                                            @change="atualizarStatus(solicitacao.id, $event.target.value)"
                                            class="appearance-none bg-transparent border-none ">
                                            <option :value="0" :selected="solicitacao.status == 0">Aberto</option>
                                            <option :value="2" :selected="solicitacao.status == 2">Andamento</option>
                                            <option :value="1" :selected="solicitacao.status == 1">Concluído</option>
                                        </select>
                                    </td>



                                    <td class="w-full py-4 mx-auto text-center ">
                                        <div class="flex gap-2 justify-center">


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

                                            <a target="&_Blank" :href="'/exportToPdf/' + solicitacao.id" class="mx-4">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24"
                                                    style="fill: rgba(0, 0, 0, 0.7);transform: ;msFilter:;">
                                                    <path
                                                        d="M19 7h-1V2H6v5H5c-1.654 0-3 1.346-3 3v7c0 1.103.897 2 2 2h2v3h12v-3h2c1.103 0 2-.897 2-2v-7c0-1.654-1.346-3-3-3zM8 4h8v3H8V4zm8 16H8v-4h8v4zm4-3h-2v-3H6v3H4v-7c0-.551.449-1 1-1h14c.552 0 1 .449 1 1v7z">
                                                    </path>
                                                    <path d="M14 10h4v2h-4z"></path>
                                                </svg>
                                            </a>
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

        <div>
            <Modal :show="showModal" @close="showModal">
                <template v-slot>
                    <div class="bg-gray-800 text-white p-2">
                        <h2 class="text-lg font-medium">Finalizar Soliciticação</h2>
                    </div>
                    <div class="p-4">
                        <form @submit.prevent="submit()">
                            <div class="">
                                <InputLabel for="resposta" value="Por onde foi feita a resposta?" />
                                <select id="role"
                                    class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    v-model="form.canal" required>
                                    <option selected value="">Selecione</option>
                                    <option value="Email">Email</option>
                                    <option value="Whatsapp">Whatsapp</option>
                                    <option value="Telefone">Telefone</option>
                                </select>

                            </div>
                            <div class="">
                                <InputLabel for="responsavel" value="Responsavel pelo retorno?" />
                                <select id="responsavel"
                                    class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    v-model="form.responsavel" required>
                                    <option selected value="">Selecione</option>
                                    <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}
                                    </option>

                                </select>

                            </div>
                            <div>
                                <InputLabel for="data" value="Data do Retorno" />
                                <TextInput class="w-full" type="date" v-model="form.data"></TextInput>
                                <InputError class="mt-2" :message="form.errors.descricao" />
                            </div>
                            <div>
                                <InputLabel for="descricao" value="Observação" />
                                <textarea id="descricao" rows="5"
                                    class="mt-1 block w-full border-gray-300 focus:border-blue-700 focus:ring-blue-700 rounded-md shadow-sm"
                                    v-model="form.descricao" required autofocus autocomplete="name"></textarea>
                                <InputError class="mt-2" :message="form.errors.descricao" />
                            </div>

                            <div class="mb-5 mt-2">
                            </div>

                            <div class="mt-6 flex justify-end">
                                <button
                                    class=" mx-5 text-white bg-gray-500 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-500 dark:hover:bg-gray-700 dark:focus:ring-gray-800"
                                    @click="closeModal">Fechar</button>

                                <button type="submit"
                                    class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"
                                    :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Concluir
                                </button>

                            </div>
                        </form>



                    </div>
                </template>
            </Modal>
        </div>
    </AuthenticatedLayout>
</template>
