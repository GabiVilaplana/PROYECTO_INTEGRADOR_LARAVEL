@extends('layouts.layoutbaseproyecto')
<style>
    .detalle-reserva-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 3rem 1.5rem;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }

    /* Encabezado */
    .detalle-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 3rem;
        flex-wrap: wrap;
        gap: 1.5rem;
        padding-bottom: 1.25rem;
        border-bottom: 1px solid #f8f9fa;
    }

    .detalle-header h1 {
        font-size: 2.25rem;
        font-weight: 700;
        color: #1f2937;
        letter-spacing: -0.5px;
        background: linear-gradient(135deg, #1f2937, #4b5563);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .detalle-header h1::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 0;
        width: 50px;
        height: 3px;
        background: linear-gradient(90deg, #3b82f6, #0ea5e9);
        border-radius: 2px;
    }

    /* Botón volver */
    .btn-volver {
        background: transparent;
        color: #64748b;
        border: 1px solid #e2e8f0;
        padding: 0.5rem 1.25rem;
        border-radius: 12px;
        font-weight: 500;
        font-size: 0.95rem;
        transition: all 0.25s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-volver:hover {
        background: #f8fafc;
        color: #3b82f6;
        border-color: #3b82f6;
        transform: translateY(-2px);
    }

    /* Tarjeta principal */
    .card-detalle {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        border: 1px solid #f1f5f9;
        max-width: 800px;
        margin: 0 auto;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card-detalle:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.12);
    }

    .card-body {
        padding: 2.5rem;
    }

    /* Secciones */
    .seccion-detalle {
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid #f8f9fa;
    }

    .seccion-detalle:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .seccion-titulo {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        letter-spacing: -0.3px;
    }

    /* Servicio */
    .servicio-info {
        display: flex;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .servicio-imagen {
        width: 140px;
        height: 140px;
        object-fit: cover;
        border-radius: 16px;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .servicio-imagen:hover {
        transform: scale(1.05);
    }

    .servicio-detalles {
        flex: 1;
    }

    .servicio-nombre {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 0.75rem 0;
        line-height: 1.2;
    }

    .servicio-descripcion {
        color: #64748b;
        line-height: 1.6;
        margin: 0 0 1.25rem 0;
        font-size: 0.95rem;
    }

    .precio-servicio {
        font-size: 1.75rem;
        font-weight: 800;
        background: linear-gradient(135deg, #3b82f6, #0ea5e9);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 0;
        line-height: 1.1;
    }

    /* Datos en grid */
    .datos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.25rem;
    }

    .dato-item {
        display: flex;
        flex-direction: column;
    }

    .dato-label {
        font-size: 0.875rem;
        color: #94a3b8;
        margin-bottom: 0.375rem;
        font-weight: 500;
    }

    .dato-valor {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1f2937;
    }

    /* Estado */
    .estado-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        backdrop-filter: blur(10px);
    }

    .estado-success {
        background: rgba(34, 197, 94, 0.15);
        color: #16a34a;
        border: 1px solid rgba(34, 197, 94, 0.3);
    }

    .estado-warning {
        background: rgba(234, 179, 8, 0.15);
        color: #d97706;
        border: 1px solid rgba(234, 179, 8, 0.3);
    }

    /* Botones de acción */
    .btn-accion {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        margin-right: 0.75rem;
        margin-bottom: 0.75rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .btn-contactar {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #1d4ed8;
        border: 1px solid #93c5fd;
    }

    .btn-contactar:hover {
        background: linear-gradient(135deg, #bfdbfe, #93c5fd);
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(59, 130, 246, 0.25);
    }

    .btn-valorar {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        color: #16a34a;
        border: 1px solid #86efac;
    }

    .btn-valorar:hover {
        background: linear-gradient(135deg, #bbf7d0, #86efac);
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(22, 163, 74, 0.25);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .detalle-reserva-container {
            padding: 2rem 1rem;
        }

        .detalle-header h1 {
            font-size: 1.875rem;
        }

        .servicio-info {
            flex-direction: column;
            text-align: center;
            gap: 1.5rem;
        }

        .servicio-imagen {
            margin: 0 auto;
            width: 120px;
            height: 120px;
        }

        .card-body {
            padding: 2rem;
        }

        .datos-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .btn-accion {
            margin-right: 0;
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .detalle-header h1 {
            font-size: 1.75rem;
        }

        .precio-servicio {
            font-size: 1.5rem;
        }

        .card-body {
            padding: 1.75rem;
        }
    }
</style>

@section('content')
    <div class="detalle-reserva-container">
        <div class="detalle-header">
            <h1>Reserva #{{ $reserva->IDReserva }}</h1>
            <a href="{{ route('reservas.mi-lista') }}" class="btn-volver">Volver a mis compras</a>
        </div>

        <div class="card-detalle">
            <div class="card-body">
                <!-- Servicio -->
                <div class="seccion-detalle">
                    <h2 class="seccion-titulo">📦 Servicio contratado</h2>
                    <div class="servicio-info">
                        @php
                            $imagen = $servicio->fotoPrincipal?->RutaFoto
                                ? asset('storage/' . ltrim($servicio->fotoPrincipal->RutaFoto, '/'))
                                : asset('storage/categorias/' . strtolower($servicio->categoria->Nombre ?? 'default') . '.jpg');
                        @endphp

                        <img src="{{ $imagen }}" alt="{{ $servicio->Nombre }}" class="servicio-imagen">

                        <div class="servicio-detalles">
                            <h3 class="servicio-nombre">{{ $servicio->Nombre }}</h3>
                            <p class="servicio-descripcion">{{ $servicio->Descripcion }}</p>
                            <p class="precio-servicio"
                                style="font-size: 1.5rem; font-weight: 700; color: #0ea5e9; margin: 0;">
                                {{ number_format($detalle->Precio, 2) }} €
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Información de la reserva -->
                <div class="seccion-detalle">
                    <h2 class="seccion-titulo">📋 Información de la reserva</h2>
                    <div class="datos-grid">
                        <div class="dato-item">
                            <span class="dato-label">ID Reserva</span>
                            <span class="dato-valor">#{{ $reserva->IDReserva }}</span>
                        </div>
                        <div class="dato-item">
                            <span class="dato-label">Fecha de reserva</span>
                            <span
                                class="dato-valor">{{ \Carbon\Carbon::parse($reserva->created_at)->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="dato-item">
                            <span class="dato-label">Estado</span>
                            <span
                                class="estado-badge estado-{{ $reserva->Estado == 'confirmada' ? 'success' : 'warning' }}">
                                {{ ucfirst($reserva->Estado) }}
                            </span>
                        </div>
                        <div class="dato-item">
                            <span class="dato-label">Total</span>
                            <span class="dato-valor">{{ number_format($reserva->Total, 2) }} €</span>
                        </div>
                        @if($detalle->FechaServicio)
                            <div class="dato-item">
                                <span class="dato-label">Fecha del servicio</span>
                                <span
                                    class="dato-valor">{{ \Carbon\Carbon::parse($detalle->FechaServicio)->format('d/m/Y') }}</span>
                            </div>
                        @endif
                        @if($detalle->Precio > $servicio->Precio)
                            <div class="dato-item">
                                <span class="dato-label">Número de personas</span>
                                <span class="dato-valor">{{ $detalle->Precio / $servicio->Precio }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Proveedor -->
                <div class="seccion-detalle">
                    <h2 class="seccion-titulo">👤 Proveedor</h2>
                    <div class="datos-grid">
                        <div class="dato-item">
                            <span class="dato-label">Nombre</span>
                            <span class="dato-valor">{{ $servicio->proveedor->Nombre ?? 'Proveedor no disponible' }}</span>
                        </div>
                        <div class="dato-item">
                            <span class="dato-label">Ciudad</span>
                            <span class="dato-valor">{{ $servicio->proveedor->Ciudad ?? 'No especificada' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="seccion-detalle">
                    <h2 class="seccion-titulo">⚙️ Acciones disponibles</h2>
                    <div>
                        <a href="{{ route('mensajes') }}" class="btn-accion btn-contactar">
                            💬 Contactar con el proveedor
                        </a>
                        @php
                            $yaValorado = $servicio->valoraciones->contains('idUsuario', auth()->id());
                        @endphp
                        @if(!$yaValorado && $reserva->Estado == 'confirmada')
                            <a href="#" class="btn-accion btn-valorar"
                                onclick="alert('Funcionalidad de valoración en desarrollo')">
                                ⭐ Valorar servicio
                            </a>
                        @elseif($yaValorado)
                            <span class="btn-accion" style="background: #f3f4f6; color: #6b7280; cursor: not-allowed;">
                                ⭐ Ya valorado
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection