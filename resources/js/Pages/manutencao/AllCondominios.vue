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

defineProps({ condominios: Object });

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


watch(() => form.qtd_andares, (newVal) =>{
    if (newVal) {
        form.qtd_total = '';
        form.qtd_total_disabled = true;
    } else {
        form.qtd_total_disabled = false;
    }
})

watch(() => form.qtd_total, (newVal) =>{
    if (newVal) {
        form.qtd_andares = '';
        form.qtd_andares_disabled = true;
    } else {
        form.qtd_andares_disabled = false;
    }
})


</script>
<template>
    <AuthenticatedLayout>
        <div class="max-w-8xl mx-auto sm:px-7 lg:px-9 ">
            <div class="py-12 text-center">
                <div class="flex justify-between mb-3">
                    <Link @click="Back" class="px-4 py-2 cursor-pointer bg-gray-500 hover:bg-gray-600 text-white rounded disabled:opacity-50 flex">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(255, 255, 255, 0.8); transform: ; msFilter: ;">
                            <path d="M21 11H6.414l5.293-5.293-1.414-1.414L2.586 12l7.707 7.707 1.414-1.414L6.414 13H21z"></path>
                        </svg>
                    </Link>

                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 uppercase font-bold">Condomínios Disponíveis</div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 p-5">
                        <div v-for="condominio in condominios.data" :key="condominio.id" class="bg-gray-100 border border-gray-300 rounded-lg p-4 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-800">
                                <a class="hover:underline cursor-pointer uppercase text-blue-800" :href="'/solicitacoes-manutencao/' + condominio.id">{{ condominio.nome }}</a>
                            </h3>

                            <p class="text-gray-700 mt-2">
                                 Abertas: {{ condominio.solicitacoes_abertas.length }}
                            </p>
                            <p class="text-gray-700 mt-2">
                                 Concluidas: {{ condominio.solicitacoes_fechada.length}}
                            </p>
                            <p class="text-gray-700 mt-2">
                                 Andamento: {{ condominio.solicitacoes_andamento.length }}
                            </p>

                        </div>
                    </div>
                    <div class="mt-4 flex gap-2 items-center justify-center">
                        <a :href="condominio.url" v-for="condominio in condominios.data.links" :key="condominio.label" class="px-4 py-2 bg-gray-500 text-white rounded disabled:opacity-50" :class="{ 'bg-gray-500': condominio.active }">
                            <span>{{ processarLabel(condominio.label) }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
