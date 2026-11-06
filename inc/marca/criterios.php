<?php
/**
 * Los criterios de Vitrinexo: el contenido del manual y, a la vez, el estado
 * del trabajo.
 *
 * Un criterio está `definido` cuando alguien puede aplicarlo sin preguntar,
 * `parcial` cuando hay una decisión tomada pero le falta alcance, y
 * `pendiente` cuando no existe. Declarar los huecos es parte del manual: lo
 * que no está escrito, cada quien lo improvisa distinto.
 *
 * El orden no es decorativo. Todo se deriva de la estrategia: el vocabulario
 * sale de a quién le hablas y el tono de qué le prometes, así que Voz va al
 * final y no al principio.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * El ancla de un criterio, derivada de su nombre. Se calcula y no se escribe:
 * un identificador escrito a mano se desincroniza del nombre en cuanto alguien
 * renombra el criterio, y entonces el enlace del menú apunta a nada.
 */
function vx_marca_ancla( string $nombre ): string {
    $sin = strtr(
        $nombre,
        [ 'á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u','ñ'=>'n','Á'=>'A','É'=>'E','Í'=>'I','Ó'=>'O','Ú'=>'U','Ñ'=>'N' ]
    );
    $s = strtolower( $sin );
    $s = preg_replace( '/[^a-z0-9]+/', '-', $s );
    return trim( (string) $s, '-' );
}

/**
 * Las tres partes con sus criterios, en orden de lectura. El número de parte y
 * el de criterio se calculan por lo mismo que el ancla: escritos a mano se
 * desincronizan en cuanto alguien mueve una parte de sitio.
 */
