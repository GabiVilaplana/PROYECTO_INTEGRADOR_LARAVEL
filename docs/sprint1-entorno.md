# Configuración del Entorno y Evolución del Proyecto

**Objetivo:** Establecer una base sólida para el desarrollo colaborativo y el despliegue.

## Implementación Técnica:

### 1. Evolución del Nombre
Se nos ocurrió a medida que trabajabamos en el primer sprint. La página iba cogiendo forma y tarde o temprano tendríamos que definir un nombre y un logo que actuase como marca de identidad de nuestro proyecto. Tras pensar un poco, **'TaskLink'** nos pareció muy indicado, ya que junta los dos conceptos que intentamos relacionar en nuestra labor; el trabajo, y conectar con personas.

### 2. Entorno de Desarrollo (Local)

Se configuró un entorno basado en **Docker** para garantizar la paridad entre desarrolladores.

- **Contenedores**: Se utilizaron servicios para PHP-FPM, Nginx, MySQL y Redis.
- **Variables de Entorno**: Gestión mediante el archivo `.env` para configurar claves de API, credenciales de base de datos y puertos.

### 3. Entorno Remoto

Se configuró un servidor remoto para pruebas de integración continua, asegurando que el código fuera funcional en un entorno real desde el inicio.
