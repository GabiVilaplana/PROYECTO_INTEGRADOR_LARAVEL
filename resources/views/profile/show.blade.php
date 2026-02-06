@extends('layouts.layoutbaseproyecto')

@section('title', 'Mi Perfil')

@section('content')
<div class="container mx-auto py-8 px-4">
    <h1 class="text-2xl font-bold mb-6">Mi Perfil</h1>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex flex-col md:flex-row items-center gap-6 mb-6">
            <img src="{{ $user->fotoPerfilUrl }}" alt="Foto de perfil" class="w-20 h-20 rounded-full object-cover">
            <div>
                <h2 class="text-xl font-semibold">{{ $user->Nombre }} {{ $user->Apellidos }}</h2>
                <p class="text-gray-600">{{ $user->email }}</p>
                <p class="text-sm text-gray-500">Rol: {{ $user->rol->Nombre ?? 'Usuario' }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="font-medium mb-2">Servicios activos</h3>
                <p>{{ $user->servicios()->where('Activo', 1)->count() }}</p>
            </div>
            <div>
                <h3 class="font-medium mb-2">Reservas</h3>
                <p>{{ $user->reservas()->count() }}</p>
            </div>
        </div>

        <div class="mt-8">
            <a href="{{ route('profile.edit') }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Editar perfil
            </a>
        </div>
    </div>
</div>
@endsection