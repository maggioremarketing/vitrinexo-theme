/**
 * Que los números se escriban como el manual dice.
 *
 * La marca opera en varios países a la vez, y casi todas estas reglas existen
 * por eso: un formato que se lee distinto en Santiago y en Bogotá no es un
 * detalle de estilo, es un dato mal entendido.
 *
 * Se mide sobre el texto que la persona ve, no sobre el código: el código
 * necesita «8:00 AM» para que strtotime lo entienda, y eso no es copy. Lo que
 * se persigue es lo que llega a una pantalla.
 *
 * El criterio estaba escrito entero y no lo medía nadie. Cuando se escribió
 * esta comprobación había tres violaciones, y una era la que el propio manual
 * declaraba arreglada: la hora de las cenas seguía siendo «8:00 pm» en la
 * ficha del evento.
 */

import { informar } from '../comun.mjs';
import { enLaPagina } from '../comun-pantalla.mjs';

/** Cada regla, con lo que persigue y por qué. */
const REGLAS = [
  {
    nombre: 'hora en 12 horas',
    busca: /\b\d{1,2}(?::\d{2})?\s?[ap]\.?\s?m\.?(?![a-z])/gi,
    porque: 'la hora va en 24 horas y con dos puntos: 20:00',
  },
  {
    nombre: 'moneda sin código',
    busca: /(?<![A-Za-z])\$\s?\d/g,
    porque: 'el monto lleva el código de tres letras delante, USD 49: en Hispanoamérica el signo peso nombra siete monedas',
  },
  {
    nombre: 'miles a la inglesa',
    busca: /(?<![\d.,])\d{1,3},\d{3}(?![\d,])/g,
    porque: 'punto para los miles y coma para los decimales: 1.234,56',
  },
  {
    nombre: 'porcentaje separado',
    busca: /\d\s+%/g,
    porque: 'el porcentaje va pegado al número: 35%',
  },
  {
    nombre: 'fecha en formato de máquina',
    busca: /(?<![\d-])\d{4}-\d{2}-\d{2}(?![\d-])/g,
    porque: 'la fecha corta se escribe 24/09/2026',
  },
  {
    nombre: 'mes o día en mayúscula',
    busca: /\b(?:de|,)\s(?:Enero|Febrero|Marzo|Abril|Mayo|Junio|Julio|Agosto|Septiembre|Octubre|Noviembre|Diciembre|Lunes|Martes|Miércoles|Jueves|Viernes|Sábado|Domingo)\b/g,
    porque: 'en la fecha larga el día y el mes van en minúscula',
  },
];

const fallos = await enLaPagina(
  async ( pagina ) =>
    pagina.evaluate( ( reglas ) => {
      // innerText y no textContent: lo que la persona ve, sin lo que está
      // oculto. Una plantilla escondida con un formato viejo no se lee.
      //
      // Y sin lo que está citado. El manual escribe «miércoles 8pm» para
      // contar qué formato se corrigió, y eso es una cita, no un uso: va
      // marcado con data-cita y no se mide. Sin esa distinción, la única
      // forma de pasar en verde sería que el manual no pudiera nombrar lo
      // que arregló.
      const copia = document.body.cloneNode( true );
      // Y fuera lo que no es copy: un <script> con reglas de precarga trae
      // «href_matches» y la comprobación denunciaba a WordPress.
      for ( const n of copia.querySelectorAll( 'script, style, template, noscript' ) ) n.remove();
      for ( const cita of copia.querySelectorAll( '[data-cita]' ) ) cita.remove();
      document.body.appendChild( copia );
      copia.style.position = 'static';
      const texto = copia.innerText;
      copia.remove();
      const malos = [];
      for ( const { nombre, busca, porque } of reglas ) {
        for ( const m of texto.match( new RegExp( busca.fuente, busca.banderas ) ) ?? [] ) {
          malos.push( `«${ m.trim() }» — ${ nombre }: ${ porque }` );
        }
      }
      return [ ...new Set( malos ) ];
    }, REGLAS.map( ( r ) => ( { ...r, busca: { fuente: r.busca.source, banderas: r.busca.flags } } ) ) )
);

informar( 'formatos', fallos );
