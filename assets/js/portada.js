/* La mesa del hero de la portada.
 *
 * Dos cosas, las mismas de la propuesta: la mesa se arma al llegar (la tabla
 * crece, cada puesto entra desde su lado, los objetos aparecen y al final las
 * casillas) y, con mouse, se inclina siguiendo el puntero. Con movimiento
 * reducido no pasa ninguna de las dos: la mesa ya está armada y quieta. */
/* Los rubros que rotan en las casillas del hero y de Perfiles. Son ejemplos de
 * lo que una empresa ofrece o busca, no miembros reales. */
window.VX_RUBROS = [
  'Desarrollo de software', 'Dirección de arte', 'Producción musical',
  'Selección de personas', 'Marketing digital', 'Diseño de marca',
  'Asesoría legal', 'Contabilidad', 'Logística y transporte',
  'Producción audiovisual', 'Capacitación de equipos', 'Bienestar laboral',
  'Traducción y localización', 'Ingeniería y construcción', 'Estrategia de negocio',
  'Fotografía de producto', 'Producción de eventos', 'Catering para empresas',
  'Abogado laboral',
];

/* Los cargos de quien se sienta a la mesa, también de ejemplo. */
window.VX_CARGOS = [
  'Directora comercial', 'Socio fundador', 'Gerente general', 'Jefe de marketing',
  'Consultora independiente', 'Gerente de operaciones', 'Directora de finanzas',
  'Jefa de personas', 'Encargado de compras', 'Fundadora', 'Director creativo',
];

/* Cambia el texto de un grupo de casillas cada `cada` milisegundos, con un
 * fundido. Se detiene con el puntero encima, con la pestaña oculta y con
 * movimiento reducido: nadie tiene que leer algo que se le escapa. */
