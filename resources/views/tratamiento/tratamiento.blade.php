<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Tratamiento</title>
</head>
<body>
@extends('layout.layout')

@section('title', 'Gestionar Animales')

@section('content')
<div class="contenedor">
    <h2 class="text-center">Gestionar Tratamiento</h2>
    <div class="row">        
        <!-- Columna para el formulario -->
        <div class="col-md-6 mx-auto">
            <div class="form-container">
                <form action="{{ url('/Tratamiento/RegistrarTratamiento') }}" method="post" enctype="multipart/form-data" class="form-table">
                    @csrf
                    <h3 class="text-center mb-4">Registrar Tratamiento</h3>
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre del Producto</label>
                        <input class="form-control" type="text" name="name" id="name" placeholder="Nombre del Tratamiento" required>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Descripcion del Tratamiento</label>
                        <input class="form-control" type="text" name="descripcion" id="descripcion" placeholder="Descripcion" required>
                    </div> 
                    <div class="mb-3">
                        <label for="name" class="form-label">Tiempo</label>
                        <input class="form-control" type="text" name="tiempo" id="tiempo" placeholder="Tiempo de Duracion" required>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Fecha</label>
                        <input class="form-control" type="date" name="datetime" id="datetime" required>
                    </div>
                                       
                    <div class="text-center">
                        <button type="submit" class="btn btn-success">Guardar</button>
                    </div>
                </form>
                
                @if (session('agregar'))
                    <div class="alert alert-success mt-3 text-center">
                        <p>{{ session('agregar') }}</p>
                    </div>
                @endif
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
            <div class="table-container table-responsive">
                <!-- Contenedor del Filtro -->
                <table class="table table-striped table-bordered table-light">
                    <thead>
                        <tr>                        
                            <th>Nombre del Tratamiento</th>
                            <th>Nombre del Producto</th>
                            <th>Tiempo</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($Tratamiento as $item)
                        <tr>
                            <td>{{ $item->Descripción }}</td>                            
                            <td>{{ $item->producto }}</td>
                            <td>{{ $item->Tiempo }}</td>
                            <td>{{ $item->Fecha }}</td>                            
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection    
</body>
</html>