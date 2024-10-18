<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import QrCode from 'qrcode';
import { computed, ref, watch } from 'vue';
import VueQrcode from '@chenfengyuan/vue-qrcode';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { toPng } from 'html-to-image';
import validaCNPJ from '@/Helpers/validaCNPJ.js'
import { Canvg } from 'canvg';
const props = defineProps({ condominio: Object, unidades: Object });

const form = useForm({

    nome: '',
    bloco: '',
    torre: '',
    unidades: '',
    qtd_andares: '',
    qtd_total: '',
    condominio_nome: props.condominio.nome, // Set initial value
    condominio_id: props.condominio.id, // Set initial value
    endereco: props.condominio.endereco, // Set initial value
    cnpj: props.condominio.cnpj, // Set initial value
});
const formcond = useForm({


    condominio_nome: props.condominio.nome, // Set initial value
    condominio_id: props.condominio.id, // Set initial value
    endereco: props.condominio.endereco, // Set initial value
    cnpj: props.condominio.cnpj, // Set initial value
    _method: "put",

});

const formEditUnidade = useForm({
    _method: "put",
    nome: '',
    bloco: '',
    torre: '',
    andar: '',

});

const downloadQRCode = () => {
    const qrcodeElement = document.querySelector('.qrcode');
    toPng(qrcodeElement)
        .then((dataUrl) => {
            const link = document.createElement('a');
            link.href = dataUrl;
            link.download = 'qrcode.png';
            link.click();
        })
        .catch((error) => {
            console.error('Erro ao gerar a imagem:', error);
        });
};

const unidadesSelecionadas = ref([])

const selectAllChecked = ref(false);

const formRemove = useForm({
    unidadesSelecionadas: unidadesSelecionadas.value, // Adicione unidadesSelecionadas como um campo do formulário
});

const btnNameSaveOrEdit = ref('Editar')
const editLine = ref(null)
const msgSucesso = ref(false)

