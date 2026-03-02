# ⚡ Arquitectura Frontend: SPA con Vue 3

!!! info "Experiencia de Usuario Fluida"
El frontend de TaskLink está diseñado como una **Single Page Application (SPA)** de alto rendimiento, permitiendo una navegación instantánea sin recargas de página y una interactividad suave que imita una aplicación nativa.

---

## 🚀 1. Núcleo Tecnológico y Rendimiento

Hemos seleccionado las herramientas más modernas para garantizar una base sólida y un desarrollo ágil:

- **Vue.js 3:** Motor principal que gestiona la reactividad de los datos y el ciclo de vida de los componentes mediante la **Composition API**.
- **Vite:** Actúa como nuestro bundler de nueva generación. Gracias a su servidor de desarrollo basado en ESM nativo, logramos tiempos de compilación casi instantáneos y un empaquetado final extremadamente optimizado.
- **Componentización Profunda:** La interfaz se divide en piezas reutilizables (inputs, botones, carruseles de servicios), lo que garantiza la consistencia visual en toda la plataforma y facilita el mantenimiento.

---

## 🗺️ 2. Navegación Avanzada (Vue Router)

La gestión de las rutas es fundamental para la experiencia SPA:

!!! abstract "Gestión de Estado de Navegación"
_ **Lazy Loading:** Las vistas pesadas (como el Dashboard o la edición de Perfil) solo se cargan cuando el usuario navega a ellas, reduciendo el peso del bundle inicial hasta en un 40%.
_ **History Mode:** Configurado para utilizar URLs limpias (ej. `/servicios/12`) sin el carácter `#`, mejorando tanto el SEO como la estética de compartir enlaces. \* **Navigation Guards:** Protegemos las rutas sensibles verificando el estado del usuario antes de permitir el acceso a áreas restringidas.

---

## 🔌 3. Comunicación con el Backend (Axios)

Para el consumo de la API REST de Laravel, utilizamos **Axios** con una configuración centralizada:

- **Interceptores Globales:** Hemos implementado interceptores que capturan automáticamente los errores de red. Si la API devuelve un error `401 Unauthorized` (sesión expirada), el sistema redirige al usuario al login de forma desatendida.
- **Headers de Seguridad:** Cada petición incluye automáticamente las credenciales necesarias, garantizando una comunicación fluida y protegida mediante cookies de estado.

---

!!! success "Interfaz Reactiva y Escalable"
Esta arquitectura separa completamente la lógica de negocio de la visualización, permitiendo a TaskLink evolucionar y crecer en complejidad sin sacrificar la velocidad de carga.
