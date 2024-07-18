<?php

namespace App\Http\Controllers;

use App\Models\Solicitacao;
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


        return Inertia::render('Dashboard', [
            'solicitacoes' => $solicitacoes,
            'condominios' => $condominios,
            'assuntos' => $assuntos,
            'moradores' => $moradores,
            'totalPorStatus' => $totalPorStatus
        ]);
    }
    public function relatorio()
    {

        return Inertia::render('Relatorio');
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
