/**
 * Que no quede ni un color escrito a mano fuera de la paleta.
 *
 * Es la regla que más fácil se rompe: escribir #fef3c7 al lado del padding es
 * más rápido que buscar el papel. Y se rompe en silencio, porque una pantalla
 * con un ámbar suelto no se ve mal, se ve vieja.
 *
 * Antes de esta comprobación había 187 colores a mano solo en este
 * repositorio, repartidos en 57 valores: tres familias enteras que no seguían
 * a ningún token y que se quedaron en el mundo viejo cuando cambió la paleta.
 */

import { readdirSync, statSync } from 'node:fs';
import { join, relative } from 'node:path';
import { TEMA, informar, leer, sinComentarios, tokensDelSistema } from '../comun.mjs';

const fallos = [];

/** Los valores que el sistema declara. Lo demás es un color inventado. */
const mapa = Object.fromEntries( tokensDelSistema().flatMap( ( g ) => g.tokens.map( ( t ) => [ t.nombre, t.valor ] ) ) );
const DE_LA_PALETA = new Set(
  Object.values( mapa ).filter( ( v ) => /^#[0-9a-fA-F]{3,8}$/.test( v.trim() ) ).map( ( v ) => v.trim().toUpperCase() )
);

// El .js entra desde que se encontro que network.js pintaba los puntos de la
// portada con el azul, el cian y el verde de la paleta vieja, meses despues
// del repintado: el barrido de entonces solo miraba hojas de estilo.
const EXTENSIONES = [ '.php', '.css', '.js' ];
const FUERA = [ 'node_modules', '.git', 'pruebas', 'assets/img' ];

function archivos( carpeta = '' ) {
  const salida = [];
  for ( const nombre of readdirSync( join( TEMA, carpeta ) ) ) {
    const ruta = carpeta ? `${ carpeta }/${ nombre }` : nombre;
    if ( FUERA.some( ( f ) => ruta.startsWith( f ) ) ) continue;
    if ( statSync( join( TEMA, ruta ) ).isDirectory() ) salida.push( ...archivos( ruta ) );
    else if ( EXTENSIONES.some( ( e ) => nombre.endsWith( e ) ) ) salida.push( ruta );
  }
  return salida;
}

for ( const archivo of archivos() ) {
  let fuente = sinComentarios( leer( archivo ) );

  // El :root es donde los valores viven: ahí sí se escriben.
  if ( archivo.endsWith( 'style.css' ) ) {
    const i = fuente.indexOf( ':root' );
    if ( i >= 0 ) fuente = fuente.slice( 0, i ) + fuente.slice( fuente.indexOf( '\n}', i ) );
  }

  fuente.split( '\n' ).forEach( ( linea, i ) => {
    if ( linea.includes( 'color ajeno' ) ) return;

    // Un color tambien se escribe a mano en rgba() y en hsl(), y por ahi
    // sobrevivio la paleta vieja al repintado: 62 valores en sombras,
    // degradados y veladuras, donde esta regla no estaba mirando. Lo
    // translucido se escribe con color-mix() sobre un token, que lee el valor
    // en vez de copiarlo.
    const fn = linea.match( /\b(?:rgba?|hsla?)\(\s*[0-9.]/ );
    if ( fn ) {
      const nombre = fn[ 0 ].match( /[a-z]+/ )[ 0 ];
      fallos.push( `${ relative( '.', archivo ) }:${ i + 1 } escribe un color en ${ nombre }(): lo translúcido se escribe con color-mix() sobre un token` );
      return;
    }

    const m = linea.match( /#[0-9a-fA-F]{3,8}\b/ );
    if ( ! m ) return;
    const valor = m[ 0 ].toUpperCase();
    const corto = valor.length === 4;
    if ( ! DE_LA_PALETA.has( valor ) && ! corto ) {
      fallos.push( `${ relative( '.', archivo ) }:${ i + 1 } escribe ${ m[ 0 ] }, que no está en la paleta` );
    } else {
      fallos.push( `${ relative( '.', archivo ) }:${ i + 1 } escribe ${ m[ 0 ] } a mano: el valor va en el :root y la pantalla nombra el papel` );
    }
  } );
}

informar( 'colores', fallos );
