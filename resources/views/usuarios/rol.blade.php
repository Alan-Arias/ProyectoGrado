<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Rol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .table thead {
            background-color: #0d6efd;
            color: white;
        }

        .table td, .table th {
            vertical-align: middle;
        }

        .btn-outline-primary {
            font-size: 0.9rem;
            padding: 0.3rem 0.6rem;
        }
    </style>
</head>
<body>
@extends('layout.layout')

@section('title', 'Gestionar Personal')

@section('content')

<div class="container py-5">
    <div class="card p-4">
        <h3 class="text-center mb-4">Gestión de Roles del Personal</h3>

        <!-- Barra de Búsqueda -->
        <form method="GET" action="{{ url('/Usuarios/BuscarRol') }}" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Buscar por nombre..." value="{{ request('search') }}">
                <button class="btn btn-primary" type="submit">Buscar</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th>Nombre Completo</th>
                        <th>Fecha Nacimiento</th>
                        <th>Nombre de Usuario</th>
                        <th>Email</th>
                        <th>Estado</th>
                        <th>Tipo de Usuario</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($Personal as $item)
                        <tr>
                            <td>{{ $item->{'nombre completo'} }}</td>
                            <td>{{ $item->fecha_nacimiento }}</td>
                            <td>{{ $item->usuariov2->nombre ?? 'Sin Datos' }}</td>
                            <td>{{ $item->usuariov2->email ?? 'Sin Datos' }}</td>
                            <td>
                                <form action="{{ url('/Usuarios/CambiarEstado', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <select name="estado" class="form-select form-select-sm" onchange="this.form.submit()">
                                        <option value="activo" {{ strtolower($item->estado) === 'activo' ? 'selected' : '' }}>Activo</option>
                                        <option value="inhabilitado" {{ strtolower($item->estado) !== 'activo' ? 'selected' : '' }}>Inhabilitado</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                @if($item->usuariov2 && $item->usuariov2->tipo_user)
                                    @php
                                        $tipo = $item->usuariov2->tipo_user;
                                        $clase = match($tipo) {
                                            'Administrador' => 'bg-primary',
                                            'Veterinario' => 'bg-success',
                                            'Secretaria' => 'bg-info text-dark',
                                            'Director General' => 'bg-warning text-dark',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $clase }}">{{ $tipo }}</span>
                                @else
                                    <a href="{{ url('/Usuarios/AgregarRol', $item->id) }}" class="btn btn-outline-primary">Añadir Rol</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- PAGINACIÓN PERSONALIZADA -->
        <div class="d-flex justify-content-center mt-3">
            <div class="custom-pagination">
                @if ($Personal->lastPage() > 1)
                    <ul class="pagination">
                        <!-- Anterior -->
                        <li class="page-item {{ ($Personal->currentPage() == 1) ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $Personal->appends(['search' => request('search')])->previousPageUrl() }}">&laquo;</a>
                        </li>

                        <!-- Página 1 -->
                        <li class="page-item {{ ($Personal->currentPage() == 1) ? 'active' : '' }}">
                            <a class="page-link" href="{{ $Personal->appends(['search' => request('search')])->url(1) }}">1</a>
                        </li>

                        @if ($Personal->currentPage() > 4)
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                        @endif

                        @for ($i = max(2, $Personal->currentPage() - 1); $i <= min($Personal->lastPage() - 1, $Personal->currentPage() + 1); $i++)
                            <li class="page-item {{ ($Personal->currentPage() == $i) ? 'active' : '' }}">
                                <a class="page-link" href="{{ $Personal->appends(['search' => request('search')])->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        @if ($Personal->currentPage() < $Personal->lastPage() - 3)
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                        @endif

                        <li class="page-item {{ ($Personal->currentPage() == $Personal->lastPage()) ? 'active' : '' }}">
                            <a class="page-link" href="{{ $Personal->appends(['search' => request('search')])->url($Personal->lastPage()) }}">{{ $Personal->lastPage() }}</a>
                        </li>

                        <!-- Siguiente -->
                        <li class="page-item {{ ($Personal->currentPage() == $Personal->lastPage()) ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $Personal->appends(['search' => request('search')])->nextPageUrl() }}">&raquo;</a>
                        </li>
                    </ul>
                @endif
            </div>
        </div>

    </div>
</div>

@endsection
</body>
</html>
