@extends('layouts.layoutbaseproyecto')

@section('title', 'Crear Servicio')

@section('content')
    <div class="container mx-auto py-8 px-4 max-w-3xl">
        <h1 class="text-2xl font-bold mb-6">Crear Nuevo Servicio</h1>

        <form action="{{ route('servicios.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Nombre -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2" for="Nombre">Nombre del servicio *</label>
                <input type="text" name="Nombre" id="Nombre" required
                    class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    value="{{ old('Nombre') }}">
                @error('Nombre')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Descripción -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2" for="Descripcion">Descripción *</label>
                <textarea name="Descripcion" id="Descripcion" rows="4" required
                    class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">{{ old('Descripcion') }}</textarea>
                @error('Descripcion')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Categoría -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2" for="idCategoria">Categoría *</label>
                <select name="idCategoria" id="idCategoria" required
                    class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Selecciona una categoría</option>
                    @foreach($categorias as $cat)
                        <option value="{{ $cat->IDCategoria }}" {{ old('idCategoria') == $cat->IDCategoria ? 'selected' : '' }}>
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
                        value="{{ old('Precio') }}">
                    @error('Precio')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2" for="Duracion">Duración (minutos) *</label>
                    <input type="number" name="Duracion" id="Duracion" required min="1"
                        class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        value="{{ old('Duracion') }}">
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
                            value="{{ old('lat') ?? '41.3851' }}">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1" for="lng">Longitud *</label>
                        <input type="number" step="any" name="lng" id="lng" required
                            class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            value="{{ old('lng') ?? '2.1734' }}">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1" for="radio_km">Radio (km) *</label>
                        <input type="number" step="0.1" name="radio_km" id="radio_km" required min="0.1" max="100"
                            class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            value="{{ old('radio_km') ?? '5' }}">
                    </div>
                </div>
                @error('lat')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
                @error('lng')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
                @error('radio_km')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
            </div>

            <!-- Fotos -->
            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-2">Fotos del servicio (máx. 5)</label>
                <div id="fotos-container" class="space-y-3">
                    <div class="flex items-start gap-3">
                        <input type="file" name="fotos[]" accept="image/*" class="mt-1">
                        <label class="inline-flex items-center">
                            <input type="radio" name="foto_principal" value="0" checked>
                            <span class="ml-2 text-sm">Principal</span>
                        </label>
                        <button type="button" onclick="removeFoto(this)" class="text-red-500 hover:text-red-700">×</button>
                    </div>
                </div>
                <button type="button" onclick="addFoto()" class="mt-2 text-blue-600 text-sm">+ Añadir otra foto</button>
                @error('fotos')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Botones -->
            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Crear Servicio
                </button>
                <a href="{{ route('home') }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300">
                    Cancelar
                </a>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            let fotoIndex = 1;
            function addFoto() {
                if (fotoIndex >= 5) return;
                const container = document.getElementById('fotos-container');
                const div = document.createElement('div');
                div.className = 'flex items-start gap-3';
                div.innerHTML = `
                <input type="file" name="fotos[]" accept="image/*" class="mt-1">
                <label class="inline-flex items-center">
                    <input type="radio" name="foto_principal" value="${fotoIndex}">
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