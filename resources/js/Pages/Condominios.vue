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
            <div class="py-2 text-center">
                <div class="flex justify-between mb-3">
                    <Link @click="Back" class="px-4 py-2 cursor-pointer bg-gray-500 hover:bg-gray-600 text-white rounded disabled:opacity-50 flex">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(255, 255, 255, 0.8); transform: ; msFilter: ;">
                            <path d="M21 11H6.414l5.293-5.293-1.414-1.414L2.586 12l7.707 7.707 1.414-1.414L6.414 13H21z"></path>
                        </svg>
                    </Link>
                    <a href="condominios/create" class="px-4 py-2 cursor-pointer bg-gray-500 hover:bg-gray-600 text-white rounded disabled:opacity-50 flex">
                        Adicionar Novo
                    </a>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 uppercase font-bold">Lista de condomínios</div>
                    <div class="overflow-x-auto sm:rounded-lg p-5">
                        <table class="w-full rounded text-sm text-left text-gray-800 border-2 dark:border-gray-400">
                            <thead class="text-xs uppercase">
                                <tr class="bg-gray-500 text-white">
                                    <th scope="col" class="px-48 py-3">Condomínio</th>
                                    <th scope="col" class="px-48 py-3">Unidades</th>
                                    <th scope="col" class="px-14 py-3">Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="condominio in condominios.data" :key="condominio.id" class="odd:bg-gray-200 border-b border-gray-00 text-gray-700 text-md uppercase">
                                    <th scope="row" class="px-6 text-center py-4 font-medium">
                                        <a class="hover:underline cursor-pointer text-blue-800" :href="'/condominios/' + condominio.id">{{ condominio.nome }}</a>
                                    </th>
                                    <th scope="row" class="px-6 py-4 text-center font-medium">
                                        {{ condominio.unidades.length }}
                                    </th>
                                    <td class="w-full py-4 text-center">
                                        <div class="flex gap-2">
                                            <Link :href="'/condominios/' + condominio.id" class="font-medium text-blue-600 dark:text-blue-500 hover:underline mx-4 cursor-pointer">Editar</Link>
                                            <a @click="remover(condominio.id)" class="font-medium text-blue-600 dark:text-blue-500 hover:underline cursor-pointer">Remover</a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="mt-4 flex gap-2 items-center">
                            <a :href="condominio.url" v-for="condominio in condominios.data.links" :key="condominio.label" class="px-4 py-2 bg-gray-500 text-white rounded disabled:opacity-50" :class="{ 'bg-gray-500': condominio.active }">
                                <span>{{ processarLabel(condominio.label) }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
