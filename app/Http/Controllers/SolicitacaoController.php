<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailQueueJob;
use App\Mail\Solicitacao as MailSolicitacao;
use App\Models\Foto;
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
        'nome' => 'required|string',
        'email' => 'required|email',
        'status' => 'nullable',
       ]);

       /*

       if($request->file('foto')){

        $foto = $request->file('foto')->store('uploads');

       }else{
        $foto = '';
       }

       */
      //dd($request->all());

       $solicitacao = new Solicitacao([
        'assunto' => $request->input('assunto'),
        'solicitacao' => $request->input('solicitacao'),
        'condominio_id' => $request->input('condominio'),
        'unidade_id' => $request->input('unidade'),
        'nome' => $request->input('nome'),
        'telefone' => $request->input('telefone'),
        'email' => $request->input('email'),
        'proprietario' => $request->input('proprietario'),
        'status' =>  '0', // Salva o arquivo na pasta 'uploads' (você pode ajustar conforme necessário)
       ]);

       $solicitacao->save();

          // Manipulação do upload das fotos
    if ($request->hasFile('foto')) {
        foreach ($request->file('foto') as $file) {
            $fotoPath = $file->store('uploads');
            $foto = new Foto([
                'foto' => $fotoPath,
                'solicitacao_id' => $solicitacao->id,
            ]);
            $foto->save();
        }
    }
      //Mail::to('antonioivo.3@gmail.com')->cc('antonioivopaivaneto@gmail.com')->send(new MailSolicitacao($solicitacao));

      $emails = ['antonioivo.3@gmail.com','sindico@solutionsindicancia.com.br'];
      dispatch(new SendEmailQueueJob($emails,$solicitacao->id ));

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
    public function concluirSolicitacao( $id)
    {
        $solicitacao = Solicitacao::find($id);

        if($solicitacao){
            $solicitacao->status = 1;
            $solicitacao->save();


        }


        return redirect()->back();
    }
    public function reabrirSolicitacao( $id)
    {
        $solicitacao = Solicitacao::find($id);

        if($solicitacao){
            $solicitacao->status = 0;
            $solicitacao->save();


        }


        return redirect()->back();
    }
    public function atualizarStatus(Request $request )
    {


        $solicitacao = Solicitacao::find($request->id);

        if($solicitacao){
            $solicitacao->status = $request->status;
            $solicitacao->save();


        }


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
