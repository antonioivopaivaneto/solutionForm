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


const props = defineProps({ condominio: Object, unidades: Object });

const form = useForm({
    bloco: '',
    torre: '',
    unidades: '',
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

const urlQRCode = route('solicitar',props.condominio.id);

</script>
<template>
    <AuthenticatedLayout>




        <div class="max-w-8xl mx-auto sm:px-7 lg:px-9 mt-9">
            <div class="flex flex-row ">

                <div class=" max-w-md p-4 mx-auto bg-white border border-gray-200 rounded-lg shadow-lg sm:p-8 ">

                    <Head title="Register" />
                    <h2>QRCODE EXCLUSIVO</h2>



                    <vue-qrcode :value="urlQRCode" :options="{ width: 200 }"></vue-qrcode>

                </div>

                <div class="w-full max-w-md p-4 mx-auto bg-white border border-gray-200 rounded-lg shadow-lg sm:p-8 ">

                    <Head title="Register" />


                    <form @submit.prevent="submit">
                        <h2>INSERIR NOVAS UNIDADES </h2>
                        <div>
                            <InputLabel for="name" value="Nome do Condominio" />

                            <TextInput id="name" v-model="condominio.nome" type="text" class="mt-1 block w-full" required
                                autofocus autocomplete="name" />

                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>

                        <div class=" flex gap-5">

                            <div class="mt-4">
                                <InputLabel for="Bloco" value="Bloco" />

                                <TextInput id="Bloco" type="text" class="mt-1 block w-full" v-model="form.bloco"
                                    autocomplete="username" />

                                <InputError class="mt-2" :message="form.errors.email" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="Torre" value="Torre" />

                                <TextInput id="Torre" type="text" class="mt-1 block w-full" v-model="form.torre"
                                    autocomplete="new-password" />

                                <InputError class="mt-2" :message="form.errors.password" />
                            </div>
                        </div>

                        <div class=" flex gap-5">

                            <div class="mt-4">
                                <InputLabel for="andar" value="andar" />

                                <TextInput id="andar" type="text" class="mt-1 block w-full" v-model="form.andar"
                                    autocomplete="username" />

                                <InputError class="mt-2" :message="form.errors.email" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="Unidades" value="Unidades" />

                                <TextInput id="Unidades" type="text" class="mt-1 block w-full" v-model="form.unidades"
                                     autocomplete="new-password" />

                                <InputError class="mt-2" :message="form.errors.password" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">


                            <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing">
                                Registrar
                            </PrimaryButton>
                        </div>
                    </form>
                </div>

            </div>




            <div class="py-12 text-center">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 uppercase font-bold">Lista de unidades -  Cond.{{ condominio.nome }}</div>


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
                                    <th scope="col" class=" py-3">
                                        <input  v-model="selectAllChecked" type="checkbox" @change="selectAllcehckboxes"
                                            class="form-checkbox text-blue-700 h-5 w-5">
                                    </th>
                                    <th scope="col" class="px-32  py-3">
                                        Unidades
                                    </th>
                                    <th scope="col" class="px-32  py-3">
                                        torre
                                    </th>
                                    <th scope="col" class="px-32  py-3">
                                        bloco
                                    </th>
                                    <th scope="col" class="px-32   py-3">
                                        andar
                                    </th>

                                    <th scope="col" class="px-16  py-3 ">
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
                                            {{ cond.nome }}
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

                                    <td class="w-full py-4 ">
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
