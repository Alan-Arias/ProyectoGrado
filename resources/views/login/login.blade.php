<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión - Unidad de Zoonosis</title>
    <link rel="stylesheet" href="{{ asset('css/cssLogin.css') }}">
</head>
<body>
    <div class="login-container">
        <form class="login-form" action="{{ url('/authenticate') }}" method="POST">
            @csrf
            <p class="heading">Inicio de Sesión</p>
            <p class="paragraph">Inicia sesión en tu cuenta</p>
            <div class="input-group">
                <input required placeholder="Email" id="email" name="email" type="text" />
            </div>
            <div class="input-group">
                <input required placeholder="Contraseña" name="password" id="password" type="password" />
            </div>
            <button type="submit">Iniciar Sesión</button>
            <div class="bottom-text">                
                <p><a href="#">¿Olvidaste tu contraseña?</a></p>
            </div>
        </form>
        @if (session('error'))
            <div class="alert">
                {{ session('error') }}
            </div>
        @endif
    </div>
</body>
</html>
