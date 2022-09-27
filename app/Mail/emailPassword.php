<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class emailPassword extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $senha_md5)
    {
        $this->data = $data;
        $this->senha = $senha_md5;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   
        $this->subject('Bem Vindo(a), Sua Senha para acessar!');
        return $this->markdown('mail.emailWelcome')->with(['data' => $this->data, 'senha' => $this->senha  ]);
    }
}
