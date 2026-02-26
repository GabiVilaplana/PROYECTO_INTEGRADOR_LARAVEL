# Integración OAuth2 y Login Social

**Objetivo:** Simplificar el proceso de registro permitiendo el acceso mediante cuentas de terceros.

## Implementación Técnica:

### 1. Laravel Socialite
Se integró **Laravel Socialite** para abstraer la complejidad de la comunicación con los protocolos OAuth2 de los proveedores.

-   **Configuración por Proveedor**: Se crearon credenciales (`Client ID` y `Client Secret`) en GitHub y Google.
-   **Callbacks de Seguridad**: Se definieron rutas de retorno validadas para procesar la información del usuario tras la autorización externa.

### 2. Sincronización de Cuentas
Al iniciar sesión con una red social, el sistema:

1.  Verifica si el email ya existe en la base de datos local.
2.  Si existe, vincula la cuenta social al usuario actual.
3.  Si no existe, crea un nuevo usuario con los datos proporcionados por el proveedor.
