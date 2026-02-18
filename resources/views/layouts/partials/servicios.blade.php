@vite(['resources/js/app.js'])

<section id="servicios" class="servicios-section">

	@foreach($categorias as $categoria)
		@php
			$serviciosCategoria = $servicios->where('idCategoria', $categoria->IDCategoria);
		@endphp

		@if($serviciosCategoria->isNotEmpty())
			<div class="carousel-wrapper" id="carousel-{{ $categoria->IDCategoria }}">
				<h2>{{ $categoria->Nombre}}</h2>
				<button class="carousel-btn left" aria-label="Anterior">‹</button>
				<button class="carousel-btn right" aria-label="Siguiente">›</button>

				<section class="courses carousel-track">
					@foreach($serviciosCategoria as $servicio)
						@php
							$imagen = $servicio->fotoPrincipal
								? asset('storage/' . ltrim(strtolower($categoria->Imagen), '/'))
								: asset('storage/' . ltrim($servicio->fotoPrincipal->RutaFoto, '/'));

							$categoryClass = 'category-' . Str::slug($categoria->Nombre, '-');
							$textoLectura = "Servicio de {$servicio->Nombre}. Descripción: {$servicio->Descripcion}. Precio: {$servicio->Precio} euros.";
						@endphp

						<div class="course-completo {{ $categoryClass }}">
							<!-- Contenedor interno para flip -->
							<div class="course-completo-inner">
								<!-- Botón narrar -->
								<button class="btn-narrar" aria-label="Escuchar" data-texto="{{ $textoLectura }}">🔊</button>

								<!-- Vista frontal -->
								<div class="course" data-id="{{ $servicio->IDServicio }}">
									<img src="{{ $imagen }}" alt="{{ $servicio->Nombre }}">
									<h3>{{ $servicio->Nombre }}</h3>
									<p>{{ Str::limit($servicio->Descripcion, 120) }}</p>
									<div class="course-footer">
										<span class="price">Precio - {{ number_format($servicio->Precio, 2) }}€</span>
									</div>
								</div>

								<!-- Vista trasera -->
								<div class="course-trasera">
									<h4>Información del Servicio</h4>
									<p>Nombre: {{ $servicio->Nombre }}</p>
									<p>Descripción: {{ Str::limit($servicio->Descripcion, 240) }}</p>
									<span class="precio">{{ number_format($servicio->Precio, 2) }} €</span>

									<!-- Botones de acción -->
									<div class="trasera-botones">
										<a href="{{ route('servicios.show', $servicio->IDServicio) }}" class="btn-trasera">Ver
											más</a>
										@auth
											<a href="{{ route('reservas.pago', $servicio->IDServicio) }}"
												class="btn-trasera-Comprar">Comprar ahora</a>
										@else
											<a href="{{ route('login') }}" class="btn-trasera-Comprar"
												style="background-color: #6c757d;">Inicia sesión para comprar</a>
										@endauth
										<a href="javascript:void(0)" 
											onclick="ejecutarCompraRapida({{ $servicio->IDServicio }}, '{{ $servicio->Nombre }}')" 
											class="btn-trasera-Comprar">
											Comprar ahora
										</a>
									</div>
								</div>
							</div> <!-- /.course-completo-inner -->
						</div> <!-- /.course-completo -->

					@endforeach
				</section>
			</div>
		@endif
	@endforeach

</section>

<!-- Cargamos SweetAlert2 para una interfaz profesional -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function ejecutarCompraRapida(idServicio, nombreServicio) {
    // 1. Comprobamos si el usuario está autenticado usando Laravel
    const isAuthenticated = @json(auth()->check());

    if (!isAuthenticated) {
        // Si no está logueado, lo mandamos al login
        Swal.fire({
            title: '¡Espera!',
            text: "Debes iniciar sesión para contratar este servicio.",
            icon: 'info',
            confirmButtonText: 'Ir al Login'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('login') }}";
            }
        });
        return;
    }

    // 2. Si está logueado, pedimos confirmación
    Swal.fire({
        title: '¿Confirmar compra rápida?',
        text: `Vas a contratar: ${nombreServicio}`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, comprar ahora',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostrar estado de carga
            Swal.fire({
                title: 'Procesando...',
                didOpen: () => { Swal.showLoading() }
            });

            // 3. Llamada a la API de Laravel
            fetch('/api/compra-rapida', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ idServicio: idServicio })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire(
                        '¡Contratado!',
                        'El servicio se ha pagado correctamente. Revisa tu correo.',
                        'success'
                    );
                } else {
                    Swal.fire('Error', data.error || 'Hubo un problema al procesar el pago', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'No se pudo conectar con el servidor', 'error');
            });
        }
    });
}
</script>