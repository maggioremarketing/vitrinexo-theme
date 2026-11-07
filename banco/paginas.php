<?php
/* Las paginas del area de miembros, cada una con su shortcode, como en produccion. */
require_once __DIR__ . '/wp/sitio/wp-load.php';

$paginas = [
  'dashboard'        => [ 'Panel',              'vx_dashboard' ],
  'perfil'           => [ 'Mi perfil',          'vx_perfil' ],
  'editar-perfil'    => [ 'Editar perfil',      'vx_editor_perfil' ],
  'directorio'       => [ 'Directorio',         'vx_directorio' ],
  'conexiones'       => [ 'Conexiones',         'vx_conexiones' ],
  'favoritos'        => [ 'Favoritos',          'vx_favoritos' ],
  'notificaciones'   => [ 'Notificaciones',     'vx_notificaciones' ],
  'configuracion'    => [ 'Configuración',      'vx_configuracion' ],
  'nexos'            => [ 'Nexos',              'vx_matches' ],
  'mis-eventos'      => [ 'Mis eventos',        'vx_mis_eventos' ],
  'mis-publicaciones'=> [ 'Mis publicaciones',  'vx_mis_publicaciones' ],
  'feed'             => [ 'Feed',               'vx_feed' ],
  'ayuda'            => [ 'Ayuda',              'vx_ayuda' ],
  'onboarding'       => [ 'Onboarding',         'vx_onboarding' ],
  'login'            => [ 'Ingresar',           'vx_login' ],
  'home'             => [ 'Inicio',             'vx_landing' ],
  'preguntas-frecuentes' => [ 'Preguntas frecuentes', 'vx_faq' ],
  // El blog entra ahora porque entro en RUTAS. Hasta hoy no estaba en ninguna
  // de las dos, y por eso su cabecera sirvio «ecosistema» en produccion
  // durante meses con la suite en verde.
  'blog'             => [ 'Blog',               'vx_blog' ],
  /* El evento y las tres comunidades, que el banco no creaba y nadie medía.
     Los títulos son los mismos que pone el plugin al activarse. */
  '4dinner'          => [ 'Vitrinexo 4Dinner',  'vx_4dinner' ],
  'landing-4dinner'  => [ 'Vitrinexo 4Dinner: Sobre el evento', 'vx_landing_4dinner' ],
  'comunidad-out2b'  => [ 'Vitrinexo LGBTQ+',   'vx_comunidad slug="out2b"' ],
  'comunidad-woman'  => [ 'Vitrinexo Woman',    'vx_comunidad slug="woman"' ],
  'comunidad-senior' => [ 'Vitrinexo Senior',   'vx_comunidad slug="senior"' ],
];

/* Las dos legales no tienen shortcode: su contenido lo escribe alguien en el
   escritorio, y acá no se puede reproducir. Lo que sí se puede medir, y es lo
   que importa, es lo que las rodea: la plantilla page.php, el nav, el pie y la
   animación de red detrás. El cuerpo va corto y lo dice. */
$legales = [
  'privacidad' => 'Política de privacidad',
  'terminos'   => 'Términos y condiciones',
];

foreach ( $paginas as $slug => [ $titulo, $sc ] ) {
    $existe = get_page_by_path( $slug );
    if ( $existe ) { echo "  = $slug\n"; continue; }
    $id = wp_insert_post( [
        'post_type' => 'page', 'post_status' => 'publish',
        'post_title' => $titulo, 'post_name' => $slug,
        'post_content' => "[$sc]",
    ] );
    echo is_wp_error( $id ) ? "  ! $slug: " . $id->get_error_message() . "\n" : "  + $slug\n";
}

foreach ( $legales as $slug => $titulo ) {
    if ( get_page_by_path( $slug ) ) { echo "  = $slug\n"; continue; }
    $id = wp_insert_post( [
        'post_type' => 'page', 'post_status' => 'publish',
        'post_title' => $titulo, 'post_name' => $slug,
        'post_content' => "<p>Texto de banco. El contenido real de esta página se escribe en el escritorio; acá solo se mide la plantilla que lo envuelve.</p>",
    ] );
    echo is_wp_error( $id ) ? "  ! $slug: " . $id->get_error_message() . "\n" : "  + $slug\n";
}

$home = get_page_by_path( 'home' );
if ( $home ) { update_option( 'show_on_front', 'page' ); update_option( 'page_on_front', $home->ID ); }
flush_rewrite_rules( true );
echo "listo\n";
