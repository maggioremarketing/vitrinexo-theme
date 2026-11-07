/**
 * Lo que comparten las comprobaciones que abren el navegador.
 *
 * Todas miden la misma página en los mismos anchos, así que abrir el navegador
 * y llevar la cuenta de los fallos se escribe una vez.
 */

import { chromium } from 'playwright';

/** Los anchos donde el documento tiene que sostenerse. */
export const ANCHOS = [ 390, 700, 1200, 1600 ];

export const BASE = () => process.argv[ 2 ] ?? 'https://vitrinexo.com/marca';

/**
 * Las páginas públicas del producto, además de la del manual.
 *
 * Siete de las diez comprobaciones de pantalla medían solo la URL que se les
 * pasaba, o sea el manual, y tres recorrían el sitio con su propia lista
 * copiada. Así, el contraste y el área de toque se verificaban en la página
 * que describe las reglas y no en las pantallas que las aplican: los tres
 * botones con tinta blanca sobre pastel aparecieron a mano, buscando otra
 * cosa, y ninguna prueba los había visto nunca.
 *
 * La que no responde no se mide, y se dice cuál: un banco local solo sirve
 * /marca, y dar por buena una página que no se abrió es la peor forma de
 * pasar en verde.
 */
export const RUTAS = [ '/', '/preguntas-frecuentes/', '/login/', '/privacidad/', '/terminos/' ];

/**
 * El área de miembros, que solo existe detrás de una sesión.
 *
 * Se mide cuando VX_SESION apunta a un estado de sesión de Playwright. Eso
 * solo pasa contra el WordPress local del banco: en producción no hay sesión
 * y estas rutas devuelven el login, así que no se piden.
 *
 * Hasta que existió ese banco, catorce pantallas del producto no las había
 * medido nunca nadie. No porque fueran difíciles: porque hacía falta estar
 * dentro, y las pruebas siempre miraron desde fuera.
 */
export const RUTAS_MIEMBRO = [
  '/dashboard/', '/perfil/', '/editar-perfil/', '/directorio/', '/conexiones/',
  '/favoritos/', '/notificaciones/', '/configuracion/', '/nexos/', '/mis-eventos/',
  '/mis-publicaciones/', '/feed/', '/ayuda/',
];

export const SESION = process.env.VX_SESION || '';

/**
 * Las rutas a recorrer, con el origen de BASE y el propio camino de BASE.
 *
 * Con `sinSesion` se queda solo con las públicas: las de miembro sin sesión
 * devuelven el login y medirlo no dice nada.
 */
export function rutas( { sinSesion = false } = {} ) {
  const u = new URL( BASE() );
  const lista = [ u.pathname, ...RUTAS, ...( SESION && ! sinSesion ? RUTAS_MIEMBRO : [] ) ];
  return [ ...new Set( lista ) ].map( ( r ) => u.origin + r );
}

/**
 * Abre la página y se la pasa a la medición. La ventana se estira al alto del
 * documento: elementFromPoint solo responde dentro de lo visible, y una página
 * larga medida en una ventana corta deja sin medir todo lo que no se ve. Esa
 * es la trampa que hizo que una suite midiera el 24% de la página.
 *
 * `sinSesion` abre las páginas como las ve cualquiera: sin el estado de sesión
 * del banco, y solo las públicas. Hace falta porque media web cambia cuando
 * hay sesión. La portada deja de ser la portada y pasa a ser el home del
 * miembro, y el pie cambia sus enlaces de marketing por los del área privada.
 * La suite corre siempre con la sesión puesta, así que sin esto las reglas
 * sobre lo público no las medía nadie: dos comprobaciones pasaban en verde sin
 * haber abierto nunca lo que afirmaban.
 */
export async function enLaPagina( medir, { anchos = [ 1200 ], alto = 900, soloBase = false, sinSesion = false } = {} ) {
  const navegador = await chromium.launch();
  const fallos = [], mudas = [];
  const destinos = soloBase ? [ BASE() ] : rutas( { sinSesion } );

  try {
    for ( const url of destinos ) {
      const camino = new URL( url ).pathname;
      for ( const ancho of anchos ) {
        const contexto = await navegador.newContext( {
          viewport: { width: ancho, height: alto },
          ...( SESION && ! sinSesion ? { storageState: SESION } : {} ),
        } );
        const pagina = await contexto.newPage();
        const r = await pagina.goto( url, { waitUntil: 'networkidle' } ).catch( () => null );
        if ( ! r || ! r.ok() ) {
          if ( ! mudas.includes( camino ) ) mudas.push( camino );
          await contexto.close();
          continue;
        }

        const altoDoc = await pagina.evaluate( () => document.documentElement.scrollHeight );
        await pagina.setViewportSize( { width: ancho, height: Math.min( altoDoc, 30000 ) } );

        for ( const f of await medir( pagina, ancho ) ) fallos.push( `[${ camino } · ${ ancho }px] ${ f }` );
        await contexto.close();
      }
    }
  } finally {
    await navegador.close();
  }

  if ( mudas.length ) fallos.nota = `no respondieron, no se midieron: ${ mudas.join( ', ' ) }`;
  return fallos;
}

/**
 * El color como lo devuelve el navegador no es el que está escrito: abrevia
 * #FFFFFF a rgb(255, 255, 255) y la translucidez la escribe aparte. Esto lo
 * deja en un hexadecimal de seis dígitos comparable.
 */
export const aHex = ( rgb ) => {
  const n = ( rgb.match( /[\d.]+/g ) ?? [] ).slice( 0, 3 ).map( ( x ) => Math.round( Number( x ) ) );
  return n.length === 3 ? '#' + n.map( ( x ) => x.toString( 16 ).padStart( 2, '0' ) ).join( '' ).toUpperCase() : rgb;
};
