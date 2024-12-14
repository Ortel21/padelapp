@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Disponibilidad de la Pista {{ $court->number }}</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reservations as $reservation)
            <tr>
                <td>{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('H:i') }}</td>
                <td>{{ ucfirst($reservation->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
