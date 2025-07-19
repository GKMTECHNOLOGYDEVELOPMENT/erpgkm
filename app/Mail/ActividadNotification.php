<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActividadNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $actividad;
    public $tipo;
    public $usuario;

    public function __construct($actividad, $tipo, $usuario = null)
    {
        $this->actividad = $actividad;
        $this->tipo = $tipo; // 'creacion', 'actualizacion', 'eliminacion'
        $this->usuario = $usuario;
    }

    public function build()
    {
        $subject = '';
        
        switch($this->tipo) {
            case 'creacion':
                $subject = 'Nueva actividad creada: ' . $this->actividad->titulo;
                break;
            case 'actualizacion':
                $subject = 'Actividad actualizada: ' . $this->actividad->titulo;
                break;
            case 'eliminacion':
                $subject = 'Actividad eliminada: ' . $this->actividad->titulo;
                break;
        }

        return $this->subject($subject)
                    ->view('emails.actividad-notification')
                    ->with([
                        'actividad' => $this->actividad,
                        'tipo' => $this->tipo,
                        'usuario' => $this->usuario
                    ]);
    }
}