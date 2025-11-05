<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificarEmailMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * A URL de verificação que será usada no botão do e-mail.
     *
     * @var string
     */
    public $verificationUrl;

    /**
     * Cria uma nova instância da mensagem.
     *
     * @param string $verificationUrl
     */
    public function __construct(string $verificationUrl)
    {
        $this->verificationUrl = $verificationUrl;
    }

    /**
     * Constrói a mensagem.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Verifique seu Endereço de E-mail')
            ->view('emails.verificacao-email');
    }
}
