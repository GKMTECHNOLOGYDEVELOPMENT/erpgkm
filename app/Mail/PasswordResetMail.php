<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $email;

    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

   public function build()
{
    return $this->subject('Restablecer tu contraseña - Solutions Force')
                ->view('emails.password-reset')
                ->with([
                    'token' => $this->token,
                    'email' => $this->email,
                    'resetUrl' => route('password.reset', $this->token) // ← Usa 'password.reset' con token
                ]);
}
}