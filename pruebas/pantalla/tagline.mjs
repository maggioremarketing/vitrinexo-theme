/**
 * Que el tagline de la portada salga de donde está declarado.
 *
 * El manual dice tres cosas sobre el tagline y no las medía ninguna: que vive
 * declarado una sola vez, que la portada lo lee de ahí, y que sus dos mitades
 * se destacan por regla y no a mano. Las tres sobrevivieron intactas a que la
 * portada dejara de usarlo: cuando el titular pasó a ser otra frase,
 * `vx_tagline_html()` se quedó sin una sola llamada y el manual siguió
 * diciendo que la portada lo leía. Nadie se dio cuenta porque no había nada
 * que se diera cuenta.
 *
 * Esta no usa `enLaPagina` y abre el navegador sola, por una razón: la portada
 * pública es la que se ve SIN sesión. Con sesión, `/` no es la portada, es el
 * home del miembro, que saluda por el nombre y no lleva tagline ni tiene por
 * qué. La suite corre con la sesión del banco puesta, así que una prueba que
 * mida la portada tiene que pedirla limpia. La primera versión no lo hacía y
 * fallaba solo dentro de la suite, pasando suelta: el peor modo de fallar.
 */

import { chromium } from 'playwright';
import { informar, leer } from '../comun.mjs';
import { BASE } from '../comun-pantalla.mjs';

/** El tagline, leído de su única declaración. */
const DECLARADO = ( () => {
  const m = leer( 'functions.php' ).match( /function vx_tagline\(\): string \{\s*return '([^']+)';/ );
  if ( ! m ) throw new Error( 'no se encontró la declaración de vx_tagline() en functions.php' );
  return m[ 1 ];
} )();

/** Las dos mitades del nombre, que el destacado tiene que marcar. */
const MITADES = [ 'vitrina', 'nexos' ];

const PORTADA = new URL( BASE() ).origin + '/';

const fallos = [];
const navegador = await chromium.launch();
try {
  // Contexto limpio a propósito: sin storageState, o sea sin sesión.
  const pagina = await ( await navegador.newContext( { viewport: { width: 1200, height: 900 } } ) ).newPage();
  const r = await pagina.goto( PORTADA, { waitUntil: 'networkidle' } ).catch( () => null );

  if ( ! r || ! r.ok() ) {
    // Falla, no anota. La portada es la única pantalla que esta comprobación
    // mira: si no cargó, no se midió nada, y decirlo en verde es decir que el
    // tagline está bien sin haberlo leído.
    fallos.push( `la portada no respondió en ${ PORTADA }, así que no se midió` );
  } else {
    const altoDoc = await pagina.evaluate( () => document.documentElement.scrollHeight );
    await pagina.setViewportSize( { width: 1200, height: Math.min( altoDoc, 30000 ) } );

    fallos.push( ...await pagina.evaluate( ( { frase, mitades } ) => {
      // textContent y no innerText: innerText depende de cómo esté renderizada
      // la página en ese instante, y la ventana se acaba de estirar. La
      // visibilidad se comprueba aparte, con los rectángulos.
      const texto = ( n ) => ( n.textContent || '' ).replace( /\s+/g, ' ' ).trim();
      const malos = [];

      // El nodo más profundo cuyo texto es exactamente la frase: el que la
      // lleva y nada más. Los ancestros la contienen, pero traen media página.
      const exactos = [ ...document.querySelectorAll( 'body *' ) ].filter( ( el ) => texto( el ) === frase );
      if ( ! exactos.length ) return [ `la portada no muestra el tagline declarado («${ frase }»)` ];
      const linea = exactos[ exactos.length - 1 ];
      if ( ! linea.getClientRects().length ) return [ 'el tagline está en el HTML de la portada pero no se ve' ];

      const h1 = document.querySelector( 'h1' );
      if ( h1 && texto( h1 ).includes( frase ) ) {
        malos.push( 'el tagline volvió a ser el titular de la portada, y el manual dice que no lo es' );
      }

      const destacadas = [ ...linea.querySelectorAll( 'strong' ) ].map( texto );
      for ( const m of mitades ) {
        if ( ! destacadas.includes( m ) ) {
          malos.push( `«${ m }» no está destacada: el tagline dejó de salir de vx_tagline_html()` );
        }
      }
      return malos;
    }, { frase: DECLARADO, mitades: MITADES } ) );
  }
} finally {
  await navegador.close();
}

informar( 'tagline', fallos );
