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
import { BASE } from '../comun-pantalla.mjs';

const RUTAS = [ '/', '/preguntas-frecuentes/', '/login/', '/privacidad/', '/terminos/' ];

/** De la propuesta de identidad. Cada una con por qué no se dice. */
const PROHIBIDAS = [
  [ /\becosistemas?\b/i, 'no significa nada' ],
  [ /\bsinergias?\b/i, 'no significa nada' ],
  [ /\bsoluci[oó]n(es)?\b/i, 'no significa nada' ],
  [ /\bpartners?\b/i, 'está en inglés y ya hay palabra' ],
  [ /\bmatch(es)?\b/i, 'es de las aplicaciones de citas' ],
  [ /\bearly adopters?\b/i, 'se dice Miembro Pionero' ],
  [ /VITRINEXO/, 'el nombre en caja alta grita' ],
];

const origen = new URL( BASE() ).origin;
const navegador = await chromium.launch();
const pagina = await navegador.newPage( { viewport: { width: 1280, height: 900 } } );

const fallos = [];
const medidas = [];

for ( const ruta of RUTAS ) {
  const r = await pagina.goto( origen + ruta, { waitUntil: 'domcontentloaded' } ).catch( () => null );
  if ( ! r || ! r.ok() ) continue;
  medidas.push( ruta );

  const texto = await pagina.evaluate( () => document.body.innerText );
  for ( const [ patron, porque ] of PROHIBIDAS ) {
    const m = texto.match( patron );
    if ( m ) fallos.push( `${ ruta } dice «${ m[ 0 ] }»: ${ porque }` );
  }
}

await navegador.close();

const sinMedir = RUTAS.filter( ( r ) => ! medidas.includes( r ) );
if ( sinMedir.length ) console.log( `      (no respondieron, no se midieron: ${ sinMedir.join( ', ' ) })` );

informar( 'vocabulario', fallos );
