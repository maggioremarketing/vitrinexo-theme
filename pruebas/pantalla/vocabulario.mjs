/**
 * Que las palabras prohibidas no aparezcan en lo que la gente lee.
 *
 * Se mide sobre la página renderizada y no sobre el código, porque la regla es
 * sobre el habla de la marca y no sobre los identificadores: preg_match,
 * VX_Matches y $matches[1] son código, no copy, y prohibirlos solo enseñaría a
 * ignorar la prueba.
 *
 * El manual queda fuera: es el único lugar donde las palabras prohibidas
 * tienen que aparecer, porque ahí es donde se prohíben.
 */

import { chromium } from 'playwright';
import { informar } from '../comun.mjs';
import { BASE, SESION, rutas } from '../comun-pantalla.mjs';

/** De la propuesta de identidad. Cada una con por qué no se dice. */
const PROHIBIDAS = [
  [ /\becosistemas?\b/i, 'no significa nada' ],
  [ /\bsinergias?\b/i, 'no significa nada' ],
  [ /\bsoluci[oó]n(es)?\b/i, 'no significa nada' ],
  [ /\bpartners?\b/i, 'está en inglés y ya hay palabra' ],
  [ /\bmatch(es)?\b/i, 'es de las aplicaciones de citas' ],
  [ /\bearly adopters?\b/i, 'se dice Miembro Pionero' ],
  [ /VITRINEXO/, 'el nombre en caja alta grita' ],

  // Del criterio Idioma, que decía las dos reglas y no las medía nadie.
  // El voseo: se trata de tú, y la conjugación se cuela sola al escribir
  // desde Buenos Aires o Montevideo.
  [ /\b(ten[ée]s|pod[ée]s|quer[ée]s|sab[ée]s|ten[ée]te|vos\s+(ten|pod|quer|sos))\b/i, 'se trata de tú: «tienes» y «puedes», nunca «tenés» ni «podés»' ],
  [ /\bsos\s+(un|una|el|la|miembro)\b/i, 'se trata de tú: «eres», nunca «sos»' ],

  // Y la frontera geográfica: se dice dónde hay comunidad hoy, no que la
  // marca sea de una región. La visión incluye Brasil, Estados Unidos y
  // Europa, y una marca que se declara latinoamericana se cierra esa puerta
  // sola.
  [ /\bplataforma\s+(latinoamericana|latina)\b/i, 'no se dice que la marca sea de una región: se dice dónde hay comunidad hoy' ],
  [ /\b(red|directorio|comunidad)\s+latinoamericana\b/i, 'no se dice que la marca sea de una región: se dice dónde hay comunidad hoy' ],
];

// La lista común, que incluye el área de miembros cuando hay sesión: el voseo
// se cuela en un saludo o en un aviso, y esos viven dentro.
const navegador = await chromium.launch();
const contexto = await navegador.newContext( {
  viewport: { width: 1280, height: 900 },
  ...( SESION ? { storageState: SESION } : {} ),
} );
const pagina = await contexto.newPage();

const fallos = [];
const medidas = [];

for ( const url of rutas() ) {
  const ruta = new URL( url ).pathname;
  const r = await pagina.goto( url, { waitUntil: 'domcontentloaded' } ).catch( () => null );
  if ( ! r || ! r.ok() ) continue;
  medidas.push( ruta );

  const texto = await pagina.evaluate( () => {
    const copia = document.body.cloneNode( true );
    // Y fuera lo que no es copy: un <script> con reglas de precarga trae
    // «href_matches» y la comprobación denunciaba a WordPress.
    for ( const n of copia.querySelectorAll( 'script, style, template, noscript' ) ) n.remove();
    for ( const cita of copia.querySelectorAll( '[data-cita]' ) ) cita.remove();
    document.body.appendChild( copia );
    const t = copia.innerText;
    copia.remove();
    return t;
  } );
  for ( const [ patron, porque ] of PROHIBIDAS ) {
    const m = texto.match( patron );
    if ( m ) fallos.push( `${ ruta } dice «${ m[ 0 ] }»: ${ porque }` );
  }
}

await navegador.close();

const sinMedir = rutas().map( ( u ) => new URL( u ).pathname ).filter( ( r ) => ! medidas.includes( r ) );
if ( sinMedir.length ) console.log( `      (no respondieron, no se midieron: ${ sinMedir.join( ', ' ) })` );

informar( 'vocabulario', fallos );
