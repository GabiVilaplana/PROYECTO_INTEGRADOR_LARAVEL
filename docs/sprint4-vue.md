# Arquitectura Frontend: SPA con Vue 3

**Objetivo:** Desarrollar una interfaz de usuario dinámica y fluida mediante una Single Page Application.

## Implementación Técnica:

### 1. Vue.js 3 y Options API / Composition API
Se implementó **Vue 3** como el núcleo del frontend, permitiendo una gestión reactiva de los datos y una división clara de la interfaz en componentes.

-   **Vite**: Se utilizó como bundler principal por su extrema velocidad en el desarrollo y su optimización en la compilación final.
-   **Componentización**: Se diseñó una biblioteca de componentes internos (inputs, botones, modales) para asegurar la reutilización y consistencia visual.

### 2. Vue Router (Navegación Móvil y SPA)
Se configuró **Vue Router** para gestionar los estados de navegación sin necesidad de recargas de página.

-   **Lazy Loading**: Se implementó la carga perezosa de componentes para optimizar el peso inicial de la aplicación.
-   **History Mode**: Configurado para que las URLs sean limpias y amigables para el usuario.

### 3. Comunicación con el Backend (Axios)
Se utilizó la librería **Axios** para realizar peticiones HTTP a la API de Laravel.

-   **Interceptores**: Configurados para gestionar de forma global los errores 401 (no autorizado) y 500 (error del servidor), redirigiendo al usuario al login si la sesión expira.
