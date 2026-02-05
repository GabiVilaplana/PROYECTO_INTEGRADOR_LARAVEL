@extends('layouts.layoutbaseproyecto')

@section('content')
    <style>
        :root {
            --primary-color: #007bff;
            --text-dark: #222;
            --text-gray: #6a6a6a;
            --border-color: #dddddd;
        }

        .airbnb-layout {
            max-width: 1120px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .product-header {
            padding: 24px 0;
        }

        .product-header h1 {
            font-size: 26px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .photo-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            grid-template-rows: 200px 200px;
            gap: 8px;
            height: 400px;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 32px;
        }

        .photo-grid img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .main-photo {
            grid-row: span 2;
        }

        .content-wrapper {
            display: flex;
            gap: 80px;
            position: relative;
        }

        .main-column {
            flex: 2;
        }

        .sidebar-column {
            flex: 0 0 320px;
        }

        .host-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 24px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .host-profile-img {
            width: 70px !important;
            height: 70px !important;
            border-radius: 50% !important;
            object-fit: cover !important;
            flex-shrink: 0;
            border: 1px solid #ddd;
            background: #f5f5f5;
        }

        .description-section {
            padding: 32px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .features-list {
            padding: 32px 0;
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 16px;
            color: var(--text-dark);
        }

        .review-item-feature {
            display: flex;
            gap: 16px;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid #f0f0f0;
        }

        .review-avatar {
            width: 40px;
            height: 40px;
            background: #eee;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #666;
            flex-shrink: 0;
        }

        .review-body .stars {
            color: #ff385c;
            font-size: 12px;
            margin-bottom: 4px;
            display: block;
        }

        /* Difuminado para reseñas ocultas */
        .blurred-review {
            filter: blur(3px);
            opacity: 0.6;
            pointer-events: none;
            position: relative;
        }

        .blurred-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #666;
            border-radius: 8px;
            z-index: 10;
        }

        .booking-widget {
            position: sticky;
            top: 100px;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 24px;
            box-shadow: rgba(0, 0, 0, 0.12) 0px 6px 16px;
            background: white;
            box-sizing: border-box;
            width: 100%;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid #eee;
            font-size: 16px;
        }

        .total-row b {
            font-size: 18px;
            white-space: nowrap;
        }

        .widget-btn {
            width: 100%;
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 16px;
        }

        .rating-form {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
            margin-bottom: 15px;
        }

        .rating-form input {
            display: none !important;
        }

        .rating-form label {
            font-size: 32px;
            color: #ddd;
            cursor: pointer;
            padding: 0 2px;
        }

        .rating-form label:hover,
        .rating-form label:hover~label,
        .rating-form input:checked~label {
            color: #ff385c;
        }

        @media (max-width: 950px) {
            .content-wrapper {
                flex-direction: column;
            }

            .sidebar-column {
                order: -1;
            }
        }
    </style>

    <div class="py-12">
        <!-- Mensajes flash -->
        @if(session('success'))
            <div style="max-width:1120px;margin:0 auto 24px;padding:0 24px;">
                <div style="background:#d4edda;padding:12px;border-radius:8px;color:#155724;border:1px solid #c3e6cb;">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if($errors->any())
            <div style="max-width:1120px;margin:0 auto 24px;padding:0 24px;">
                <div style="background:#f8d7da;padding:12px;border-radius:8px;color:#721c24;border:1px solid #f5c6cb;">
                    {{ $errors->first() }}
                </div>
            </div>
        @endif

        <div class="airbnb-layout">
            <!-- Cabecera -->
            <div class="product-header">
                <h1>{{ $servicio->Nombre }}</h1>
                <p>⭐ {{ number_format($media, 1) }} · {{ $total_resenas }} reseñas ·
                    {{ $servicio->proveedor?->Ciudad ?? 'Sevilla' }}, España</p>
            </div>

            <!-- Galería -->
            <section class="photo-grid">
                @php
                    // Imagen de fallback desde la categoría
                    $fallbackImage = asset('frontend/IMG/image_default.jpg');
                    if ($servicio->categoria && $servicio->categoria->Imagen) {
                        $fallbackImage = asset('storage/' . ltrim(strtolower($servicio->categoria->Imagen), '/'));
                    }

                    $fotos = $servicio->fotos->sortByDesc('EsPrincipal')->values();
                    $fotoPrincipal = $fotos->first();
                    $otrasFotos = $fotos->skip(1)->take(4);
                @endphp

                @if($fotoPrincipal && $fotoPrincipal->RutaFoto)
                    <div class="main-photo">
                        <img src="{{ asset('storage/' . ltrim($fotoPrincipal->RutaFoto, '/')) }}" alt="Imagen principal">
                    </div>
                    @foreach($otrasFotos as $foto)
                        <img src="{{ asset('storage/' . ltrim($foto->RutaFoto, '/')) }}" alt="Foto adicional">
                    @endforeach
                    @for($i = 0; $i < (4 - $otrasFotos->count()); $i++)
                        <img src="{{ $fallbackImage }}" alt="Foto de categoría">
                    @endfor
                @else
                    <div class="main-photo">
                        <img src="{{ $fallbackImage }}" alt="Imagen de categoría">
                    </div>
                    @for($i = 0; $i < 4; $i++)
                        <img src="{{ $fallbackImage }}" alt="Imagen de categoría">
                    @endfor
                @endif
            </section>

            <div class="content-wrapper">
                <div class="main-column">
                    <div class="host-info">
                        <div>
                            <h2>Servicio ofrecido por {{ $servicio->proveedor?->Nombre ?? 'el proveedor' }}</h2>
                            <p>Experiencia verificada en TaskLink</p>
                        </div>
                        @php
                            $fotoUrl = $servicio->proveedor && $servicio->proveedor->FotoPerfil
                                ? asset('storage/' . ltrim($servicio->proveedor->FotoPerfil, '/'))
                                : null;
                        @endphp

                        @if($fotoUrl)
                            <img src="{{ $fotoUrl }}" class="host-profile-img" alt="Foto del proveedor">
                        @else
                            <!-- Avatar por defecto SVG (siempre visible) -->
                            <div class="host-profile-img" style="display:flex;align-items:center;justify-content:center;background:#f0f0f0;color:#888;font-weight:bold;">
                                {{ strtoupper(substr($servicio->proveedor?->Nombre ?? '?', 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    <div class="description-section">
                        <div class="section-title">Sobre este servicio</div>
                        <p>{!! nl2br(e($servicio->Descripcion)) !!}</p>
                    </div>

                    <div class="description-section">
                        <div class="section-title">Ubicación y cobertura</div>
                        <div id="mapa-servicio"
                            style="height: 300px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);"></div>
                    </div>

                    <div class="features-list">
                        <div class="section-title">Opiniones de clientes</div>
                        @php
                            $reseñasVisibles = $servicio->valoraciones->take(5);
                            $totalReseñas = $servicio->valoraciones->count();
                        @endphp

                        @forelse($reseñasVisibles as $valoracion)
                            <div class="review-item-feature">
                                <div class="review-avatar">{{ strtoupper(substr($valoracion->usuario?->Nombre ?? '?', 0, 1)) }}
                                </div>
                                <div class="review-body">
                                    <b>{{ e($valoracion->usuario?->Nombre ?? 'Usuario') }}</b>
                                    <span class="stars">{{ str_repeat('★', $valoracion->Puntuacion) }}</span>
                                    <p>{{ e($valoracion->Comentario) }}</p>
                                    <span style="font-size: 12px; color: #999;">
                                        {{ \Carbon\Carbon::parse($valoracion->Fecha)->format('d/m/Y') }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p>Aún no hay reseñas. Sé el primero en probar este servicio.</p>
                        @endforelse
                        @if($totalReseñas > 5 && !auth()->check())
                            <div class="review-item-feature blurred-review" style="position:relative;">
                                <div class="blurred-overlay">
                                    Inicia sesión para ver más opiniones
                                </div>
                                <div class="review-avatar">?</div>
                                <div class="review-body">
                                    <b>Usuario</b>
                                    <span class="stars">★★★★★</span>
                                    <p>Esta es una opinión adicional que solo ven los usuarios registrados.</p>
                                    <span style="font-size: 12px; color: #999;">01/01/2025</span>
                                </div>
                            </div>
                        @endif
                    </div>

                    @auth
                        @php
                            $usuario_ya_ha_comentado = $servicio->valoraciones->contains('idUsuario', auth()->id());
                        @endphp

                        @if(!$usuario_ya_ha_comentado)
                            <div class="form-comentario-container">
                                <div class="section-title">Deja tu valoración</div>
                                <form action="{{ route('valoraciones.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="servicio_id" value="{{ $servicio->IDServicio }}">
                                    <div class="rating-form">
                                        <input type="radio" name="rating" value="5" id="s5"><label for="s5">★</label>
                                        <input type="radio" name="rating" value="4" id="s4"><label for="s4">★</label>
                                        <input type="radio" name="rating" value="3" id="s3" checked><label for="s3">★</label>
                                        <input type="radio" name="rating" value="2" id="s2"><label for="s2">★</label>
                                        <input type="radio" name="rating" value="1" id="s1"><label for="s1">★</label>
                                    </div>
                                    <textarea name="comentario" rows="3" placeholder="Escribe aquí tu opinión..."
                                        required></textarea>
                                    <button type="submit" class="btn-enviar"
                                        style="background:#007bff;color:white;border:none;padding:10px 20px;border-radius:6px;margin-top:10px;">Publicar
                                        valoración</button>
                                </form>
                            </div>
                        @else
                            <div style="background:#e7f3ff;padding:15px;border-radius:8px;border:1px solid #bde0ff;">
                                <p style="margin:0;color:#0056b3;">✅ Gracias por tu valoración. Ya has opinado sobre este servicio.
                                </p>
                            </div>
                        @endif
                    @else
                        <p style="color:#666;font-style:italic;">Debes <a href="{{ route('login') }}"
                                style="color:#ff385c;font-weight:bold;">iniciar sesión</a> para dejar una reseña.</p>
                    @endauth
                </div>

                <div class="sidebar-column">
                    <div class="booking-widget">
                        <div class="widget-price">
                            {{ number_format($servicio->Precio, 2) }} € <span style="font-weight:normal;font-size:16px;">/
                                sesión</span>
                        </div>

                        <div style="border:1px solid #ccc;border-radius:8px;margin-bottom:15px;overflow:hidden;">
                            <div style="padding:10px;border-bottom:1px solid #ccc;font-size:12px;">
                                <b>FECHA</b><br>
                                <span style="color:#666;">Seleccionar fecha</span>
                            </div>
                            <div style="padding:10px;font-size:12px;">
                                <b>PERSONAS</b><br>
                                <span style="color:#666;">1 persona</span>
                            </div>
                        </div>

                        <button class="widget-btn">Reservar ahora</button>
                        <p style="text-align:center;font-size:12px;margin-top:10px;color:#666;">No se te cobrará nada aún
                        </p>

                        <div class="total-row">
                            <span>Total</span>
                            <b>{{ number_format($servicio->Precio, 2) }} €</b>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const lat = {{ $servicio->lat ?? 'null' }};
            const lng = {{ $servicio->lng ?? 'null' }};
            const radioKm = {{ $servicio->radio_km ?? 0 }};

            if (lat && lng) {
                const map = L.map('mapa-servicio').setView([lat, lng], 12);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                let popupContent = '<b>{{ addslashes($servicio->Nombre) }}</b><br>';
                @if($servicio->proveedor)
                    popupContent += 'Proveedor: {{ addslashes($servicio->proveedor->Nombre . " " . $servicio->proveedor->Apellidos) }}<br>';
                @endif
                popupContent += 'Radio: {{ $servicio->radio_km }} km';

                L.marker([lat, lng]).addTo(map).bindPopup(popupContent).openPopup();

                if (radioKm > 0) {
                    L.circle([lat, lng], {
                        radius: radioKm * 1000,
                        color: '#4f7cff',
                        fillColor: '#cce6ff',
                        fillOpacity: 0.2,
                        weight: 2
                    }).addTo(map);
                }
            } else {
                document.getElementById('mapa-servicio').innerHTML = '<div class="d-flex align-items-center justify-content-center h-100 text-muted" style="height:100%;">Ubicación no disponible</div>';
            }
        });
    </script>
@endsection