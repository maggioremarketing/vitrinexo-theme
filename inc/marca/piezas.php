<?php
/**
 * Las piezas con que está escrito el manual: un bloque de criterio, un párrafo
 * de prosa, una muestra de color, una celda de contraste.
 *
 * Viven aparte del contenido porque el contenido es largo y esto no cambia
 * casi nunca. Ninguna de estas piezas nombra un primitivo: todas pasan por la
 * capa semántica, que es lo que el propio manual exige.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/** La portada: qué es este documento y de qué commit salió. */
function vx_marca_portada(): void {
    $criterios = vx_marca_criterios();
    $definidos = count( array_filter( $criterios, fn( $c ) => $c['estado'] === 'definido' ) );
    ?>
    <header class="vx-portada">
        <p class="vx-sobretitulo">Vitrinexo · Manual de marca</p>
        <h1 class="vx-display"><?php echo esc_html( function_exists( 'vx_tagline' ) ? vx_tagline() : '' ); ?></h1>
        <p class="vx-bajada">
            Directorio B2B de empresas de servicios profesionales. Este documento dice cómo se ve,
            cómo habla y por qué existe la marca, para que alguien que nunca habló con nosotros
            pueda escribir un botón, elegir un color y redactar un error, y le salga igual.
        </p>
        <p class="vx-nota">
            Se genera del mismo CSS que el sitio: si un token cambia, esta página cambia sola.
            No tiene versión, tiene commit. <?php echo esc_html( $definidos ); ?> de
            <?php echo count( $criterios ); ?> criterios están definidos.
        </p>
    </header>
    <?php
}

/** Una de las tres partes, con su número y su bajada. */
function vx_marca_seccion( array $parte, callable $contenido ): void {
    ?>
    <section id="<?php echo esc_attr( $parte['id'] ); ?>" class="vx-parte">
        <div class="vx-parte-cabeza">
            <span class="vx-dato vx-numero"><?php echo esc_html( $parte['numero'] ); ?></span>
            <h2 class="vx-h2"><?php echo esc_html( $parte['titulo'] ); ?></h2>
        </div>
        <p class="vx-bajada"><?php echo esc_html( $parte['bajada'] ); ?></p>
        <?php $contenido(); ?>
    </section>
    <?php
}

/**
 * Un bloque de criterio. El título sale del criterio y no se escribe aparte:
 * un título escrito dos veces termina diciendo una cosa en el menú y otra en
 * el cuerpo.
 */
function vx_marca_bloque( string $criterio, callable $cuerpo ): void {
    ?>
    <div class="vx-bloque" id="<?php echo esc_attr( $criterio ); ?>">
        <h3 class="vx-h4"><?php echo esc_html( vx_marca_nombre( $criterio ) ); ?></h3>
        <div class="vx-bloque-cuerpo"><?php $cuerpo(); ?></div>
    </div>
    <?php
}

/** Prosa. El ancho de lectura y el aire entre párrafos, en un solo lugar. */
function vx_marca_p( string $html ): void {
    echo '<p class="vx-p">' . wp_kses( $html, vx_marca_kses() ) . '</p>';
}

/** Una declaración: una frase que se sostiene sola. */
function vx_marca_declaracion( string $texto ): void {
    echo '<p class="vx-h3">' . esc_html( $texto ) . '</p>';
}

/** Lo que se puede escribir dentro de la prosa del manual. */
function vx_marca_kses(): array {
    return [
        'strong' => [], 'em' => [], 'code' => [], 'br' => [],
        'a' => [ 'href' => [], 'rel' => [], 'target' => [] ],
    ];
}

/**
 * Una foto de referencia con su pie. El pie no describe lo que se ve, que ya
 * se ve: dice qué hay que repetir de esa foto.
 */
function vx_marca_foto( string $archivo, string $alt, string $pie ): void {
    ?>
    <figure class="vx-foto">
        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/marca/' . $archivo ); ?>"
             alt="<?php echo esc_attr( $alt ); ?>" class="vx-imagen" loading="lazy">
        <figcaption class="vx-nota"><?php echo esc_html( $pie ); ?></figcaption>
    </figure>
    <?php
}

