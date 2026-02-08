@extends('layouts.layoutbaseproyecto')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1>Servicios en {{ $zona->nombre }}</h1>
            <p class="lead">Encuentra servicios disponibles en esta zona.</p>

            @if($servicios->count() > 0)
                <div class="row">
                    @foreach($servicios as $servicio)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                @php
                                    $imagen = null;
                                    if ($servicio->fotoPrincipal && $servicio->fotoPrincipal->RutaFoto) {
                                        $imagen = asset('storage/' . ltrim($servicio->fotoPrincipal->RutaFoto, '/'));
                                    } elseif ($servicio->categoria && $servicio->categoria->Imagen) {
                                        $imagen = asset('storage/' . ltrim($servicio->categoria->Imagen, '/'));
                                    }
                                @endphp

                                @if($imagen)
                                    <img src="{{ $imagen }}" class="card-img-top"
                                        alt="{{ $servicio->Nombre ?? 'Servicio sin título' }}"
                                        style="height: 200px; object-fit: cover;"/>
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                        style="height: 200px;">
                                        <span class="text-muted">Sin imagen</span>
                                    </div>
                                @endif

                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">{{ $servicio->Nombre }}</h5>
                                    <p class="card-text flex-grow-1">{{ Str::limit($servicio->Descripcion, 100) }}</p>
                                    @if($servicio->Precio)
                                        <p class="card-text"><strong>Precio:</strong> {{ number_format($servicio->Precio, 2) }}€</p>
                                    @endif
                                    <a href="{{ route('servicios.show', $servicio->IDServicio) }}"
                                        class="btn btn-primary mt-auto">Ver detalles</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info">
                    <p>No hay servicios disponibles en {{ $zona->nombre }} en este momento.</p>
                </div>
            @endif

            <a href="{{ route('home') }}" class="btn btn-secondary mt-3">← Volver al inicio</a>
        </div>
    </div>
</div>
@endsection