<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial Sanitario de {{ $animal->nombre }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/cssHistorialSanitario.css') }}">
</head>
<body>    
    <div class="container mt-5">
        <h2 class="title">Historial Sanitario de {{ $animal->nombre }}</h2>
        <div class="text-center mt-4 mb-5">
            <a href="{{ url()->previous() }}" class="btn btn-custom">← Volver</a>
        </div>  
        <div class="row justify-content-center">
            @foreach ($historial as $item)
            <div class="col-md-6 mb-4 d-flex justify-content-center">
                <div class="card">
                    <div class="heartbeatloader">
                        <svg class="svgdraw" width="100%" height="100%" viewBox="0 0 150 400" xmlns="http://www.w3.org/2000/svg">
                            <path class="path" d="M 0 200 l 40 0 l 5 -40 l 5 40 l 10 0 l 5 15 l 10 -140 l 10 220 l 5 -95 l 10 0 l 5 20 l 5 -20 l 30 0" fill="transparent" stroke-width="4" stroke="black"></path>
                        </svg>
                        <div class="innercircle"></div>
                        <div class="outercircle"></div>
                    </div>
                    <div class="card__image-wrapper">
                        <img src="{{ asset($item->animal->foto_animal) }}" width="100%" alt="Foto de {{ $item->animal->nombre }}">
                        <p class="fecha-aplicacion">{{ \Carbon\Carbon::parse($item->fecha_aplicacion)->format('d \d\e F \d\e Y') }}</p>
                    </div>
                    <div class="card__content">
                        <p class="card__title">Vacuna: {{ $item->nombre_vacuna }} / {{ $item->Vacuna->TipoVacuna->nombre }}</p>
                        <p class="card__description"><strong>Raza:</strong> {{ $item->animal->raza->nombre }}</p>
                        <p class="card__description"><strong>Fecha de aplicación:</strong> {{ $item->fecha_aplicacion }}</p>
                        <p class="card__description"><strong>Propietario:</strong> {{ $item->animal->propietario->nombres }}</p>                                                
                    </div>
                </div>
            </div>
            @endforeach
        </div>        
    </div>
</body>
</html>

