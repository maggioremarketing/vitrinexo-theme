/**
 * Que el logotipo no se dibuje a mano.
 *
 * El día que el logotipo cambie, cambia el archivo. Cada pantalla que escribe
 * la marca con tipografía propia, o que apunta a otra copia del archivo, es un
 * lugar donde va a seguir el logotipo viejo, y nadie se va a acordar de ese
 * lugar.
 */

import { informar, leer, resolver, tokensDelSistema } from '../comun.mjs';

const OFICIAL = 'assets/img/vitrinexo.svg';
/** Las otras piezas de marca que el manual sí puede mostrar. */
const DE_MARCA = [ 'assets/img/isotipo.svg', 'assets/img/isotipo-lleno.svg', 'assets/img/compartir.png',
                   'assets/img/favicon-16.png', 'assets/img/favicon-32.png', 'assets/img/favicon-180.png',
                   'assets/img/favicon-192.png', 'assets/img/favicon-512.png', 'assets/img/isotipo-512.png' ];
const fallos = [];

for ( const archivo of [ 'templates/page-marca.php', 'inc/marca/contenido.php', 'inc/marca/piezas.php' ] ) {
  const fuente = leer( archivo );

  // Las etiquetas se buscan sin el PHP: un <?php ... ?> dentro de un atributo
  // tiene un > que corta la etiqueta a la mitad, y la prueba terminaba
  // denunciando imágenes sin alt que sí lo tenían. Las rutas, en cambio, viven
  // dentro del PHP, así que esas se buscan en la fuente entera: medirlas sobre
  // el texto sin PHP es no medir ninguna.
  const etiquetas = fuente.replace( /<\?php[\s\S]*?\?>/g, 'PHP' );

  // Ninguna otra copia del logotipo.
  // Las fotos de referencia del manual viven en su propia carpeta y no son
  // logotipos; lo que se vigila es que nadie apunte a otra copia de la marca.
  for ( const [ , ruta ] of fuente.matchAll( /assets\/img\/(?!marca\/)([\w.-]+\.(?:svg|png|jpe?g))/g ) ) {
    const completa = `assets/img/${ ruta }`;
    if ( completa !== OFICIAL && ! DE_MARCA.includes( completa ) ) {
      fallos.push( `${ archivo } apunta a ${ ruta }, que no es una pieza de marca` );
    }
  }

  // Toda imagen dice qué hay en ella.
  for ( const [ etiqueta ] of etiquetas.matchAll( /<img[^>]*>/g ) ) {
    if ( ! /\salt=/.test( etiqueta ) ) fallos.push( `${ archivo } tiene una imagen sin alt: ${ etiqueta.replace( /\s+/g, ' ' ).slice( 0, 64 ) }` );
  }

  // El logotipo se marca para que las pruebas de pantalla lo reconozcan: la
  // norma exime del contraste al texto que es parte de una marca, y sin marca
  // explícita esa exención habría que deducirla del tamaño o del color.
  // La plantilla puede nombrar el archivo o llamar a vx_logo_url(). Si solo se
  // mirara la ruta, pasar las plantillas a la función apagaría esta regla sin
  // que nadie se entere, que es la peor forma de perder una comprobación.
  if ( fuente.includes( OFICIAL ) || fuente.includes( 'vx_logo_url()' ) ) {
    for ( const [ etiqueta ] of etiquetas.matchAll( /<img[^>]*>/g ) ) {
      if ( ! etiqueta.includes( 'data-logotipo' ) && ! etiqueta.includes( 'vx-imagen' ) ) {
        fallos.push( `${ archivo } pinta el logotipo sin data-logotipo: ${ etiqueta.replace( /\s+/g, ' ' ).slice( 0, 64 ) }` );
      }
    }
  }
}

/**
 * Y los colores del archivo son del sistema.
 *
 * Esta es la comprobación que faltaba. El manual afirmaba que el logotipo traía
 * un navy y un degradado verde «que conviven a propósito» con la paleta, y
 * siguió afirmándolo después de que el archivo cambiara entero: ninguna prueba
 * miraba el SVG. Un documento que se jacta de no poder desincronizarse no puede
 * tener un criterio que nadie mide.
 */
const svg = [ OFICIAL, 'assets/img/isotipo.svg', 'assets/img/isotipo-lleno.svg' ].map( leer ).join( '\n' );
const palabra = /#[0-9a-fA-F]{6}\b/g;

const delSistema = new Set(
  tokensDelSistema()
    .flatMap( ( g ) => g.tokens.map( ( t ) => resolver( t.valor, Object.fromEntries( tokensDelSistema().flatMap( ( x ) => x.tokens.map( ( y ) => [ y.nombre, y.valor ] ) ) ) ) ) )
    .filter( ( v ) => /^#[0-9a-fA-F]{6}$/.test( v ) )
    .map( ( v ) => v.toUpperCase() )
);

const enElArchivo = [ ...new Set( ( svg.match( palabra ) ?? [] ).map( ( c ) => c.toUpperCase() ) ) ];

if ( ! enElArchivo.length ) fallos.push( 'el archivo del logotipo no declara ningún color' );
for ( const color of enElArchivo ) {
  if ( ! delSistema.has( color ) ) fallos.push( `el logotipo usa ${ color }, que no está en la paleta del sistema` );
}

// Y lo que la prosa afirma del logotipo tiene que estar en el archivo.
const prosa = leer( 'inc/marca/contenido.php' );
const bloque = prosa.slice( prosa.indexOf( "'identidad-logotipo'" ), prosa.indexOf( "'identidad-isotipo'" ) );
for ( const [ citado ] of bloque.matchAll( palabra ) ) {
  if ( ! enElArchivo.includes( citado.toUpperCase() ) ) {
    fallos.push( `el criterio Logotipo cita ${ citado } y el archivo no lo tiene` );
  }
}

informar( 'logotipo', fallos );
