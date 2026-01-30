@vite(['resources/js/app.js'])

<section id="servicios" class="servicios-section">

	@foreach($categorias as $categoria)
		@php
			$serviciosCategoria = $servicios->where('idCategoria', $categoria->IDCategoria);
		@endphp

		@if($serviciosCategoria->isNotEmpty())
			<div class="carousel-wrapper" id="carousel-{{ $categoria->IDCategoria }}">
				<h2>{{ $categoria->Nombre }}</h2>
				<button class="carousel-btn left" aria-label="Anterior">‹</button>
				<button class="carousel-btn right" aria-label="Siguiente">›</button>

				<section class="courses carousel-track">
					@foreach($serviciosCategoria as $servicio)
						@php
							$imagen = $servicio->fotoPrincipal
								? asset('storage/' . ltrim($servicio->fotoPrincipal->RutaFoto, '/'))
								: asset('store/' . Str::slug($categoria->Nombre, '-') . '.jpg');

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
										<a href="/compra-temporal" class="btn-trasera-Comprar">Comprar ahora</a>
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