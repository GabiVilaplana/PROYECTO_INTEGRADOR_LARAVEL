# Autenticación de Usuarios y Seguridad

**Objetivo:** Implementar un sistema de acceso seguro y flexible para los diferentes tipos de usuarios.

## Implementación Técnica:

### 1. Laravel Breeze & Sanctum
Se utilizó **Laravel Breeze** para la autenticación inicial, integrando **Laravel Sanctum** para gestionar tanto sesiones web como tokens de API.

-   **Registro Personalizado**: Se añadieron campos extra al flujo de registro para diferenciar entre proveedores de servicios y clientes.
-   **Middleware de Autenticación**: Todas las rutas privadas se protegieron mediante el middleware `auth`.

### 2. Seguridad de Credenciales

-   **Hashing**: Las contraseñas se encriptan siempre mediante **Bcrypt** antes de guardarse.
-   **Acceso Seguro**: Se implementaron mecanismos de protection contra ataques de fuerza bruta (Rate Limiting) nativos de Laravel.

### 3. Gestión de Sesiones
Se configuró el driver de sesión para utilizar archivos o base de datos, asegurando que el estado de login sea consistente.
