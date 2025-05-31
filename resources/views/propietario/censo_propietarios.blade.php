<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo Censo Propietario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/cssPropietarioIMG.css') }}">    
</head>
<body>
@extends('layout.layoutv2')

@section('title', 'Módulo Censo Propietario')

@section('content')
<div class="contenedor">
    <div class="row">
        <!-- Columna para el formulario -->
        <div class="col-md-6 mx-auto">
            <div class="form-container">
                @if (session('agregar'))
                    <div class="alert alert-success mt-3 text-center">
                        <p>{{ session('agregar') }}</p>
                    </div>
                @endif
                <form action="{{ url('/CensoAnimales/RegistrarPropietario') }}" method="post" enctype="multipart/form-data" class="form-table">
                    @csrf                    
                    <br>
                    <h3 class="text-center mb-4">Registrar Datos del Propietario</h3>
                    <div class="mb-3">
                        <label for="name" class="form-label">Nro Carnet</label>
                        <input class="form-control" type="text" name="carnet" id="carnet" placeholder="Ejemplo: 1234567-SC" required>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Nro Telefono</label>
                        <input class="form-control" type="text" name="telefono" id="telefono" placeholder="Numero de Telefono" required>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre Completo</label>
                        <input class="form-control" type="text" name="name" id="name" placeholder="Nombre del Propietario" required>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Fecha de Nacimiento</label>
                        <input class="form-control" type="date" name="date" id="date" required>
                    </div>
                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input class="form-control" type="text" name="direccion" id="direccion" placeholder="Barrio X, calle Y, numero Z" required>
                    </div>
                    <div class="mb-3">
                        <label for="pc_prop" class="form-label">Foto de la Fachada de la Casa</label>
                        <input class="form-control" type="file" name="pc_prop" id="pc_prop" required>
                        <small id="error-msg-prop" style="color: red; display: none;">
                            El archivo debe ser menor a 1MB. Puedes reducir su tamaño en 
                            <a href="https://tinypng.com/" target="_blank">Este Enlace</a>.
                        </small>
                    </div>

                    <script>
                    document.getElementById('pc_prop').addEventListener('change', function() {
                        var file = this.files[0];
                        var maxSize = 1 * 1024 * 1024; // 1MB en bytes
                        var errorMsg = document.getElementById('error-msg-prop');

                        if (file && file.size > maxSize) {
                            errorMsg.style.display = 'block';
                            this.value = ''; // Limpiar el input
                        } else {
                            errorMsg.style.display = 'none';
                        }
                    });
                    </script>
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

        <!-- Columna para la tabla -->
        <div class="col-md-6">
            <div class="alert alert-info">
                Total de propietarios registrados: <strong>{{ $totalPropietarios }}</strong>
            </div>        
        </div>
    </div>
</div> 
<!-- Modal para ver la imagen en pantalla completa -->
<div id="imageModal" class="modal" onclick="closeFullScreen()">
    <span class="close">&times;</span>
    <img class="modal-content" id="fullImage">
</div>

@endsection   

</body>

<script>
    function openFullScreen(src) {
        const image = document.getElementById("fullImage");
        image.src = src;
        image.onload = function () {
            // Asegura que la imagen mantenga su tamaño original y sea responsive
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
