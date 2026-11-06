<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// ─── Setup básico ─────────────────────────────────────────────────────────────

add_action( 'after_setup_theme', function () {
    load_theme_textdomain( 'vitrinexo', get_template_directory() . '/languages' );

    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ] );
    add_theme_support( 'custom-logo', [
        'height'      => 40,
        'width'       => 140,
        'flex-height' => true,
        'flex-width'  => true,
    ] );

    // Tamaños de imagen del sistema
    add_image_size( 'vx-avatar', 200, 200, true );
    add_image_size( 'vx-logo',   200, 200, false );
    add_image_size( 'vx-banner', 1200, 375, true );
    add_image_size( 'vx-card',   400, 400, true );

    // Menús de navegación
    register_nav_menus( [
        'primary'    => __( 'Menú principal', 'vitrinexo' ),
        'footer'     => __( 'Menú footer', 'vitrinexo' ),
        'social'     => __( 'Redes sociales', 'vitrinexo' ),
    ] );
} );

// ─── Enqueue assets ────────────────────────────────────────────────────────────

add_action( 'wp_enqueue_scripts', function () {
    $ver = wp_get_theme()->get( 'Version' );
    $uri = get_template_directory_uri();

    // Bootstrap 5.3.3
    wp_enqueue_style( 'bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css', [], '5.3.3' );

    // Tabler Icons 3.19.0
    wp_enqueue_style( 'tabler-icons', 'https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.19.0/dist/tabler-icons.min.css', [], '3.19.0' );

    // Sistema de diseño Vitrinexo (incluye Switzer Variable local vía @import en style.css)
    wp_enqueue_style( 'vitrinexo-design', $uri . '/assets/css/style.css', [ 'bootstrap', 'tabler-icons' ], $ver );

    // Bootstrap JS Bundle
    wp_enqueue_script( 'bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', [], '5.3.3', true );

    // JS principal del tema
    wp_enqueue_script( 'vitrinexo-main', $uri . '/assets/js/main.js', [ 'bootstrap' ], $ver, true );

    // Datos para JS (nonce, api_url, user_id)
    $user_id = get_current_user_id();
    wp_localize_script( 'vitrinexo-main', 'vx_data', [
        'nonce'      => wp_create_nonce( 'wp_rest' ),
        'api_url'    => rest_url( 'vitrinexo/v1/' ),
        'user_id'    => $user_id,
        'avatar_url' => $user_id ? vx_get_current_user_avatar_url() : '',
        'user_name'  => $user_id ? vx_get_current_user_short_name() : '',
        'user_slug'  => $user_id ? (string) get_user_meta( $user_id, 'vx_perfil_slug', true ) : '',
    ] );

    // Scripts condicionales por página
    if ( is_page( 'onboarding' ) ) {
        wp_enqueue_script( 'vitrinexo-onboarding', $uri . '/assets/js/onboarding.js', [ 'vitrinexo-main' ], $ver, true );
    }

    if ( is_page( [ 'directorio', 'busqueda' ] ) ) {
        wp_enqueue_script( 'vitrinexo-directorio', $uri . '/assets/js/directorio.js', [ 'vitrinexo-main' ], $ver, true );
    }

    if ( is_page( 'editar-perfil' ) ) {
        wp_enqueue_script( 'vitrinexo-editor', $uri . '/assets/js/editor-perfil.js', [ 'vitrinexo-main' ], $ver, true );
    }

    if ( is_front_page() || is_page( [ 'privacidad', 'terminos' ] ) ) {
        wp_enqueue_script( 'vitrinexo-network', $uri . '/assets/js/network.js', [], $ver, true );
    }
} );

// ─── Helpers para wp_localize_script ──────────────────────────────────────────

function vx_get_current_user_avatar_url(): string
{
    $user_id    = get_current_user_id();
    $foto_id    = (int) get_user_meta( $user_id, 'vx_foto', true );
    if ( $foto_id ) {
        $url = wp_get_attachment_image_url( $foto_id, 'vx-avatar' );
        if ( $url ) return $url;
    }
    // Sin foto: avatar generado (círculo Vitrinexo + iniciales).
    if ( function_exists( 'vx_avatar_placeholder_url' ) && function_exists( 'vx_iniciales_de' ) ) {
        $nombre   = (string) get_user_meta( $user_id, 'vx_nombre', true );
        $apellido = (string) get_user_meta( $user_id, 'vx_apellido', true );
        return vx_avatar_placeholder_url( vx_iniciales_de( $nombre, $apellido ) );
    }
    return get_avatar_url( $user_id, [ 'size' => 200 ] );
}

function vx_get_current_user_short_name(): string
{
    $user_id  = get_current_user_id();
    $nombre   = (string) get_user_meta( $user_id, 'vx_nombre', true );
    $apellido = (string) get_user_meta( $user_id, 'vx_apellido', true );
    if ( $nombre ) {
        return $nombre . ( $apellido ? ' ' . mb_substr( $apellido, 0, 1 ) . '.' : '' );
    }
    return wp_get_current_user()->display_name;
}

