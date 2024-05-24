<template>
    <div
        class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-blue-200 dark:bg-dots-lighter dark:bg-blue-200 selection:bg-red-500 selection:text-white">


        <div class="p-5 pt-0">
            <div class="">
                <img :src="logo" class="transition w-36 -ml-7  " alt="">

            </div>


            <div class="" v-if="!stepTree">
                <h1 class="text-4xl text-gray-900 text-blue-900">
                    {{ condominio.nome }}
                    <b class="text-5xl">Faça</b> <br />Sua Solicitação
                </h1>

                <span>{{ Npasso }}º Passo</span>

            </div>

            <form class="max-w-sm mx-auto fade">


                <img :src="header" v-if="!stepTree" class="transition w-80 mx-auto" alt="">

                <transition>

                    <div class="" v-if="stepOne">


                        <div class="mb-5 mt-2">
                            <label class="block text-gray-700 ml-1 text-sm font-bold mb-2" for="username">
                                Tema
                            </label>
                            <vue3-simple-typeahead :class="{ 'border-red-600': validacao }" v-model="form.assunto"
                                class="bg-gray-50 border text-gray-700 border-gray-300 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                id="assunto" placeholder="Escolha o assunto..." :items="assuntos" :minInputLength="1"
                                @keyup="selectionAssunto()"></vue3-simple-typeahead>
                        </div>
                        <div class="mb-5">
                            <label class="block text-gray-700 ml-1 text-sm font-bold mb-2" for="username">
                                Solicitação
                            </label>
                            <textarea  :class="{ 'border-red-600': validacao }" @mouseenter="selectionAssunto()"
                                v-model="form.solicitacao" rows="6" class="bg-gray-50  border-gray-300 text-gray-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full
        dark:focus:ring-blue-500 dark:focus:border-blue-500" required placeholder="Sua Solicitação"></textarea>
                        </div>


                        <div class="mb-5">

                            <label class="block text-gray-700 ml-1 text-sm font-bold mb-2" for="username">
                                Foto
                            </label>

                            <div id="imgResult" :src="camera"
                                class="text-xs text-gray-700 text-center flex justify-center  p-2 rounded w-full h-30 cursor-pointer bg-blue-800/20">
                                <span class="p-3" v-if="images.length === 0" @click="chooseImagem()">Clique aqui para
                                    inserir imagens</span>

                                <div v-for="(image, index) in images" :key="index">
                                    <div class="image-container" @click="removeImage(index)">
                                        <div class="">
                                            <img :src="image" alt="Imagem"
                                                class="mx-2 w-20 h-20 img-removed rounded-sm z-10 " />


                                            <span class="remove-text">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="mt-1" width="18"
                                                    height="18" viewBox="0 0 24 24"
                                                    style="fill: rgba(0, 0, 0, .5);transform: ;msFilter:;">
                                                    <path
                                                        d="M9.172 16.242 12 13.414l2.828 2.828 1.414-1.414L13.414 12l2.828-2.828-1.414-1.414L12 10.586 9.172 7.758 7.758 9.172 10.586 12l-2.828 2.828z">
                                                    </path>
                                                    <path
                                                        d="M12 22c5.514 0 10-4.486 10-10S17.514 2 12 2 2 6.486 2 12s4.486 10 10 10zm0-18c4.411 0 8 3.589 8 8s-3.589 8-8 8-8-3.589-8-8 3.589-8 8-8z">
                                                    </path>
                                                </svg>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <input multiple ref="fileInput" type="file" max="3" id="foto" name="foto[]"
                                @change="handleFileUpload" @input="onSelectFile"
                                class="file-input bg-gray-50 hidden border border-gray-500 text-gray-950 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5"
                                :class="{ 'border-red-500': !form.foto && validacao }" />




                        </div>
                    </div>
                </transition>
                <div class="" v-if="stepTwo">

                    <div class="mb-5 ">
                        <label class="block text-gray-700 ml-1 text-sm font-bold mb-2" for="username">
                            Seu Nome
                        </label>
                        <input type="text"  v-model="form.nome" :class="{ 'border-red-600': validacao }"
                            placeholder="Seu Nome "
                            class="bg-gray-50 border text-gray-700  border-gray-300 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500">

                    </div>
                    <div class="mb-5 ">
                        <label class="block text-gray-700 ml-1 text-sm font-bold mb-2" for="username">
                            Telefone
                        </label>
                        <input v-mask="'(##) #####-####'" type="text" v-model="form.telefone" :class="{ 'border-red-600': validacao }"
                            placeholder="Seu Telefone"
                            class="bg-gray-50 border text-gray-700  border-gray-300 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500">

                    </div>
                    <div class="mb-5">
                        <label class="block text-gray-700 ml-1 text-sm font-bold mb-2" for="username">
                            Seu Email
                        </label>
                        <input v-model="form.email" :class="{ 'border-red-600': validacaoEmail }" type="email"
                            placeholder="Seu Email " required
                            class="bg-gray-50 border text-gray-700  border-gray-300 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500">

                        <span class="text-red-500 mx-auto text-center text-xs">{{ error }}</span>
                    </div>


                    <div class="mb-5 mt-2">
                        <label class="block text-gray-700 ml-1 text-sm font-bold mb-2" for="username">
                            Unidade
                        </label>
                        <select :class="{ 'border-red-600': validacao }" v-model="form.unidade"
    class="bg-gray-50 border text-gray-700 border-gray-300 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500"
    id="unidade" placeholder="Escolha a Unidade...">
    <option disabled value="">Escolha a Unidade...</option>
    <option v-for="unidade in unidades" :key="unidade.id" :value="unidade.id">{{ unidade.nome }}</option>
