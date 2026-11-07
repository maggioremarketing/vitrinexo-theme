/**
 * Genera las piezas anchas del motivo de retratos.
 *
 *   npm run retratos
 *
 * El collage del manual es vertical y no escala: sus recortes funcionan porque
 * son chicos respecto de una cara, y a 1600 px de ancho cada recorte sería más
 * grande que una cara entera. Para lo ancho el motivo cambia de forma — una
 * rejilla de retratos enteros — pero no de reglas: los rasgos siguen en las
 * mismas coordenadas, los colores salen de la paleta y hay un solo lugar libre.
 *
 * Las dos piezas se escriben en assets/img/marca/ porque son el espécimen: el
 * manual muestra el mismo archivo que publica el sitio, no una copia.
 */
import { writeFileSync } from 'node:fs';

const C = {
  ink:   '#14161F', coral: '#FF7A4D', lav:  '#CBC3FF', rosa:   '#F4C6DC',
  mant:  '#F2EC79', cel:   '#A9C6E8', sal:  '#7DBF9E', terra:  '#C58D78',
  blanco:'#FFFFFF', hueso: '#F4F1EC',
};

/* ── El retrato. 200 x 250, con los rasgos en coordenadas fijas. ────────── */

const peloAtras = p => p.h === 1
  ? `<path d="M46 112 Q40 38 100 38 Q160 38 154 112 L162 206 Q100 222 38 206Z" fill="${p.ha}"/>`
  : p.h === 3 ? `<circle cx="100" cy="36" r="22" fill="${p.ha}"/>` : '';

const peloAdelante = p => {
  const ha = p.ha;
  if ( p.h === 0 ) return `<path d="M52 104 Q48 44 100 44 Q152 44 148 104 Q140 72 104 70 Q74 70 52 104Z" fill="${ha}"/>`;
  if ( p.h === 1 ) return `<path d="M52 100 Q56 52 100 50 Q146 52 148 96 Q120 70 70 82Z" fill="${ha}"/>`;
  if ( p.h === 2 ) return [[62,70,18],[82,54,20],[106,50,21],[130,60,19],[144,82,16],[56,92,14]]
    .map( c => `<circle cx="${c[0]}" cy="${c[1]}" r="${c[2]}" fill="${ha}"/>` ).join('');
  if ( p.h === 3 ) return `<path d="M52 100 Q50 52 100 52 Q150 52 148 100 Q132 66 100 66 Q68 66 52 100Z" fill="${ha}"/>`;
  return '';
};

const cara = p => {
  const s = p.sk, cl = p.cl, ink = C.ink;
  const ojos = p.e
    ? `<path d="M74 114 q8 -6 16 0 M110 114 q8 -6 16 0" stroke="${ink}" stroke-width="3.5" fill="none" stroke-linecap="round"/>`
    : `<circle cx="82" cy="113" r="4.5" fill="${ink}"/><circle cx="118" cy="113" r="4.5" fill="${ink}"/>`;
  const lentes = p.g
    ? `<g fill="none" stroke="${ink}" stroke-width="3.5"><rect x="64" y="101" width="32" height="23" rx="9"/><rect x="104" y="101" width="32" height="23" rx="9"/><path d="M96 111h8"/></g>`
    : '';
  return `<path d="M18 250 Q18 186 100 180 Q182 186 182 250Z" fill="${cl}"/>`
       + `<path d="M82 176 Q100 190 118 176 L116 150 H84Z" fill="${s}"/>`
       + `<path d="M78 184 L100 206 L122 184" fill="none" stroke="${C.blanco}" stroke-width="5" opacity=".7"/>`
       + `<ellipse cx="100" cy="112" rx="48" ry="58" fill="${s}"/>${ojos}${lentes}`
       + `<path d="M101 129 Q95 140 104 143" fill="none" stroke="${ink}" stroke-width="3" stroke-linecap="round" opacity=".7"/>`
       + `<path d="M86 152 Q100 161 114 152" fill="none" stroke="${ink}" stroke-width="3.5" stroke-linecap="round"/>`;
};

const retrato = p => peloAtras( p ) + cara( p ) + peloAdelante( p );

/* El lugar libre: el mismo contorno, en línea punteada y sin relleno. */
const lugarLibre = `<g fill="none" stroke="${C.ink}" stroke-width="2.5" stroke-dasharray="3 6" stroke-linecap="round">`
  + `<ellipse cx="100" cy="112" rx="48" ry="58"/>`
  + `<path d="M18 250 Q18 186 100 180 Q182 186 182 250"/></g>`;

