<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitação</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="font-sans antialiased " onload="window.print()">

    <div class="flex   ">
        <div class="">
            <h1 class="text-2xl font-bold uppercase  text-white bg-black p-5">Solicitação -
                {{ $solicitacao->condominio->nome }} </h1>
            <div class="w-full  text-sm text-gray-800  border-r-2 border-l-2 border-b-2 border-black p-4">
                <h2 class="text-gray-600 text-sm font-bold mb-4">HORÁRIO E LOCAL</h2>
                <div class="text-gray-800 text-sm font-semibold border-b-2">{{ $solicitacao->created_at->format('d/m/Y, H:i') }}
                    <h2 class="text-gray-600 text-sm font-bold mb-4 mt-5 uppercase">{{ $solicitacao->assunto }}</h2>
                        <div class="text-gray-800 text-sm font-semibold">{{ $solicitacao->solicitacao }}
                        </div>
                </div>

                <div class="mt-5">
                    <h2 class="text-gray-600 text-sm font-bold  uppercase ">Solicitante</h2>
                    <div class="font-semibold ">{{ $solicitacao->nome }}</div>
                    <div class="font-semibold"> {{ $solicitacao->unidade->nome }}</div>
                    <div class="font-semibold"> {{ $solicitacao->telefone }}</div>
                </div>

            </div>
        </div>
        <div class="w-40 text-center  border-r-2 border-b-2 border-black">
            <div class="text-sm font-bold uppercase  text-white bg-black p-4 ">
                <span>Nº da OS</span>
                <div class="">{{ $solicitacao->id }}</div>
            </div>

            <div class="text-center h-  text-sm text-gray-800 p-4  ">
                <div class="mx-auto mb-5 text-center">
                    <h2 class="text-gray-600 text-xs font-bold mt-4 mb-2">STATUS</h2>
                    <span class="text-gray-800 text-sm font-bold mt-3 ">
                        @if ($solicitacao->status == 0)
                            Aberto
                        @elseif($solicitacao->status == 2)
                            Andamento
                        @elseif($solicitacao->status == 1)
                            Concluído
                        @else
                            Desconhecido
                        @endif
                    </span>
                </div>
                <div class="text-center  mx-auto flex justify-center items-center  ">
                    {!! $qrcode !!}
                </div>

            </div>


        </div>
    </div>

        <div class="">

            <h3 class="mt-5 font-bold uppercase text-gray-700">Fotos</h3>
            @if ($solicitacao->fotos->isEmpty())
                <span>Não Consta</span>
            @endif
            <div class="flex space-x-4">
                @foreach ($solicitacao->fotos->slice(0, 3) as $foto)
                    <a href="{{ asset($foto->foto) }}" target="_blank">
                        <img src="{{ asset($foto->foto) }}" class="rounded-sm h-36 w-36" alt="">
                    </a>
                @endforeach
            </div>
        </div>





        <script>
            function formatarNumero(numero) {
                // Implemente a lógica de formatação do número de telefone, se necessário
                return numero;
            }

            function formatarData(data) {
                // Implemente a lógica de formatação da data, se necessário
                return data;
            }
        </script>
</body>

</html>
