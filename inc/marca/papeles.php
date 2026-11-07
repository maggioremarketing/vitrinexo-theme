<?php
/**
 * Los papeles: la capa semántica, agrupada por oficio y con el oficio escrito.
 *
 * Sueltos y todos del mismo tamaño, cuarenta tokens parecen cuarenta colores
 * casi iguales entre los que hay que elegir. Tres superficies se parecen
 * porque una es la hoja, otra lo que se levanta un milímetro sobre ella y la
 * tercera lo que se hunde: la diferencia no está en el color, está en el
 * trabajo. Por eso va escrito al lado.
 *
 * Esta lista es también un contrato: una prueba comprueba que todo token
 * semántico del CSS esté acá con su oficio, y que no haya acá ninguno que el
 * CSS ya no declare. Un papel que nadie usa no es un papel, es un token que
 * sobra.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @return array<int,array{titulo:string,bajada:string,papeles:array<string,string>}>
 */
function vx_marca_familias(): array {
    return [
        [
            'titulo' => 'Superficies',
            'bajada' => 'Sobre qué se apoya todo. La hoja, lo que se levanta sobre ella y lo que se hunde.',
            'papeles' => [
                '--color-background'     => 'La hoja. El fondo de la página completa.',
                '--color-surface'        => 'Lo que se levanta: tarjetas, paneles, la ficha de un miembro.',
                '--color-surface-muted'  => 'Lo que se hunde: encabezados de tabla, campos en reposo, zonas de apoyo.',
                '--color-border'         => 'El borde de todo lo anterior. Separa sin pesar.',
            ],
        ],
        [
            'titulo' => 'Tintas',
            'bajada' => 'Lo que se lee. Se elige por jerarquía, nunca por color.',
            'papeles' => [
                '--color-text-primary'   => 'El texto que hay que leer: titulares, nombres, cuerpo.',
                '--color-text-secondary' => 'Lo que acompaña: bajadas, metadatos, ayuda de campo.',
                '--color-text-inverse'   => 'La tinta cuando el fondo es oscuro o saturado.',
            ],
        ],
        [
            'titulo' => 'Marca',
            'bajada' => 'El teal. Es lo que identifica a Vitrinexo y lo que mueve la acción principal.',
            'papeles' => [
                '--color-primary'        => 'La acción principal y los elementos que marcan territorio de marca.',
                '--color-primary-hover'  => 'La misma acción con el puntero encima.',
                '--color-primary-active' => 'La misma acción mientras se presiona.',
                '--color-primary-soft'   => 'El fondo tenue que señala sin gritar: avisos, distintivos, filas activas.',
                '--color-primary-ink'    => 'El coral cuando tiene que leerse: enlaces, títulos de acento e íconos sobre superficie clara.',
                '--color-on-fill'        => 'La tinta que va encima de cualquier relleno de la paleta, coral o pastel. Es oscura, no blanca: blanco sobre coral da 2,58, sobre lavanda 1,43 y sobre salvia 2,12.',
            ],
        ],
        [
            'titulo' => 'Apoyo',
            'bajada' => 'El púrpura y el rosa. Acompañan a la marca; no compiten con ella por la acción principal.',
            'papeles' => [
                '--color-secondary'        => 'La acción secundaria y las piezas que necesitan distinguirse del teal.',
                '--color-secondary-hover'  => 'La acción secundaria con el puntero encima.',
                '--color-secondary-active' => 'La acción secundaria mientras se presiona.',
                '--color-secondary-soft'   => 'El fondo tenue de lo secundario.',
                '--color-accent'           => 'El realce: lo que tiene que encontrarse de un vistazo en una pantalla llena.',
                '--color-accent-hover'     => 'El realce con el puntero encima.',
                '--color-accent-active'    => 'El realce mientras se presiona.',
                '--color-accent-soft'      => 'El fondo tenue del realce.',
            ],
        ],
        [
            'titulo' => 'Estados',
            'bajada' => 'Qué pasó. Cada uno viene en par: la tinta que lo dice y el fondo sobre el que se dice.',
            'papeles' => [
                '--color-success'    => 'Salió bien: perfil aprobado, conexión aceptada.',
                '--color-success-bg' => 'El fondo del aviso de que salió bien.',
                '--color-info'       => 'Hay algo que saber, y no es una falla.',
                '--color-info-bg'    => 'El fondo del aviso informativo.',
                '--color-warning'    => 'Hay que mirar esto: vencimiento cerca, dato incompleto.',
                '--color-warning-bg' => 'El fondo del aviso de atención.',
                '--color-remarcado'  => 'El subrayado de una palabra dentro de una frase. Es el otro oficio de la mantequilla, y no es un aviso: un aviso interrumpe, un remarcado acompaña.',
            ],
        ],
        [
            'titulo' => 'Relieve y forma',
            'bajada' => 'Cuánto se levanta una pieza y qué tan redondeada es. Se eligen por jerarquía, igual que las tintas.',
            'papeles' => [
                '--shadow-sm'    => 'Apenas despegado: tarjetas en reposo.',
                '--shadow-md'    => 'Levantado: menús desplegables, tarjetas con el puntero encima.',
                '--shadow-lg'    => 'Flotando: diálogos y cajones.',
                '--radius-sm'    => 'Piezas chicas: distintivos, campos, botones compactos.',
                '--radius-md'    => 'La medida de la casa: botones y tarjetas.',
                '--radius-lg'    => 'Piezas grandes: paneles y diálogos.',
                '--radius-pill'  => 'Redondeado completo: filtros, etiquetas, avatares.',
            ],
        ],
        [
            'titulo' => 'Escala tipográfica',
            'bajada' => 'Ocho peldaños y nada entre medio. Tener tamaños no es tener jerarquía: cada peldaño dice para qué sirve.',
            'papeles' => [
                '--fs-display' => 'El titular de portada. Uno por página.',
                '--fs-h2'      => 'El título de una sección mayor.',
                '--fs-h3'      => 'Una declaración: una frase que se sostiene sola.',
                '--fs-h4'      => 'El título de un bloque dentro de una sección.',
                '--fs-body-l'  => 'La bajada. Más grande que la prosa porque se lee antes.',
                '--fs-body'    => 'El cuerpo: prosa, campos, tablas.',
                '--fs-body-s'  => 'Lo secundario: notas, pies, ayuda de campo.',
                '--fs-caption' => 'Etiquetas, distintivos y datos sueltos.',
            ],
        ],
        [
            'titulo' => 'Tracking',
            'bajada' => 'Cuánto respira cada letra. Va pegado al tamaño y no suelto: el mismo tracking que asienta un titular de 40 px aprieta una nota de 12.',
            'papeles' => [
                '--ls-hero'        => 'El titular de la portada del sitio, el único que crece con el ancho. Cierra un poco más porque llega a 64 px y ahí el aire sobra de verdad.',
                '--ls-display'     => 'El titular de una página. Se cierra apenas.',
                '--ls-h2'          => 'El título de sección.',
                '--ls-h3'          => 'La declaración.',
                '--ls-h4'          => 'El título de bloque.',
                '--ls-body-l'      => 'La bajada: sin tocar.',
                '--ls-body'        => 'La prosa: sin tocar. El cuerpo se lee mejor con el tracking que la fuente trae de fábrica.',
                '--ls-body-s'      => 'La nota. Abre un poco, porque en chico las letras se tocan.',
                '--ls-caption'     => 'La etiqueta y el dato. Abre un poco más por lo mismo.',
                '--ls-sobretitulo' => 'La caja alta de 12 px o más. Es la que abre de verdad: en mayúsculas, sin el tracking extra, las letras se pegan.',
                '--ls-sobretitulo-xs' => 'La caja alta de 11 px o menos. Abre todavía más, porque cuanto más chica la mayúscula, más aire necesita.',
            ],
        ],
        [
            'titulo' => 'Espaciado',
            'bajada' => 'La escala de aire, en múltiplos de 4. Existe para que lo próximo que haga falta caiga en un peldaño en vez de inventar 33 px.',
            'papeles' => [
                '--space-1'  => 'Entre una etiqueta y su dato.',
                '--space-2'  => 'Entre piezas pegadas: un ícono y su texto.',
                '--space-3'  => 'Dentro de una pieza chica: el relleno de un distintivo.',
                '--space-4'  => 'La medida de la casa: relleno de botón, separación entre campos.',
                '--space-5'  => 'Entre grupos de campos.',
                '--space-6'  => 'El relleno de una tarjeta y el aire entre párrafos.',
                '--space-8'  => 'Entre bloques distintos de una misma sección.',
                '--space-10' => 'Entre una sección y la siguiente, en pantalla angosta.',
                '--space-12' => 'Entre una sección y la siguiente.',
                '--space-16' => 'Entre bloques mayores de una página larga.',
                '--space-20' => 'El aire de respiro de una parte completa.',
            ],
        ],
        [
            'titulo' => 'Tipografía',
            'bajada' => 'Con qué se escribe. Una sola familia en dos papeles, para que no haya que elegir.',
            'papeles' => [
                '--font-display' => 'Titulares y cualquier texto que se lea antes que el resto.',
                '--font-body'    => 'El cuerpo: prosa, campos, tablas.',
                '--font-mono'    => 'El dato: etiquetas en caja alta, fechas, horas y cifras que se leen de un vistazo. JetBrains Mono, subconjuntada. Nunca para prosa: una frase en mono se lee como un error del sistema.',
                '--peso-prosa'   => '400. El cuerpo y todo lo que se lee seguido.',
                '--peso-apoyo'   => '500. Lo que acompaña sin mandar: etiquetas, pies, metadatos.',
                '--peso-titulo'  => '600. Nombres y títulos, y el peso más alto que la identidad admite. El 700 que había en diecisiete reglas se migró acá.',
            ],
        ],
    ];
}

