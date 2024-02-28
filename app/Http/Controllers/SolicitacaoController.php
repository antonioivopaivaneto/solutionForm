<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailQueueJob;
use App\Mail\Solicitacao as MailSolicitacao;
use App\Models\Solicitacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
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
        'status' => 'nullable',
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
        'status' =>  '0', // Salva o arquivo na pasta 'uploads' (você pode ajustar conforme necessário)
       ]);

       $solicitacao->save();




      //Mail::to('antonioivo.3@gmail.com')->cc('antonioivopaivaneto@gmail.com')->send(new MailSolicitacao($solicitacao));

      $emails = ['antonioivo.3@gmail.com','sindico@solutionsindicancia.com.br'];
      dispatch(new SendEmailQueueJob($emails,$solicitacao ));

       return ;


    }

    public function showImage($filename)
    {

        $path = storage_path("app/uploads/{$filename}");

        if (!Storage::exists("uploads/{$filename}")) {
            abort(404);
        }

        return response()->file($path);

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

        $solicitacao = Solicitacao::find($id);

        if($solicitacao){

            $imagePath = $solicitacao->foto;

            $solicitacao->delete();

            if ($imagePath && file_exists($imagePath)) {
                unlink($imagePath);
            }

        }

        return redirect()->back();
    }
}
