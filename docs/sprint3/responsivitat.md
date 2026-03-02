# 📱 Diseño Responsivo y Estrategia Adaptativa

!!! info "Ecosistema Frontend Dual"
TaskLink utiliza un enfoque híbrido para garantizar la mejor experiencia de usuario en cada parte del sistema. Combinamos la potencia de **Tailwind CSS** para las páginas base con la robustez de **Bootstrap** para la aplicación central (SPA).

---

## 🎨 1. Landings y Páginas Base (Laravel + Tailwind)

Para las páginas de aterrizaje, login y registros iniciales, utilizamos **Tailwind CSS 4.0**, permitiendo un diseño extremadamente ligero y personalizable.

### Estrategia Mobile-First

Construimos pensando primero en el móvil y escalamos usando breakpoints nativos:

| Breakpoint | Pantalla    | Aplicación en TaskLink                                       |
| :--------- | :---------- | :----------------------------------------------------------- |
| `sm:`      | Móvil Large | Ajustes de padding y alineaciones de cabecera.               |
| `md:`      | Tablet      | Transformación de bloques apilados a rejilla simple.         |
| `lg:`      | Escritorio  | Layouts complejos con `flex-row` y contenedores `max-w-4xl`. |

---

## ⚡ 2. Aplicación Central (Vue SPA + Bootstrap)

La SPA de TaskLink se apoya en **Bootstrap 5** y **Scoped CSS** para gestionar una interfaz rica en interacciones y altamente dinámica.

!!! abstract "Técnicas de Adaptación en Vue"
_ **Bootstrap Containers:** Utilizamos el sistema de rejilla de Bootstrap para la estructura general de las vistas, asegurando un comportamiento predecible en todos los navegadores.
_ **Custom Media Queries:** En componentes críticos como `PaginaPrincipalView.vue`, implementamos consultas de medios específicas (ej. `@media (max-width: 950px)`) para ocultar controles de carrusel y optimizar el scroll táctil en dispositivos móviles. \* **Escalado de Componentes:** Los elementos clave (Categorías, Cards de Servicio) adaptan sus dimensiones (ancho/alto) dinámicamente para maximizar el área visible.

---

## � 3. Layouts Dinámicos: Flexbox y Grid

Independientemente del framework, la base técnica se apoya en los estándares más modernos:

- **Flexbox:** Utilizado extensivamente en carruseles y menús para permitir que los elementos "fluyan" lateralmente con scroll horizontal en móviles.
- **CSS Grid:** Aplicado en la vista de administración y listados de servicios para organizar tarjetas de forma bidimensional eficiente.

---

> **🖼️ Sugerencia de Imagen:** Una comparativa dividida que muestre el **Welcome** (Tailwind) y el **Dashboard** (Vue/Bootstrap) reduciría cualquier duda sobre cómo el sistema mantiene una estética coherente a pesar de usar diferentes tecnologías subyacentes.

---

!!! success "Identidad Visual Unificada"
Gracias a esta combinación de tecnologías, TaskLink ofrece una interfaz que no solo escala técnicamente, sino que se siente fluida y "nativa" en cualquier dispositivo que utilice el usuario.
