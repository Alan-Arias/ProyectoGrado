<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Historial Sanitario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/cssAnimalIMG.css') }}">
</head>
<body>
@extends('layout.layout')

@section('title', 'Gestionar Historial Sanitario')

@section('content')
<div class="contenedor">
    <h2 class="text-center">Gestionar Historial Sanitario</h2>
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
                                <a class="nav-link text-white" href="{{ url('/Tratamiento') }}">Tratamiento</a>
                            </li>                            
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
        <!-- Formulario al lado izquierdo -->
        <div class="col-md-6 mx-auto">
            <div class="form-container">
                <form action="{{ url('/HistorialSanitario/Registrar') }}" method="post" class="form-table" enctype="multipart/form-data"> 
                    @csrf
                    <h3 class="text-center mb-4">Registrar Historial Sanitario</h3>
                    <div class="mb-3">
                        <label for="vname" class="form-label">Nombre de la Vacuna</label>
                        <input type="hidden" name="vname_id" id="vname_id" required>
                        <input type="hidden" name="vtname_id" id="vtname_id" required>
                        <input class="form-control" type="text" name="vname" id="vname" placeholder="Nombre de la Vacuna" readonly required>
                        <button type="button" class="btn btn-warning mt-2" data-bs-toggle="modal" data-bs-target="#vacunaModal">
                            Buscar Vacuna
                        </button>
                    </div>
                    <div class="mb-3">
                        <label for="tname" class="form-label">Tratamiento</label>
                        <input type="hidden" name="tname_id" id="tname_id" required>
                        <input class="form-control" type="text" name="tname" id="tname" placeholder="Nombre del Tratamiento" readonly required>
                        <button type="button" class="btn btn-warning mt-2" data-bs-toggle="modal" data-bs-target="#tratamientoModal">
                            Buscar Tratamiento
                        </button>
                    </div>
                    <div class="mb-3">
                        <label for="personal" class="form-label">Personal Sanitario</label>
                        <div id="razas-container">
                            <input class="form-control" type="text" name="personal" placeholder="Indique un nombre" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="namea" class="form-label">Nombre del Animal</label>
                        <input type="hidden" name="name_id" id="name_id" required>
                        <input class="form-control" type="text" name="namea" id="namea" placeholder="Nombre del animal" readonly required>
                        <button type="button" class="btn btn-warning mt-2" data-bs-toggle="modal" data-bs-target="#animalModal">
                            Buscar Animal
                        </button>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Fecha de la Aplicación</label>
                        <input class="form-control" type="date" name="date" id="date" required>
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
                                <option value="3" {{ request('perPage') == 3 ? 'selected' : '' }}>3</option>
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
                            <th>Tipo de la Vacuna</th>
                            <th>Nombre del Propietario</th>
                            <th>Nombre del Animal</th>
                            <th>Encargado/s</th>
                            <th>Fecha de Aplicación</th>
                            <th>Foto del Animal</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($Historial as $item)
                        <tr>
                            <td>{{ $item->nombre_vacuna }}</td>
                            <td>{{ $item->Vacuna->TipoVacuna->nombre }}</td>
                            <td>{{ $item->animal->propietario->nombres }}</td>                    
                            <td>{{ optional($item->animal)->nombre }}</td> 
                            <td>{{ $item->PersonalVacuna->nombre }}</td>
                            <td>{{ $item->fecha_aplicacion }}</td>
                            <td>
                                @if($item->animal->foto_animal)
                                        <img src="{{ asset($item->animal->foto_animal) }}" alt="Foto de {{ $item->animal->nombre }}" 
                                            width="100" class="thumbnail" onclick="openFullScreen('{{ asset($item->animal->foto_animal) }}')">
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
                    @if ($Historial->lastPage() > 1)
                        <ul class="pagination">
                            {{-- Botón "Anterior" --}}
                            <li class="page-item {{ ($Historial->currentPage() == 1) ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $Historial->previousPageUrl() }}" aria-label="Anterior">&laquo;</a>
                            </li>

                            {{-- Primera página siempre visible --}}
                            <li class="page-item {{ ($Historial->currentPage() == 1) ? 'active' : '' }}">
                                <a class="page-link" href="{{ $Historial->url(1) }}">1</a>
                            </li>

                            {{-- "..." si la página actual está lejos del inicio --}}
                            @if ($Historial->currentPage() > 4)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif

                            {{-- Página actual y una antes/después --}}
                            @for ($i = max(2, $Historial->currentPage() - 1); $i <= min($Historial->lastPage() - 1, $Historial->currentPage() + 1); $i++)
                                <li class="page-item {{ ($Historial->currentPage() == $i) ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $Historial->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor

                            {{-- "..." si la página actual está lejos del final --}}
                            @if ($Historial->currentPage() < $Historial->lastPage() - 3)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif

                            {{-- Última página siempre visible --}}
                            @if ($Historial->lastPage() > 1)
                                <li class="page-item {{ ($Historial->currentPage() == $Historial->lastPage()) ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $Historial->url($Historial->lastPage()) }}">{{ $Historial->lastPage() }}</a>
                                </li>
                            @endif

                            {{-- Botón "Siguiente" --}}
                            <li class="page-item {{ ($Historial->currentPage() == $Historial->lastPage()) ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $Historial->nextPageUrl() }}" aria-label="Siguiente">&raquo;</a>
                            </li>
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div> 

