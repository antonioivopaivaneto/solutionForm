<?php

namespace App\Http\Controllers;

use App\Models\Condominio;
use App\Models\Solicitacao;
use App\Models\Unidade;
use App\Service\Unidade\UnidadeNumeracaoService;
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




public function store(Request $request, UnidadeNumeracaoService $service)
{
    $condominio = Condominio::find($request->input('condominio_id'));
    $tipo = $request->input('tipo_numeracao');
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

        //$numeros = $this->gerarNumeracaoUnidades($unidadesString, $qtdAndares);
        $numeros = $service->gerarNumeracao($unidadesString, $qtdAndares, $tipo);

        foreach ($numeros as $numeroCompleto) {
            Unidade::create([
                'nome' => "{$nomeTorre}UND.{$numeroCompleto}{$nomeBloco}",
                'bloco' => $bloco,
                'andar' => $service->extrairAndar($numeroCompleto, $unidadesString),
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

    public function updateMassa(Request $request)
{
    foreach ($request->unidades as $u) {
        Unidade::where('id', $u['id'])->update([
            'nome' => $u['nome'],
            'torre' => $u['torre'],
            'bloco' => $u['bloco'],
            'andar' => $u['andar'],
        ]);
    }

    return back()->with('success', 'Unidades atualizadas');
}

}
