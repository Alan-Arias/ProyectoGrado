@extends('layout.layout')

@section('title', 'Gestionar Backups')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Gestión de Backups</h2>

    {{-- Mensaje de advertencia --}}
    <div class="alert alert-warning" role="alert">
        Antes de descargar un archivo backup, revise el diagrama de clases para evitar llaves foráneas nulas al momento de ejecutar el respaldo.
    </div>

    {{-- Botones para generar backups --}}
    <div class="mb-4">
        <a href="{{ route('backup.full') }}" class="btn btn-primary">Generar Full Backup</a>
        <a href="{{ route('backup.partial') }}" class="btn btn-success">Generar Backup de Inserts</a>
    </div>
            @if (session('agregar'))
                <div class="alert alert-success mt-3">
                    <p>{{ session('agregar') }}</p>
                </div>
            @endif
    {{-- Tabla para mostrar los backups generados --}}
    <div class="col-md-12">
        <div class="table-container table-responsive">
            <div class="card">
                <div class="card-header">Historial de Backups</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Nombre de Archivo</th>
                                    <th>Ruta</th>
                                    <th>Tipo</th>
                                    <th>Tamaño (KB)</th>
                                    <th>Fecha de Creación</th>
                                    <th>Usuario</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($backups as $backup)
                                <tr>
                                    <td>{{ $backup->nombre_archivo }}</td>
                                    <td>{{ $backup->ruta_archivo }}</td>
                                    <td>{{ ucfirst($backup->tipo_respaldo) }}</td>
                                    <td>{{ number_format($backup->tamaño / 1024, 2) }}</td>
                                    <td>{{ $backup->fecha_creacion }}</td>
                                    <td>{{ $backup->usuario->nombre ?? 'Desconocido' }}</td>
                                    <td>
                                        <a href="{{ route('backup.download', $backup->id) }}" class="btn btn-info btn-sm">Descargar</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{-- Mensaje si no hay backups --}}
                        @if ($backups->isEmpty())
                            <p class="text-center mt-3">No hay backups disponibles.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

