<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $reservations = Reservation::where('status', 'available')->get();

        return view('home', compact('reservations'));
    }

    public function storeReservation(Request $request)
    {
        $validated = $request->validate([
            'court_number' => 'required|integer|min:1|max:10',
            'reservation_date' => 'required|date|after:today',
            'duration' => 'required|integer|min:1|max:2',
        ]);

        Reservation::create([
            'user_id' => Auth::id(),
            'court_number' => $validated['court_number'],
            'reservation_date' => $validated['reservation_date'],
            'duration' => $validated['duration'],
            'status' => 'active',
        ]);

        return redirect()->route('home')->with('success', 'Reserva realizada correctamente');
    }
}
