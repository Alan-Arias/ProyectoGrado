<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo Censo Enfermedades</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
@extends('layout.layoutv2')

@section('title', 'Módulo Censo Enfermedades')

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
                <form action="{{ url('/CensoAnimales/RegistrarEnfermedad') }}" method="post" enctype="multipart/form-data" class="form-table">
                @csrf
                <h3 class="text-center mb-4">Observación de Enfermedades</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="nro_manzana">Nombre del Animal:</label>
                        <input type="text" class="form-control full-width" name="nombre_animal" id="nombreAnimal" required readonly>
                        <input type="hidden" class="form-control full-width" name="animal_id" id="animalId" required>
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#animalSelector">
                            Añadir Animal
                        </button>
                    </div>

                    <div class="form-group">
                        <label for="nombre_enfermedad">Nombre de la Enfermedad:</label>
                        <input type="text" class="form-control full-width" name="nombre_enfermedad" id="nombre_enfermedad" required readonly>
                        <input type="hidden" class="form-control full-width" name="enfermedadId" id="enfermedadId">

                        <div class="mt-2">
                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#tipoEnfermedadSelector">
                                Añadir Enfermedad
                            </button>

                            <button type="button" class="btn btn-secondary" id="sinEnfermedadBtn">
                                Sin Enfermedad
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="fecha">Fecha Inicio:</label>
                        <input type="date" class="form-control full-width" name="fecha" id="fechaInicio" required>
                    </div>

                    <div class="form-group">
                        <label for="caracteristicas">Características:</label>
                        <input type="text" class="form-control full-width" name="caracteristicas" id="caracteristicas" required>
                    </div>

                    <div class="form-group">
                        <label for="vacuna">(Opcional) Vacuna:</label>
                        <input type="text" class="form-control full-width" name="vacuna" placeholder="nombre de la vacuna aplicada">
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-success">Guardar</button>
                    </div>
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
            <!-- CDN de Chart.js -->
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

            <div class="mb-4">
                <canvas id="estadoAnimalesChart" width="400" height="200"></canvas>
            </div>

            <script>
                const ctx = document.getElementById('estadoAnimalesChart').getContext('2d');
                const estadoAnimalesChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: ['Sanos', 'Enfermos'],
                        datasets: [{
                            label: 'Estado de los animales',
                            data: [{{ $totalSanos }}, {{ $totalEnfermos }}],
                            backgroundColor: ['#28a745', '#dc3545'],
                            borderColor: ['#fff', '#fff'],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: 'Distribución de Animales Sanos y Enfermos'
                            }
                        }
                    }
                });
            </script>
        </div>
    </div>
