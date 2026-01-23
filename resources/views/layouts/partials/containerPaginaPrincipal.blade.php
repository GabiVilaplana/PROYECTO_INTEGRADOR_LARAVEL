<!-- @extends('layouts.layoutbaseproyecto')
@section('content')
    @include('layouts.partials.categorias')
    @include('layouts.partials.servicios')
    @include('layouts.partials.hero')
@endsection -->

{{-- resources/views/layouts/partials/containerPaginaPrincipal.blade.php --}}
@include('layouts.partials.categorias', ['categorias' => $categorias])
@include('layouts.partials.servicios')
@include('layouts.partials.hero')