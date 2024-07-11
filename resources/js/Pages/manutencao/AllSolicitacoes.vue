<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import validaCNPJ from '@/Helpers/validaCNPJ.js'
import VueQrcode from '@chenfengyuan/vue-qrcode';
import { ref, watch } from 'vue';

defineProps({ solicitacoes: Object });

const form = useForm({
    nome: '',
    bloco: '',
    torre: '',
    unidades: '',
    qtd_andares: '',
    qtd_total: '',
    endereco: '',
    cnpj: '',
});

const folderImg = ref('../')


const apName = (torre, unidades, bloco) => {
    let exampleName = '';
    if (unidades.includes('-')) {
        let [inicio] = unidades.split('-').map(Number);
        exampleName = `${torre}/UND.${inicio}-${bloco}`;
    } else if (unidades.includes(';')) {
        let [primeiroNumero] = unidades.split(';').map(Number);
        exampleName = `${torre}/UND.${primeiroNumero}-${bloco}`;
    }else{
        let [inicio] = unidades.split('-').map(Number);
        exampleName = `${torre}/UND.${inicio}-${bloco}`;
    }
    return exampleName;
};

const redirectToSolicitacao = (id) => {
    router.push(`/condominio/${id}/solicitacao`);
};

const generateQRCode = (id) => {
    return QRCode.toDataURL(`/condominio/${id}/solicitacao`);
};

const Back = () => {
    window.history.back();
};

const submit = () => {
    form.post('/condominios', {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};

const remover = (id) => {
    if (confirm("Deseja remover esta solicitação ? ")) {
        router.delete(route('condominios.destroy', id), { preserveScroll: true });
    }
};

const validadoCnpj = ref(true)

const validarCnpj = async () => {
    const resultado = await validaCNPJ(form.cnpj);
    validadoCnpj.value = resultado ? true : false;
};

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




// Função para formatar o número de telefone
function formatarNumero(telefone) {
    // Remove os traços e parênteses do número de telefone
    return telefone.replace(/[-()\s]/g, '');
}

const statusMap = {
    0:'Aberto',
    2:'Andamento',
    1:'Concluido',
}

</script>
<template>
    <AuthenticatedLayout>
        <div class="max-w-8xl mx-auto sm:px-7 lg:px-9 p-2 ">
            <div class="py-12 text-center">
                <div class="flex justify-between mb-3">
                    <Link @click="Back" class="px-4 py-2 cursor-pointer bg-gray-500 hover:bg-gray-600 text-white rounded disabled:opacity-50 flex">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(255, 255, 255, 0.8); transform: ; msFilter: ;">
                            <path d="M21 11H6.414l5.293-5.293-1.414-1.414L2.586 12l7.707 7.707 1.414-1.414L6.414 13H21z"></path>
                        </svg>
                    </Link>

                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 uppercase font-bold">Lista de solicitações</div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 p-5">
                        <div v-for="solicitacao in solicitacoes.data" :key="solicitacao.id" :class="{'font-extrabold': solicitacao.status == 0, 'font-normal': solicitacao.status == 1}" class="bg-gray-100 border border-gray-300 rounded-lg p-4 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-800">
                                <a class="hover:underline cursor-pointer text-blue-800" :href="'/condominios/' + solicitacao.condominio.id">{{ solicitacao.condominio.nome }}</a>
                            </h3>
                            <p class="text-gray-700 mt-2">
                                Unidade: <a class="hover:underline cursor-pointer text-blue-800" :href="'/unidades/' + solicitacao.unidade.id">{{ solicitacao.unidade.nome }}</a>
                            </p>
                            <p class="text-gray-700 mt-2">
                                Local: {{ solicitacao.local }}
                            </p>
                            <p class="text-gray-700 mt-2">
                                Morador: {{ solicitacao.nome }}
                            </p>


                            <p class="text-gray-700 mt-2">
                                Assunto: {{ solicitacao.assunto }}
                            </p>
                            <p class="text-gray-700 mt-2">
                                Data e hora: {{ formatarData(solicitacao.created_at) }}
                            </p>
                            <p class="text-gray-700 mt-2">
                                Status: {{ statusMap[solicitacao.status] }}
                                                        </p>
                            <div class="flex flex-row justify-center p-1 mt-2">
                                <span v-for="fotos in solicitacao.fotos.slice(0, 3)" :key="fotos.id" class="flex-shrink-0 mx-2">
                                    <a class="cursor-pointer" :href="folderImg + fotos.foto " target="_blank">
                                        <img :src="folderImg + fotos.foto"  class="rounded-sm w-16 h-12 " alt="">
                                    </a>
                                </span>
                                <span v-if="solicitacao.fotos.length > 3" class="flex items-center justify-center w-12">
                                    <box-icon name='plus-medical' color="#0072bb" size="sx"></box-icon>
                                </span>
                            </div>
                            <div class=" mt-4 mx-auto text-center">

                                <a :href="'/resposta/' + solicitacao.id" class="px-4 py-2 cursor-pointer bg-gray-500 hover:bg-gray-600 text-white rounded disabled:opacity-50 ">
                        Responder
                    </a>

                            </div>
                        </div>
                    </div>
                    <div class="mt-4 flex gap-2 items-center justify-center p-3">
                        <a :href="solicitacao.url" v-for="solicitacao in solicitacoes.links" :key="solicitacao.label" class="px-4 py-2 bg-gray-500 text-white rounded disabled:opacity-50" :class="{ 'bg-gray-500': solicitacao.active }">
                            <span>{{ processarLabel(solicitacao.label) }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
