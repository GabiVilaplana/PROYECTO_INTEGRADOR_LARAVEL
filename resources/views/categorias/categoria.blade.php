@extends('layouts.layoutbaseproyecto')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>Servicios de {{ $categoria->Nombre }}</h1>
                @if($categoria->Descripcion)
                    <p class="lead">{{ $categoria->Descripcion }}</p>
                @endif

                @if($servicios->count() > 0)
                    <div class="row">
                        @foreach($servicios as $servicio)
                            <div class="col-md-4 mb-4">
                                <div class="card h-100">
                                    @php
                                        if ($servicio->fotoPrincipal) {
                                            $imagen = asset('storage/' . ltrim($servicio->fotoPrincipal->ruta, '/'));
                                        } elseif ($servicio->categoria) {
                                            $imagen = asset('storage/' . ltrim($servicio->categoria->Imagen, '/'));
                                        } else {
                                            $imagen = null;
                                        }
                                    @endphp

                                    @if($imagen)
                                        <img src="{{ $imagen }}" class="card-img-top"
                                            alt="{{ $servicio->Titulo ?? 'Servicio sin título' }}"
                                            style="height: 200px; object-fit: cover;"/>
                                    @else
                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                            style="height: 200px;">
                                            <span class="text-muted">Sin imagen</span>
                                        </div>
                                    @endif
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title">{{ $servicio->Titulo }}</h5>
                                        <p class="card-text flex-grow-1">{{ Str::limit($servicio->Descripcion, 100) }}</p>
                                        @if($servicio->Precio)
                                            <p class="card-text"><strong>Precio:</strong> {{ $servicio->Precio }}€</p>
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
                        <p>No hay servicios disponibles en esta categoría actualmente.</p>
                    </div>
                @endif

                <a href="{{ route('home') }}" class="btn btn-secondary mt-3">← Volver al inicio</a>
            </div>
        </div>
    </div>
@endsection