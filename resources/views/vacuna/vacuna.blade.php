<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Vacunas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
@extends('layout.layout')

@section('title', 'Gestionar Vacunas')

@section('content')
<div class="contenedor">
    <h2 class="text-center">Gestionar Vacunas</h2>
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
                                <a class="nav-link text-white" href="{{ url('/Vacunas/TipoVacunas') }}">Gestionar Tipos de Vacunas</a>
                            </li>                            
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
        <!-- Formulario al lado izquierdo -->
        <div class="col-md-6 mx-auto">
            <div class="form-container">
                <form action="{{ url('/Vacunas/RegistrarVacunas') }}" method="post" class="form-table"> 
                    @csrf
                    <h3 class="text-center mb-4">Registrar Vacuna</h3>
                    <div class="mb-3">
                        <label for="tipo" class="form-label">Nombre de la Vacuna</label>
                        <div id="razas-container">
                            <input class="form-control" type="text" name="nombre" placeholder="Indique un nombre" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="tipo_vacuna" class="form-label">Tipo de Vacuna</label>
                        <div class="input-group">
                            <input type="hidden" id="inputTipoVacuna" name="tipo_vacuna_id">
                            <input type="text" class="form-control" id="tipo_vacuna_nombre" placeholder="Seleccione tipo de vacuna" readonly>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tipoVacunaModal">
                                Seleccionar
                            </button>
                        </div>
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
                            <th>Nombre de la Vacuna</th>
                            <th>Tipo de Vacuna</th>                            
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($Vacunas as $item)
                            <tr>
                                <td>{{ $item->nombre }}</td>
                                <td>{{ $item->TipoVacuna->nombre }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                <div class="custom-pagination">
                    @if ($Vacunas->lastPage() > 1)
                        <ul class="pagination">
                            {{-- Botón "Anterior" --}}
                            <li class="page-item {{ ($Vacunas->currentPage() == 1) ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $Vacunas->previousPageUrl() }}" aria-label="Anterior">&laquo;</a>
                            </li>

                            {{-- Primera página siempre visible --}}
                            <li class="page-item {{ ($Vacunas->currentPage() == 1) ? 'active' : '' }}">
                                <a class="page-link" href="{{ $Vacunas->url(1) }}">1</a>
                            </li>

                            {{-- "..." si la página actual está lejos del inicio --}}
                            @if ($Vacunas->currentPage() > 4)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif

                            {{-- Página actual y una antes/después --}}
                            @for ($i = max(2, $Vacunas->currentPage() - 1); $i <= min($Vacunas->lastPage() - 1, $Vacunas->currentPage() + 1); $i++)
                                <li class="page-item {{ ($Vacunas->currentPage() == $i) ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $Vacunas->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor

                            {{-- "..." si la página actual está lejos del final --}}
                            @if ($Vacunas->currentPage() < $Vacunas->lastPage() - 3)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif

                            {{-- Última página siempre visible --}}
                            @if ($Vacunas->lastPage() > 1)
                                <li class="page-item {{ ($Vacunas->currentPage() == $Vacunas->lastPage()) ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $Vacunas->url($Vacunas->lastPage()) }}">{{ $Vacunas->lastPage() }}</a>
                                </li>
                            @endif

                            {{-- Botón "Siguiente" --}}
                            <li class="page-item {{ ($Vacunas->currentPage() == $Vacunas->lastPage()) ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $Vacunas->nextPageUrl() }}" aria-label="Siguiente">&raquo;</a>
                            </li>
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Tipo Vacuna -->
<div class="modal fade" id="tipoVacunaModal" tabindex="-1" aria-labelledby="tipoVacunaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Seleccionar Tipo de Vacuna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 d-flex">
                    <input type="text" id="busquedaVacuna" class="form-control" placeholder="Buscar tipo de vacuna...">
                    <button class="btn btn-primary ms-2" id="btnBuscarVacuna">Buscar</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="tablaVacunas">
                        <thead class="table-dark">
                            <tr>
                                <th>Nombre</th>
                                <th>Fabrica</th>
                                <th>Numero Lote</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div id="paginacionVacunas" class="d-flex justify-content-center mt-3">
                    <button class="btn btn-secondary" id="prevBtnVacuna" disabled>Anterior</button>
                    <button class="btn btn-secondary" id="nextBtnVacuna">Siguiente</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Datos de las vacunas (estos datos deberían venir del servidor, pero se simulan aquí)
    const vacunasData = @json($TipoVacuna);
    const itemsPorPaginaVacunas = 2; // Número de elementos por página
    let paginaActualVacuna = 1;

    // Cargar las vacunas en la tabla
    function cargarVacunas(vacunas = vacunasData) {
        const tbody = document.querySelector('#tablaVacunas tbody');
        tbody.innerHTML = '';

        const inicio = (paginaActualVacuna - 1) * itemsPorPaginaVacunas;
        const fin = inicio + itemsPorPaginaVacunas;
        const vacunasPagina = vacunas.slice(inicio, fin);

        vacunasPagina.forEach(item => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${item.nombre}</td>
                <td>${item.fabrica}</td>
                <td>${item.nro_lote}</td>
                <td>
                    <button type="button" class="btn btn-success btn-sm"
                            onclick="seleccionarTipoVacuna('${item.id}', '${item.nombre}')">
                        Seleccionar
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });

        // Habilitar/deshabilitar los botones de paginación
        document.getElementById('prevBtnVacuna').disabled = paginaActualVacuna === 1;
        document.getElementById('nextBtnVacuna').disabled = fin >= vacunas.length;
    }

    // Filtrar las vacunas según la búsqueda
    function filtrarVacunas() {
        const busqueda = document.getElementById('busquedaVacuna').value.trim().toLowerCase();
        console.log("Valor de búsqueda: ", busqueda); // Verificamos el valor de la búsqueda

        if (busqueda === '') {
            // Si no hay texto de búsqueda, mostramos todas las vacunas
            console.log("No hay texto de búsqueda, mostrando todas las vacunas.");
            vacunasFiltradas = vacunasData;
        } else {
            // Filtramos las vacunas que coincidan con la búsqueda
            vacunasFiltradas = vacunasData.filter(v => {
                // Aseguramos que las propiedades sean cadenas de texto y luego aplicamos `toLowerCase`
                const nombre = (v.nombre || '').toString().toLowerCase();
                const fabrica = (v.fabrica || '').toString().toLowerCase();
                const nroLote = (v.nro_lote || '').toString().toLowerCase();
                return nombre.includes(busqueda) || fabrica.includes(busqueda) || nroLote.includes(busqueda);
            });
            console.log("Vacunas filtradas: ", vacunasFiltradas); // Verificamos las vacunas filtradas
        }

        // Reseteamos la página actual y recargamos las vacunas filtradas
        paginaActualVacuna = 1;
        cargarVacunas(vacunasFiltradas);
    }

    // Resetear la búsqueda a la vista inicial
    function restablecerBusquedaVacuna() {
        document.getElementById('busquedaVacuna').value = '';
        paginaActualVacuna = 1;
        cargarVacunas();
    }

    // Evento del botón de búsqueda
    document.getElementById('btnBuscarVacuna').addEventListener('click', filtrarVacunas);

    // Evento del botón "Anterior"
    document.getElementById('prevBtnVacuna').addEventListener('click', function () {
        if (paginaActualVacuna > 1) {
            paginaActualVacuna--;
            cargarVacunas(vacunasFiltradas);
        }
    });

    // Evento del botón "Siguiente"
    document.getElementById('nextBtnVacuna').addEventListener('click', function () {
        if ((paginaActualVacuna * itemsPorPaginaVacunas) < vacunasFiltradas.length) {
            paginaActualVacuna++;
            cargarVacunas(vacunasFiltradas);
        }
    });

    // Cargar las vacunas al cargar la página
    document.addEventListener('DOMContentLoaded', function () {
        cargarVacunas(vacunasData);
    });

    // Función para seleccionar el tipo de vacuna
    function seleccionarTipoVacuna(id, nombre) {
        console.log(`Vacuna seleccionada: ID ${id}, Nombre: ${nombre}`);
        // Llenar los inputs correspondientes
        document.getElementById('inputTipoVacuna').value = id;
        document.getElementById('tipo_vacuna_nombre').value = nombre;
        bootstrap.Modal.getInstance(document.getElementById('tipoVacunaModal')).hide();
    }
</script>

@endsection
</body>
</html>
