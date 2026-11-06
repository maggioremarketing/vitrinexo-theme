/**
 * Que el manual siga siendo verdad sobre sí mismo.
 *
 * Tres cosas se comprueban acá, y las tres protegen contra la misma forma de
 * decadencia: que el manual siga diciendo algo que el código ya no hace.
 *
 *  1. Toda comprobación corre. Una que existe y no corre es peor que no
 *     tenerla, porque da tranquilidad sin dar nada.
 *  2. El registro de decisiones dice la verdad. Cuando una decisión movió un
 *     token, deja escrito su valor de hoy, y acá se compara con el real. Un
 *     registro desincronizado se cree igual, y por eso hace más daño que no
 *     existir.
 *  3. Un criterio no se declara definido sin estar escrito. «Definido» es una
 *     afirmación sobre el manual, no sobre la intención de alguien.
 */

import { readdirSync } from 'node:fs';
import { join } from 'node:path';
import { comprobaciones } from '../catalogo.mjs';
import { TEMA, informar, leer, resolver, tokensDelSistema } from '../comun.mjs';

const fallos = [];

// 1. Ninguna comprobación queda fuera de la cadena.
const encontradas = comprobaciones();
const enCarpetas = [ 'sistema', 'pantalla' ].flatMap( ( c ) =>
  readdirSync( join( TEMA, 'pruebas', c ) ).filter( ( f ) => f.endsWith( '.mjs' ) ).map( ( f ) => `${ c }/${ f }` )
);
const corren = [ ...encontradas.sistema, ...encontradas.pantalla ];
for ( const archivo of enCarpetas ) {
  if ( ! corren.includes( archivo ) ) fallos.push( `la comprobación ${ archivo } existe y no corre` );
}
// Y ningún archivo de prueba vive fuera de las dos carpetas, donde nadie lo buscaría.
for ( const suelto of readdirSync( join( TEMA, 'pruebas' ) ) ) {
  if ( suelto.endsWith( '.mjs' ) && ! [ 'catalogo.mjs', 'comun.mjs', 'comun-pantalla.mjs', 'verificar.mjs', 'accesibilidad.mjs', 'avance.mjs' ].includes( suelto ) ) {
    fallos.push( `pruebas/${ suelto } no está en sistema/ ni en pantalla/, así que no lo corre nadie` );
  }
}

// 2. El registro de decisiones, contra el sistema real.
const mapa = Object.fromEntries( tokensDelSistema().flatMap( ( g ) => g.tokens.map( ( t ) => [ t.nombre, t.valor ] ) ) );
const registro = leer( 'inc/marca/decisiones.php' );

/**
 * Una decisión superada guarda el valor que el token tuvo mientras rigió, no el
 * de hoy. Compararla obligaría a reescribir la historia cada vez que algo
 * cambia, y un registro que se reescribe solo deja de ser un registro.
 */
const decisiones = registro.split( /\n        \[\n/ ).slice( 1 );

for ( const decision of decisiones ) {
  const estado = decision.match( /'estado'\s*=>\s*'([^']+)'/ )?.[ 1 ] ?? '';
  if ( estado === 'superada' ) continue;

  const bloque = decision.match( /'tokens'\s*=>\s*\[([^\]]*)\]/ )?.[ 1 ] ?? '';
  for ( const [ , nombre, escrito ] of bloque.matchAll( /'(--[\w-]+)'\s*=>\s*'([^']+)'/g ) ) {
    const real = mapa[ nombre ];
    if ( real === undefined ) {
      fallos.push( `el registro nombra ${ nombre }, que el CSS ya no declara` );
    } else if ( real.trim() !== escrito.trim() && resolver( real, mapa ) !== resolver( escrito, mapa ) ) {
      fallos.push( `el registro dice que ${ nombre } vale ${ escrito }, y vale ${ real }` );
    }
  }
}

// 3. Un criterio definido tiene bloque escrito, y ningún bloque escrito queda
//    fuera de la lista de criterios.
const criterios = leer( 'inc/marca/criterios.php' );
const contenido = leer( 'inc/marca/contenido.php' );

const bloques = new Map();
for ( const [ , id, cuerpo ] of contenido.matchAll( /vx_marca_bloque\( '([^']+)', function \(\) \{([\s\S]*?)\n    \} \);/g ) ) {
  bloques.set( id, cuerpo );
}

const ancla = ( n ) => n.normalize( 'NFD' ).replace( /[̀-ͯ]/g, '' ).toLowerCase().replace( /[^a-z0-9]+/g, '-' ).replace( /^-|-$/g, '' );

let parte = '';
for ( const linea of criterios.split( '\n' ) ) {
  const p = linea.match( /'id'\s*=>\s*'([^']+)'/ );
  if ( p ) parte = p[ 1 ];
  const c = linea.match( /\[ '([^']+)', '(definido|parcial|pendiente)'/ );
  if ( ! c || ! parte ) continue;

  const id = `${ parte }-${ ancla( c[ 1 ] ) }`;
  const cuerpo = bloques.get( id );

  if ( cuerpo === undefined ) {
    fallos.push( `el criterio ${ id } no tiene bloque en contenido.php` );
    continue;
  }
  if ( c[ 2 ] === 'definido' && cuerpo.includes( 'vx_marca_pendiente' ) ) {
    fallos.push( `el criterio ${ id } se declara definido y su bloque todavía dice pendiente` );
  }
  if ( c[ 2 ] !== 'definido' && ! cuerpo.includes( 'vx_marca_pendiente' ) ) {
    fallos.push( `el criterio ${ id } está ${ c[ 2 ] } y su bloque no dice qué falta` );
  }
  bloques.delete( id );
}
for ( const huerfano of bloques.keys() ) {
  fallos.push( `contenido.php escribe el bloque ${ huerfano }, que no es un criterio` );
}

informar( 'gobernanza', fallos );
