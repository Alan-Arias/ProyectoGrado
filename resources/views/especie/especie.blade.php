<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Especies</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
@extends('layout.layout')

@section('title', 'Gestionar Especies')

@section('content')
<div class="contenedor">
    <h2 class="text-center">Gestionar Especies</h2>
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="form-container">
                <form action="{{ url('/Especies/RegistrarEspecie') }}" method="post" class="form-table">
                    @csrf
                    <h3 class="text-center mb-4">Registrar Especie</h3>
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre de la especie</label>
                        <input class="form-control" type="text" name="name" id="name" placeholder="Nombre de la Especie" required>
                    </div>
                    <div class="mb-3">
                        <label for="razas" class="form-label">Raza</label>
                        <div id="razas-container">
                            <input class="form-control" type="text" name="razas[]" placeholder="Indique una raza" required>
                        </div>
                        <button type="button" class="btn btn-warning mt-2" onclick="agregarRaza()">Agregar otra raza</button>
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
            <div class="table-container">
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
                                <option value="1" {{ request('perPage') == 1 ? 'selected' : '' }}>1</option>
                                <option value="3" {{ request('perPage') == 3 ? 'selected' : '' }}>3</option>
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
            // Función para actualizar la paginación y guardar la selección en localStorage
            function updatePagination() {
                var perPageSelect = document.getElementById('perPage');
                var selectedValue = perPageSelect.value;
                localStorage.setItem('perPage', selectedValue); // Guardar en localStorage
                document.getElementById('paginationForm').submit(); // Enviar el formulario
            }

            // Al cargar la página, establecer el valor seleccionado en el dropdown
            window.onload = function() {
                var storedValue = localStorage.getItem('perPage');
                if (storedValue) {
                    document.getElementById('perPage').value = storedValue;
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
                            <th>Nombre de la Especie <button class="btn btn-warning btn-sm" onclick="editarEspecie()">Editar</button></th>
                            <th>Raza <button class="btn btn-warning btn-sm" onclick="editarRaza()">Editar</button></th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($Especies as $especie)
                        <tr>
                            <td>
                                <span class="especie-text">{{ $especie->nombre }}</span>
                                <input type="text" class="form-control d-none especie-input" value="{{ $especie->nombre }}">
                                <button class="btn btn-success btn-sm d-none especie-save" onclick="guardarEspecie({{ $especie->id }}, this)">Guardar</button>
                            </td>
                            <td>
                                <ul class="list-unstyled">
                                    @foreach ($especie->razas as $raza)
                                        <li>
                                            <span class="raza-text">{{ $raza->nombre }}</span>
                                            <input type="text" class="form-control d-none raza-input" value="{{ $raza->nombre }}">
                                            <button class="btn btn-success btn-sm d-none raza-save" onclick="guardarRaza({{ $raza->id }}, this)">Guardar</button>
                                        </li>
                                    @endforeach
                                </ul>                                
                            </td>
                            <td>
                                <a href="{{ url('/Razas/AgregarRaza/' . $especie->id) }}" class="btn btn-info btn-sm">Agregar Raza</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                <div class="custom-pagination">
                    @if ($Especies->lastPage() > 1)
                        <ul class="pagination">
                            {{-- Botón "Anterior" --}}
                            <li class="page-item {{ ($Especies->currentPage() == 1) ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $Especies->previousPageUrl() }}" aria-label="Anterior">&laquo;</a>
                            </li>

                            {{-- Primera página siempre visible --}}
                            <li class="page-item {{ ($Especies->currentPage() == 1) ? 'active' : '' }}">
                                <a class="page-link" href="{{ $Especies->url(1) }}">1</a>
                            </li>

                            {{-- "..." si la página actual está lejos del inicio --}}
                            @if ($Especies->currentPage() > 4)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif

                            {{-- Página actual y una antes/después --}}
                            @for ($i = max(2, $Especies->currentPage() - 1); $i <= min($Especies->lastPage() - 1, $Especies->currentPage() + 1); $i++)
                                <li class="page-item {{ ($Especies->currentPage() == $i) ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $Especies->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor

                            {{-- "..." si la página actual está lejos del final --}}
                            @if ($Especies->currentPage() < $Especies->lastPage() - 3)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif

                            {{-- Última página siempre visible --}}
                            @if ($Especies->lastPage() > 1)
                                <li class="page-item {{ ($Especies->currentPage() == $Especies->lastPage()) ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $Especies->url($Especies->lastPage()) }}">{{ $Especies->lastPage() }}</a>
                                </li>
                            @endif

                            {{-- Botón "Siguiente" --}}
                            <li class="page-item {{ ($Especies->currentPage() == $Especies->lastPage()) ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $Especies->nextPageUrl() }}" aria-label="Siguiente">&raquo;</a>
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
<script>
    function agregarRaza() {
        let container = document.getElementById("razas-container");
        let input = document.createElement("input");
        input.type = "text";
        input.name = "razas[]";
        input.classList.add("form-control", "mt-2");
        input.placeholder = "Indique otra raza";
        container.appendChild(input);
    }

    function editarEspecie() {
        document.querySelectorAll(".especie-text").forEach(el => el.classList.add("d-none"));
        document.querySelectorAll(".especie-input").forEach(el => el.classList.remove("d-none"));
        document.querySelectorAll(".especie-save").forEach(el => el.classList.remove("d-none"));
    }

    function editarRaza() {
        document.querySelectorAll(".raza-text").forEach(el => el.classList.add("d-none"));
        document.querySelectorAll(".raza-input").forEach(el => el.classList.remove("d-none"));
        document.querySelectorAll(".raza-save").forEach(el => el.classList.remove("d-none"));
    }

    function guardarEspecie(id, button) {
        let input = button.previousElementSibling;
        let nombre = input.value;

        // Crear un formulario para enviar los datos
        let formData = new FormData();
        formData.append('nombre', nombre);

        fetch(`/Especies/ActualizarEspecie/${id}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(() => location.reload());
    }

    function guardarRaza(id, button) {
        let input = button.previousElementSibling;
        let nombre = input.value;

        // Crear un formulario para enviar los datos
        let formData = new FormData();
        formData.append('nombre', nombre);

        fetch(`/Razas/ActualizarRaza/${id}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(() => location.reload());
    }
</script>
</html>
