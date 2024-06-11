<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitação</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="font-sans antialiased bg-gray-100" onload="window.print()">

    <h1 class="text-2xl font-bold uppercase mb-3">Solicitação</h1>
    <table class="w-full rounded text-sm text-center text-gray-800 border-2 dark:border-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b-2 border-gray-500">
            <tr class="bg-gray-500 text-white text-nowrap">
                <th scope="col" class=" ">Condomínio</th>
                <th scope="col" class=" ">Unidade</th>
                <th scope="col" class=" ">Morador</th>
                <th scope="col" class=" ">Email</th>
                <th scope="col" class=" ">Telefone</th>
                <th scope="col" class=" ">Assunto</th>
                <th scope="col" class=" ">Solicitação</th>
                <th scope="col" class=" ">Data e Hora</th>
                <th scope="col" class=" ">Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="">{{ $solicitacao->condominio->nome }}</td>
                <td class="">{{ $solicitacao->unidade->nome }}</td>
                <td class="">{{ $solicitacao->morador }}</td>
                <td class="">{{ $solicitacao->email }}</td>
                <td class="">{{ $solicitacao->telefone }}</td>
                <td class="">{{ $solicitacao->assunto }}</td>
                <td class="">{{ $solicitacao->solicitacao }}</td>
                <td class="">{{ $solicitacao->created_at->format('d/m/Y, H:i') }}</td>

                <td class="">
                    <select class="appearance-none bg-transparent border-none">
                        <option value="0" {{ $solicitacao->status == 0 ? 'selected' : '' }}>Aberto</option>
                        <option value="2" {{ $solicitacao->status == 2 ? 'selected' : '' }}>Andamento</option>
                        <option value="1" {{ $solicitacao->status == 1 ? 'selected' : '' }}>Concluído</option>
                    </select>
                </td>

            </tr>
        </tbody>
    </table>

    <p class="mt-5">Fotos</p>

    @foreach ($solicitacao->fotos->slice(0, 3) as $foto)
    <a href="{{ asset($foto->foto) }}" target="_blank">
        <img src="{{ asset($foto->foto) }}" width="180px" class="rounded-sm  " alt="">
    </a>
@endforeach



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
