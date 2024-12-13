<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PADEL SH - Reservas de Pistas de Pádel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">PADEL SH</a>
            <div class="d-flex">
                <a href="{{ route('contact') }}" class="text-blue-500 hover:underline">Contacto</a> |
                <a href="{{ route('my-reservations') }}" class="text-blue-500 hover:underline">Mis Reservas</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @yield('content')  <!-- Aquí se insertará el contenido de cada vista -->
    </div>

    <footer class="bg-dark text-white text-center py-4 mt-4">
        <p>2024 © Todos los derechos reservados</p>
        <a href="{{ route('terms') }}" class="text-white hover:underline">Condiciones de uso</a> |
        <a href="{{ route('privacy') }}" class="text-white hover:underline">Política de privacidad</a>
    </footer>

</body>

</html>
