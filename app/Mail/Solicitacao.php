<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Solicitacao extends Mailable
{
    use Queueable, SerializesModels;

    public $solicitacao;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($solicitacao)
    {
        $this->solicitacao = $solicitacao;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(

            subject: 'Solicitacao Por QrCode',


        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'mail.solicitacao',
            with:[
                'solicitacao' => $this->solicitacao,
            ]

        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
