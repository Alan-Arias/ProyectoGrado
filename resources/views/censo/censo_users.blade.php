<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro o Inicio de Sesión - Censista</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .form-container {
            max-width: 600px;
            margin: auto;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="form-container shadow rounded bg-white p-4">
        <div class="text-center mb-4">
            <h4><i class="bi bi-person-circle"></i> Registro / Inicio de Sesión</h4>
            <p class="text-muted">Ingresa tus datos como censista para continuar</p>
        </div>

        @if(session('sin_intentos'))
            <div class="alert alert-warning">{{ session('sin_intentos') }}</div>
        @endif
        @if(session('mensaje'))
            <div class="alert alert-info alert-dismissible fade show">
                {{ session('mensaje') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        <!-- Tabs de Bootstrap -->
        <ul class="nav nav-tabs mb-3" id="censoTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button">Iniciar Sesión</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="registro-tab" data-bs-toggle="tab" data-bs-target="#registro" type="button">Registrarse</button>
            </li>
        </ul>

        <div class="tab-content" id="censoTabsContent">
            <!-- Iniciar Sesión -->
            <div class="tab-pane fade show active" id="login" role="tabpanel">
                <form method="POST" action="{{ url('/CensoAnimales') }}">
                    @csrf
                    <input type="hidden" name="accion" value="login">

                    <div class="mb-3">
                        <label for="codigo_estudiante_login" class="form-label">Código de Estudiante</label>
                        <input type="text" class="form-control" id="codigo_estudiante_login" name="codigo_estudiante" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión</button>
                    </div>
                </form>
            </div>

            <!-- Registro -->
            <div class="tab-pane fade" id="registro" role="tabpanel">
                <form method="POST" action="{{ url('/CensoAnimales') }}" onsubmit="return confirmarEnvio()">
                    @csrf
                    <div class="mb-3">
                        <label for="codigo_estudiante" class="form-label">Código de Estudiante</label>
                        <input type="text" class="form-control" id="codigo_estudiante" name="codigo_estudiante" required>
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="apellido" class="form-label">Apellido</label>
                        <input type="text" class="form-control" id="apellido" name="apellido" required>
                    </div>
                    <div class="mb-3">
                        <label for="codigo_carrera" class="form-label">Código de Carrera</label>
                        <input type="text" class="form-control" id="codigo_carrera" name="codigo_carrera" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success"><i class="bi bi-person-plus-fill"></i> Registrarse</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmarEnvio() {
        return confirm("¿Deseas continuar con este censista?");
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
