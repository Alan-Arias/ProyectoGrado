<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/cssLayout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cssProfile.css') }}">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid">        
            <a class="navbar-brand" href="#">
                <img src="{{ asset('logo/logo-ai.png') }}" alt="Zoonosis Camiri" height="70">
            </a>        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <button class="btn btn-secondary d-none" id="closeMenuBtn" aria-label="Cerrar menú">Cerrar Menú</button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/Especies') }}"><i class="fas fa-paw"></i> Gestionar Especies</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/Usuarios') }}"><i class='fas fa-user-cog'></i> Gestionar Personal</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/Propietarios') }}"><i class="fas fa-user"></i> Gestionar Propietarios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/Animales') }}"><i class="fas fa-dog"></i> Gestionar Animales</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/HistorialSanitario') }}"><i class="fas fa-file-medical"></i> Historial Sanitario</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/Vacunas/TipoVacunas') }}"><i class="fas fa-syringe"></i> Gestionar Vacunas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/Reportes') }}"><i class="fas fa-search"></i> Gestionar Reportes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/panel/reintentos') }}"><i class="fa-solid fa-chalkboard-teacher"></i> Panel de Control</a>
                </li>
            </ul>                                                
        </div> 
        <img src="{{ asset('logo/profile.png') }}" class="user-icon" onclick="toggleMenu()" alt="">       
        <div class="menu" id="menu">
                <ul>
                    <div class="menu-header">
                        <span>Username: {{ session('user_nombre') }}</span>
                        <span>Email: {{ session('user_email') }}</span>
                    </div>
                    <li>
                        <div style="display: inline;">
                            <p style="display: inline; color: blue;">Usuario Tipo: </p>
                            <span style="color: black;">{{ session('user_tipo') }}</span>
                        </div>                        
                        <div class="switch-container">
                            <div class="switch-text" style="color: black;">
                                Cambiar modo
                            </div>
                            <label class="switch">
                                <input type="checkbox" id="modeSwitch">
                                <span class="slider"></span>
                            </label>                            
                        </div>
                        <p><a href="{{ url('/CensoAnimales') }}">Modulo Censo</a></p>                            
                        <div>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-lg" style="color: white; background-color:rgb(252, 63, 63); border: none; margin: 5px;">
                                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                                </button>
                            </form>
                        </div>                        
                    </li>
                </ul>
            </div>
    </div>
</nav>
<div class="container mt-4">
    @yield('content')
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('searchForm').addEventListener('submit', function(event) {
        let searchInput = document.getElementById('searchInput');
        if (searchInput.value.trim() === '') {
            event.preventDefault();
            searchInput.style.border = '2px solid red';                        
        } else {
            searchInput.style.border = '';
        }
    });
</script>  
<script>
document.addEventListener('DOMContentLoaded', function() {
    const isDarkMode = localStorage.getItem('darkMode') === 'true';
    if (isDarkMode) {
        document.body.classList.add('dark-mode');
        document.getElementById('modeSwitch').checked = true;
    }
});
document.getElementById('modeSwitch').addEventListener('change', function() {
    if (this.checked) {
        document.body.classList.add('dark-mode');
        localStorage.setItem('darkMode', 'true');
    } else {
        document.body.classList.remove('dark-mode');
        localStorage.setItem('darkMode', 'false');
    }
});
</script>  
<script>
    function toggleMenu() {
        const menu = document.getElementById("menu");
        menu.classList.toggle("mostrar");
    }

    function setActive(link) {
        const navLinks = document.querySelectorAll("#sidebar .nav-link");
        navLinks.forEach(item => item.classList.remove("active"));
        link.classList.add("active");
    }
    window.onclick = function(event) {
        if (!event.target.matches('.user-icon')) {
            const menu = document.getElementById('menu');
            if (menu.classList.contains('mostrar')) {
                menu.classList.remove('mostrar');
            }
        }
    }
</script>     
<script>
    const openMenuBtn = document.getElementById('openMenuBtn');
    const closeMenuBtn = document.getElementById('closeMenuBtn');
    const navbarNav = document.getElementById('navbarNav');

    openMenuBtn.addEventListener('click', function() {
        closeMenuBtn.classList.remove('d-none');
        openMenuBtn.classList.add('d-none');
    });

    closeMenuBtn.addEventListener('click', function() {
        closeMenuBtn.classList.add('d-none');
        openMenuBtn.classList.remove('d-none');
        navbarNav.classList.remove('show'); // Cerrar el menú dinámico
    });
</script>  
</body>
</html>
