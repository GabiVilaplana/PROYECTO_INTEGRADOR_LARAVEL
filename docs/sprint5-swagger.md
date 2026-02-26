# Documentación Interactiva con Swagger

**Objetivo:** Facilitar la integración manual y técnica de la API para externos o futuros desarrolladores.

## Implementación Técnica:

### 1. Documentación OpenAPI (L5-Swagger)
Se instaló y configuró el paquete **L5-Swagger** para generar una interfaz visual de la API.

-   **Anotaciones en Código**: Se utilizaron bloques de comentarios PHPDoc en los controladores para definir esquemas (`@OA\Schema`), parámetros de entrada y respuestas exitosas.
-   **Swagger UI**: Se habilitó una ruta dedicada (`/api/documentation`) donde se puede probar cada endpoint enviando datos reales.

### 2. Mantenimiento Automático
Se configuró para que la documentación se regenere automáticamente al realizar cambios significativos en los controladores de la API, asegurando que siempre esté actualizada.
