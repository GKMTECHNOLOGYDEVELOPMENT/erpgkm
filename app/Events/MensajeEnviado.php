<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class MensajeEnviado implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $mensaje;

    public function __construct($mensaje)
    {
        $this->mensaje = $mensaje;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('chat');
    }

    public function broadcastAs()
    {
        return 'mensaje.enviado';
    }
}