</select>

                    </div>
                    <div class="mb-5 mt-2 flex gap-20 ">
                        <div class="flex justify-between items-center gap-2 mx-9">
                            <input name="proprietario" type="radio" v-model="form.proprietario"
                            value="proprietario"
                                :class="{ 'border-red-600': validacao }"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-200 focus:ring-2 dark:bg-gray-200 dark:border-gray-400"
                                id="condominio" placeholder="Informe seu condominio..." />
                            <label class="text-gray-700 text-sm font-bold" for="condominio">
                                Proprietário
                            </label>
                        </div>
                        <div class="flex items-center gap-2">
                            <input name="proprietario" @mouseenter="selectionCondominio()" v-model="form.proprietario"
                                type="radio" :class="{ 'border-red-600': validacao }"
                                value="locatario"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-200 focus:ring-2 dark:bg-gray-200 dark:border-gray-400"
                                id="locatario" placeholder="Sua Unidade/bloco/torre" />
                            <label class="text-gray-700 text-sm font-bold" for="locatario">
                                Locatário
                            </label>
                        </div>
                    </div>

                </div>

                <div class="" v-if="stepTree">

                    <h1 class="text-2xl text-gray-900 text-blue-900 mb-2 ">
                        <b class="text-5xl font-black ">Sua solicitação foi recebida!</b>
                    </h1>

                    <p class="text-gray-700 font-thin  mb-6">
                        Em breve entraremos em contato pelo E-mail informado.<br> Gestão Eficiênte e Participativa
                    </p>

                </div>

                <div class="flex flex-row-reverse gap-10">
                    <button type="button" v-if="finalizar" @click="ShowStep3()"
                        class="text-white mb-6  bg-green-900 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                        Finalizar
                    </button>
                    <button :class="{ 'cursor-not-allowed': validacao }" type="button" v-if="stepOne"
                        @click="ShowStep2()"
                        class="text-white mb-6  bg-blue-900 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Proximo
                    </button>
                    <button type="button" v-if="stepTree" @click="ShowStep1()"
                        class="text-white mb-6  bg-blue-900 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Retornar
                    </button>
                </div>
            </form>


        </div>
    </div>
</template>

<script setup>
import logo from "./../../img/logo.png";
import header from "./../../img/atendimento.png";
import gif from "./../../img/success.gif";
import { reactive, ref } from "vue";
import { router } from "@inertiajs/vue3";
import TheMask from 'vue-the-mask';

