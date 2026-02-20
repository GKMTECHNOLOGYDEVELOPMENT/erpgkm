<?php
// app/Mail/NotificacionGerenciaMail.php

namespace App\Mail;

use App\Models\Usuario;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificacionGerenciaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;
    public $accesoWeb;
    public $accesoApp;
    public $admin;

    public function __construct(Usuario $usuario, $accesoWeb, $accesoApp, $admin)
    {
        $this->usuario = $usuario;
        $this->accesoWeb = $accesoWeb;
        $this->accesoApp = $accesoApp;
        $this->admin = $admin;
    }

    public function build()
    {
        return $this->subject('📋 Notificación: Credenciales Generadas - ' . $this->usuario->Nombre)
                    ->view('emails.notificacion-gerencia');
    }
}