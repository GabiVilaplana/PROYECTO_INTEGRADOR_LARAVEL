@vite(['resources/js/app.js'])

<section class="hero">
    <video class="hero-video" autoplay muted loop>
        <source src="{{ asset('MP4/video.mp4') }}" type="video/mp4" />
        Tu navegador no soporta videos.
    </video>
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1>Conecta el talento con las oportunidades</h1>
        <p>
            Conectamos tus talentos con las oportunidades<br />
            que te ofrecen otras personas
        </p>
        <a href="#servicios" class="hero-button">Descubre más</a>
    </div>
</section>
