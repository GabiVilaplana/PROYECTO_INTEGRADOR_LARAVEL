# Procesamiento Asíncrono con Colas y Redis

**Objetivo:** Optimizar los tiempos de respuesta del servidor delegando tareas pesadas al backend asíncrono.

## Implementación Técnica:

### 1. Colas de Trabajo (Queues)
Se implementó el sistema de colas de Laravel para tareas que no requieren una respuesta inmediata para el usuario:

-   **Envío de Emails**: Los correos de bienvenida o confirmación de reserva se encolan automáticamente.
-   **Procesamiento de Imágenes**: Tareas de redimensionado o guardado de fotos de servicios.

### 2. Infraestructura con Redis
Se configuró **Redis** como el driver de cache y colas.

-   **Worker Dedicado**: Un proceso `php artisan queue:work` se mantiene activo ejecutando las tareas en segundo plano.
-   **Tolerancia a Fallos**: Se configuró un número limitado de reintentos para evitar bucles infinitos en caso de errores en jobs externos (como el servidor de correo).
