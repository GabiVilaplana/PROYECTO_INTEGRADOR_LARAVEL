# Despliegue en Producción mediante Contenedores

**Objetivo:** Trasladar la aplicación del entorno de desarrollo a un servidor estable y accesible al público.

## Implementación Técnica:

### 1. Optimización para Producción
El despliegue final no es una copia directa del entorno local:

-   **Build de Assets**: Se ejecutó `npm run build` para compilar y minificar los archivos JS/CSS de Vue, reduciendo drásticamente el peso de la web.
-   **Configuración de Laravel**: Se activó la caché de configuración, rutas y vistas mediante comandos artisan para acelerar el tiempo de respuesta del servidor PHP.

### 2. Estructura de Docker en Producción
Se adaptó el archivo `docker-compose.yml` para entornos vivos:

-   **Restart Policies**: Configurado como `unless-stopped` para asegurar que el servicio se reinicie automáticamente tras un posible fallo del servidor.
-   **Redes Aisladas**: Uso de redes internas de Docker para que la base de datos no sea accesible directamente desde internet, solo desde el contenedor de la aplicación.
