<?php

namespace App\Http\Controllers;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
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

        $condominios = Condominio::with('solicitacoes', 'unidades')->orderBy('nome', 'asc')->paginate(10);


        return Inertia('Condominios', compact('condominios'));
    }
    public function condominiosManutencista()
    {

        $condominios = Condominio::with('solicitacoesAbertas', 'unidades', 'solicitacoesFechada', 'solicitacoesAndamento')->orderBy('nome', 'asc')->paginate(10);


        return Inertia('manutencao/AllCondominios', compact('condominios'));
    }

    public function Solicitacao($condominio)
    {
        $condominio = Condominio::with('unidades')->find($condominio);
        return Inertia::render('Solicitacao-Condominio', compact('condominio'));
        //
    }

    public function customPage($condominio)
    {

        $condominio = Condominio::with('unidades')->find($condominio);

        $qrcodeUrl = route('solicitar',$condominio->id); // Substitua 'solicitacao.show' pela sua rota
        $qrcode = QrCode::size(200)->generate($qrcodeUrl); // Ajuste o tamanho conforme necessário

        return view('condominio.printpage', compact('condominio', 'qrcode'));

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



        return Inertia('Cadastro-Condominios', compact('condominios'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validação dos dados de entrada
        $request->validate([
            'nome' => 'required|string',
            'bloco' => 'nullable|string',
            'torre' => 'nullable|string',
            'unidades' => 'required|string',
            'endereco' => 'required|string',
            'cnpj' => 'required|string|unique:condominios,cnpj',
        ], [
            'nome.required' => 'O campo nome é obrigatório.',
            'nome.string' => 'O campo nome deve ser uma string.',
            'bloco.string' => 'O campo bloco deve ser uma string.',
            'torre.string' => 'O campo torre deve ser uma string.',
            'unidades.required' => 'O campo unidades é obrigatório.',
            'unidades.string' => 'O campo unidades deve ser uma string.',
            'endereco.required' => 'O campo endereço é obrigatório.',
            'endereco.string' => 'O campo endereço deve ser uma string.',
            'cnpj.required' => 'O campo CNPJ é obrigatório.',
            'cnpj.string' => 'O campo CNPJ deve ser uma string.',
            'cnpj.unique' => 'O CNPJ informado já está cadastrado.',

        ]);

        // Criação do condomínio
        $condominio = Condominio::create([
            'nome' => $request->input('nome'),
            'endereco' => $request->input('endereco'),
            'cnpj' => $request->input('cnpj'),
        ]);

        // Captura dos parâmetros
        $nome = $request->input('nome');
        $bloco = $request->input('bloco');
        $torre = $request->input('torre');
        $unidadesString = $request->input('unidades');
        $qtdAndares = (int) $request->input('qtd_andares');
        $qtdTotal = $request->input('qtd_total');

        if ($qtdTotal === null) {
            if (strpos($unidadesString, '-') !== false) {
                [$inicio, $fim] = explode('-', $unidadesString);
                $unidades = range((int)$inicio, (int)$fim);
            } elseif (strpos($unidadesString, ';') !== false) {
                // Se forem unidades individuais
                $unidades = explode(';', $unidadesString);
                $unidades = array_map('intval', $unidades);
            } else {
                return redirect()->back()->with('error', 'Formato de unidades inválido.');
            }




            foreach (range(1, $qtdAndares) as $andar) {
                foreach ($unidades as $numeroUnidade) {

                    $nomeBloco = $bloco ? "-" . $bloco : '';
                    $nomeTorre = $torre ? $torre . "/" : '';

                    $unidadeComAndar = $andar . substr($numeroUnidade, -2);
                    Unidade::create([
                        'nome' => "{$nomeTorre}UND.{$unidadeComAndar}{$nomeBloco}",
                        'bloco' => $bloco,
                        'andar' => $andar,
                        'torre' => $torre,
                        'condominio_id' => $condominio->id,
                    ]);
                }
            }
        } else {
            $inicioUnidade = (int) $unidadesString; // Supondo que unidadesString é um único número aqui

            foreach (range($inicioUnidade, $inicioUnidade + $qtdTotal - 1) as $numeroUnidade) {
                Unidade::create([
                    'nome' => $torre . "UND." . $numeroUnidade . $bloco,
                    'bloco' => $bloco,
                    'andar' => '', // Calcular o andar
                    'torre' => $torre,
                    'condominio_id' => $condominio->id,
                ]);
            }
        }

        return redirect()->route('condominios.create')->with('success', 'Condomínio e unidades cadastrados com sucesso!');
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
        $unidades = $condominio->unidades()->with('solicitacoes')->orderBy('nome', 'asc')->paginate(10); // 10 unidades por página

        return Inertia('Editar-Condominio', compact('condominio', 'unidades'));
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
    public function update(Request $request)
    {
        $condominio = Condominio::where('id', $request->input('condominio_id'))->first();

        $condominio->nome = $request->input('condominio_nome');
        $condominio->endereco = $request->input('endereco');
        $condominio->cnpj = $request->input('cnpj');
        $condominio->save();


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
        $condominio = Condominio::find($id);

        if (!$condominio) {
            return redirect()->route('condominios.create')->with('error', 'Condomínio não encontrado.');
        }


        // Excluir todas as solicitações relacionadas ao condomínio
        // Verificar se há solicitações relacionadas
        if (method_exists($condominio, 'solicitacoes') && method_exists($condominio, 'unidades')) {
            // Excluir todas as solicitações relacionadas ao condomínio
            $condominio->solicitacoes()->delete();
            $condominio->unidades()->delete();
        }
        // Excluir o condomínio
        $condominio->delete();

        return redirect()->route('condominios.create')->with('success', 'Condomínio e suas unidades foram excluídas com sucesso.');
    }
}
