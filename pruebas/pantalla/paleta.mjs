/**
 * Que el número impreso diga el color que se está mostrando.
 *
 * Es el defecto que ninguna prueba de código ve: la muestra se pinta con el
 * token y el número se escribe aparte, y basta con que uno de los dos cambie
 * para que el manual mienta con total convicción. Acá se compara el color que
 * el navegador de verdad pintó contra el hexadecimal que está escrito al lado.
 */

import { informar } from '../comun.mjs';
import { enLaPagina, aHex } from '../comun-pantalla.mjs';

// Esta comprobación mide la página del manual y solo esa: lo que busca
// —las muestras de color, el menú lateral, las clases .vx-*— no existe en
// las pantallas del producto. Abrirla al sitio entero no la hacía más
// estricta, la hacía gritar «no hay ninguna muestra» en cinco páginas.
const fallos = await enLaPagina( async ( pagina ) =>
  pagina.evaluate( () => {
    const aHexLocal = ( rgb ) => {
      const n = ( rgb.match( /[\d.]+/g ) ?? [] ).slice( 0, 3 ).map( ( x ) => Math.round( Number( x ) ) );
      return n.length === 3 ? '#' + n.map( ( x ) => x.toString( 16 ).padStart( 2, '0' ) ).join( '' ).toUpperCase() : rgb;
    };

    const malos = [];
    const muestras = document.querySelectorAll( '[data-hex]' );
    if ( ! muestras.length ) return [ 'no hay ninguna muestra de color en la página' ];

    for ( const muestra of muestras ) {
      const dice = muestra.dataset.hex.toUpperCase();
      const pintado = aHexLocal( getComputedStyle( muestra ).backgroundColor );
      if ( dice !== pintado ) malos.push( `una muestra dice ${ dice } y está pintada ${ pintado }` );

      // Y el número que se lee al lado es el mismo que el de la muestra.
      const fila = muestra.parentElement;
      const impreso = [ ...fila.querySelectorAll( '.vx-dato' ) ]
        .map( ( d ) => d.textContent.trim().toUpperCase() )
        .find( ( t ) => /^#[0-9A-F]{6}$/.test( t ) );
      if ( impreso && impreso !== pintado ) {
        malos.push( `la fila imprime ${ impreso } junto a una muestra pintada ${ pintado }` );
      }
    }
    return [ ...new Set( malos ) ];
  } ),
  { soloBase: true }
);

informar( 'paleta', fallos );
