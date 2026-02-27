# Página Inicial, Formulario y Diccionario de Datos

**Objetivo:** Documentar la fase de prototipado inicial, la implementación del primer canal de comunicación (Formulario) y la arquitectura detallada de datos que sustenta a TaskLink.

---

## 🖥️ 1. Creación de la Página Inicial (SPA)

La primera fase del proyecto se centró en establecer una presencia digital fluida. Aunque TaskLink es una **Single Page Application (SPA)** compleja, su cimentación comenzó con una estructura de navegación intuitiva que priorizaba el acceso rápido a los servicios.

- **Tecnología:** Uso de Vue.js con Vite para una reactividad instantánea.
- **Enfoque UX:** Diseño limpio, centrado en el buscador de servicios y la propuesta de valor de la plataforma.
- **MkDocs:** Paralelamente, establecimos este sistema de documentación para asegurar que cada avance técnico quedara registrado para el equipo y los evaluadores.

---

## 📩 2. El Formulario de Contacto (MVP)

El formulario de contacto fue nuestra primera funcionalidad operativa "end-to-end". Sirvió para validar la comunicación básica entre el cliente y el sistema.

### Especificaciones Técnicas

- **Campos:** Nombre, Email, Asunto y Mensaje.
- **Validación del Lado del Cliente (JS):**
    - Comprobación de campos obligatorios.
    - Validación de formato de correo electrónico mediante expresiones regulares.
    - Prevención de envío de mensajes vacíos para optimizar el almacenamiento.
- **Persistencia:** Los datos se procesan y se almacenan en la tabla `mensajes` de la base de datos para su posterior gestión administrativa.

---

## 🗄️ 3. Diccionario de Datos (Arquitectura de Base de Datos)

A continuación, se detalla la estructura completa de la base de datos de TaskLink, organizada por módulos lógicos. Esta arquitectura asegura la integridad y escalabilidad del sistema.

### 👥 Módulo de Identidad y Acceso

#### Tabla: `rols`

| Campo           | Tipo           | Descripción                                   |
| :-------------- | :------------- | :-------------------------------------------- |
| **IDRol**       | BIGINT (PK)    | Identificador único del rol.                  |
| **Nombre**      | VARCHAR        | Nombre del rol (Admin, Profesional, Cliente). |
| **Descripcion** | VARCHAR (Null) | Detalle de las capacidades del rol.           |

#### Tabla: `usuarios`

| Campo         | Tipo             | Descripción                      |
| :------------ | :--------------- | :------------------------------- |
| **IDUsuario** | BIGINT (PK)      | Identificador único del usuario. |
| **Nombre**    | VARCHAR          | Nombre de pila.                  |
| **email**     | VARCHAR (Unique) | Correo electrónico (login).      |
| **password**  | VARCHAR          | Hash de la contraseña.           |
| **idRol**     | BIGINT (FK)      | Relación con la tabla `rols`.    |
| **Activo**    | BOOLEAN          | Estado de la cuenta.             |

---

### 💼 Módulo de Servicios y Catálogo

#### Tabla: `categorias`

| Campo           | Tipo           | Descripción                           |
| :-------------- | :------------- | :------------------------------------ |
| **IDCategoria** | BIGINT (PK)    | Identificador único de la categoría.  |
| **Nombre**      | VARCHAR        | Nombre (Carpintería, Limpieza, etc.). |
| **Imagen**      | VARCHAR (Null) | Icono o imagen representativa.        |

#### Tabla: `servicios`

| Campo           | Tipo          | Descripción                         |
| :-------------- | :------------ | :---------------------------------- |
| **IDServicio**  | BIGINT (PK)   | Identificador único del servicio.   |
| **Nombre**      | VARCHAR       | Título del servicio.                |
| **Precio**      | DECIMAL (8,2) | Coste del servicio.                 |
| **idCategoria** | BIGINT (FK)   | Relación con `categorias`.          |
| **idProveedor** | BIGINT (FK)   | Quién ofrece el servicio (usuario). |
| **idZona**      | BIGINT (FK)   | Área geográfica vinculada.          |

---

### 📍 Módulo Geográfico y Logístico

#### Tabla: `zonas`

| Campo         | Tipo        | Descripción                       |
| :------------ | :---------- | :-------------------------------- |
| **id**        | BIGINT (PK) | Identificador de zona.            |
| **nombre**    | VARCHAR     | Ciudad o región.                  |
| **lat / lng** | DECIMAL     | Coordenadas para geolocalización. |

#### Tabla: `servicio_disponibilidades`

| Campo                | Tipo        | Descripción               |
| :------------------- | :---------- | :------------------------ |
| **id**               | BIGINT (PK) | Identificador.            |
| **idServicio**       | BIGINT (FK) | Servicio vinculado.       |
| **DiaSemana**        | ENUM        | Lunes, Martes, etc.       |
| **HoraInicio / Fin** | TIME        | Franja de disponibilidad. |

---

### 🗓️ Módulo de Negocio y Feedback

#### Tabla: `reservas`

| Campo            | Tipo        | Descripción                       |
| :--------------- | :---------- | :-------------------------------- |
| **IDReserva**    | BIGINT (PK) | Identificador de la reserva.      |
| **idUsuario**    | BIGINT (FK) | Cliente que reserva.              |
| **FechaReserva** | DATETIME    | Momento exacto de la cita.        |
| **Estado**       | VARCHAR     | Pendiente, Confirmada, Cancelada. |

#### Tabla: `valoracion_servicios`

| Campo            | Tipo        | Descripción                    |
| :--------------- | :---------- | :----------------------------- |
| **IDValoracion** | BIGINT (PK) | Identificador de la reseña.    |
| **Puntuacion**   | INT         | Escala de 1 a 5 estrellas.     |
| **Comentario**   | TEXT        | Opinión detallada del cliente. |

---

### 💬 Módulo de Soporte y Comunicación

#### Tabla: `mensajes`

| Campo                  | Tipo           | Descripción                |
| :--------------------- | :------------- | :------------------------- |
| **IDMensaje**          | BIGINT (PK)    | Identificador del mensaje. |
| **Asunto / Contenido** | VARCHAR / TEXT | Datos del mensaje enviado. |
| **Estado**             | VARCHAR        | Leído, Respondido, Nuevo.  |

#### Tabla: `faqs`

| Campo                    | Tipo        | Descripción                     |
| :----------------------- | :---------- | :------------------------------ |
| **id**                   | BIGINT (PK) | Identificador de la pregunta.   |
| **Pregunta / Respuesta** | TEXT        | Contenidos del centro de ayuda. |

---

> [!TIP]
> **Integridad Referencial:** Toda la base de datos utiliza claves foráneas con eliminación en cascada para evitar datos huérfanos y asegurar que la eliminación de un usuario o servicio limpie correctamente sus registros asociados (fotos, valoraciones, etc.).
