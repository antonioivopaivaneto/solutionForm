<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import VueQrcode from '@chenfengyuan/vue-qrcode';


defineProps({ condominios: Object })

const form = useForm({
    nome: '',
    bloco: '',
    torre: '',
    unidades:  '',
    andares:  '',
});



// Lembre-se de importar o método 'useRouter' do Vue Router


// Método para redirecionar para a página de solicitação do condomínio específico
const redirectToSolicitacao = (id) => {
    router.push(`/condominio/${id}/solicitacao`);
};

// Método para gerar o QR code para o condomínio específico
const generateQRCode = (id) => {
    return QRCode.toDataURL(`/condominio/${id}/solicitacao`);
};

const Back = () =>{
    window.history.back()
}

const submit = () => {
    form.post('/condominios',{
  preserveScroll: true,
  onSuccess: () => form.reset(),
})
};



const remover = (id) => {
    if (confirm("Deseja remover esta solicitação ? ")) {

        router.delete(route('condominios.destroy', id), { preserveScroll: true })
    }

}


</script>

<template>
    <AuthenticatedLayout>


        <div class="max-w-8xl mx-auto sm:px-7 lg:px-9 mt-9">


            <div class="w-full max-w-md p-4 mx-auto bg-white border border-gray-200 rounded-lg shadow-lg sm:p-8 ">

                <Head title="Register" />

                <form @submit.prevent="submit">

                    <div>
                        <InputLabel for="name" value="Nome do Condominio" />

                        <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.nome" required autofocus
                            autocomplete="name" />

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
                        <InputLabel for="Andares" value="Andares" />

                        <TextInput id="Andares" type="text" class="mt-1 block w-full" v-model="form.andares"
                            autocomplete="username" />

                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="Unidades" value="Unidades" />

                        <TextInput id="Unidades" type="text" class="mt-1 block w-full" v-model="form.unidades" required
                            autocomplete="new-password" />

                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>
                </div>

                    <div class="flex items-center justify-end mt-4">


                        <PrimaryButton  class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Registrar
                        </PrimaryButton>
                    </div>
                </form>
            </div>
            <div class="py-12 text-center">
                <div class="flex mb-3">
                    <a @click="Back"
                        class="px-4 py-2 cursor-pointer  bg-gray-500 hover:bg-gray-600 text-white rounded disabled:opacity-50 flex">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            style="fill: rgba(255, 255, 255, 0.8);transform: ;msFilter:;">
                            <path
                                d="M21 11H6.414l5.293-5.293-1.414-1.414L2.586 12l7.707 7.707 1.414-1.414L6.414 13H21z">
                            </path>
                        </svg>
                    </a>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 uppercase font-bold">Lista de condominios</div>
                    <div class=" overflow-x-auto sm:rounded-lg p-5 ">
                        <table class="w-full rounded text-sm text-left text-gray-800 border-2 dark:border-gray-400 ">
                            <thead class="text-xs  uppercase 0 ">
                                <tr class="bg-gray-500 text-white">
                                    <th scope="col" class="px-48 py-3">
                                        condominio
                                    </th>
                                    <th scope="col" class="px-48 py-3">
                                        Unidades
                                    </th>

                                    <th scope="col" class="px-14 py-3  ">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr v-for="condominio in condominios.data" :key="condominio.id"
                                    class="odd:bg-gray-200   border-b border-gray-00 text-gray-700">

                                    <th scope="row" class="px-6 text-center py-4 font-medium ">
                                        <Link :href="'/condominios/' + condominio.id">  {{ condominio.nome }}</Link>
                                    </th>
                                    <th scope="row" class="px-6 py-4  text-center font-medium ">
                                        {{ condominio.unidades_count }}
                                    </th>


                                    <td class="w-full py-4  text-center ">
                                        <div class="flex gap-2">
                                            <Link :href="'/condominios/' + condominio.id"
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline mx-4 cursor-pointer">Editar</Link>

                                            <a @click="remover(condominio.id)"
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline  cursor-pointer">Remover</a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="mt-4 flex gap-2 items-center">
                            <a :href="condominio.url" v-for="condominio in condominios.data.links"
                                :key="condominio.label"
                                class="px-4 py-2 bg-gray-500 text-white rounded disabled:opacity-50"
                                :class="{ 'bg-gray-500': condominio.active }">
                                <span>{{ processarLabel(condominio.label) }}</span>
                            </a>
                        </div>
                    </div>
                  </div>
            </div>
        </div>



    </AuthenticatedLayout>
</template>
