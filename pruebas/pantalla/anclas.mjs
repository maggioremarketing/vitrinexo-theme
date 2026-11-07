/**
 * Que ningún enlace de la página apunte a un bloque que no existe.
 *
 * El menú del sitio lleva seis entradas y cuatro de ellas apuntaban a bloques
 * que la portada ya no tenía: «Para quién es» y las tres comunidades, que
 * colgaban de #el-multiverso después de que la sección pasara a llamarse
 * #comunidades. El enlace no se rompe de una forma visible: lleva a la
 * portada y la deja quieta arriba, así que parece que no hizo nada.
 *
 * Había una comprobación de anclas y miraba solo el menú del manual, con
 * `.vx-menu`. El menú del producto no lo miraba nadie, que es el mismo hueco
 * por el que se pasaron los tres botones con tinta blanca.
 *
 * Se comprueba el fragmento de todo enlace que lleva a la misma página donde
 * está: los de otra página se miden cuando se visita esa página, porque todas
 * están en la lista.
 *
 * Y se recorre dos veces, con sesión y sin ella, porque el pie no es el mismo:
 * logueado cambia los enlaces de marketing por los del área privada, así que
 * una sola pasada con la sesión del banco no habría visto ni uno de los nueve
 * enlaces rotos que encontró esta prueba.
 */

import { informar } from '../comun.mjs';
import { enLaPagina } from '../comun-pantalla.mjs';

const medir =
  async ( pagina ) =>
    pagina.evaluate( () => {
      const aqui = location.origin + location.pathname;
      const malos = new Set();

      for ( const a of document.querySelectorAll( 'a[href]' ) ) {
        const crudo = a.getAttribute( 'href' );
        if ( ! crudo || crudo === '#' ) continue;

        // Un enlace con onclick que cancela la navegación, o una pestaña de
        // Bootstrap, no va a ninguna parte: el href es un asa, no un destino.
        // Las cinco pestañas de /configuracion/ son así, y acusarlas sería la
        // prueba pasándose, que es cómo se llenan de ruido las suites.
        if ( a.hasAttribute( 'onclick' ) ) continue;
        if ( a.dataset.bsToggle === 'tab' || a.dataset.bsToggle === 'pill' ) continue;
        if ( a.getAttribute( 'role' ) === 'tab' ) continue;

        let u;
        try { u = new URL( crudo, location.href ); } catch { continue; }
        if ( ! u.hash || u.hash === '#' ) continue;
        if ( u.origin + u.pathname !== aqui ) continue;

        const id = decodeURIComponent( u.hash.slice( 1 ) );
        if ( document.getElementById( id ) ) continue;
        // Un ancla también puede ser un <a name>, que sigue siendo válido.
        if ( document.querySelector( `a[name="${ CSS.escape( id ) }"]` ) ) continue;

        const texto = ( a.textContent || '' ).replace( /\s+/g, ' ' ).trim().slice( 0, 40 );
        malos.add( `«${ texto || a.className || 'enlace sin texto' }» apunta a #${ id } y no existe ese bloque` );
      }
      return [ ...malos ];
    } );

const fallos = [ ...await enLaPagina( medir, { anchos: [ 1200 ], sinSesion: true } ) ];
const conSesion = await enLaPagina( medir, { anchos: [ 1200 ] } );
for ( const f of conSesion ) if ( ! fallos.includes( f ) ) fallos.push( f );
if ( conSesion.nota ) fallos.nota = conSesion.nota;

informar( 'anclas', fallos );