</div>
<!-- Modal para seleccionar animales -->
<div class="modal fade" id="animalSelector" tabindex="-1" aria-labelledby="animalSelectorLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="animalSelectorLabel">Seleccionar Animal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="text" id="searchAnimalInput" class="form-control" placeholder="Buscar por nombre o propietario...">
                </div>
                <div class="table-responsive">
                    <table class="table table-hover" id="animalTable">
                        <thead class="thead-dark">
                            <tr>
                                <th>Nombre</th>
                                <th>Raza</th>
                                <th>Color</th>
                                <th>Propietario</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>     

                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between">
                    <button class="btn btn-secondary" id="prevAnimalButton" disabled>Anterior</button>
                    <button class="btn btn-secondary" id="nextAnimalButton">Siguiente</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<script>
    const censoData = @json($censo_data);
    let currentPage = 1;
    let searchTerm = '';

    function fetchAnimals() {
        const tableBody = document.querySelector('#animalTable tbody');
        tableBody.innerHTML = `<tr><td colspan="6" class="text-center">Cargando...</td></tr>`;

        fetch(`/buscar-animales?page=${currentPage}&search=${encodeURIComponent(searchTerm)}&censo_data=${censoData}`)
            .then(response => response.json())
            .then(data => {
                tableBody.innerHTML = '';

                if (data.data.length === 0) {
                    tableBody.innerHTML = `<tr><td colspan="6" class="text-center">No se encontraron resultados.</td></tr>`;
                    return;
                }

                data.data.forEach(animal => {
                    const { id, nombre, raza, color, propietario } = animal;
                    const razaNombre = raza ? raza.nombre : 'No disponible';
                    const propietarioNombre = propietario ? propietario.nombres : 'No disponible';

                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${nombre || 'No disponible'}</td>
                        <td>${razaNombre}</td>
                        <td>${color || 'No disponible'}</td>
                        <td>${propietarioNombre}</td>
                        <td><button class="btn btn-success" onclick="selectAnimal('${id}', '${nombre}')">Seleccionar</button></td>
                    `;
                    tableBody.appendChild(row);
                });

                document.getElementById('prevAnimalButton').disabled = !data.prev_page_url;
                document.getElementById('nextAnimalButton').disabled = !data.next_page_url;
            })
            .catch(error => {
                console.error('Error al cargar animales:', error);
                tableBody.innerHTML = `<tr><td colspan="6" class="text-center text-danger">Error al cargar animales.</td></tr>`;
            });
    }

    document.getElementById('searchAnimalInput').addEventListener('input', function () {
        searchTerm = this.value.trim().toLowerCase();
        currentPage = 1;
        fetchAnimals();
    });

    document.getElementById('prevAnimalButton').addEventListener('click', function () {
        if (currentPage > 1) {
            currentPage--;
            fetchAnimals();
        }
    });

    document.getElementById('nextAnimalButton').addEventListener('click', function () {
        currentPage++;
        fetchAnimals();
    });

    document.addEventListener('DOMContentLoaded', function () {
        fetchAnimals(); // cargar al inicio
    });

    function selectAnimal(animalId, animalName) {
        console.log(`Animal seleccionado: ${animalName} (ID: ${animalId})`);
        document.getElementById('nombreAnimal').value = animalName;
        document.getElementById('animalId').value = animalId;
        const modal = bootstrap.Modal.getInstance(document.getElementById('animalSelector'));
        modal.hide();
    }
</script>

<div class="modal fade" id="tipoEnfermedadSelector" tabindex="-1" aria-labelledby="tipoEnfermedadSelectorLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="tipoEnfermedadSelectorLabel">Seleccionar Tipo de Enfermedad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="text" id="searchTipoEnfermedadInput" class="form-control" placeholder="Buscar por nombre ...">
                </div>
                <div class="table-responsive">
                    <table class="table table-hover" id="tipoEnfermedadTable">
                        <thead class="thead-dark">
                            <tr>
                                <th>Nombre</th>
                                <th>Especie</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ListaEnfermedad as $enfermedad)
                                <tr>
                                    <td>{{ $enfermedad->nombre }}</td>
                                    <td>{{ $enfermedad->especie_enf }}</td>
                                    <td>
                                        <button class="btn btn-success" onclick="selectTipoEnfermedad('{{ $enfermedad->id }}', '{{ $enfermedad->nombre }}')">Seleccionar</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between">
                    <button class="btn btn-secondary" id="prevTipoEnfermedadButton" disabled>Anterior</button>
                    <button class="btn btn-secondary" id="nextTipoEnfermedadButton">Siguiente</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('sinEnfermedadBtn').addEventListener('click', function() {
        // Poner "Sin enfermedad" en el input
        document.getElementById('nombre_enfermedad').value = 'Sin enfermedad';
        
        // Vaciar el ID oculto para que se guarde NULL
        document.getElementById('enfermedadId').value = '';

        // Poner "No hay datos" en características
        document.getElementById('caracteristicas').value = 'No hay datos';

        // Poner fecha actual en fecha_inicio
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');
        document.getElementById('fechaInicio').value = `${year}-${month}-${day}`;
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tableBody = document.querySelector('#tipoEnfermedadTable tbody');

        // Evento de búsqueda
        document.getElementById('searchTipoEnfermedadInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            const rows = tableBody.querySelectorAll('tr');

            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                const nombre = cells[0].textContent.toLowerCase();
                const especie = cells[1].textContent.toLowerCase();
                
                if (nombre.includes(searchTerm) || especie.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });

    function selectTipoEnfermedad(enfermedadId, enfermedadName) {
        document.getElementById('nombre_enfermedad').value = enfermedadName;
        document.getElementById('enfermedadId').value = enfermedadId;
        const modal = bootstrap.Modal.getInstance(document.getElementById('tipoEnfermedadSelector'));
        modal.hide();
    }
</script>
@endsection 
</body>
</html>