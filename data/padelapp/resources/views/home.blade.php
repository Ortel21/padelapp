@extends('layouts.app')

@section('content')
<link href="{{ asset('css/home.css') }}" rel="stylesheet">
<div class="home-page container">
    <h1 class="page-title">BIENVENIDO A PÁDEL SH, RESERVA TU PISTA</h1>

    <!-- Mensaje de éxito -->
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <!-- Botones de días de la semana -->
    <div id="day-buttons" class="day-buttons">
        <button class="day-btn" data-day="0">Sábado</button>
        <button class="day-btn" data-day="1">Domingo</button>
        <button class="day-btn" data-day="2">Lunes</button>
        <button class="day-btn" data-day="3">Martes</button>
        <button class="day-btn" data-day="4">Miércoles</button>
        <button class="day-btn" data-day="5">Jueves</button>
        <button class="day-btn" data-day="6">Viernes</button>
    </div>

    <div id="week-selector">
        <button id="prev-week-btn">Anterior</button>
        <span id="current-week"></span>
        <button id="next-week-btn">Siguiente</button>
    </div>
    
    <!-- Contenedor de reservas -->
    <div id="reserves-container" class="cpt-1">
        <div id="dayheader" class="panel panel-default">
            <div class="titlefont" id="current-day-header"></div>
            <div id="day" class="day-cal_week table-responsive table-top-level">
                <!-- Pista 1 -->
                <div class="r-court">
                    <div class="text-center">
                        <h5 class="court title text-truncate" title="PISTA 1">
                            <small class="badge badge-green hidden" id="current-badge-1"></small> PISTA 1
                        </h5>
                    </div>
                    <div class="court_reserves">
                        <div class="inner_court_reserves" id="time-slots-1">
                        </div>
                    </div>
                </div>
                <!-- Fin de Pista 1 -->
                <!-- Pista 2 -->
                <div class="r-court">
                    <div class="text-center">
                        <h5 class="court title text-truncate" title="PISTA 2">
                            <small class="badge badge-green hidden" id="current-badge-2"></small> PISTA 2
                        </h5>
                    </div>
                    <div class="court_reserves">
                        <div class="inner_court_reserves" id="time-slots-2">
                        </div>
                    </div>
                </div>
                <!-- Fin de Pista 2 -->
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/home.js') }}"></script>
@endsection
