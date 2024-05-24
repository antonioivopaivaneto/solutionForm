<?php

namespace App\Http\Controllers;

use App\Models\Condominio;
use App\Models\Solicitacao as ModelsSolicitacao;
use App\Models\Unidade;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CondominioController extends Controller
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

    public function Solicitacao($condominio)
    {
        $condominio = Condominio::with('unidades')->find($condominio);



        return Inertia::render('Solicitacao-Condominio',compact('condominio'));
            //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $condominios = Condominio::withCount('unidades')->paginate(10);



        return Inertia('Cadastro-Condominios',compact('condominios'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());

        /*
        $request->validate([
            'nome' => 'required|string',
            'bloco' => 'required|string',
            'torre' => 'required|string',
            'unidades' => 'required|integer|min=1',
            'andares' => 'required|integer|min=1',
        ]);

        */

        // Crie o condomínio primeiro e obtenha o ID
        $condominio = Condominio::create([
            'nome' => $request->input('nome'),
            // Adicione outros campos necessários para o condomínio
        ]);



        $nome = $request->input('nome');
        $bloco = $request->input('bloco');
        $torre = $request->input('torre');
        $totalUnidades = $request->input('unidades');
        $totalAndares = $request->input('andares');

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
                if ($request->input('andares') != null) {
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

        return redirect()->route('condominios.create');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       // Busca o condomínio sem carregar as unidades
    $condominio = Condominio::findOrFail($id);

    // Paginando as unidades do condomínio
    $unidades = $condominio->unidades()->paginate(10); // 10 unidades por página



        return Inertia('Editar-Condominio',compact('condominio','unidades'));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
{
    $condominio = Condominio::find($id);

    if (!$condominio) {
        return redirect()->route('condominios.create')->with('error', 'Condomínio não encontrado.');
    }

    // Excluir todas as unidades associadas ao condomínio
    $condominio->unidades()->delete();

    // Excluir todas as solicitações relacionadas ao condomínio
  // Verificar se há solicitações relacionadas
  if (method_exists($condominio, 'solicitacoes')) {
    // Excluir todas as solicitações relacionadas ao condomínio
    $condominio->solicitacoes()->delete();
}
    // Excluir o condomínio
    $condominio->delete();

    return redirect()->route('condominios.create')->with('success', 'Condomínio e suas unidades foram excluídas com sucesso.');
}

}