// ─── Eliminar la barra de admin para no admins ─────────────────────────────────

add_filter( 'show_admin_bar', function ( bool $show ): bool {
    return current_user_can( 'manage_options' ) ? $show : false;
} );

// ─── Título de página para páginas de cuenta ──────────────────────────────────

add_filter( 'document_title_parts', function ( array $title ): array {
    if ( is_page( 'dashboard' ) ) $title['title'] = 'Dashboard | Vitrinexo';
    return $title;
} );

// ─── La marca, en un solo lugar ────────────────────────────────────────────────

/**
 * La URL del archivo del logotipo, con la versión del tema pegada.
 *
 * La versión no es decoración: el CDN de Hostinger cachea los assets por URL,
 * y un <img src> sin query nunca cambia de URL. El archivo se actualizaba en el
 * servidor y los nodos del CDN seguían sirviendo el logotipo viejo durante
 * días, con curl trayendo uno y el navegador otro.
 */
function vx_logo_url(): string {
    return get_template_directory_uri() . '/assets/img/vitrinexo.svg?v=' . wp_get_theme()->get( 'Version' );
}

/**
 * El tagline oficial. Vive acá y no escrito a mano en cada plantilla: una
 * frase que se copia y se pega se desincroniza en la tercera copia, y la
 * tercera copia es siempre la que ve el cliente.
 */
function vx_tagline(): string {
    return 'Tu vitrina para construir nexos de negocio.';
}

// ─── Manual de marca ───────────────────────────────────────────────────────────
// La ruta /marca, su hoja y su regla de reescritura. Vive aparte porque es un
// documento que se genera del sistema de diseño, no una página del sitio.

require_once get_template_directory() . '/inc/marca/ruta.php';

// ─── Template loader por slug de página ────────────────────────────────────────

add_filter( 'template_include', function ( string $template ): string {
    // Blog archive (cuando la página "Blog" está configurada como Posts page en Reading)
    if ( is_home() ) {
        $tpl = get_template_directory() . '/templates/page-blog.php';
        if ( file_exists( $tpl ) ) return $tpl;
    }

    if ( ! is_page() ) return $template;

    // Use pagename query var (includes parent path for child pages) with fallback to post_name
    $pagename = (string) get_query_var( 'pagename' );
    $slug     = $pagename ?: get_post_field( 'post_name', get_queried_object_id() );

    $map  = [
        'home'                    => 'front-page',
        'login'                   => 'page-login',
        'confirmar-correo'        => 'page-confirmar-correo',
        'verificacion-pendiente'  => 'page-verificacion-pendiente',
        'onboarding'              => 'page-onboarding',
        'dashboard'               => 'page-dashboard',
        'directorio'              => 'page-directorio',
        'ayuda'                   => 'page-ayuda',
        'busqueda'                => 'page-search-results',
        'matches'                 => 'page-matches',
        'match-seeks'             => 'page-matches',
        'match-offers'            => 'page-matches',
        'perfil'                  => 'page-perfil',
        'editar-perfil'           => 'page-editor-perfil',
        'favoritos'               => 'page-favoritos',
        'conexiones'              => 'page-conexiones',
        'conexion-aceptada'       => 'page-conexion-aceptada',
        'conexion-rechazada'      => 'page-conexion-rechazada',
        'notificaciones'          => 'page-notificaciones',
        'configuracion'           => 'page-configuracion',
        'comunidad-out2b'         => 'page-comunidad',
        'comunidad-woman'         => 'page-comunidad',
        'comunidad-senior'        => 'page-comunidad',
        'landing-4dinner'         => 'page-4dinner-public',
        '4dinner'                 => 'page-4dinner',
        'mis-eventos'             => 'page-mis-eventos',
        'oportunidades'           => 'page-publicaciones',
        'preguntas-frecuentes'    => 'page-faq',
        'mis-publicaciones'       => 'page-mis-publicaciones',
        'blog'                    => 'page-blog',
        'marca'                   => 'page-marca',
        'recuperar-contrasena'    => 'page-recuperar-contrasena',
        'nueva-contrasena'        => 'page-nueva-contrasena',
    ];

    // También detectar por query var para perfil dinámico
    if ( get_query_var( 'vx_perfil_slug' ) ) {
        $tpl = get_template_directory() . '/templates/page-perfil.php';
        if ( file_exists( $tpl ) ) return $tpl;
    }

    if ( isset( $map[ $slug ] ) ) {
        $tpl = get_template_directory() . '/templates/' . $map[ $slug ] . '.php';
        if ( file_exists( $tpl ) ) return $tpl;
    }

    return $template;
} );

// ─── Google Analytics (GA4) ─────────────────────────────────────────────────

add_action( 'wp_head', function () {
    // No rastreamos a administradores para no ensuciar las métricas.
    if ( current_user_can( 'manage_options' ) ) {
        return;
    }
    ?>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-KQS9JSS56Y"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){ dataLayer.push( arguments ); }
      gtag( 'js', new Date() );
      gtag( 'config', 'G-KQS9JSS56Y' );
    </script>
    <?php
}, 1 );

