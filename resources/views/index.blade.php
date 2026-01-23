@extends('layouts.layoutbaseproyecto')

@section('title', 'Inicio')


@section('content')
    @include('layouts.partials.containerPaginaPrincipal', ['categorias' => $categorias])
@endsection