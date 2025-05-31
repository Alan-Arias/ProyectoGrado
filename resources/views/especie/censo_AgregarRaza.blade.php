@extends('layout.layoutv2')

@section('title', 'Agregar Raza')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@300;500;700&display=swap');

    body {
        font-family: 'Nunito', sans-serif;
        background-color: #f1f4f9;
        color: #333;
    }

    .container {
        max-width: 900px;
        margin: 0 auto;
        padding: 30px 15px;
    }

    .card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        padding: 30px;
        margin-bottom: 40px;
    }

    .card h2 {
        font-weight: 700;
        font-size: 1.8rem;
        margin-bottom: 20px;
        color: #2c3e50;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        font-weight: 500;
        margin-bottom: 6px;
        display: block;
    }

    .form-control {
        border-radius: 10px;
        padding: 10px 14px;
        border: 1px solid #ccc;
        width: 100%;
        transition: border-color 0.3s ease;
    }

    .form-control:focus {
        border-color: #3498db;
        outline: none;
    }

    .btn {
        padding: 10px 16px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1rem;
        margin-right: 10px;
    }

    .btn-success {
        background-color: #2ecc71;
        border: none;
        color: white;
    }

    .btn-success:hover {
        background-color: #27ae60;
    }

    .btn-primary {
        background-color: #3498db;
        border: none;
        color: white;
    }

    .btn-primary:hover {
        background-color: #2980b9;
    }

    .alert {
        background-color: #d1f7dc;
        color: #256029;
        border-left: 6px solid #2ecc71;
        padding: 15px;
        border-radius: 10px;
        margin-top: 20px;
    }

    .raza-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 20px;
        margin-top: 25px;
    }

    .raza-card {
        background-color: #ffffff;
        border-radius: 14px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        text-align: center;
        transition: transform 0.2s ease;
    }

    .raza-card:hover {
        transform: translateY(-4px);
    }

    .raza-card h4 {
        font-size: 1.1rem;
        font-weight: 600;
        color: #34495e;
    }

</style>

<div class="container">
    <div class="card">
        <h2>Agregar Raza a la Especie: <span style="color:#3498db;">{{ $especie->nombre }}</span></h2>
        <form action="{{ url('/Razas/RegistrarRaza') }}" method="post">
            @csrf
            <input type="hidden" name="especie_id" value="{{ $especie->id }}">
            <div class="form-group">
                <label for="nombre">Nombre de la Raza</label>
                <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre de la Raza" required>
            </div>
            <button type="submit" class="btn btn-success">Agregar Raza</button>
            <a href="{{ url('/CensoAnimales/Especies') }}" class="btn btn-primary">Volver</a>
        </form>

        @if (session('agregar'))
            <div class="alert">
                {{ session('agregar') }}
            </div>
        @endif
    </div>

    <div class="card">
        <h2>Razas registradas para <span style="color:#3498db;">{{ $especie->nombre }}</span></h2>
        <div class="raza-list">
            @foreach ($especie->razas as $raza)
                <div class="raza-card">
                    <h4>{{ $raza->nombre }}</h4>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection