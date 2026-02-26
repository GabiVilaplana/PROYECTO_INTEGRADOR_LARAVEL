# Autenticación API y Consumo de Datos

**Objetivo:** Sincronizar de forma segura el estado de autenticación entre el navegador y el servidor Laravel.

## Implementación Técnica:

### 1. Laravel Sanctum (Cookies y Tokens)
Se configuró **Laravel Sanctum** para gestionar la seguridad de las peticiones API.

-   **CSRF Protection**: Antes cada login o acción sensible, el frontend solicita un token CSRF para prevenir ataques maliciosos.
-   **Stateful Requests**: Se configuraron los dominios permitidos en `cors.php` para asegurar que las cookies de sesión se envíen correctamente desde el puerto de Vite al puerto de Laravel.

### 2. Gestión de Estado Global (Pinia)
Para mantener la información del usuario logueado en toda la aplicación, se utilizó **Pinia**.

-   **Persistencia**: Se asegura que el estado del usuario se mantenga accesible desde cualquier componente de la SPA, permitiendo mostrar u ocultar opciones de menú dinámicamente.

### 3. Flujo de Login y Registro
Se implementaron formularios dinámicos con validación asíncrona que responden a los errores enviados por la API de Laravel en tiempo real, mejorando la experiencia del usuario (UX).
