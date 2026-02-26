# Importación de Datos y Estructura de la Base de Datos

**Objetivo:** Establecer la estructura de persistencia y poblar el sistema con datos de prueba realistas.

## Implementación Técnica:

### 1. Migraciones de Base de Datos
Se utilizaron las **Migrations** de Laravel para definir el esquema de la base de datos de forma programática.

-   **Campos**: Se definieron tipos de datos adecuados (enums para estados, claves foráneas para integridad).
-   **Índices**: Se añadieron índices en claves foráneas para optimizar el rendimiento de las consultas.

### 2. Poblamiento mediante Seeders y Factories
Para el desarrollo y pruebas, se crearon **Seeders** y **Factories**:

-   **UsuarioSeeder**: Crea perfiles de Administrador, Profesional y Cliente.
-   **ServicioSeeder**: Distribuye servicios realistas entre los distintos profesionales.
-   **Faker PHP**: Se utilizó la librería Faker para generar nombres, correos y descripciones aleatorias, permitiendo probar la interfaz con gran volumen de datos.

### 3. Ejecución del Proceso
Se centralizó la lógica en `DatabaseSeeder.php`, permitiendo resetear y poblar la base de datos con un comando:
```bash
php artisan migrate:fresh --seed
```
