/**
 * Que el documento no se salga de la pantalla por el costado.
 *
 * Una tabla de colores, un bloque de código o una palabra larga bastan para
 * que aparezca la barra horizontal, y con ella la página entera se corre al
 * arrastrar. Se mide en los cuatro anchos, y cuando desborda se nombra al
 * culpable: «hay desborde» sin decir de quién manda a buscar a ciegas.
 */

import { informar } from '../comun.mjs';
import { ANCHOS, enLaPagina } from '../comun-pantalla.mjs';

const fallos = await enLaPagina(
  async ( pagina, ancho ) =>
    pagina.evaluate( ( limite ) => {
      const malos = [];
      // Los dos, y el mayor: con la raíz sola, una tabla que se sale del body
      // no mueve el número y la prueba pasa con la página desbordada. Es lo que
      // pasó la primera vez que se probó rompiéndola a propósito.
      const ancho = Math.max( document.documentElement.scrollWidth, document.body.scrollWidth );
      if ( ancho <= limite + 1 ) return [];

      // Un panel fuera de pantalla no mueve la página: va en position fixed,
      // que se saca del flujo. La prueba ya eximía al panel y no a sus hijos,
      // que son estáticos dentro de él, así que el menú lateral cerrado del
      // teléfono aparecía como desborde en cada medición. Se mira el ancestro.
      const porFixed = ( el ) => {
        for ( let n = el; n && n !== document.body; n = n.parentElement ) {
          if ( getComputedStyle( n ).position === 'fixed' ) return true;
        }
        return false;
      };

      const culpables = [];
      for ( const el of document.querySelectorAll( 'body *' ) ) {
        const caja = el.getBoundingClientRect();
        if ( caja.right > limite + 1 && ! porFixed( el ) ) {
          const nombre = `${ el.tagName.toLowerCase() }.${ ( el.className || '' ).toString().split( ' ' )[ 0 ] }`;
          culpables.push( `${ nombre } llega hasta ${ Math.round( caja.right ) }` );
        }
      }

      // Si lo único que sobresalía estaba en un fixed, no hay desborde: la
      // barra horizontal no aparece y la página no se corre al arrastrar.
      if ( ! culpables.length ) return [];

      malos.push( `el documento mide ${ ancho } de ancho y la ventana ${ limite }` );
      malos.push( ...culpables );
      return [ ...new Set( malos ) ].slice( 0, 6 );
    }, ancho )
  , { anchos: ANCHOS }
);

informar( 'desborde', fallos );
