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
        $request->validate([
            'nome' => 'required|string',
            'bloco' => 'required|string',
            'torre' => 'required|string',
            'unidades' => 'required|string', // Mudança para string em vez de integer
            'endereco' => 'required|string',
            'cnpj' => 'required|string',
        ]);

        // Crie o condomínio primeiro e obtenha o ID
        $condominio = Condominio::create([
            'nome' => $request->input('nome'),
            'endereco' => $request->input('endereco'),
            'cnpj' => $request->input('cnpj'),
        ]);

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
    $unidades = $condominio->unidades()->with('solicitacoes')->orderBy('torre','desc')->paginate(10); // 10 unidades por página

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
