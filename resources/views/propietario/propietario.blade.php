<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Propietario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/cssPropietarioIMG.css') }}">    
</head>
<body>
@extends('layout.layout')

@section('title', 'Gestionar Propietario')

@section('content')
<div class="contenedor">
    <h2 class="text-center">Gestionar Propietario</h2>
    <div class="row">
        <!-- Columna para el formulario -->
        <div class="col-md-6 mx-auto">
            <div class="form-container">
                <form action="{{ url('/Propietarios/RegistrarPropietario') }}" method="post" enctype="multipart/form-data" class="form-table">
                    @csrf
                    <h3 class="text-center mb-4">Registrar Propietario</h3>
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre Completo</label>
                        <input class="form-control" type="text" name="name" id="name" placeholder="Nombre del Propietario" required>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Fecha de Nacimiento</label>
                        <input class="form-control" type="date" name="date" id="date" required>
                    </div>
                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input class="form-control" type="text" name="direccion" id="direccion" placeholder="Barrio X, calle Y, numero Z" required>
                    </div>
                    <div class="mb-3">
                        <label for="pc_prop" class="form-label">Foto de la Fachada de la Casa</label>
                        <input class="form-control" type="file" name="pc_prop" id="pc_prop" required>
                        <small id="error-msg-prop" style="color: red; display: none;">
                            El archivo debe ser menor a 1MB. Puedes reducir su tamaño en 
                            <a href="https://tinypng.com/" target="_blank">Este Enlace</a>.
                        </small>
                    </div>

                    <script>
                    document.getElementById('pc_prop').addEventListener('change', function() {
                        var file = this.files[0];
                        var maxSize = 1 * 1024 * 1024; // 1MB en bytes
                        var errorMsg = document.getElementById('error-msg-prop');

                        if (file && file.size > maxSize) {
                            errorMsg.style.display = 'block';
                            this.value = ''; // Limpiar el input
                        } else {
                            errorMsg.style.display = 'none';
                        }
                    });
                    </script>
                    <div class="text-center">
                        <button type="submit" class="btn btn-success">Guardar</button>
                    </div>
                </form>
                
                @if (session('agregar'))
                    <div class="alert alert-success mt-3 text-center">
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

        <!-- Columna para la tabla -->
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
                            <th>Codigo Usuario</th>
                            <th>Nombre Completo</th>
                            <th>Fecha de Nacimiento</th>
                            <th>Dirección</th>
                            <th>Foto de la Fachada</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($Propietarios as $item)
                        <tr>
                            <td>{{ $item->codigo }}</td>
                            <td>{{ $item->nombres }}</td>
                            <td>{{ $item->fecha_nac }}</td>
                            <td>{{ $item->direccion }}</td>
                            <td>
                                @if($item->foto_fachada)
                                    <!-- <iframe src="https://drive.google.com/file/d/1_55MwlTkdUyYfv_grCPDDTKlpC0HLS6o/preview" width="auto" height="auto" allow="autoplay"></iframe>-->
                                    <img src="{{ asset($item->foto_fachada) }}" alt="Foto de la casa de {{ $item->nombres }}" 
                                        width="100" class="thumbnail" onclick="openFullScreen('{{ asset($item->foto_fachada) }}')">
                                @else
                                    No disponible
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                <div class="custom-pagination">
                    @if ($Propietarios->lastPage() > 1)
                        <ul class="pagination">
                            {{-- Botón "Anterior" --}}
                            <li class="page-item {{ ($Propietarios->currentPage() == 1) ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $Propietarios->previousPageUrl() }}" aria-label="Anterior">&laquo;</a>
                            </li>

                            {{-- Primera página siempre visible --}}
                            <li class="page-item {{ ($Propietarios->currentPage() == 1) ? 'active' : '' }}">
                                <a class="page-link" href="{{ $Propietarios->url(1) }}">1</a>
                            </li>

                            {{-- "..." si la página actual está lejos del inicio --}}
                            @if ($Propietarios->currentPage() > 4)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif

                            {{-- Página actual y una antes/después --}}
                            @for ($i = max(2, $Propietarios->currentPage() - 1); $i <= min($Propietarios->lastPage() - 1, $Propietarios->currentPage() + 1); $i++)
                                <li class="page-item {{ ($Propietarios->currentPage() == $i) ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $Propietarios->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor

                            {{-- "..." si la página actual está lejos del final --}}
                            @if ($Propietarios->currentPage() < $Propietarios->lastPage() - 3)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif

                            {{-- Última página siempre visible --}}
                            @if ($Propietarios->lastPage() > 1)
                                <li class="page-item {{ ($Propietarios->currentPage() == $Propietarios->lastPage()) ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $Propietarios->url($Propietarios->lastPage()) }}">{{ $Propietarios->lastPage() }}</a>
                                </li>
                            @endif

                            {{-- Botón "Siguiente" --}}
                            <li class="page-item {{ ($Propietarios->currentPage() == $Propietarios->lastPage()) ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $Propietarios->nextPageUrl() }}" aria-label="Siguiente">&raquo;</a>
                            </li>
                        </ul>
                    @endif
                </div>
            </div>        
        </div>
    </div>
</div> 

<!-- Modal para ver la imagen en pantalla completa -->
<div id="imageModal" class="modal" onclick="closeFullScreen()">
    <span class="close">&times;</span>
    <img class="modal-content" id="fullImage">
</div>

@endsection   

</body>

<script>
    function openFullScreen(src) {
        const image = document.getElementById("fullImage");
        image.src = src;
        image.onload = function () {
            // Asegura que la imagen mantenga su tamaño original y sea responsive
            image.style.width = "auto";
            image.style.height = "auto";
            document.getElementById("imageModal").style.display = "flex";
        };
    }

    function closeFullScreen() {
        document.getElementById("imageModal").style.display = "none";
    }
</script>

</html>
