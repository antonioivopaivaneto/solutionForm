<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import QrCode from "qrcode";
import { computed, ref } from "vue";
import VueQrcode from "@chenfengyuan/vue-qrcode";
import moment from "moment";
import Modal from "@/Components/Modal.vue";

const solicitacaoSelected = ref();

const formatDate = (dateString) => {
    return moment(dateString).format("D/M/YYYY");
};

const props = defineProps({
    condominio: Object,
    unidade: Object,
    solicitacao: Object,
    users: Array,
});

const form = useForm({
    bloco: "",
    torre: "",
    unidade: "",
    andar: "",
    condominio_nome: props.condominio.nome, // Set initial value
    condominio_id: props.condominio.id, // Set initial value
});

const formEditUnidade = useForm({
    _method: "put",
    nome: "",
    bloco: "",
    torre: "",
    andar: "",
});

const unidadeSelecionadas = ref([]);
const folderImg = ref("../");
const selectAllChecked = ref(false);

const formRemove = useForm({
    unidadeSelecionadas: unidadeSelecionadas.value, // Adicione unidadeSelecionadas como um campo do formulário
});

const btnNameSaveOrEdit = ref("Editar");
const editLine = ref(null);
const msgSucesso = ref(false);
const exibirTodasRespostas = ref(false);

const editar = (unidade) => {
    btnNameSaveOrEdit.value = "Salvar";

    if (editLine.value === unidade.id) {
        editLine.value = null;
        btnNameSaveOrEdit.value = "Editar";

        formEditUnidade.post("/unidade/" + unidade.id, {
            preserveScroll: true,
            onSuccess: () => (msgSucesso.value = true),
        });
    } else {
        editLine.value = unidade.id;
        btnNameSaveOrEdit.value = "Salvar";

        formEditUnidade.nome = unidade.nome;
        formEditUnidade.bloco = unidade.bloco;
        formEditUnidade.torre = unidade.torre;
        formEditUnidade.andar = unidade.andar;
    }
};

// Método para redirecionar para a página de solicitação do condomínio específico
const redirectToSolicitacao = (id) => {
    router.push(`/condominio/${id}/solicitacao`);
};

// Método para gerar o QR code para o condomínio específico
const generateQRCode = (id) => {
    return QRCode.toDataURL(`/condominio/${id}/solicitacao`);
};

const submit = () => {
    form.solicitacao_id = solicitacaoSelected.value;
    router.post("/retorno", form, {
        onFinish: () => {
            msgSucesso.value = true;
            closeModal(); // Chama a função para fechar o modal
            router.reload(); // Recarrega a página
        },
    });
};

const showMore = ref(false);

const toggleShow = (solicitacao) => {
    solicitacao.showMore = !solicitacao.showMore;
};

const urlQRCode = route("solicitar", props.condominio.id);

const formatarData = (data) => {
    const options = {
        day: "numeric",
        month: "numeric",
        year: "numeric",
        hour: "numeric",
        minute: "numeric",
        hour12: false,
    };

    return new Date(data).toLocaleDateString("pt-BR", options);
};

const remover = (id) => {
    if (confirm("Deseja remover esta solicitação ? ")) {
        router.delete(route("solicitacao.destroy", id), {
            preserveScroll: true,
        });
    }
};
const removerResposta = (id) => {
    if (confirm("Deseja remover esta Resposta ? ")) {
        router.delete("/resposta.destroy", id, {
            onFinish: () => {
                msgSucesso.value = true;
            },
        });
    }
};
const removerRetorno = (id) => {
    if (confirm("Deseja remover esta Retorno ? ")) {
        router.delete(route("retorno.destroy", id), { preserveScroll: true });
    }
};
const baixarPlanilha = (id) => {
    router.get(route("downloadExcel", id), { preserveScroll: true });
};
const concluir = (id) => {
    if (
        confirm("Essa solicitação entrara para o histórico, deseja concluir ? ")
    ) {
        router.get(route("concluirSolicitacao", id), { preserveScroll: true });
    }
};
const reabrir = (id) => {
    if (confirm("Essa solicitação Sairá histórico, deseja Reabrir ? ")) {
        router.get(route("reabrirSolicitacao", id), { preserveScroll: true });
    }
};

const Back = () => {
    window.history.back();
};

const atualizarStatus = (solicitacaoId, novoStatus) => {
    solicitacaoSelected.value = solicitacaoId;
    if (novoStatus === "1") {
        openModal();
    } else if (
        confirm(
            "tem certeza de que deseja alterar o status desta solicitação? "
        )
    ) {
        router.put(
            route("atualizarStatus", { id: solicitacaoId }),
            { status: novoStatus },
            { preserveScroll: true }
        );
        msgSucesso.value = true;
    }
};