/* ── El reparto ─────────────────────────────────────────────────────────────
 * Seis rasgos con periodos 5, 3, 2, 6, 3 y 6. Su mínimo común múltiplo es 30,
 * así que las primeras treinta combinaciones no se repiten: la pared del
 * directorio no puede ser seis personas clonadas.                            */
const PIELES = [ C.lav, C.rosa, C.mant, C.cel, C.sal, C.terra ];
const PELOS  = [ C.ink, C.terra, C.coral ];
const ROPAS  = [ C.coral, C.cel, C.sal, C.lav, C.rosa, C.mant ];

const reparto = n => Array.from( { length: n }, ( _, i ) => ( {
  h:  [ 0, 1, 2, 3, 4 ][ ( i * 3 + 1 ) % 5 ],
  g:  ( i * 5 ) % 3 === 0 ? 1 : 0,
  e:  ( i * 7 ) % 2,
  sk: PIELES[ ( i * 5 ) % 6 ],
  ha: PELOS[ ( i * 2 + 1 ) % 3 ],
  cl: ROPAS[ ( i * 7 + 2 ) % 6 ],
} ) );

/* Siete fondos: con cinco o diez columnas no se alinean nunca en franjas. */
const FONDOS = [ C.hueso, C.blanco, C.hueso, C.lav, C.blanco, C.cel, C.hueso ];

/** Un retrato dentro de su casilla, escalado para cubrirla y centrado en la cara. */
function casilla( x, y, w, h, i, { libre = false, id, gente } ) {
  const p = gente[ i % gente.length ];
  const crudo = FONDOS[ i % FONDOS.length ];
  // El lugar libre va siempre sobre blanco: sobre un pastel parece una ficha a
  // la que le falta la foto, no un lugar que está esperando a alguien.
  const fondo = libre ? C.blanco : ( crudo === p.sk ? C.blanco : crudo );
  const k = Math.max( w / 200, h / 250 );
  const ox = x + ( w - 200 * k ) / 2, oy = y + ( h - 250 * k ) / 2;
  return `<g clip-path="url(#${id})"><rect x="${x}" y="${y}" width="${w}" height="${h}" fill="${fondo}"/>`
       + `<g transform="translate(${ox.toFixed(1)} ${oy.toFixed(1)}) scale(${k.toFixed(3)})">`
       + `${libre ? lugarLibre : retrato( p )}</g></g>`;
}

function rejilla( { w, h, cols, filas, libre, alt, pre } ) {
  const tw = w / cols, th = h / filas, gente = reparto( cols * filas );
  const defs = [], cuerpo = [];
  let i = 0;
  for ( let f = 0; f < filas; f++ ) for ( let c = 0; c < cols; c++, i++ ) {
    const id = `${pre}${i}`;
    defs.push( `<clipPath id="${id}"><rect x="${( c * tw ).toFixed(2)}" y="${( f * th ).toFixed(2)}" width="${tw.toFixed(2)}" height="${th.toFixed(2)}"/></clipPath>` );
    cuerpo.push( casilla( c * tw, f * th, tw, th, i, { libre: i === libre, id, gente } ) );
  }
  return `<?xml version="1.0" encoding="UTF-8"?>\n`
    + `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ${w} ${h}" role="img" aria-label="${alt}">`
    + `<defs>${defs.join('')}</defs><rect width="${w}" height="${h}" fill="${C.hueso}"/>${cuerpo.join('')}</svg>\n`;
}

const dir = process.argv[ 2 ] || 'assets/img/marca';

/* Portada. Casilla de 320 x 450 (0,71) contra los 200 x 250 del retrato (0,80):
   el recorte es mínimo y las cabezas salen enteras. Diez retratos a 780 px de
   ancho dan caras de 156 px, que todavía tienen cara. */
writeFileSync( `${dir}/retratos-portada.svg`, rejilla( {
  w: 1600, h: 900, cols: 5, filas: 2, libre: 3, pre: 'p',
  alt: 'Diez retratos ilustrados en dos filas; uno es un lugar libre dibujado con línea punteada.',
} ) );

/* Franja. 1600 x 674 es 2,375 — exactamente la proporción a la que la franja se
   publica en escritorio (1140 x 480), así que no se recorta nada. */
writeFileSync( `${dir}/retratos-mosaico.svg`, rejilla( {
  w: 1600, h: 674, cols: 10, filas: 3, libre: 14, pre: 'm',
  alt: 'Una pared de treinta retratos ilustrados, todos distintos; uno es un lugar libre dibujado con línea punteada.',
} ) );

console.log( `  retratos-portada.svg  1600x900  10 retratos, lugar libre en el 4` );
console.log( `  retratos-mosaico.svg  1600x674  30 retratos, lugar libre en el 15` );
