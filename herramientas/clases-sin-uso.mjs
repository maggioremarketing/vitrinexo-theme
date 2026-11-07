/**
 * Qué clases define style.css que ningún markup escribe.
 *
 *   npm run clases-sin-uso
 *
 * Informa, no falla. Una clase sin uso no rompe nada, y la medición tiene un
 * margen que conviene conocer antes de borrar nada con ella en la mano:
 *
 *  - El markup no está solo en el código. También hay HTML guardado en las
 *    páginas de WordPress, así que se bajan de /wp-json/wp/v2/pages y entran
 *    al corpus. La primera vez que se hizo no rescataron ninguna clase, pero
 *    eso es un resultado, no una suposición.
 *  - Hay clases que el PHP arma por pedazos, `community-header-vx--<?php …>`.
 *    Se detectan los prefijos y se da por viva cualquier clase que empiece por
 *    uno. Sin esta regla la primera corrida declaró muertas tres variantes de
 *    comunidad que están en uso.
 *  - Si alguna clase de una familia vive, la familia entera se respeta: un
 *    modificador que nadie escribe suele venir armado desde algún sitio.
 *  - Lo que no puede ver: una página que solo exista detrás de una sesión
 *    iniciada y que traiga HTML escrito a mano. El área de miembros se
 *    renderiza con shortcodes, que sí están en el código, así que el hueco es
 *    estrecho, pero existe.
 *
 * Y un hallazgo que no es código muerto: una clase que el CSS estiliza y nadie
 * escribe, al lado de una que el markup escribe y nadie estiliza, suele ser un
 * nombre que se desvió. Ahí la regla no sobra, dejó de aplicarse. Pasó con
 * .vx-legal-body contra .vx-legal-content.
 */
import { readdirSync, statSync, readFileSync, existsSync, writeFileSync } from 'node:fs';
import { join } from 'node:path';
import { TEMA } from '../pruebas/comun.mjs';

const PLUGIN = join( TEMA, '../vitrinexo-core' );
const CACHE  = join( TEMA, '../.wp-paginas.html' );
const FUERA  = [ 'node_modules', '.git', 'vendor' ];
const EXT    = [ '.php', '.js', '.html', '.json', '.md', '.twig' ];

function archivos( dir, salida = [] ) {
  for ( const n of readdirSync( dir ) ) {
    if ( FUERA.includes( n ) ) continue;
    const r = join( dir, n );
    if ( statSync( r ).isDirectory() ) archivos( r, salida );
    else if ( EXT.some( ( e ) => n.endsWith( e ) ) ) salida.push( r );
  }
  return salida;
}

/** Las páginas de WordPress, ya renderizadas. Se cachean para no pedirlas siempre. */
async function desdeWordPress() {
  if ( existsSync( CACHE ) ) return readFileSync( CACHE, 'utf8' );
  try {
    const r = await fetch( 'https://vitrinexo.com/wp-json/wp/v2/pages?per_page=100&_fields=content' );
    const html = ( await r.json() ).map( ( p ) => p.content?.rendered ?? '' ).join( '\n' );
    writeFileSync( CACHE, html );
    return html;
  } catch {
    console.log( '  (no se pudieron bajar las páginas de WordPress: la medición mira solo el código)\n' );
    return '';
  }
}

const fuentes = [ ...( existsSync( PLUGIN ) ? archivos( PLUGIN ) : [] ), ...archivos( TEMA ) ]
  .filter( ( f ) => ! f.endsWith( 'style.css' ) );
if ( ! existsSync( PLUGIN ) ) console.log( '  (el plugin no está al lado: la medición mira solo el tema)\n' );

const corpus = fuentes.map( ( f ) => readFileSync( f, 'utf8' ) ).join( '\n' ) + '\n' + await desdeWordPress();
const css = readFileSync( join( TEMA, 'assets/css/style.css' ), 'utf8' ).replace( /\/\*[\s\S]*?\*\//g, ' ' );

const PREFIJOS = [ ...new Set( [ ...corpus.matchAll(
  /([a-z][\w-]*?-{1,2})(?:<\?php|\$\{|' ?\. ?\$|" ?\+ ?)/g ) ].map( ( m ) => m[ 1 ] ) ) ]
  .filter( ( p ) => p.length > 3 );

const todas = [ ...new Set( [ ...css.matchAll( /\.(-?[_a-zA-Z][\w-]*)/g ) ].map( ( m ) => m[ 1 ] ) ) ];
const viva  = ( c ) => new RegExp( `(^|[^\\w-])${ c }([^\\w-]|$)` ).test( corpus )
                    || PREFIJOS.some( ( p ) => c.startsWith( p ) );

const vivas = new Set( todas.filter( viva ) );
const familia = ( c ) => c.split( /--|__/ )[ 0 ];
const familiasVivas = new Set( [ ...vivas ].map( familia ) );
const muertas = todas.filter( ( c ) => ! vivas.has( c ) && ! familiasVivas.has( familia( c ) ) );

console.log( `clases que el CSS define: ${ todas.length }` );
console.log( `  con uso:  ${ vivas.size }` );
console.log( `  sin uso:  ${ muertas.length }` );
if ( muertas.length ) {
  console.log( '\n' + muertas.map( ( c ) => '  .' + c ).join( '\n' ) );
  console.log( '\nAntes de borrar, leer la cabecera de este archivo.' );
}