// Função para formatar o número de telefone
function formatarNumero(telefone) {
    // Remove os traços e parênteses do número de telefone
    return telefone.replace(/[-()\s]/g, "");
}

const showModal = ref(false);

const closeModal = () => {
    showModal.value = false;
    console.log(showModal.value);
};

const openModal = () => {
    showModal.value = true;
    console.log(showModal.value);
};
</script>

<template>
    <AuthenticatedLayout>
        <div class="">
            <modal :show="showModal" @close="showModal">
                <template v-slot>
                    <div class="bg-gray-800 text-white p-2">
                        <h2 class="text-lg font-medium">
                            Finalizar Soliciticação
                        </h2>
                    </div>
                    <div class="p-4">
                        <form @submit.prevent="submit()">
                            <div class="">
                                <InputLabel
                                    for="resposta"
                                    value="Por onde foi feita a resposta?"
                                />
                                <select
                                    id="role"
                                    class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    v-model="form.canal"
                                    required
                                >
                                    <option selected value="">Selecione</option>
                                    <option value="Email">Email</option>
                                    <option value="Whatsapp">Whatsapp</option>
                                    <option value="Telefone">Telefone</option>
                                    <option value="Pessoalmente">
                                        Pessoalmente
                                    </option>
                                </select>
                            </div>
                            <div class="">
                                <InputLabel
                                    for="responsavel"
                                    value="Responsavel pelo retorno?"
                                />
                                <select
                                    id="responsavel"
                                    class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    v-model="form.responsavel"
                                    required
                                >
                                    <option selected value="">Selecione</option>
                                    <option
                                        v-for="user in users"
                                        :key="user.id"
                                        :value="user.id"
                                    >
                                        {{ user.name }}
                                    </option>
                                </select>
                            </div>
                            <div>
                                <InputLabel
                                    for="data"
                                    value="Data do Retorno"
                                />
                                <TextInput
                                    class="w-full"
                                    type="date"
                                    v-model="form.data"
                                ></TextInput>
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.descricao"
                                />
                            </div>
                            <div>
                                <InputLabel
                                    for="descricao"
                                    value="Observação"
                                />
                                <textarea
                                    id="descricao"
                                    rows="5"
                                    class="mt-1 block w-full border-gray-300 focus:border-blue-700 focus:ring-blue-700 rounded-md shadow-sm"
                                    v-model="form.descricao"
                                    required
                                    autofocus
                                    autocomplete="name"
                                ></textarea>
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.descricao"
                                />
                            </div>

                            <div class="mb-5 mt-2"></div>

                            <div class="mt-6 flex justify-end">
                                <button
                                    class="mx-5 text-white bg-gray-500 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-500 dark:hover:bg-gray-700 dark:focus:ring-gray-800"
                                    @click="closeModal"
                                >
                                    Fechar
                                </button>

                                <button
                                    type="submit"
                                    class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"
                                    :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing"
                                >
                                    Concluir
                                </button>
                            </div>
                        </form>
                    </div>
                </template>
            </modal>
        </div>

        <div class="max-w-8xl mx-auto sm:px-7 lg:px-9 mt-9">
            <div class="flex">
                <a
                    @click="Back"
                    class="px-4 py-2 cursor-pointer bg-gray-500 hover:bg-gray-600 text-white rounded disabled:opacity-50 flex"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        style="
                            fill: rgba(255, 255, 255, 0.8);
                            transform: ;
                            msfilter: ;
                        "
                    >
                        <path
                            d="M21 11H6.414l5.293-5.293-1.414-1.414L2.586 12l7.707 7.707 1.414-1.414L6.414 13H21z"
                        ></path>
                    </svg>
                </a>
            </div>

            <div class="py-12">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="flex justify-between">
                        <div class="">
                            <div
                                class="pt-6 text-gray-900 uppercase font-bold ml-10"
                            >
                                Condominio: {{ condominio.nome }}
                            </div>
                            <div
                                class="pb-6 text-gray-900 uppercase font-bold ml-10"
                            >
                                Solicitaçãos Unidade: {{ unidade.nome }}
                            </div>
                        </div>

                        <div class="">
                            <div class="flex gap-10 mt-5 mx-5">
                                <a
                                    :href="'/download-excel/' + solicitacao.id"
                                    class=""
                                >
                                    <svg
                                        width="21"
                                        height="21"
                                        xmlns="http://www.w3.org/2000/svg"
                                        style="
                                            st0 {
                                                fill: #515151;
                                                stroke: #515151;
                                                stroke-width: 0.9276;
                                                stroke-miterlimit: 10;
                                            }
                                        "
                                        xmlns:svg="http://www.w3.org/2000/svg"
                                        enable-background="new 0 0 24 24"
                                        version="1.1"
                                        xml:space="preserve"
                                    >
                                        <g class="layer">
                                            <title>Layer 1</title>
                                            <path
                                                class="st0"
                                                d="m13.7,0.7c0,0 0,0 0,0l-12.5,2.4c-0.2,0 -0.4,0.2 -0.3,0.5l0,16.6c0,0.2 0.1,0.4 0.4,0.4l12.4,2.4c0.1,0 0.3,0 0.4,-0.1c0.1,-0.1 0.2,-0.2 0.2,-0.3l0,-2.2l7.6,0c0.5,0 0.9,-0.4 0.9,-0.9l0,-15.2c0,-0.5 -0.4,-0.9 -0.9,-0.9l-7.6,0l0,-2.2c0,-0.1 -0.1,-0.3 -0.1,-0.3c-0.2,-0.1 -0.4,-0.2 -0.5,-0.2zm-0.4,1l0,1.9c-0.1,0.1 -0.1,0.3 0,0.4l0,15.8c0,0.1 0,0.1 0,0.2l0,2l-11.6,-2.2l0,-15.9l11.6,-2.2zm0.9,2.6l7.6,0l0,15.1l-7.6,0l0,-2.2l1.8,0l0,-0.9l-1.8,0l0,-2.7l1.8,0l0,-0.9l-1.8,0l0,-2.2l1.8,0l0,-0.9l-1.8,0l0,-2.2l1.8,0l0,-0.9l-1.8,0l0,-2.2zm2.7,2.2l0,0.9l3.6,0l0,-0.9l-3.6,0zm-13.1,1.2l2.4,4.2l-2.6,4.1l2.2,0l1.4,-2.7c0.1,-0.3 0.2,-0.5 0.2,-0.6l0,0c0.1,0.3 0.1,0.5 0.2,0.6l1.5,2.7l2.2,0l-2.6,-4.2l2.5,-4.1l-2,0l-1.3,2.5c-0.1,0.3 -0.2,0.6 -0.3,0.7l0,0c-0.1,-0.3 -0.2,-0.5 -0.3,-0.7l-1.2,-2.5l-2.3,0zm13.1,1.9l0,0.9l3.6,0l0,-0.9l-3.6,0zm0,3.1l0,0.9l3.6,0l0,-0.9l-3.6,0zm0,3.6l0,0.9l3.6,0l0,-0.9l-3.6,0z"
                                                id="svg_1"
                                            />
                                        </g>
                                    </svg>
                                </a>

                                <button
                                    target="&_Blank"
                                    onclick="window.print()"
                                    class="mx-4"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="24"
                                        height="24"
                                        viewBox="0 0 24 24"
                                        style="
                                            fill: rgba(0, 0, 0, 0.7);
                                            transform: ;
                                            msfilter: ;
                                        "
                                    >
                                        <path
                                            d="M19 7h-1V2H6v5H5c-1.654 0-3 1.346-3 3v7c0 1.103.897 2 2 2h2v3h12v-3h2c1.103 0 2-.897 2-2v-7c0-1.654-1.346-3-3-3zM8 4h8v3H8V4zm8 16H8v-4h8v4zm4-3h-2v-3H6v3H4v-7c0-.551.449-1 1-1h14c.552 0 1 .449 1 1v7z"
                                        ></path>
                                        <path d="M14 10h4v2h-4z"></path>
                                    </svg>
                                </button>
                                <a
                                    @click="remover(solicitacao.id)"
                                    class="font-medium text-blue-600 dark:text-blue-500 hover:underline cursor-pointer"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="24"
                                        height="24"
                                        viewBox="0 0 24 24"
                                        style="
                                            fill: rgba(0, 0, 0, 0.7);
                                            transform: ;
                                            msfilter: ;
                                        "
                                    >
                                        <path
                                            d="M5 20a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8h2V6h-4V4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v2H3v2h2zM9 4h6v2H9zM8 8h9v12H7V8z"
                                        ></path>
                                        <path
                                            d="M9 10h2v8H9zm4 0h2v8h-2z"
                                        ></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div class="flex flex-row-reverse mx-5 fixed right-5 z-10">
                        <div
                            v-if="msgSucesso"
                            class="bg-green-100 border mb-5 w-96 border-green-400 text-green-700 px-4 py-3 rounded relative"
                            role="alert"
                        >
                            <strong class="font-bold">Sucesso! </strong>
                            <span class="block sm:inline"> </span>
                            <span
                                @click="msgSucesso = false"
                                class="absolute top-0 bottom-0 right-0 px-4 py-3"
                            >
                                <svg
                                    class="fill-current h-6 w-6 text-green-500"
                                    role="button"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20"
                                >
                                    <title>Close</title>
                                    <path
                                        d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"
                                    />
                                </svg>
                            </span>
                        </div>
                    </div>

                    <div class="relative overflow-x-auto sm:rounded-lg p-5">
                        <div class="bg-white p-4 rounded shadow mb-4">
                            <table class="border">
                                <tr class="border">
                                    <td class="font-extrabold p-1">
                                        condominio:
                                    </td>
                                    <td class="border p-1">
                                        {{ condominio.nome }}
                                    </td>
                                    <td class="border w-ful text-center p-1">
                                        {{ solicitacao.assunto }}
                                    </td>
                                </tr>
                                <tr class="border">
                                    <td class="font-extrabold p-1">Nome:</td>
                                    <td class="border p-1">
                                        {{ solicitacao.nome }}
                                    </td>
                                    <td class="border text-center p-1">
                                        Mensagem
                                    </td>
                                </tr>
                                <tr class="border">
                                    <td class="font-extrabold p-1">
                                        Telefone:
                                    </td>
                                    <td class="border p-1">
                                        <a
                                            class="cursor-pointer hover:underline text-green-700"
                                            target="_blank"
                                            :href="
                                                'https://wa.me/' +
                                                formatarNumero(
                                                    solicitacao.telefone
                                                ) +
                                                '?text=Referente ao assunto ' +
                                                solicitacao.assunto
                                            "
                                        >
                                            {{ solicitacao.telefone }}
                                        </a>
                                    </td>
                                    <td
                                        rowspan="4"
                                        style="width: 100%"
                                        class="border w-96 text-center p-1"
                                    >
                                        {{ solicitacao.solicitacao }}
                                    </td>
                                </tr>
                                <tr class="border">
                                    <td class="font-extra p-1">Emails:</td>
                                    <td class="border p-1">
                                        {{ solicitacao.email }}
                                    </td>
                                </tr>
                                <tr class="border">
                                    <td class="font-extra p-1">Unid:</td>
                                    <td class="border p-1">
                                        {{ solicitacao.unidade.nome }}
                                    </td>
                                </tr>
                                <tr class="border">
                                    <td class="font-extra p-1">data e hora:</td>
                                    <td class="border p-1">
                                        {{
                                            formatarData(solicitacao.created_at)
                                        }}
                                    </td>
                                </tr>
                            </table>

                            <div class="mt-1 flex" v-if="solicitacao.fotos">
                                <div class="flex flex-row">
                                    <span
                                        v-for="fotos in solicitacao.fotos.slice(
                                            0,
                                            3
                                        )"
                                        :key="fotos.id"
                                        class="flex-shrink-0 ml-1"
                                    >
                                        <a
                                            class="cursor-pointer"
                                            :href="folderImg + fotos.foto"
                                            target="_blank"
                                        >
                                            <img
                                                :src="folderImg + fotos.foto"
                                                class="rounded-sm w-36 h-36"
                                                alt=""
                                            />
                                        </a>
                                    </span>
                                    <span
                                        v-if="solicitacao.fotos.length > 3"
                                        class="flex items-center justify-center w-12"
                                    >
                                        <box-icon
                                            name="plus-medical"
                                            color="#0072bb"
                                            size="sx"
                                        ></box-icon>
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-end justify-end">
                                <button @click="exibirTodasRespostas = !exibirTodasRespostas" class="">{{ exibirTodasRespostas ? 'Mostrar menos' : 'Exibir todas' }}</button>
                            </div>
                            <div :class="{'max-h-96 overflow-y-auto pr-2':!exibirTodasRespostas,
                                'max-h-full overflow-visible':exibirTodasRespostas
                            }">
                                <div
                                    class="mt-5"
                                    v-for="resposta in solicitacao.resposta"
                                    :key="resposta.id"
                                >
                                    <div
                                        class="bg-gray-100 rounded p-1 border border-gray-700"
                                    >
                                        <div class="flex justify-between">
                                            <div class="font-extrabold">
                                                {{ resposta.user.name }}
                                            </div>

                                            <a
                                                @click="
                                                    removerResposta(resposta.id)
                                                "
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline cursor-pointer"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    width="24"
                                                    height="24"
                                                    viewBox="0 0 24 24"
                                                    style="
                                                        fill: rgba(
                                                            0,
                                                            0,
                                                            0,
                                                            0.7
                                                        );
                                                        transform: ;
                                                        msfilter: ;
                                                    "
                                                >
                                                    <path
                                                        d="M5 20a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8h2V6h-4V4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v2H3v2h2zM9 4h6v2H9zM8 8h9v12H7V8z"
                                                    ></path>
                                                    <path
                                                        d="M9 10h2v8H9zm4 0h2v8h-2z"
                                                    ></path>
                                                </svg>
                                            </a>
                                        </div>

                                        <div class="">
                                            <div class="">Resposta :</div>
                                            <div class="my-2">
                                                {{ resposta.descricao }}
                                            </div>
                                            <div class="flex justify-end">
                                                {{
                                                    formatarData(
                                                        resposta.created_at
                                                    )
                                                }}
                                            </div>
                                            <div class="mt-3">
                                                <strong>Fotos Resposta:</strong>
                                                <div class="flex flex-row">
                                                    <span
                                                        v-for="fotos in resposta.fotos"
                                                        :key="fotos.id"
                                                        class="flex-shrink-0 mr-3"
                                                    >
                                                        <a
                                                            class="cursor-pointer"
                                                            :href="
                                                                folderImg +
                                                                fotos.foto
                                                            "
                                                            target="_blank"
                                                        >
                                                            <img
                                                                :src="
                                                                    folderImg +
                                                                    fotos.foto
                                                                "
                                                                class="rounded-sm w-36 h-36"
                                                                alt=""
                                                            />
                                                        </a>
                                                    </span>
                                                    <span
                                                        v-if="
                                                            solicitacao.fotos
                                                                .length > 3
                                                        "
                                                        class="flex items-center justify-center w-12"
                                                    >
                                                        <box-icon
                                                            name="plus-medical"
                                                            color="#0072bb"
                                                            size="sx"
                                                        ></box-icon>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="mt-5"
                                    v-for="retorno in solicitacao.retorno"
                                    :key="retorno.id"
                                >
                                    <div
                                        class="bg-gray-100 rounded p-1 border border-gray-700"
                                    >
                                        <div class="flex justify-between">
                                            <div class="flex-col">
                                                <div class="">
                                                    Responsavel pelo retorno:
                                                    {{ retorno.user.name }}
                                                </div>
                                                <div class="">
                                                    Canal utilizado:
                                                    {{ retorno.canal }}
                                                </div>
                                                <div class="">
                                                    Data retorno:
                                                    {{
                                                        formatDate(retorno.data)
                                                    }}
                                                </div>
                                                <div class="">
                                                    Observações:
                                                    {{ retorno.descricao }}
                                                </div>
                                            </div>

                                            <a
                                                @click="
                                                    removerRetorno(retorno.id)
                                                "
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline cursor-pointer"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    width="24"
                                                    height="24"
                                                    viewBox="0 0 24 24"
                                                    style="
                                                        fill: rgba(
                                                            0,
                                                            0,
                                                            0,
                                                            0.7
                                                        );
                                                        transform: ;
                                                        msfilter: ;
                                                    "
                                                >
                                                    <path
                                                        d="M5 20a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8h2V6h-4V4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v2H3v2h2zM9 4h6v2H9zM8 8h9v12H7V8z"
                                                    ></path>
                                                    <path
                                                        d="M9 10h2v8H9zm4 0h2v8h-2z"
                                                    ></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <h1
                                        class="font-extrabold text-gray-500 uppercase"
                                    >
                                        Status:
                                    </h1>
                                    <select
                                        v-model="solicitacao.status"
                                        @change="
                                            atualizarStatus(
                                                solicitacao.id,
                                                $event.target.value
                                            )
                                        "
                                        class="border-gray-300 focus:border-blue-700 focus:ring-blue-700 rounded-md shadow-sm"
                                    >
                                        <option
                                            :value="0"
                                            :selected="solicitacao.status == 0"
                                        >
                                            Aberto
                                        </option>
                                        <option
                                            :value="2"
                                            :selected="solicitacao.status == 2"
                                        >
                                            Andamento
                                        </option>
                                        <option
                                            :value="1"
                                            :selected="solicitacao.status == 1"
                                        >
                                            Concluído
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
