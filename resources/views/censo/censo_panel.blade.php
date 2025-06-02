@extends('layout.layout')
@section('content')
<div class="container mt-4">
    <h4>🔁 Panel de Reintentos de Censistas</h4>

    @if(session('mensaje'))
        <div class="alert alert-success">{{ session('mensaje') }}</div>
    @endif

    <form method="GET" class="d-flex mb-3">
        <input type="text" name="buscar" class="form-control me-2" placeholder="Buscar por código o nombre..." value="{{ $busqueda }}">
        <button type="submit" class="btn btn-outline-primary">Buscar</button>
    </form>

    <form method="POST" action="{{ route('panel.reintentos.reset') }}" class="mb-3">
        @csrf
        <button name="reset_all" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que quieres restablecer todos los intentos?')">
            🔄 Restablecer todos los intentos
        </button>
    </form>

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Intentos Realizados</th>
                <th>Totales</th>
                <th>Última Actualización</th>
                <th>Estado</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($intentos as $item)
            <tr>
                <td>{{ $item->id_censista }}</td>
                <td>{{ $item->nombre }} {{ $item->apellido }}</td>
                <td>{{ $item->intentos_realizados }}</td>
                <td>{{ $item->intentos_totales }}</td>
                <td>{{ $item->updated_at }}</td>
                <td>
                    @php
                        $estado = DB::table('censista')->where('codigo_estudiante', $item->id_censista)->value('activo');
                    @endphp
                    <span class="badge {{ $estado ? 'bg-success' : 'bg-secondary' }}">
                        {{ $estado ? 'Aprobado' : 'Pendiente' }}
                    </span>
                </td>
                <td>
                    <form method="POST" action="{{ route('panel.reintentos.estado') }}">
                        @csrf
                        <input type="hidden" name="censista_id" value="{{ $item->id_censista }}">
                        <input type="hidden" name="estado" value="{{ $estado ? '0' : '1' }}">
                        <button class="btn btn-sm {{ $estado ? 'btn-danger' : 'btn-success' }}">
                            {{ $estado ? 'Denegar' : 'Aprobar' }}
                        </button>
                    </form>

                    <form method="POST" action="{{ route('panel.reintentos.reset') }}" class="mt-1">
                        @csrf
                        <input type="hidden" name="codigo_estudiante" value="{{ $item->id_censista }}">
                        <button name="reset_one" class="btn btn-warning btn-sm">
                            Restablecer
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center">No se encontraron censistas.</td></tr>
        @endforelse
        </tbody>
    </table>
    <div class="d-flex justify-content-center mt-3">
        {{ $intentos->links() }}
    </div>
</div>
@endsection
