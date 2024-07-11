<?php

namespace App\Http\Controllers;

use App\Models\FotoResposta;
use App\Models\RespostaSolicitacao;
use App\Models\Solicitacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class RespostaSolicitacaoController extends Controller
{
    public function index()
    {
        dd('teste');
        return ;
    }

    public function show(Solicitacao $id)
    {

        $solicitacao = json_decode(Solicitacao::find($id)->first());
        return Inertia::render('manutencao/RespostaManutencao',compact('solicitacao'));


    }

    public function store(Request $request)
    {
        $resposta = new RespostaSolicitacao([
            'descricao' => $request->input('descricao'),
            'solicitacao_id' => $request->input('solicitacao_id'),
            'user_id' => Auth::user()->id,

        ]);

        $resposta->save();

        // Manipulação do upload das fotos
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $fotoPath = $file->store('uploads');
                $foto = new FotoResposta([
                    'foto' => $fotoPath,
                    'solicitacao_id' => $resposta->id,
                ]);
                $foto->save();
            }
        }
        //Mail::to('antonioivo.3@gmail.com')->cc('antonioivopaivaneto@gmail.com')->send(new MailSolicitacao($solicitacao));

        //$emails = ['antonioivo.3@gmail.com','sindico@solutionsindicancia.com.br'];
        // dispatch(new SendEmailQueueJob($emails,$solicitacao->id ));


        return redirect()->back()->with('success', 'ok');
    }

    public function destroy($id)
    {
        $resposta = RespostaSolicitacao::find($id);


        if ($resposta) {

            $imagePath = $resposta->foto;

            $resposta->delete();

            if ($imagePath && file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        return redirect()->back();
    }
}
