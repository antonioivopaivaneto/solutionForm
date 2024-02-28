<?php

namespace App\Jobs;

use App\Mail\Solicitacao as MailSolicitacao;
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
    protected $solicitacao;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($send_mail,$solicitacao)
    {
        $this->send_mail = $send_mail;
        $this->solicitacao = $solicitacao;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new MailSolicitacao($this->solicitacao);
        Mail::to($this->send_mail )->cc('antonioivopaivaneto@gmail.com')->send($email);
    }
}
