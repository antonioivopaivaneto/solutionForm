<?php

namespace App\Http\Controllers;

use App\Models\Solicitacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $condominios = DB::table('solicitacoes')
    ->select('condominio', DB::raw('count(*) as total'))
    ->groupBy('condominio')
    ->orderByDesc('total')
    ->limit(5)
    ->get();

    $assuntos = DB::table('solicitacoes')
    ->select('assunto',DB::raw('count(*) as total'))
    ->groupBy('assunto')
    ->orderByDesc('total')
    ->limit(5)
    ->get();

    $moradores = DB::table('solicitacoes')
    ->select('nome',DB::raw('count(*) as total'))
    ->groupBy('nome')
    ->orderByDesc('total')
    ->limit(5)
    ->get();


        $solicitacoes =  Solicitacao::orderBy('created_at','desc')->where('status','=',0)->paginate(15);
        return Inertia::render('Dashboard', ['solicitacoes' => $solicitacoes,
        'condominios' =>$condominios,
        'assuntos' =>$assuntos,
        'moradores' => $moradores
    ]);
    }
    public function historico()
    {

        $solicitacoes =  Solicitacao::orderBy('created_at','desc')->where('status','=',1)->paginate(15);
        return Inertia::render('Historico', ['solicitacoes' => $solicitacoes    ]);
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
