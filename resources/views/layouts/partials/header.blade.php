@vite(['resources/js/app.js'])
<header>
    <div class="logo-container">
        <a href="{{ url('/') }}">
            <img src="{{ asset('IMG/logo.png') }}" alt="TaskLink Logo" class="logo-icon">
        </a>
    </div>

    <div class="searchBar" tabindex="0">
        <div class="searchBarZona" id="zonaToggle">
            <h5>Zonas</h5>
            <p id="zonaTexto">Buscar destinos</p>

            <div id="zonaSuggestions" class="zonaSuggestions">
                <div class="zona-container">
                    <input type="text" id="zonaInput" placeholder="Escribe una ciudad..." class="zonaInput">

                    <div class="zona-results" id="zonaResults">
                        <!-- Los resultados se cargarán aquí -->
                    </div>
                </div>
            </div>
        </div>
        <div class="searchBarDate" id="dateToggle">
            <h5>Fechas</h5>
            <p id="zonaFechas">Introduce las fechas</p>

            <div id="dateSuggestions" class="dateSuggestions">
                <div class="calendar-container">
                    <!-- Opciones rápidas a la izquierda -->
                    <div class="quick-options">
                        <div class="quick-option" data-range="today">
                            <strong>Hoy</strong>
                            <div id="today-date"></div>
                        </div>
                        <div class="quick-option" data-range="tomorrow">
                            <strong>Mañana</strong>
                            <div id="tomorrow-date"></div>
                        </div>
                        <div class="quick-option" data-range="weekend">
                            <strong>Este finde</strong>
                            <div id="weekend-date"></div>
                        </div>
                    </div>

                    <!-- Calendario y inputs a la derecha (CORREGIDO) -->
                    <div class="calendar-main">
                        <!-- Inputs de fechas DENTRO del calendar-main -->
                        <div class="date-inputs-container">
                            <input type="text" id="fechaDesde" class="dateInput" placeholder="Fecha de inicio" readonly>
                            <input type="text" id="fechaHasta" class="dateInput" placeholder="Fecha de fin" readonly>
                        </div>

                        <div class="calendar-header">
                            <button class="nav-btn prev-month">&lt;</button>
                            <h6 id="currentMonth">febrero 2026</h6>
                            <button class="nav-btn next-month">&gt;</button>
                        </div>
                        <div class="calendar-grid" id="calendarGrid">
                            <!-- Días del mes se generan automáticamente -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="searchBarServicio" id="servicioToggle">
            <h5>Servicio</h5>
            <p id="zonaServicio">Añade un servicio</p>

            <div id="servicioSuggestions" class="servicioSuggestions">
                <div class="servicio-container">
                    <input type="text" id="servicioInput" placeholder="Escribe un servicio..." class="servicioInput">

                    <div class="servicio-results" id="servicioResults">
                        @isset($categorias)
                            @foreach($categorias as $categoria)
                                <div data-nombre="{{ $categoria->Nombre }}" data-id="{{ $categoria->id }}"
                                    class="servicio-option">
                                    {{ $categoria->Nombre }}
                                </div>
                            @endforeach
                        @endisset
                    </div>
                </div>
            </div>
        </div>
        <form id="searchForm" method="GET" action="{{ route('buscar') }}">
            <input type="hidden" name="zona" id="filtroZona">
            <input type="hidden" name="fecha_desde" id="filtroFechaDesde">
            <input type="hidden" name="fecha_hasta" id="filtroFechaHasta">
            <input type="hidden" name="servicio" id="filtroServicio">

            <button type="submit" class="btnBuscar">
                <span class="icon-SearchBarBuscar">🔍</span>
                <span class="text">Buscar</span>
            </button>
        </form>
    </div>

    <div class="right-header">
        @auth
            <span class="texto-servicios">{{ Auth::user()->name }}</span>
            <div class="icono-perfil">
                <img src="{{ Auth::user()->foto_perfil_url }}" class="profile-icon">
            </div>
        @else
            <span class="texto-servicios"><a href="{{ route('login') }}">Iniciar Sesión</a></span>
        @endauth
    </div>

    @auth
        <div id="user-dropdown" class="user-dropdown">
            <ul class="dropdown-menu">
                <h2>Mi cuenta</h2>
                <div class="dropdown-user-info">
                    <form action="{{ route('perfil.foto.actualizar') }}" method="POST" enctype="multipart/form-data"
                        style="display: inline;">
                        @csrf
                        <label for="foto_perfil_input" style="cursor: pointer;">
                            <img src="{{ Auth::user()->foto_perfil_url }}" class="profile-dropdown-icon"
                                alt="Foto de perfil">
                        </label>
                        <input type="file" id="foto_perfil_input" name="foto_perfil" accept="image/*" style="display: none;"
                            onchange="this.form.submit()">
                    </form>
                    <div>
                        <p><strong>! Hola, {{ Auth::user()->name }} ¡</strong></p>
                    </div>
                </div>

                <li class="divider">
                    <hr>
                </li>
                <li><a href="{{ route('profile') }}"><span>👤</span> Perfil</a></li>
                <li><a href="{{ route('favoritos') }}"><span>❤️</span> Favoritos</a></li>
                <li><a href="{{ route('mensajes') }}"><span>💬</span> Mensajes</a></li>
                <li><a href="{{ route('reservas.mi-lista') }}"><span>🧾</span> Mis compras</a></li>
                @if(Auth::user()->idRol != 2)
                    <li><a href="{{ route('servicios.create') }}"><span>➕🛠️</span> Añadir Servicio</a></li>
                @endif
                <li class="divider">
                    <hr>
                </li>
                <li><a href="{{ route('ayuda') }}"><span>❓</span> Centro de ayuda</a></li>
                <li class="divider">
                    <hr>
                </li>
                <li>
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <span>➜</span> Cerrar sesión
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    @endauth
</header>