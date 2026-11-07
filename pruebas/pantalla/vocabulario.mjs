/**
 * Que las palabras prohibidas no aparezcan en lo que la gente lee, y que cada
 * pieza de la línea lleve la marca madre adelante.
 *
 * Se mide sobre la página renderizada y no sobre el código, porque la regla es
 * sobre el habla de la marca y no sobre los identificadores: preg_match,
 * VX_Matches y $matches[1] son código, no copy, y prohibirlos solo enseñaría a
 * ignorar la prueba.
 *
 * El manual queda fuera: es el único lugar donde las palabras prohibidas
 * tienen que aparecer, porque ahí es donde se prohíben.
 */

import { chromium } from 'playwright';
import { informar } from '../comun.mjs';
import { SESION, visitas, estaDentro, receta } from '../comun-pantalla.mjs';

/** De la propuesta de identidad. Cada una con por qué no se dice. */
const PROHIBIDAS = [
  [ /\becosistemas?\b/i, 'no significa nada' ],
  [ /\bsinergias?\b/i, 'no significa nada' ],
  [ /\bsoluci[oó]n(es)?\b/i, 'no significa nada' ],
  [ /\bpartners?\b/i, 'está en inglés y ya hay palabra' ],
  [ /\bmatch(es)?\b/i, 'es de las aplicaciones de citas' ],
  [ /\bearly adopters?\b/i, 'se dice Miembro Pionero' ],
  [ /VITRINEXO/, 'el nombre en caja alta grita' ],

  // Del criterio Idioma, que decía las dos reglas y no las medía nadie.
  // El voseo: se trata de tú, y la conjugación se cuela sola al escribir
  // desde Buenos Aires o Montevideo.
  [ /\b(ten[ée]s|pod[ée]s|quer[ée]s|sab[ée]s|ten[ée]te|vos\s+(ten|pod|quer|sos))\b/i, 'se trata de tú: «tienes» y «puedes», nunca «tenés» ni «podés»' ],
  [ /\bsos\s+(un|una|el|la|miembro)\b/i, 'se trata de tú: «eres», nunca «sos»' ],

  // Y la frontera geográfica: se dice dónde hay comunidad hoy, no que la
  // marca sea de una región. La visión incluye Brasil, Estados Unidos y
  // Europa, y una marca que se declara latinoamericana se cierra esa puerta
  // sola.
  [ /\bplataforma\s+(latinoamericana|latina)\b/i, 'no se dice que la marca sea de una región: se dice dónde hay comunidad hoy' ],
  [ /\b(red|directorio|comunidad)\s+latinoamericana\b/i, 'no se dice que la marca sea de una región: se dice dónde hay comunidad hoy' ],
];

/**
 * Las piezas de la línea, que nunca se nombran sin la marca madre adelante.
 *
 * Es la otra regla que el manual declara «sin excepción y también en
 * conversación», y hasta acá no la medía nadie. Mientras no se midió, el
 * plugin la rompía en una docena de pantallas: la landing del evento decía
 * «Activa 4Dinner», el pie y el menú del miembro decían «4Dinner» a secas, y
 * las tres comunidades aparecían como «Woman», «LGBTQ+» y «Senior» en el
 * panel, en el perfil y en el onboarding.
 *
 * Se mide sobre lo renderizado por lo mismo que las palabras prohibidas:
 * vx_landing_4dinner, comunidad-card--woman y VX_Senior_Verification son
 * identificadores, no copy. Y además es la única forma de dar por buena la
 * portada, donde el nombre viene partido en dos elementos —«Vitrinexo
 * <em>4Dinner</em>»—: en el código son dos cadenas y en la pantalla una sola,
 * que es como se lee.
 *
 * La comprobación distingue mayúsculas a propósito. Ver más abajo.
 */
const PIEZAS = [ '4Dinner', 'Woman', 'Senior', 'LGBTQ\\+' ];

/**
 * Dos de los cuatro nombres son además español corriente, y ahí la palabra no
 * nombra a la pieza sino a la gente. Un ejecutivo senior lo es tenga o no la
 * comunidad, y un profesional LGBTQ+ también. Exigirles la marca madre sería
 * pedir «Vitrinexo» delante de un adjetivo.
 *
 * La frontera se sostiene de dos maneras distintas, porque las dos palabras no
 * se parecen:
 *
 *   · «senior» es un adjetivo del español y va en minúscula; el nombre de la
 *     pieza es propio y va en mayúscula. Alcanza con no ignorar la caja, y por
 *     eso ninguno de los cuatro patrones lleva la bandera /i.
 *   · «LGBTQ+» es una sigla y va siempre en alta, así que esa marca no existe.
 *     Se tacha antes del examen donde modifica a un sustantivo de personas:
 *     «profesionales LGBTQ+» son las personas, «Vitrinexo LGBTQ+» es la pieza.
 *
 * La lista se queda corta a propósito. Cada entrada nueva es un agujero en la
 * regla, así que antes de agregar una conviene cambiar el texto.
 */
