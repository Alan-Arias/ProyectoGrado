@extends('layout.layout')

@section('title', 'Cambiar Propietario')

@section('content')
<style>
    body {
        background-color: #f8f9fa;
    }

    .card {
        border-radius: 1rem;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .card-title {
        font-size: 1.5rem;
        font-weight: bold;
    }

    .btn {
        width: 100%;
    }

    .btn-secondary {
        background-color: #6c757d;
        border: none;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card p-4">
                <h2 class="card-title text-center mb-4">Cambiar Propietario de <br><span class="text-primary">{{ $animal->nombre }}</span></h2>

                <form action="{{ route('actualizar.propietario', $animal->id) }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Propietario Actual:</label>
                        <input type="text" class="form-control" value="{{ $animal->propietario->nombres }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label for="codigo_propietario_nuevo" class="form-label">Nuevo Propietario:</label>
                        <select class="form-control" name="codigo_propietario_nuevo" required>
                            <option value="">Seleccione un propietario</option>
                            @foreach($propietarios as $propietario)
                                <option value="{{ $propietario->codigo }}">{{ $propietario->nombres }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success">Guardar Cambio</button>
                        <a href="{{ url('/Animales') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
