# Diseño Responsivo y Tailwind CSS

**Objetivo:** Asegurar que la plataforma sea accesible y funcional en cualquier resolución de pantalla.

## Implementación Técnica:

### 1. Integración de Tailwind CSS
Se adoptó **Tailwind CSS** como el framework principal debido a su arquitectura basada en utilidades (*Utility-First*).

-   **Configuración**: Se optimizó `tailwind.config.js` para eliminar CSS innecesario en producción mediante Purge CSS.

### 2. Estructura Flexbox y Grid
Se evitaron los floats y diseños rígidos en favor de layouts modernos:

-   **Flexbox**: Utilizado para la alineación de elementos en barras de navegación y menús.
-   **CSS Grid**: Utilizado para la cuadrícula principal de servicios y perfiles de usuario.

### 3. Breakpoints Personalizados
Se utilizaron los prefijos de estado de Tailwind para adaptar la UI:

-   `sm:`: Adaptaciones para móviles grandes.
-   `md:`: Estructura para tablets.
-   `lg:`: Diseño final para pantallas de escritorio.
