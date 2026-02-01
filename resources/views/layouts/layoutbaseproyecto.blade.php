
<body>
    @include('layouts.partials.header')
    <main>@yield('content')</main>
    @include('layouts.partials.footer')

     <!-- --- INICIO CHATBOT N8N --- -->
    <link href="https://cdn.jsdelivr.net/npm/@n8n/chat/dist/style.css" rel="stylesheet" />
    <script type="module">
        import { createChat } from 'https://cdn.jsdelivr.net/npm/@n8n/chat/dist/chat.bundle.es.js';

        createChat({
            webhookUrl: 'http://localhost:5678/webhook/5a2850e5-267c-40bc-b03e-298857e06950/chat',
            showWelcomeScreen: true,
            title: '¡Hola! 👋',
            subtitle: 'Asistente de TaskLink. Estamos aquí para ayudarte 24/7.',
            placeholder: 'Escribe tu pregunta...',
            initialMessages: [
                '¡Hola! 👋 Soy el asistente de TaskLink.',
                '¿Buscas algún servicio o quieres consultar el estado de una reserva?'
            ],
            i18n: {
                en: {
                    title: '¡Hola! 👋', // Esto corregirá el "Hi there!"
                    subtitle: 'Asistente de TaskLink. Estamos para ayudarte 24/7.', // Esto corregirá el "Start a chat..."
                    inputPlaceholder: 'Escribe tu pregunta...',
                }
            }
        });
    </script>
    <!-- --- FIN CHATBOT N8N --- -->
</body>