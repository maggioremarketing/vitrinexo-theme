/**
 * Que no haya emojis ni pictogramas en nada que se lea.
 *
 * La marca los prohíbe en todas partes, y una regla así se rompe sola en
 * cuanto alguien pega un rótulo de otro lado. Se mide sobre el copy y no sobre
 * el archivo entero: un → dentro de un comentario de código no lo ve nadie,
 * y prohibirlo ahí solo enseña a ignorar la prueba.
 *
 * Entra también el visto y el aspa, que no son emojis de manual pero hacen el
 * mismo trabajo y se ven distinto en cada sistema operativo. Para eso está
 * Tabler, que se pinta con la tinta que lo rodea.
 */

import { readdirSync, statSync } from 'node:fs';
import { join, relative } from 'node:path';
import { TEMA, informar, leer } from '../comun.mjs';

/** Emoji, dingbats, flechas y símbolos misceláneos. */
const PICTOGRAMAS = /[\u{1F000}-\u{1FAFF}\u{2190}-\u{21FF}\u{2300}-\u{23FF}\u{2600}-\u{27BF}\u{2B00}-\u{2BFF}\u{FE0F}]/u;

/** Una línea que es solo comentario no llega a ninguna pantalla. */
const esComentario = ( linea ) => /^\s*(\/\/|\*|\/\*|#)/.test( linea );

const EXTENSIONES = [ '.php', '.js' ];
// banco/ queda fuera: dentro hay un WordPress entero y su enlace al tema
// vuelve acá, así que recorrerlo no termina.
const FUERA = [ 'node_modules', '.git', 'pruebas', 'banco' ];

function archivos( carpeta = '' ) {
  const salida = [];
  for ( const nombre of readdirSync( join( TEMA, carpeta ) ) ) {
    if ( FUERA.includes( nombre ) ) continue;
    const ruta = join( carpeta, nombre );
    if ( statSync( join( TEMA, ruta ) ).isDirectory() ) salida.push( ...archivos( ruta ) );
    else if ( EXTENSIONES.some( ( e ) => nombre.endsWith( e ) ) ) salida.push( ruta );
  }
  return salida;
}

const fallos = [];
for ( const archivo of archivos() ) {
  leer( archivo ).split( '\n' ).forEach( ( linea, i ) => {
    if ( esComentario( linea ) ) return;
    const m = linea.match( PICTOGRAMAS );
    if ( m ) {
      fallos.push( `${ relative( '.', archivo ) }:${ i + 1 } usa «${ m[ 0 ] }» (U+${ m[ 0 ].codePointAt( 0 ).toString( 16 ).toUpperCase() }): ${ linea.trim().slice( 0, 60 ) }` );
    }
  } );
}

informar( 'emojis', fallos );
