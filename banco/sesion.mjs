/**
 * Inicia sesión en el WordPress del banco y guarda el estado.
 *
 *   npm run banco:sesion
 *
 * Es 127.0.0.1 y el usuario es inventado, con una clave que generó
 * banco/montar.sh y que vive solo en banco/wp/clave.txt.
 */
import { readFileSync } from 'node:fs';
import { chromium } from 'playwright';

const dir = 'banco';
const clave = readFileSync( `${ dir }/wp/clave.txt`, 'utf8' ).trim();

const navegador = await chromium.launch();
const contexto = await navegador.newContext();
const pagina = await contexto.newPage();

await pagina.goto( 'http://127.0.0.1:8910/wp-login.php', { waitUntil: 'domcontentloaded' } );
await pagina.fill( '#user_login', 'ana' );
await pagina.fill( '#user_pass', clave );
await Promise.all( [
  pagina.waitForNavigation( { waitUntil: 'domcontentloaded' } ),
  pagina.click( '#wp-submit' ),
] );

const dentro = ! pagina.url().includes( 'wp-login.php' );
console.log( dentro ? `  sesión iniciada → ${ pagina.url().replace( 'http://127.0.0.1:8910', '' ) }`
                    : '  no entró: ¿está levantado el banco? (npm run banco:servir)' );

await contexto.storageState( { path: `${ dir }/wp/sesion.json` } );
await navegador.close();
process.exit( dentro ? 0 : 1 );