/**
 * Una palabra y lo que trae puesto. Sirve para un valor y para un público: los
 * dos son una palabra que no significa nada hasta que dice qué cambia por
 * tenerla.
 */
function vx_marca_valor( string $palabra, string $consecuencia ): void {
    ?>
    <div class="vx-valor">
        <p class="vx-h3"><?php echo esc_html( $palabra ); ?></p>
        <p class="vx-p"><?php echo wp_kses( $consecuencia, vx_marca_kses() ); ?></p>
    </div>
    <?php
}

/**
 * Un criterio que todavía no está escrito, con la pregunta que lo desbloquea.
 *
 * Declarar los huecos es parte del manual: lo que no está escrito, cada quien
 * lo improvisa distinto. Y un hueco con la pregunta adentro se cierra más
 * rápido que un hueco que solo dice «pendiente».
 */
function vx_marca_pendiente( string $pregunta ): void {
    ?>
    <p class="vx-pendiente">
        <span class="vx-estado vx-estado-pendiente">Pendiente</span>
        <span><?php echo esc_html( $pregunta ); ?></span>
    </p>
    <?php
}

/** Una rampa de primitivos: una fila por matiz, un peldaño por casilla. */
function vx_marca_rampa( string $titulo, array $tokens ): void {
    // El orden de una familia no es el del archivo: es base, puntero encima,
    // presionado y suave. Fuera de ese orden no es una familia, es una fila de
    // colores.
    $orden = [ 'hover' => 1, 'presion' => 2, 'tinta' => 3, 'suave' => 4 ];
    $peldano = static function ( string $nombre ) use ( $orden ): int {
        $cola = substr( strrchr( $nombre, '-' ), 1 );
        return $orden[ $cola ] ?? 0;
    };
    usort( $tokens, fn( $a, $b ) => $peldano( $a['nombre'] ) <=> $peldano( $b['nombre'] ) );
    ?>
    <div class="vx-rampa">
        <p class="vx-rampa-nombre"><?php echo esc_html( $titulo ); ?></p>
        <div class="vx-rampa-pasos">
            <?php foreach ( $tokens as $t ) :
                $valor = vx_marca_resolver( $t['valor'] );
                [ $hex, $op ] = vx_marca_como_se_escribe( $valor );
                $paso = substr( strrchr( $t['nombre'], '-' ), 1 );
                if ( ! isset( $orden[ $paso ] ) ) $paso = 'base';
                ?>
                <div class="vx-peldano">
                    <span class="vx-muestra" data-hex="<?php echo esc_attr( $hex ); ?>"
                          style="background:<?php echo esc_attr( $valor ); ?>"></span>
                    <span class="vx-dato"><?php echo esc_html( $paso ); ?></span>
                    <span class="vx-dato vx-tenue"><?php echo esc_html( $hex ); ?></span>
                    <span class="vx-dato vx-tenue"><?php echo esc_html( $op ); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}

/**
 * Los papeles de una familia, con su oficio al lado.
 *
 * La rejilla tiene pistas fijas y no es flex: con flex basta un oficio más
 * largo que los demás para correr la columna siguiente en esa sola fila, y en
 * una tabla de colores la alineación es lo que deja comparar de un vistazo.
 */
function vx_marca_familia( array $familia ): void {
    $mapa = vx_marca_mapa_tokens();
    ?>
    <div class="vx-familia">
        <p class="vx-familia-nombre"><?php echo esc_html( $familia['titulo'] ); ?></p>
        <p class="vx-nota"><?php echo esc_html( $familia['bajada'] ); ?></p>
        <div class="vx-papeles">
            <?php foreach ( $familia['papeles'] as $nombre => $oficio ) :
                $bruto = $mapa[ $nombre ] ?? '';
                $valor = vx_marca_resolver( $bruto );
                $color = vx_marca_es_color( $valor );
                ?>
                <div class="vx-papel">
                    <span class="vx-papel-muestra<?php echo $color ? '' : ' vx-papel-sin-muestra'; ?>"
                          <?php if ( $color ) : ?>
                              data-hex="<?php echo esc_attr( vx_marca_como_se_escribe( $valor )[0] ); ?>"
                              style="background:<?php echo esc_attr( $valor ); ?>"
                          <?php endif; ?>
                          aria-hidden="true"></span>
                    <span class="vx-dato"><?php echo esc_html( $nombre ); ?></span>
                    <span class="vx-dato vx-tenue"><?php
                        echo esc_html( $color ? vx_marca_como_se_escribe( $valor )[0] : $valor );
                    ?></span>
                    <span class="vx-dato vx-tenue"><?php
                        echo esc_html( $color ? vx_marca_como_se_escribe( $valor )[1] : '' );
                    ?></span>
                    <span class="vx-papel-oficio"><?php echo esc_html( $oficio ); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}

