<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modulo Censo Animales</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/cssAnimalIMG.css') }}">
</head>
<body>
@extends('layout.layoutv2')

@section('title', 'Modulo Censo Animales')

@section('content')
<div class="form-container">
    <div class="row">
        <!-- Formulario al lado izquierdo -->
        <div class="col-md-6 mx-auto">
            <div class="form-container">
                <form action="{{ url('/CensoAnimales/RegistrarAnimal') }}" method="post" class="form-table" enctype="multipart/form-data"> 
                    @csrf                    
                    <h3 class="text-center mb-4">Censo de Animales</h3>
                    <div class="mb-3">
                        <label for="gestion" class="form-label">Seleccionar Gestion</label>
                        <input class="form-control" type="text" name="gestion" id="gestion" readonly required>
                        <input type="hidden" name="gestion_id" id="gestion_id" required>                        
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#censoModal">Añadir Gestion</button>
                    </div> 
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
                        <select class="form-select" name="carnetvacuna" id="d" required>
                            <option value="">Seleccione una opción</option>
                            <option value="tiene">tiene</option>
                            <option value="no_tiene">no tiene</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Fecha de la Última Vacuna</label>
                        <input class="form-control" type="date" name="datevacuna" id="datevacuna" required>
                    </div>
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
                    <div class="mb-3">
                        <label for="otb" class="form-label">Otb</label>
                        <input class="form-control" type="text" name="otb" id="otb" readonly required>
                        <input type="hidden" name="otb_id" id="otb_id" required>                        
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#otbModal">Añadir Otb</button>
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
                <a href="{{ route('animales.exportar') }}" class="btn btn-success mb-3">Descargar Excel</a>
                <div id="graficoColumnas" style="width: 100%; height: 500px;"></div>
                <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                <script>
                    google.charts.load('current', {'packages':['corechart']});
                    google.charts.setOnLoadCallback(drawChart);

                    function drawChart() {
                        const data = google.visualization.arrayToDataTable([
                            ['Categoría', 'Cantidad', { role: 'style' }],
                            @php
                                $totalCensados = $Animales->count();
                                $machos = $Animales->where('sexo', 'macho')->count();
                                $hembras = $Animales->where('sexo', 'hembra')->count();
                                $castrados = $Animales->where('castrado', 'si')->count();
                            @endphp
                            ['Total', {{ $totalCensados }}, 'color: #1E88E5'],
                            ['Machos', {{ $machos }}, 'color: #43A047'],
                            ['Hembras', {{ $hembras }}, 'color: #E53935'],
                            ['Castrados', {{ $castrados }}, 'color: #FDD835'],
                        ]);

                        const options = {
                            title: 'Reporte General de Animales Censados',
                            legend: { position: 'none' },
                            vAxis: { minValue: 0 },
                            backgroundColor: 'transparent',
                            bar: { groupWidth: '60%' },
                            animation: {
                                startup: true,
                                duration: 1000,
                                easing: 'out',
                            }
                        };

                        const chart = new google.visualization.ColumnChart(document.getElementById('graficoColumnas'));
                        chart.draw(data, options);
                    }
                </script>
            </div>
        </div>
    </div>
</div>
<!-- Modal para seleccionar Otb -->
<div class="modal fade" id="otbModal" tabindex="-1" aria-labelledby="otbModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="otbModalLabel">Seleccionar Otb</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="mensajeExito5" class="alert alert-success" style="display: none;">
                    Se ha añadido exitosamente
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Nombre</th>
                                <th>Opcion</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($Otb as $item)
                            <tr>
                                <td>{{ $item->nombre_area }}</td>                                
                                <td>
                                    <button type="button" class="btn btn-success btn-sm"
                                        onclick="seleccionarOtb('{{ $item->id }}', '{{ $item->nombre_area }}')">
                                        Seleccionar
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal para seleccionar Otb -->
<div class="modal fade" id="censoModal" tabindex="-1" aria-labelledby="censoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="censoModalLabel">Seleccionar Otb</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="mensajeExito6" class="alert alert-success" style="display: none;">
                    Se ha añadido exitosamente
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Gestion</th>
                                <th>Fecha de Inicio</th>
                                <th>Fecha de Fin</th>
                                <th>Coordinador</th>
                                <th>Opcion</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($Censo as $item)
                            <tr>
                                <td>{{ $item->gestion }}</td>
                                <td>{{ $item->fecha_inicio }}</td>
                                <td>{{ $item->fecha_fin }}</td>
                                <td>{{ $item->coordinador }}</td>                                
                                <td>
                                    <button type="button" class="btn btn-success btn-sm"
                                        onclick="seleccionarCenso('{{ $item->id }}', '{{ $item->gestion }}')">
                                        Seleccionar
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
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
    let paginaActualCliente = 1;
    const itemsPorPaginaClientes = 2;
    let busquedaCliente = '';

    async function cargarClientes() {
        const url = `/propietarios/ajax?page=${paginaActualCliente}&busqueda=${busquedaCliente}`;
        const res = await fetch(url);
        const data = await res.json();

        const tbody = document.querySelector('#tablaClientes tbody');
        tbody.innerHTML = '';

        data.data.forEach(item => {
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
        document.getElementById('prevBtnCliente').disabled = !data.prev_page_url;
        document.getElementById('nextBtnCliente').disabled = !data.next_page_url;
    }

    document.getElementById('btnBuscarCliente').addEventListener('click', () => {
        busquedaCliente = document.getElementById('busquedaCliente').value.trim();
        paginaActualCliente = 1;
        cargarClientes();
    });

    document.getElementById('prevBtnCliente').addEventListener('click', () => {
        if (paginaActualCliente > 1) {
            paginaActualCliente--;
            cargarClientes();
        }
    });
    document.getElementById('nextBtnCliente').addEventListener('click', () => {
        paginaActualCliente++;
        cargarClientes();
    });
    document.addEventListener('DOMContentLoaded', () => {
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
                                onclick="seleccionarEspecie('${item.id}', '${item.nombre}', '${RazaAnimal.id}')">
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
                                onclick="seleccionarEspecie('${item.id}', '${item.nombre}', '${RazaAnimal.id}')">
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
    function seleccionarCenso(id, gestion) {
        
        document.getElementById('gestion').value = gestion;
        document.getElementById('gestion_id').value = id;        
                
        var mensajeExito = document.getElementById('mensajeExito6');
        mensajeExito.style.display = 'block';

        setTimeout(function() {
            mensajeExito.style.display = 'none';
        }, 2000);
        
    }
</script>
<script>
    function seleccionarOtb(id, nombre_area) {
        
        document.getElementById('otb').value = nombre_area;
        document.getElementById('otb_id').value = id;        
                
        var mensajeExito = document.getElementById('mensajeExito5');
        mensajeExito.style.display = 'block';

        setTimeout(function() {
            mensajeExito.style.display = 'none';
        }, 2000);
        
    }
</script>
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
    function seleccionarEspecie(id, nombre, raza_id) {
        
        document.getElementById('especie').value = nombre;
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