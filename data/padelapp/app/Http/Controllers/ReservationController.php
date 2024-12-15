<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ReservationCreated;
use App\Jobs\SendReservationReminderJob;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Bus\Dispatchable;

class ReservationController extends Controller
{
    public function showReservationForm(Request $request)
    {
        $courtId = $request->input('court');
        $startTime = \Carbon\Carbon::parse($request->input('start'));
        $endTime = \Carbon\Carbon::parse($request->input('end'));
        $durationMinutes = $startTime->diffInMinutes($endTime);

        return view('reserve')->with([
            'courtId' => $courtId,
            'startTime' => $startTime,
            'endTime' => $endTime,
            'durationMinutes' => $durationMinutes,
        ]);
    }

    public function fetchReservations(Request $request)
    {
        $courtNumber = $request->input('court_number');
        $date = $request->input('date');

        $reservations = Reservation::where('court_number', $courtNumber)
            ->whereDate('start_time', $date)
            ->get(['start_time', 'duration_minutes']);

        return response()->json($reservations);
    }

    public function store(Request $request)
    {
        $request->validate([
            'court_number' => 'required|in:1,2',
            'reservation_date' => 'required|date',
            'time_slot' => 'required',
            'duration' => 'required|integer',
        ]);

        $startTime = \Carbon\Carbon::createFromFormat(
            'Y-m-d H:i',
            $request->reservation_date . ' ' . explode(' - ', $request->time_slot)[0]
        );

        $durationMinutes = $request->duration;

        $reservation = Reservation::create([
            'user_id' => Auth::id(),
            'court_number' => $request->court_number,
            'start_time' => $startTime->format('Y-m-d H:i:s'),
            'duration_minutes' => intval($durationMinutes),
            'status' => 'filled',
        ]);

        Notification::send(Auth::user(), new ReservationCreated($reservation));

        // $reminderTime = Carbon::parse($reservation->start_time)->subMinutes(22);

        // $this->dispatch(new SendReservationReminderJob($reservation))->delay($reminderTime);

        return redirect()->route('home')->with('success', 'Reserva realizada con éxito.');
    }
}
