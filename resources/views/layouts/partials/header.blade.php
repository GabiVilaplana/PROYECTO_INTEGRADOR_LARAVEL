@vite(['resources/js/app.js'])
<header>
    <div class="logo-container">
        <img src="{{ asset('IMG/logo.png') }}" alt="TaskLink Logo" class="logo-icon">
    </div>

    <div class="right-header" tabindex="1">
        @auth
            <span class="texto-servicios">{{ Auth::user()->name }}</span>
            <div class="icono-perfil">
                <img src="{{ asset('IMG/imagenPerfilRedonda.png') }}" class="profile-icon">
            </div>
        @else
            <span class="texto-servicios"><a href="{{ route('login') }}">Iniciar Sesión</a></span>
        @endauth
    </div>

    @auth
        <div id="user-dropdown" class="user-dropdown">
            <ul class="dropdown-menu">
                <h2>Mi cuenta</h2>
                <li class="divider">
                    <hr>
                </li>
                <li><a href="{{ route('profile') }}"><span>👤</span> Perfil</a></li>
                <li><a href="{{ route('favoritos') }}"><span>❤️</span> Favoritos</a></li>
                <li><a href="{{ route('mensajes') }}"><span>💬</span> Mensajes</a></li>
                <li><a href="{{ route('servicios.create') }}"><span>➕🛠️</span> Añadir Servicio</a></li>
                <li class="divider">
                    <hr>
                </li>
                <!-- <li><a href="#"><span>🌐</span> Idiomas y moneda</a></li> -->
                <li><a href="{{ route('ayuda') }}"><span>❓</span> Centro de ayuda</a></li>
                <li class="divider">
                    <hr>
                </li>
                <li>
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <span>➜</span> Cerrar sesión
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    @endauth
</header>