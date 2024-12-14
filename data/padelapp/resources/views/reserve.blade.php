@extends('layouts.app')

@section('content')
<div class="container">
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

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('reserve.store') }}" method="POST">
        @csrf

        <!-- Campo oculto para el número de pista -->
        <input type="hidden" name="court_number" value="{{ request()->input('court') }}" />

        <div class="form-group">
            <label for="reservation_date">Fecha de la reserva:</label>
            <input type="date" name="reservation_date" id="reservation_date" class="form-control" value="{{ old('reservation_date', \Carbon\Carbon::parse(request()->get('start'))->toDateString()) }}" required readonly>
        </div>

        <div class="form-group">
            <label for="duration">Duración (horas):</label>
            <select name="duration" id="duration" class="form-control" required>
                <option value="60" {{ request()->input('duration') == 60 ? 'selected' : '' }}>1:00</option>
                <option value="90" {{ request()->input('duration') == 90 ? 'selected' : '' }}>1:30</option>
                <option value="120" {{ request()->input('duration') == 120 ? 'selected' : '' }}>2:00</option>
            </select>
        </div>

        <div class="form-group">
            <label for="time_slot">Hora de la reserva:</label>
            <input type="text" name="time_slot" id="time_slot" class="form-control" value="{{ \Carbon\Carbon::parse(request()->get('start'))->format('H:i') }} - {{ \Carbon\Carbon::parse(request()->get('end'))->format('H:i') }}" required readonly>
        </div>

        <button type="submit" class="btn btn-primary">Hacer reserva</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const timeSlot = document.getElementById('time_slot');
        const duration = document.getElementById('duration');
        const startTime = new Date("{{ request()->input('start') }}");
        const endTime = new Date("{{ request()->input('end') }}");

        function updateEndTime() {
            const selectedDuration = parseInt(duration.value);
            const newEndTime = new Date(startTime);
            newEndTime.setMinutes(newEndTime.getMinutes() + selectedDuration);

            timeSlot.value = `${newEndTime.getHours().toString().padStart(2, '0')}:${newEndTime.getMinutes().toString().padStart(2, '0')} - ${endTime.getHours().toString().padStart(2, '0')}:${endTime.getMinutes().toString().padStart(2, '0')}`;
        }

        duration.addEventListener('change', updateEndTime);
    });
</script>

@endsection