<!-- Modal para seleccionar Animales -->
<div class="modal fade" id="animalModal" tabindex="-1" aria-labelledby="animalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="animalModalLabel">Seleccionar Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="mensajeExito" class="alert alert-success" style="display: none;">
                    Se ha añadido exitosamente
                </div>

                <!-- Barra de búsqueda -->
                <div class="mb-3 d-flex">
                    <input type="text" id="busquedaAnimal" class="form-control" placeholder="Buscar animal...">
                    <button class="btn btn-primary ms-2" id="btnBuscarAnimal">Buscar</button>
                </div>

                <!-- Tabla de Animales -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="tablaAnimales">
                        <thead class="table-dark">
                            <tr>
                                <th>Nombre</th>
                                <th>Fecha de Nacimiento</th>
                                <th>Castrado</th>
                                <th>Estado</th>
                                <th>Propietario</th>
                                <th>Foto</th>
                                <th>Opción</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div id="paginacionAnimales" class="d-flex justify-content-center mt-3">
                    <button class="btn btn-secondary" id="prevBtnAnimal" disabled>Anterior</button>
                    <button class="btn btn-secondary" id="nextBtnAnimal">Siguiente</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Script JS -->
<script>
    const animalesData = @json($Animales);
    const itemsPorPaginaAnimales = 3;
    let paginaActualAnimal = 1;
    let animalesFiltrados = [...animalesData];

    function cargarAnimales() {
        const tbody = document.querySelector('#tablaAnimales tbody');
        tbody.innerHTML = '';
        const inicio = (paginaActualAnimal - 1) * itemsPorPaginaAnimales;
        const fin = inicio + itemsPorPaginaAnimales;
        const animalesPagina = animalesFiltrados.slice(inicio, fin);

        animalesPagina.forEach(item => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${item.nombre}</td>
                <td>${item.fecha_nac}</td>
                <td>${item.castrado}</td>
                <td>${item.estado}</td>
                <td>${item.propietario ? item.propietario.nombres : ''}</td>
                <td><img src="${item.foto_animal}" alt="Foto de ${item.nombre}" width="100"></td>
                <td>
                    <button type="button" class="btn btn-success btn-sm"
                            onclick="seleccionarAnimal('${item.id}', '${item.nombre}')">
                        Seleccionar
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });

        document.getElementById('prevBtnAnimal').disabled = paginaActualAnimal === 1;
        document.getElementById('nextBtnAnimal').disabled = fin >= animalesFiltrados.length;
    }

    function restablecerBusquedaAnimal() {
        document.getElementById('busquedaAnimal').value = '';
        animalesFiltrados = [...animalesData];
        paginaActualAnimal = 1;
        cargarAnimales();
    }

    document.getElementById('btnBuscarAnimal').addEventListener('click', function() {
        const busqueda = document.getElementById('busquedaAnimal').value.trim().toLowerCase();
        if (busqueda === '') {
            restablecerBusquedaAnimal();
            return;
        }
        animalesFiltrados = animalesData.filter(item =>
            item.nombre.toLowerCase().includes(busqueda) ||
            (item.propietario && item.propietario.nombres.toLowerCase().includes(busqueda))
        );
        paginaActualAnimal = 1;
        cargarAnimales();
    });

    document.getElementById('prevBtnAnimal').addEventListener('click', function() {
        if (paginaActualAnimal > 1) {
            paginaActualAnimal--;
            cargarAnimales();
        }
    });

    document.getElementById('nextBtnAnimal').addEventListener('click', function() {
        if ((paginaActualAnimal * itemsPorPaginaAnimales) < animalesFiltrados.length) {
            paginaActualAnimal++;
            cargarAnimales();
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        cargarAnimales();
    });
</script>
<!-- Modal para seleccionar Vacunas -->
<div class="modal fade" id="vacunaModal" tabindex="-1" aria-labelledby="vacunaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="vacunaModalLabel">Seleccionar Vacuna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="mensajeExito2" class="alert alert-success" style="display: none;">
                    Se ha añadido exitosamente
                </div>

                <!-- Barra de búsqueda -->
                <div class="mb-3 d-flex">
                    <input type="text" id="busquedaVacuna" class="form-control" placeholder="Buscar vacuna o tipo...">
                    <button class="btn btn-primary ms-2" id="btnBuscarVacuna">Buscar</button>
                </div>

                <!-- Mensaje de no resultados -->
                <div id="mensajeSinResultadosVacuna" class="alert alert-warning" style="display: none;">
                    No se encontraron resultados.
                </div>

                <!-- Tabla de Vacunas -->
                <div class="table-responsive" id="contenedorTablaVacunas">
                    <table class="table table-striped table-hover" id="tablaVacunas">
                        <thead class="table-dark">
                            <tr>
                                <th>Tipo Vacuna / Vacuna</th>
                                <th>Opción</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div id="paginacionVacunas" class="d-flex justify-content-center mt-3">
                    <button class="btn btn-secondary" id="prevBtnVacuna" disabled>Anterior</button>
                    <button class="btn btn-secondary" id="nextBtnVacuna">Siguiente</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
  const vacunasData = @json($Vacunas);
  const itemsPorPaginaVacunas = 5;
  let paginaActualVacuna = 1;
  let vacunasFiltradas = [...vacunasData];

  function mostrarMensajeSinResultados(mostrar) {
      document.getElementById('mensajeSinResultadosVacuna').style.display = mostrar ? 'block' : 'none';
      document.getElementById('contenedorTablaVacunas').style.display = mostrar ? 'none' : 'block';
      document.getElementById('paginacionVacunas').style.display = mostrar ? 'none' : 'flex';
  }

  function cargarVacunas() {
      const tbody = document.querySelector('#tablaVacunas tbody');
      tbody.innerHTML = '';

      const inicio = (paginaActualVacuna - 1) * itemsPorPaginaVacunas;
      const fin = inicio + itemsPorPaginaVacunas;
      const vacunasPagina = vacunasFiltradas.slice(inicio, fin);

      if (vacunasPagina.length === 0) {
          mostrarMensajeSinResultados(true);
          return;
      }

      mostrarMensajeSinResultados(false);

      vacunasPagina.forEach(item => {
          const tr = document.createElement('tr');
          tr.innerHTML = `
              <td>${item.TipoVacuna?.nombre || 'Sin tipo'} / ${item.nombre}</td>
              <td>
                  <button type="button" class="btn btn-success btn-sm"
                          onclick="seleccionarVacuna('${item.TipoVacuna?.id || ''}', '${item.TipoVacuna?.nombre || ''}', '${item.id}', '${item.nombre}')">
                      Seleccionar
                  </button>
              </td>
          `;
          tbody.appendChild(tr);
      });

      document.getElementById('prevBtnVacuna').disabled = paginaActualVacuna === 1;
      document.getElementById('nextBtnVacuna').disabled = fin >= vacunasFiltradas.length;
  }

  function restablecerBusquedaVacuna() {
      document.getElementById('busquedaVacuna').value = '';
      vacunasFiltradas = [...vacunasData];
      paginaActualVacuna = 1;
      cargarVacunas();
  }

  function buscarVacunas() {
      const busqueda = document.getElementById('busquedaVacuna').value.trim().toLowerCase();
      if (busqueda === '') {
          restablecerBusquedaVacuna();
          return;
      }

      vacunasFiltradas = vacunasData.filter(item =>
          item.nombre.toLowerCase().includes(busqueda) ||
          (item.TipoVacuna && item.TipoVacuna.nombre.toLowerCase().includes(busqueda))
      );

      paginaActualVacuna = 1;
      cargarVacunas();
  }

  document.getElementById('btnBuscarVacuna').addEventListener('click', buscarVacunas);

  document.getElementById('busquedaVacuna').addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
          buscarVacunas();
      }
  });

  document.getElementById('prevBtnVacuna').addEventListener('click', function () {
      if (paginaActualVacuna > 1) {
          paginaActualVacuna--;
          cargarVacunas();
      }
  });

  document.getElementById('nextBtnVacuna').addEventListener('click', function () {
      if ((paginaActualVacuna * itemsPorPaginaVacunas) < vacunasFiltradas.length) {
          paginaActualVacuna++;
          cargarVacunas();
      }
  });

  document.addEventListener('DOMContentLoaded', function () {
      cargarVacunas();
  });
