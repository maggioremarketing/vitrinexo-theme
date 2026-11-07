/**
 * Que el tracking salga siempre de un peldaño.
 *
 * Es la regla que más fácil se rompe, porque escribir -0.03em al lado del
 * font-size es más rápido que buscar el token. Y se rompe en silencio: nadie
 * revisa el tracking de una pantalla que ya estaba hecha.
 *
 * Antes de esta comprobación había 66 valores a mano y una regla general que
 * apretaba todo h1 y h2 un 7% sin mirar el tamaño.
 */

import { readdirSync, statSync } from 'node:fs';
import { join, relative } from 'node:path';
import { TEMA, informar, leer, sinComentarios, tokensDelSistema } from '../comun.mjs';

const fallos = [];

const peldanos = tokensDelSistema()
  .flatMap( ( g ) => g.tokens.map( ( t ) => t.nombre ) )
  .filter( ( n ) => n.startsWith( '--ls-' ) );

if ( peldanos.length < 8 ) fallos.push( `solo hay ${ peldanos.length } peldaños de tracking declarados` );

const css = sinComentarios( leer( 'assets/css/style.css' ) );
for ( const [ , valor ] of css.matchAll( /letter-spacing:\s*([^;}]+)/g ) ) {
  const v = valor.trim();
  if ( ! v.startsWith( 'var(--ls-' ) ) fallos.push( `style.css escribe letter-spacing: ${ v } a mano` );
  else {
    const nombre = v.match( /var\(\s*(--[\w-]+)/ )?.[ 1 ];
    if ( nombre && ! peldanos.includes( nombre ) ) fallos.push( `style.css usa ${ nombre }, que no existe en la escala` );
  }
}

// La hoja del manual, por lo mismo.
const manual = sinComentarios( leer( 'assets/css/marca.css' ) );
for ( const [ , valor ] of manual.matchAll( /letter-spacing:\s*([^;}]+)/g ) ) {
  const v = valor.trim();
  if ( ! v.startsWith( 'var(--ls-' ) && v !== 'normal' ) fallos.push( `marca.css escribe letter-spacing: ${ v } a mano` );
}

/**
 * Y el CSS que no vive en una hoja.
 *
 * page.php llevaba en el head una copia entera de los estilos de las páginas
 * legales, con la fuente y cuatro tracking escritos a mano, y era la que de
 * verdad pintaba la página: la versión sistematizada se había renombrado y
 * llevaba meses sin aplicarse. Esta comprobación miraba dos archivos .css, así
 * que no vio ninguno de los cuatro.
 *
 * Hoy el tema no tiene CSS fuera de assets/css. Esto es lo que hace que siga
 * siendo verdad.
 */
// banco/ queda fuera por lo mismo que en colores: su enlace al tema vuelve acá.
const FUERA = [ 'node_modules', '.git', 'assets/css', 'assets/img', 'assets/fonts', 'pruebas', 'banco' ];
const EXT = [ '.php', '.js' ];

function archivos( carpeta = '' ) {
  const salida = [];
  for ( const nombre of readdirSync( join( TEMA, carpeta ) ) ) {
    const ruta = carpeta ? `${ carpeta }/${ nombre }` : nombre;
    if ( FUERA.some( ( f ) => ruta.startsWith( f ) ) ) continue;
    if ( statSync( join( TEMA, ruta ) ).isDirectory() ) salida.push( ...archivos( ruta ) );
    else if ( EXT.some( ( e ) => nombre.endsWith( e ) ) ) salida.push( ruta );
  }
  return salida;
}

for ( const archivo of archivos() ) {
  const fuente = sinComentarios( leer( archivo ) );
  for ( const [ , valor ] of fuente.matchAll( /letter-spacing:\s*([^;}"'`]+)/g ) ) {
    const v = valor.trim();
    if ( v.startsWith( 'var(--ls-' ) || v === 'normal' || v === 'inherit' ) continue;
    // El manual arma el valor con PHP: letter-spacing:var(' . $token . ')'. El
    // token sale de la escala y la comprobación de tokens ya lo mira; lo que
    // acá se persigue es el número escrito a mano, no una concatenación.
    if ( v === 'var(' ) continue;
    fallos.push( `${ relative( '.', archivo ) } escribe letter-spacing: ${ v } a mano, fuera de una hoja de estilos` );
  }
}

informar( 'tracking', fallos );