// ─── Redirección 301: /publicaciones/ → /oportunidades/ ─────────────────────────

add_action( 'template_redirect', function () {
    $path = trim( parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ), '/' );
    if ( $path === 'publicaciones' ) {
        wp_safe_redirect( home_url( '/oportunidades/' ), 301 );
        exit;
    }
}, 1 );

// ─── 404 personalizado ────────────────────────────────────────────────────────

add_action( 'template_redirect', function () {
    if ( is_404() ) {
        $tpl = get_template_directory() . '/templates/page-404.php';
        if ( file_exists( $tpl ) ) {
            status_header( 404 );
            require $tpl; // Fix: require en lugar de include para fallar explícitamente
            exit;
        }
    }
} );

// ─── Comentarios del blog (hilos + reacción + avatar/enlace a perfil) ──────────

/** URL del perfil público de Vitrinexo de un usuario (o '' si no tiene slug). */
function vx_user_profile_url( int $user_id ): string {
    if ( ! $user_id ) return '';
    $slug = (string) get_user_meta( $user_id, 'vx_perfil_slug', true );
    return $slug ? home_url( '/perfil/' . $slug . '/' ) : '';
}

/**
 * Render de un comentario en estilo Vitrinexo.
 * - Avatar = foto de perfil (vía filtro pre_get_avatar_data).
 * - Nombre enlaza al perfil del autor (si es miembro con slug).
 * - Fecha en texto plano (sin hipervínculo).
 * - Botón "Responder" (hilos) y reacción (me gusta).
 * NOTA: abre el <li> sin cerrarlo; Walker_Comment cierra tras los hijos.
 */
function vx_render_comment( $comment, $args, $depth ) {
    $uid         = (int) $comment->user_id;
    $author      = get_comment_author( $comment );
    $profile_url = vx_user_profile_url( $uid );
    $is_logged   = is_user_logged_in();
    $cid         = (int) $comment->comment_ID;

    $rc      = function_exists( 'vx_comentario_reacciones_count' ) ? vx_comentario_reacciones_count( $cid ) : 0;
    $reacted = ( $is_logged && function_exists( 'vx_usuario_reacciono_comentario' ) )
        ? vx_usuario_reacciono_comentario( $cid, get_current_user_id() ) : false;

    $reply = '';
    if ( $is_logged ) {
        $reply = get_comment_reply_link( array_merge( $args, [
            'reply_text' => '<i class="ti ti-arrow-back-up"></i> Responder',
            'depth'      => $depth,
            'max_depth'  => $args['max_depth'] ?? 4,
        ] ), $comment );
    }
    ?>
    <li <?php comment_class( 'vx-comment', $comment ); ?> id="comment-<?php echo $cid; ?>">
      <div class="vx-comment__row" style="display:flex;gap:12px;padding:14px 0;border-top:1px solid var(--color-border)">
        <?php echo get_avatar( $comment, 40, '', $author, [ 'class' => 'vx-comment__avatar', 'style' => 'width:40px;height:40px;border-radius:var(--radius-sm);object-fit:cover;flex-shrink:0' ] ); ?>
        <div style="flex:1;min-width:0">
          <div class="d-flex align-items-center gap-2 flex-wrap" style="margin-bottom:2px">
            <?php if ( $profile_url ) : ?>
              <a href="<?php echo esc_url( $profile_url ); ?>" class="text-body-label link-primary-color" style="font-weight:600"><?php echo esc_html( $author ); ?></a>
            <?php else : ?>
              <span class="text-body-label" style="font-weight:600"><?php echo esc_html( $author ); ?></span>
            <?php endif; ?>
            <span class="text-xs-muted"><?php echo esc_html( get_comment_date( 'j M Y · H:i', $comment ) ); ?></span>
          </div>
          <div class="vx-comment__text" style="color:var(--color-text-primary);line-height:1.55;margin-bottom:6px">
            <?php comment_text( $comment ); ?>
          </div>
          <div class="d-flex align-items-center gap-3" style="font-size:13px">
            <?php if ( $is_logged ) : ?>
            <button class="vx-creact-btn btn-plain-vx" data-id="<?php echo $cid; ?>" data-reacted="<?php echo $reacted ? '1' : '0'; ?>"
                    style="background:none;border:none;cursor:pointer;padding:0;color:<?php echo $reacted ? 'var(--color-primary)' : 'var(--color-text-secondary)'; ?>;font-weight:600;display:inline-flex;align-items:center;gap:4px">
              <i class="ti ti-heart<?php echo $reacted ? '-filled' : ''; ?>"></i> <span class="vx-creact-count"><?php echo (int) $rc; ?></span>
            </button>
            <?php else : ?>
            <span class="text-xs-muted" style="display:inline-flex;align-items:center;gap:4px"><i class="ti ti-heart"></i> <?php echo (int) $rc; ?></span>
            <?php endif; ?>
            <?php if ( $reply ) : ?>
            <span class="vx-comment__reply" style="font-size:13px"><?php echo $reply; ?></span>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php
    // Sin </li>: lo cierra Walker_Comment tras renderizar las respuestas anidadas.
}
