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
export const RUTAS = [ '/', '/preguntas-frecuentes/', '/login/', '/privacidad/', '/terminos/', '/landing-4dinner/' ];

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
  // El perfil lleva slug. `/perfil/` a secas no es la pantalla: es la frase
  // «Perfil no encontrado», que es lo que midió la suite desde el primer día.
  '/dashboard/', '/perfil/ana-reyes/', '/editar-perfil/', '/directorio/', '/conexiones/',
  '/favoritos/', '/notificaciones/', '/configuracion/', '/nexos/', '/mis-eventos/',
  '/mis-publicaciones/', '/feed/', '/ayuda/',
  // Las cuatro que faltaban, y son justo las que nombran las piezas de la línea:
  // la página del evento y las tres comunidades. Mientras no estuvieron, la
  // regla de la marca madre se podía medir en verde sin abrir ni una sola
  // pantalla que la rompiera.
  '/4dinner/', '/comunidad-out2b/', '/comunidad-woman/', '/comunidad-senior/',
  // /onboarding/ no está, y conviene decir por qué: la sesión del banco ya
  // terminó el onboarding, así que esa ruta redirige al panel y medirla sería
  // medir el panel dos veces con otro nombre. El paso 5, donde se eligen las
  // comunidades, hoy no lo mide nadie.
];

export const SESION = process.env.VX_SESION || '';

/**
 * Que la sesión siga viva, y que se note cuando no.
 *
 * Esta es la trampa más cara del banco y no se ve venir: `npm run banco`
 * vuelve a poner la clave de cada miembro con `wp_set_password`, y eso
 * invalida todas las sesiones abiertas. El `sesion.json` de antes sigue ahí,
 * Playwright lo carga sin quejarse, WordPress devuelve **200** y la ruta de
 * miembro dibuja la página de visitante: un pie público de cuatrocientos
 * caracteres. Las catorce pantallas pasan en verde sin haberse abierto.
 *
 * Por eso no alcanza con mirar el código de respuesta. WordPress le pone
 * `logged-in` al body cuando hay sesión, así que se pregunta por eso, y si no
 * está se falla en vez de informar: un banco roto que pasa en verde es peor
 * que un banco caído.
 *
 * Devuelve `true` si hay sesión, `false` si la página es la de visitante y
 * `null` si no se puede saber, que es el caso de las pocas plantillas que no
 * llaman a `body_class()`. Callar es mejor que inventar: se falla con el
 * `false`, no con la ausencia de dato.
 */
export const receta = ( rutas ) => {
  const donde = rutas.length > 3
    ? `${ rutas.length } rutas (${ rutas.slice( 0, 3 ).join( ', ' ) }…)`
    : rutas.join( ', ' );
  return `se midió la página de visitante en ${ donde }: la sesión del banco ya no sirve. `
    + 'Se rehace con `npm run banco:sesion` (montar el banco repone las claves y eso invalida la sesión guardada).';
};

export const estaDentro = ( pagina ) => pagina.evaluate( () => {
  const clases = document.body.className.trim();
  if ( ! clases ) return null;
  return clases.split( /\s+/ ).includes( 'logged-in' );
} );

/** Las rutas a recorrer, con el origen de BASE y el propio camino de BASE. */
export function rutas() {
  const u = new URL( BASE() );
  const lista = [ u.pathname, ...RUTAS, ...( SESION ? RUTAS_MIEMBRO : [] ) ];
  return [ ...new Set( lista ) ].map( ( r ) => u.origin + r );
}

/**
 * Cada visita a hacer: una ruta y si se abre con la sesión puesta o sin ella.
 *
 * Las públicas se abren SIEMPRE sin sesión, porque así las ve cualquiera, y
 * además con sesión cuando la hay, porque varias cambian por completo: la
 * portada deja de ser la portada y pasa a ser el home del miembro, y el pie
 * cambia sus enlaces de marketing por los del área privada. Las de miembro
 * solo con sesión: sin ella devuelven el login y medirlo no dice nada.
 *
 * Esto estaba al revés y costó caro. La suite corre siempre con la sesión del
 * banco puesta, así que durante toda su vida no abrió ni una sola vez la
 * portada pública: trece enlaces rotos y un texto a 4,36:1 pasaron el banco en
 * verde y los encontró producción. Una comprobación que no abre lo que afirma
 * medir es peor que no tenerla.
 */
export function visitas() {
  const publicas = rutas().filter( ( u ) => ! RUTAS_MIEMBRO.includes( new URL( u ).pathname ) );
  const miembro  = rutas().filter( ( u ) => RUTAS_MIEMBRO.includes( new URL( u ).pathname ) );
  return [
    ...publicas.map( ( url ) => ( { url, conSesion: false } ) ),
    ...( SESION ? publicas.map( ( url ) => ( { url, conSesion: true } ) ) : [] ),
    ...miembro.map( ( url ) => ( { url, conSesion: true } ) ),
  ];
}

/**
 * Abre la página y se la pasa a la medición. La ventana se estira al alto del
 * documento: elementFromPoint solo responde dentro de lo visible, y una página
 * larga medida en una ventana corta deja sin medir todo lo que no se ve. Esa
 * es la trampa que hizo que una suite midiera el 24% de la página.
 *
 * Recorre lo que devuelve `visitas()`: las públicas sin sesión y, cuando hay,
 * también con ella; las de miembro solo con sesión. La etiqueta del fallo dice
 * cuál de las dos, porque la misma ruta con y sin sesión son dos páginas.
 */
export async function enLaPagina( medir, { anchos = [ 1200 ], alto = 900, soloBase = false } = {} ) {
  const navegador = await chromium.launch();
  const fallos = [], mudas = [];
  const afuera = [];
  const destinos = soloBase ? [ { url: BASE(), conSesion: !! SESION } ] : visitas();

  try {
    for ( const { url, conSesion } of destinos ) {
      const camino = new URL( url ).pathname + ( conSesion && SESION ? ' · con sesión' : '' );
      for ( const ancho of anchos ) {
        const contexto = await navegador.newContext( {
          viewport: { width: ancho, height: alto },
          ...( SESION && conSesion ? { storageState: SESION } : {} ),
        } );
        const pagina = await contexto.newPage();
        const r = await pagina.goto( url, { waitUntil: 'networkidle' } ).catch( () => null );
        if ( ! r || ! r.ok() ) {
          if ( ! mudas.includes( camino ) ) mudas.push( camino );
          await contexto.close();
          continue;
        }

        if ( SESION && conSesion && false === await estaDentro( pagina ) && ! afuera.includes( camino ) ) afuera.push( camino );

        const altoDoc = await pagina.evaluate( () => document.documentElement.scrollHeight );
        await pagina.setViewportSize( { width: ancho, height: Math.min( altoDoc, 30000 ) } );

        for ( const f of await medir( pagina, ancho ) ) fallos.push( `[${ camino } · ${ ancho }px] ${ f }` );
        await contexto.close();
      }
    }
  } finally {
    await navegador.close();
  }

  if ( afuera.length ) fallos.unshift( receta( afuera ) );
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
