# Pruebas y documentación del backend

**Objetivo:** Asegurar la calidad del código y facilitar el mantenimiento.

## Implementación Técnica:

### 1. Pruebas Automáticas (PHPUnit)
Se implementaron pruebas unitarias y de integración utilizando **PHPUnit**, el framework de pruebas nativo de Laravel.

- Se testearon los controladores de la API para verificar que los endpoints devolvieran los códigos de estado (200, 201, 404, etc.) y estructuras JSON correctas.
- Comando de ejecución:
  ```bash
  php artisan test
  ```

### 2. Documentación de Código
Se siguió el estándar de comentarios **PHPDoc** en todos los modelos y controladores para facilitar la comprensión del código mediante VS Code u otras herramientas de autocompletado.
