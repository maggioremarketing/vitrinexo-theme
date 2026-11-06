<?php
/**
 * Las rutas de nexos, y las redirecciones desde las viejas.
 *
 * «Match» es palabra prohibida por el manual: es de las aplicaciones de citas
 * y acá nadie busca pareja. En el idioma de la marca una conexión de negocio
 * es un nexo, y las dos caras ya tenían nombre en la portada: lo que ofreces y
 * lo que buscas.
 *
 * Las rutas nuevas se sirven desde el código y no desde una página de
 * WordPress, por lo mismo que /marca: así el nombre no depende de que nadie
 * renombre un slug en el escritorio. Las viejas redirigen con 301 permanente,
 * porque son enlaces que los miembros pueden tener guardados y que los correos
 * ya enviados siguen apuntando.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

const VX_NEXOS_REGLAS = '1';

/** La ruta nueva, el shortcode que la llena y de qué ruta vieja viene. */
function vx_nexos_rutas(): array {
    return [
        'nexos'              => [ 'vx_matches',      'matches' ],
        'nexos-que-busco'    => [ 'vx_match_seeks',  'match-seeks' ],
        'nexos-que-ofrezco'  => [ 'vx_match_offers', 'match-offers' ],
    ];
}

add_action( 'init', function () {
    foreach ( array_keys( vx_nexos_rutas() ) as $ruta ) {
        add_rewrite_rule( '^' . $ruta . '/?$', 'index.php?vx_nexos=' . $ruta, 'top' );
    }
    if ( get_option( 'vx_nexos_reglas' ) !== VX_NEXOS_REGLAS ) {
        flush_rewrite_rules( false );
        update_option( 'vx_nexos_reglas', VX_NEXOS_REGLAS, false );
    }
} );

add_filter( 'query_vars', function ( array $vars ): array {
    $vars[] = 'vx_nexos';
    return $vars;
} );

/**
 * Las direcciones viejas redirigen permanente. Van en prioridad 1, antes del
 * 404 del tema, porque estas páginas sí existen en WordPress y no queremos
 * que se sirvan por su slug viejo.
 */
add_action( 'template_redirect', function () {
    $camino = trim( (string) parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ), '/' );
    foreach ( vx_nexos_rutas() as $nueva => [ , $vieja ] ) {
        if ( $camino === $vieja ) {
            $consulta = $_SERVER['QUERY_STRING'] ?? '';
            wp_redirect( home_url( '/' . $nueva . '/' ) . ( $consulta ? '?' . $consulta : '' ), 301 );
            exit;
        }
    }
}, 1 );

/** Y la ruta nueva se sirve con la plantilla y el shortcode que le toca. */
add_action( 'template_redirect', function () {
    $ruta = (string) get_query_var( 'vx_nexos' );
    if ( ! $ruta ) return;

    $rutas = vx_nexos_rutas();
    if ( ! isset( $rutas[ $ruta ] ) ) return;

    status_header( 200 );
    global $wp_query;
    $wp_query->is_404 = false;

    $GLOBALS['vx_nexos_shortcode'] = $rutas[ $ruta ][0];
    require get_template_directory() . '/templates/page-nexos.php';
    exit;
}, 1 );