const editar = (unidade) => {
    btnNameSaveOrEdit.value = "Salvar";

    if (editLine.value === unidade.id) {
        editLine.value = null
        btnNameSaveOrEdit.value = "Editar";


        formEditUnidade.post('/unidades/' + unidade.id, {
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


    form.post('/unidades', {
        preserveScroll: true,
        onSuccess: () => {
            msgSucesso.value = true,
                form.reset()

        },


    })
};
const submitCond = (condominio) => {

    formcond.condominio_nome = props.condominio.nome;


    formcond.put('/condominios/' + condominio, {
        preserveScroll: true,
        onSuccess: () => {
            msgSucesso.value = true,
                formcond.reset()

        },


    })
};

const Back = () => {
    window.history.back()
}

const removerTodas = (condominio) => {

    if (!confirm("Deseja remover Todas unidades deste condominio? ")) {
        return
    }

    router.delete(route('unidades.destroyAll', condominio), { preserveScroll: true })
    msgSucesso.value = true

}
const remover = (id) => {

    if (unidadesSelecionadas.value.length > 0) {
        if (confirm("Deseja remover todas as unidades selecionadas? ")) {
            formRemove.unidadesSelecionadas = unidadesSelecionadas.value;
            formRemove.delete(`/unidades/remover-massa/${unidadesSelecionadas.value.join(',')}`, {
                preserveScroll: true
            })
            selectAllChecked.value = false;

        }

    }
    else if (confirm("Deseja remover esta unidade? ")) {
        router.delete(route('unidades.destroy', id), { preserveScroll: true })
    }

    msgSucesso.value = true


}
const selectAllcehckboxes = (event) => {
    const isChecked = event.target.checked;

    props.unidades.data.forEach(cond => {
        const index = unidadesSelecionadas.value.indexOf(cond.id);

        if (isChecked && index === -1) {
            unidadesSelecionadas.value.push(cond.id);
        } else if (!isChecked && index !== -1) {
            unidadesSelecionadas.value.splice(index, 1);
        }
    });
}

const urlQRCode = route('solicitar', props.condominio.id);


const apName = (torre, unidades, bloco) => {
    let exampleName = '';
    if (unidades.includes('-')) {
        let [inicio] = unidades.split('-').map(Number);
        exampleName = `${torre}/UND.${inicio}-${bloco}`;
    } else if (unidades.includes(';')) {
        let [primeiroNumero] = unidades.split(';').map(Number);
        exampleName = `${torre}/UND.${primeiroNumero}-${bloco}`;
    } else {
        let [inicio] = unidades.split('-').map(Number);
        exampleName = `${torre}/UND.${inicio}-${bloco}`;
    }
    return exampleName;
};



const validadoCnpj = ref(true)

const validarCnpj = async () => {
    const resultado = await validaCNPJ(form.cnpj);
    validadoCnpj.value = resultado ? true : false;
};


watch(() => form.qtd_andares, (newVal) => {
    if (newVal) {
        form.qtd_total = '';
        form.qtd_total_disabled = true;
    } else {
        form.qtd_total_disabled = false;
    }
})

watch(() => form.qtd_total, (newVal) => {
    if (newVal) {
        form.qtd_andares = '';
        form.qtd_andares_disabled = true;
    } else {
        form.qtd_andares_disabled = false;
    }
})

// Referência para o elemento que contém o QR Code
const qrcodeElement = ref(null);


const copiarImagem = () => {

    // Obtém a URL do QR Code

    // Tenta copiar a URL para a área de transferência
    navigator.clipboard.writeText(urlQRCode)
        .then(() => {
            console.log('URL do QR Code copiada para a área de transferência:', urlQRCode);
            alert('URL do QR Code copiada para a área de transferência.');
        })
        .catch((error) => {
            console.error('Erro ao copiar URL do QR Code para a área de transferência:', error);
            alert('Erro ao copiar URL do QR Code para a área de transferência.');
        });
};





</script>
<template>
    <AuthenticatedLayout>




        <div class="max-w-8xl mx-auto sm:px-7 lg:px-9 mt-9">

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


            <div class="flex flex-row ">

                <div class=" p-4 mx-auto bg-white border border-gray-200 rounded-lg shadow-lg ">

                    <Head title="Editar condominos" />
                    <h2 class="ml-7">QRCODE EXCLUSIVO</h2>
                    <div class="flex">

                    <figure class="qrcode mx-auto text-center" id="qrcode">
                        <vue-qrcode id="qrcode" :value="urlQRCode" ref="qrcode" tag="svg" :options="{
                    errorCorrectionLevel: 'Q',
                    width: 300,
                }"></vue-qrcode>
                        <img class="qrcode__image" src="./../../img/Camada 2.png" alt="Chen Fengyuan" />
                    </figure>
                </div>

                    <div class="flex">
                        <a  target="&_blank":href="route('imprimir', condominio.id)"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Imprimir
                        </a>
                        <PrimaryButton @click="downloadQRCode" class="ml-1">
                            Download

                        </PrimaryButton>
                        <PrimaryButton @click="copiarImagem" class="ml-1">
                            Copiar Link

                        </PrimaryButton>
                    </div>
                </div>

                <div class="w-full max-w-md p-4 mx-auto bg-white border border-gray-200 rounded-lg shadow-lg sm:p-8 ">
                    <form @submit.prevent="submit">
                        <h2>INSERIR NOVAS UNIDADES </h2>

                        <div class="flex gap-5">
                            <div class="mt-4">
                                <InputLabel for="Torre" value="Torre" />
                                <TextInput id="Torre" type="text" class="mt-1 block w-full" v-model="form.torre"
                                    autocomplete="new-password" />
                                <InputError class="mt-2" :message="form.errors.password" />
                            </div>
                            <div class="mt-4">
                                <InputLabel for="Bloco" value="Bloco" />
                                <TextInput id="Bloco" type="text" class="mt-1 block w-full" v-model="form.bloco"
                                    autocomplete="username" />
                                <InputError class="mt-2" :message="form.errors.email" />
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="mt-4">
                                <InputLabel for="Torre" value="QTD andares" />
                                <TextInput :class="{ 'bg-gray-200 cursor-not-allowed': form.qtd_andares_disabled }"
                                    :disabled="form.qtd_andares_disabled" id="Torre" type="text"
                                    class="mt-1 block w-full" v-model="form.qtd_andares" autocomplete="new-password" />
                                <InputError class="mt-2" :message="form.errors.qtd_andares" />
                            </div>
                            <div class="mt-4">
                                <InputLabel for="unidades_intervalo" value="Numeração Andar" />
                                <TextInput id="numeracao" type="text" class="mt-1 block w-full" v-model="form.unidades"
                                    required autocomplete="new-password" />
                                <InputError class="mt-2" :message="form.errors.password" />
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="mt-4">
                                <InputLabel for="qtd_total" value="Quantidade Total" />
                                <TextInput :class="{ 'bg-gray-200 cursor-not-allowed': form.qtd_total_disabled }"
                                    :disabled="form.qtd_total_disabled" id="Torre" type="text" class="mt-1 block w-full"
                                    v-model="form.qtd_total" autocomplete="new-password" />
                                <InputError class="mt-2" :message="form.errors.qtd_total" />
                            </div>

                        </div>
                        <div>
                            Exemplo de nomeação: {{ apName(form.torre, form.unidades, form.bloco) }}
                        </div>
                        <div class="flex items-center justify-end mt-4">


                            <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing">
                                Registrar
                            </PrimaryButton>
                        </div>
                    </form>
                </div>


                <div class="w-full max-w-md p-4 mx-auto bg-white border border-gray-200 rounded-lg shadow-lg sm:p-8 ">
                    <form @submit.prevent="submitCond(condominio.id)">
                        <h2>EDITAR CONDOMINIO</h2>
                        <div>
                            <InputLabel for="name" value="Nome do Condominio" />

                            <TextInput id="name" v-model="condominio.nome" type="text" class="mt-1 block w-full"
                                required autofocus autocomplete="name" />

                            <InputError class="mt-2" :message="formcond.errors.name" />
                        </div>
                        <div>
                            <InputLabel for="cnpj" value="CNPJ" />
                            <TextInput @blur="validarCnpj()" v-mask="'##.###.###/####-##'" id="cnpj" type="text"
                                class="mt-1 block w-full " v-model="form.cnpj" required autocomplete="cnpj"
                                :class="{ 'border-gray-100': validadoCnpj === true, 'border-red-500': validadoCnpj === false }" />
                            <InputError class="mt-2" :message="form.errors.cnpj" />
                        </div>
                        <div>
                            <InputLabel for="endereco" value="Endereço" />
                            <TextInput id="endereco" type="text" class="mt-1 block w-full" v-model="formcond.endereco"
                                required autofocus autocomplete="endereco" />
                            <InputError class="mt-2" :message="formcond.errors.endereco" />
                        </div>




                        <div class="flex items-center justify-end mt-4">


                            <PrimaryButton class="ml-4" :class="{ 'opacity-25': formcond.processing }"
                                :disabled="formcond.processing">
                                Registrar
                            </PrimaryButton>
                        </div>
                    </form>
                </div>


            </div>




            <div class="py-9">


                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 uppercase font-bold">Lista de unidades - Cond.{{ condominio.nome }}
                    </div>

                    <div class="flex justify-between  mx-5">
                        <div class="">
                            <SecondaryButton @click="removerTodas(condominio.id)" class="">remover todas unidades
                            </SecondaryButton>
                        </div>



                        <div class="mx-5">
                            <div v-if="msgSucesso"
                                class="bg-green-100 border  w-96 border-green-400 text-green-700 px-3 py-2 rounded relative"
                                role="alert">
                                <strong class="font-bold">Sucesso! </strong>
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
                        </div>
                    </div>


                    <div class="relative overflow-x-auto sm:rounded-lg p-5 ">
                        <table class="w-full rounded text-sm text-center text-gray-800 border-2 dark:border-gray-400 ">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b-2 border-gray-500">
                                <tr class="bg-gray-500 text-white">
                                    <th scope="col" class=" py-3">
                                        <input v-model="selectAllChecked" type="checkbox" @change="selectAllcehckboxes"
                                            class="form-checkbox text-blue-700 h-5 w-5">
                                    </th>
                                    <th scope="col" class="px-32  py-3">
                                        Unidades
                                    </th>
                                    <th scope="col" class="px-14  py-3">
                                        torre
                                    </th>
                                    <th scope="col" class="px-14  py-3">
                                        bloco
                                    </th>
                                    <th scope="col" class="px-14   py-3">
                                        andar
                                    </th>
                                    <th scope="col" class="px-3   py-3">
                                        Solicitações
                                    </th>

                                    <th scope="col" class="px-5  py-3 ">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>


                                <tr v-for="cond in  unidades.data" :key="cond.id"
                                    class="odd:bg-gray-200   border-b border-gray-00 text-gray-700">

                                    <td class="px-6 py-4">
                                        <!-- Checkbox para selecionar a unidade -->
                                        <input type="checkbox" v-model="unidadesSelecionadas" :value="cond.id"
                                            class="form-checkbox text-blue-700  h-5 w-5">
                                    </td>

                                    <th scope="row" class="px-6 py-4 font-medium ">

                                        <input
                                            class="bg-gray-50 text-center border border-gray-300 text-gray-900 text-sm rounded-lg  block w-full p-2.5 dark:bg-white dark:border-gray-200 dark:placeholder-gray-400 dark:text-gray-700 dark:focus:ring-gray-400 dark:focus:border-gray-400"
                                            v-if="editLine === cond.id" type="text" v-model="formEditUnidade.nome">
                                        <template v-else>
                                            <Link class="text-blue-500 " :href="'/unidades/' + cond.id">

                                            {{ cond.nome }}
                                            </Link>
                                        </template>
                                    </th>
                                    <th scope="row" class="px-6 py-4 font-medium ">
                                        <input v-if="editLine === cond.id" type="text" v-model="formEditUnidade.torre"
                                            class=" text-center bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg  block w-full p-2.5 dark:bg-white dark:border-gray-200 dark:placeholder-gray-400 dark:text-gray-700 dark:focus:ring-gray-400 dark:focus:border-gray-400">
                                        <template v-else>
                                            {{ cond.torre }}
                                        </template>

                                    </th>
                                    <th scope="row" class="px-6 py-4 font-medium ">

                                        <input v-if="editLine === cond.id" type="text" v-model="formEditUnidade.bloco"
                                            class=" text-center bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg  block w-full p-2.5 dark:bg-white dark:border-gray-200 dark:placeholder-gray-400 dark:text-gray-700 dark:focus:ring-gray-400 dark:focus:border-gray-400">
                                        <template v-else>
                                            {{ cond.bloco }}
                                        </template>

                                    </th>
                                    <th scope="row" class="px-6 py-4 font-medium ">
                                        <input v-if="editLine === cond.id" type="text" v-model="formEditUnidade.andar"
                                            class="text-center bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg  block w-full p-2.5 dark:bg-white dark:border-gray-200 dark:placeholder-gray-400 dark:text-gray-700 dark:focus:ring-gray-400 dark:focus:border-gray-400">
                                        <template v-else>
                                            {{ cond.andar }}
                                        </template>

                                    </th>
                                    <th scope="row" class="px-3 py-4 font-medium cursor-pointer ">

                                        <Link class="text-blue-500 " :href="'/unidades/' + cond.id">
                                        {{ cond.solicitacoes.length }}
                                        </Link>
                                    </th>
                                    <td class=" py-4 ">
                                        <div class="flex gap-2">
                                            <a @click="editar(cond)"
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline mx-4 cursor-pointer">{{
                    btnNameSaveOrEdit }}</a>

                                            <a @click="remover(cond.id)"
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline  cursor-pointer">Remover</a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>


                        <div class="mt-4 flex gap-2 items-center">
                            <a :href="uni.url" v-for="uni in unidades.links" :key="uni.label"
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

<style scoped>
.qrcode {
    display: inline-block;
    font-size: 0;
    margin-bottom: 0;
    position: relative;
}

.qrcode__image {
    background-color: #fff;
    border: 0.25rem solid #fff;
    border-radius: 0.25rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.25);
    height: 15%;
    left: 50%;
    overflow: hidden;
    position: absolute;
    top: 50%;
    transform: translate(-50%, -50%);
    width: 15%;
}

qrcode-container {
    text-align: center;
}

.download-button {
    margin-top: 20px;
    padding: 10px 20px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.download-button:hover {
    background-color: #45a049;
}
</style>
