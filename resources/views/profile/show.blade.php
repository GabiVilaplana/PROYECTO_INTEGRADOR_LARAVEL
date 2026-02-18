@extends('layouts.layoutbaseproyecto')

@section('title', 'Mi Perfil')

@section('content')
    <div class="profile-container">
        <h1 class="profile-title">Mi Perfil</h1>

        <div class="profile-card">
            <div class="profile-header">
                <img src="{{ $user->foto_perfil_url }}" alt="Foto de perfil" class="profile-avatar animated">
                <div class="profile-info">
                    <h2 class="profile-name">{{ $user->Nombre }} {{ $user->Apellidos }}</h2>
                    <p class="profile-email">{{ $user->email }}</p>

                    @if(Auth::user()->idRol == 1)
                        <form action="{{ route('profile.rol.update') }}" method="POST" class="role-form mt-4">
                            @csrf
                            @method('PUT')

                            <label class="role-checkbox-label">
                                <input type="checkbox" name="es_proveedor" value="1" {{ $user->idRol == 3 ? 'checked' : '' }}
                                    class="role-checkbox-input">
                                <span class="role-checkbox-text">
                                    Asignar como creador de servicios
                                </span>
                            </label>
                            <p class="role-checkbox-desc">
                                Rol 3 = Creador de servicios, Rol 2 = Usuario estándar
                            </p>

                            <div class="mt-3">
                                <label class="role-checkbox-label admin-checkbox">
                                    <input type="checkbox" name="es_admin" value="1" {{ $user->idRol == 1 ? 'checked' : '' }}
                                        class="role-checkbox-input admin-input">
                                    <span class="role-checkbox-text">Administrador del sistema</span>
                                </label>
                                <p class="role-checkbox-desc">
                                    Rol 1 = Administrador (acceso total)
                                </p>
                            </div>

                            <button type="submit" class="btn-update-roles">
                                Actualizar Roles
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Tabs -->
            <div class="tabs mt-6">
                <button class="tab-button active" data-tab="stats">Resumen</button>
                @if($user->servicios()->count() > 0) {{-- Mostrar incluso si hay inactivos --}}
                    <button class="tab-button" data-tab="servicios">Mis Servicios</button>
                @endif
                @if($user->reservas()->count() > 0)
                    <button class="tab-button" data-tab="reservas">Mis Reservas</button>
                @endif
            </div>

            <!-- Contenido de las tabs -->
            <div class="tab-content mt-6">
                <!-- Resumen -->
                <div id="tab-stats" class="tab-pane active">
                    <div class="stats-grid">
                        <div class="stat-item">
                            <h3 class="stat-title">Servicios activos</h3>
                            <p class="stat-value">{{ $user->servicios()->where('Activo', 1)->count() }}</p>
                        </div>
                        <div class="stat-item">
                            <h3 class="stat-title">Reservas</h3>
                            <p class="stat-value">{{ $user->reservas()->count() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Lista de servicios -->
                @if($user->servicios()->count() > 0)
                    <div id="tab-servicios" class="tab-pane hidden">
                        <h3 class="section-subtitle">Mis Servicios</h3>
                        <div class="services-list mt-4">
                            @foreach($user->servicios()->with('categoria')->get() as $servicio)
                                <div class="service-item" data-servicio-id="{{ $servicio->IDServicio }}">
                                    <h4 class="service-title">{{ $servicio->Titulo }}</h4>
                                    <p class="service-category">Categoría: {{ $servicio->categoria->Nombre ?? 'Sin categoría' }}</p>
                                    <p class="service-price">Precio: {{ number_format($servicio->Precio, 2) }} €</p>
                                    <p class="service-duration">Duración: {{ $servicio->Duracion }} min</p>
                                    @if($servicio->Descripcion)
                                        <p class="service-description">{{ Str::limit($servicio->Descripcion, 100) }}</p>
                                    @endif

                                    <div class="mt-3">
                                        <a href="{{ route('servicios.edit', $servicio->IDServicio) }}" class="btn-action btn-edit">
                                            <span>✏️</span> Editar
                                        </a>

                                        <form action="{{ route('servicios.toggle-activo', $servicio->IDServicio) }}" method="POST"
                                            class="toggle-servicio-form" style="display:inline;">
                                            @csrf
                                            <button type="submit"
                                                class="btn-action {{ $servicio->Activo ? 'btn-delete' : 'btn-activate' }}">
                                                <span>{{ $servicio->Activo ? '❌' : '✅' }}</span>
                                                {{ $servicio->Activo ? 'Deshabilitar' : 'Habilitar' }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Reservas -->
                @if($user->reservas()->count() > 0)
                    <div id="tab-reservas" class="tab-pane hidden">
                        <h3 class="section-subtitle">Mis Reservas</h3>
                        <div class="reservations-list mt-4">
                            @foreach($user->reservas()->with('detalles.servicio')->get() as $reserva)
                                <div class="reservation-item">
                                    <h4 class="reservation-title">Reserva #{{ $reserva->IDReserva }}</h4>
                                    <p class="reservation-date">Fecha:
                                        {{ \Carbon\Carbon::parse($reserva->FechaReserva)->format('d/m/Y') }}
                                    </p>
                                    <p class="reservation-total">Total: {{ number_format($reserva->Total, 2) }} €</p>
                                    <p class="reservation-status">Estado:
                                        <span class="status-badge status-{{ strtolower($reserva->Estado ?? 'pendiente') }}">
                                            {{ ucfirst($reserva->Estado ?? 'Pendiente') }}
                                        </span>
                                    </p>

                                    @if($reserva->detalles->count() > 0)
                                        <div class="reservation-services mt-2">
                                            <strong>Servicios:</strong>
                                            <ul class="reservation-services-list">
                                                @foreach($reserva->detalles as $detalle)
                                                    <li class="reservation-service-item">
                                                        @if($detalle->servicio)
                                                            {{ $detalle->servicio->Titulo }}
                                                            ({{ number_format($detalle->Precio, 2) }} €)
                                                            - {{ \Carbon\Carbon::parse($detalle->FechaServicio)->format('d/m/Y') }}
                                                            {{ $detalle->HoraServicio }}
                                                        @else
                                                            Servicio no disponible
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="profile-actions mt-8">
                <a href="{{ route('profile.edit') }}" class="btn-edit-profile">
                    Editar perfil
                </a>
            </div>
        </div>
    </div>
@endsection