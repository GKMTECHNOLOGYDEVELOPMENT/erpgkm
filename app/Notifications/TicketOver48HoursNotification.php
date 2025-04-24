<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use App\Models\Ticket;
use Google\Service\AppHub\Channel;

class TicketOver48HoursNotification extends Notification
{
    protected $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    // Definimos el canal de la notificación, en este caso es un canal de WebSocket
    public function via($notifiable)
    {
        return ['broadcast'];
    }

    // Definir el canal de difusión (Broadcast Channel)
    public function broadcastOn()
    {
        return new Channel('tickets.' . $this->ticket->idTickets);
    }

    // Definir el mensaje a transmitir
    public function broadcastWith()
    {
        return [
            'ticket_id' => $this->ticket->idTickets,
            'numero_ticket' => $this->ticket->numero_ticket,
            'mensaje' => 'Este ticket tiene más de 48 horas de creación.',
        ];
    }
}
