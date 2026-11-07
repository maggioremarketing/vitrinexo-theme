/**
 * Inicia sesión en el WordPress del banco y guarda el estado.
 *
 *   npm run banco:sesion
 *
 * Son tres, y ninguna sobra. La de ana abre las diecisiete pantallas del área
 * de miembros. La de gabriela abre una sola, /onboarding/, porque el plugin
 * retiene ahí a quien no lo terminó y manda al panel a quien sí. La de pruebas
 * abre el escritorio de WordPress, donde la copia también es nuestra.
 *
 * Es 127.0.0.1 y los usuarios son inventados, con una clave que generó
 * banco/montar.sh y que vive solo en banco/wp/clave.txt.
 */
import { readFileSync } from 'node:fs';
import { chromium } from 'playwright';

const dir = 'banco';
const clave = readFileSync( `${ dir }/wp/clave.txt`, 'utf8' ).trim();

const navegador = await chromium.launch();

/** Entra como `usuario` y deja el estado en `archivo`. Devuelve si entró. */
async function entrar( usuario, archivo ) {
  const contexto = await navegador.newContext();
  const pagina = await contexto.newPage();

  await pagina.goto( 'http://127.0.0.1:8910/wp-login.php', { waitUntil: 'domcontentloaded' } );
  await pagina.fill( '#user_login', usuario );
  await pagina.fill( '#user_pass', clave );
  await Promise.all( [
    pagina.waitForNavigation( { waitUntil: 'domcontentloaded' } ),
    pagina.click( '#wp-submit' ),
  ] );

  const dentro = ! pagina.url().includes( 'wp-login.php' );
  console.log( dentro
    ? `  ${ usuario }: sesión iniciada → ${ pagina.url().replace( 'http://127.0.0.1:8910', '' ) }`
    : `  ${ usuario }: no entró: ¿está levantado el banco? (npm run banco:servir)` );

  await contexto.storageState( { path: `${ dir }/wp/${ archivo }` } );
  await contexto.close();
  return dentro;
}

const ana = await entrar( 'ana', 'sesion.json' );
const nueva = await entrar( 'gabriela', 'sesion-onboarding.json' );
// Y la del escritorio, que es otra interfaz y otro permiso: ana es miembro y
// wp-admin le devuelve el panel del producto, no el de WordPress.
const admin = await entrar( 'pruebas', 'sesion-admin.json' );

await navegador.close();
process.exit( ana && nueva && admin ? 0 : 1 );
