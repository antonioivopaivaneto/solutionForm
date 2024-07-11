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

const msgSucesso = ref(false)


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
        onSuccess: () =>{
            msgSucesso.value = true;
            form.reset()
        } ,
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


        <div class="max-w-8xl mx-auto sm:px-7 lg:px-9 mt-9 ">


<div class="flex mb-3 ">
    <a @click="Back"
        class="px-4 py-2 cursor-pointer  bg-gray-500 hover:bg-gray-600 text-white rounded disabled:opacity-50 flex">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
            style="fill: rgba(255, 255, 255, 0.8);transform: ;msFilter:;">
            <path d="M21 11H6.414l5.293-5.293-1.414-1.414L2.586 12l7.707 7.707 1.414-1.414L6.414 13H21z">
            </path>
        </svg>
    </a>
</div>

            <div class="w-full max-w-xl p-4 mx-auto bg-white border border-gray-200 rounded-lg shadow-lg sm:p-8">



                            <div v-if="msgSucesso"
                                class="bg-green-100 border  w-96 border-green-400 text-green-700 px-3 py-2 rounded relative"
                                role="alert">
                                <strong class="font-bold">Sucesso! <Link :href="route('condominios.index')"> ver condominios</Link> </strong>
                                <span class="block sm:inline">
                                </span>
                                <span @click="msgSucesso = false" class="absolute top-0 bottom-0 right-0 px-3 py-2">
                                    <svg class="fill-current h-6 w-6 text-green-500" role="button"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <title>Close</title>
                                        <path
                                            d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                                    </svg>
                                </span>
                            </div>

                <Head title="Register" />
                <form @submit.prevent="submit">
                    <div>
                        <InputLabel for="name" value="Nome do Condominio" />
                        <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.nome" required autofocus  />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>
                    <div>
                        <InputLabel for="cnpj" value="CNPJ" />
                        <TextInput @blur="validarCnpj()" v-mask="'##.###.###/####-##'"  id="cnpj" type="text" class="mt-1 block w-full " v-model="form.cnpj" required
                        :class="{ 'border-gray-100': validadoCnpj === true,   'border-red-500': validadoCnpj === false }"  />
                        <InputError class="mt-2" :message="form.errors.cnpj" />
                    </div>
                    <div>
                        <InputLabel for="endereco" value="Endereço" />
                        <TextInput id="endereco" type="text" class="mt-1 block w-full" v-model="form.endereco" required   />
                        <InputError class="mt-2" :message="form.errors.endereco" />
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="mt-4">
                            <InputLabel for="Torre" value="Torre" />
                            <TextInput id="Torre" type="text" class="mt-1 block w-full" v-model="form.torre"  />
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="Bloco" value="Bloco" />
                            <TextInput id="Bloco" type="text" class="mt-1 block w-full" v-model="form.bloco" />
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="mt-4">
                            <InputLabel for="Torre" value="QTD andares" />
                            <TextInput  :class="{'bg-gray-200 cursor-not-allowed':form.qtd_andares_disabled}" :disabled="form.qtd_andares_disabled" id="Torre" type="text" class="mt-1 block w-full" v-model="form.qtd_andares" />
                            <InputError class="mt-2" :message="form.errors.qtd_andares" />
                        </div>
                    <div class="mt-4">
                        <InputLabel for="unidades_intervalo" value="Numeração Andar" />
                        <TextInput id="numeracao" type="text" class="mt-1 block w-full" v-model="form.unidades" required  />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="mt-4">
                            <InputLabel for="Torre" value="Quantidade Total" />
                            <TextInput :class="{'bg-gray-200 cursor-not-allowed':form.qtd_total_disabled}" :disabled="form.qtd_total_disabled"  id="Torre" type="text" class="mt-1 block w-full" v-model="form.qtd_total"  />
                            <InputError class="mt-2" :message="form.errors.qtd_total" />
                        </div>

                    </div>
                    <div>
                        Exemplo de nomeação: {{ apName(form.torre, form.unidades, form.bloco) }}
                    </div>
                    <div class="flex items-center justify-end mt-4">
                        <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Registrar
                        </PrimaryButton>
                    </div>
                </form>
            </div>

        </div>
    </AuthenticatedLayout>
</template>
