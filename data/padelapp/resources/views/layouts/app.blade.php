<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PADEL SH - Reservas de Pistas de Pádel</title>
    <link href="{{ asset('css/layout.css') }}" rel="stylesheet">
</head>

<body>

    <!-- Navbar principal -->
    <nav class="navbar">
        <div class="container-fluid">
            <!-- Logo y nombre de la app centrados verticalmente -->
            <div class="navbar-top d-flex align-items-center justify-content-between">
                <div class="navbar-left d-flex align-items-center">
                    <img src="/img/padelsh_logo.webp" alt="Logo Padel SH" class="logo">
                    <div class="app-name-container">
                        <span class="app-name">PADEL SANTIAGO HERNÁNDEZ</span>
                    </div>
                </div>
                <div class="navbar-user d-flex flex-column align-items-end">
                    <span class="user-name">{{ Auth::user()->name }} {{ Auth::user()->surname }}</span>
                    <br>
                    <div class="navbar-bottom d-flex flex-column align-items-end mt-2">
                        <a href="{{ route('logout') }}" class="logout-btn" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Cerrar sesión</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Menú de navegación inferior -->
    <div class="submenu">
        <ul class="submenu-list">
            <li><a href="{{ route('home') }}">Inicio</a></li>
            <li><a href=#>Contacto</a></li>
        </ul>
    </div>

    <!-- Contenido principal -->
    <div class="container mt-4">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>2024 © Todos los derechos reservados</p>
        <a href=#>Condiciones de uso</a> |
        <a href=#>Política de privacidad</a>
    </footer>

</body>

</html>