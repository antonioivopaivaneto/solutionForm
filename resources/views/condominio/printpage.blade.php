<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitação</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
        }
        .page {
            width: 297mm;
            height: 210mm;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            overflow: hidden;
        }
        .section {
            display: flex;
            width: 100%;
            height: 50%;
        }
        .half {
            width: 50%;
            position: relative;
        }
        .text-overlay {
            position: absolute;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            width: 100%;
        }
        .underline {
            text-decoration: underline;
        }
        @media print {
            .underline {
                text-decoration: underline !important;
            }
        }
    </style>
</head>

<body class="font-sans antialiased " onload="window.print()">

    <div class="page">
        <div class="section">
            <div class="half flex justify-center items-center">
                <img src="{{ asset('print.png') }}" alt="Imagem" class="w-full h-full object-cover">
                <div class="text-overlay bottom-24 text-2xl">Comunicação Fácil e descomplicada</div>
                <div class="text-overlay bottom-5 text-2xl">Solution Sindicância</div>
            </div>
            <div class="half flex flex-col justify-center items-center p-5">
                <div class="mt-5"></div>
                {!! $qrcode !!}
                <h1 class="font-bold uppercase text-1xl text-center mx-auto mt-5 mb-8">Informe uma manutenção, faça uma reclamação, sugestão ou elogio de forma fácil e rápida.</h1>
                <h1 class="font-bold uppercase text-1xl text-center mx-auto text-blue-700"><u>Solution Sindicância Inovando em Comunicações</u></h1>
            </div>
        </div>
        <div class="section mt-1 border border-t-2 border-dashed">
            <div class="half flex justify-center items-center">
                <img src="{{ asset('print.png') }}" alt="Imagem" class="w-full h-full object-cover">
                <div class="text-overlay bottom-24 text-2xl">Comunicação Fácil e descomplicada</div>
                <div class="text-overlay bottom-5 text-2xl">Solution Sindicância</div>
            </div>
            <div class="half flex flex-col justify-center items-center p-5">
                <div class="mt-5"></div>

                {!! $qrcode !!}
                <h1 class="font-bold uppercase text-1xl text-center mx-auto mt-5 mb-10">Informe uma manutenção, faça uma reclamação, sugestão ou elogio de forma fácil e rápida.</h1>
                <h1 class="font-bold uppercase text-1xl text-center mx-auto  text-blue-700 "><u>Solution Sindicância Inovando em Comunicações</u></h1>
            </div>
        </div>
    </div>

</body>

</html>
