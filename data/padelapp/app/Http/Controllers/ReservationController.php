<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function showReservationForm(Request $request)
    {
        $courtId = $request->input('court');
        $startTime = \Carbon\Carbon::parse($request->input('start'))->setTimezone('Europe/Madrid');
        $endTime = \Carbon\Carbon::parse($request->input('end'))->setTimezone('Europe/Madrid');
        $durationMinutes = $startTime->diffInMinutes($endTime);

        // session(['durationMinutes' => $durationMinutes]);

        return view('reserve')->with([
            'courtId' => $courtId,
            'startTime' => $startTime,
            'endTime' => $endTime,
            'durationMinutes' => $durationMinutes,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'court_number' => 'required|in:1,2',
            'reservation_date' => 'required|date',
            'time_slot' => 'required',
            'duration' => 'required|integer',
        ]);

        $startTime = new \DateTime($request->reservation_date . ' ' . explode(' - ', $request->time_slot)[0]);
        $durationMinutes = $request->duration;

        Reservation::create([
            'user_id' => Auth::id(),
            'court_number' => $request->court_number,
            'start_time' => $startTime,
            'duration_minutes' => $durationMinutes,
            'status' => 'free',
        ]);

        return redirect()->route('my-reservations')->with('success', 'Reserva realizada con éxito.');
    }
}
