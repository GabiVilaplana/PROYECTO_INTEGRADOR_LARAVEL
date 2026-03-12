# 🔑 Autenticación API y Gestión de Estado

!!! info "Seguridad de Grado Bancario"
    Sincronizar el estado del usuario entre el frontend (Vue) y el backend (Laravel) de forma segura es vital. Para ello, implementamos una arquitectura **Stateful** basada en sesiones y cookies, optimizada para aplicaciones SPA modernas.

---

## 🛡️ 1. Laravel Sanctum: Seguridad invisible

A diferencia de los tokens JWT tradicionales que pueden ser interceptados más fácilmente, utilizamos **Laravel Sanctum** para una gestión basada en cookies seguras:

1.  **Protección CSRF:** Antes de cualquier intento de login, el frontend solicita un "token de miga de pan" (`/sanctum/csrf-cookie`). Esto garantiza que solo nuestra aplicación pueda enviar comandos al servidor.
2.  **Stateful Requests:** Hemos configurado nuestros dominios en `cors.php` y `sanctum.php` para permitir que el navegador comparta las cookies de sesión de forma persistente, eliminando la necesidad de adjuntar tokens manualmente en cada cabecera.

---

## 🍍 2. Gestión de Estado Global con Pinia

Para que la aplicación sepa _quién_ está navegando en todo momento, utilizamos **Pinia**, el almacén de estados oficial de Vue:

!!! abstract "Flujo de Datos del Usuario"p
- **Persistencia Reactiva:** Almacenamos el objeto `user` y su rol en un store centralizado accesible desde cualquier componente.
- **Detección de Sesión:** Al arrancar la aplicación (`App.vue`), realizamos una petición silenciosa a `/api/user`. Si hay éxito, recuperamos la sesión; si no, mantenemos al usuario como "invitado". \* **UI Dinámica:** Gracias a esta sincronización, opciones como "Subir Servicio" o "Mis Reservas" aparecen o desaparecen en tiempo real según el estado del login.

---

## 📑 3. Flujo de Login y Registro Seguro

Los formularios de acceso no son simples campos de texto; son sistemas validados en dos pasos:

- **Validación Local:** Yup y Vee-Validate comprueban que el formato sea correcto antes de enviar nada.
- **Callback de API:** Si Laravel detecta un error (credenciales incorrectas o email duplicado), los mensajes de error bajan de forma estandarizada y se muestran directamente sobre el campo afectado, mejorando radicalmente la UX.

---

!!! success "Arquitectura Robusta"
    Este diseño asegura que TaskLink sea invulnerable a los ataques web más comunes, manteniendo siempre la fluidez que el usuario espera de una SPA.
