<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use App\Notifications\ReservationReminder;
use Carbon\Carbon;

class SendReservationReminder extends Command
{
    protected $signature = 'reservation:send-reminder';

    protected $description = 'Envía un recordatorio 20 minutos antes de la reserva';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $now = Carbon::now();
        $reminderTime = $now->addMinutes(20);

        $reservations = Reservation::where('start_time', $reminderTime)
                                    ->get();

        foreach ($reservations as $reservation) {
            $reservation->user->notify(new ReservationReminder($reservation));
        }

        $this->info('Recordatorio de reservas enviado correctamente.');
    }
}

