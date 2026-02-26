# Copias de seguridad y despliegue remoto

**Objetivo:** Garantizar la integridad de los datos y la disponibilidad fuera del entorno local.

## Implementación Técnica:

### 1. Gestión de Backups
Se configuraron scripts para realizar exportaciones de la base de datos MySQL (mysqldump).

- **Persistencia**: En el archivo `docker-compose.yml`, se utilizó un volumen persistente para los datos de MySQL:
  ```yaml
  volumes:

    - db_data:/var/lib/mysql
  ```
  Esto garantiza que los datos se mantengan incluso si se detienen o eliminan los contenedores de Docker.

### 2. Despliegue Inicial
Se configuraron las variables de entorno en el archivo `.env` del servidor remoto para asegurar una conexión segura con la base de datos y la correcta generación de las URLs de la aplicación.
