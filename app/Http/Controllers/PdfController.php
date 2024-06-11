<?php

namespace App\Http\Controllers;

use App\Models\Solicitacao;
use Illuminate\Http\Request;
use Dompdf\Dompdf;

class PdfController extends Controller
{
    public function exportToPdf($id)
{
    // Obtenha os dados da solicitação
    $solicitacao = Solicitacao::with('fotos', 'unidade', 'condominio')->find($id);

    if (!$solicitacao) {
        abort(404); // Ou trate o caso de solicitação não encontrada
    }

    // Formate os dados em HTML
    // Formate os dados em HTML
    $html = '<html>';
    $html .= '<head><style>body { font-family: Arial, sans-serif; }</style></head>';
    $html .= '<body>';
    $html .= '<img src="' . asset('logo.png') . '" alt="Logo" style="width: 250px; height: auto; margin:0">'; // Adicione o logotipo2
    $html .= '<h1  style="border-bottom: 1px solid #000;">Solicitação</h1>';
    $html .= '<p ><strong>Código:</strong> ' . $solicitacao->id . '</p>';
    $html .= '<p><strong>Condomínio:</strong> ' . $solicitacao->condominio->nome . '</p>';
    $html .= '<p><strong>Unidade:</strong> ' . $solicitacao->unidade->nome . '</p>';
    $html .= '<p><strong>Morador:</strong> ' . $solicitacao->morador . '</p>';
    $html .= '<p><strong>Email:</strong> ' . $solicitacao->email . '</p>';
    $html .= '<p><strong>Telefone:</strong> ' . $solicitacao->telefone . '</p>';
    $html .= '<p><strong>Assunto:</strong> ' . $solicitacao->assunto . '</p>';
    $html .= '<p><strong>Solicitação:</strong> ' . $solicitacao->solicitacao . '</p>';
    $html .= '<p><strong>Data e Hora:</strong> ' . $solicitacao->created_at->format('d/m/Y, H:i') . '</p>';
    $html .= '<p><strong>Status:</strong> ' . ($solicitacao->status == 0 ? 'Aberto' : ($solicitacao->status == 2 ? 'Andamento' : 'Concluido')) . '</p>';

        // Adicionar fotos ao HTML
        if ($solicitacao->fotos->isNotEmpty()) {
            $html .= '<h2>Fotos</h2>';
            foreach ($solicitacao->fotos as $foto) {
                $html .= '<img src="' . asset( $foto->foto) . '" alt="Foto" style="width: 250px; height: auto; margin: 10px 0;">';
            }
        }

    $html .= '</body></html>';
    // Crie uma nova instância do dompdf
    $dompdf = new Dompdf();

    // Carregue o HTML formatado
    $dompdf->loadHtml($html);

    // Renderize o PDF
    $dompdf->render();

    return view('pdf.solicitacao', compact('solicitacao'));


    //return view('pdf.solicitacao')->with('html', $html);

    // Retorne o PDF para download
    return $dompdf->stream('solicitacao.pdf');
}
}
