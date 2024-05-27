<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import QrCode from 'qrcode';
import { computed, ref } from 'vue';
import VueQrcode from '@chenfengyuan/vue-qrcode';


const props = defineProps({ condominio: Object, unidade: Object, solicitacoes: Object });

const form = useForm({
    bloco: '',
    torre: '',
    unidade: '',
    andar: '',
    condominio_nome: props.condominio.nome, // Set initial value
    condominio_id: props.condominio.id, // Set initial value
});

const formEditUnidade = useForm({
    _method: "put",
    nome: '',
    bloco: '',
    torre: '',
    andar: '',

});

const unidadeSelecionadas = ref([])
const folderImg = ref('../')
const selectAllChecked = ref(false);

const formRemove = useForm({
    unidadeSelecionadas: unidadeSelecionadas.value, // Adicione unidadeSelecionadas como um campo do formulário
});

const btnNameSaveOrEdit = ref('Editar')
const editLine = ref(null)
const msgSucesso = ref(false)

const editar = (unidade) => {
    btnNameSaveOrEdit.value = "Salvar";

    if (editLine.value === unidade.id) {
        editLine.value = null
        btnNameSaveOrEdit.value = "Editar";


        formEditUnidade.post('/unidade/' + unidade.id, {
            preserveScroll: true,
            onSuccess: () => msgSucesso.value = true,
        })


    } else {
        editLine.value = unidade.id
        btnNameSaveOrEdit.value = "Salvar"


        formEditUnidade.nome = unidade.nome;
        formEditUnidade.bloco = unidade.bloco;
        formEditUnidade.torre = unidade.torre;
        formEditUnidade.andar = unidade.andar;

    }



}

// Método para redirecionar para a página de solicitação do condomínio específico
const redirectToSolicitacao = (id) => {
    router.push(`/condominio/${id}/solicitacao`);
};

// Método para gerar o QR code para o condomínio específico
const generateQRCode = (id) => {
    return QRCode.toDataURL(`/condominio/${id}/solicitacao`);
};

const submit = (unidade) => {

    form.condominio_nome = props.condominio.nome;


    form.post('/unidade', {
        preserveScroll: true,
        onSuccess: () => {
            msgSucesso.value = true,
                form.reset()

        },


    })
};

const showMore = ref(false)

const toggleShow = (solicitacao) => {
    solicitacao.showMore = !solicitacao.showMore;

}


const urlQRCode = route('solicitar',props.condominio.id);


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
const reabrir = (id) => {
    if (confirm("Essa solicitação Sairá histórico, deseja Reabrir ? ")) {

router.get(route('reabrirSolicitacao', id), {preserveScroll: true})
}

}

const Back = () =>{
    window.history.back()
}
</script>
<template>
    <AuthenticatedLayout>




        <div class="max-w-8xl mx-auto sm:px-7 lg:px-9 mt-9">
            <div class="flex ">
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

            <div class="py-12 text-center">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="pt-6 text-gray-900 uppercase font-bold">Condominio: {{ condominio.nome }}</div>
                    <div class="pb-6 text-gray-900 uppercase font-bold">Lista de Solicitações por Unidade: {{ unidade.nome }}</div>


                    <div class="flex flex-row-reverse mx-5">
                        <div v-if="msgSucesso"
                            class="bg-green-100 border mb-5 w-96 border-green-400 text-green-700 px-4 py-3 rounded relative"
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


                    <div class="relative overflow-x-auto sm:rounded-lg p-5 ">
                        <table class="w-full rounded text-sm text-center text-gray-800 border-2 dark:border-gray-400 ">
                            <thead class="text-xs   uppercase 0 ">
                                <tr class="bg-gray-500 text-white">
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


                                <tr v-for="solicitacao in  solicitacoes.data" :key="solicitacao.id"
                                    class="odd:bg-gray-200   border-b border-gray-00 text-gray-700">

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
                                  <img :src="folderImg +fotos.foto" class="rounded-sm mx-0.5 w-8 h-10 mx-2 " alt="">
                                </a>
                              </span>
                              <span v-if="solicitacao.fotos.length > 3" class="w-3 flex items-center justify-center">
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
                                            <a v-if="solicitacao.status == 0" @click="concluir(solicitacao.id)"
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline mx-4 cursor-pointer">

                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24"
                                                    style="fill: rgba(0, 0, 0, 0.7);transform: ;msFilter:;">
                                                    <path
                                                        d="m10 15.586-3.293-3.293-1.414 1.414L10 18.414l9.707-9.707-1.414-1.414z">
                                                    </path>
                                                </svg>
                                            </a>
                                            <a v-if="solicitacao.status == 1" @click="reabrir(solicitacao.id)"
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline mx-4 cursor-pointer">

                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 0.7);transform: ;msFilter:;"><path d="M19.89 10.105a8.696 8.696 0 0 0-.789-1.456l-1.658 1.119a6.606 6.606 0 0 1 .987 2.345 6.659 6.659 0 0 1 0 2.648 6.495 6.495 0 0 1-.384 1.231 6.404 6.404 0 0 1-.603 1.112 6.654 6.654 0 0 1-1.776 1.775 6.606 6.606 0 0 1-2.343.987 6.734 6.734 0 0 1-2.646 0 6.55 6.55 0 0 1-3.317-1.788 6.605 6.605 0 0 1-1.408-2.088 6.613 6.613 0 0 1-.382-1.23 6.627 6.627 0 0 1 .382-3.877A6.551 6.551 0 0 1 7.36 8.797 6.628 6.628 0 0 1 9.446 7.39c.395-.167.81-.296 1.23-.382.107-.022.216-.032.324-.049V10l5-4-5-4v2.938a8.805 8.805 0 0 0-.725.111 8.512 8.512 0 0 0-3.063 1.29A8.566 8.566 0 0 0 4.11 16.77a8.535 8.535 0 0 0 1.835 2.724 8.614 8.614 0 0 0 2.721 1.833 8.55 8.55 0 0 0 5.061.499 8.576 8.576 0 0 0 6.162-5.056c.22-.52.389-1.061.5-1.608a8.643 8.643 0 0 0 0-3.45 8.684 8.684 0 0 0-.499-1.607z"></path></svg>
                                            </a>



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
                            <a :href="uni.url" v-for="uni in solicitacoes.links" :key="uni.label"
                                class="px-4 py-2 bg-gray-500 text-white rounded disabled:opacity-50"
                                :class="{ 'bg-gray-500': uni.active }">
                                <span>{{ uni.label.replaceAll('&amp;laquo;', '').replaceAll('&amp;raquo;', '') }}</span>
                            </a>
                        </div>




                    </div>
                </div>
            </div>
        </div>



    </AuthenticatedLayout>
</template>
