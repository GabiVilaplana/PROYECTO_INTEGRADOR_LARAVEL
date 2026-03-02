# 🛡️ Gestión de Roles y Permisos (ACL)

!!! info "Control de Acceso de Grado Empresarial"
    La seguridad en TaskLink se basa en el principio de menor privilegio. Solo los usuarios autorizados pueden realizar acciones críticas, garantizando que el sistema sea seguro tanto a nivel de API como de interfaz de usuario.

---

## 🏗️ 1. Backend: El Muro de Seguridad (Laravel)

El servidor Laravel es el juez final de lo que se puede y no se puede hacer. He implementado un sistema robusto mediante:

- **Laravel Policies:** Cada modelo (Servicio, Reserva, etc.) tiene su propia "política de seguridad". Por ejemplo, un proveedor solo puede editar sus propios servicios, nunca los de otro usuario.
- **Middlewares de Rol:** Hemos creado interceptores en las rutas (ej. `CheckAdmin`) que bloquean cualquier petición API si el usuario no tiene el rango necesario en la base de datos.
- **Validación basada en Datos:** No confiamos en lo que dice el frontend; cada acción se valida comprobando la relación real entre el usuario autenticado y el recurso solicitado.

---

## 🎮 2. Frontend: Una Interfaz Inteligente (Vue)

Para ofrecer una experiencia limpia, la SPA de Vue adapta su comportamiento dinámicamente:

!!! abstract "Control de la Experiencia de Usuario"
_ **Navigation Guards:** El enrutador de Vue verifica el rol del usuario antes de cargar una vista. Si un cliente intenta entrar manualmente a `/admin`, el sistema le redirige instantáneamente al dashboard principal.
_ **Directivas v-if de Rango:** Los botones sensibles (Borrar, Editar, Panel de Control) solo se renderizan en el DOM si el `usuarioStore` confirma que el usuario tiene el permiso necesario. \* **Protección de Componentes:** Los componentes críticos comprueban internamente el rol antes de montarse, añadiendo una segunda capa de seguridad visual.

---

## 📊 3. Matriz de Permisos

| Acción                  | Cliente | Proveedor | Administrador |
| :---------------------- | :-----: | :-------: | :-----------: |
| Reservar Servicios      |   ✅    |    ✅     |      ✅       |
| Crear Servicios         |   ❌    |    ✅     |      ✅       |
| Panel de Administración |   ❌    |    ❌     |      ✅       |
| Gestión de Usuarios     |   ❌    |    ❌     |      ✅       |

---

!!! success "Seguridad Extremo a Extremo"
    Al combinar validación forzosa en el servidor con una UI reactiva y protegida, TaskLink ofrece un entorno seguro y profesional para todos sus usuarios.
