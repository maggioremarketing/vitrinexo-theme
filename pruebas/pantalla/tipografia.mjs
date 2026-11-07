/**
 * Que la página obedezca la escala que documenta.
 *
 * El manual afirma que está escrito con sus propios peldaños, y eso hay que
 * comprobarlo viéndolo: el tamaño y el tracking se heredan, y una regla vieja
 * del tema puede estar pisando el token sin que nadie lo note. Pasó: h1 y h2
 * llevaban -0,07em de una regla global, que a 40 px son 2,8 px de menos entre
 * letra y letra, y el titular se leía apretado.
 *
 * Se compara el valor renderizado contra el token, no contra un número escrito
 * a mano acá: un valor copiado se desincroniza en cuanto alguien mueve la
 * escala.
 */

import { informar, resolver, tokensDelSistema } from '../comun.mjs';
import { chromium } from 'playwright';
import { BASE, enLaPagina } from '../comun-pantalla.mjs';

/**
 * En pantalla angosta los dos titulares bajan un peldaño. No es otra escala:
 * es otro escalón de la misma, y por eso se mide igual en vez de dejar sin
 * medir los anchos chicos.
 */
const ANGOSTO = 900;
const BAJA_UN_PELDANO = { '--fs-display': '--fs-h2', '--fs-h2': '--fs-h3' };

/** Qué peldaño le toca a cada pieza del manual. */
const PIEZAS = [
  [ '.vx-display', '--fs-display', '--ls-display' ],
  [ '.vx-h2', '--fs-h2', '--ls-h2' ],
  [ '.vx-h3', '--fs-h3', '--ls-h3' ],
  [ '.vx-h4', '--fs-h4', '--ls-h4' ],
  [ '.vx-bajada', '--fs-body-l', '--ls-body-l' ],
  [ '.vx-p', '--fs-body', '--ls-body' ],
  [ '.vx-nota', '--fs-body-s', '--ls-body-s' ],
  [ '.vx-sobretitulo', '--fs-caption', '--ls-sobretitulo' ],
];

const mapa = Object.fromEntries( tokensDelSistema().flatMap( ( g ) => g.tokens.map( ( t ) => [ t.nombre, t.valor ] ) ) );
const px = ( token ) => parseFloat( resolver( mapa[ token ] ?? '', mapa ) );
const em = ( token ) => parseFloat( resolver( mapa[ token ] ?? '', mapa ) );

/** Lo que le toca a cada pieza en un ancho dado. */
const esperadoEn = ( ancho ) =>
  PIEZAS.map( ( [ sel, fs, ls ] ) => {
    const token = ancho <= ANGOSTO && BAJA_UN_PELDANO[ fs ] ? BAJA_UN_PELDANO[ fs ] : fs;
    return { sel, tam: px( token ), tracking: em( ls ) * px( token ) };
  } );

const fallos = await enLaPagina( async ( pagina, ancho ) =>
  pagina.evaluate( ( piezas ) => {
    const malos = [];
    for ( const { sel, tam, tracking } of piezas ) {
      const el = document.querySelector( sel );
      if ( ! el ) { malos.push( `no hay ningún ${ sel } en la página` ); continue; }

      const s = getComputedStyle( el );
      const tamReal = parseFloat( s.fontSize );
      // «normal» es cero de tracking, y es lo que devuelve quien no lo declara.
      const trackingReal = s.letterSpacing === 'normal' ? 0 : parseFloat( s.letterSpacing );

      if ( Math.abs( tamReal - tam ) > 0.5 ) {
        malos.push( `${ sel } mide ${ tamReal }px y su peldaño dice ${ tam }px` );
      }
      if ( Math.abs( trackingReal - tracking ) > 0.05 ) {
        malos.push( `${ sel } lleva ${ trackingReal.toFixed( 2 ) }px de tracking y su peldaño dice ${ tracking.toFixed( 2 ) }px` );
      }
    }
    return malos;
  }, esperadoEn( ancho ) ),
  { anchos: [ 390, 1200 ], soloBase: true }
);

/**
 * Y ningún titular grande del sitio se queda sin peldaño.
 *
 * Al borrar la regla general de h1 y h2, todo título que dependía solo de ella
 * se quedó en cero de tracking. Uno lo hizo, y solo se vio abriendo la página:
 * el titular de preguntas frecuentes, que se estilaba en el atributo style.
 */
const RUTAS = [ '/', '/preguntas-frecuentes/', '/login/', '/privacidad/', '/terminos/' ];
const origen = new URL( BASE() ).origin;
const navegador = await chromium.launch();
const pagina = await navegador.newPage( { viewport: { width: 1280, height: 900 } } );

for ( const ruta of RUTAS ) {
  const r = await pagina.goto( origen + ruta, { waitUntil: 'domcontentloaded' } ).catch( () => null );
  if ( ! r || ! r.ok() ) continue;

  const sueltos = await pagina.evaluate( () => {
    const malos = [];
    for ( const el of document.querySelectorAll( 'h1, h2, h3' ) ) {
      const s = getComputedStyle( el );
      const px = parseFloat( s.fontSize );
      const ls = s.letterSpacing === 'normal' ? 0 : parseFloat( s.letterSpacing );
      const texto = ( el.textContent || '' ).trim().slice( 0, 30 );
      if ( px >= 22 && ls === 0 && texto ) malos.push( `${ el.tagName } de ${ px }px sin tracking: «${ texto }»` );
    }
    return [ ...new Set( malos ) ];
  } );
  for ( const s of sueltos ) fallos.push( `${ ruta } tiene un ${ s }` );
}

await navegador.close();

informar( 'tipografia', fallos );
