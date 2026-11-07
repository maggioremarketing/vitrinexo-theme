/**
 * El área que de verdad se puede presionar.
 *
 * La regla de la casa es 44×44. La norma pide 24×24, y 24 no alcanza para un
 * teléfono en una mano, parado en la calle.
 *
 * Se mide en los cuatro anchos y sobre la página entera, sin tope de altura:
 * una prueba con un límite silencioso deja secciones enteras sin medir y da
 * tranquilidad sin dar nada.
 *
 * La excepción es la de la propia norma: un enlace dentro de un párrafo no
 * tiene tamaño propio, lo tiene la línea de texto donde vive.
 */

import { informar } from '../comun.mjs';
import { ANCHOS, enLaPagina } from '../comun-pantalla.mjs';

const MINIMO = 44;

const fallos = await enLaPagina(
  async ( pagina ) =>
    pagina.evaluate( ( minimo ) => {
      const malos = [];
      const controles = document.querySelectorAll( 'a[href], button, input, select, textarea, [role="button"], [tabindex]:not([tabindex="-1"])' );

      for ( const el of controles ) {
        const estilo = getComputedStyle( el );
        if ( estilo.display === 'none' || estilo.visibility === 'hidden' ) continue;

        // El enlace en medio de una frase queda exento por la propia norma: su
        // tamaño no es suyo, es el de la línea de texto donde vive.
        //
        // La norma dice «en una frase o en un bloque de texto», y acá solo se
        // miraba <p>. Un enlace dentro de un titular —«Hola, Ana»— es el mismo
        // caso y se denunciaba igual, así que la lista es la de los elementos
        // que de verdad son texto corrido.
        const TEXTO = 'p, h1, h2, h3, h4, h5, h6, li, blockquote, figcaption, dd, dt, label, td, th';
        if ( estilo.display.startsWith( 'inline' ) && el.closest( TEXTO ) ) continue;

        // Una casilla dentro de su etiqueta no es el objetivo: lo es la
        // etiqueta, porque al tocarla el navegador marca la casilla. Medir el
        // cuadradito y no lo que de verdad se pulsa denuncia un formulario
        // correcto y deja pasar uno con la etiqueta suelta.
        if ( /^(checkbox|radio)$/.test( el.type || '' ) ) {
          const etiqueta = el.closest( 'label' ) || document.querySelector( `label[for="${ CSS.escape( el.id || '\0' ) }"]` );
          if ( etiqueta ) {
            const e = etiqueta.getBoundingClientRect();
            if ( e.width >= minimo && e.height >= minimo ) continue;
          }
        }

        const caja = el.getBoundingClientRect();
        if ( ! caja.width && ! caja.height ) continue;

        if ( caja.width < minimo || caja.height < minimo ) {
          const nombre = ( el.textContent || '' ).replace( /\s+/g, ' ' ).trim().slice( 0, 30 ) || el.getAttribute( 'aria-label' ) || el.tagName.toLowerCase();
          // Con un decimal y no redondeado: un fallo que dice «44×44 y
          // necesita 44×44» manda a buscar el problema al lugar equivocado.
          const medida = ( n ) => n.toFixed( 1 ).replace( '.0', '' ).replace( '.', ',' );
          malos.push( `«${ nombre }» mide ${ medida( caja.width ) }×${ medida( caja.height ) } y necesita ${ minimo }×${ minimo }` );
        }
      }
      return [ ...new Set( malos ) ];
    }, minimoDelProceso() )
  , { anchos: ANCHOS }
);

function minimoDelProceso() { return MINIMO; }

informar( 'toque', fallos );
