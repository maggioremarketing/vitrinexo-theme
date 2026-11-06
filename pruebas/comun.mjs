/**
 * Lo que todas las comprobaciones necesitan: dónde está el tema, cómo se lee
 * un archivo y cómo se informa un fallo.
 *
 * Un fallo tiene que decir cuál es el problema, no solo que lo hay. Una prueba
 * que falla sin nombrar qué cuesta más de lo que ahorra.
 */

import { readFileSync, readdirSync } from 'node:fs';
import { join } from 'node:path';
import { fileURLToPath } from 'node:url';

// fileURLToPath y no url.pathname: la ruta del tema tiene espacios, y pathname
// los devuelve como %20. Una ruta que existe y no se puede abrir cuesta una
// tarde de buscar el archivo equivocado.
export const TEMA = fileURLToPath( new URL( '..', import.meta.url ) );

export const leer = ( ruta ) => readFileSync( join( TEMA, ruta ), 'utf8' );

/** Los archivos que componen el manual. Lo que se mide es esto, no el tema entero. */
export const ARCHIVOS_MANUAL = [
  'templates/page-marca.php',
  'assets/css/marca.css',
  'assets/js/marca.js',
  ...readdirSync( join( TEMA, 'inc/marca' ) ).map( ( f ) => `inc/marca/${ f }` ),
];

/** El CSS sin comentarios: un comentario que nombra un primitivo no lo usa. */
export const sinComentarios = ( css ) => css.replace( /\/\*[\s\S]*?\*\//g, '' );

/** Las rampas de primitivos, leídas de donde el manual las declara. */
export function gruposPrimitivos() {
  const php = leer( 'inc/marca/papeles.php' );
  const m = php.match( /function vx_marca_grupos_primitivos\(\): array \{\s*return \[([^\]]+)\]/ );
  if ( ! m ) throw new Error( 'no encontré vx_marca_grupos_primitivos en papeles.php' );
  return [ ...m[ 1 ].matchAll( /'([^']+)'/g ) ].map( ( x ) => x[ 1 ] );
}

/**
 * Los tokens del :root, agrupados por el comentario que los titula. Es el
 * mismo recorrido que hace inc/marca/tokens.php; si los dos dejaran de
 * coincidir, la prueba mediría algo distinto de lo que la página pinta.
 */
export function tokensDelSistema() {
  const css = leer( 'assets/css/style.css' );
  const raiz = css.match( /:root\s*\{([\s\S]*?)\n\}/ );
  if ( ! raiz ) throw new Error( 'no encontré el bloque :root en style.css' );

  const grupos = [];
  let actual = { titulo: '', tokens: [] };

  // El mismo criterio que inc/marca/tokens.php: un comentario al final de una
  // línea explica el valor de al lado y no abre familia. Si los dos lectores
  // dejaran de coincidir, la prueba mediría grupos que la página no pinta.
  const bloque = raiz[ 1 ].replace( /(?<=\S)[ \t]*\/\*[\s\S]*?\*\//g, '' );

  for ( const parte of bloque.split( /(\/\*[^*]*\*\/)/ ) ) {
    const comentario = parte.trim().match( /^\/\*\s*(.+?)\s*\*\/$/s );
    if ( comentario ) {
      if ( actual.tokens.length ) grupos.push( actual );
      actual = { titulo: comentario[ 1 ].split( '\n' )[ 0 ].trim(), tokens: [] };
      continue;
    }
    for ( const [ , nombre, valor ] of parte.matchAll( /--([\w-]+)\s*:\s*([^;}]+)/g ) ) {
      actual.tokens.push( { nombre: `--${ nombre }`, valor: valor.trim() } );
    }
  }
  if ( actual.tokens.length ) grupos.push( actual );
  return grupos;
}

/** Un valor resuelto hasta el literal, saltando los var() encadenados. */
export function resolver( valor, mapa, saltos = 8 ) {
  const v = String( valor ).trim();
  const ref = v.match( /^var\(\s*(--[\w-]+)\s*(?:,[^)]*)?\)$/ );
  if ( ! ref || saltos === 0 ) return v;
  return mapa[ ref[ 1 ] ] ? resolver( mapa[ ref[ 1 ] ], mapa, saltos - 1 ) : v;
}

/** El informe de una comprobación. Sale 1 si algo falló, para la cadena. */
export function informar( nombre, fallos ) {
  if ( ! fallos.length ) {
    console.log( `  ✓ ${ nombre }` );
    process.exit( 0 );
  }
  console.log( `  ✕ ${ nombre }` );
  for ( const f of fallos ) console.log( `      ${ f }` );
  process.exit( 1 );
}
