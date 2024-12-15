<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Carbon\Carbon;

class ReservationCreated extends Notification
{
    use Queueable;

    protected $reservation;

    public function __construct($reservation)
    {
        $this->reservation = $reservation;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $reservation = $this->reservation;

        return (new MailMessage)
            ->subject('Reserva Confirmada')
            ->greeting('Hola, ' . $notifiable->name)
            ->line('Tu reserva para la pista ' . $reservation->court_number . ' ha sido confirmada.')
            ->line('Fecha: ' . $reservation->start_time->format('d-m-Y H:i'))
            ->line('Duración: ' . $reservation->duration_minutes . ' minutos')
            ->action('Ver Reserva', url('/reservations/' . $reservation->id))
            ->line('Gracias por usar nuestro servicio.');
    }

    public function toArray($notifiable)
    {
        return [
            'reservation_id' => $this->reservation->id,
            'court_number' => $this->reservation->court_number,
            'start_time' => $this->reservation->start_time,
        ];
    }
}
