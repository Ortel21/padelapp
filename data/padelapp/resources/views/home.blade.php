@extends('layouts.app')

@section('content')
    <div class="container">
        <!-- Header con opciones de contacto y mis reservas -->
        <div class="d-flex justify-content-between">
            <div>
                <a href="{{ route('contact') }}" class="text-blue-500 hover:underline">Contacto</a> |
                <a href="{{ route('my-reservations') }}" class="text-blue-500 hover:underline">Mis Reservas</a>
            </div>
            <div>
                <span>Bienvenido, {{ Auth::user()->name }}</span> |
                <a href="{{ route('profile.edit') }}" class="text-blue-500 hover:underline">Perfil</a> |
                <a href="{{ route('logout') }}" class="text-blue-500 hover:underline" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>

        <hr>

        <h1>Reserva tu pista</h1>

        <!-- Mensaje de éxito -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Formulario para hacer una nueva reserva -->
        <form action="{{ route('reserve.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="court_number">Selecciona el número de la pista:</label>
                <input type="number" name="court_number" id="court_number" class="form-control" min="1" max="10" required>
            </div>

            <div class="form-group">
                <label for="reservation_date">Fecha y hora de la reserva:</label>
                <input type="datetime-local" name="reservation_date" id="reservation_date" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="duration">Duración (horas):</label>
                <input type="number" name="duration" id="duration" class="form-control" min="1" max="2" required>
            </div>

            <button type="submit" class="btn btn-primary">Hacer reserva</button>
        </form>

        <hr>

        <!-- Mostrar las reservas disponibles -->
        <h3>Reservas disponibles</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Número de Pista</th>
                    <th>Fecha y Hora</th>
                    <th>Duración (horas)</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reservations as $reservation)
                    <tr>
                        <td>{{ $reservation->court_number }}</td>
                        <td>{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d/m/Y H:i') }}</td>
                        <td>{{ $reservation->duration }}</td>
                        <td>{{ ucfirst($reservation->status) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white text-center py-4 mt-4">
        <p>2024 © Todos los derechos reservados</p>
        <a href="{{ route('terms') }}" class="text-white hover:underline">Condiciones de uso</a> | 
        <a href="{{ route('privacy') }}" class="text-white hover:underline">Política de privacidad</a>
    </footer>
@endsection
