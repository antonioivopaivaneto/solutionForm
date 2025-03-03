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
use Illuminate\Support\Facades\Mail;

class SendEmailQueueJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $send_mail;
    protected $solicitacao_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($send_mail,$solicitacao_id)
    {
        $this->send_mail = $send_mail;
        $this->solicitacao_id = $solicitacao_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $solicitacao = Solicitacao::with('condominio', 'unidade', 'fotos')->find($this->solicitacao_id);
        $email = new MailSolicitacao($solicitacao);
        Mail::to($this->send_mail)->cc('antonioivopaivaneto@gmail.com')->send($email);
    }
}
