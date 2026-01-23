@vite(['resources/js/app.js'])
@section('content')
    <div class="carousel-wrapper">
        <h2>Categorias</h2>
        <button class="carousel-btn left" id="btn-left">‹</button>
        <button class="carousel-btn right" id="btn-right">›</button>
        <section class="courses carousel-track" id="datosCategoria">
            @foreach($categorias as $categoria)
                <div class="card">
                    <img src="{{ asset('storage/' . $categoria->Imagen) }}" alt="{{ $categoria->Nombre }}">
                    <h3>{{ $categoria->Nombre }}</h3>
                    <p>{{ $categoria->Descripcion }}</p>
                </div>
            @endforeach

        </section>
    </div>
@endsection