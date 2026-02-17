@extends('layouts.layoutbaseproyecto')

@section('title', 'Editar Servicio')

@section('content')
<div class="container mx-auto py-8 px-4 max-w-3xl">
    <h1 class="text-2xl font-bold mb-6">Editar Servicio</h1>

    <form action="{{ route('servicios.update', $servicio->IDServicio) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Nombre -->
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2" for="Nombre">Nombre del servicio *</label>
            <input type="text" name="Nombre" id="Nombre" required
                class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                value="{{ old('Nombre', $servicio->Nombre) }}">
            @error('Nombre')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <!-- Descripción -->
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2" for="Descripcion">Descripción *</label>
            <textarea name="Descripcion" id="Descripcion" rows="4" required
                class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">{{ old('Descripcion', $servicio->Descripcion) }}</textarea>
            @error('Descripcion')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <!-- Categoría -->
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2" for="idCategoria">Categoría *</label>
            <select name="idCategoria" id="idCategoria" required
                class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                <option value="">Selecciona una categoría</option>
                @foreach($categorias as $cat)
                    <option value="{{ $cat->IDCategoria }}" {{ (old('idCategoria') ?? $servicio->idCategoria) == $cat->IDCategoria ? 'selected' : '' }}>
                        {{ $cat->Nombre }}
                    </option>
                @endforeach
            </select>
            @error('idCategoria')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <!-- Precio y Duración -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 font-medium mb-2" for="Precio">Precio (€) *</label>
                <input type="number" step="0.01" name="Precio" id="Precio" required min="0"
                    class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    value="{{ old('Precio', $servicio->Precio) }}">
                @error('Precio')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-gray-700 font-medium mb-2" for="Duracion">Duración (minutos) *</label>
                <input type="number" name="Duracion" id="Duracion" required min="1"
                    class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    value="{{ old('Duracion', $servicio->Duracion) }}">
                @error('Duracion')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <!-- Ubicación -->
        <div class="mb-4">
            <h2 class="font-medium text-gray-700 mb-2">Ubicación del servicio</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1" for="lat">Latitud *</label>
                    <input type="number" step="any" name="lat" id="lat" required
                        class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        value="{{ old('lat', $servicio->lat) ?? '41.3851' }}">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1" for="lng">Longitud *</label>
                    <input type="number" step="any" name="lng" id="lng" required
                        class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        value="{{ old('lng', $servicio->lng) ?? '2.1734' }}">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1" for="radio_km">Radio (km) *</label>
                    <input type="number" step="0.1" name="radio_km" id="radio_km" required min="0.1" max="100"
                        class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        value="{{ old('radio_km', $servicio->radio_km) ?? '5' }}">
                </div>
            </div>
            @error('lat')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
            @error('lng')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
            @error('radio_km')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
        </div>

        <!-- Fotos existentes -->
        @if($servicio->fotos->isNotEmpty())
        <div class="mb-6">
            <h3 class="font-medium text-gray-700 mb-2">Fotos actuales</h3>
            <div class="flex flex-wrap gap-3 mb-4">
                @foreach($servicio->fotos as $index => $foto)
                    <div class="relative group">
                        <img src="{{ asset('storage/' . $foto->RutaFoto) }}" alt="Foto {{ $index + 1 }}"
                             class="w-24 h-24 object-cover rounded border">
                        <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded flex items-center justify-center">
                            <span class="text-white text-xs">Actual</span>
                        </div>
                        <div class="mt-1 text-center">
                            <label class="inline-flex items-center text-sm">
                                <input type="radio" name="foto_principal_existente" value="{{ $foto->IDFoto }}"
                                    {{ ($servicio->fotoPrincipal && $servicio->fotoPrincipal->IDFoto == $foto->IDFoto) ? 'checked' : '' }}>
                                <span class="ml-1">Principal</span>
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Nuevas fotos -->
        <div class="mb-6">
            <label class="block text-gray-700 font-medium mb-2">Añadir nuevas fotos (máx. 5 en total)</label>
            <div id="fotos-container" class="space-y-3">
                <div class="flex items-start gap-3">
                    <input type="file" name="fotos_nuevas[]" accept="image/*" class="mt-1">
                    <label class="inline-flex items-center">
                        <input type="radio" name="foto_principal_nueva" value="0">
                        <span class="ml-2 text-sm">Principal</span>
                    </label>
                    <button type="button" onclick="removeFoto(this)" class="text-red-500 hover:text-red-700">×</button>
                </div>
            </div>
            <button type="button" onclick="addFoto()" class="mt-2 text-blue-600 text-sm">+ Añadir otra foto</button>
            @error('fotos_nuevas')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <!-- Botones -->
        <div class="flex gap-3">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Guardar Cambios
            </button>
            <a href="{{ route('servicios.show', $servicio->IDServicio) }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300">
                Cancelar
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
let fotoIndex = 1;
function addFoto() {
    const container = document.getElementById('fotos-container');
    if (container.children.length >= 5) return;
    
    const div = document.createElement('div');
    div.className = 'flex items-start gap-3';
    div.innerHTML = `
        <input type="file" name="fotos_nuevas[]" accept="image/*" class="mt-1">
        <label class="inline-flex items-center">
            <input type="radio" name="foto_principal_nueva" value="${fotoIndex}">
            <span class="ml-2 text-sm">Principal</span>
        </label>
        <button type="button" onclick="removeFoto(this)" class="text-red-500 hover:text-red-700">×</button>
    `;
    container.appendChild(div);
    fotoIndex++;
}

function removeFoto(button) {
    if (document.querySelectorAll('#fotos-container > div').length <= 1) return;
    button.closest('div').remove();
    fotoIndex--;
}
</script>
@endpush
@endsection