# Gestión de Roles y Permisos (ACL)

**Objetivo:** Garantizar que los usuarios solo accedan a las funcionalidades permitidas según su perfil.

## Implementación Técnica:

### 1. Modelo de Autorización de Laravel
En el backend, se implementó un sistema de control de acceso:

-   **Policies**: Se definieron reglas específicas (ej --> solo el creador de un servicio puede editarlo).
-   **Gate**: Se utilizaron para acciones puntuales que no dependen directamente de un modelo.

### 2. Middlewares de Ruta
Se crearon middlewares personalizados (ej --> `CheckAdmin`, `CheckProvider`) para bloquear peticiones a nivel de API si el usuario no cumple los requisitos.

### 3. Control de UI en Vue
En el frontend, se implementó una lógica de visualización basada en el objeto `user` recibido de la API:

-   **Directivas v-if**: Se ocultan botones de edición o paneles de administración según el rol.
-   **Navigation Guards**: El enrutador de Vue verifica el rol del usuario antes de resolver una ruta, evitando que un cliente acceda manualmente a la URL del panel de administración.
