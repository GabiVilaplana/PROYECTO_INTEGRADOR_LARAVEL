<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Acceso clientes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css'])

</head>


<body class="min-h-screen bg-gradient-to-br from-blue-900 via-blue-800 to-blue-700 flex items-center justify-center">

    <div class="w-full max-w-4xl bg-white rounded-2xl shadow-2xl overflow-hidden grid md:grid-cols-2">

        {{-- PANEL IZQUIERDO --}}
        <div class="hidden md:flex flex-col justify-center p-12 bg-blue-900 text-white">
            <h2 class="text-3xl font-bold mb-4">
                Servicios profesionales
            </h2>

            <p class="text-blue-200 mb-8">
                Accede a tu área privada y gestiona tus servicios contratados.
            </p>

            <ul class="space-y-4 text-sm">
                <li class="flex items-center gap-2">
                    <span class="text-blue-300">✔</span> Control total
                </li>
                <li class="flex items-center gap-2">
                    <span class="text-blue-300">✔</span> Atención personalizada
                </li>
                <li class="flex items-center gap-2">
                    <span class="text-blue-300">✔</span> Acceso seguro
                </li>
            </ul>
        </div>

        {{-- LOGIN --}}
        <div class="p-8 sm:p-12">
            {{-- LOGO OPCIONAL --}}
            {{-- <img src="{{ asset('img/logo.png') }}" class="h-12 mb-6"> --}}

            <h1 class="text-2xl font-bold text-gray-900 mb-1">
                Iniciar sesión
            </h1>

            <p class="text-gray-500 mb-8">
                Bienvenido de nuevo
            </p>

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                {{-- EMAIL --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Email
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full rounded-lg border-gray-300 focus:border-blue-600 focus:ring-blue-600"
                        placeholder="cliente@empresa.com">
                    @error('email')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- PASSWORD --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Contraseña
                    </label>
                    <input type="password" name="password" required
                        class="w-full rounded-lg border-gray-300 focus:border-blue-600 focus:ring-blue-600"
                        placeholder="••••••••">
                    @error('password')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- OPCIONES --}}
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="remember" class="rounded text-blue-600">
                        Recordarme
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-blue-600 hover:underline">
                            ¿Olvidaste tu contraseña?
                        </a>
                    @endif
                </div>

                {{-- BOTÓN --}}
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition">
                    Acceder
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-8">
                ¿No tienes cuenta?
                <a href="#" class="text-blue-600 font-medium hover:underline">
                    Contacta con nosotros
                </a>
            </p>
        </div>
    </div>

</body>

</html>