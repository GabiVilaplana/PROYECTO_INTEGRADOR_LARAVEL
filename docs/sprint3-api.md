# Desarrollo de la API RESTful

**Objetivo:** Exponer la funcionalidad del sistema mediante servicios web consumibles por aplicaciones modernas.

## Implementación Técnica:

### 1. Rutas y Versionado
Las rutas se encuentran en `routes/api.php`, separadas de las rutas web habituales.

-   **Prefixing**: Se agruparon por recursos (`/servicios`, `/usuarios`, `/categorias`).
-   **Middleware de API**: Uso de `EnsureFrontendRequestsAreStateful` para permitir cookies entre dominios durante el desarrollo de la SPA.

### 2. API Resources y Datos JSON
Para garantizar una respuesta API consistente, se implementaron **API Resources**:

-   **Transformación**: Se oculta información sensible (como IDs internos o tokens) y se renonbran campos para que sean más legibles en el frontend.
-   **Estandarización**: Todos los errores se devuelven con el código HTTP correspondiente (404, 422, 500) y un mensaje JSON descriptivo.

### 3. Documentación Estructurada
Se preparó la base para la documentación técnica mediante anotaciones en el código que luego serían procesadas por herramientas de generación de documentación API.
