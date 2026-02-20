<?php
// app/Mail/CredencialesAppMail.php

namespace App\Mail;

use App\Models\Usuario;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CredencialesAppMail extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;
    public $usuarioApp;
    public $password;

    public function __construct(Usuario $usuario, $usuarioApp, $password)
    {
        $this->usuario = $usuario;
        $this->usuarioApp = $usuarioApp;
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject('📱 Credenciales de Acceso - Aplicación Móvil')
                    ->view('emails.credenciales-app');
    }
}