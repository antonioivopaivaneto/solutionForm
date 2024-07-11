<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref } from 'vue';

const props = defineProps({ solicitacao: {} });

const msgSucesso = ref();
const selectedFiles = ref([]);
const images = ref([]);
const formData = new FormData();

const form = useForm({
    descricao: '',
    solicitacao_id:'',
});

const submit = () => {
    // Append form fields to formData
    formData.append('descricao', form.descricao);
    formData.append('solicitacao_id', props.solicitacao.id);

    // Append selected files to formData
    selectedFiles.value.forEach((file, index) => {
        formData.append(`foto[${index}]`, file);
    });

    router.post(route('resposta.store'), formData, {
        onSuccess: () => {
            msgSucesso.value = true;
            form.reset();
            images.value = [];
            selectedFiles.value = [];
        }
    });
};

const Back = () => {
    window.history.back();
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

const remover = (id) => {
    if (confirm("Deseja remover esta solicitação ? ")) {
        router.delete(route('removeUser', { id }), { preserveScroll: true });
        msgSucesso.value = true;
    }
}

const removeImage = (index) => {
    if (window.confirm("Deseja realmente remover esta imagem? ")) {
        images.value.splice(index, 1);
        formData.delete(`foto[${index}]`);
        selectedFiles.value.splice(index, 1);
    }
}

const onSelectFile = (event) => {
    const files = event.target.files;
    files.forEach(file => {
        const imgUrl = URL.createObjectURL(file);
        images.value.push(imgUrl);
        selectedFiles.value.push(file);
    });
};

const chooseImagem = () => {
    document.getElementById('foto').click();
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



</script>

<template>
    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-9 p-3">
            <div class="w-full max-w-md p-4 mx-auto bg-white border border-gray-200 rounded-lg shadow-lg sm:p-8">
                <Head title="Resposta" />
                <div class="font-bold text-gray-800 mb-5">
                    Solicitacao ID : {{ solicitacao.id }}
                </div>

                <div v-if="msgSucesso" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                    <p class="font-bold">Sucesso!</p>
                    <p>A resposta foi registrada com sucesso.</p>
                </div>


                <form @submit.prevent="submit" v-if="!msgSucesso">
                    <div>
                        <InputLabel for="descricao" value="O que Foi feito?" />
                        <textarea id="descricao" rows="8"
                                  class="mt-1 block w-full border-gray-300 focus:border-blue-700 focus:ring-blue-700 rounded-md shadow-sm"
                                  v-model="form.descricao" required autofocus autocomplete="name"></textarea>
                        <InputError class="mt-2" :message="form.errors.descricao" />
                    </div>

                    <div class="mb-5 mt-2">

<label class="block text-gray-700 ml-1 text-sm font-bold mb-2" for="username">
    Foto
</label>

<div id="imgResult" :src="camera"
    class="text-xs text-gray-700 text-center flex justify-center  p-2 rounded w-full h-30  bg-blue-800/10">
    <span class="p-7 cursor-pointer ">
        <svg @click="chooseImagem()" xmlns="http://www.w3.org/2000/svg" width="24"
            height="24" viewBox="0 0 24 24"
            style="fill: rgba(0, 0, 0, 0.7)">
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

                    <div class="flex items-center justify-end mt-4">
                        <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Registrar
                        </PrimaryButton>
                    </div>
                </form>


                <a v-if="msgSucesso" :href="'/condominios-manutencao/' " class="px-4 py-2 cursor-pointer bg-gray-500 hover:bg-gray-600 text-white rounded disabled:opacity-50 flex">
                        Mais Solicitacões
                    </a>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

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
    border-radius: 4px;
    transition: all 0.3s;
    cursor: pointer;
}

.image-container:hover .img-removed {
    display: block;
    opacity: 1;
}

.image-container:hover .remove-text {
    display: block;
    opacity: 1;
}

.img-removed {
    cursor: pointer;
}
</style>
