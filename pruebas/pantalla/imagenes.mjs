/**
 * Que ninguna imagen se deforme ni aparezca sin decir qué hay en ella.
 *
 * Una imagen sin alt deja fuera a quien no la ve, y una imagen estirada es la
 * marca mal puesta: el logotipo con la proporción cambiada es un logotipo
 * distinto.
 */

import { informar } from '../comun.mjs';
import { ANCHOS, enLaPagina } from '../comun-pantalla.mjs';

const fallos = await enLaPagina(
  async ( pagina ) => {
    // Las fotos van con loading="lazy", así que medir apenas termina la red da
    // por no cargada a la que todavía está pidiéndose. Se espera a que cada una
    // resuelva, para bien o para mal: lo que se quiere saber es cuál falló, no
    // cuál iba lenta.
    //
    // Pero una imagen lazy que nunca entra en pantalla no empieza a cargar, así
    // que no dispara load ni error, y su promesa no resuelve nunca. Esta prueba
    // se colgaba para siempre por eso, y solo hizo falta que la página creciera
    // lo suficiente como para dejar una imagen abajo del todo.
    //
    // No se arregla recorriendo la página: eso depende de que el barrido sea
    // más lento que la red, y cuando no lo es la prueba acusa de no cargar a
    // una imagen que estaba cargando. Se les quita el lazy, que empieza la
    // descarga en el acto y no depende de dónde esté el scroll.
    await pagina.evaluate( () => {
      for ( const img of document.images ) if ( img.loading === 'lazy' ) img.loading = 'eager';
    } );

    await pagina.evaluate( () => Promise.all(
      [ ...document.images ].map( ( img ) => img.complete ? null : new Promise( ( listo ) => {
        img.addEventListener( 'load', listo, { once: true } );
        img.addEventListener( 'error', listo, { once: true } );
        setTimeout( listo, 10000 ); // y un plazo, para que una sola imagen no cuelgue la suite
      } ) )
    ) );

    return pagina.evaluate( () => {
      const malos = [];
      for ( const img of document.images ) {
        // El nombre sale del atributo y no de currentSrc: cuando la imagen no
        // carga, currentSrc viene vacío y el fallo no dice cuál es.
        const nombre = ( img.getAttribute( 'src' ) || '(sin src)' ).split( '/' ).pop();
        const alt = img.getAttribute( 'alt' );
        const decorativa = img.getAttribute( 'aria-hidden' ) === 'true' || alt === '';
        if ( alt === null ) {
          malos.push( `${ nombre } no dice qué hay en ella` );
        } else if ( ! decorativa && alt.trim().length < 3 ) {
          malos.push( `${ nombre } tiene un alt que no describe nada: «${ alt }»` );
        }

        if ( ! img.complete || ! img.naturalWidth ) {
          malos.push( `${ nombre } no cargó` );
          continue;
        }

        const caja = img.getBoundingClientRect();
        if ( ! caja.width || ! caja.height ) continue;
        const propia = img.naturalWidth / img.naturalHeight;
        const puesta = caja.width / caja.height;
        if ( Math.abs( propia - puesta ) / propia > 0.02 ) {
          malos.push( `${ nombre } está deformada: nació ${ propia.toFixed( 2 ) } y se muestra ${ puesta.toFixed( 2 ) }` );
        }
      }
      return [ ...new Set( malos ) ];
    } );
  }, { anchos: ANCHOS }
);

informar( 'imagenes', fallos );
