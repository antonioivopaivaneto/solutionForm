<?php

namespace App\Http\Controllers;

use App\Models\Condominio;
use App\Models\Solicitacao;
use App\Models\Unidade;
use Illuminate\Http\Request;

class UnidadeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
private function gerarNumeracaoUnidades(string $entrada, int $qtd_andares): array
{
    $unidades = [];

    $prefixo = '';
    $faixa = $entrada;

    if (strpos($entrada, '/') !== false) {
        [$prefixo, $faixa] = explode('/', $entrada);
    }

    [$inicio, $fim] = explode('-', $faixa);

    $prefixo = trim($prefixo);
    $inicio = (int) trim($inicio);
    $fim = (int) trim($fim);

    if ($prefixo === '') {
        // Caso sem prefixo, exemplo '01-04'
        for ($andar = 1; $andar <= $qtd_andares; $andar++) {
            for ($num = $inicio; $num <= $fim; $num++) {
                $sufixo = str_pad((string)$num, 2, '0', STR_PAD_LEFT);
                $numero = $andar . $sufixo; // ex: 1 + 01 = 101
                $unidades[] = $numero;
            }
        }
    } else {
        // Caso com prefixo, exemplo '1/01-03' ou '10/01-04'
        // Para formar o número, fazemos: prefixo * 100 + (andar -1)*10 + sufixo
        $prefixoInt = (int)$prefixo;

        for ($andar = 1; $andar <= $qtd_andares; $andar++) {
            for ($num = $inicio; $num <= $fim; $num++) {
                $sufixo = str_pad((string)$num, 2, '0', STR_PAD_LEFT);
                $numero = ($prefixoInt * 100) + (($andar - 1) * 10) + (int)$sufixo;
                $unidades[] = (string)$numero;
            }
        }
    }

    return $unidades;
}

private function extrairAndar(string $numeroCompleto, string $prefixo = ''): int
{
    $numeroCompleto = (string) $numeroCompleto;

    if ($prefixo === '') {
        // Sem prefixo, andar é o primeiro dígito (centena)
        return (int) substr($numeroCompleto, 0, 1);
    } else {
        // Com prefixo, andar é o dígito imediatamente após o prefixo
        $prefixoLength = strlen($prefixo);
        $numeroLength = strlen($numeroCompleto);

        if ($numeroLength > $prefixoLength) {
            return (int) substr($numeroCompleto, $prefixoLength, 1);
        } else {
            return 0;
        }
    }
}

public function store(Request $request)
{
    $condominio = Condominio::find($request->input('condominio_id'));

    $bloco = $request->input('bloco');
    $torre = $request->input('torre');
    $unidadesString = $request->input('unidades');
    $qtdAndares = (int) $request->input('qtd_andares');
    $qtdTotal = $request->input('qtd_total');

    $nomeBloco = $bloco ? "-" . $bloco : '';
    $nomeTorre = $torre ? $torre . "/" : '';

    if ($qtdTotal === null) {
        $prefixo = '';
        if (strpos($unidadesString, '/') !== false) {
            [$prefixo, ] = explode('/', $unidadesString);
            $prefixo = trim($prefixo);
        }

        $numeros = $this->gerarNumeracaoUnidades($unidadesString, $qtdAndares);

        foreach ($numeros as $numeroCompleto) {
            Unidade::create([
                'nome' => "{$nomeTorre}UND.{$numeroCompleto}{$nomeBloco}",
                'bloco' => $bloco,
                'andar' => $this->extrairAndar($numeroCompleto, $prefixo),
                'torre' => $torre,
                'condominio_id' => $condominio->id,
            ]);
        }
    } else {
        // Para qtdTotal preenchido - lógica antiga mantida
        $tamanho = strlen(trim($unidadesString));
        $inicio = (int) $unidadesString;

        for ($i = $inicio; $i < $inicio + $qtdTotal; $i++) {
            $numeroUnidade = str_pad($i, $tamanho, '0', STR_PAD_LEFT);

            Unidade::create([
                'nome' => "{$nomeTorre}UND.{$numeroUnidade}{$nomeBloco}",
                'bloco' => $bloco,
                'andar' => '',
                'torre' => $torre,
                'condominio_id' => $condominio->id,
            ]);
        }
    }

    return redirect()->back()->with('success', 'Unidades criadas com sucesso!');
}


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        // Paginando as unidades do condomínio
        $unidade = Unidade::find($id); // 10 unidades por página
        $historico = $request->boolean('historico');

        $solicitacoes = Solicitacao::where('unidade_id', $unidade->id)
            ->when($historico, function ($query) {
                // Se for histórico, filtra status = 1
                $query->where('status', 1);
            }, function ($query) {
                // Se não for histórico, filtra status = 0 ou 3
                $query->whereIn('status', [0, 3]);
            })
            ->with('fotos')
            ->orderByRaw('CASE WHEN status IS NULL OR status = 0 THEN 0 ELSE 1 END ASC')
            ->paginate(10);

        // Busca o condomínio sem carregar as unidades
        $condominio = Condominio::find($unidade->condominio_id);

        return Inertia('Solicitacao-Unidade', compact('condominio', 'unidade', 'solicitacoes', 'historico'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $unidade = Unidade::find($id);

        $unidade->update($request->all());

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $unidade = Unidade::find($id);
        $unidade->delete();

        return redirect()->back();
    }

    public function destroyMassa($unidades)
    {
        // Converta a string de IDs para um array
        $ids = explode(',', $unidades);

        Unidade::whereIn('id', $ids)->delete();

        return redirect()->back();
    }
    public function destroyAll($condominio)
    {

        Unidade::where('condominio_id', $condominio)->delete();

        return redirect()->back();
    }
}
