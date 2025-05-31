<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Censo de Animales')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/cssLayoutV2.css') }}">
</head>
<body>
    <div id="wrapper">
        <!-- Sidebar -->
        <div id="sidebar">
            <h4 class="text-center">Censo de Animales</h4>
            <ul class="nav flex-column mt-3">
                <li>
                    <a href="{{ url('/CensoAnimales/CensoIndex') }}" class="sidebar-link"><i class="bi bi-house-door-fill"></i> Inicio</a>
                </li>
                <li>
                    <a href="#" id="toggleRegistrarOtb" class="sidebar-link"><i class="bi bi-list-ul"></i> Modulo OTB</a>
                    <ul class="nav flex-column ms-3" id="registrarOtb" style="display: none;">
                        <li>
                            <a href="{{ url('/CensoAnimales/Otb') }}" class="sidebar-link"><i class="bi bi-plus-circle"></i> Registrar Otb</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#" id="toggleRegistrarPropietarios" class="sidebar-link"><i class="bi bi-list-ul"></i> Modulo Propietarios</a>
                    <ul class="nav flex-column ms-3" id="registrarPropietarios" style="display: none;">
                        <li>
                            <a href="{{ url('/CensoAnimales/Propietarios') }}" class="sidebar-link"><i class="bi bi-plus-circle"></i> Registrar Propietarios</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#" id="toggleRegistrarAnimales" class="sidebar-link"><i class="bi bi-list-ul"></i> Modulo Animales</a>
                    <ul class="nav flex-column ms-3" id="registrarEspecies" style="display: none;">
                        <li>
                            <a href="{{ url('/CensoAnimales/Animales') }}" class="sidebar-link"><i class="bi bi-plus-circle"></i> Registrar Animales</a>
                        </li>
                        <li>
                            <a href="{{ url('/CensoAnimales/Especies') }}" class="sidebar-link"><i class="bi bi-plus-circle"></i> Listas de Especies</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#" id="toggleRegistrarEnfermedades" class="sidebar-link"><i class="bi bi-list-ul"></i> Modulo Observacion</a>
                    <ul class="nav flex-column ms-3" id="registrarEnfermedades" style="display: none;">
                        <li>
                            <a href="{{ url('/CensoAnimales/Enfermedades') }}" class="sidebar-link"><i class="bi bi-plus-circle"></i> Registrar Enfermedades</a>
                        </li>
                        <li>
                            <a href="{{ url('/CensoAnimales/ListaEnfermedades') }}" class="sidebar-link"><i class="bi bi-plus-circle"></i> Listas  de Enfermedades</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>        
        <!-- Overlay para cerrar el sidebar en móviles -->
        <div id="overlay"></div>

        <!-- Contenido principal -->
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm rounded px-3">
                <button class="sidebar-toggle" id="toggleSidebar"><i class="bi bi-list"></i></button>
                <span class="navbar-brand">Módulo Censo</span>
                @if (session('mensaje'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('mensaje') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                @endif
                <div class="ms-auto d-flex align-items-center">
                    <button class="btn btn-primary">
                        <i class="bi bi-person-circle"></i> {{ session('codigo_estudiante', 'Usuario Invitado') }} <br>
                        @if($intento)
                            <small class="text-white">
                                Intentos: {{ $intento->intentos_realizados }} / {{ $intento->intentos_totales }}
                            </small>
                        @endif
                    </button>

                    <form action="{{ route('logoutCensista') }}" method="POST" class="d-inline ms-2">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </nav>
            <div class="mt-4">
                <div class="card p-4 shadow-sm">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <footer>
        &copy; {{ date('Y') }} Censo de Animales - Todos los derechos reservados
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById("toggleSidebar").addEventListener("click", function() {
            document.getElementById("sidebar").classList.toggle("active");
            document.getElementById("overlay").classList.toggle("active");
        });

        // Cierra el sidebar si se hace clic en el overlay
        document.getElementById("overlay").addEventListener("click", function() {
            document.getElementById("sidebar").classList.remove("active");
            this.classList.remove("active");
        });
    </script>
        <script>
            document.getElementById("toggleRegistrarOtb").addEventListener("click", function() {
                var especies = document.getElementById("registrarOtb");
                if (especies.style.display === "none" || especies.style.display === "") {
                    especies.style.display = "block"; // Mostrar
                } else {
                    especies.style.display = "none"; // Ocultar
                }
            });
            document.getElementById("toggleRegistrarAnimales").addEventListener("click", function() {
                var especies = document.getElementById("registrarEspecies");
                if (especies.style.display === "none" || especies.style.display === "") {
                    especies.style.display = "block"; // Mostrar
                } else {
                    especies.style.display = "none"; // Ocultar
                }
            });
            document.getElementById("toggleRegistrarPropietarios").addEventListener("click", function() {
                var especies = document.getElementById("registrarPropietarios");
                if (especies.style.display === "none" || especies.style.display === "") {
                    especies.style.display = "block"; // Mostrar
                } else {
                    especies.style.display = "none"; // Ocultar
                }
            });
            document.getElementById("toggleRegistrarEnfermedades").addEventListener("click", function() {
                var especies = document.getElementById("registrarEnfermedades");
                if (especies.style.display === "none" || especies.style.display === "") {
                    especies.style.display = "block"; // Mostrar
                } else {
                    especies.style.display = "none"; // Ocultar
                }
            });
        </script>  
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                let sidebarLinks = document.querySelectorAll(".sidebar-link");
                function marcarActivo(link) {
                    sidebarLinks.forEach(item => item.classList.remove("active"));
                    link.classList.add("active");
                }
                sidebarLinks.forEach(link => {
                    link.addEventListener("click", function() {
                        marcarActivo(this);                      
                        localStorage.setItem("sidebarActive", this.getAttribute("href"));
                    });
                });
                let activeLink = localStorage.getItem("sidebarActive");
                if (activeLink) {
                    let link = document.querySelector(`.sidebar-link[href="${activeLink}"]`);
                    if (link) marcarActivo(link);
                }
            });
        </script>
  
    @yield('scripts')
</body>
</html>