const props = defineProps({  condominio: Object });
const selectedFiles = ref([]);

const unidades = props.condominio.unidades;
const unidadeNames = ref(unidades.map(unidade => unidade.nome));

function chooseImagem() {
    const input = document.getElementById('foto');
    input.click();
}

const isEmailValid = (email) => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

const assuntos = [
    'Reclamação',
    'Sugestão',
    'Elogio',
    'Hall',
    'Estacionamento',
    'Portaria',
    'Limpeza',
    'Manutenção',
    'Segurança',
    'Áreas Comuns',
    'Reservas',
    'Administrativo',
    'Financeiro',
];

const error = ref();
const validacaoEmail = ref(false);
const validacao = ref(false);
const finalizar = ref(false);
const stepTwo = ref(false);
const stepTree = ref(false);
const stepOne = ref(true);
const Npasso = ref(1);

const formData = new FormData();

const form = reactive({
    assunto: null,
    solicitacao: null,
    condominio: document.getElementById('condominio'),
    unidade: null,
    nome: null,
    email: null,
    telefone: null,
    proprietario: null,
});

const images = ref([]);

const removeImage = (index) => {
    if (window.confirm("Deseja realmente remover esta imagem?")) {
        images.value.splice(index, 1);
        formData.delete('foto[]', selectedFiles.value[index]);
        selectedFiles.value.splice(index, 1);
    }
}

const handleFileUpload = (event) => {
    const selectedFilesArray = Array.from(event.target.files);

    selectedFilesArray.forEach(file => {
        const imgUrl = URL.createObjectURL(file);
        images.value.push(imgUrl);
        selectedFiles.value.push(file);
        formData.append('foto[]', file);
    });
};

const selectionAssunto = () => {
    form.assunto = document.getElementById('assunto').value;
}

const selectionCondominio = () => {
    form.condominio = document.getElementById('condominio').value;
}

const ShowStep2 = () => {
    if (!form.assunto || !form.solicitacao) {
        validacao.value = true;
        return;
    }
    validacao.value = false;
    stepTree.value = false;
    stepOne.value = false;
    stepTwo.value = true;
    Npasso.value = 2;
    finalizar.value = true;
}

const ShowStep1 = () => {
    stepOne.value = true;
    stepTwo.value = false;
    stepTree.value = false;
    Npasso.value = 1;
    finalizar.value = false;
}

const ShowStep3 = () => {

    form.condominio = props.condominio.id


    if (!form.assunto || !form.solicitacao  || !form.nome) {
        validacao.value = true;
        return;
    }

    if (!isEmailValid(form.email)) {
        validacaoEmail.value = true;
        error.value = "email Invalido";
        return;
    }
    validacaoEmail.value = false;
    validacao.value = false;
    error.value = "";

    Object.keys(form).forEach(key => {
        formData.append(key, form[key]);
    });

    router.post('/solicitacao', formData);

    form.assunto = '';
    form.solicitacao = '';
    form.nome = '';
    form.condominio = '';
    form.unidade = '';
    form.email = '';

    stepOne.value = false;
    stepTwo.value = false;
    Npasso.value = 3;
    finalizar.value = false;
    stepTree.value = true;
}
</script>


<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.image-container {
    display: inline-block;
    position: relative;
}

.file-input {
    display: none;
    cursor: pointer;
}

.remove-text {
    position: absolute;
    top: -5px;
    right: 5px;
    color: white;
    padding: 2px 4px;
    border-radius: 4px 4px 4px 4px;
    transition: all 0.5s;
    /* Adicione uma transição de 0.3 segundos para suavizar as mudanças de opacidade */
    cursor: pointer;

}

.image-container:hover .img-removed {
    /* Exiba o ícone "X" ao passar o mouse */
    display: block;
    opacity: 1;
    /* Torna o ícone visível ao passar o mouse */

}

.image-container:hover .remove-text {
    /* Exiba o texto "Remover" ao passar o mouse */
    display: block;
    opacity: 1;
    /* Torna o ícone visível ao passar o mouse */

}

.img-removed {
    cursor: pointer;
}
</style>
