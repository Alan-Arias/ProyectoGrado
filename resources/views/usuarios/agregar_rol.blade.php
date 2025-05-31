@extends('layout.layout')

@section('title', 'Añadir Rol')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card {
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-weight: 600;
        }

        .btn-success {
            transition: background-color 0.3s ease;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .formulario-rol {
            margin-top: 15px;
        }
    </style>
<div class="container py-5">
    <h2 class="text-center mb-4">Hacer Usuario del Sistema a <strong>{{ $personal->{'nombre completo'} }}</strong></h2>
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card p-4">
                <form action="{{ url('/Usuarios/GuardarRol', $personal->id) }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Rol detectado :</label>
                        <input type="text" class="form-control" value="{{ $personal->tipo_usuario }}" readonly>
                        <input type="hidden" name="rol" value="{{ $personal->tipo_usuario }}">
                    </div>
                    <div class="mb-3">
                        <label for="nombre_completo" class="form-label">Nombre de Usuario:</label>
                        <input class="form-control" type="text" name="nombre_completo" id="nombre_completo" placeholder="username" required>
                        <input type="hidden" name="id_personal" value="{{ $personal->id }}">
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input class="form-control" type="email" name="email" id="email" placeholder="alguien@algo.com" required>
                    </div>

                    <div class="mb-3">
                        <label for="contraseña" class="form-label">Contraseña:</label>
                        <input class="form-control" type="password" name="contraseña" id="contraseña" required minlength="5">
                    </div>
                    <button type="submit" class="btn btn-success w-100 mt-3">Guardar Rol</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const contraseñaInput = document.getElementById('contraseña');
        const mensaje = document.createElement('div');
        mensaje.style.color = 'red';
        mensaje.style.fontSize = '0.9em';
        contraseñaInput.parentNode.appendChild(mensaje);

        contraseñaInput.addEventListener('input', function () {
            if (contraseñaInput.value.length < 5) {
                mensaje.textContent = 'La contraseña debe tener al menos 5 caracteres.';
            } else {
                mensaje.textContent = '';
            }
        });
    });
</script>
@endsection