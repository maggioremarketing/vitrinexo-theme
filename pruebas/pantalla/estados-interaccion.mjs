/**
 * Que ningún estado cambie el tamaño, que el foco siempre se vea y que el
 * hover pida puntero.
 *
 * Si el hover ensancha, la fila se corre justo cuando alguien iba a hacer
 * clic. Y Tailwind envuelve sus hover: en @media (hover: hover), pero el CSS
 * escrito a mano no lo hace solo: sin envolver, el hover se queda pegado en un
 * teléfono, porque el primer toque lo activa y no hay puntero que lo retire.
 */

import { informar, leer, sinComentarios } from '../comun.mjs';
import { enLaPagina } from '../comun-pantalla.mjs';

const fallos = [];

// Todo hover del manual pide puntero.
const css = sinComentarios( leer( 'assets/css/marca.css' ) );
/**
 * El cuerpo de cada @media (hover: hover), contando llaves. Con una expresión
 * regular el bloque terminaba en la primera regla anidada, y la prueba
 * denunciaba hovers que sí estaban envueltos: el fallo era del extractor y no
 * del CSS.
 */
function cuerposDeHover( hoja ) {
  const cuerpos = [];
  const abre = /@media\s*\(\s*hover:\s*hover\s*\)\s*\{/g;
  let m;
  while ( ( m = abre.exec( hoja ) ) ) {
    let nivel = 1;
    let i = m.index + m[ 0 ].length;
    const desde = i;
    while ( i < hoja.length && nivel > 0 ) {
      if ( hoja[ i ] === '{' ) nivel++;
      else if ( hoja[ i ] === '}' ) nivel--;
      i++;
    }
    cuerpos.push( hoja.slice( desde, i - 1 ) );
  }
  return cuerpos.join( '\n' );
}

const enMedia = cuerposDeHover( css );
for ( const [ regla ] of css.matchAll( /^[^@{}]*:hover[^{]*\{/gm ) ) {
  if ( ! enMedia.includes( regla.trim() ) ) fallos.push( `«${ regla.trim() }» no está dentro de @media (hover: hover)` );
}

// Esta comprobación mide la página del manual y solo esa: lo que busca
// —las muestras de color, el menú lateral, las clases .vx-*— no existe en
// las pantallas del producto. Abrirla al sitio entero no la hacía más
// estricta, la hacía gritar «no hay ninguna muestra» en cinco páginas.
for ( const f of await enLaPagina( async ( pagina ) => {
  const malos = [];

  // El foco se ve y no mueve nada.
  const medida = await pagina.evaluate( () => {
    const a = document.querySelector( '.vx-menu-lista a' );
    const antes = a.getBoundingClientRect();
    a.focus();
    const estilo = getComputedStyle( a );
    const despues = a.getBoundingClientRect();
    return {
      seVe: estilo.outlineStyle !== 'none' && parseFloat( estilo.outlineWidth ) > 0,
      corrio: Math.abs( antes.width - despues.width ) > 0.5 || Math.abs( antes.height - despues.height ) > 0.5,
    };
  } );

  if ( ! medida.seVe ) malos.push( 'un enlace del menú enfocado no muestra contorno' );
  if ( medida.corrio ) malos.push( 'enfocar un enlace del menú le cambia el tamaño' );

  // Y el hover tampoco mueve nada.
  const hover = await pagina.evaluate( async () => {
    const a = document.querySelectorAll( '.vx-menu-lista a' )[ 1 ];
    const antes = a.getBoundingClientRect();
    a.dispatchEvent( new MouseEvent( 'mouseover', { bubbles: true } ) );
    await new Promise( ( r ) => requestAnimationFrame( r ) );
    const despues = a.getBoundingClientRect();
    return Math.abs( antes.height - despues.height ) > 0.5 || Math.abs( antes.width - despues.width ) > 0.5;
  } );
  if ( hover ) malos.push( 'pasar el puntero por un enlace del menú le cambia el tamaño' );

  return malos;
}, { soloBase: true } ) ) fallos.push( f );

informar( 'estados-interaccion', fallos );