const GENTE = /\b(persona|personas|profesionales?|ejecutivos?|empresarios?|socios?|miembros?)\s+LGBTQ\+/g;

// La lista común, que incluye el área de miembros cuando hay sesión: el voseo
// se cuela en un saludo o en un aviso, y esos viven dentro.
const navegador = await chromium.launch();

const fallos = [];
const afuera = [];
const medidas = [];
const pendientes = [];

// Una pestaña por modo, no una sola: el copy de la portada pública y el del
// home del miembro son dos textos distintos en la misma ruta.
const pestana = {};
for ( const conSesion of [ false, true ] ) {
  if ( conSesion && ! SESION ) continue;
  const contexto = await navegador.newContext( {
    viewport: { width: 1280, height: 900 },
    ...( conSesion && SESION ? { storageState: SESION } : {} ),
  } );
  pestana[ conSesion ] = await contexto.newPage();
}

for ( const { url, conSesion } of visitas() ) {
  const ruta = new URL( url ).pathname + ( conSesion && SESION ? ' · con sesión' : '' );
  pendientes.push( ruta );
  const pagina = pestana[ conSesion ];
  const r = await pagina.goto( url, { waitUntil: 'domcontentloaded' } ).catch( () => null );
  if ( ! r || ! r.ok() ) continue;
  if ( conSesion && SESION && false === await estaDentro( pagina ) ) afuera.push( ruta );
  medidas.push( ruta );

  const texto = await pagina.evaluate( () => {
    const copia = document.body.cloneNode( true );
    // Y fuera lo que no es copy: un <script> con reglas de precarga trae
    // «href_matches» y la comprobación denunciaba a WordPress.
    for ( const n of copia.querySelectorAll( 'script, style, template, noscript' ) ) n.remove();
    for ( const cita of copia.querySelectorAll( '[data-cita]' ) ) cita.remove();
    document.body.appendChild( copia );

    // Lo que está plegado también se lee, y hay que destaparlo antes de mirar.
    //
    // innerText devuelve lo visible, que para el contraste o el desborde es lo
    // correcto: lo que no se ve no se mide. Para las palabras no: el menú de
    // comunidades del miembro es un dropdown cerrado, las pestañas de
    // Configuración son paneles con display:none, y el menú del teléfono vive
    // en un offcanvas. Ahí dentro están justo los nombres de las piezas, y
    // mientras no se destapó, cambiarlos por la forma prohibida pasaba en
    // verde. Un texto que la persona ve con un clic es texto que alguien
    // escribió y que alguien lee.
    for ( const n of copia.querySelectorAll( '*' ) ) {
      const s = getComputedStyle( n );
      if ( 'none' === s.display )      n.style.setProperty( 'display', 'block', 'important' );
      if ( 'hidden' === s.visibility ) n.style.setProperty( 'visibility', 'visible', 'important' );
    }

    const t = copia.innerText;
    copia.remove();
    return t;
  } );
  for ( const [ patron, porque ] of PROHIBIDAS ) {
    const m = texto.match( patron );
    if ( m ) fallos.push( `${ ruta } dice «${ m[ 0 ] }»: ${ porque }` );
  }

  // El salto de línea entre la marca y la pieza es del render, no del habla:
  // «Vitrinexo <em>4Dinner</em>» puede llegar con un espacio o con un salto
  // según el elemento, y en los dos casos se lee igual. Se aplana antes de
  // mirar, así el patrón pregunta por el nombre y no por el maquetado.
  const plano = texto.replace( GENTE, '' ).replace( /\s+/g, ' ' );
  for ( const pieza of PIEZAS ) {
    const m = plano.match( new RegExp( `(?<!Vitrinexo )\\b${ pieza }`, '' ) );
    if ( ! m ) continue;
    const desde = Math.max( 0, m.index - 32 );
    const cerca = ( desde ? '…' : '' ) + plano.slice( desde, m.index + m[ 0 ].length + 24 ).trim() + '…';
    fallos.push( `${ ruta } dice «${ cerca }»: las piezas llevan la marca madre adelante, se escribe «Vitrinexo ${ m[ 0 ] }»` );
  }
}

await navegador.close();

if ( afuera.length ) fallos.unshift( receta( afuera ) );

const sinMedir = pendientes.filter( ( r ) => ! medidas.includes( r ) );
if ( sinMedir.length ) console.log( `      (no respondieron, no se midieron: ${ sinMedir.join( ', ' ) })` );

informar( 'vocabulario', fallos );
