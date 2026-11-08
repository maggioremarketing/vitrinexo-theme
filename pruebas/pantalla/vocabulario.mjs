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
import { visitas, visitasAdmin, estaDentro, receta } from '../comun-pantalla.mjs';

/** De la propuesta de identidad. Cada una con por qué no se dice. */
const PROHIBIDAS = [
  [ /\becosistemas?\b/i, 'no significa nada' ],
  [ /\bsinergias?\b/i, 'no significa nada' ],
  [ /\bsoluci[oó]n(es)?\b/i, 'no significa nada' ],
  [ /\bpartners?\b/i, 'está en inglés y ya hay palabra' ],
  [ /\bmatch(es)?\b/i, 'es de las aplicaciones de citas' ],
  [ /\bearly adopters?\b/i, 'se dice Miembro Pionero' ],
  [ /\bbadges?\b/i, 'se dice distintivo' ],
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

  // «continental» va sola, sin sustantivo al lado, al revés que las dos de
  // arriba: no aparece nunca para otra cosa que para decir de qué región es la
  // marca, y de hecho hoy no aparece en ninguna parte.
  [ /\bcontinental(es)?\b/i, 'no se dice que la marca sea de una región: se dice en qué ciudades hay mesa hoy' ],

  // «Hispanoamérica» sí lleva sustantivo, y la razón la dio el propio manual.
  // Nació sola, como «continental», y al medirla salió roja en cinco frases
  // que dicen exactamente lo que el criterio permite decir: «el lanzamiento es
  // en Hispanoamérica y en español», «el español es el de la región», «en
  // Hispanoamérica ese signo nombra siete monedas distintas». Eso no es
  // declarar de qué región es la marca: es decir dónde hay comunidad hoy, que
  // es la mitad permitida de la regla. Prohibir el nombre de la región entero
  // obligaba a reescribir cinco frases correctas para esquivar una palabra, y
  // eso es lo que enseña a ignorar la prueba. Lo que no se dice es que
  // Vitrinexo sea de ahí, y eso es lo que se mide.
  //
  // Y la mitad que hay que decir en voz alta: esta regla NO caza ninguna de
  // las tres frases que la hicieron nacer. «una ciudad de Hispanoamérica» en
  // el hero del evento, «servicios B2B en Hispanoamérica» en el blog y
  // «directivas hispanoamericanas» en Vitrinexo Woman pasan las tres en verde,
  // y pasan bien: medidas contra el criterio, ninguna declaraba que Vitrinexo
  // sea de una región. Se cambiaron por cómo se leen, que es una decisión de
  // redacción, y las decisiones de redacción no las toma una prueba. Queda
  // escrito porque quien lea esto en seis meses va a saber que nació de esas
  // frases y va a suponer que están protegidas. No lo están.
  [ /\b(plataforma|red|directorio|comunidad)\s+hispanoamericanas?\b/i, 'no se dice que la marca sea de una región: se dice dónde hay comunidad hoy' ],
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

// Una pestaña por sesión, no una sola: el copy de la portada pública y el del
// home del miembro son dos textos distintos en la misma ruta. Y la tercera,
// la de quien no terminó el onboarding, es la única que puede abrir esa
// pantalla: con la sesión de miembro el plugin redirige al panel.
const paradas = [ ...visitas(), ...visitasAdmin() ];

const pestana = {};
for ( const { sesion } of paradas ) {
  if ( sesion in pestana ) continue;
  const contexto = await navegador.newContext( {
    viewport: { width: 1280, height: 900 },
    ...( sesion ? { storageState: sesion } : {} ),
  } );
  pestana[ sesion ] = await contexto.newPage();
}

const noHtml = [];

for ( const { url, sesion, camino: ruta, escritorio } of paradas ) {
  pendientes.push( ruta );
  const pagina = pestana[ sesion ];
  const r = await pagina.goto( url, { waitUntil: 'domcontentloaded' } ).catch( () => null );
  if ( ! r || ! r.ok() ) continue;

  // Responder 200 no es servir la pantalla. `feed` es un endpoint reservado de
  // WordPress y su regla de reescritura gana sobre la página con ese slug, así
  // que /feed/ devolvía 824 bytes de RSS. Esta comprobación leía ese XML, no le
  // encontraba ninguna palabra prohibida —un RSS no tiene copy— y se anotaba la
  // ruta como medida. Mirar el tipo de contenido lo caza solo.
  const tipo = ( r.headers()[ 'content-type' ] || '' ).toLowerCase();
  if ( ! tipo.startsWith( 'text/html' ) ) {
    noHtml.push( `${ ruta } (${ tipo.split( ';' )[ 0 ] || 'sin tipo' })` );
    continue;
  }
  if ( sesion && ! escritorio && false === await estaDentro( pagina ) ) afuera.push( ruta );
  medidas.push( ruta );

  const medida = await pagina.evaluate( () => {
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
    // Un <details> cerrado no se destapa con CSS: lo pliega el navegador, no
    // una regla, y ningún display:block lo abre. Las preguntas frecuentes son
    // justo eso, un <details> por pregunta, así que el título se leía y la
    // respuesta no, incluida la palabra «badge» que la propia respuesta usaba
    // para explicar que no se dice.
    //
    // Esto le pasa a esta comprobación y no a las demás, y vale saber por qué.
    // El <details> cerrado sí compone caja: la recorta con overflow:hidden,
    // pero la caja está y tiene color, ancho y posición. Las comprobaciones
    // que miden geometría —contraste, desborde, toque, tipografía— lo venían
    // viendo todo este tiempo. La que no lo veía es esta, porque innerText
    // devuelve lo que el navegador compone a la vista y el contenido recortado
    // no entra. Lo comprobé rompiendo el contraste y el desborde dentro de una
    // respuesta plegada: salen en rojo igual con esto y sin esto.
    //
    // Por eso abrirlos vive acá y no en el embudo de comun-pantalla.mjs,
    // donde no cambiaría nada.
    for ( const d of copia.querySelectorAll( 'details' ) ) d.open = true;

    for ( const n of copia.querySelectorAll( '*' ) ) {
      const s = getComputedStyle( n );
      if ( 'none' === s.display )      n.style.setProperty( 'display', 'block', 'important' );
      if ( 'hidden' === s.visibility ) n.style.setProperty( 'visibility', 'visible', 'important' );
    }

    const t = copia.innerText;
    copia.remove();
    return { titulo: document.title, cuerpo: t };
  } );

  // El título de la página también se lee, y no está en el cuerpo.
  //
  // Es lo que dice la pestaña del navegador, lo que Google muestra en el
  // resultado y lo que aparece cuando alguien pega el enlace en un chat. Para
  // mucha gente es lo primero y a veces lo único que lee de la página. Durante
  // un rato la comprobación miró solo el `body`, y mientras tanto el título de
  // la landing decía «4Dinner — Sobre el evento»: la regla estaba rota en el
  // lugar más visible y la prueba pasaba en verde.
  //
  // Va aparte y no concatenado al cuerpo porque el aviso tiene que decir dónde
  // está: un título se arregla en otro lado que un párrafo.
  const zonas = [
    [ '', medida.cuerpo ],
    [ ' en el título', medida.titulo ],
  ];

  for ( const [ donde, texto ] of zonas ) {
    for ( const [ patron, porque ] of PROHIBIDAS ) {
      const m = texto.match( patron );
      if ( m ) fallos.push( `${ ruta } dice${ donde } «${ m[ 0 ] }»: ${ porque }` );
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
      fallos.push( `${ ruta } dice${ donde } «${ cerca }»: las piezas llevan la marca madre adelante, se escribe «Vitrinexo ${ m[ 0 ] }»` );
    }
  }
}

await navegador.close();

if ( afuera.length ) fallos.unshift( receta( afuera ) );

// Esto iba con console.log, que se imprime en verde y en rojo pero no cambia el
// código de salida: la comprobación podía no abrir una sola pantalla y el
// despliegue pasaba igual. Es el mismo agujero que ya se tapó en enLaPagina el
// 7 de noviembre de 2026, y seguía abierto acá, que es justo donde se mide el
// copy. Mientras una ruta esté en la lista, no medirla es fallar.
const dichoNoHtml = noHtml.map( ( d ) => d.split( ' (' )[ 0 ] );
const sinMedir = pendientes.filter( ( r ) => ! medidas.includes( r ) && ! dichoNoHtml.includes( r ) );
if ( noHtml.length ) fallos.unshift( `no devolvieron HTML, así que lo medido no era la pantalla: ${ noHtml.join( ', ' ) }` );
if ( sinMedir.length ) fallos.unshift( `no respondieron, así que no se midieron: ${ sinMedir.join( ', ' ) }` );

informar( 'vocabulario', fallos );
