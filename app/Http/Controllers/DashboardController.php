<?php

namespace App\Http\Controllers;

use App\Models\Condominio;
use App\Models\Retorno;
use App\Models\Solicitacao;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if (Auth::user()->role === 'manutence') {
            return redirect()->route('condominios.manutencao');
        }

        $condominios = DB::table('solicitacoes')
            ->join('condominios', 'solicitacoes.condominio_id', '=', 'condominios.id')
            ->select('condominios.nome', DB::raw('count(*) as total'))
            ->groupBy('condominios.nome')
            ->orderByDesc('total')
            ->limit(5)
            ->get();


        $assuntos = DB::table('solicitacoes')
            ->select('assunto', DB::raw('count(*) as total'))
            ->groupBy('assunto')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $moradores = DB::table('solicitacoes')
            ->join('unidades', 'solicitacoes.unidade_id', '=', 'unidades.id')
            ->select('unidades.nome as unidade_nome', 'solicitacoes.nome as morador_nome', DB::raw('count(*) as total'))
            ->groupBy('unidades.nome', 'solicitacoes.nome')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Total de solicitações por status
        $totalPorStatus = Solicitacao::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();


        // Iniciando a consulta das solicitações
        $query = Solicitacao::query();

        // Aplicando filtro por status (aberto ou em andamento)
        $query->where(function ($query) {
            $query->where('status', 0)
                ->orWhere('status', 2);
        });

        // Carregando relacionamentos
        $query->with('fotos', 'unidade', 'condominio');

        // Verificando e aplicando filtros adicionais, se existirem
        if ($request->filtro && $request->order) {

            $filtro = $request->filtro;
            $order = $request->order;

            $query->orderBy($filtro, $order);
        }

        if ($request->pesquisa) {
            $query->where('nome', $request->pesquisa);
        }

        $solicitacoes = $query->orderBy('created_at', 'desc')->paginate(15);
        $users = User::all();


        return Inertia::render('Dashboard', [
            'solicitacoes' => $solicitacoes,
            'condominios' => $condominios,
            'assuntos' => $assuntos,
            'moradores' => $moradores,
            'totalPorStatus' => $totalPorStatus,
            'users' => $users,
        ]);
    }
    public function relatorio()
    {
        $condominios = Condominio::all();
        $assuntos = DB::table('solicitacoes')
        ->select('assunto', DB::raw('count(*) as total'))
        ->groupBy('assunto')
        ->orderByDesc('total')
        ->limit(5)
        ->get();


        return Inertia::render('Relatorio',compact('condominios','assuntos'));
    }
    public function relatorioCompleto(Request $request)
    {
        $condominioId = $request->solicitacao_id;
        $dataInicio = $request->data[0];
        $dataFinal = $request->data[1];
        $assunto = $request->assunto;

        // Filtragem condicional para o assunto e condomínio
        $queryRespostas = Retorno::whereBetween('data', [$dataInicio, $dataFinal]);

        $querySolicitacoes = Solicitacao::whereBetween('created_at', [$dataInicio, $dataFinal]);

        if ($assunto) {
            $querySolicitacoes->where('assunto', $assunto);
            $queryLocais = DB::table('solicitacoes')
                ->where('assunto', $assunto);
        } else {
            $queryLocais = DB::table('solicitacoes');
        }

        if ($condominioId) {
            $querySolicitacoes->where('condominio_id', $condominioId);
            $queryLocais->where('condominio_id', $condominioId);
        }

        $respostas = $queryRespostas->get();
        $solicitacoes = $querySolicitacoes->get();

        // Agrega os locais com base no filtro de assunto e condomínio
        $locais = $queryLocais
            ->select('local', DB::raw('count(*) as total'))
            ->groupBy('local')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Agrega os assuntos com base no filtro de assunto e condomínio
        $assuntos = DB::table('solicitacoes')
            ->whereBetween('created_at', [$dataInicio, $dataFinal])
            ->when($assunto, function ($query) use ($assunto) {
                return $query->where('assunto', $assunto);
            })
            ->when($condominioId, function ($query) use ($condominioId) {
                return $query->where('condominio_id', $condominioId);
            })
            ->select('assunto', DB::raw('count(*) as total'))
            ->groupBy('assunto')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Agrega as unidades com base no filtro de assunto e condomínio
        $unidades = DB::table('solicitacoes')
            ->join('unidades', 'solicitacoes.unidade_id', '=', 'unidades.id')
            ->whereBetween('solicitacoes.created_at', [$dataInicio, $dataFinal])
            ->when($assunto, function ($query) use ($assunto) {
                return $query->where('solicitacoes.assunto', $assunto);
            })
            ->when($condominioId, function ($query) use ($condominioId) {
                return $query->where('solicitacoes.condominio_id', $condominioId);
            })
            ->select('unidades.nome as unidade_nome', 'solicitacoes.nome as morador_nome', DB::raw('count(*) as total'))
            ->groupBy('unidades.nome', 'solicitacoes.nome')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $condominios = Condominio::all();

        // Atualiza os dados do relatório com as respostas, canais, status e assuntos
        $canais = [
            'Email' => 0,
            'Whatsapp' => 0,
            'Telefone' => 0,
        ];
        $status = [
            '0' => 0,
            '1' => 0,
            '2' => 0,
        ];

        foreach ($respostas as $resposta) {
            if (isset($canais[$resposta->canal])) {
                $canais[$resposta->canal]++;
            }
            if (isset($status[$resposta->status])) {
                $status[$resposta->status]++;
            }
        }
        foreach ($solicitacoes as $solicitacao) {
            if (isset($status[$solicitacao->status])) {
                $status[$solicitacao->status]++;
            }
        }

        return Inertia::render('RelatorioCompleto', compact('locais', 'unidades', 'condominios', 'respostas', 'canais', 'status', 'assuntos'));
    }


    public function historico()
    {

        $solicitacoes =  Solicitacao::orderBy('created_at', 'desc')->where('status', '=', 1)
            ->with('fotos', 'unidade', 'condominio')
            ->paginate(15);
        return Inertia::render('Historico', ['solicitacoes' => $solicitacoes]);
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
        //
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
        //
    }
}
