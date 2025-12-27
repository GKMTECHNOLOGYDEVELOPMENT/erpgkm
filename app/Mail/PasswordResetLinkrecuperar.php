<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Usuario;

class PasswordResetLinkrecuperar extends Mailable
{
    use Queueable, SerializesModels;

    public $resetUrl;
    public $usuario;

    public function __construct($resetUrl, Usuario $usuario)
    {
        $this->resetUrl = $resetUrl;
        $this->usuario = $usuario;
    }

    public function build()
    {
        return $this->subject('Restablecer Contraseña - ' . config('app.name'))
                    ->view('emails.password-reset-recuperacion')
                    ->with([
                        'nombre' => $this->usuario->Nombre,
                        'resetUrl' => $this->resetUrl,
                        'empresa' => config('app.name')
                    ]);
    }
}