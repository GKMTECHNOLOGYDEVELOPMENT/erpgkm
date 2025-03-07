<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UsuarioCreado extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;
    public $clave;

    /**
     * Crear una nueva instancia de mensaje.
     *
     * @param string $usuario
     * @param string $clave
     * @return void
     */
    public function __construct($usuario, $clave)
    {
        $this->usuario = $usuario;
        $this->clave = $clave;
    }

    /**
     * Construir el mensaje.
     *
     * @return \Illuminate\Contracts\Mail\Mailable
     */
    public function build()
    {
        return $this->subject('Detalles de tu cuenta')
                    ->view('emails.usuario_creado');
    }
}
