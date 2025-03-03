<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailQueueJob;
use App\Mail\Solicitacao as MailSolicitacao;
use App\Models\Condominio;
use App\Models\Foto;
use App\Models\Retorno;
use App\Models\Solicitacao;
use App\Models\Unidade;
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
            'telefone' => 'required|string',
            'solicitacao' => 'required|string',
            'nome' => 'required|string',
            'email' => 'required|email',
            'status' => 'nullable',
            'local' => 'nullable',
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
            'local' => $request->input('local'),
            'solicitacao' => $request->input('solicitacao'),
            'condominio_id' => $request->input('condominio'),
            'unidade_id' => (int) $request->input('unidade'),
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

        //$emails = ['antonioivo.3@gmail.com','sindico@solutionsindicancia.com.br'];
        // dispatch(new SendEmailQueueJob($emails,$solicitacao->id ));





        return redirect()->route('solicitar', $request->input('solicitacao'));
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
        $solicitacao = Solicitacao::where('id', $id)->with('fotos', 'unidade', 'condominio','resposta.user','resposta.fotos','retorno.user')->first();


        $unidade = Unidade::find($solicitacao->unidade_id);
        $condominio = Condominio::find($solicitacao->condominio_id);
        return Inertia::render('Solicitacao-Show', compact('solicitacao', 'condominio', 'unidade'));
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
    public function concluirSolicitacao($id)
    {
        $solicitacao = Solicitacao::find($id);

        if ($solicitacao) {
            $solicitacao->status = 1;
            $solicitacao->save();
        }


        return redirect()->back();
    }
    public function reabrirSolicitacao($id)
    {
        $solicitacao = Solicitacao::find($id);

        if ($solicitacao) {
            $solicitacao->status = 0;
            $solicitacao->save();
        }


        return redirect()->back();
    }
    public function atualizarStatus(Request $request)
    {


        $solicitacao = Solicitacao::find($request->id);

        if ($solicitacao) {
            $solicitacao->status = $request->status;
            $solicitacao->save();
        }


        return redirect()->back();
    }
    public function retorno(Request $request)
    {

        $retorno = new Retorno([
            'canal'  => $request->canal,
            'data'  => $request->data,
            'user_id'  => $request->responsavel,
            'descricao'  => $request->descricao,
            'solicitacao_id'  => $request->solicitacao_id,
        ]);

        $retorno->save();


        $solicitacao = Solicitacao::find($request->solicitacao_id);

        if ($solicitacao) {
            $solicitacao->status = 1;
            $solicitacao->save();
        }

        return to_route('dashboard');

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


        if ($solicitacao) {

            $solicitacao->resposta()->delete();
            $imagePath = $solicitacao->foto;

            $solicitacao->delete();

            if ($imagePath && file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        return redirect()->route('dashboard');
    }


    public function Retornodestroy($id)
    {

        $retorno = Retorno::find($id);
        if ($retorno) {

            $retorno->delete();
        }
        return redirect()->back();
    }
    public function solicitacoesManutencao($id)
    {
        $query = Solicitacao::query();

        $query->where('condominio_id', $id);
        $query->with('fotos', 'unidade', 'condominio');


        $solicitacoes = $query->orderBy('created_at', 'desc')->paginate(15);

        return Inertia('manutencao/AllSolicitacoes', compact('solicitacoes'));
    }
}
