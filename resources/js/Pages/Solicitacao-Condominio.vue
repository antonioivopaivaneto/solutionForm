<template>
    <div
        class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-blue-200 dark:bg-dots-lighter dark:bg-blue-200 selection:bg-red-500 selection:text-white">


        <div class="p-5 pt-0">
            <div class="">
                <img :src="logo" class="transition w-36 -ml-7  " alt="">



            </div>


            <div class="" v-if="!stepTree">
                <h1 class="text-4xl text-gray-900 text-blue-900">
                    <b class="text-5xl"> {{ condominio.nome }}</b>

                </h1>
                <h1 class="text-4xl text-gray-900 text-blue-900 mt-4">

                   Faça Sua Solicitação
                </h1>

                <span>{{ Npasso }}º Passo</span>
                <div class="flex ">
                    <a @click="ShowStep1()" v-if="stepTwo" class=" py-2 cursor-pointer  text-gray-800 rounded flex">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            style="fill: rgba(0, 0, 0, 0.6);transform: ;msFilter:;">
                            <path
                                d="M21 11H6.414l5.293-5.293-1.414-1.414L2.586 12l7.707 7.707 1.414-1.414L6.414 13H21z">
                            </path>
                        </svg>
                    </a>
                </div>
            </div>
            <form class="max-w-sm mx-auto fade">
                <img :src="header" v-if="!stepTree" class="transition w-80 mx-auto" alt="">
                <transition>
                    <div class="" v-if="stepOne">
                        <div class="mb-5 mt-2">
                            <label class="block text-gray-700 ml-1 text-sm font-bold mb-2" for="username">
                                Tema*
                            </label>
                            <div>
                                <multiselect v-model="value" :options="assuntos" placeholder="Selecione uma opção"
                                    label="assunto" track-by="assunto" :searchable="true" @update:modelValue="onSelect">
                                </multiselect>
                            </div>
                        </div>
                        <div class="mb-5 ">
                        <label class="block text-gray-700 ml-1 text-sm font-bold mb-2" for="username">
                            Local
                        </label>
                        <input type="text" v-model="form.local" :class="{ 'border-red-600': validacao }"
                            placeholder="Local da Solicitação"
                            class="bg-gray-50 border text-gray-700  border-gray-300 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500">

                    </div>
                        <div class="mb-5">
                            <label class="block text-gray-700 ml-1 text-sm font-bold mb-2" for="username">
                                Solicitação*
                            </label>
                            <textarea :class="{ 'border-red-600': validacao }"
                                v-model="form.solicitacao" rows="6" class="bg-gray-50  border-gray-300 text-gray-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full
        dark:focus:ring-blue-500 dark:focus:border-blue-500" required placeholder="Sua Solicitação"></textarea>
                        </div>


                        <div class="mb-5">

                            <label class="block text-gray-700 ml-1 text-sm font-bold mb-2" for="username">
                                Foto
                            </label>

                            <div id="imgResult" :src="camera"
                                class="text-xs text-gray-700 text-center flex justify-center  p-2 rounded w-full h-30  bg-blue-800/10">
                                <span class="p-7 cursor-pointer ">
                                    <svg @click="chooseImagem()" xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24"
                                        style="fill: rgba(0, 0, 0, 0.7);transform: ;msFilter:;">
                                        <path
                                            d="M12 8c-2.168 0-4 1.832-4 4s1.832 4 4 4 4-1.832 4-4-1.832-4-4-4zm0 6c-1.065 0-2-.935-2-2s.935-2 2-2 2 .935 2 2-.935 2-2 2z">
                                        </path>
                                        <path
                                            d="M20 5h-2.586l-2.707-2.707A.996.996 0 0 0 14 2h-4a.996.996 0 0 0-.707.293L6.586 5H4c-1.103 0-2 .897-2 2v11c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2V7c0-1.103-.897-2-2-2zM4 18V7h3c.266 0 .52-.105.707-.293L10.414 4h3.172l2.707 2.707A.996.996 0 0 0 17 7h3l.002 11H4z">
                                        </path>
                                    </svg>

                                </span>


                                <div v-for="(image, index) in images" :key="index">
                                    <div class="image-container">
                                        <div class="">
                                            <img :src="image" alt="Imagem"
                                                class="mx-2 w-20 h-20 img-removed rounded-sm z-10 " />


                                            <span class="remove-text" @click="removeImage(index)">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="mt-1" width="18"
                                                    height="18" viewBox="0 0 24 24"
                                                    style="fill: rgba(255, 0, 0, .5);transform: ;msFilter:;">
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
                            Seu Nome*
                        </label>
                        <input type="text" v-model="form.nome" :class="{ 'border-red-600': validacao }"
                            placeholder="Seu Nome "
                            class="bg-gray-50 border text-gray-700  border-gray-300 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500">

                    </div>
                    <div class="mb-5 ">
                        <label class="block text-gray-700 ml-1 text-sm font-bold mb-2" for="username">
                            Telefone*
                        </label>
                        <input v-mask="'(##) #####-####'" type="text" v-model="form.telefone"
                            :class="{ 'border-red-600': validacao }" placeholder="Seu Telefone"
                            class="bg-gray-50 border text-gray-700  border-gray-300 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500">

                    </div>
                    <div class="mb-5">
                        <label class="block text-gray-700 ml-1 text-sm font-bold mb-2" for="username">
                            Seu Email*
                        </label>
                        <input v-model="form.email" :class="{ 'border-red-600': validacaoEmail }" type="email"
                            placeholder="Seu Email " required
                            class="bg-gray-50 border text-gray-700  border-gray-300 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500">

                        <span class="text-red-500 mx-auto text-center text-xs">{{ error }}</span>
                    </div>


                    <div class="mb-5 mt-2">
                        <label class="block text-gray-700 ml-1 text-sm font-bold mb-2" for="username">
                            Unidade*
                        </label>



                        <div>

                            <multiselect v-model="value2" :searchable="true" :options="unidadesFormatadas" placeholder="Selecione uma opção"
    :custom-label="customLabel" @update:modelValue="onSelectUnidade">
