/**
 * Que un tachado nunca sea lo único que dice que algo es falso.
 *
 * El recurso de la portada enuncia una creencia y la tacha. Es una figura
 * fuerte mirándola, y una trampa sin mirarla: la raya es el único lugar donde
 * vive la negación. Quien no la ve —un lector de pantalla, que no anuncia
 * `s` salvo que se lo pidan, o una pantalla donde la raya no alcanza a
 * destacarse del fondo— lee la frase entera como una afirmación nuestra, y
 * entiende exactamente lo contrario de lo que la portada dice.
 *
 * Por eso se miden dos cosas en cada tachado con texto:
 *
 * 1. Que haya un equivalente en palabras: un texto solo para lectores dentro
 *    del tachado, o un aria-label que lo reemplace.
 * 2. Que la raya llegue a 3:1 contra el fondo que de verdad queda debajo, que
 *    es el mínimo de la norma para lo que no es texto. El coral de marca da
 *    2,29 sobre hueso y era el color obvio para esta raya.
 *
 * El color de la raya es `text-decoration-color`, que por omisión es
 * `currentColor`: si nadie lo declaró, se mide el color del texto, que es la
 * raya que se va a pintar.
 *
 * Queda fuera lo que lleva `data-cita`, que es el atributo con el que el
 * manual marca un valor que cita en vez de usar. La columna «No decimos» del
 * vocabulario es justo eso: ocho palabras tachadas cuya negación no la lleva
 * la raya sino el encabezado de la columna, que está escrito. Acusarlas sería
 * la prueba pasándose, y una prueba que acusa de más se desatiende igual que
 * una que no acusa. Se exime por un atributo explícito y no por deducción,
 * igual que `contraste` exime el logotipo por `data-logotipo`.
 */

import { informar } from '../comun.mjs';
import { enLaPagina } from '../comun-pantalla.mjs';

const medir =
  async ( pagina ) =>
    pagina.evaluate( () => {
      const lienzo = document.createElement( 'canvas' ).getContext( '2d', { willReadFrequently: true } );
      const rgba = ( css ) => {
        lienzo.clearRect( 0, 0, 1, 1 );
        lienzo.fillStyle = css;
        lienzo.fillRect( 0, 0, 1, 1 );
        const [ r, g, b, a ] = lienzo.getImageData( 0, 0, 1, 1 ).data;
        return [ r, g, b, a / 255 ];
      };
      const luminancia = ( [ r, g, b ] ) => {
        const c = ( v ) => { v /= 255; return v <= 0.03928 ? v / 12.92 : ( ( v + 0.055 ) / 1.055 ) ** 2.4; };
        return 0.2126 * c( r ) + 0.7152 * c( g ) + 0.0722 * c( b );
      };
      const razon = ( a, b ) => {
        const [ x, y ] = [ luminancia( a ), luminancia( b ) ];
        return ( Math.max( x, y ) + 0.05 ) / ( Math.min( x, y ) + 0.05 );
      };
      const sobre = ( frente, fondo ) => frente.slice( 0, 3 ).map( ( v, i ) => v * frente[ 3 ] + fondo[ i ] * ( 1 - frente[ 3 ] ) );
      const fondoDe = ( el ) => {
        let color = [ 255, 255, 255, 1 ];
        const capas = [];
        for ( let n = el; n; n = n.parentElement ) {
          const c = rgba( getComputedStyle( n ).backgroundColor );
          if ( c[ 3 ] > 0 ) capas.push( c );
          if ( c[ 3 ] === 1 ) break;
        }
        for ( const capa of capas.reverse() ) color = [ ...sobre( capa, color ), 1 ];
        return color;
      };

      /** Un equivalente en palabras, acá o en quien lo contiene. */
      const tieneEquivalente = ( el ) => {
        if ( el.hasAttribute( 'data-cita' ) ) return true;
        if ( el.querySelector( '.vx-solo-lectores' ) ) return true;
        for ( let n = el; n; n = n.parentElement ) {
          if ( ( n.getAttribute?.( 'aria-label' ) || '' ).trim() ) return true;
        }
        return false;
      };

      const malos = new Set();
      for ( const el of document.querySelectorAll( 'body *' ) ) {
        const e = getComputedStyle( el );
        if ( ! e.textDecorationLine.includes( 'line-through' ) ) continue;
        if ( e.display === 'none' || e.visibility === 'hidden' ) continue;
        if ( ! el.getClientRects().length ) continue;

        // Solo el elemento que declara la raya, no cada hijo que la hereda
        // pintada encima: acusar a los dos es acusar dos veces lo mismo.
        const padre = el.parentElement;
        if ( padre && getComputedStyle( padre ).textDecorationLine.includes( 'line-through' ) ) continue;

        const texto = ( el.textContent || '' ).replace( /\s+/g, ' ' ).trim();
        if ( ! texto ) continue;
        const corto = texto.slice( 0, 45 );

        if ( ! tieneEquivalente( el ) ) {
          malos.add( `«${ corto }» va tachado y nada lo dice en palabras: leído en voz alta afirma lo que niega` );
        }

        const declarado = e.textDecorationColor;
        const raya = rgba( declarado === 'currentcolor' ? e.color : declarado );
        const fondo = fondoDe( el );
        const r = razon( sobre( raya, fondo ), fondo );
        if ( r < 3 ) {
          malos.add( `la raya de «${ corto }» da ${ r.toFixed( 2 ) }:1 contra su fondo y la norma pide 3` );
        }
      }
      return [ ...malos ];
    } );

const fallos = await enLaPagina( medir, { anchos: [ 1200 ] } );

informar( 'tachado', fallos );
