<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TecnicoAlteradoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $chamado;

    public function __construct($chamado)
    {
        $this->chamado = $chamado;
    }

    public function build()
    {
        return $this->subject('Novo chamado atribuído a você')
            ->view('emails.tecnico-alterado');
    }
}

