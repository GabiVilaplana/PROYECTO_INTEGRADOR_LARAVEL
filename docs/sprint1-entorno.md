# Configuración del Entorno y Evolución del Proyecto

**Objetivo:** Establecer una infraestructura técnica robusta y una identidad visual coherente que facilite el desarrollo colaborativo y asegure un despliegue escalable.

---

## 🏗️ 1. Evolución de la Marca: TaskLink

La identidad de nuestro proyecto no fue algo estático; surgió orgánicamente durante el desarrollo del primer sprint. A medida que la Single Page Application (SPA) tomaba forma, comprendimos la necesidad de una marca que comunicara nuestra propuesta de valor.

- **El Nombre:** **'TaskLink'** fue seleccionado por su capacidad de sintetizar nuestra misión.
    - _Task_ (Tarea): Representa el núcleo del servicio, el trabajo profesional que se ofrece.
    - _Link_ (Enlace): Simboliza la conexión humana y técnica entre el profesional y su cliente.
- **Identidad Visual:** El logo fue diseñado para ser minimalista y moderno, asegurando una presencia clara tanto en resoluciones de escritorio como en interfaces móviles. Se ha estandarizado su tamaño y visualización mediante CSS personalizado para mantener la consistencia en toda la documentación.

---

## 🐳 2. Entorno de Desarrollo Local (Docker)

Para garantizar que todos los desarrolladores trabajen en un entorno idéntico y evitar el clásico "en mi máquina funciona", implementamos una arquitectura basada en **Docker**.

### Arquitectura de Microservicios

Utilizamos `docker-compose` para orquestar los siguientes contenedores interconectados:

| Servicio       | Tecnología | Propósito                                                                  |
| :------------- | :--------- | :------------------------------------------------------------------------- |
| **Web Server** | Nginx      | Gestión de peticiones HTTP y servicio de activos estáticos.                |
| **App Engine** | PHP-FPM    | Procesamiento de la lógica de Laravel y ejecución de scripts.              |
| **App Visual** | Vue.js     | Generación de la Single Page Application (SPA) con Vue.js.              |
| **Database**   | MySQL      | Almacenamiento persistente de datos de usuarios, servicios y reseñas.      |
| **Cache/Bus**  | Redis      | Optimización de rendimiento mediante caché y gestión de colas de mensajes. |

### Configuración y Seguridad

La gestión de la configuración se centraliza en el archivo `.env`. Este archivo (que no se sube al repositorio por seguridad) permite definir:

- Claves secretas de la aplicación.
- Credenciales de acceso a la base de datos.
- Puertos de exposición local para evitar conflictos con otros proyectos.

---

## 🌐 3. Infraestructura Remota y CI/CD

Desde las etapas iniciales, el proyecto ha contado con una vertiente remota para validar el comportamiento en escenarios reales.

- **Pruebas de Integración:** La configuración de un entorno remoto nos permite asegurar que el código es funcional fuera del entorno controlado del desarrollador local.
- **GitHub Pages:** La documentación técnica que estás leyendo se despliega de forma automatizada, garantizando que los cambios en los manuales sean visibles de inmediato para todo el equipo.
- **Estrategia de Git:** Adoptamos un flujo de trabajo basado en ramas para que nosotros puedamos desarrollar funcionalidades de forma aislada (Backend/Frontend) y fusionarlas tras superar ciclos de revisión.

---

> [!TIP]
> **Consistencia técnica:** Gracias a Docker, la transición entre el desarrollo local y el despliegue remoto es casi imperceptible, minimizando errores de configuración en producción.
