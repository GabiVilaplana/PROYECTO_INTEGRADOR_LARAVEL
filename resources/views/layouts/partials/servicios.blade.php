@vite(['resources/js/app.js'])

<section id="servicios" class="servicios-section">
	<h2>Servicios</h2>
	<div class="servicios-grid">
		@if(isset($servicios) && $servicios->count())
			@foreach($servicios as $servicio)
				<article class="servicio-card">
					<img src="{{ asset('storage/' . ($servicio->Foto ?? 'default.png')) }}" alt="{{ $servicio->Titulo ?? 'Servicio' }}">
					<h3>{{ $servicio->Titulo ?? 'Sin título' }}</h3>
					<p>{{ Str::limit($servicio->Descripcion ?? '', 120) }}</p>
				</article>
			@endforeach
		@else
			<p>No hay servicios disponibles por el momento.</p>
		@endif
	</div>
</section>

