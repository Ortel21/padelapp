@extends('layouts.app')

@section('content')
<link href="{{ asset('css/reserve.css') }}" rel="stylesheet">

<div class="container">

    <h1>Reserva tu pista</h1>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('reserve.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="court_number">Número de Pista:</label>
            <input type="text" name="court_number" value="{{ request()->input('court') }}" required readonly/>
        </div>

        <div class="form-group">
            <label for="reservation_date">Fecha de la reserva:</label>
            <input type="date" name="reservation_date" id="reservation_date" class="form-control" value="{{ old('reservation_date', \Carbon\Carbon::parse(request()->get('start'))->toDateString()) }}" required readonly>
        </div>

        <div class="form-group">
            <label for="duration">Duración (horas):</label>
            <select name="duration" id="duration" class="form-control" required>
                <option value="60" {{ request()->input('duration') == 60 ? 'selected' : '' }}>1:00</option>
                <option value="90" {{ request()->input('duration') == 90 ? 'selected' : '' }}>1:30</option>
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

        const startTimeLocal = new Date(startTime.getTime() + startTime.getTimezoneOffset() * 60000);
        const endTimeLocal = new Date(endTime.getTime() + endTime.getTimezoneOffset() * 60000);

        const timeDiff = (endTimeLocal - startTimeLocal) / 60000;
        let defaultDuration = 60;

        if (timeDiff === 90) {
            defaultDuration = 90;
        }

        duration.value = defaultDuration;

        function updateEndTime() {
            const selectedDuration = parseInt(duration.value);
            const newEndTime = new Date(startTimeLocal);
            newEndTime.setMinutes(newEndTime.getMinutes() + selectedDuration);

            timeSlot.value = `${startTimeLocal.getHours().toString().padStart(2, '0')}:${startTimeLocal.getMinutes().toString().padStart(2, '0')} - ${newEndTime.getHours().toString().padStart(2, '0')}:${newEndTime.getMinutes().toString().padStart(2, '0')}`;
        }

        updateEndTime();

        duration.addEventListener('change', updateEndTime);
    });
</script>


@endsection