/**
 * Que de toda pantalla se salga, y que dónde estás se vea sin depender del
 * color.
 *
 * El color solo no llega al 3:1 que la norma pide para lo que no es texto, así
 * que la marca de posición lleva contorno además de fondo. Acá se comprueba
 * que ese contorno exista de verdad y no solo en la intención del CSS.
 */

import { informar } from '../comun.mjs';
import { enLaPagina } from '../comun-pantalla.mjs';

// Esta comprobación mide la página del manual y solo esa: lo que busca
// —las muestras de color, el menú lateral, las clases .vx-*— no existe en
// las pantallas del producto. Abrirla al sitio entero no la hacía más
// estricta, la hacía gritar «no hay ninguna muestra» en cinco páginas.
const fallos = await enLaPagina( async ( pagina ) => {
  const rotos = await pagina.evaluate( () => {
    const malos = [];
    const enlaces = [ ...document.querySelectorAll( '.vx-menu a[href^="#"]' ) ];

    if ( enlaces.length < 28 ) malos.push( `el menú tiene ${ enlaces.length } enlaces y los criterios son 28` );

    for ( const a of enlaces ) {
      const id = decodeURIComponent( a.getAttribute( 'href' ) ).slice( 1 );
      if ( id && ! document.getElementById( id ) ) malos.push( `el menú apunta a #${ id } y no existe ese bloque` );
    }

    // Todo bloque de criterio tiene quien lo nombre en el menú.
    const apuntados = new Set( enlaces.map( ( a ) => decodeURIComponent( a.getAttribute( 'href' ) ).slice( 1 ) ) );
    for ( const bloque of document.querySelectorAll( '.vx-bloque[id]' ) ) {
      if ( ! apuntados.has( bloque.id ) ) malos.push( `el bloque ${ bloque.id } no está en el menú` );
    }
    return malos;
  } );

  // Dónde estás, marcado con algo más que color: se compara el ítem marcado
  // con uno que no lo está.
  await pagina.evaluate( () => {
    const a = document.querySelector( '.vx-menu a[data-criterio]' );
    if ( a ) a.setAttribute( 'aria-current', 'true' );
  } );

  const marca = await pagina.evaluate( () => {
    const enlaces = [ ...document.querySelectorAll( '.vx-menu a[data-criterio]' ) ];
    const leer = ( el ) => {
      const s = getComputedStyle( el );
      return { borde: s.borderColor, ancho: s.borderWidth, contorno: s.outlineStyle, peso: s.fontWeight, caja: el.getBoundingClientRect().height };
    };
    return { marcado: leer( enlaces[ 0 ] ), suelto: leer( enlaces[ 1 ] ) };
  } );

  if ( marca.marcado.borde === marca.suelto.borde && marca.marcado.contorno === marca.suelto.contorno ) {
    rotos.push( 'el ítem donde estás se distingue solo por color: mismo borde y mismo contorno que los demás' );
  }
  if ( Math.abs( marca.marcado.caja - marca.suelto.caja ) > 0.5 ) {
    rotos.push( `marcar dónde estás cambia la altura de la fila (${ marca.marcado.caja } contra ${ marca.suelto.caja })` );
  }

  return rotos;
}, { soloBase: true } );

informar( 'navegacion', fallos );
