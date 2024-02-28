<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

    <h1>Nova Solicitacao</h1>
    <br>
<hr>

<b>Condominio:</b> <span>{{$solicitacao->condominio}}</span><br>
<b>Unidade:</b> <span>{{$solicitacao->unidade}}</span><br>
<b>Morador:</b> <span>{{$solicitacao->nome}}</span><br>
<b>Assunto:</b> <span>{{$solicitacao->assunto}}</span><br>
<b>Solicitacao:</b> <span>{{$solicitacao->solicitacao}}</span><br>
<b>Email para Retorno:</b> <span>{{$solicitacao->email}}</span><br>

@if($solicitacao->foto)
<img src="{{url( $solicitacao->foto)}}" alt="Image" width="200"/>
@else
<p>Nenhuma foto disponível</p>
@endif


</body>
</html>
