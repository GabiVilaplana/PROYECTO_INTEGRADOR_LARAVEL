# Dockerización y CI/CD

**Objetivo:** Estandarizar el entorno para garantizar que "funciona en mi máquina" y en producción.

## Implementación Técnica:

### 1. Orquestación con Docker Compose
Se orquestaron múltiples contenedores para cubrir todas las necesidades del proyecto:

- **app**: Backend Laravel (PHP-FPM).
- **web**: Servidor Nginx para servir la web y la API.
- **db**: Base de datos MySQL con volúmenes persistentes.
- **redis**: Para la gestión de colas y caché.
- **vite**: Para la compilación en tiempo real del frontend.
- **n8n**: Plataforma de automatización integrada.

### 2. CI/CD inicial
Se definieron scripts en `composer.json` y `package.json` para automatizar las tareas de despliegue, preparación de la base de datos y compilación de assets.
