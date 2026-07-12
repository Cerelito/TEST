# G1 Construcciones — Sitio Web

Sitio web de una sola página (landing) para **G1 Construcciones**, edición
20° Aniversario. Diseño estilo *liquid glass* (glassmorphism) sobre fondo
oscuro con acentos en verde lima y cian de la marca.

Diseñado y desarrollado por **[apotemaone.com](https://apotemaone.com)**.

## Cómo verlo

Es un sitio 100% estático (HTML, CSS y JavaScript). No necesita instalación
ni servidor especial.

**Opción rápida:** abre `index.html` con doble clic en tu navegador.

**Opción recomendada (para que carguen bien las imágenes y tipografías):**
levanta un servidor local desde esta carpeta:

```bash
# Con Python (ya viene en Mac/Linux)
python3 -m http.server 8000
# luego abre http://localhost:8000

# O con Node
npx serve
```

## Cómo publicarlo en internet

Al ser estático, se puede subir tal cual a cualquier hosting:

- **Netlify / Vercel / Cloudflare Pages:** arrastra la carpeta y listo.
- **GitHub Pages:** sube la carpeta a un repo y actívalo.
- **Hosting tradicional (cPanel):** copia todos los archivos a `public_html`.

## Estructura

```
g1-construcciones/
├── index.html          Estructura y contenido de la página
├── styles.css          Diseño, material glass, animaciones, responsive
├── script.js           Interacciones (menú, pestañas, reveals, contadores, tilt)
└── assets/
    ├── img/            Fotografías de obra y de la marca
    └── favicon/        Íconos de pestaña (logo G1) y og:image
```

## Cómo editar el contenido

- **Textos, teléfonos, correo, dirección:** en `index.html`.
- **Colores de marca:** en `styles.css`, al inicio, en el bloque `:root`
  (`--lime`, `--cyan`, `--ink`, etc.).
- **Fotos:** reemplaza los archivos dentro de `assets/img/` conservando el
  mismo nombre, o actualiza las rutas `src=""` en `index.html`.

## Secciones

1. **Inicio (Hero)** — presentación y estadísticas clave.
2. **Conócenos** — historia, misión y visión.
3. **Valores G1** — los 4 valores y los 2 compromisos.
4. **Servicios** — segmentados por fase: Habitacional, Industrial, Mega Obras.
5. **Resultados** — 20 años en números (contadores animados).
6. **Contacto** — teléfono, correo y dirección.

## Notas técnicas

- Responsivo verificado de 320px (celulares pequeños) a 2560px.
- Respeta la preferencia de *movimiento reducido* del sistema (accesibilidad).
- Sin dependencias externas de JavaScript: carga rápido y es fácil de mantener.
- Tipografías (Space Grotesk + Inter) se cargan desde Google Fonts; con
  conexión a internet se ven idénticas, sin ella el navegador usa una
  alternativa del sistema.
