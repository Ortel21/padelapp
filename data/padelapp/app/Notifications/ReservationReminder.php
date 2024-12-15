<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ReservationReminder extends Notification
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
            ->subject('Recordatorio de tu Reserva')
            ->greeting('Hola, ' . $notifiable->name)
            ->line('Este es un recordatorio de tu reserva en la pista ' . $reservation->court_number . '.')
            ->line('La reserva está programada para las ' . $reservation->start_time->format('d-m-Y H:i'))
            ->line('¡Gracias por usar nuestro servicio!');
    }
}
