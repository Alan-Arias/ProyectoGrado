<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modulo Otb</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
@extends('layout.layoutv2')

@section('title', 'Módulo Otb')

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
                <form action="{{ url('/CensoAnimales/RegistrarOtb') }}" method="post" enctype="multipart/form-data" class="form-table">
                    @csrf
                    <h3 class="text-center mb-4">Registrar Datos del Censo</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="otb_name">Nombre de la Otb:</label>
                            <input type="text" class="form-control full-width" name="otb_name" required>
                        </div>                    
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
        <!-- Columna para la tabla -->
        <div class="col-md-6">
            <div class="table-container table-responsive">
                <table class="table table-striped table-bordered table-light">
                    <thead>
                        <tr>
                            <th>Nombre Otb</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($Otb as $item)
                        <tr>
                            <td>{{ $item->nombre_area }}</td>
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