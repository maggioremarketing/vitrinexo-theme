# Vitrinexo — Tema WordPress

## El proyecto

Vitrinexo es una plataforma B2B de directorio de servicios profesionales. Empresa independiente. **Nunca mencionar Maggiore Marketing**. **No restringir geográficamente** — visión global (habla hispana, Brasil, EEUU, Europa).

**Sitio:** https://vitrinexo.com  
**Par de repositorios:** `luismaggiore/vitrinexo-theme` (este) y `luismaggiore/vitrinexo-core`

## Reglas no negociables

- Siempre **Vitrinexo** — nunca "VitriNexo"
- Las piezas de la línea llevan **la marca madre adelante**, sin excepción y también en conversación:
  Vitrinexo 4Dinner, Vitrinexo Woman, Vitrinexo LGBTQ+, Vitrinexo Senior. Nunca "4Dinner" a secas.
  Lo mide `pruebas/pantalla/vocabulario.mjs` sobre el texto renderizado, no sobre el código: los
  identificadores (`vx_landing_4dinner`, `comunidad-card--woman`) siguen siendo cortos
- Programa: **Miembro Pionero / Miembros Pioneros**
- Siempre **"distintivo"** — nunca "badge"
- Sin guiones largos (—) en copy
- Español chileno: "tienes", "puedes" — nunca voseo

## Stack

- WordPress 7.0, Bootstrap 5.3.3, Tabler Icons 3.19.0
- Fuente: Switzer Variable
- Colores: `--color-primary: #00aeb8` (teal), navy `#1a2335`
- CSS en `assets/css/style.css` (usar CSS variables del sistema de diseño)

## Estructura del tema

```
vitrinexo-theme/
├── functions.php                ← enqueue de assets, condicionales por página
├── page.php                     ← template para páginas interiores (privacidad, términos)
│                                   Incluye nav, animación de red (network.js), footer
├── assets/
│   ├── css/style.css            ← sistema de diseño completo con CSS variables
│   ├── js/
│   │   ├── main.js
│   │   └── network.js           ← animación de puntos y líneas (canvas, fixed, z-index:0)
│   └── img/
│       ├── vitrinexo.svg        ← logo principal (dark, para fondo claro)
│       └── vitrinexo-email.png  ← logo para emails (PNG 144×52px, fondo transparente)
├── templates/
│   └── front-page.php           ← landing page principal
└── partials/
    ├── nav.php
    └── footer.php               ← incluye links a /privacidad/ y /terminos/
```

## Manual de marca (/marca)

El manual vive en el código, no en una página de WordPress: se sirve por regla de reescritura desde `inc/marca/ruta.php`, así que nadie puede editarlo desde el escritorio. Lleva `noindex`.

```
inc/marca/
├── ruta.php         ← la regla /marca, el noindex y los enqueue
├── tokens.php       ← lee el :root de style.css (la página es el espécimen)
├── criterios.php    ← las 3 partes y sus 28 criterios, con su estado
├── papeles.php      ← la capa semántica con el oficio de cada token
├── piezas.php       ← las piezas de render (bloque, muestra, celda, tabla)
├── contenido.php    ← la prosa de cada criterio
└── decisiones.php   ← qué se eligió, contra qué y con qué medición
templates/page-marca.php    ← la página
assets/css/marca.css        ← solo tokens semánticos, nunca primitivos
pruebas/                    ← la suite (no se sube al servidor)
```

**La regla del proyecto:** cada regla se escribe, el sistema la obedece, una prueba la mide, y la prueba se verifica rompiendo el código a propósito. Saltarse lo último deja una suite verde que no mide nada.

```bash
npm run verificar        # lee código y tokens, no abre nada
npm run accesibilidad    # mide la página publicada con Playwright
npm run avance           # qué criterio está escrito y cuál no (informa, no falla)
```

`npm run accesibilidad -- http://127.0.0.1:8899/marca` mide otra URL.

Los tokens nuevos del sistema de diseño (`--fs-*`, `--space-*`, `--color-primary-ink`) se agregaron con el manual y son aditivos: no cambian ninguna pantalla existente.

**El tracking sale siempre de un peldaño `--ls-*`, nunca escrito a mano.** Se elige por el tamaño al que se aplica: `--ls-hero` para el titular que crece con el ancho, luego `--ls-display`, `--ls-h2`, `--ls-h3`, `--ls-h4`, y `--ls-sobretitulo` / `--ls-sobretitulo-xs` para caja alta según sea de 12 px o más, o de 11 o menos. Ya no hay regla general sobre `h1, h2`: si un titular nuevo no declara el suyo, se queda en cero y la prueba de tipografía lo caza.

En el panel de WordPress el tracking sigue escrito a mano a propósito: wp-admin no carga `style.css`, así que ahí un `var(--ls-*)` no resolvería a nada.

## Deploy

Push a `main` → GitHub Actions → rsync SSH → Hostinger.

```yaml
# Secrets requeridos en el repo
SSH_HOST: 195.200.3.42
SSH_PORT: 65002
SSH_USERNAME: u969893599
SSH_PRIVATE_KEY: <clave Ed25519 privada>
SSH_TARGET: /home/u969893599/public_html/wp-content/themes/vitrinexo-theme
```

El workflow tiene **retry automático** (90s entre intentos). Autenticación por SSH key, nunca contraseña.

## Animación de red (network.js)

Se carga en `functions.php` condicionalmente:

```php
if ( is_front_page() || is_page( [ 'privacidad', 'terminos' ] ) ) {
    wp_enqueue_script( 'vitrinexo-network', $uri . '/assets/js/network.js', [], $ver, true );
}
```

El canvas se inyecta automáticamente con `position: fixed; z-index: 0`. El contenido de la página debe tener `position: relative; z-index: 1` para quedar encima.

## Páginas legales

`/privacidad/` y `/terminos/` usan el template `page.php`:
- Fondo blanco transparente sobre la animación de red
- Tipografía Vitrinexo: Switzer, navy, teal
- Sin mención a Maggiore ni restricción geográfica
- El nav y footer se incluyen igual que el resto del sitio

## Dominios

- `vitrinexo.com` — dominio principal
- `vitrinexo.cl` — dominio aparcado (alias completo, mismo contenido). Gestionado en hPanel → Dominios aparcados.

## Caché

**Sube la versión del tema en `style.css` cada vez que cambies un asset.** El `?ver=` sale de ahí, y sin cambiarlo el CDN sigue sirviendo el archivo viejo desde algunos nodos: `curl` puede traer el nuevo mientras el navegador recibe el anterior. Pasó con los tokens del manual de marca, y se ve como una variable CSS que existe en el archivo y llega vacía a la página.

**Las imágenes no llevan `?ver=` solas.** El `?ver=` lo agrega `wp_enqueue_*` a CSS y JS, pero un `<img src>` escrito en una plantilla tiene URL fija y el CDN nunca la suelta: el logotipo nuevo estuvo horas en el servidor mientras el navegador seguía recibiendo el viejo, con `curl` trayendo uno y Chrome otro. Por eso la URL del logotipo sale de `vx_logo_url()`, que le pega la versión del tema. Cualquier imagen que cambie necesita lo mismo.

Si aun así hay que purgar a mano: **hPanel → vitrinexo.com → Rendimiento → CDN → Vaciar caché**

LiteSpeed se purga automáticamente al hacer deploy.

## Pendientes

- Integración de pagos con Stripe
- Contenido de la sección 4Dinner (solo tiene header)
