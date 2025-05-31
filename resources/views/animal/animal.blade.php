<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Animales</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/cssAnimalIMG.css') }}">
</head>
<body>
@extends('layout.layout')

@section('title', 'Gestionar Animales')

@section('content')
<div class="contenedor">
    <h2 class="text-center">Gestionar Animales</h2>
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
                                <a class="nav-link text-white" href="{{ url('/Animales/TipoAnimal') }}">🐾 Tipo de Animal</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ url('/Animales/FormaAdquisicion') }}">📦 Forma de Adquisición</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ url('/Animales/Incapacidad') }}">🧠 Grado de Incapacidad</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ url('/Animales/HistorialPropietarios') }}">Historial de Propietarios</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
        <!-- Formulario al lado izquierdo -->
        <div class="col-md-6 mx-auto">
            <div class="form-container">
                <form action="{{ url('/Animales/RegistrarAnimal') }}" method="post" class="form-table" enctype="multipart/form-data"> 
                    @csrf
                    <h3 class="text-center mb-4">Registrar Animal</h3>
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre del Animal</label>
                        <input class="form-control" type="text" name="name" id="name" placeholder="Nombre del animal" required>
                    </div>                    
                    <div class="mb-3">
                        <label for="color" class="form-label">Color</label>
                        <input class="form-control" type="text" name="color" id="color" placeholder="Color del Animal" required>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Fecha de Nacimiento</label>
                        <input class="form-control" type="date" name="date" id="date" required>
                    </div>
                    <div class="mb-3">
                        <label for="edad" class="form-label">Edad</label>
                        <input class="form-control" type="text" name="edad" id="edad" placeholder="Edad del Animal"readonly required>
                    </div>
                    <script>
                        document.getElementById("date").addEventListener("input", function() {
                            let birthDate = new Date(this.value);
                            let today = new Date();

                            let years = today.getFullYear() - birthDate.getFullYear();
                            let months = today.getMonth() - birthDate.getMonth();

                            if (months < 0) {
                                years--;
                                months += 12;
                            }

                            if (birthDate > today) {
                                document.getElementById("edad").value = "Fecha inválida";
                            } else {
                                document.getElementById("edad").value = `${years} años y ${months} meses`;
                            }
                        });
                    </script>
                    <div class="mb-3">
                        <label for="color" class="form-label">Sexo</label>
                        <select class="form-select" name="sexo" id="sexo" required>
                            <option value="">Seleccione una opción</option>
                            <option value="hembra">Hembra</option>
                            <option value="macho">Macho</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="color" class="form-label">¿Tiene Carnet de Vacuna?</label>
                        <select class="form-select" name="carnetvacuna" id="carnetvacuna" required>
                            <option value="">Seleccione una opción</option>
                            <option value="tiene">tiene</option>
                            <option value="no_tiene">no tiene</option>
                        </select>
                    </div>

                    <div class="mb-3" id="fechaVacunaDiv">
                        <label for="date" class="form-label">Fecha de la Última Vacuna</label>
                        <input class="form-control" type="date" name="datevacuna" id="datevacuna">
                    </div>

                    <script>
                        // Obtener el select y el campo de la fecha
                        const carnetVacunaSelect = document.getElementById('carnetvacuna');
                        const fechaVacunaDiv = document.getElementById('fechaVacunaDiv');
                        
                        // Ocultar la fecha de la última vacuna por defecto si se selecciona "no tiene"
                        carnetVacunaSelect.addEventListener('change', function () {
                            if (this.value === 'no_tiene') {
                                // Ocultar el campo de la fecha
                                fechaVacunaDiv.style.display = 'none';
                            } else {
                                // Mostrar el campo de la fecha
                                fechaVacunaDiv.style.display = 'block';
                            }
                        });

                        // Comprobar el valor al cargar la página en caso de que ya esté seleccionado
                        if (carnetVacunaSelect.value === 'no_tiene') {
                            fechaVacunaDiv.style.display = 'none';
                        }
                    </script>

                    <div class="mb-3">
                        <label for="castrado" class="form-label">Castrado</label>
                        <select class="form-select" name="castrado" id="castrado" required>
                            <option value="">Seleccione una opción</option>
                            <option value="no">No está Castrado</option>
                            <option value="si">Sí está castrado</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado del Animal</label>
                        <select class="form-select" name="estado" id="estado" onchange="toggleDecesoFields()" required>
                            <option value="">Seleccione una opción</option>
                            <option value="Vivo">Vivo</option>
                            <option value="Fallecido">Fallecido</option>
                        </select>
                    </div>
                    <div id="decesoFields" style="display: none;" class="mb-3">
                        <label for="fecha_deceso" class="form-label">Fecha de Deceso</label>
                        <input type="date" class="form-control" name="fecha_deceso" id="fecha_deceso">
                    </div>
                    <div id="motivoFields" style="display: none;" class="mb-3">
                        <label for="motivo_deceso" class="form-label">Motivo de Deceso</label>
                        <input type="text" class="form-control" name="motivo_deceso" id="motivo_deceso">
                    </div>
                    <div class="mb-3">
                        <label for="especie" class="form-label">Especie del Animal</label>
                        <input class="form-control" type="text" name="especie" id="especie" readonly required>
                        <input type="hidden" name="especie_id" id="especie_id" required>
                        <input type="hidden" name="raza_id" id="raza_id" required>
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#especieModal">Añadir Especie</button>
                    </div>                    
                    <div class="mb-3">
                        <label for="propietario" class="form-label">Nombre del Propietario</label>
                        <input class="form-control" type="text" name="propietario" id="propietario" readonly required>
                        <input type="hidden" name="propietario_id" id="propietario_id" required>
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#clienteModal">Añadir Propietario</button>
                    </div>
                    <div class="mb-3">
                        <label for="pc_animal" class="form-label">Foto del Animal</label>
                        <input class="form-control" type="file" name="pc_animal" id="pc_animal" required>
                        <small id="error-msg" style="color: red; display: none;">
                            El archivo debe ser menor a 1MB. Puedes reducir su tamaño en 
                            <a href="https://tinypng.com/" target="_blank">Este Enlace</a>.
                        </small>
                    </div>
                    <script>
                    document.getElementById('pc_animal').addEventListener('change', function() {
                        var file = this.files[0];
                        var maxSize = 1 * 1024 * 1024; // 2MB en bytes
                        var errorMsg = document.getElementById('error-msg');

                        if (file && file.size > maxSize) {
                            errorMsg.style.display = 'block';
                            this.value = ''; // Limpiar el input
                        } else {
                            errorMsg.style.display = 'none';
                        }
                    });
                    </script>                    
                    <div class="mb-3">
                        <label class="form-label">Tipo de Animal</label><br>
                        @foreach ($tipos as $tipo)
                            <div>
                                <input type="radio" id="tipo_{{ $tipo->id }}" name="tipo_animal_id" value="{{ $tipo->id }}" required>
                                <label for="tipo_{{ $tipo->id }}">{{ $tipo->nombre }}</label>
                            </div>
                        @endforeach
                        @error('tipo_animal_id')
                            <br><span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="adquisicion" class="form-label">Forma de Adquisición</label>
                        <select class="form-select" name="forma_adquisicion_id" id="adquisicion" required>
                            <option value="">Seleccione una opción</option>
                            @foreach ($FormaAdquisicion as $forma)
                                <option value="{{ $forma->id }}">{{ $forma->nombre }}</option>
                            @endforeach
                        </select>
                        @error('forma_adquisicion_id')
                            <br><span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="incapacidad" class="form-label">Grado de Incapacidad</label>
                        <select class="form-select" name="grado_incapacidad_id" id="incapacidad" required>
                            <option value="">Seleccione una opción</option>
                            @foreach ($grados as $grado)
                                <option value="{{ $grado->id }}">{{ $grado->descripcion }}</option>
                            @endforeach
                        </select>
                        @error('grado_incapacidad_id')
                            <br><span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="alergico" class="form-label">Alergico</label>
                        <select class="form-select" name="alergico" id="alergico" required>
                            <option value="">Seleccione una opción</option>
                            <option value="si">Es Alergico</option>
                            <option value="no">No es Alergico</option>                    
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

        <!-- Tabla al lado derecho -->
        <div class="col-md-6">        
            @if (session('agregar'))
                <div class="alert alert-success mt-3">
                    <p>{{ session('agregar') }}</p>
                </div>
            @endif
            @if (session('success'))
                <div class="alert alert-success mt-3">
                    <p>{{ session('success') }}</p>
                </div>
            @endif
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
                                <option value="5" {{ request('perPage') == 5 ? 'selected' : '' }}>5</option>
                                <option value="3" {{ request('perPage') == 3 ? 'selected' : '' }}>3</option>
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
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Estado</th>
                            <th>Raza</th>
                            <th>Edad</th>
                            <th>Propietario</th>
                            <th>Foto</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($Animales as $item)
                            <tr class="position-relative {{ $item->estado == 'Fallecido' ? 'fallecido-row' : '' }}">
                                <td>{{ $item->nombre }}</td>
                                <td>{{ $item->TipoAnimal->nombre }}</td>
                                <td>{{ $item->estado }}</td>
                                <th>{{ $item->raza->nombre }}</th>
                                <td>
                                    @php
                                        try {
                                            if ($item->fecha_nac) {
                                                $fechaNacimiento = \Carbon\Carbon::parse($item->fecha_nac);
                                                $ahora = \Carbon\Carbon::now();

                                                if ($fechaNacimiento->greaterThan($ahora)) {
                                                    echo 'Fecha futura';
                                                } else {
                                                    $diff = $fechaNacimiento->diff($ahora);
                                                    $anios = $diff->y;
                                                    $meses = $diff->m;
                                                    echo "{$anios} año" . ($anios != 1 ? 's' : '') . " {$meses} mes" . ($meses != 1 ? 'es' : '');
                                                }
                                            } else {
                                                echo 'Edad no disponible';
                                            }
                                        } catch (\Exception $e) {
                                            echo 'Fecha inválida';
                                        }
                                    @endphp
                                </td>
                                <td>{{ $item->propietario->nombres }}</td>
                                <td>
                                    @if($item->foto_animal)
                                        <img src="{{ asset($item->foto_animal) }}" alt="Foto de {{ $item->nombre }}" 
                                            width="100" class="thumbnail" onclick="openFullScreen('{{ asset($item->foto_animal) }}')">
                                    @else
                                        No disponible
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('historial.show', $item->id) }}" class="btn btn-primary btn-sm">Ver Historial</a>
                                    <a href="{{ route('cambiar.propietario', $item->id) }}" class="btn btn-warning btn-sm">Cambiar Propietario</a>
                                    <a href="{{ route('historial.propietario', $item->id) }}" class="btn btn-info btn-sm">Historial de Propietarios</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                <div class="custom-pagination">
                    @if ($Animales->lastPage() > 1)
                        <ul class="pagination">
                            {{-- Botón "Anterior" --}}
                            <li class="page-item {{ ($Animales->currentPage() == 1) ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $Animales->previousPageUrl() }}" aria-label="Anterior">&laquo;</a>
                            </li>

                            {{-- Primera página siempre visible --}}
                            <li class="page-item {{ ($Animales->currentPage() == 1) ? 'active' : '' }}">
                                <a class="page-link" href="{{ $Animales->url(1) }}">1</a>
                            </li>

                            {{-- "..." si la página actual está lejos del inicio --}}
                            @if ($Animales->currentPage() > 4)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif

                            {{-- Página actual y una antes/después --}}
                            @for ($i = max(2, $Animales->currentPage() - 1); $i <= min($Animales->lastPage() - 1, $Animales->currentPage() + 1); $i++)
                                <li class="page-item {{ ($Animales->currentPage() == $i) ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $Animales->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor

                            {{-- "..." si la página actual está lejos del final --}}
                            @if ($Animales->currentPage() < $Animales->lastPage() - 3)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif

                            {{-- Última página siempre visible --}}
                            @if ($Animales->lastPage() > 1)
                                <li class="page-item {{ ($Animales->currentPage() == $Animales->lastPage()) ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $Animales->url($Animales->lastPage()) }}">{{ $Animales->lastPage() }}</a>
                                </li>
                            @endif

                            {{-- Botón "Siguiente" --}}
                            <li class="page-item {{ ($Animales->currentPage() == $Animales->lastPage()) ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $Animales->nextPageUrl() }}" aria-label="Siguiente">&raquo;</a>
                            </li>
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para seleccionar propietarios -->
<div class="modal fade" id="clienteModal" tabindex="-1" aria-labelledby="clienteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="clienteModalLabel">Seleccionar Propietario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="mensajeExito" class="alert alert-success" style="display: none;">
                    Se ha añadido exitosamente
                </div>
                <div class="mb-3 d-flex">
                    <input type="text" id="busquedaCliente" class="form-control" placeholder="Buscar propietario...">
                    <button class="btn btn-primary ms-2" id="btnBuscarCliente">Buscar</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="tablaClientes">
                        <thead class="table-dark">
                            <tr>
                                <th>Codigo Cliente</th>
                                <th>Nombre</th>
                                <th>Fecha de Nacimiento</th>
                                <th>Dirección</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div id="paginacionClientes" class="d-flex justify-content-center mt-3">
                    <button class="btn btn-secondary" id="prevBtnCliente" disabled>Anterior</button>
                    <button class="btn btn-secondary" id="nextBtnCliente">Siguiente</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<script>
    const clientesData = @json($Propietarios);
    const itemsPorPaginaClientes = 3;
    let paginaActualCliente = 1;

    function cargarClientes() {
        const tbody = document.querySelector('#tablaClientes tbody');
        tbody.innerHTML = '';
        const inicio = (paginaActualCliente - 1) * itemsPorPaginaClientes;
        const fin = inicio + itemsPorPaginaClientes;
        const clientesPagina = clientesData.slice(inicio, fin);

        clientesPagina.forEach(item => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${item.codigo}</td>
                <td>${item.nombres}</td>
                <td>${item.fecha_nac}</td>
                <td>${item.direccion}</td>
                <td>
                    <button type="button" class="btn btn-success btn-sm" 
                            onclick="seleccionarCliente('${item.codigo}', '${item.nombres}')">
                        Seleccionar
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });

        document.getElementById('prevBtnCliente').disabled = paginaActualCliente === 1;
        document.getElementById('nextBtnCliente').disabled = fin >= clientesData.length;
    }

    function restablecerBusqueda() {
        document.getElementById('busquedaCliente').value = '';
        paginaActualCliente = 1;
        cargarClientes();
    }

    document.getElementById('btnBuscarCliente').addEventListener('click', function() {
        const busqueda = document.getElementById('busquedaCliente').value.trim();
        if (busqueda === '') {
            restablecerBusqueda();
            return;
        }
        const filteredClientes = clientesData.filter(item =>
            item.nombres.toLowerCase().includes(busqueda.toLowerCase()) || 
            item.codigo.toLowerCase().includes(busqueda.toLowerCase())
        );

        paginaActualCliente = 1;
        const tbody = document.querySelector('#tablaClientes tbody');
        tbody.innerHTML = '';
        
        filteredClientes.forEach(item => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${item.codigo}</td>
                <td>${item.nombres}</td>
                <td>${item.fecha_nac}</td>
                <td>${item.direccion}</td>
                <td>
                    <button type="button" class="btn btn-success btn-sm" 
                            onclick="seleccionarCliente('${item.codigo}', '${item.nombres}')">
                        Seleccionar
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });


        document.getElementById('prevBtnCliente').disabled = true;
        document.getElementById('nextBtnCliente').disabled = true;
    });

    document.getElementById('prevBtnCliente').addEventListener('click', function() {
        if (paginaActualCliente > 1) {
            paginaActualCliente--;
            cargarClientes();
        }
    });

    document.getElementById('nextBtnCliente').addEventListener('click', function() {
        if ((paginaActualCliente * itemsPorPaginaClientes) < clientesData.length) {
            paginaActualCliente++;
            cargarClientes();
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        cargarClientes();
    });
</script>

<!-- Modal para seleccionar especies -->
<div class="modal fade" id="especieModal" tabindex="-1" aria-labelledby="especieModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="especieModalLabel">Seleccionar Especie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="mensajeExito2" class="alert alert-success" style="display: none;">
                    Se ha añadido exitosamente
                </div>
                <div class="mb-3 d-flex">
                    <input type="text" id="busquedaEspecie" class="form-control" placeholder="Buscar especie...">
                    <button class="btn btn-primary ms-2" id="btnBuscarEspecie">Buscar</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="tablaEspecies">
                        <thead class="table-dark">
                            <tr>
                                <th>Nombre</th>
                                <th>Opción</th>
                            </tr>
                        </thead>
                        <tbody>
                        <!-- Los registros se llenarán dinámicamente -->
                        </tbody>
                    </table>
                </div>
                <div id="paginacion" class="d-flex justify-content-center mt-3">
                    <button class="btn btn-secondary" id="prevBtn" disabled>Anterior</button>
                    <button class="btn btn-secondary" id="nextBtn">Siguiente</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    const especies = @json($Especies);
    const itemsPorPagina = 1;
    let paginaActual = 1;

    function cargarEspecies() {
        const tbody = document.querySelector('#tablaEspecies tbody');
        tbody.innerHTML = '';
        const inicio = (paginaActual - 1) * itemsPorPagina;
        const fin = inicio + itemsPorPagina;
        const especiesPagina = especies.slice(inicio, fin);

        especiesPagina.forEach(item => {
            item.razas.forEach(RazaAnimal => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${item.nombre} / ${RazaAnimal.nombre}</td>
                    <td>
                        <button type="button" class="btn btn-success btn-sm" 
                                onclick="seleccionarEspecie('${item.id}', '${item.nombre}', '${RazaAnimal.id}', '${RazaAnimal.nombre}')">
                            Seleccionar
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        });

        document.getElementById('prevBtn').disabled = paginaActual === 1;
        document.getElementById('nextBtn').disabled = fin >= especies.length;
    }

    function restablecerBusqueda() {
        document.getElementById('busquedaEspecie').value = '';
        paginaActual = 1;
        cargarEspecies();
    }

    document.getElementById('btnBuscarEspecie').addEventListener('click', function() {
        const busqueda = document.getElementById('busquedaEspecie').value.trim();
        if (busqueda === '') {
            restablecerBusqueda();
            return;
        }
        const filteredEspecies = especies.filter(item =>
            item.nombre.toLowerCase().includes(busqueda) || 
            item.razas.some(raza => raza.nombre.toLowerCase().includes(busqueda))
        );

        // Reestablecer la paginación
        paginaActual = 1;
        const tbody = document.querySelector('#tablaEspecies tbody');
        tbody.innerHTML = '';

        filteredEspecies.forEach(item => {
            item.razas.forEach(RazaAnimal => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${item.nombre} / ${RazaAnimal.nombre}</td>
                    <td>
                        <button type="button" class="btn btn-success btn-sm" 
                                onclick="seleccionarEspecie('${item.id}', '${item.nombre}', '${RazaAnimal.id}', '${RazaAnimal.nombre}')">
                            Seleccionar
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        });

        document.getElementById('prevBtn').disabled = true;
        document.getElementById('nextBtn').disabled = true;
    });

    document.getElementById('prevBtn').addEventListener('click', function() {
        if (paginaActual > 1) {
            paginaActual--;
            cargarEspecies();
        }
    });

    document.getElementById('nextBtn').addEventListener('click', function() {
        if ((paginaActual * itemsPorPagina) < especies.length) {
            paginaActual++;
            cargarEspecies();
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        cargarEspecies();
    });
</script>

<!-- modal para ver la imagen -->
<div id="imageModal" class="modal" onclick="closeFullScreen()">
    <span class="close">&times;</span>
    <img class="modal-content" id="fullImage">
</div>
@endsection   
</body>
<script>
    function seleccionarCliente(codigo, nombres) {
        
        document.getElementById('propietario').value = nombres;
        document.getElementById('propietario_id').value = codigo;        
                
        var mensajeExito = document.getElementById('mensajeExito');
        mensajeExito.style.display = 'block';

        setTimeout(function() {
            mensajeExito.style.display = 'none';
        }, 2000);
        
    }
</script>
<script>
    function seleccionarEspecie(id, nombre, raza_id, nombreRaza) {
        
        document.getElementById('especie').value = nombreRaza;
        document.getElementById('especie_id').value = id;
        document.getElementById('raza_id').value = raza_id;        

        var mensajeExito = document.getElementById('mensajeExito2');
        mensajeExito.style.display = 'block';

        setTimeout(function() {
            mensajeExito.style.display = 'none';
        }, 2000);        
    }
</script>
<script>
    function seleccionarFormaAdquisicion(id, nombre) {
        
        document.getElementById('adquisicion').value = nombre;
        document.getElementById('adquisicion_id').value = id;        
                
        var mensajeExito = document.getElementById('mensajeExito3');
        mensajeExito.style.display = 'block';

        setTimeout(function() {
            mensajeExito.style.display = 'none';
        }, 2000);        
    }
</script>
<script>
    function seleccionarIncapacidad(id, descripcion) {
        
        document.getElementById('incapacidad').value = descripcion;
        document.getElementById('incapacidad_id').value = id;        
                
        var mensajeExito = document.getElementById('mensajeExito4');
        mensajeExito.style.display = 'block';

        setTimeout(function() {
            mensajeExito.style.display = 'none';
        }, 2000);        
    }
</script>
<script>
    function openFullScreen(src) {
        const image = document.getElementById("fullImage");
        image.src = src;
        image.onload = function () {
            image.style.width = "auto";
            image.style.height = "auto";
            document.getElementById("imageModal").style.display = "flex";
        };
    }

    function closeFullScreen() {
        document.getElementById("imageModal").style.display = "none";
    }
</script>
<script>
function toggleDecesoFields() {
    let estado = document.getElementById("estado").value;
    let decesoFields = document.getElementById("decesoFields");
    let motivoFields = document.getElementById("motivoFields");

    if (estado === "Fallecido") {
        decesoFields.style.display = "";
        motivoFields.style.display = "";
    } else {
        decesoFields.style.display = "none";
        motivoFields.style.display = "none";
    }
}
</script>

</html>