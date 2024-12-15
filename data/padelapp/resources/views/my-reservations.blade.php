@extends('layouts.app')

@section('content')
<link href="{{ asset('css/my_reservations.css') }}" rel="stylesheet">

<div class="container">
    <h1 class="page-title">Mis Reservas</h1>

    <div class="filter-calendar">
        <form action="{{ route('reservations.my') }}" method="GET" class="filter-form">
            <label for="filter_date">Filtrar por fecha:</label>
            <input type="date" id="filter_date" name="filter_date" value="{{ request('filter_date', now()->toDateString()) }}">
            <button type="submit" class="btn btn-primary">Buscar</button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Pista</th>
                    <th>Duración</th>
                    <th>Estado</th>
                    @if(auth()->user()->role === 'admin')
                        <th>Acciones</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($reservations as $reservation)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($reservation->start_time)->format('d/m/Y') }}</td>

                    <td>{{ $reservation->court_number }}</td>

                    <td>
                        {{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }} - 
                        {{ \Carbon\Carbon::parse($reservation->start_time)->addMinutes($reservation->duration_minutes)->format('H:i') }}
                    </td>

                    <td class="{{ \Carbon\Carbon::parse($reservation->start_time)->isPast() ? 'status-finished' : (\Carbon\Carbon::parse($reservation->start_time)->isFuture() ? 'status-active' : 'status-active') }}">
                        {{ \Carbon\Carbon::parse($reservation->start_time)->isPast() ? 'Finalizada' : (\Carbon\Carbon::parse($reservation->start_time)->isFuture() ? 'Activa' : 'Activa') }}
                    </td>

                    @if(auth()->user()->role === 'admin')
                    <td>
                        @if ($reservation->active && !\Carbon\Carbon::parse($reservation->start_time)->isPast())
                        <form action="{{ route('reservations.destroy', $reservation->id) }}" method="POST" class="action-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Cancelar</button>
                        </form>
                        @else
                        <span>-</span>
                        @endif
                    </td>
                    @else
                    <td><span>-</span></td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if ($reservations->isEmpty())
    <p class="text-muted">No se encontraron reservas para la fecha seleccionada.</p>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        const filterInput = document.getElementById('filter_date');

        if (!filterInput.value) {
            filterInput.value = today;
        }
    });
</script>
@endsection