/**
 * Una combinación medida: la tinta sobre el fondo, con su razón debajo. La
 * muestra es lo que se juzga; el número está para no tener que juzgarla.
 */
function vx_marca_celda( string $tinta, string $fondo, float $minimo = 4.5 ): void {
    $mapa = vx_marca_mapa_tokens();
    $t    = vx_marca_resolver( $mapa[ $tinta ] ?? $tinta );
    $f    = vx_marca_resolver( $mapa[ $fondo ] ?? $fondo );
    $r    = vx_marca_contraste( $t, $f );
    $pasa = $r >= $minimo;
    ?>
    <div class="vx-celda">
        <?php // demostración a propósito: esta muestra existe para enseñar el contraste, incluso cuando falla. ?>
        <span class="vx-celda-muestra" data-demostracion="1"
              style="background:<?php echo esc_attr( $f ); ?>;color:<?php echo esc_attr( $t ); ?>" aria-hidden="true">Aa</span>
        <span class="vx-dato <?php echo $pasa ? 'vx-pasa' : 'vx-falla'; ?>">
            <?php echo esc_html( number_format( $r, 2, ',', '' ) ); ?>
            <?php if ( ! $pasa ) : ?>
                <i class="ti ti-x" aria-hidden="true"></i>
                <span class="vx-solo-lectores">no pasa</span>
            <?php endif; ?>
        </span>
    </div>
    <?php
}

/** Un peldaño de la escala tipográfica, escrito con el peldaño que documenta. */
function vx_marca_peldano_texto( string $token, string $uso, string $como ): void {
    $mapa  = vx_marca_mapa_tokens();
    $valor = vx_marca_resolver( $mapa[ $token ] ?? '' );
    // El tracking es parte del peldaño, así que se nombra y se aplica junto al
    // tamaño: un espécimen que no lleva su propio tracking no es el espécimen.
    $ls    = str_replace( '--fs-', '--ls-', $token );
    $tiene = isset( $mapa[ $ls ] );
    ?>
    <div class="vx-nivel">
        <div class="vx-nivel-cabeza">
            <span class="vx-dato"><?php echo esc_html( $token ); ?></span>
            <span class="vx-dato vx-tenue"><?php echo esc_html( $valor ); ?></span>
            <?php if ( $tiene ) : ?>
                <span class="vx-dato vx-tenue"><?php echo esc_html( vx_marca_resolver( $mapa[ $ls ] ) ); ?></span>
            <?php endif; ?>
            <span class="vx-nivel-uso"><?php echo esc_html( $uso ); ?></span>
        </div>
        <p class="vx-nivel-muestra" style="font-size:var(<?php echo esc_attr( $token ); ?>)<?php
            echo $tiene ? ';letter-spacing:var(' . esc_attr( $ls ) . ')' : ''; ?>">
            Conecta, colabora y crece
        </p>
        <p class="vx-nota"><?php echo esc_html( $como ); ?></p>
    </div>
    <?php
}

/** Dos columnas: lo que decimos y lo que no. */
function vx_marca_vocabulario( array $pares ): void {
    ?>
    <div class="vx-vocabulario">
        <div class="vx-vocabulario-cabeza">
            <span class="vx-sobretitulo">Decimos</span>
            <span class="vx-sobretitulo">No decimos</span>
        </div>
        <?php foreach ( $pares as [ $si, $no ] ) : ?>
            <div class="vx-vocabulario-fila">
                <span class="vx-si"><?php echo esc_html( $si ); ?></span>
                <span class="vx-no"><?php echo esc_html( $no ); ?></span>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
}
