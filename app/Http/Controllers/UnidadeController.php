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
            if (strpos($unidadesString, '-') !== false) {
                [$inicio, $fim] = explode('-', $unidadesString);
                $inicio = (int) $inicio;
                $fim = (int) $fim;
    
                // Descobrir quantos dígitos tem o sufixo (ex: 1001 tem 4 dígitos, sufixo são os últimos 3)
                $tamanhoTotal = strlen($inicio);
                $tamanhoSufixo = $tamanhoTotal - 1; // considerando andar 1 dígito
    
                $unidades = [];
                for ($i = $inicio; $i <= $fim; $i++) {
                    // pega o sufixo: últimos N dígitos
                    $numeroStr = (string)$i;
                    $sufixo = substr($numeroStr, -$tamanhoSufixo);
                    $unidades[] = $sufixo;
                }
            } elseif (strpos($unidadesString, ';') !== false) {
                $unidades = explode(';', $unidadesString);
            } else {
                return redirect()->back()->with('error', 'Formato de unidades inválido.');
            }
    
            foreach (range(1, $qtdAndares) as $andar) {
                foreach ($unidades as $sufixo) {
                    $sufixoPadded = str_pad($sufixo, 3, '0', STR_PAD_LEFT);
                    $numeroCompleto = $andar . $sufixoPadded; // ex: 1 + 001 = 1001
    
                    Unidade::create([
                        'nome' => "{$nomeTorre}UND.{$numeroCompleto}{$nomeBloco}",
                        'bloco' => $bloco,
                        'andar' => $andar,
                        'torre' => $torre,
                        'condominio_id' => $condominio->id,
                    ]);
                }
            }
        } else {
            // Continua igual, para qtdTotal definido
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
    public function show($id)
    {
        // Paginando as unidades do condomínio
        $unidade = Unidade::find($id); // 10 unidades por página

        $solicitacoes = Solicitacao::where('unidade_id', $unidade->id)
        ->with('fotos')
        ->orderByRaw('CASE WHEN status IS NULL OR status = 0 THEN 0 ELSE 1 END ASC')
        ->paginate(10);

        // Busca o condomínio sem carregar as unidades
        $condominio = Condominio::find($unidade->condominio_id);

        return Inertia('Solicitacao-Unidade', compact('condominio', 'unidade','solicitacoes'));
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
