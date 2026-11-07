/**
 * Las comprobaciones que necesitan la página de verdad.
 *
 * Hay cosas que solo se pueden medir renderizadas: el color que quedó debajo
 * del texto, el área que de verdad se puede presionar, el número que se está
 * viendo. La razón calculada entre dos tokens no es la razón real si hay algo
 * translúcido en el medio.
 *
 *   npm run accesibilidad              mide la página publicada
 *   npm run accesibilidad -- URL       mide otra, por ejemplo una local
 */

import { spawnSync } from 'node:child_process';
import { comprobaciones, INFORMAN } from './catalogo.mjs';
import { TEMA } from './comun.mjs';

const BASE = process.argv[ 2 ] ?? 'https://vitrinexo.com/marca';

// Se comprueba que la página esté arriba antes de abrir el navegador: una
// suite entera fallando porque el sitio devolvió 502 no dice nada de la marca.
const r = await fetch( BASE, { redirect: 'follow' } ).catch( () => null );
if ( ! r || ! r.ok ) {
  console.log( `  ✕ la página no respondió en ${ BASE }${ r ? ` (${ r.status })` : '' }` );
  process.exit( 1 );
}

console.log( `Comprobaciones de pantalla sobre ${ BASE }` );

let malas = 0, informadas = 0;
for ( const archivo of comprobaciones().pantalla ) {
  const p = spawnSync( process.execPath, [ `pruebas/${ archivo }`, BASE ], { cwd: TEMA, stdio: 'inherit' } );
  if ( p.status === 0 ) continue;
  if ( INFORMAN.includes( archivo ) ) { informadas++; continue; }
  malas++;
}

if ( informadas ) {
  console.log( `\n  ${ informadas } comprobación${ informadas > 1 ? 'es' : '' } informa sin frenar el despliegue.` );
  console.log( '  Lo que encuentra es real y está anotado en el manual. Ver INFORMAN en pruebas/catalogo.mjs.' );
}

process.exit( malas ? 1 : 0 );
