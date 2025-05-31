<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modulo Animal</title>
</head>
<body>
@extends('layout.layout')

@section('title', 'Gestionar Historial Propietarios')

@section('content')
<div class="contenedor">
    <h2 class="text-center">Gestionar Cambio de Propietarios</h2>
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
        <div class="row">
            <div class="form-container">
                <div class="">        
                    @if (session('agregar'))
                        <div class="alert alert-success mt-3">
                            <p>{{ session('agregar') }}</p>
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success mt-3">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif
                    <div class="table-container table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Nombre del Animal</th>
                                    <th>Tipo Animal</th>
                                    <th>Raza</th>
                                    <th>Propietario Anterior</th>
                                    <th>Propietario Actual</th>
                                    <th>Foto</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($Animales as $animal)
                                    <tr class="position-relative {{ $animal->estado == 'Fallecido' ? 'fallecido-row' : '' }}">
                                        <td>{{ $animal->nombre }}</td>
                                        <td>{{ $animal->TipoAnimal->nombre }}</td>
                                        <td>{{ $animal->raza->nombre }}</td>
            
                                        {{-- Propietario anterior --}}
                                        <td>
                                            <span class="codigo-anterior">{{ $animal->ultimoCambio->codigo_propietario_anterior ?? 'Sin datos' }}</span>
                                            <br>
                                            <small class="nombre-anterior text-muted">
                                                {{ $animal->ultimoCambio->propietarioAnterior->nombres ?? 'Sin datos' }}
                                            </small>
                                        </td>

                                        {{-- Propietario actual --}}
                                        <td>
                                            <span class="codigo-actual">{{ $animal->propietario->codigo ?? 'Sin datos' }}</span>
                                            <br>
                                            <small class="nombre-actual text-muted">
                                                {{ $animal->propietario->nombres ?? 'Sin datos' }}
                                            </small>
                                        </td>

                                        {{-- Foto --}}
                                        <td>
                                            @if($animal->foto_animal)
                                                <img src="{{ asset($animal->foto_animal) }}" alt="Foto de {{ $animal->nombre }}"
                                                    width="100" class="thumbnail" onclick="openFullScreen('{{ asset($animal->foto_animal) }}')">
                                            @else
                                                No disponible
                                            @endif
                                        </td>

                                        {{-- Botón cambiar propietario --}}
                                        <td>
                                            <a href="{{ route('cambiar.propietario', $animal->id) }}" class="btn btn-warning btn-sm">Cambiar Propietario</a>
                                            <a href="{{ route('historial.propietario', $animal->id) }}" class="btn btn-info btn-sm">Historial de Propietarios</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
</div>
@endsection
<script>
    function toggleNombre(button) {
        const nombreDiv = button.nextElementSibling;
        if (nombreDiv.classList.contains('d-none')) {
            nombreDiv.classList.remove('d-none');
            button.textContent = 'Ocultar nombre';
        } else {
            nombreDiv.classList.add('d-none');
            button.textContent = 'Ver nombre';
        }
    }
</script>

</body>
</html>