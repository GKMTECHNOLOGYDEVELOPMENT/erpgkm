<?php
// app/Mail/CredencialesWebMail.php

namespace App\Mail;

use App\Models\Usuario;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CredencialesWebMail extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;
    public $correoAcceso;
    public $password;

    public function __construct(Usuario $usuario, $correoAcceso, $password)
    {
        $this->usuario = $usuario;
        $this->correoAcceso = $correoAcceso;
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject('🔐 Credenciales de Acceso - Plataforma Web')
                    ->view('emails.credenciales-web');
    }
}