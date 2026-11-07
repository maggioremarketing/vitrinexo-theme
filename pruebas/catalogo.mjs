/**
 * Qué comprobaciones existen y cómo corre cada una.
 *
 * Se descubren leyendo las carpetas, no una lista escrita a mano: la manera
 * más fácil de que una comprobación no corra es escribir el archivo y
 * olvidarse de agregarlo a la cadena.
 *
 * Hay dos clases y la diferencia es si necesitan el navegador. Las de sistema
 * leen el código y los tokens; las de pantalla abren la página de verdad,
 * porque hay cosas que solo se pueden medir renderizadas.
 */

import { readdirSync } from 'node:fs';
import { join } from 'node:path';
import { TEMA } from './comun.mjs';

const enCarpeta = ( carpeta ) =>
  readdirSync( join( TEMA, 'pruebas', carpeta ) )
    .filter( ( f ) => f.endsWith( '.mjs' ) )
    .sort()
    .map( ( f ) => `${ carpeta }/${ f }` );

/**
 * Las que informan sin frenar el despliegue.
 *
 * `toque` encuentra 87 áreas de pulsación bajo 44×44 en el teléfono, y son
 * defectos de verdad: 65 son los enlaces del pie, de 22,4 px de alto. No es
 * exceso de la prueba y no se arregla aflojándola; se arregla en el producto,
 * y es un cambio de maquetación en todas las páginas.
 *
 * Mientras tanto informa. Y no bloquea por una razón concreta además de la
 * deuda: mide la web publicada, cuyo contenido cambia sin que cambie el
 * código, así que una pregunta frecuente nueva movería el número y rompería
 * el despliegue sin que nadie hubiera tocado el diseño.
 */
export const INFORMAN = [ 'pantalla/toque.mjs' ];

export function comprobaciones() {
  return { sistema: enCarpeta( 'sistema' ), pantalla: enCarpeta( 'pantalla' ) };
}
