# 🏗️ Estructura y Flujo de Datos

!!! info "Objetivo del Sistema"
    Establecer una arquitectura de persistencia sólida y garantizar un flujo de datos dinámico. En TaskLink, una vez inicializado el sistema, los **usuarios registrados** son los protagonistas que autogestionan la mayoría de las acciones y servicios.

---

## 👥 1. Filosofía de Contenido: Autogestión Profesional

En TaskLink, la integridad y actualización de la información es una responsabilidad compartida, centrada en el valor que aporta el usuario:

<div style="display: flex; align-items: center; gap: 20px;">
  <div style="flex: 1;">
    <ul>
      <li><strong>Responsabilidad del Usuario:</strong> Los profesionales gestionan íntegramente sus perfiles y servicios. TaskLink actúa como el facilitador técnico y vitrina de su talento.</li>
      <li><strong>Validación Técnica:</strong> Se realizó una importación inicial controlada para validar la arquitectura, asegurar los flujos de visualización y optimizar el rendimiento.</li>
    </ul>
  </div>
  <div style="flex: 1; text-align: right;">
    <img src="../imagenes/importacion.png" alt="importacio" style="border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); max-width: 100%;">
  </div>
</div>

---

## ⚙️ 2. Implementación Técnica (Backend)

Explora los pilares de nuestra base de datos mediante las herramientas nativas de Laravel:

### 📂 2.1. Migraciones de Base de Datos

!!! abstract "Arquitectura del Esquema"
    Utilizamos **Migrations** para definir el esquema de forma programática y versionada, lo que garantiza la integridad y portabilidad del sistema.

    *   **Integridad:** Definición precisa de tipos de datos y restricciones de claves foráneas.
    *   **Rendimiento:** Planificación estratégica de índices para agilizar búsquedas en tablas críticas como usuarios, servicios y zonas.

<br>

### 🧪 2.2. Poblamiento (Seeders & Factories)

!!! info "Generación Masiva de Datos para Pruebas"
    Para simular un entorno real y estresar la plataforma durante el desarrollo, empleamos herramientas de generación masiva con datos coherentes:

    <div style="display: flex; align-items: center; gap: 25px; margin-top: 20px;">
      <div style="flex: 1.5;">
        <ul>
          <li><strong>Seeders de Roles y Usuarios:</strong>
            <ul>
              <li>Configuración de roles base: <code>admin</code>, <code>usuario</code> (cliente) y <code>creadorServicio</code> (profesional que vende sus servicios).</li>
              <li>Creación de cuentas fijas para pruebas: <code>admin@admin.com</code>, <code>alexlopez@tasklink.com</code> y <code>gabivilaplana@tasklink.com</code>.</li>
            </ul>
          </li>
          <li><strong>Servicios por Geolocalización:</strong>
            <ul>
              <li>Pre-poblamiento de servicios reales como <em>Limpieza Integral</em> (Madrid), <em>Jardinería</em> (Barcelona), <em>Diseño Web</em> (Sevilla) y <em>Mecánica</em> (Zaragoza). Estos servicios son los que se muestran en el mapa de la pantalla de inicio.</li>
              <li>Esto valida el sistema de radios de acción y mapas desde el primer segundo.</li>
            </ul>
          </li>
          <li><strong>Factories & Faker:</strong> Generación dinámica de cientos de perfiles aleatorios para asegurar que el scroll infinito y los filtros de búsqueda funcionen con fluidez (UX). NO solamente perfiles, sino que que también se crean cientos de servicios aleatorios para validar la funcionalidad de los filtros de búsqueda.</li>
        </ul>
      </div>
      <div style="flex: 1; text-align: right;">
        <img src="../imagenes/Factory&Seeder.png" alt="Factory & Seeder" style="border-radius: 10px; box-shadow: 0 8px 16px rgba(0,0,0,0.15); max-width: 100%; border: 1px solid #eee;">
      </div>
    </div>

---

## 🚀 3. Ejecución del Ciclo de Vida

El control total del estado de la base de datos se gestiona mediante comandos de Artisan, permitiendo una transición fluida entre entornos:

### 🛠️ 3.1. Reconstrucción del Entorno (Clean Slate)

En la fase de desarrollo, utilizamos el comando de "reinicio total" para asegurar que los cambios en las migraciones se apliquen sin conflictos:

```bash
# Reseteo completo, reaplicación de migraciones y repoblamiento
php artisan migrate:fresh --seed
```

> [!TIP]
> También puedes usar el comando simplificado del **Makefile**: `make populate`.

### 🔄 3.2. Fases del Poblamiento (Seeding Pipeline)

El archivo `DatabaseSeeder.php` orquestra la construcción del entorno en tres fases lógicas para respetar las dependencias de claves foráneas:

1.  **Fase 1: Estructura y Reglas:**
    - `RolSeeder`: Define los permisos de nivel de sistema.
    - `ZonasSeeder` y `CategoriaSeeder`: Establecen el catálogo base de operaciones.
2.  **Fase 2: Identidad y Perfiles:**
    - `UsuarioSeeder`: Inyecta los administradores y usuarios de prueba.
    - `FaqSeeder`: Carga el centro de ayuda inicial.
3.  **Fase 3: Transaccional y Social:**
    - `ServicioSeeder`, `ReservaSeeder` y `PagoSeeder`: Generan la actividad económica.
    - `MensajeSeeder` y `ValoracionServicioSeeder`: Simulan la interacción entre usuarios.

!!! success "Flujo Orgánico y Escalabilidad"
    Esta arquitectura permite que, una vez superada la fase de pruebas/QA, simplemente ejecutemos las migraciones en producción (`php artisan migrate`) para empezar con un flujo **100% orgánico**, basado exclusivamente en la actividad real de los proveedores y clientes de TaskLink.
