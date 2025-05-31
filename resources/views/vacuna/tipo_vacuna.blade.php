<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Tipo Vacuna</title>
</head>
<body>
@extends('layout.layout')

@section('title', 'Gestionar Tipo Vacuna')

@section('content')

<div class="contenedor">
    <h2 class="text-center">Gestionar Tipo Vacunas</h2>
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
    <div class="row">
        <div>
            <nav class="navbar navbar-expand-lg" style="background-color:rgb(45, 54, 42);">
                <div class="container-fluid">
                    <a class="navbar-brand text-success fw-bold" href="#">Panel de Navegacion</a>
                    <div class="justify-content-between" id="panelNav">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ url('/Vacunas') }}">Gestionar Vacunas</a>
                            </li>                        
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
        <!-- Formulario al lado izquierdo -->
        <div class="col-md-6 mx-auto">
            <div class="form-container">
                <form action="{{ url('/Vacunas/RegistrarTipos') }}" method="post" class="form-table"> 
                    @csrf
                    <h3 class="text-center mb-4">Registrar Tipo Vacuna</h3>            
                    <div class="mb-3">
                        <label for="tipo" class="form-label">Tipo de Vacuna</label>
                        <div id="razas-container">
                            <input class="form-control" type="text" name="nombre" placeholder="Indique un nombre" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="fabrica" class="form-label">Fábrica</label>
                        <input class="form-control" type="text" name="fabrica" id="fabrica" placeholder="Nombre de la Fábrica" required>
                    </div>
                    <div class="mb-3">
                        <label for="lote" class="form-label">Nro. de Lote</label>
                        <input class="form-control" type="text" name="lote" id="lote" placeholder="Numero de Lote" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-success">Guardar</button>
                    </div>
                </form>
                @if (session('agregar'))
                    <div class="alert alert-success mt-3">
                        <p>{{ session('agregar') }}</p>
                    </div>
                @endif
            </div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <!-- Tabla al lado derecho -->
        <div class="col-md-6">
            <div class="table-container table-responsive">
                <!-- Contenedor del Filtro -->
                <div class="mb-3 position-relative">
                    <button id="filterButton" class="btn btn-primary btn-sm" onclick="toggleFilter()">
                        <i class="fas fa-filter"></i> Filtros
                    </button>

                    <!-- Selector de Paginación (oculto por defecto) -->
                    <div id="filterSelect" class="dropdown-menu" style="display: none; position: absolute; top: 100%; left: 0;">
                        <form method="GET" id="paginationForm" class="p-2">
                            <label for="perPage" class="mr-2">Mostrar:</label>
                            <select name="perPage" id="perPage" class="form-control" onchange="document.getElementById('paginationForm').submit()">                                
                                <option value="4" {{ request('perPage') == 4 ? 'selected' : '' }}>4</option>
                                <option value="5" {{ request('perPage') == 5 ? 'selected' : '' }}>5</option>
                                <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>                                
                            </select>
                        </form>
                    </div>
                </div>
            <!-- Script para mostrar/ocultar el selector -->
            <script>
            function toggleFilter() {
                var filterSelect = document.getElementById("filterSelect");
                if (filterSelect.style.display === "none") {
                    filterSelect.style.display = "block";
                } else {
                    filterSelect.style.display = "none";
                }
            }
            </script>
            <!-- Estilo para el dropdown -->
            <style>
            .dropdown-menu {
                border: 1px solid #ccc;
                background-color: white;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                z-index: 1000; /* Asegurarse de que esté encima de otros elementos */
            }
            </style>
                <table class="table table-striped table-bordered table-light">
                    <thead>
                        <tr>
                            <th>Tipo de Vacuna</th>                            
                            <th>Nombre de la Fábrica</th>
                            <th>Numero de Lote</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($TipoVacuna as $item)
                            <tr>
                                <td>{{ $item->nombre }}</td>                                
                                <td>{{ $item->fabrica }}</td>
                                <td>{{ $item->nro_lote }}</td>
                                <td>
                                    <a href="{{ route('vacuna.create', ['tipo_vacuna_id' => $item->id]) }}" class="btn btn-sm btn-primary">
                                        Añadir Vacuna
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                <div class="custom-pagination">
                    @if ($TipoVacuna->lastPage() > 1)
                        <ul class="pagination">
                            {{-- Botón "Anterior" --}}
                            <li class="page-item {{ ($TipoVacuna->currentPage() == 1) ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $TipoVacuna->previousPageUrl() }}" aria-label="Anterior">&laquo;</a>
                            </li>

                            {{-- Primera página siempre visible --}}
                            <li class="page-item {{ ($TipoVacuna->currentPage() == 1) ? 'active' : '' }}">
                                <a class="page-link" href="{{ $TipoVacuna->url(1) }}">1</a>
                            </li>

                            {{-- "..." si la página actual está lejos del inicio --}}
                            @if ($TipoVacuna->currentPage() > 4)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif

                            {{-- Página actual y una antes/después --}}
                            @for ($i = max(2, $TipoVacuna->currentPage() - 1); $i <= min($TipoVacuna->lastPage() - 1, $TipoVacuna->currentPage() + 1); $i++)
                                <li class="page-item {{ ($TipoVacuna->currentPage() == $i) ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $TipoVacuna->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor

                            {{-- "..." si la página actual está lejos del final --}}
                            @if ($TipoVacuna->currentPage() < $TipoVacuna->lastPage() - 3)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif

                            {{-- Última página siempre visible --}}
                            @if ($TipoVacuna->lastPage() > 1)
                                <li class="page-item {{ ($TipoVacuna->currentPage() == $TipoVacuna->lastPage()) ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $TipoVacuna->url($TipoVacuna->lastPage()) }}">{{ $TipoVacuna->lastPage() }}</a>
                                </li>
                            @endif

                            {{-- Botón "Siguiente" --}}
                            <li class="page-item {{ ($TipoVacuna->currentPage() == $TipoVacuna->lastPage()) ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $TipoVacuna->nextPageUrl() }}" aria-label="Siguiente">&raquo;</a>
                            </li>
                        </ul>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
</body>
</html>