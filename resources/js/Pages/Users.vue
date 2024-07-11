<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref } from 'vue';


const props = defineProps({ users: Object });

const msgSucesso = ref();

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false,
    role: '',
});

const submit = () => {
    router.post(route('novoUser', form), { preserveScroll: true })
    msgSucesso.value = true
    form.reset()



};

const Back = () => {
    window.history.back()
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

        router.delete(route('removeUser', { id: id }), { preserveScroll: true })
        msgSucesso.value = true
    }

}

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
                    <a href="register" class="px-4 py-2 cursor-pointer bg-gray-500 hover:bg-gray-600 text-white rounded disabled:opacity-50 flex">
                        Adicionar Novo
                    </a>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
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

                    <div class="p-6 text-gray-900 uppercase font-bold">Lista de Usuarios ADM</div>
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
                    <div class=" overflow-x-auto sm:rounded-lg p-5 ">
                        <table class="w-full rounded text-sm text-left text-gray-800 border-2 dark:border-gray-400 ">
                            <thead class="text-xs  uppercase 0 ">
                                <tr class="bg-gray-500 text-white">
                                    <th scope="col" class="px-6 py-3">

                                        Nome


                                    </th>


                                    <th scope="col" class="px-6 py-3">
                                        Acesso
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Email
                                    </th>

                                    <th scope="col" class="px-6 py-3">
                                        Cadastrado em
                                    </th>

                                    <th scope="col" class="px-16 py-3 ">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>


                                <tr v-for="user in users.data" :key="user.id"
                                    class="odd:bg-gray-200   border-b border-gray-00 text-gray-700">

                                    <td class="px-6 py-4">
                                        {{ user.name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ user.role }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ user.email }}
                                    </td>

                                    <td class="px-6 py-4">
                                        {{ formatarData(user.created_at) }}
                                    </td>

                                    <td class=" mx-auto ">
                                        <div class="flex ">

                                            <a @click="remover(user.id)"
                                                class="font-medium text-blue-600  dark:text-blue-500 hover:underline  cursor-pointer"><svg
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 0.7);">
                                                    <path
                                                        d="M5 20a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8h2V6h-4V4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v2H3v2h2zM9 4h6v2H9zM8 8h9v12H7V8z">
                                                    </path>
                                                    <path d="M9 10h2v8H9zm4 0h2v8h-2z"></path>
                                                </svg></a>


                        <div>

<multiselect v-model="value2" :searchable="true" :options="unidadesFormatadas" placeholder="Selecione uma opção"
:custom-label="customLabel" @update:modelValue="onSelectUnidade">
</multiselect>


</div>

                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="mt-4 flex gap-2 items-center">
                            <a :href="user.url" v-for="user in users.links" :key="user.label"
                                class="px-4 py-2 bg-gray-500 text-white rounded disabled:opacity-50"
                                :class="{ 'bg-gray-500': user.active }">
                                <span>{{ processarLabel(user.label) }}</span>
                            </a>
                        </div>









                    </div>


                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
