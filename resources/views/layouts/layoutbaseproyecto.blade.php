<body>
    @include('layouts.partials.header')
    <main>@yield('content')</main>
    @include('layouts.partials.footer')

    <!-- --- INICIO CHATBOT N8N --- -->
    <link href="https://cdn.jsdelivr.net/npm/@n8n/chat/dist/style.css" rel="stylesheet" />

    <script type="module">
        import { createChat } from 'https://cdn.jsdelivr.net/npm/@n8n/chat/dist/chat.bundle.es.js';

        createChat({
            webhookUrl: 'http://localhost:5678/webhook/35fe1b02-f68f-4121-9dda-e00633f0e25a/chat',
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
                    title: '¡Hola! 👋',
                    subtitle: 'Asistente de TaskLink. Estamos para ayudarte 24/7.',
                    inputPlaceholder: 'Escribe tu pregunta...',
                }
            },
        });
    </script>
    <style>
        #n8n-chat .chat-window-toggle {
            background-color: #0ea5e9 !important;
            /* Fondo azul */
            border-radius: 50% !important;
            width: 60px !important;
            height: 60px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2) !important;
            cursor: pointer !important;
        }

        /* Ícono dentro del botón */
        #n8n-chat .chat-window-toggle svg {
            width: 32px !important;
            height: 32px !important;
            fill: white !important;
            /* Color del ícono */
        }

        #n8n-chat .chat-window {
            background-color: #f0f9ff !important;
            border-radius: 16px !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
        }

        #n8n-chat .chat-header {
            background-color: #0ea5e9 !important;
            color: white !important;
        }

        #n8n-chat .chat-header h1,
        #n8n-chat .chat-header p {
            color: white !important;
        }

        #n8n-chat .chat-body,
        #n8n-chat .chat-footer {
            background-color: #f0f9ff !important;
        }

        #n8n-chat .chat-input-send-button {
            background-color: #0ea5e9 !important;
            border: none !important;
        }

        #n8n-chat .chat-input-send-button:hover {
            background-color: #0284c7 !important;
        }

        @media (max-width: 768px) {
            #n8n-chat .chat-window-toggle {
                width: 50px !important;
                height: 50px !important;
            }

            #n8n-chat .chat-window-toggle svg {
                width: 24px !important;
                height: 24px !important;
            }

            /* Opcional: reducir el tamaño del chat si está abierto */
            #n8n-chat .chat-window {
                width: 95vw !important;
                max-width: 320px !important;
                right: 2.5% !important;
                bottom: 80px !important;
                border-radius: 12px !important;
            }

            #n8n-chat .chat-header {
                padding: 12px !important;
            }

            #n8n-chat .chat-input-container input {
                font-size: 14px !important;
                padding: 8px 12px !important;
            }
        }

        /* Pantallas muy pequeñas (ej. iPhone SE) */
        @media (max-width: 480px) {
            #n8n-chat .chat-window-toggle {
                width: 44px !important;
                height: 44px !important;
            }

            #n8n-chat .chat-window-toggle svg {
                width: 20px !important;
                height: 20px !important;
            }

            #n8n-chat .chat-window {
                width: 96vw !important;
                max-width: 290px !important;
                bottom: 70px !important;
            }
        }

        /* Pantallas grandes (desktop) – asegurar tamaño máximo */
        @media (min-width: 1200px) {
            #n8n-chat .chat-window {
                max-width: 400px !important;
            }
        }
    </style>
</body>