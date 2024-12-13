<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    // Método para mostrar la vista de inicio
    public function index()
    {
        // Recuperamos las pistas disponibles
        // Aquí, supongo que quieres mostrar una lista de todas las reservas activas o disponibles.
        $reservations = Reservation::where('status', 'available')->get();

        // Retornamos la vista con las reservas disponibles
        return view('home', compact('reservations'));
    }

    // Método para crear una nueva reserva
    public function storeReservation(Request $request)
    {
        // Validación de los datos recibidos
        $validated = $request->validate([
            'court_number' => 'required|integer|min:1|max:10', // Ejemplo, 10 pistas disponibles
            'reservation_date' => 'required|date|after:today', // Fecha posterior a hoy
            'duration' => 'required|integer|min:1|max:2', // Duración máxima de 2 horas
        ]);

        // Crear una nueva reserva en la base de datos
        Reservation::create([
            'user_id' => Auth::id(), // Asociamos la reserva al usuario logueado
            'court_number' => $validated['court_number'],
            'reservation_date' => $validated['reservation_date'],
            'duration' => $validated['duration'],
            'status' => 'active', // La reserva se activa de inmediato
        ]);

        // Redirigir al usuario a la página principal con un mensaje de éxito
        return redirect()->route('home')->with('success', 'Reserva realizada correctamente');
    }
}
