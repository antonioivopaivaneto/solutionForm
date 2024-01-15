<?php

namespace App\Http\Controllers;

use App\Mail\Solicitacao as MailSolicitacao;
use App\Models\Solicitacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class SolicitacaoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Solicitacao');

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
       $request->validate([
        'assunto' => 'required|string',
        'solicitacao' => 'required|string',
        'condominio' => 'required|string',
        'unidade' => 'required|string',
        'nome' => 'required|string',
        'email' => 'required|email',
        'foto' => 'nullable',
       ]);

       if($request->file('foto')){
        $foto = $request->file('foto')->store('uploads');
       }else{
        $foto = '';
       }




       $solicitacao = new Solicitacao([
        'assunto' => $request->input('assunto'),
        'solicitacao' => $request->input('solicitacao'),
        'condominio' => $request->input('condominio'),
        'unidade' => $request->input('unidade'),
        'nome' => $request->input('nome'),
        'email' => $request->input('email'),
        'foto' =>  $foto, // Salva o arquivo na pasta 'uploads' (você pode ajustar conforme necessário)
       ]);

       $solicitacao->save();

       Mail::to('antonioivo.3@gmail.com')->send(new MailSolicitacao($solicitacao));


       return redirect()->route('solicitacao.index');


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
