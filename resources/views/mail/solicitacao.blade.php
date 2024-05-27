<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<h1>Nova Solicitação</h1>
<br>
<hr>

<b>Condomínio:</b> <span>{{ $solicitacao->condominio->nome }}</span><br>
<b>Unidade:</b> <span>{{ $solicitacao->unidade->nome }}</span><br>
<b>Morador:</b> <span>{{ $solicitacao->nome }}</span><br>
<b>Responsavel:</b> <span>{{ $solicitacao->proprietario  }}</span><br>
<b>Assunto:</b> <span>{{ $solicitacao->assunto }}</span><br>
<b>Solicitação:</b> <span>{{ $solicitacao->solicitacao }}</span><br>
<b>Email para Retorno:</b> <span>{{ $solicitacao->email }}</span><br>

@if($solicitacao->fotos->isNotEmpty())
    @foreach($solicitacao->fotos as $foto)
        <img src="{{ url($foto->foto) }}" alt="Imagem" width="200"/>
    @endforeach
@else
    <p>Nenhuma foto disponível</p>
@endif

</body>
</html>
