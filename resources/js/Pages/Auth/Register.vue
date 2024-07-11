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


        <div class="max-w-8xl mx-auto sm:px-7 lg:px-9 p-5 ">

            <div class="flex my-5 ">
                <a @click="Back"
                    class="px-4 py-2 cursor-pointer  bg-gray-500 hover:bg-gray-600 text-white rounded disabled:opacity-50 flex">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        style="fill: rgba(255, 255, 255, 0.8);transform: ;msFilter:;">
                        <path d="M21 11H6.414l5.293-5.293-1.414-1.414L2.586 12l7.707 7.707 1.414-1.414L6.414 13H21z">
                        </path>
                    </svg>
                </a>
            </div>

            <div class="w-full  mx-auto bg-white border border-gray-200 rounded-lg shadow-lg sm:p-8 ">


                <Head title="Register" />

                <form @submit.prevent="submit">
                    <div class="grid grid-cols-2 gap-5">
                        <div class="">
                            <div>
                        <InputLabel for="name" value="Name" />

                        <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required
                            autofocus autocomplete="name" />

                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div class="">

<InputLabel for="password" value="Senha" />

<TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password"
    required autocomplete="new-password" />

<InputError class="mt-2" :message="form.errors.password" />
</div>



                        </div>
                        <div class="">
                            <div class="">
                        <InputLabel for="email" value="Email" />

                        <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required
                            autocomplete="username" />

                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>




                    <div class="">
                        <InputLabel for="password_confirmation" value="Confirmar Senha" />

                        <TextInput id="password_confirmation" type="password" class="mt-1 block w-full"
                            v-model="form.password_confirmation" required autocomplete="new-password" />

                        <InputError class="mt-2" :message="form.errors.password_confirmation" />
                    </div>

                        </div>
                    </div>

                    <div class="mt-4">
                        <InputLabel for="role" value="Acesso" />

                        <select id="role" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" v-model="form.role" required>
                            <option value="">Selecione</option>
                            <option value="admin">Administrador</option>
                            <option value="manutence">Manutencista</option>
                        </select>

                        <InputError class="mt-2" :message="form.errors.role" />
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

        <div class="py-12">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
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




                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
