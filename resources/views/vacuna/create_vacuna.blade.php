<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo Vacuna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

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

        .btn-success {
            width: 100%;
        }
    </style>
</head>
<body>
@extends('layout.layout')

@section('title', 'Gestionar Personal')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card p-4">
                <h3 class="card-title text-center mb-4">
                    Añadir nueva vacuna para: <br> <span class="text-primary">{{ $tipoVacuna->nombre }}</span>
                </h3>

                <form action="{{ route('vacuna.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tipo_vacuna_id" value="{{ $tipoVacuna->id }}">

                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre de la Vacuna</label>
                        <input type="text" class="form-control" name="nombre" id="nombre" required>
                    </div>

                    <button type="submit" class="btn btn-success">Guardar Vacuna</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
</body>
</html>
