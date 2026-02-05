@extends('layouts.layoutbaseproyecto')

@section('content')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Contenido original -->
                    <div class="container mt-5 mb-5">
                        <div class="row">
                            <div class="col-12">
                                <h1 class="mb-3">{{ $servicio->Nombre }}</h1>
                                <p class="text-muted fs-5">{{ $servicio->Descripcion }}</p>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <!-- Imagen principal -->
                            <div class="col-md-6 mb-4">
                                @if($rutaImagen)
                                    <img src="{{ $rutaImagen }}" alt="{{ $servicio->Nombre }}"
                                        class="img-fluid rounded shadow-sm"
                                        style="max-height: 400px; object-fit: cover; width: 100%;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center rounded shadow-sm"
                                        style="height: 400px;">
                                        <span class="text-muted">Sin imagen</span>
                                    </div>
                                @endif

                                <!-- Galería de fotos adicionales -->
                                @if($servicio->fotos->count() > 1)
                                    <div class="mt-3 d-flex gap-2 flex-wrap">
                                        @foreach($servicio->fotos as $foto)
                                            @if(!$foto->EsPrincipal)
                                                <img src="{{ asset('storage/' . $foto->RutaFoto) }}" alt="Foto {{ $loop->index }}"
                                                    class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <!-- Detalles del servicio -->
                            <div class "col-md-6">
                                <div class="card p-4 shadow-sm">
                                    <ul class="list-unstyled fs-5">
                                        <li class="mb-2"><strong>💰 Precio:</strong>
                                            {{ number_format($servicio->Precio, 2) }} €</li>
                                        <li class="mb-2"><strong>⏱️ Duración:</strong> {{ $servicio->Duracion }} minutos
                                        </li>
                                        <li class="mb-2"><strong>🏷️ Categoría:</strong>
                                            {{ $servicio->categoria?->Nombre ?? '—' }}</li>

                                        @if($servicio->proveedor)
                                            <li class="mb-2"><strong>👤 Proveedor:</strong>
                                                {{ $servicio->proveedor->Nombre }} {{ $servicio->proveedor->Apellidos }}
                                            </li>
                                            <li class="mb-2"><strong>✉️ Contacto:</strong>
                                                {{ $servicio->proveedor->CorreoElectronico }}</li>
                                        @else
                                            <li class="mb-2"><strong>👤 Proveedor:</strong> No disponible</li>
                                        @endif

                                        <li class="mb-2"><strong>📍 Radio de cobertura:</strong>
                                            {{ $servicio->radio_km }} km</li>
                                    </ul>

                                    <div class="mt-4 d-grid gap-2">
                                        <button class="btn btn-primary btn-lg">
                                            <i class="bi bi-cart"></i> Contratar servicio
                                        </button>
                                        <a href="{{ url('/') }}" class="btn btn-outline-secondary">
                                            ← Volver al inicio
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mapa -->
                        <div class="row mt-5">
                            <div class="col-12">
                                <h2 class="mb-3">Ubicación del proveedor</h2>
                                <div id="mapa-servicio"
                                    style="height: 400px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Leaflet CSS -->
                    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

                    <!-- Leaflet JS -->
                    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const lat = {{ $servicio->lat }};
                            const lng = {{ $servicio->lng }};

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

                                L.marker([lat, lng]).addTo(map)
                                    .bindPopup(popupContent)
                                    .openPopup();

                                L.circle([lat, lng], {
                                    radius: {{ $servicio->radio_km * 1000 }},
                                    color: '#4f7cff',
                                    fillColor: '#cce6ff',
                                    fillOpacity: 0.2,
                                    weight: 2
                                }).addTo(map);
                            } else {
                                document.getElementById('mapa-servicio').innerHTML = '<div class="d-flex align-items-center justify-content-center h-100 text-muted">Ubicación no disponible</div>';
                            }
                        });
                    </script>

                </div>
            </div>
        </div>
    </div>
@endsection