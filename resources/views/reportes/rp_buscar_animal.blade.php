@extends('layout.layout')

@section('title', 'Gestionar Reportes')

@section('content')
<link rel="stylesheet" href="{{ asset('css/cssRP_Buscar_Animal.css') }}">

<style>
    .grafico-container {
        background-color: #f8f9fa;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
    }
    .card-title {
        font-size: 1.1rem;
    }
    canvas#graficoAnimales {
        max-height: 350px;
    }
</style>

<div class="container py-5">
    {{-- Gráfico --}}
    <div style="display: flex; justify-content: center; align-items: center; width: 100%;">
        <div id="grafico3d" style="width: 100%; max-width: 500px; min-width: 280px; height: 400px; margin: 0 auto;"></div>
    </div>
    <br>
    {{-- Mensaje de error --}}
    @if(session('error'))
        <div class="alert alert-danger shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    {{-- Formularios de búsqueda --}}
    {{-- (No modificado según tu solicitud) --}}
    <div class="card mb-4 p-4 shadow-sm">
        <h5 class="mb-3">Buscar un animal por características:</h5>
        <form action="{{ route('reporteAnimal') }}" method="GET" class="row g-3 align-items-center">
            <div class="col-md-4">
                <input type="search" name="query" class="form-control" placeholder="Nombre, color o dueño" value="{{ request('query') }}">
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i> Buscar</button>
            </div>
        </form>

        <hr class="my-4">

        <h5 class="mb-3">Buscar por edad:</h5>
        <form action="{{ route('reporteAnimal') }}" method="GET" class="row g-3 align-items-center">
            <div class="col-md-3">
                <input type="number" name="edad_min" class="form-control" placeholder="Edad mínima" min="0" value="{{ request('edad_min') }}">
            </div>
            <div class="col-md-3">
                <input type="number" name="edad_max" class="form-control" placeholder="Edad máxima" min="0" value="{{ request('edad_max') }}">
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-success" type="submit"><i class="fas fa-search"></i> Buscar</button>
            </div>
        </form>
    </div>

    <div class="cards-wrapper">
        @forelse ($animales as $animal)
            <div class="card-container">
            <div class="card">
                <div class="img-content">
                @if($animal->foto_animal)
                    <img src="{{ asset($animal->foto_animal) }}" alt="Foto de {{ $animal->nombre }}" />
                @else
                    <img src="{{ asset('images/default-animal.png') }}" alt="Sin foto" />
                @endif
                </div>
                <div class="content">
                <p class="heading">{{ $animal->nombre }}</p>
                <p><strong>Edad:</strong> {{ $animal->edad }}</p>
                <p><strong>Color:</strong> {{ $animal->color }}</p>
                <p><strong>Raza:</strong>{{ $animal->raza->nombre }}</p>
                <p><strong>Sexo:</strong> {{ $animal->sexo }}</p>
                @if ($animal->censo_data === 'si')
                    <p class="text-warning fw-bold">Animal censado</p>
                @endif
                <p><strong>Propietario:</strong> {{ $animal->propietario->nombres ?? 'Sin propietario' }}</p>
                </div>
            </div>
            </div>
        @empty
            <p style="text-align: center;">No se encontraron animales.</p>
        @endforelse
    </div>
</div>
</div>

{{-- Chart JS --}}
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-3d.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script>
Highcharts.chart('grafico3d', {
    chart: {
        type: 'pie',
        options3d: {
            enabled: true,
            alpha: 45,
            beta: 0,
            depth: 40,
            viewDistance: 20
        },
        backgroundColor: '#f8f9fa'
    },
    title: {
        text: 'Reporte General de Animales (3D)',
        style: {
            fontSize: '1.5rem'
        }
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            depth: 40,
            dataLabels: {
                enabled: true,
                format: '{point.name}: {point.y}',
                style: {
                    fontSize: '0.9rem'
                }
            },
            edgeColor: '#ffffff',
            edgeWidth: 1
        }
    },
    tooltip: {
        backgroundColor: '#333',
        borderColor: '#ccc',
        style: {
            color: '#fff'
        }
    },
    series: [{
        name: 'Cantidad de Animales',
        data: [
            { name: 'Total', y: {{ $totalAnimales }}, color: '#0d6efd' },
            { name: 'Castrados', y: {{ $totalCastrados }}, color: '#198754' },
            { name: 'Hembras', y: {{ $totalHembras }}, color: '#dc3545' },
            { name: 'Machos', y: {{ $totalMachos }}, color: '#ffc107' }
        ]
    }]
});
</script>
@endsection
