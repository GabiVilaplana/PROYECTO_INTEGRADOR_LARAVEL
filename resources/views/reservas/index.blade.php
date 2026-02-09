@extends('layouts.layoutbaseproyecto')

@section('content')
    <div class="mis-compras-container">
        <div class="mis-compras-header">
            <h1>Mis servicios contratados</h1>
            <a href="{{ route('home') }}" class="btn-volver">Volver al inicio</a>
        </div>

        @if($reservas->isEmpty())
            <div class="alert-no-compras">
                <p>No has contratado ningún servicio aún.</p>
            </div>
        @else
            <div class="compras-grid">
                @foreach($reservas as $reserva)
                    {{-- Obtener el primer detalle (asumiendo 1 servicio por reserva) --}}
                    @php
                        $detalle = $reserva->detalles->first();
                        $servicio = $detalle ? $detalle->servicio : null;
                    @endphp

                    @if($servicio)
                        <div class="card-compra">
                            @php
                                $imagen = $servicio->fotoPrincipal?->RutaFoto
                                    ? asset('storage/' . ltrim($servicio->fotoPrincipal->RutaFoto, '/'))
                                    : asset('storage/categorias/' . strtolower($servicio->categoria->Nombre ?? 'default') . '.jpg');
                            @endphp

                            @if($imagen)
                                <img src="{{ $imagen }}" class="compra-img-top" alt="{{ $servicio->Nombre }}">
                            @else
                                <div class="placeholder-imagen">Sin imagen</div>
                            @endif

                            <div class="card-body">
                                <h5 class="card-title">{{ $servicio->Nombre }}</h5>
                                <p class="precio-compra">{{ number_format($detalle->Precio, 2) }} €</p>
                                <span class="estado-badge estado-{{ $reserva->Estado == 'confirmada' ? 'success' : 'warning' }}">
                                    {{ ucfirst($reserva->Estado) }}
                                </span>
                                <a href="{{ route('servicios.show', $servicio->IDServicio) }}" class="btn-detalles">Ver Servicio</a>
                                <a href="{{ route('reservas.show', $reserva->IDReserva) }}" class="btn-detalles">Ver Reserva</a>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
@endsection