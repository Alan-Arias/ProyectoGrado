@extends('layout.layout')

@section('title', 'Resultados de Búsqueda')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f7f9fc;
        color: #333;
    }

    .card-animal {
        background-color: #fff;
        border-radius: 15px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
        border: 1px solid #e3e3e3;
    }

    .card-animal:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .card-img-top {
        height: 220px;
        object-fit: cover;
        width: 100%;
    }

    .card-body {
        padding: 1.2rem;
    }

    .card-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #0056b3;
    }

    .card-text {
        font-size: 0.95rem;
        color: #555;
    }

    h2 {
        font-weight: 600;
        color: #2c3e50;
        text-align: center;
        margin-bottom: 2rem;
    }

    .alert {
        font-size: 0.95rem;
    }

    /* Modal personalizado (se mantiene) */
    .modal-custom {
        backdrop-filter: blur(10px);
        background-color: rgba(0, 0, 0, 0.8);
    }

    .modal-content-custom {
        background: transparent;
        border: none;
        text-align: center;
    }

    #modalImage {
        max-height: 80vh;
        border-radius: 12px;
        box-shadow: 0 0 30px rgba(0, 123, 255, 0.4);
    }

    .btn-close-custom {
        margin-top: 1rem;
    }
</style>

<div class="container mt-5">
    <h2>Resultados de la Búsqueda</h2>

    @if(session('error'))
        <div class="alert alert-danger text-center shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    @if($Animales->isEmpty())
        <div class="alert alert-warning text-center shadow-sm">
            No se encontraron resultados.<br>
            <small><strong>Prueba buscando por el código de usuario.</strong></small>
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @foreach ($Animales as $item)
                <div class="col">
                    <div class="card card-animal h-100">
                        @if($item->foto_animal)
                            <img src="{{ asset($item->foto_animal) }}" alt="Foto de {{ $item->nombre }}"
                                 class="card-img-top" onclick="openModal('{{ asset($item->foto_animal) }}')" style="cursor: pointer;">
                        @else
                            <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 220px;">
                                <span class="text-muted">Sin imagen</span>
                            </div>
                        @endif
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $item->nombre }}</h5>
                            <p class="card-text"><strong>Color:</strong> {{ $item->color }}</p>
                            <p class="card-text"><strong>Propietario:</strong> {{ $item->propietario->nombres }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Modal -->
<div class="modal fade modal-custom" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-custom">
            <img id="modalImage" src="" alt="Imagen Ampliada" class="img-fluid">
            <button class="btn btn-outline-light btn-close-custom" data-bs-dismiss="modal">Cerrar</button>
        </div>
    </div>
</div>

<script>
    function openModal(imageSrc) {
        document.getElementById('modalImage').src = imageSrc;
        const modal = new bootstrap.Modal(document.getElementById('imageModal'));
        modal.show();
    }
</script>
@endsection
