<?php
/**
 * Lee los tokens del sistema de diseño desde el propio CSS del tema.
 *
 * El manual se arma con esto y no con una lista escrita a mano: si alguien
 * cambia un token en assets/css/style.css, la página de marca cambia sola. Un
 * manual que se puede desincronizar del código no sirve de nada, y por eso
 * este no tiene versión: tiene commit.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/** El archivo que manda. Un solo lugar, para que no haya dos verdades. */
function vx_marca_archivo_tokens(): string {
    return get_template_directory() . '/assets/css/style.css';
}

/**
 * Los tokens del bloque :root, agrupados por los comentarios de sección que
 * trae el propio CSS. El comentario titula el grupo; el grupo sin comentario
 * arriba se queda sin título y no se pinta.
 *
 * @return array<int,array{titulo:string,tokens:array<int,array{nombre:string,valor:string}>}>
 */
function vx_marca_grupos_tokens(): array {
    static $cache = null;
    if ( $cache !== null ) return $cache;

    $css = (string) @file_get_contents( vx_marca_archivo_tokens() );
    // El archivo empieza con BOM y el BOM se cuela en el primer nombre.
    $css = preg_replace( '/^\xEF\xBB\xBF/', '', $css );

    // Solo el primer :root. Lo que venga después es tema de componente.
    if ( ! preg_match( '/:root\s*\{(.*?)\n\}/s', $css, $m ) ) {
        return $cache = [];
    }

    // Un comentario al final de una línea explica el valor que tiene al lado;
    // no abre familia. Se quitan antes de partir, así solo titula grupo el
    // comentario que va solo en su línea.
    $bloque = preg_replace( '/(?<=\S)[ \t]*\/\*.*?\*\//s', '', $m[1] );

    $grupos = [];
    $actual = [ 'titulo' => '', 'tokens' => [] ];

    foreach ( preg_split( '/(\/\*[^*]*\*\/)/', $bloque, -1, PREG_SPLIT_DELIM_CAPTURE ) as $parte ) {
        // Con /s, igual que la prueba: un comentario de varias líneas también
        // titula grupo. Sin el modificador, el lector y la prueba leían grupos
        // distintos y la comprobación medía algo que la página no pintaba.
        if ( preg_match( '/^\/\*\s*(.+?)\s*\*\/$/s', trim( $parte ), $c ) ) {
            $c[1] = trim( explode( "\n", $c[1] )[0] );
            if ( $actual['tokens'] ) $grupos[] = $actual;
            $actual = [ 'titulo' => $c[1], 'tokens' => [] ];
            continue;
        }
        if ( preg_match_all( '/--([\w-]+)\s*:\s*([^;}]+)/', $parte, $pares, PREG_SET_ORDER ) ) {
            foreach ( $pares as $p ) {
                $actual['tokens'][] = [ 'nombre' => '--' . $p[1], 'valor' => trim( $p[2] ) ];
            }
        }
    }
    if ( $actual['tokens'] ) $grupos[] = $actual;

    return $cache = $grupos;
}

/** Todos los tokens aplanados en un mapa nombre → valor. */
function vx_marca_mapa_tokens(): array {
    static $mapa = null;
    if ( $mapa !== null ) return $mapa;

    $mapa = [];
    foreach ( vx_marca_grupos_tokens() as $g ) {
        foreach ( $g['tokens'] as $t ) $mapa[ $t['nombre'] ] = $t['valor'];
    }
    return $mapa;
}

/**
 * Resuelve un valor hasta el literal: salta los var() encadenados. El tope de
 * saltos no es paranoia, es que un var() que se apunta a sí mismo cuelga la
 * página entera.
 */