window.vxRotarRubros = function (casillas, contenedor, cada, lista = window.VX_RUBROS) {
  if (!casillas.length || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
  let pausa = false;
  contenedor.addEventListener('pointerenter', () => { pausa = true; });
  contenedor.addEventListener('pointerleave', () => { pausa = false; });

  const elegir = (evitar) => {
    let r;
    do { r = lista[Math.floor(Math.random() * lista.length)]; } while (evitar.includes(r));
    return r;
  };

  setInterval(() => {
    if (pausa || document.hidden) return;
    const usados = casillas.map((c) => c.textContent);
    casillas.forEach((c, i) => {
      const nuevo = elegir(usados);
      usados.push(nuevo);
      setTimeout(() => {
        c.classList.add('is-cambiando');
        setTimeout(() => { c.textContent = nuevo; c.classList.remove('is-cambiando'); }, 350);
      }, i * 250);
    });
  }, cada);
};

(function () {
  'use strict';

  const hero = document.getElementById('vxMesaHero');
  if (!hero) return;

  window.vxRotarRubros([...hero.querySelectorAll('[data-vx-rubro]')], hero, 3500);
  window.vxRotarRubros([...hero.querySelectorAll('[data-vx-cargo]')], hero, 3500, window.VX_CARGOS);

  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  const ease = 'cubic-bezier(.2,.8,.2,1)';
  const base = { easing: ease, fill: 'backwards' };
  const mesa = hero.querySelector('.vx-mesa');

  if (mesa && typeof mesa.animate === 'function') {
    hero.querySelector('.vx-mesa-hero__fondo')?.animate(
      [{ transform: 'scale(.92)', opacity: 0 }, { transform: 'none', opacity: 1 }],
      { ...base, duration: 1000 }
    );

    mesa.querySelector('.vx-mesa__tabla')?.animate(
      [{ transform: 'scale(.6)', opacity: 0 }, { transform: 'none', opacity: 1 }],
      { ...base, duration: 700, delay: 200 }
    );

    // Cada puesto entra desde su lado de la mesa: arriba, derecha, abajo, izquierda.
    const desde = ['translateY(-40px)', 'translateX(40px)', 'translateY(40px)', 'translateX(-40px)'];
    mesa.querySelectorAll('.vx-mesa__puesto, .vx-mesa__libre').forEach((el, i) => {
      el.animate(
        [{ transform: desde[i % 4], opacity: 0 }, { transform: 'none', opacity: 1 }],
        { ...base, duration: 800, delay: 450 + i * 120 }
      );
    });

    mesa.querySelectorAll('i').forEach((el, i) => {
      el.animate(
        [{ opacity: 0, scale: .4 }, { opacity: 1, scale: 1 }],
        { ...base, duration: 500, delay: 650 + i * 60 }
      );
    });

    hero.querySelectorAll('.vx-casilla').forEach((el, i) => {
      el.animate(
        [{ translate: '0 12px', opacity: 0 }, { translate: '0 0', opacity: 1 }],
        { ...base, duration: 700, delay: 1150 + i * 140 }
      );
    });
  }

  // La inclinación: solo con mouse y con la mesa al lado del texto.
  const inclina = document.getElementById('vxMesaInclina');
  const seccion = hero.closest('section');
  if (!inclina || !seccion || !window.matchMedia('(pointer: fine) and (min-width: 960px)').matches) return;

  let cuadro = 0;
  seccion.addEventListener('pointermove', (e) => {
    cancelAnimationFrame(cuadro);
    cuadro = requestAnimationFrame(() => {
      const r = hero.getBoundingClientRect();
      const x = Math.max(-0.6, Math.min(0.6, (e.clientX - r.left) / r.width - 0.5));
      const y = Math.max(-0.6, Math.min(0.6, (e.clientY - r.top) / r.height - 0.5));
      inclina.style.transform = `rotateY(${x * 16}deg) rotateX(${-y * 14}deg)`;
    });
  });
  seccion.addEventListener('pointerleave', () => {
    cancelAnimationFrame(cuadro);
    inclina.style.transform = '';
  });
})();

/* Perfiles: rostros armados con pedazos de varias personas, como en la
 * propuesta. Cada pedazo es el mismo retrato del sistema recortado en una
 * casilla; juntos dicen «detrás de un nexo hay muchas personas». Los colores
 * se leen del :root, no se escriben acá. */
(function () {
  'use strict';

  const caja = document.getElementById('vxCaras');
  if (!caja) return;

  const raiz = getComputedStyle(document.documentElement);
  const tok = (n) => raiz.getPropertyValue('--' + n).trim();
  const C = {
    ink: tok('tinta'), coral: tok('coral'), lav: tok('lavanda'), rosa: tok('rosa'),
    mant: tok('mantequilla'), cel: tok('celeste'), sal: tok('salvia'),
    terra: tok('terracota'), blanco: tok('blanco'), hueso: tok('hueso'),
  };
  if (!C.ink) return;

  // Seis personas: forma del pelo, lentes, ojos sonrientes, piel, pelo y ropa.
  const GENTE = [
    { h: 0, g: 1, e: 0, sk: C.lav,   ha: C.ink,   cl: C.coral },
    { h: 1, g: 0, e: 0, sk: C.rosa,  ha: C.terra, cl: C.cel },
    { h: 2, g: 0, e: 1, sk: C.mant,  ha: C.ink,   cl: C.sal },
    { h: 3, g: 1, e: 0, sk: C.cel,   ha: C.coral, cl: C.lav },
    { h: 4, g: 0, e: 0, sk: C.sal,   ha: C.ink,   cl: C.rosa },
    { h: 1, g: 1, e: 1, sk: C.terra, ha: C.ink,   cl: C.mant },
  ];
  const FONDOS = [C.hueso, C.lav, C.mant, C.blanco, C.rosa, C.cel, C.sal];
  // Las seis casillas en que se parte cada rostro (x, y, ancho, alto).
  const PIEZAS = [[0, 0, 92, 104], [92, 0, 108, 104], [0, 104, 108, 50], [108, 104, 92, 50], [0, 154, 100, 96], [100, 154, 100, 96]];

  function retrato(p) {
    const k = C.ink;
    let atras = '', adelante = '';
    if (p.h === 1) atras = `<path d="M46 112 Q40 38 100 38 Q160 38 154 112 L162 206 Q100 222 38 206Z" fill="${p.ha}"/>`;
    if (p.h === 3) atras = `<circle cx="100" cy="36" r="22" fill="${p.ha}"/>`;
    if (p.h === 0) adelante = `<path d="M52 104 Q48 44 100 44 Q152 44 148 104 Q140 72 104 70 Q74 70 52 104Z" fill="${p.ha}"/>`;
    if (p.h === 1) adelante = `<path d="M52 100 Q56 52 100 50 Q146 52 148 96 Q120 70 70 82Z" fill="${p.ha}"/>`;
    if (p.h === 2) adelante = [[62, 70, 18], [82, 54, 20], [106, 50, 21], [130, 60, 19], [144, 82, 16], [56, 92, 14]]
      .map((c) => `<circle cx="${c[0]}" cy="${c[1]}" r="${c[2]}" fill="${p.ha}"/>`).join('');
    if (p.h === 3) adelante = `<path d="M52 100 Q50 52 100 52 Q150 52 148 100 Q132 66 100 66 Q68 66 52 100Z" fill="${p.ha}"/>`;
    const ojos = p.e
      ? `<path d="M74 114 q8 -6 16 0 M110 114 q8 -6 16 0" stroke="${k}" stroke-width="3.5" fill="none" stroke-linecap="round"/>`
      : `<circle cx="82" cy="113" r="4.5" fill="${k}"/><circle cx="118" cy="113" r="4.5" fill="${k}"/>`;
    const lentes = p.g
      ? `<g fill="none" stroke="${k}" stroke-width="3.5"><rect x="64" y="100" width="32" height="25" rx="9"/><rect x="104" y="100" width="32" height="25" rx="9"/><path d="M96 111h8"/></g>`
      : '';
    return `${atras}<path d="M18 250 Q18 186 100 180 Q182 186 182 250Z" fill="${p.cl}"/>`
      + `<path d="M82 176 Q100 190 118 176 L116 150 H84Z" fill="${p.sk}"/>`
      + `<path d="M78 182 L100 206 L122 182" fill="none" stroke="${C.blanco}" stroke-width="5" opacity=".7"/>`
      + `<ellipse cx="100" cy="112" rx="48" ry="58" fill="${p.sk}"/>${adelante}${ojos}${lentes}`
      + `<path d="M101 118 Q95 134 104 136" fill="none" stroke="${k}" stroke-width="3" stroke-linecap="round" opacity=".7"/>`
      + `<path d="M86 150 Q100 159 114 150" fill="none" stroke="${k}" stroke-width="3.5" stroke-linecap="round"/>`;
  }

  const libre = `<g fill="none" stroke="${C.ink}" stroke-width="2.5" stroke-dasharray="3 6" stroke-linecap="round">`
    + `<ellipse cx="100" cy="112" rx="48" ry="58"/><path d="M18 250 Q18 186 100 180 Q182 186 182 250"/></g>`;

  // Un poco de grano, como en la propuesta: un collage impreso, no un vector.
  const grano = (id) => `<filter id="${id}g" x="0" y="0" width="100%" height="100%"><feTurbulence type="fractalNoise" baseFrequency=".9" numOctaves="2" stitchTiles="stitch"/><feColorMatrix values="0 0 0 0 0  0 0 0 0 0  0 0 0 0 0  0 0 0 .55 0"/></filter>`;

  const mezclar = (a) => a.map((x) => [Math.random(), x]).sort((x, y) => x[0] - y[0]).map((x) => x[1]);
  let serie = 0;

  function collage() {
    const id = 'vxc' + serie++;
    const orden = mezclar([0, 1, 2, 3, 4, 5]);
    const fondos = mezclar(FONDOS);
    const defs = PIEZAS.map((r, i) => `<clipPath id="${id}-${i}"><rect x="${r[0]}" y="${r[1]}" width="${r[2]}" height="${r[3]}"/></clipPath>`).join('');
    const piezas = PIEZAS.map((r, i) =>
      `<g class="vx-cara__pieza" clip-path="url(#${id}-${i})"><rect x="${r[0]}" y="${r[1]}" width="${r[2]}" height="${r[3]}" fill="${fondos[i]}"/>${retrato(GENTE[orden[i]])}</g>`
    ).join('');
    return `<svg viewBox="0 0 200 250" aria-hidden="true" focusable="false"><defs>${defs}${grano(id)}</defs>${piezas}<rect width="200" height="250" filter="url(#${id}g)" opacity=".22"/></svg>`;
  }

  const puestoLibre = () => {
    const id = 'vxc' + serie++;
    return `<svg viewBox="0 0 200 250" aria-hidden="true" focusable="false"><defs>${grano(id)}</defs><rect width="200" height="250" fill="${C.blanco}"/>${libre}<rect width="200" height="250" filter="url(#${id}g)" opacity=".22"/></svg>`;
  };

  const caras = [...caja.querySelectorAll('[data-vx-cara="a"], [data-vx-cara="b"]')];
  caras.forEach((c) => { c.innerHTML = collage(); });
  const hueco = caja.querySelector('[data-vx-cara="libre"]');
  if (hueco) hueco.innerHTML = puestoLibre();

  window.vxRotarRubros([...caja.querySelectorAll('[data-vx-rubro]')], caja, 4500);

  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  let pausa = false;
  caja.addEventListener('pointerenter', () => { pausa = true; });
  caja.addEventListener('pointerleave', () => { pausa = false; });

  setInterval(() => {
    if (pausa || document.hidden) return;
    caras.forEach((c, ci) => {
      c.innerHTML = collage();
      if (typeof Element.prototype.animate !== 'function') return;
      c.querySelectorAll('.vx-cara__pieza').forEach((p, i) => p.animate(
        [{ opacity: 0, transform: `translate(${i % 2 ? 10 : -10}px, 0)` }, { opacity: 1, transform: 'none' }],
        { duration: 600, delay: ci * 90 + i * 60, easing: 'cubic-bezier(.2,.8,.2,1)', fill: 'backwards' }
      ));
    });
  }, 4500);
})();
