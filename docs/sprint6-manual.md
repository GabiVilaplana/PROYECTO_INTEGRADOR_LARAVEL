# Manual Técnico y Guía de Mantenimiento

**Objetivo:** Proporcionar la documentación necesaria para que otros desarrolladores o administradores puedan mantener el sistema.

## Contenido Técnico:

### 1. Diagrama de Arquitectura
Descripción del flujo de datos: `Usuario -> Nginx -> Vue SPA -> Laravel API -> MySQL/Redis`.

### 2. Guía de Instalación Rápida
Pasos para clonar y levantar el sistema en un nuevo servidor:

1.  Configuración del archivo `.env`.
2.  Levantamiento con `docker-compose up -d`.
3.  Preparación inicial con `php artisan setup`.

### 3. Tareas de Mantenimiento

-   **Backups**: Procedimiento de exportación de la base de datos.
-   **Logs**: Ubicación y análisis de errores mediante los logs de Laravel y Docker.
