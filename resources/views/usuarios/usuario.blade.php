<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Personal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
@extends('layout.layout')

@section('title', 'Gestionar Personal')

@section('content')
<div class="contenedor">            
    <h2 class="text-center">Gestionar Personal</h2>
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
                    <div class="justify-content-between" id="panelNav"> <!-- ID único -->
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ url('/Usuarios/Rol') }}">Gestionar Rol</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ url('/backup') }}">Gestionar Backups</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>

        <!-- Columna para el formulario -->
        <div class="col-md-6 mx-auto">
            <div class="form-container">
                @if (session('agregar'))
                    <div class="alert alert-success mt-3 text-center">
                        <p>{{ session('agregar') }}</p>
                    </div>
                @endif
                <form action="{{ url('/Usuarios/RegistrarUsuario') }}" method="post" class="form-table">
                    @csrf
                    <h3 class="text-center mb-4">Registrar Personal</h3>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre Completo</label>
                        <input class="form-control" type="text" name="nombre" id="nombre" placeholder="Nombre Completo" required>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                        <input class="form-control" type="date" name="fecha_nacimiento" id="fecha_nacimiento" required>
                    </div>
                    <div class="mb-3">
                        <label for="edad" class="form-label">Edad</label>
                        <input class="form-control" type="text" name="edad" id="edad" placeholder="Edad" required readonly>
                    </div>
                    <script>
                        document.getElementById('fecha_nacimiento').addEventListener('change', function () {
                            const fechaNacimiento = new Date(this.value);
                            const hoy = new Date();
                            let edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
                            const mes = hoy.getMonth() - fechaNacimiento.getMonth();

                            if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNacimiento.getDate())) {
                                edad--;
                            }

                            document.getElementById('edad').value = edad;
                        });

                        document.getElementById('submitBtn').addEventListener('click', function (event) {
                            const edad = parseInt(document.getElementById('edad').value);
                            
                            // Verificamos si la edad es menor a 18
                            if (edad < 18) {
                                alert("La persona debe ser mayor de edad para guardar los datos.");
                                event.preventDefault();  // Prevenir el envío del formulario
                            }
                        });
                    </script>

                    <div class="mb-3">
                        <label for="sexo" class="form-label">Sexo</label>
                        <select class="form-select" name="sexo" id="sexo" required>
                            <option value="">Seleccione una opción</option>
                            <option value="masculino">Masculino</option>
                            <option value="femenino">Femenino</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="direccion" class="form-label">Direccion Domicilio</label>
                        <input class="form-control" type="text" name="direccion" id="direccion" placeholder="Direccion" required>
                    </div>
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Telefono</label>
                        <input class="form-control" type="text" name="telefono" id="telefono" placeholder="Nro Telefono" required>
                    </div>
                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-select" name="estado" id="estado" required>
                            <option value="">Seleccione una opción</option>
                            <option value="activo">Activo</option>
                            <option value="inhabilitado">Inhabilitado</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tipo_usuario" class="form-label">Tipo de Usuario</label>
                        <select class="form-select" name="tipo_usuario" id="tipo_usuario" required onchange="mostrarCampos()">
                            <option value="">Seleccione una opción</option>
                            <option value="Administrador">Administrador</option>
                            <option value="Veterinario">Veterinario</option>
                            <option value="Secretaria">Secretaria</option>
                            <option value="Director General">Director General</option>
                        </select>
                        </div>
                        <!-- Campos adicionales ocultos inicialmente -->
                        <div class="mb-3" id="campo_especialidad" style="display: none;">
                        <label for="especialidad" class="form-label">Especialidad</label>
                        <input type="text" class="form-control" name="especialidad" id="especialidad">
                        </div>

                        <div class="mb-3" id="campo_horario" style="display: none;">
                        <label for="horario_trabajo" class="form-label">Horario de trabajo</label>
                        <input type="text" class="form-control" name="horario_trabajo" id="horario_trabajo">
                        </div>

                        <div class="mb-3" id="campo_anios" style="display: none;">
                        <label for="anios_cargo" class="form-label">Años en el cargo</label>
                        <input type="number" step="0.1" min="0" class="form-control" name="anios_cargo" id="anios_cargo">
                        </div>

                        <script>
                        function mostrarCampos() {
                        const tipo = document.getElementById("tipo_usuario").value;

                        // Oculta todos los campos al inicio
                        document.getElementById("campo_especialidad").style.display = "none";
                        document.getElementById("campo_horario").style.display = "none";
                        document.getElementById("campo_anios").style.display = "none";

                        if (tipo === "Veterinario") {
                            document.getElementById("campo_especialidad").style.display = "block";
                        } else if (tipo === "Secretaria") {
                            document.getElementById("campo_horario").style.display = "block";
                        } else if (tipo === "Director General") {
                            document.getElementById("campo_anios").style.display = "block";
                        }
                        }
                        </script>
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

        <!-- Columna para la tabla -->
        <div class="col-md-6 mx-auto">
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
                                <option value="1" {{ request('perPage') == 1 ? 'selected' : '' }}>1</option>
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
            <table class="table table-striped table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Nombre Completo</th>
                        <th>Fecha Nacimiento</th>                        
                        <th>Nombre de Usuario</th>
                        <th>Email</th>
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
                                @if($item->usuariov2)
                                    {{ ucfirst($item->usuariov2->tipo_user) ?? 'Sin Rol' }}
                                @else
                                    <a href="{{ url('/Usuarios/AgregarRol', $item->id) }}" class="btn btn-outline-primary">Añadir Rol</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                <div class="custom-pagination">
                @if ($Personal->lastPage() > 1)
                    <ul class="pagination">
                        {{-- Botón "Anterior" --}}
                        <li class="page-item {{ ($Personal->currentPage() == 1) ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $Personal->previousPageUrl() }}" aria-label="Anterior">&laquo;</a>
                        </li>

                        {{-- Primera página siempre visible --}}
                        <li class="page-item {{ ($Personal->currentPage() == 1) ? 'active' : '' }}">
                            <a class="page-link" href="{{ $Personal->url(1) }}">1</a>
                        </li>

                        {{-- "..." si la página actual está lejos del inicio --}}
                        @if ($Personal->currentPage() > 4)
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                        @endif

                        {{-- Página actual y una antes/después --}}
                        @for ($i = max(2, $Personal->currentPage() - 1); $i <= min($Personal->lastPage() - 1, $Personal->currentPage() + 1); $i++)
                            <li class="page-item {{ ($Personal->currentPage() == $i) ? 'active' : '' }}">
                                <a class="page-link" href="{{ $Personal->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        {{-- "..." si la página actual está lejos del final --}}
                        @if ($Personal->currentPage() < $Personal->lastPage() - 3)
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                        @endif

                        {{-- Última página siempre visible --}}
                        @if ($Personal->lastPage() > 1)
                            <li class="page-item {{ ($Personal->currentPage() == $Personal->lastPage()) ? 'active' : '' }}">
                                <a class="page-link" href="{{ $Personal->url($Personal->lastPage()) }}">{{ $Personal->lastPage() }}</a>
                            </li>
                        @endif

                        {{-- Botón "Siguiente" --}}
                        <li class="page-item {{ ($Personal->currentPage() == $Personal->lastPage()) ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $Personal->nextPageUrl() }}" aria-label="Siguiente">&raquo;</a>
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
