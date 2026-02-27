# Arquitectura Backend con Laravel MVC

**Objetivo:** Reimplementar la lógica de negocio siguiendo los mejores estándares de la industria.

## Implementación Técnica:

### 1. Patrón MVC (Modelo-Vista-Controlador)
El backend se estructuró siguiendo estrictamente el patrón MVC de Laravel:

-   **Modelos**: Representan la base de datos y contienen la lógica de relaciones y scopes.
-   **Vistas**: Inicialmente implementadas en Blade para la maquetación híbrida.
-   **Controladores**: Orquestan las peticiones y delegan el procesamiento de datos.

### 2. Eloquent ORM
Se aprovechó la potencia de **Eloquent** para realizar consultas complejas sin escribir SQL puro.

-   **Eager Loading**: Uso de `with()` para evitar el problema de consultas N+1 al cargar servicios con sus categorías.
-   **Accessors y Mutators**: Formateo automático de datos (específicamente fechas y rutas de imágenes) antes de enviarlos a la vista.

### 3. Inyección de Dependencias
Se utilizó el contenedor de servicios de Laravel para inyectar dependencias en controladores, facilitando la testabilidad y el desacoplamiento del código.
