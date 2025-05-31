@extends('layout.layoutv2')

@section('title', 'Módulo Censo')

@section('content')
<div class="container py-5">
    <div class="row g-4">
        <!-- Formulario a la izquierda -->
        <div class="col-md-6">
            <div class="card shadow-sm p-4">
                <h3 class="text-center mb-4">Registrar Datos del Censo</h3>

                @if (session('agregar'))
                    <div class="alert alert-success text-center">
                        {{ session('agregar') }}
                    </div>
                @endif

                <form action="{{ url('/CensoAnimales/RegistrarCenso') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="gestion" class="form-label">Gestión:</label>
                        <input type="text" class="form-control" name="gestion" required>
                    </div>

                    <div class="mb-3">
                        <label for="fecha_inicio" class="form-label">Fecha Inicio:</label>
                        <input type="date" class="form-control" name="fecha_inicio" required>
                    </div>

                    <div class="mb-3">
                        <label for="fecha_fin" class="form-label">Fecha Fin:</label>
                        <input type="date" class="form-control" name="fecha_fin" required>
                    </div>

                    <div class="mb-3">
                        <label for="coordinador" class="form-label">Coordinador:</label>
                        <input type="text" class="form-control" name="coordinador" required>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-success">Guardar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabla a la derecha -->
        <div class="col-md-6">
            <div class="card shadow-sm p-4">
                <h4 class="text-center mb-3">Censos Registrados</h4>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="table-primary">
                            <tr>
                                <th>Gestión</th>
                                <th>Inicio</th>
                                <th>Fin</th>
                                <th>Coordinador</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($Censo as $item)
                                <tr>
                                    <td>{{ $item->gestion }}</td>
                                    <td>{{ $item->fecha_inicio }}</td>
                                    <td>{{ $item->fecha_fin }}</td>
                                    <td>{{ $item->coordinador }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
