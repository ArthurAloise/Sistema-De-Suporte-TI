<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChamadoPendenteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $chamado;

    public function __construct(Ticket $chamado)
    {
        $this->chamado = $chamado;
    }

    public function build()
    {
        return $this->subject('Chamado marcado como Pendente')
            ->view('emails.chamado-pendente');
    }
}
