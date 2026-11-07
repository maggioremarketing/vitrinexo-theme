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
 * Está vacía, y conviene que siga estándolo. La escribió el día que `toque`
 * encontró 324 áreas de pulsación bajo 44×44 y hubo que decidir si bloquear
 * el despliegue, aflojar la regla o anotar la deuda. Se anotó, se arreglaron
 * las 324 en el mismo día, y la lista se quedó sin nadie.
 *
 * Si algo vuelve a entrar acá, que sea con su número escrito en el registro
 * de decisiones y con fecha para salir. Una comprobación que informa sin
 * frenar es una deuda, no una categoría.
 */
export const INFORMAN = [];

export function comprobaciones() {
  return { sistema: enCarpeta( 'sistema' ), pantalla: enCarpeta( 'pantalla' ) };
}
