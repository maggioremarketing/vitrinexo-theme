/**
 * Lo que comparten las comprobaciones que abren el navegador.
 *
 * Todas miden la misma página en los mismos anchos, así que abrir el navegador
 * y llevar la cuenta de los fallos se escribe una vez.
 */

import { chromium } from 'playwright';

/** Los anchos donde el documento tiene que sostenerse. */
export const ANCHOS = [ 390, 700, 1200, 1600 ];

export const BASE = () => process.argv[ 2 ] ?? 'https://vitrinexo.com/marca';

/**
 * Las páginas públicas del producto, además de la del manual.
 *
 * Siete de las diez comprobaciones de pantalla medían solo la URL que se les
 * pasaba, o sea el manual, y tres recorrían el sitio con su propia lista
 * copiada. Así, el contraste y el área de toque se verificaban en la página
 * que describe las reglas y no en las pantallas que las aplican: los tres
 * botones con tinta blanca sobre pastel aparecieron a mano, buscando otra
 * cosa, y ninguna prueba los había visto nunca.
 *
 * La que no responde no se mide, y se dice cuál: un banco local solo sirve
 * /marca, y dar por buena una página que no se abrió es la peor forma de
 * pasar en verde.
 */
export const RUTAS = [ '/', '/preguntas-frecuentes/', '/login/', '/privacidad/', '/terminos/' ];

/** Las rutas a recorrer, con el origen de BASE y el propio camino de BASE. */
export function rutas() {
  const u = new URL( BASE() );
  return [ ...new Set( [ u.pathname, ...RUTAS ] ) ].map( ( r ) => u.origin + r );
}

/**
 * Abre la página y se la pasa a la medición. La ventana se estira al alto del
 * documento: elementFromPoint solo responde dentro de lo visible, y una página
 * larga medida en una ventana corta deja sin medir todo lo que no se ve. Esa
 * es la trampa que hizo que una suite midiera el 24% de la página.
 */
export async function enLaPagina( medir, { anchos = [ 1200 ], alto = 900, soloBase = false } = {} ) {
  const navegador = await chromium.launch();
  const fallos = [], mudas = [];
  const destinos = soloBase ? [ BASE() ] : rutas();

  try {
    for ( const url of destinos ) {
      const camino = new URL( url ).pathname;
      for ( const ancho of anchos ) {
        const contexto = await navegador.newContext( { viewport: { width: ancho, height: alto } } );
        const pagina = await contexto.newPage();
        const r = await pagina.goto( url, { waitUntil: 'networkidle' } ).catch( () => null );
        if ( ! r || ! r.ok() ) {
          if ( ! mudas.includes( camino ) ) mudas.push( camino );
          await contexto.close();
          continue;
        }

        const altoDoc = await pagina.evaluate( () => document.documentElement.scrollHeight );
        await pagina.setViewportSize( { width: ancho, height: Math.min( altoDoc, 30000 ) } );

        for ( const f of await medir( pagina, ancho ) ) fallos.push( `[${ camino } · ${ ancho }px] ${ f }` );
        await contexto.close();
      }
    }
  } finally {
    await navegador.close();
  }

  if ( mudas.length ) fallos.nota = `no respondieron, no se midieron: ${ mudas.join( ', ' ) }`;
  return fallos;
}

/**
 * El color como lo devuelve el navegador no es el que está escrito: abrevia
 * #FFFFFF a rgb(255, 255, 255) y la translucidez la escribe aparte. Esto lo
 * deja en un hexadecimal de seis dígitos comparable.
 */
export const aHex = ( rgb ) => {
  const n = ( rgb.match( /[\d.]+/g ) ?? [] ).slice( 0, 3 ).map( ( x ) => Math.round( Number( x ) ) );
  return n.length === 3 ? '#' + n.map( ( x ) => x.toString( 16 ).padStart( 2, '0' ) ).join( '' ).toUpperCase() : rgb;
};
