<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modulo Agregar Enfermedades</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
@extends('layout.layoutv2')

@section('title', 'Módulo Agregar Enfermedades')

@section('content')
<div class="contenedor">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="form-container">
                @if (session('agregar'))
                    <div class="alert alert-success mt-3 text-center">
                        <p>{{ session('agregar') }}</p>
                    </div>
                @endif
                <form action="{{ url('/CensoAnimales/ListaEnfermedadesRegistrar') }}" method="post" enctype="multipart/form-data" class="form-table">
                @csrf
                <h3 class="text-center mb-4">Listas de Enfermedades</h3>
                    <div class="form-group">
                        <label for="bloque">Nombre de la Enfermedad:</label>
                        <input type="text" class="form-control full-width" name="nombre_enfermedad" required>
                    </div>
                    <div class="form-group">
                        <label for="especie_enf" class="form-label">Indique Especie</label>
                        <select class="form-select" name="especie_enf" id="especie_enf" onchange="toggleDecesoFields()" required>
                            <option value="">Seleccione una opción</option>
                            <option value="canino">Canino</option>
                            <option value="felino">Felino</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-success">Guardar</button>
                    </div>
                </form>
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
        <div class="col-md-6">
            <div class="table-container table-responsive">
                <div class="mb-3 position-relative">
                    <button id="filterButton" class="btn btn-primary btn-sm" onclick="toggleFilter()">
                        <i class="fas fa-filter"></i> Filtros
                    </button>
                    <!-- Selector de Paginación (oculto por defecto) -->
                    <div id="filterSelect" class="dropdown-menu" style="display: none; position: absolute; top: 100%; left: 0;">
                        <form method="GET" id="paginationForm" class="p-2">
                            <label for="perPage" class="mr-2">Mostrar:</label>
                            <select name="perPage" id="perPage" class="form-control" onchange="document.getElementById('paginationForm').submit()">
                                <option value="2" {{ request('perPage') == 2 ? 'selected' : '' }}>2</option>
                                <option value="4" {{ request('perPage') == 4 ? 'selected' : '' }}>4</option>
                            </select>
                        </form>
                    </div>
                </div>
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
                            <th>Nombre</th>
                            <th>Especie</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ListaEnfermedad as $item)
                        <tr>
                            <td>{{ $item->nombre }}</td>
                            <td>{{ $item->especie_enf }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
</body>
</html>