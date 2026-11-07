<?php
/**
 * El registro de decisiones.
 *
 * Este manual no tiene versión, tiene commit. Lo que sí hay que versionar son
 * las decisiones: qué se eligió, contra qué, y con qué medición. Sin registro,
 * cada decisión hay que volver a tomarla, y la conversación empieza de cero.
 *
 * Cuando una decisión movió un token, deja escrito su valor de hoy en
 * `tokens`. Una prueba lo compara con el valor real del CSS: un registro que
 * se desincroniza del sistema es peor que no tener registro, porque se cree.
 *
 * Una decisión con estado `superada` no se compara: guarda el valor que el
 * token tuvo mientras rigió. Sin esa distinción, la única forma de que la
 * prueba pase es reescribir la historia, y entonces el registro deja de serlo.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @return array<int,array{
 *   fecha:string, titulo:string, eligio:string, contra:string,
 *   medicion:string, estado:string, tokens:array<string,string>
 * }>
 */
function vx_marca_decisiones(): array {
    return [
        [
            'fecha'    => '2026-11-07',
            'titulo'   => 'Lo translúcido se escribe con color-mix sobre un token, no con rgba',
            'eligio'   => 'color-mix(in srgb, var(--token) N%, transparent) para toda sombra, veladura, degradado y halo de foco. Un borde de 1 px no es un velo: usa el token sólido.',
            'contra'   => 'Dejar rgba() con el valor copiado, o agregar una tabla paralela de canales (--tinta-rgb) que hay que mantener sincronizada a mano.', // primitivo a propósito: el registro nombra la alternativa que descartó.
            'medicion' => 'Quedaban 62 rgba() en el tema y 3 en el plugin, con trece colores de la paleta vieja: el turquesa, el púrpura, el rosa fucsia, el ámbar, el azul, el verde, el navy frío y el blanco azulado. Ahora quedan cero en los dos repositorios. color-mix lee el token en vez de copiarlo, así que si la tinta cambia, la sombra la sigue.',
            'estado'   => 'aplicada',
            'tokens'   => [],
        ],
        [
            'fecha'    => '2026-11-07',
            'titulo'   => 'La prueba de colores mira rgba, hsl y JavaScript',
            'eligio'   => 'Que falle ante cualquier rgba() o hsl() con números, y que recorra también los .js del tema.',
            'contra'   => 'Dejarla mirando solo hexadecimales en .php y .css, que es como estaba.',
            'medicion' => 'Por los dos huecos pasaron 62 colores en el CSS y los tres con los que network.js pintaba los puntos del fondo de la portada: azul, cian y verde de la paleta vieja, meses después del repintado. Verificada rompiéndola con un rgba, con un hsl y con un hexadecimal en un .js.',
            'estado'   => 'aplicada',
            'tokens'   => [],
        ],
        [
            'fecha'    => '2026-11-07',
            'titulo'   => 'Tres botones tenían tinta blanca sobre un relleno pastel',
            'eligio'   => 'Tinta oscura, con --color-on-fill, en el botón de peligro, su estado hover y los dos botones de 4Dinner.',
            'contra'   => 'Dejar el blanco que venía del relleno saturado anterior.',
            'medicion' => 'Blanco sobre terracota 2,82:1, sobre terracota-presión 3,98:1 y sobre mantequilla-presión 1,87:1, contra el mínimo de 4,5:1. Con tinta dan 6,40, 4,53 y 9,64. Son el mismo error que el botón primario de septiembre: el relleno se repintó a pastel y la tinta encima se quedó blanca.',
            'estado'   => 'aplicada',
            'tokens'   => [],
        ],
        [
            'fecha'    => '2026-11-07',
            'titulo'   => 'Se poda el CSS que ningún markup nombra, por regla y no por clase',
            'eligio'   => 'Borrar una regla solo cuando todas las clases de todos sus selectores están muertas, y respetar la familia entera si alguna de sus clases vive.',
            'contra'   => 'Borrar por clase, que era lo rápido.',
            'medicion' => 'Salieron 232 reglas y 1.134 líneas, el 22 % del archivo. Ninguna de las 820 clases que algún markup escribe perdió una sola regla. Borrar por clase habría quitado body.admin-bar .vx-topbar, donde admin-bar la genera WordPress y no aparece en ningún archivo nuestro: la regla sobrevivió porque comparte selector con una clase viva.',
            'estado'   => 'aplicada',
            'tokens'   => [],
        ],
        [
            'fecha'    => '2026-11-07',
            'titulo'   => 'El markup no está solo en el código',
            'eligio'   => 'Bajar las 38 páginas de WordPress ya renderizadas y sumarlas al corpus antes de medir.',
            'contra'   => 'Medir solo contra los archivos del repositorio.',
            'medicion' => 'No rescataron ninguna clase, pero eso es un resultado y no una suposición. Lo que sí rescató clases fue detectar los nombres que el PHP arma por pedazos: la primera corrida declaró muertas las tres variantes de comunidad, que se escriben community-header-vx--<?php echo $slug ?>.',
            'estado'   => 'aplicada',
            'tokens'   => [],
        ],
        [
            'fecha'    => '2026-11-07',
            'titulo'   => 'Las páginas legales tienen un nombre desviado',
            'eligio'   => 'Dejar las reglas .vx-legal-body y .vx-legal-container donde están, sin borrarlas.',
            'contra'   => 'Podarlas con el resto, que es lo que la medición decía.',
            'medicion' => 'El markup de page.php escribe vx-legal-content y vx-legal-wrap; el CSS estiliza vx-legal-body y vx-legal-container. Son nueve reglas que nunca se aplicaron. No es código muerto: es trabajo que alguien hizo y que dejó de tener efecto cuando el nombre cambió en un lado y no en el otro. Borrarlas lo tira; renombrarlas cambia cómo se ve una página publicada. Ninguna de las dos cosas es una poda.',
            'estado'   => 'abierta',
            'tokens'   => [],
        ],
        [
            'fecha'    => '2026-11-07',
            'titulo'   => 'El banner del problema va sin lugar libre',
            'eligio'   => 'Ocho retratos en dos filas de cuatro, todos presentes. 1600 × 970.',
            'contra'   => 'La misma rejilla con un lugar libre, que es lo que llevan las otras dos piezas.',
            'medicion' => 'Se armaron las dos y se miraron en el hueco real. La de arriba lee bien, pero el banner dice «aquí todos están para hacer negocios»: el hueco punteado contradice la frase que ilustra, y sería la tercera invitación del mismo scroll. El hueco mide 1,65 de ancho por alto en todos los anchos salvo 992 px, así que la pieza se dibuja en esa proporción y no se recorta casi nunca; la casilla queda en 0,82 contra los 0,80 del retrato.',
            'estado'   => 'aplicada',
            'tokens'   => [],
        ],
        [
            'fecha'    => '2026-11-07',
            'titulo'   => 'La excepción del literal vale solo donde la hoja no está cargada',
            'eligio'   => 'Que la prueba del plugin separe los dos casos: donde no hay hoja del tema comprueba que el valor exista en la paleta, y donde sí la hay exige var(--token). Y que la paleta se lea del :root del tema en vez de una copia, fallando si las dos dejan de coincidir.',
            'contra'   => 'Barrer los 362 hexadecimales del plugin, que es lo que este registro decía ayer que faltaba.',
            'medicion' => 'No había nada que barrer: los 362 están todos en correos, wp-admin, páginas de wp_die() y un SVG de servidor, y en los shortcodes no hay ninguno. Lo que faltaba era el borde de la excepción, que valía para el repositorio entero. Verificada rompiéndola con un hexadecimal en un shortcode, un rgba en un shortcode, un color fuera de paleta en un correo y una paleta del tema que cambia sin que cambie la copia.',
            'estado'   => 'aplicada',
            'tokens'   => [],
        ],
        [
            'fecha'    => '2026-11-07',
            'titulo'   => 'Decisión superada: «los colores a mano del plugin siguen sin medirse»',
            'eligio'   => 'Nada. Queda como registro de una medición mal hecha.',
            'contra'   => 'Borrarla y dejar el registro limpio.',
            'medicion' => 'Decía 399 hexadecimales sin medir. Los dos datos estaban mal: son 362 y el plugin tiene su propia prueba, pruebas-colores.mjs, que los mide y pasa desde antes. El 399 salía de contar también el archivo de la prueba y el CLAUDE.md. Un registro que se corrige a sí mismo vale más que uno que nunca se equivoca.',
            'estado'   => 'superada',
            'tokens'   => [],
        ],
        [
            'fecha'    => '2026-11-07',
            'titulo'   => 'Las pruebas corren antes de publicar',
            'eligio'   => 'Las comprobaciones de sistema de los dos repositorios entran en el flujo de despliegue. Si fallan, no se publica.',
            'contra'   => 'Seguir corriéndolas a mano, que es como estaban.',
            'medicion' => 'No corrían en ninguno de los dos repositorios: el despliegue iba de la rama al servidor sin mirar nada. Es la razón de fondo de que 62 colores de la paleta vieja aguantaran meses después del repintado, y de que el turquesa siguiera en el foco de una página publicada. Las de sistema no necesitan instalar nada, así que el paso agrega segundos; las de pantalla se quedan fuera porque piden navegador y servidor.',
            'estado'   => 'aplicada',
            'tokens'   => [],
        ],
        [
            'fecha'    => '2026-11-06',
            'titulo'   => 'A lo ancho el motivo es una rejilla de retratos, no el collage',
            'eligio'   => 'Dos piezas nuevas: diez retratos en dos filas para la portada y treinta en tres filas para la franja del directorio. Mismas coordenadas de rasgos, misma paleta, un solo lugar libre.',
            'contra'   => 'Estirar el collage de seis recortes al ancho, que era la opción obvia y la que sonaba mejor en el papel.',
            'medicion' => 'Se armaron las tres y se miraron al tamaño en el que se publican, no a 1600. El collage estirado deja cada recorte más grande que una cara entera y se lee como cuatro caras inconexas. Seis retratos en una fila de 16 a 9 dan una casilla de 0,30 contra los 0,80 del retrato y cortan la cara a ambos lados. Dos filas de cinco dan 0,71 y las cabezas salen enteras.',
            'estado'   => 'aplicada',
            'tokens'   => [],
        ],
        [
            'fecha'    => '2026-11-06',
            'titulo'   => 'La paleta pasa a pasteles sobre blanco con un solo acento',
            'eligio'   => 'La paleta de la propuesta de identidad de Elisa: base, coral y cinco pasteles. Página blanca, paneles hueso, paneles oscuros en tinta.',
            'contra'   => 'Seguir con el teal, el púrpura y el rosa vibrantes, que es lo que el producto tenía lanzado.',
            'medicion' => 'La capa semántica entera se repuntó y 256 referencias a los primitivos viejos se remapearon. Quedan 247 colores a mano en el CSS del tema y 451 en el plugin.',
            'estado'   => 'aplicada',
            'tokens'   => [ '--color-background' => 'var(--blanco)', '--color-surface' => 'var(--hueso)', '--color-primary' => 'var(--coral)' ], // primitivo a propósito: el registro nombra los tokens que movió.
        ],
        [
            'fecha'    => '2026-11-06',
            'titulo'   => 'La tinta sobre el relleno de marca es oscura',
            'eligio'   => 'Un papel nuevo, --color-on-fill, que vale tinta. El botón primario lleva texto oscuro.',
            'contra'   => 'Tinta blanca sobre coral, que es lo que el producto hacía con el teal y lo que pide el reflejo.',
            'medicion' => 'Blanco sobre coral da 2,58 y no llega al mínimo de 4,5. La tinta da 6,99, medido sobre la portada renderizada.',
            'estado'   => 'aplicada',
            'tokens'   => [ '--color-on-fill' => 'var(--tinta)' ], // primitivo a propósito: el registro nombra el token que movió.
        ],
        [
            'fecha'    => '2026-11-06',
            'titulo'   => 'El coral que se lee baja un paso respecto del boceto',
            'eligio'   => '#AD4319 para --coral-tinta.', // color ajeno y primitivo a propósito: el registro cita el valor elegido.
            'contra'   => 'El #B4471F que la propuesta usa para el texto de error.', // color ajeno: el valor del boceto que se descartó.
            'medicion' => '#B4471F da 5,44 sobre blanco y 4,83 sobre hueso, pero 4,47 sobre la superficie hundida, bajo el mínimo. #AD4319 da 4,80 en el peor caso de las tres superficies.', // color ajeno: la medición que sostiene la decisión.
            'estado'   => 'aplicada',
            'tokens'   => [],
        ],
        [
            'fecha'    => '2026-11-06',
            'titulo'   => 'El contorno de foco no es de color de marca',
            'eligio'   => 'El foco se dibuja en tinta.',
            'contra'   => 'Usar el coral o un pastel, que sería lo coherente con la marca.',
            'medicion' => 'La norma pide 3:1 para lo que no es texto. Sobre la hoja, lavanda da 1,76, salvia 2,14, celeste 1,76 y coral 2,58. Ninguno llega.',
            'estado'   => 'aplicada',
            'tokens'   => [],
        ],
        [
            'fecha'    => '2026-11-06',
            'titulo'   => 'El logotipo deja de tener colores propios',
            'eligio'   => 'El archivo nuevo, con sus cuatro colores tomados de la paleta: tinta, coral, salvia y lavanda.',
            'contra'   => 'El anterior, que traía un navy y un degradado verde que no estaban en ninguna paleta y había que explicar como una convivencia a propósito.',
            'medicion' => 'Una comprobación compara ahora los colores del archivo contra la paleta, y lo que la prosa cita contra el archivo. Esa prueba no existía, y por eso el manual afirmó durante semanas unos colores que el logotipo ya no tenía.',
            'estado'   => 'aplicada',
            'tokens'   => [],
        ],
        [
            'fecha'    => '2026-09-24',
            'titulo'   => 'La escala tipográfica es de ocho peldaños',
            'eligio'   => 'Ocho tamaños fijos, de 40 a 12 px, cada uno con su uso escrito.',
            'contra'   => 'Seguir sin escala, que es lo que había.',
            'medicion' => 'El CSS traía 277 declaraciones de tamaño escritas a mano. 166 caen fuera de los ocho peldaños; 13 px aparece 55 veces, 11 px 19 y 15 px 16.',
            'estado'   => 'aplicada',
            'tokens'   => [
                '--fs-display' => '40px', '--fs-h2' => '32px', '--fs-h3' => '24px', '--fs-h4' => '20px',
                '--fs-body-l' => '18px', '--fs-body' => '16px', '--fs-body-s' => '14px', '--fs-caption' => '12px',
            ],
        ],
        [
            'fecha'    => '2026-09-24',
            'titulo'   => 'El espaciado va en múltiplos de cuatro',
            'eligio'   => 'Once peldaños de 4 a 80 px. La medida de la casa es 16.',
            'contra'   => 'Seguir escribiendo cada margen a mano.',
            'medicion' => 'La escala cubre los valores que el producto ya usaba sin nombrarlos. Los peldaños sin uso existen para que lo próximo caiga en uno en vez de inventar 33 px.',
            'estado'   => 'aplicada',
            'tokens'   => [ '--space-4' => '16px', '--space-6' => '24px', '--space-12' => '48px' ],
        ],
        [
            'fecha'    => '2026-09-24',
            'titulo'   => 'Cada rampa de color se titula sola',
            'eligio'   => 'Un comentario propio por rampa en el CSS: Verde, Teal, Púrpura, Rosa, Hielo.',
            'contra'   => 'Dejar el teal colgando del comentario del verde, como estaba.',
            'medicion' => 'El lector de tokens leía las dos rampas como una sola de veinte peldaños, y la paleta del manual las pintaba juntas.',
            'estado'   => 'aplicada',
            'tokens'   => [],
        ],
        [
            'fecha'    => '2026-09-24',
            'titulo'   => 'El teal que se lee es el peldaño 900',
            'eligio'   => 'Un papel nuevo, --color-primary-ink, para el teal que tiene que leerse: enlaces, títulos de acento e íconos.',
            'contra'   => 'Usar --color-primary #00AEB8, que es lo que se venía haciendo, o subir solo hasta cyan-800.', // color ajeno: el registro cita la medición y el token que movió.
            'medicion' => 'Sobre las tres superficies del sistema: #00AEB8 da 2,55 / 2,64 / 2,68. cyan-800 da 4,37 / 4,51 / 4,58, y el 4,37 del fondo de página está bajo el mínimo. cyan-900 da 6,48 / 6,70 / 6,81.', // color ajeno: el registro cita la medición y el token que movió.
            'estado'   => 'superada',
            'tokens'   => [ '--color-primary-ink' => 'var(--color-cyan-900)' ], // primitivo a propósito: el valor que tuvo mientras rigió.
        ],
        [
            'fecha'    => '2026-09-24',
            'titulo'   => 'El fondo del botón primario sigue sin resolverse',
            'eligio'   => 'Nada todavía.',
            'contra'   => 'Dejar el teal de marca como fondo de todo botón primario.',
            'medicion' => 'Tinta blanca sobre #00AEB8 da 2,71:1, bajo el mínimo de 4,5:1. Sobre cyan-900 da 6,88:1. Cambiarlo toca todos los botones primarios del producto, no solo esta página.', // color ajeno: el registro cita la medición y el token que movió.
            'estado'   => 'abierta',
            'tokens'   => [],
        ],
        [
            'fecha'    => '2026-09-24',
            'titulo'   => 'Los estados no se usan como tinta',
            'eligio'   => 'Verde y rosa de estado son fondo, no texto. Lo que va encima se escribe con la tinta normal.',
            'contra'   => 'Escribir «aprobado» en verde y «vence pronto» en rosa, que es lo que pide el reflejo.',
            'medicion' => 'Sobre el fondo de página, --color-success da 2,70:1 y --color-warning 2,98:1, contra un mínimo de 4,5:1. Los pares -bg sí funcionan como fondo con tinta oscura encima.',
            'estado'   => 'aplicada',
            'tokens'   => [],
        ],
        [
            'fecha'    => '2026-09-24',
            'titulo'   => 'El manual se publica sin indexar',
            'eligio'   => 'La página es pública para quien tenga el enlace y lleva noindex.',
            'contra'   => 'Dejarla indexable, o cerrarla tras la sesión de miembro.',
            'medicion' => 'Es lo que hace falta para mandársela a un proveedor sin que compita con la portada en los buscadores.',
            'estado'   => 'aplicada',
            'tokens'   => [],
        ],
    ];
}