</multiselect>


                        </div>




                    </div>
                    <label class="block text-gray-700 ml-1 text-sm font-bold mb-2" for="username">
                        Responsavel*
                    </label>
                    <div class="mb-5 mt-2 flex gap-20 ">

                        <div class="flex justify-between items-center gap-2 ">
                            <input name="proprietario" type="radio" v-model="form.proprietario" value="proprietario"
                                :class="{ 'border-red-600': validacao }"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-200 focus:ring-2 dark:bg-gray-200 dark:border-gray-400"
                                id="condominio" placeholder="Informe seu condominio..." />
                            <label class="text-gray-700 text-sm font-bold" for="condominio">
                                Proprietário
                            </label>
                        </div>
                        <div class="flex items-center gap-2 mx-8">
                            <input name="proprietario" @mouseenter="selectionCondominio()" v-model="form.proprietario"
                                type="radio" :class="{ 'border-red-600': validacao }" value="locatario"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-200 focus:ring-2 dark:bg-gray-200 dark:border-gray-400"
                                id="locatario" placeholder="Sua Unidade/bloco/torre" />
                            <label class="text-gray-700 text-sm font-bold" for="locatario">
                                Locatário
                            </label>
                        </div>
                    </div>

                </div>

                <div class="" v-if="stepTree">

                    <h1 class="text-2xl text-gray-900 text-blue-900 mb-2 mt-9 ">
                        <b class="text-5xl font-black ">Sua solicitação foi recebida!</b>
                    </h1>

                    <p class="text-gray-700 font-thin  mb-6 mt-5 leading-tight">
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
import { computed, reactive, ref } from "vue";
import { router } from "@inertiajs/vue3";
import TheMask from 'vue-the-mask';
import Multiselect from '@vueform/multiselect';
import '@vueform/multiselect/themes/default.css';
import axios from "axios";


const props = defineProps({ condominio: Object });
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

const assuntos = ref([
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
    'Outros',
]);

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
    assunto: '',
    local: '',
    solicitacao: null,
    condominio: document.getElementById('condominio'),
    unidade: '',
    nome: null,
    email: null,
    telefone: null,
    proprietario: null,
});


const onSelect = (value) => {
    form.assunto = value;
};
const onSelectUnidade = (value) => {
    form.unidade = value;
};

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

const resetForm = () => {
    form.assunto = '';
    form.solicitacao = '';
    form.foto = [];
    form.nome = '';
    form.telefone = '';
    form.email = '';
    form.local = '';
    form.unidade = '';
    images.length = 0;
    stepOne.value = true;
    stepTwo.value = false;
    stepTree.value = false;
    validacao.value = false;
    validacaoEmail.value = false;
    error.value = '';
    Npasso.value = 1;
};

const ShowStep1 = () => {
    stepOne.value = true;
    stepTwo.value = false;
    stepTree.value = false;
    Npasso.value = 1;
    finalizar.value = false;
}

const ShowStep3 = () => {

    form.condominio = props.condominio.id


    if (!form.assunto || !form.solicitacao || !form.nome) {
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

    console.log(formData);

    axios.post('/nova-solicitacao', formData)
  .then(response => {
    // Manipule a resposta aqui, se necessário
    console.log(response.data);
    // Reinicie o formulário ou faça outras ações após o envio bem-sucedido
  })
  .catch(error => {
    // Manipule os erros aqui, se necessário
    console.error('Erro ao enviar formulário:', error);
  });

    //router.post('/solicitacao', formData);
    resetForm();


    stepOne.value = false;
    stepTwo.value = false;
    Npasso.value = 3;
    finalizar.value = false;
    stepTree.value = true;

}
const searchQuery = ref('');

const filteredOptions = computed(() => {
    if (searchQuery.value) {
        return assuntos.value.filter(option =>
            option.toLowerCase().includes(searchQuery.value.toLowerCase())
        );
    }
    return assuntos.value;
});

const onSearch = query => {
    searchQuery.value = query;
};


// Converter o array de unidades para um formato adequado para o multiselect
const unidadesFormatadas = unidades.map(unidade => ({
  value: unidade.id, // Usar o ID como valor
  label: unidade.nome // Usar o nome como texto exibido
}));

const customLabel = (option) => {
  // Normalizar o texto da opção para ignorar caracteres especiais
  const normalizedLabel = option.label.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
  return normalizedLabel;
};


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

.img-removed {}
</style>
