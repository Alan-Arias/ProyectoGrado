@extends('layout.layout')

@section('title', 'Registro de Cambios')

@section('content')
<style>
    body {
        background-color: #f8f9fa;
    }

    .card {
        border-radius: 1rem;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .table thead {
        background-color: #0d6efd;
        color: white;
    }

    .btn {
        width: 100%;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card p-4">
                <h2 class="text-center mb-4">Historial de Propietarios de <span class="text-primary">{{ $animal->nombre }}</span></h2>

                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Fecha de Cambio</th>
                                <th>Propietario Anterior</th>
                                <th>Propietario Nuevo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($historial as $cambio)
                                <tr>
                                    <td>{{ $cambio->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $cambio->propietarioAnterior ? $cambio->propietarioAnterior->nombres : 'Desconocido' }}</td>
                                    <td>{{ $cambio->propietarioNuevo ? $cambio->propietarioNuevo->nombres : 'Desconocido' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-grid mt-3">
                    <a href="{{ url('/Animales') }}" class="btn btn-secondary">Volver</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