/**
 * Los grupos del CSS que son rampas de primitivos. Se declaran acá porque la
 * regla «ninguna pantalla nombra un primitivo» necesita saber cuáles lo son, y
 * deducirlo del nombre del token es deducirlo de una convención que alguien va
 * a romper.
 */
function vx_marca_grupos_primitivos(): array {
    return [ 'Base', 'Coral', 'Lavanda', 'Rosa', 'Celeste', 'Salvia', 'Mantequilla', 'Terracota' ];
}

/** Todos los papeles declarados, en un mapa nombre → oficio. */
function vx_marca_oficios(): array {
    $oficios = [];
    foreach ( vx_marca_familias() as $f ) {
        foreach ( $f['papeles'] as $nombre => $oficio ) $oficios[ $nombre ] = $oficio;
    }
    return $oficios;
}

/**
 * Los primitivos: las rampas de color. Son la escala de donde salen los
 * papeles, y ninguna pantalla puede nombrarlos directamente.
 *
 * Una escala puede tener peldaños sin usar: existe justamente para que lo
 * próximo que haga falta caiga en un peldaño en vez de inventar un color.
 */
function vx_marca_rampas(): array {
    return [ 'cyan' => 'Teal de marca', 'purple' => 'Púrpura de apoyo', 'pink' => 'Rosa de realce', 'green' => 'Verde de estado', 'ice' => 'Neutros' ];
}