</script>
<!-- Modal para seleccionar Tratamiento -->
<div class="modal fade" id="tratamientoModal" tabindex="-1" aria-labelledby="tratamientoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tratamientoModalLabel">Seleccionar Tratamiento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="mensajeExito3" class="alert alert-success" style="display: none;">
                    Se ha añadido exitosamente
                </div>
                <div class="mb-3 d-flex">
                    <input type="text" id="busquedaTratamiento" class="form-control" placeholder="Buscar tratamiento o producto...">
                    <button class="btn btn-primary ms-2" id="btnBuscarTratamiento">Buscar</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="tablaTratamientos">
                        <thead class="table-dark">
                            <tr>
                                <th>Descripción del Tratamiento</th>
                                <th>Nombre del Producto</th>
                                <th>Fecha</th>
                                <th>Tiempo</th>
                                <th>Opción</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div id="paginacionTratamientos" class="d-flex justify-content-center mt-3">
                    <button class="btn btn-secondary me-2" id="prevBtnTratamiento" disabled>Anterior</button>
                    <button class="btn btn-secondary" id="nextBtnTratamiento">Siguiente</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    const tratamientosData = @json($Tratamiento);
    const itemsPorPaginaTratamientos = 4;
    let paginaActualTratamiento = 1;

    function cargarTratamientos() {
        const tbody = document.querySelector('#tablaTratamientos tbody');
        tbody.innerHTML = '';

        const inicio = (paginaActualTratamiento - 1) * itemsPorPaginaTratamientos;
        const fin = inicio + itemsPorPaginaTratamientos;
        const tratamientosPagina = tratamientosData.slice(inicio, fin);

        tratamientosPagina.forEach(item => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${item.Descripción}</td>
                <td>${item.producto}</td>
                <td>${item.Fecha}</td>
                <td>${item.Tiempo}</td>
                <td>
                    <button type="button" class="btn btn-success btn-sm"
                        onclick="seleccionarTratamiento('${item.id}', '${item.producto}')">
                        Seleccionar
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });

        document.getElementById('prevBtnTratamiento').disabled = paginaActualTratamiento === 1;
        document.getElementById('nextBtnTratamiento').disabled = fin >= tratamientosData.length;
    }

    function restablecerBusquedaTratamientos() {
        document.getElementById('busquedaTratamiento').value = '';
        paginaActualTratamiento = 1;
        cargarTratamientos();
    }

    document.getElementById('btnBuscarTratamiento').addEventListener('click', function () {
        const busqueda = document.getElementById('busquedaTratamiento').value.trim().toLowerCase();
        if (busqueda === '') {
            restablecerBusquedaTratamientos();
            return;
        }

        const tbody = document.querySelector('#tablaTratamientos tbody');
        tbody.innerHTML = '';

        const resultadosFiltrados = tratamientosData.filter(item =>
            item.Descripción.toLowerCase().includes(busqueda) ||
            item.producto.toLowerCase().includes(busqueda)
        );

        resultadosFiltrados.forEach(item => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${item.Descripción}</td>
                <td>${item.producto}</td>
                <td>${item.Fecha}</td>
                <td>${item.Tiempo}</td>
                <td>
                    <button type="button" class="btn btn-success btn-sm"
                        onclick="seleccionarTratamiento('${item.id}', '${item.producto}')">
                        Seleccionar
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });

        document.getElementById('prevBtnTratamiento').disabled = true;
        document.getElementById('nextBtnTratamiento').disabled = true;
    });

    document.getElementById('prevBtnTratamiento').addEventListener('click', function () {
        if (paginaActualTratamiento > 1) {
            paginaActualTratamiento--;
            cargarTratamientos();
        }
    });

    document.getElementById('nextBtnTratamiento').addEventListener('click', function () {
        if ((paginaActualTratamiento * itemsPorPaginaTratamientos) < tratamientosData.length) {
            paginaActualTratamiento++;
            cargarTratamientos();
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        cargarTratamientos();
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
    function seleccionarAnimal(id, nombre) {
        
        document.getElementById('namea').value = nombre;
        document.getElementById('name_id').value = id;        
                
        var mensajeExito = document.getElementById('mensajeExito');
        mensajeExito.style.display = 'block';

        setTimeout(function() {
            mensajeExito.style.display = 'none';
        }, 2000);
        
    }
</script>
<script>
    function seleccionarVacuna(idtipo, nombreTipo, idvacuna, nombreVacuna) {
        document.getElementById('vname').value = nombreVacuna;
        document.getElementById('vname_id').value = idvacuna;
        document.getElementById('vtname_id').value = idtipo;        

        var mensajeExito = document.getElementById('mensajeExito2');
        mensajeExito.style.display = 'block';

        setTimeout(function() {
            mensajeExito.style.display = 'none';
        }, 2000);        
    }
</script>
<script>
    function seleccionarTratamiento(id, nombre) {
        
        document.getElementById('tname').value = nombre;
        document.getElementById('tname_id').value = id;      
                
        var mensajeExito = document.getElementById('mensajeExito3');
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
</html>