function vx_marca_partes(): array {
    static $partes = null;
    if ( $partes !== null ) return $partes;

    $crudo = [
        [
            'id'      => 'estrategia',
            'titulo'  => 'Estrategia',
            'bajada'  => 'Por qué existe la marca. Todo lo demás se deriva de acá: el vocabulario sale de a quién le hablas, y el tono de qué le prometes.',
            'criterios' => [
                [ 'Contexto', 'definido', 'La competencia no es otra plataforma: es la agenda. Directorio verificado, no red social ni marketplace.' ],
                [ 'Qué significa la marca', 'definido', 'Vitrina y nexo. De ahí sale vitrinear, el verbo propio.' ],
                [ 'Tagline', 'definido', 'Declarado una vez. El titular del sitio y el del manual salen de la misma función.' ],
                [ 'Línea de producto', 'definido', 'Cinco piezas, todas con la marca madre adelante. Se escribe Vitrinexo 4Dinner, nunca 4Dinner solo.' ],
                [ 'Misión', 'definido', 'Lo que el producto hace hoy, con cada palabra respaldada por el sistema.' ],
                [ 'Visión', 'definido', 'Lanzamiento en Hispanoamérica y en español; la visión incluye Brasil, Estados Unidos y Europa.' ],
                [ 'Valores', 'definido', 'Rigor, franqueza, reciprocidad, paridad y sobriedad, cada uno con lo que cambia por tenerlo.' ],
                [ 'Públicos objetivos', 'definido', 'Tres, y los dos primeros son la misma persona en momentos distintos.' ],
                [ 'Objetivos estratégicos', 'parcial', '100 miembros antes de que termine 2026 para cobrar en 2027. Falta el modelo de suscripción.' ],
                [ 'Propuesta de valor', 'definido', 'Te muestras ante un directorio verificado y a cambio te dejas verificar. La verificación es el producto.' ],
            ],
        ],
        [
            'id'      => 'identidad',
            'titulo'  => 'Identidad visual',
            'bajada'  => 'Cómo se ve la marca.',
            'criterios' => [
                [ 'Logotipo', 'definido', 'Sus cuatro colores son del sistema. Respeto igual a la altura de la o, mínimo 90 px, y dos monocromas.' ],
                [ 'Isotipo', 'definido', 'La vitrina con los nexos en sus aberturas. Abierta sobre 48 px, llena bajo 32, y las dos en monocromo.' ],
                [ 'Tipografías', 'definido', 'Switzer Variable, servida localmente. Cuatro pesos en uso de los nueve disponibles.' ],
                [ 'Jerarquía', 'definido', 'Tamaño, peso y tinta, en ese orden. El color nunca distingue solo.' ],
                [ 'Escala tipográfica', 'definido', 'Ocho peldaños. Esta página los obedece, así que si alguien la rompe se nota leyendo.' ],
                [ 'Paleta de color', 'definido', 'Pasteles sobre blanco con el coral de acento único. Ni un color escrito a mano queda fuera de la paleta.' ],
                [ 'Iconografía', 'definido', 'Tabler Icons 3.19.0, trazo de 2 px sobre rejilla de 24. Una sola familia.' ],
                [ 'Motivos', 'definido', 'La red: puntos que se unen. Va detrás del contenido y nunca encima.' ],
                [ 'Dirección fotográfica', 'definido', 'Gente trabajando, nunca posando. Dos registros de luz y la silueta del nexo como recurso propio.' ],
                [ 'Favicon', 'definido', 'El isotipo lleno, en SVG y en PNG de 32, 16 y 180.' ],
                [ 'Imagen para compartir', 'definido', '1200 × 630 sobre hueso, con el logotipo, el tagline y el dominio.' ],
            ],
        ],
        [
            'id'      => 'voz',
            'titulo'  => 'Voz',
            'bajada'  => 'Cómo habla la marca.',
            'criterios' => [
                [ 'Idioma', 'parcial', 'Español de Hispanoamérica, de tú, sin voseo. Faltan portugués e inglés.' ],
                [ 'Tono', 'definido', 'Directo y sin adorno. Se le da la razón a la objeción antes de responderla.' ],
                [ 'Mayúsculas y minúsculas', 'definido', 'Tipo oración en todas partes. Caja alta solo en sobretítulos.' ],
                [ 'Vocabulario', 'parcial', 'Ocho pares y siete palabras sin reemplazo. El producto todavía usa «match» en tres rutas vivas.' ],
                [ 'Patrones de copy', 'definido', 'Botón, título, error, campo obligatorio, aviso y pantalla vacía. Una sola forma para cada uno.' ],
                [ 'Números y formatos', 'parcial', 'Teléfono, moneda, fecha, hora, decimales y porcentaje. La fecha corta se revisa cuando entre Estados Unidos.' ],
                [ 'Emojis', 'definido', 'Ninguno, en ninguna parte. Incluye el visto, el aspa y la flecha escrita en un rótulo.' ],
            ],
        ],
    ];

    $n      = 0;
    $partes = [];

    foreach ( $crudo as $i => $p ) {
        $criterios = [];
        foreach ( $p['criterios'] as $c ) {
            $n++;
            $criterios[] = [
                'n'      => $n,
                'nombre' => $c[0],
                'estado' => $c[1],
                'nota'   => $c[2] ?? '',
                'id'     => $p['id'] . '-' . vx_marca_ancla( $c[0] ),
            ];
        }
        $partes[] = [
            'id'        => $p['id'],
            'numero'    => str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT ),
            'titulo'    => $p['titulo'],
            'bajada'    => $p['bajada'],
            'criterios' => $criterios,
        ];
    }

    return $partes;
}

/** Todos los criterios, aplanados y en orden. */
function vx_marca_criterios(): array {
    $todos = [];
    foreach ( vx_marca_partes() as $p ) {
        foreach ( $p['criterios'] as $c ) $todos[] = $c;
    }
    return $todos;
}

/** El nombre de un criterio por su ancla. El título del bloque sale de acá. */
function vx_marca_nombre( string $id ): string {
    foreach ( vx_marca_criterios() as $c ) {
        if ( $c['id'] === $id ) return $c['nombre'];
    }
    return '';
}

/** Cómo se rotula cada estado. */
function vx_marca_etiqueta( string $estado ): string {
    return [ 'definido' => 'Definido', 'parcial' => 'Parcial', 'pendiente' => 'Pendiente' ][ $estado ] ?? $estado;
}
