<?php

namespace App\Jobs;

use App\Models\Reservation;
use App\Notifications\ReservationReminder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendReservationReminderJob implements ShouldQueue
{
    use Queueable;

    protected $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function handle()
    {
        $reservation = $this->reservation;

        Notification::send($reservation->user, new ReservationReminder($reservation));
    }
}