function vx_marca_resolver( string $valor, ?array $mapa = null, int $saltos = 8 ): string {
    $mapa = $mapa ?? vx_marca_mapa_tokens();
    $v    = trim( $valor );

    if ( preg_match( '/^var\(\s*(--[\w-]+)\s*(?:,[^)]*)?\)$/', $v, $m ) && $saltos > 0 ) {
        return isset( $mapa[ $m[1] ] )
            ? vx_marca_resolver( $mapa[ $m[1] ], $mapa, $saltos - 1 )
            : $v;
    }
    return $v;
}

/** true si el valor se puede pintar como muestra. */
function vx_marca_es_color( string $valor ): bool {
    return (bool) preg_match( '/^(#|rgb|hsl|oklch)/i', trim( $valor ) );
}

/** Cuánto deja pasar un color. Lo que no declara opacidad es opaco. */
function vx_marca_opacidad( string $valor ): float {
    $v = trim( $valor );
    if ( preg_match( '/^rgba\(\s*[\d.]+\s*,\s*[\d.]+\s*,\s*[\d.]+\s*,\s*([\d.]+)\s*\)$/i', $v, $m ) ) {
        return (float) $m[1];
    }
    if ( preg_match( '/^#[0-9a-f]{6}([0-9a-f]{2})$/i', $v, $m ) ) {
        return hexdec( $m[1] ) / 255;
    }
    return 1.0;
}

/**
 * Cómo se escribe un color para leerlo: el hexadecimal de seis dígitos y su
 * opacidad al lado, siempre, también cuando es 100%.
 *
 * Escribirla solo donde no es 100% deja al único color translúcido como la
 * única fila con un campo de más, y una fila con un campo de más parece un
 * error en vez de un valor. Va en porcentaje y no en los dos dígitos que el
 * hexadecimal admite al final: nadie lee 8F como 56%.
 *
 * @return array{0:string,1:string} [hexadecimal, opacidad]
 */
function vx_marca_como_se_escribe( string $valor ): array {
    $v = trim( $valor );

    if ( preg_match( '/^rgba?\(\s*([\d.]+)\s*,\s*([\d.]+)\s*,\s*([\d.]+)\s*(?:,\s*[\d.]+\s*)?\)$/i', $v, $m ) ) {
        $hex = '#' . strtoupper(
            str_pad( dechex( (int) round( (float) $m[1] ) ), 2, '0', STR_PAD_LEFT ) .
            str_pad( dechex( (int) round( (float) $m[2] ) ), 2, '0', STR_PAD_LEFT ) .
            str_pad( dechex( (int) round( (float) $m[3] ) ), 2, '0', STR_PAD_LEFT )
        );
    } else {
        $hex = strtoupper( preg_replace( '/^(#[0-9a-f]{6})[0-9a-f]{2}$/i', '$1', $v ) );
    }

    return [ $hex, round( vx_marca_opacidad( $v ) * 100 ) . '%' ];
}

/**
 * La razón de contraste entre dos colores, por WCAG 2.1. Se usa para imprimir
 * el número al lado de la muestra; lo que de verdad manda es la medición sobre
 * la página renderizada, que resuelve lo que quedó debajo.
 */
function vx_marca_contraste( string $a, string $b ): float {
    $l = static function ( string $hex ): float {
        $hex = ltrim( trim( $hex ), '#' );
        if ( strlen( $hex ) === 3 ) $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        $canal = static function ( float $c ): float {
            $c /= 255;
            return $c <= 0.03928 ? $c / 12.92 : pow( ( $c + 0.055 ) / 1.055, 2.4 );
        };
        return 0.2126 * $canal( (float) hexdec( substr( $hex, 0, 2 ) ) )
             + 0.7152 * $canal( (float) hexdec( substr( $hex, 2, 2 ) ) )
             + 0.0722 * $canal( (float) hexdec( substr( $hex, 4, 2 ) ) );
    };

    $x = $l( vx_marca_como_se_escribe( $a )[0] );
    $y = $l( vx_marca_como_se_escribe( $b )[0] );
    return ( max( $x, $y ) + 0.05 ) / ( min( $x, $y ) + 0.05 );
}
