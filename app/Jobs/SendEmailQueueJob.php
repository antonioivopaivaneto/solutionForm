<?php

namespace App\Jobs;

use App\Mail\Solicitacao as MailSolicitacao;
use App\Models\Solicitacao;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmailQueueJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

   public $emails;
public $solicitacao_id;

public function __construct($emails, $solicitacao_id)
{
    $this->emails = is_array($emails) ? $emails : [$emails];
    $this->solicitacao_id = $solicitacao_id;
}
   public function handle()
{
    Log::info('JOB INICIADO');

    $solicitacao = Solicitacao::with('condominio', 'unidade')
        ->find($this->solicitacao_id);

    if (!$solicitacao) {
        Log::warning("Solicitação não encontrada: {$this->solicitacao_id}");
        return;
    }

   foreach ($this->emails as $email) {
    try {
        Mail::to($email)->send(new MailSolicitacao($solicitacao));
    } catch (\Exception $e) {
        Log::error("Erro ao enviar para {$email}: " . $e->getMessage());
    }
}

    Log::info('EMAIL ENVIADO');
}

}
