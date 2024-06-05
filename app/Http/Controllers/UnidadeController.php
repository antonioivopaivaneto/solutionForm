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
        $condominio = Condominio::where('id', $request->input('condominio_id'))->first();


        if ($request->input('condominio_nome') != $condominio->nome) {
            $condominio->nome = $request->input('condominio_nome');
            $condominio->endereco = $request->input('endereco');
            $condominio->cnpj = $request->input('cnpj');
            $condominio->save();
        }

        $nome = $request->input('nome');
        $bloco = $request->input('bloco');
        $torre = $request->input('torre');
        $totalUnidades = $request->input('unidades');
        $totalAndares = $request->input('andar');


        $nome = $request->input('nome');
        $bloco = $request->input('bloco');
        $torre = $request->input('torre');
        $unidadesString = $request->input('unidades');

        // Verifica se o formato de unidades é um intervalo (101-104) ou unidades individuais (101;104)
        if (strpos($unidadesString, '-') !== false) {
            // Se for um intervalo
            [$inicio, $fim] = explode('-', $unidadesString);
            $unidades = range($inicio, $fim);
        } elseif (strpos($unidadesString, ';') !== false) {
            // Se forem unidades individuais
            $unidades = explode(';', $unidadesString);
        } else {
            // Se o formato for inválido
            return redirect()->back()->with('error', 'Formato de unidades inválido.');
        }

        // Criar as unidades com base no formato fornecido
        foreach ($unidades as $numeroUnidade) {
            Unidade::create([
                'nome' => "$torre/UND.$numeroUnidade-$bloco",
                'bloco' => $bloco,
                'andar' => '',
                'torre' => $torre,
                'condominio_id' => $condominio->id,
            ]);
        }

        return redirect()->back();
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
