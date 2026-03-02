# 🔐 Autenticación y Seguridad

!!! info "Objetivo del Sistema"
    Implementar un ecosistema de acceso **seguro, escalable y flexible**. TaskLink garantiza la protección de los datos del usuario mientras ofrece una experiencia de navegación fluida tanto en la web como en futuros dispositivos móviles.

---

## 🛡️ 1. Infraestructura: Laravel Breeze & Sanctum

Hemos integrado las herramientas de seguridad más modernas del ecosistema Laravel (versión 12) para gestionar la identidad:

- **Laravel Breeze (API Stack):** Proporciona la lógica base de autenticación, optimizada para funcionar como un backend moderno que alimenta nuestra SPA en Vue.
- **Laravel Sanctum:** Gestiona la emisión de tokens y la autenticación basada en cookies. Esto permite que el usuario se mantenga conectado de forma segura sin comprometer su privacidad.

!!! abstract "Lógica de Registro Personalizada"
    El controlador `RegisteredUserController` ha sido adaptado para las necesidades de TaskLink:
_ **Segmentación Automática:** Al registrarse, el sistema separa de forma inteligente el nombre y los apellidos introducidos.
_ **Asignación de Roles:** Por defecto, cada nuevo usuario recibe el rol de **Cliente (ID: 2)**, garantizando que el acceso a las funciones de "Proveedor" requiera una validación o cambio de perfil específico.

---

## 🛡️ 2. Seguridad y Criptografía

La integridad de las credenciales es nuestra prioridad absoluta. Aplicamos múltiples capas de protección:

1.  **Encriptación de Nivel Bancario:** Nunca guardamos contraseñas en texto plano. Utilizamos el algoritmo **Bcrypt** mediante mutadores automáticos en el modelo `Usuario`.
2.  **Protección de Rutas (Middleware):** Todas las rutas sensibles dentro de `api.php` están blindadas por el middleware `auth:sanctum`. Nadie puede acceder a perfiles, reservas o mensajes sin un token válido.
3.  **Control de Abuso (Rate Limiting):** Implementamos mecanismos nativos de Laravel para mitigar ataques de fuerza bruta, limitando el número de intentos de login por minuto.

---

## 🔑 3. Gestión de Sesiones y Estado

TaskLink mantiene una comunicación constante entre el servidor y el cliente para validar la identidad:

- **Punto de Verificación (`/api/check`):** La SPA consulta este endpoint para reconstruir el estado de la sesión si el usuario refresca el navegador.
- **Persistencia Segura:** Las sesiones se gestionan de forma segura, permitiendo cierres de sesión (`logout`) que invalidan tanto el token de la base de datos como la cookie del navegador.

!!! success "Ecosistema Blindado"
    Gracias a la combinación de los estándares de Laravel y nuestras personalizaciones, TaskLink ofrece un entorno donde los profesionales y clientes pueden operar con total confianza de que su información está siempre protegida.
