<template>
    <div class="relative h-screen overflow-hidden">
        <!-- Background image -->
        <div class="absolute inset-0 z-0 h-screen bg-cover" >
        </div>

        <!-- Dark overlay -->

        <!-- Centered content -->
        <div class="relative z-20 flex flex-col items-center justify-center h-full text-center text-white">
            <h1 class="text-6xl font-bold mb-4 ">Solution Form Helper</h1>
            <div class="flex space-x-4">
                <a href="login" class="bg-blue-700 hover:bg-blue-900 text-white font-bold py-2 px-4 rounded">
                    Entrar
                </a>
                <a class="bg-green-700 hover:bg-green-900 text-white font-bold py-2 px-4 rounded">
                    Contato
                </a>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { Head, Link } from '@inertiajs/vue3';
    import backgroundImage from "./../../img/Camada 2.png"; // Substitua o caminho pela sua imagem de fundo
</script>

<style>
    .bg-dots-darker {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
    }

    @media (prefers-color-scheme: dark) {
        .dark\:bg-dots-lighter {
            background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
        }
    }
</style>
