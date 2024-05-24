<?php

namespace App\Http\Controllers;

use App\Models\Condominio;
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
        $condominio = Condominio::where('id',$request->input('condominio_id'))->first();


        if($request->input('condominio_nome') != $condominio->nome){
            $condominio->nome = $request->input('condominio_nome');
            $condominio->save();

        }

        $nome = $request->input('nome');
        $bloco = $request->input('bloco');
        $torre = $request->input('torre');
        $totalUnidades = $request->input('unidades');
        $totalAndares = $request->input('andar');


        // Calcula a quantidade de unidades por andar
        // Verifica se totalAndares é null ou <= 0 e define como 1 se necessário
        if (is_null($totalAndares) || $totalAndares <= 0) {
            $totalAndares = 1;
        }

        $unidadesPorAndar = intval(ceil($totalUnidades / $totalAndares));

        $unidades = [];
        $unidadeCounter = 1;

        for ($andar = 1; $andar <= $totalAndares; $andar++) {
            for ($i = 1; $i <= $unidadesPorAndar && $unidadeCounter <= $totalUnidades; $i++) {
                $unidade_nome = '';
                if ($bloco) {
                    $unidade_nome = "B$bloco";
                }
                if ($torre) {
                    $unidade_nome .= "T$torre";
                }
                if ($request->input('andar') != null) {
                    $unidade_nome .= "A$andar";
                }

                $unidade_nome .= "UNI$unidadeCounter";

                $unidades[] = [
                    'nome' => "$unidade_nome",
                    'bloco' => $bloco,
                    'torre' => $torre,
                    'andar' => $andar,
                    'condominio_id' => $condominio->id, // Relaciona a unidade com o condomínio
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $unidadeCounter++;
            }
        }

        Unidade::insert($unidades);

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
        //
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
}
