# Planificación Temporal y Cronograma

**Objetivo:** Definir la hoja de ruta cronológica del proyecto para cumplir con los plazos de entrega.

## Implementación Técnica:

### 1. Cronograma Inicial (GanttProject)
Al comienzo del proyecto, utilizamos **GanttProject** para estimar la duración de las fases principales y las dependencias entre hitos críticos.

-   **Hitos definidos**: Diseño de base de datos, maquetación inicial, migración a Laravel y despliegue final.
-   **Gestión de Dependencias**: Identificación de tareas que bloqueaban otras (e.g., la API debía estar lista antes que el frontend de Vue).

### 2. Transición a Gestión Ágil
Conforme el proyecto avanzó hacia un modelo más iterativo, decidimos prescindir del diagrama de Gantt estático a favor de la flexibilidad que ofrece **GitHub Projects**.

-   **Razón del cambio**: Los diagramas de Gantt se volvían difíciles de mantener ante cambios rápidos en los requerimientos.
-   **Resultado**: Centralización de toda la gestión temporal en hitos (*milestones*) de GitHub.
