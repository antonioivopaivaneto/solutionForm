<?php

namespace App\Http\Controllers;

use App\Models\Solicitacao;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelController extends Controller
{
    public function downloadExcel($id)
    {
        $solicitacao = Solicitacao::with('fotos', 'unidade', 'condominio')->find($id);

        if (!$solicitacao) {
            abort(404); // Ou trate o caso de solicitação não encontrada de acordo com sua lógica
        }

        // Criar uma nova instância da planilha
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Condomínio:');
$sheet->setCellValue('A2', $solicitacao->condominio->nome);

$sheet->setCellValue('B1', 'Unidade:');
$sheet->setCellValue('B2', $solicitacao->unidade->nome);

$sheet->setCellValue('C1', 'Morador:');
$sheet->setCellValue('C2', $solicitacao->nome);

$sheet->setCellValue('D1', 'Email:');
$sheet->setCellValue('D2', $solicitacao->email);

$sheet->setCellValue('E1', 'Telefone:');
$sheet->setCellValue('E2', $solicitacao->telefone);

$sheet->setCellValue('F1', 'Assunto:');
$sheet->setCellValue('F2', $solicitacao->assunto);

$sheet->setCellValue('G1', 'Local:');
$sheet->setCellValue('G2', $solicitacao->local);

$sheet->setCellValue('H1', 'Solicitação:');
$sheet->setCellValue('H2', $solicitacao->solicitacao);

$sheet->setCellValue('I1', 'Data e Hora:');
$sheet->setCellValue('I2', $solicitacao->created_at->format('d/m/Y, H:i'));

$sheet->setCellValue('J1', 'Foto:');
$sheet->setCellValue('J2', ''); // Você precisará lidar com as fotos aqui, conforme necessário

$sheet->setCellValue('K1', 'Status:');
$sheet->setCellValue('K2', $solicitacao->status == 0 ? 'Aberto' : ($solicitacao->status == 2 ? 'Andamento' : 'Concluído'));

$sheet->setCellValue('L1', 'Responsável:');
$sheet->setCellValue('L2', ''); // Deve ser ajustado conforme necessário

$sheet->setCellValue('M1', 'Prazo Início:');
$sheet->setCellValue('M2', ''); // Deve ser ajustado conforme necessário

$sheet->setCellValue('N1', 'Prazo Fim:');
$sheet->setCellValue('N2', ''); // Deve ser ajustado conforme necessário



        // Adicione a imagem na célula I1
        $row = 2;

        foreach ($solicitacao->fotos as $foto) {
            $imagePath =  Storage::path($foto->foto); // Caminho absoluto para a imagem com barras invertidas corrigidas
            if ($solicitacao->fotos->isNotEmpty()) {
                $drawing = new Drawing();
                $drawing->setPath($imagePath); // Caminho para a imagem
                $drawing->setCoordinates('J' . $row); // Célula onde a imagem será inserida
                $drawing->setWidth(100); // Largura da imagem
                $drawing->setHeight(100); // Altura da imagem
                $drawing->setWorksheet($sheet);
                $row++;
            } else {
                $sheet->setCellValue('J2' . $row, 'Image Not Found');            }
        }


        // Adicionando margem entre as células
        $sheet->getStyle('A1:N2')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
        $sheet->getStyle('A1:N2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('A1:N2')->getAlignment()->setWrapText(true);

        // Ajustando o tamanho das colunas
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(10);
        $sheet->getColumnDimension('D')->setWidth(25);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(40);
        $sheet->getColumnDimension('I')->setWidth(15);
        $sheet->getColumnDimension('J')->setWidth(15);
        $sheet->getColumnDimension('K')->setWidth(10);
        $sheet->getColumnDimension('L')->setWidth(15);
        $sheet->getColumnDimension('M')->setWidth(15);
        $sheet->getColumnDimension('N')->setWidth(15);
        $sheet->getColumnDimension('O')->setWidth(15);


      // Definir títulos em negrito na linha 1, com aumento de fonte, texto em maiúsculas e centralização do conteúdo
$sheet->getStyle('A1:O1')->applyFromArray([
    'font' => [
        'bold' => true,
        'size' => 12, // Ajuste o tamanho da fonte conforme necessário
        'color' => ['rgb' => 'FFFFFF'], // Texto branco
    ],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => ['rgb' => '2b536f'], // Fundo azul claro
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Centralize o texto horizontalmente
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, // Centralize o texto verticalmente
    ],
    'borders' => [ // Adicione bordas para destacar os títulos
        'outline' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
    ],
]);


        // Aumentar a altura da linha A1
        $sheet->getRowDimension('1')->setRowHeight(30); // Ajuste a altura conforme necessário
        // Salvar a planilha no diretório public
        $file_path = public_path('storage/solicitacao.xlsx');
        $writer = new Xlsx($spreadsheet);
        $writer->save($file_path);

        // Verificar se o arquivo foi salvo corretamente
        if (!file_exists($file_path)) {
            abort(500, 'Erro ao salvar o arquivo.');
        }


        $response = response()->download($file_path);
        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
        return $response;


    }
}
