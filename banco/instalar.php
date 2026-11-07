<?php
/* Instala el WordPress local y deja el tema y el plugin activos.
   Banco de pruebas: todo vive en 127.0.0.1 y nada sale de esta maquina. */
define( 'WP_INSTALLING', true );
require_once __DIR__ . '/wp/sitio/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/upgrade.php';
require_once ABSPATH . 'wp-admin/includes/plugin.php';

$clave = file_get_contents( __DIR__ . '/wp/clave.txt' );

if ( ! is_blog_installed() ) {
    $r = wp_install( 'Vitrinexo local', 'pruebas', 'pruebas@127.0.0.1', true, '', $clave );
    echo "instalado, usuario id {$r['user_id']}\n";
} else {
    // Misma razón que en miembros.php: sobre una base que ya existía, la clave
    // del administrador sería la del montaje anterior.
    $admin = get_user_by( 'login', 'pruebas' );
    if ( $admin ) wp_set_password( $clave, $admin->ID );
    echo "ya estaba instalado, clave puesta al día\n";
}

switch_theme( 'vitrinexo-theme' );
echo "tema: " . get_option( 'stylesheet' ) . "\n";

$r = activate_plugin( 'vitrinexo-core/vitrinexo-core.php' );
echo "plugin: " . ( is_wp_error( $r ) ? $r->get_error_message() : 'activo' ) . "\n";

update_option( 'permalink_structure', '/%postname%/' );
flush_rewrite_rules( true );
echo "enlaces permanentes: " . get_option( 'permalink_structure' ) . "\n";
