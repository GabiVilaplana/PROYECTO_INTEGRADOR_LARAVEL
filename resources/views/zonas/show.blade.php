@extends('layouts.layoutbaseproyecto')

@section('content')
    <div class="zona-container">
        <div class="zona-header">
            <h1>Servicios en {{ $zona->nombre }}</h1>
            <a href="{{ route('home') }}" class="btn-volver">Volver al inicio</a>
        </div>

        @if($servicios->isEmpty())
            <div class="alert-no-servicios">
                <p>No hay servicios disponibles en <strong>{{ $zona->nombre }}</strong> en este momento.</p>
            </div>
        @else
            <div class="servicios-grid">
                @foreach($servicios as $servicio)
                    <div class="card-servicio">
                        @php
                            $imagen = null;
                            if ($servicio->fotoPrincipal?->RutaFoto) {
                                $imagen = asset('storage/' . ltrim($servicio->fotoPrincipal->RutaFoto, '/'));
                            } elseif ($servicio->categoria?->Imagen) {
                                $imagen = asset('storage/' . ltrim($servicio->categoria->Imagen, '/'));
                            }
                        @endphp

                        @if($imagen)
                            <img src="{{ $imagen }}" class="card-img-top" alt="{{ $servicio->Nombre }}">
                        @else
                            <div class="placeholder-imagen">
                                Sin imagen disponible
                            </div>
                        @endif

                        <div class="card-body">
                            <h5 class="card-title">{{ $servicio->Nombre }}</h5>
                            <p class="card-text">{{ Str::limit($servicio->Descripcion, 100) }}</p>
                            @if($servicio->Precio)
                                <p class="precio-servicio">{{ number_format($servicio->Precio, 2) }} €</p>
                            @endif
                            <a href="{{ route('servicios.show', $servicio->IDServicio) }}" class="btn-detalles">Ver detalles</a>
                            <a href="{{ route('servicios.show', $servicio->IDServicio) }}" class="btn-trasera-Comprar">Comprar
                                ahora</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection