<template>
    <div
        class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-blue-200 dark:bg-dots-lighter dark:bg-blue-200 selection:bg-red-500 selection:text-white">
        <div class="p-5 pt-10">
            <div class="" v-if="!stepTree">
                <h1 class="text-4xl text-gray-900 text-blue-900">
                    <b class="text-5xl">Faça</b> <br />Sua Solicitação
                </h1>

                <span>{{ Npasso }}º Passo</span>

            </div>

            <form class="max-w-sm mx-auto mt-6 fade">


                <img :src="header" v-if="!stepTree" class="transition w-80 mx-auto mt-6" alt="">

                <transition>

                    <div class="" v-if="stepOne">


                        <div class="mb-5 mt-2">
                            <vue3-simple-typeahead :class="{ 'border-red-600': validacao }" v-model="form.assunto"
                                class="bg-gray-50 border text-gray-700 border-gray-300 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                id="assunto" placeholder="Escolha o assunto..." :items="assuntos" :minInputLength="1"
                                @keyup="selectionAssunto()"></vue3-simple-typeahead>
                        </div>
                        <div class="mb-5">
                            <textarea :class="{ 'border-red-600': validacao }" @mouseenter="selectionAssunto()"
                                v-model="form.solicitacao" rows="6" class="bg-gray-50  border-gray-300 text-gray-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full
        dark:focus:ring-blue-500 dark:focus:border-blue-500" required placeholder="Sua Solicitação"></textarea>
                        </div>

                        <div class="mb-5">
                            <input @change="uploadFile"
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none p-2 dark:placeholder-gray-400"
                                id="file_input" type="file">

                        </div>

                    </div>
                </transition>


                <div class="" v-if="stepTwo">


                    <div class="mb-5 mt-2 flex gap-2">
                        <vue3-simple-typeahead input="form.condominio" :class="{ 'border-red-600': validacao }"
                            class="bg-gray-50 border text-gray-700  border-gray-300 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            id="condominio" placeholder="Informe seu condominio..." :items="[
                                'ACACIAS',
                                'ALAMEDA COTEGIPE',
                                'ALTO DE ITAQUERA',
                                'AP MOEMA CARINAS',
                                'ATTIMO TATUAPÉ',
                                'AVIS RARA',
                                'BEATRIZ',
                                'DOMANI',
                                'ESPLANADA',
                                'GUARACIABA',
                                'HAUS MITRE BROOKLIN',
                                'ILHA DE CRETA',
                                'NEW YORK CLUB',
                                'PANORAMA',
                                'PLATINA PATRIANI',
                                'TAMBORE 6',
                                'THE HILL',
                                'THE TIFFANY\'S MORUMBI',
                                'VERTIZ CLUB HOME',
                                'WISH MOEMA',
                                'YASMIN',
                                'ACQUA PARK',
                                'UPPER VILLE',
                                'ALTO DA MATA',
                                'MAXMITRE',
                                'ANDALUZ',
                                'ESPAÇO A RESIDENCE',
                                'ESTAÇÃO 235',
                                'BRISE VERGUEIRO',
                                'ABSOLUTO ECOVIDA',
                                'CAMINHOS DO MAR',
                                'ESTAÇÃO 348',
                                'PRIME LIFE',
                                'PATIO LUSITANIA'
                            ]
                                " :minInputLength="1" @selectItem="alert('teste')" @keyup="selectionCondominio()">
                        </vue3-simple-typeahead>

                        <input @mouseenter="selectionCondominio()" v-model="form.unidade" type="text"
                            placeholder="Sua Unidade" :class="{ 'border-red-600': validacao }"
                            class="bg-gray-50 border text-gray-700  border-gray-300 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </div>
                    <div class="mb-5 ">
                        <input type="text" v-model="form.nome" :class="{ 'border-red-600': validacao }"
                            placeholder="Seu Nome "
                            class="bg-gray-50 border text-gray-700  border-gray-300 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500">

                    </div>
                    <div class="mb-5">
                        <input v-model="form.email" :class="{ 'border-red-600': validacaoEmail }" type="email"
                            placeholder="Seu Email " required
                            class="bg-gray-50 border text-gray-700  border-gray-300 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500">

                            <span class="text-red-500 mx-auto text-center text-xs">{{ error }}</span>
                    </div>

                </div>

                <div class="" v-if="stepTree">

                    <h1 class="text-2xl text-gray-900 text-blue-900 mb-2 ">
                        <b class="text-5xl ">Obrigado por Solicitar!</b>
                    </h1>

                    <p class="text-gray-700 font-thin  mb-6">
                        Sua solicitação sera enviada diretamente para a sindicancia. Gestão Eficiênte e Participativa
                    </p>
                </div>

                <div class="flex flex-row-reverse gap-10">
                    <button type="button" v-if="finalizar" @click="ShowStep3()"
                        class="text-white mb-6  bg-green-900 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                        finalizar
                    </button>
                    <button :class="{ 'cursor-not-allowed': !validacao }" type="button" v-if="stepOne" @click="ShowStep2()"
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

const isEmailValid = (email) =>{

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
]
const error = ref()
const validacaoEmail = ref(false);
const validacao = ref(false);
const finalizar = ref(false)
const stepTwo = ref(false)
const stepTree = ref(false)
const stepOne = ref(true)
const Npasso = ref(1)

const form = reactive({
    assunto: null,
    solicitacao: null,
    foto: null,
    condominio: document.getElementById('condominio'),
    unidade: null,
    nome: null,
    email: null,
})


const uploadFile = (event) => {
    form.foto = event.target.files[0];
}

const selectionAssunto = () => {

    form.assunto = document.getElementById('assunto').value

}
const selectionCondominio = () => {

    form.condominio = document.getElementById('condominio').value

}



const ShowStep2 = () => {

    if (!form.assunto || !form.solicitacao) {
        validacao.value = true
        return
    }
    validacao.value = false

    stepTree.value = false
    stepOne.value = false
    stepTwo.value = true
    Npasso.value = 2
    finalizar.value = true
}
const ShowStep1 = () => {

    stepOne.value = true
    stepTwo.value = false
    stepTree.value = false
    Npasso.value = 1
    finalizar.value = false

}

const ShowStep3 = () => {

    if (!form.assunto || !form.solicitacao || !form.condominio || !form.unidade || !form.nome ) {
        validacao.value = true
        return
    }

    if(!isEmailValid(form.email)){
        validacaoEmail.value = true

        error.value = "email Invalido"
        return

    }
    validacaoEmail.value = false
    validacao.value = false
    error.value = ""

    router.post('/solicitacao', form)

    form.assunto = ''
    form.solicitacao = ''
    form.nome = ''
    form.condominio = ''
    form.unidade = ''
    form.email = ''


    stepOne.value = false
    stepTwo.value = false
    Npasso.value = 3
    finalizar.value = false
    stepTree.value = true




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
</style>
