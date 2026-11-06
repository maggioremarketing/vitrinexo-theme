<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$user_id    = get_current_user_id();
$nombre     = vx_get_current_user_short_name();
$avatar_url = vx_get_current_user_avatar_url();
$slug       = (string) get_user_meta( $user_id, 'vx_perfil_slug', true );
$unread     = class_exists( 'VX_Notification' ) ? VX_Notification::count_unread( $user_id ) : 0;
$perfil_url = $slug ? home_url( '/perfil/' . $slug . '/' ) : home_url( '/editar-perfil/' );

// Comunidades a las que el usuario pertenece
$vx_user_nav     = class_exists( 'VX_User' ) ? VX_User::get( $user_id ) : null;
$mis_comunidades = [];
if ( $vx_user_nav ) {
    $coms_map = [
        'out2b'  => [ 'slug' => 'comunidad-out2b',  'label' => 'LGBTQ+',  'icon' => 'ti-rainbow',       'color' => '#a855f7' ],
        'woman'  => [ 'slug' => 'comunidad-woman',   'label' => 'Woman',  'icon' => 'ti-gender-female', 'color' => '#ec4899' ],
        'senior' => [ 'slug' => 'comunidad-senior',  'label' => 'Senior', 'icon' => 'ti-award',         'color' => '#d97706' ],
    ];
    foreach ( $coms_map as $id => $data ) {
        if ( $vx_user_nav->is_in_community( $id ) ) {
            $mis_comunidades[] = $data;
        }
    }
}
$comunidad_activa = is_page( 'comunidad-out2b' ) || is_page( 'comunidad-woman' ) || is_page( 'comunidad-senior' );
?>

<!-- Barra fija superior (mismo formato que la landing) -->
<header class="vx-topbar">
  <a href="<?php echo esc_url( home_url( '/dashboard/' ) ); ?>" class="vx-topbar__logo">
    <img src="<?php echo esc_url( vx_logo_url() ); ?>" alt="Vitrinexo" height="26">
  </a>

  <!-- Navegación desktop -->
  <nav class="vx-topbar__nav-desktop">
    <a href="<?php echo esc_url( home_url( '/directorio/' ) ); ?>"<?php echo is_page( 'directorio' ) ? ' class="active"' : ''; ?>>Directorio</a>
    <a href="<?php echo esc_url( home_url( '/matches/' ) ); ?>"<?php echo is_page( 'matches' ) ? ' class="active"' : ''; ?>>Matches</a>
    <a href="<?php echo esc_url( home_url( '/conexiones/' ) ); ?>"<?php echo is_page( 'conexiones' ) ? ' class="active"' : ''; ?>>Conexiones</a>
    <a href="<?php echo esc_url( home_url( '/favoritos/' ) ); ?>"<?php echo is_page( 'favoritos' ) ? ' class="active"' : ''; ?>>Favoritos</a>
    <?php if ( ! empty( $mis_comunidades ) ) : ?>
    <div class="dropdown">
      <a href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"<?php echo $comunidad_activa ? ' class="active"' : ''; ?>>Comunidades <i class="ti ti-chevron-down" style="font-size:.72em;vertical-align:middle"></i></a>
      <ul class="dropdown-menu dropdown-vx border-0 shadow" style="padding:6px 0;min-width:180px">
        <?php foreach ( $mis_comunidades as $com ) : ?>
        <li><a class="dropdown-vx-item" href="<?php echo esc_url( home_url( '/' . $com['slug'] . '/' ) ); ?>"><i class="ti <?php echo esc_attr( $com['icon'] ); ?>" style="color:<?php echo esc_attr( $com['color'] ); ?>"></i><?php echo esc_html( $com['label'] ); ?></a></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <?php endif; ?>
    <a href="<?php echo esc_url( home_url( '/4dinner/' ) ); ?>"<?php echo is_page( '4dinner' ) ? ' class="active"' : ''; ?>>4Dinner</a>
    <a href="<?php echo esc_url( home_url( '/oportunidades/' ) ); ?>"<?php echo is_page( 'oportunidades' ) ? ' class="active"' : ''; ?>>Oportunidades</a>
    <a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>"<?php echo is_page( 'blog' ) ? ' class="active"' : ''; ?>>Blog</a>
    <a href="<?php echo esc_url( home_url( '/preguntas-frecuentes/' ) ); ?>"<?php echo is_page( 'preguntas-frecuentes' ) ? ' class="active"' : ''; ?>>Preguntas frecuentes</a>
  </nav>

  <!-- Acciones desktop: notificaciones + menú de cuenta -->
  <div class="vx-topbar__actions-desktop">
    <a href="<?php echo esc_url( home_url( '/notificaciones/' ) ); ?>" class="btn-vx btn-ghost-vx btn-vx-sm btn-vx-icon-sm position-relative" aria-label="Notificaciones">
      <i class="ti ti-bell"></i>
      <span class="notif-dot<?php echo $unread > 0 ? '' : ' d-none'; ?>" id="vx-notif-badge" data-count="<?php echo (int) $unread; ?>"></span>
    </a>
    <div class="dropdown">
      <button class="btn-vx btn-ghost-vx btn-vx-sm d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="<?php echo esc_url( $avatar_url ); ?>" alt="<?php echo esc_attr( $nombre ); ?>" class="nav-avatar">
        <span class="nav-username"><?php echo esc_html( $nombre ); ?></span>
        <i class="ti ti-chevron-down nav-chevron"></i>
      </button>
      <ul class="dropdown-menu dropdown-menu-end dropdown-vx border-0 shadow" style="padding:6px 0;min-width:200px">
        <li><a class="dropdown-vx-item" href="<?php echo esc_url( home_url( '/dashboard/' ) ); ?>"><i class="ti ti-layout-dashboard"></i>Dashboard</a></li>
        <li><a class="dropdown-vx-item" href="<?php echo esc_url( $perfil_url ); ?>"><i class="ti ti-user"></i>Perfil</a></li>
        <li><a class="dropdown-vx-item" href="<?php echo esc_url( home_url( '/editar-perfil/' ) ); ?>"><i class="ti ti-pencil"></i>Editar perfil</a></li>
        <li><a class="dropdown-vx-item" href="<?php echo esc_url( home_url( '/notificaciones/' ) ); ?>"><i class="ti ti-bell"></i>Notificaciones</a></li>
        <li><a class="dropdown-vx-item" href="<?php echo esc_url( home_url( '/configuracion/' ) ); ?>"><i class="ti ti-settings"></i>Configuración</a></li>
        <li><a class="dropdown-vx-item" href="<?php echo esc_url( home_url( '/ayuda/' ) ); ?>"><i class="ti ti-help-circle"></i>Ayuda</a></li>
        <li><hr class="dropdown-vx-divider my-1"></li>
        <li><a class="dropdown-vx-item danger" href="<?php echo esc_url( wp_logout_url( home_url( '/login/' ) ) ); ?>"><i class="ti ti-logout"></i>Cerrar sesión</a></li>
      </ul>
    </div>
  </div>

  <!-- Hamburguesa solo en mobile -->
  <button class="vx-topbar__toggle" data-bs-toggle="offcanvas" data-bs-target="#vxDrawer" aria-controls="vxDrawer" aria-label="Abrir menú">
    <span></span><span></span><span></span>
  </button>
</header>

<!-- Drawer lateral (solo mobile) -->
<div class="offcanvas offcanvas-end vx-drawer" tabindex="-1" id="vxDrawer" aria-labelledby="vxDrawerLabel">
  <div class="vx-drawer__header">
    <a href="<?php echo esc_url( home_url( '/dashboard/' ) ); ?>">
      <img src="<?php echo esc_url( vx_logo_url() ); ?>" alt="Vitrinexo" height="24">
    </a>
    <button type="button" class="vx-drawer__close" data-bs-dismiss="offcanvas" aria-label="Cerrar">
      <i class="ti ti-x"></i>
    </button>
  </div>
  <nav class="vx-drawer__nav">
    <a href="<?php echo esc_url( home_url( '/dashboard/' ) ); ?>"     data-bs-dismiss="offcanvas">Dashboard</a>
    <a href="<?php echo esc_url( home_url( '/directorio/' ) ); ?>"    data-bs-dismiss="offcanvas">Directorio</a>
    <a href="<?php echo esc_url( home_url( '/matches/' ) ); ?>"       data-bs-dismiss="offcanvas">Matches</a>
    <a href="<?php echo esc_url( home_url( '/conexiones/' ) ); ?>"    data-bs-dismiss="offcanvas">Conexiones</a>
    <a href="<?php echo esc_url( home_url( '/favoritos/' ) ); ?>"     data-bs-dismiss="offcanvas">Favoritos</a>
    <?php foreach ( $mis_comunidades as $com ) : ?>
    <a href="<?php echo esc_url( home_url( '/' . $com['slug'] . '/' ) ); ?>" data-bs-dismiss="offcanvas"><?php echo esc_html( $com['label'] ); ?></a>
    <?php endforeach; ?>
    <a href="<?php echo esc_url( home_url( '/4dinner/' ) ); ?>"       data-bs-dismiss="offcanvas">4Dinner</a>
    <a href="<?php echo esc_url( home_url( '/oportunidades/' ) ); ?>" data-bs-dismiss="offcanvas">Oportunidades</a>
    <a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>"          data-bs-dismiss="offcanvas">Blog</a>
    <a href="<?php echo esc_url( home_url( '/preguntas-frecuentes/' ) ); ?>" data-bs-dismiss="offcanvas">Preguntas frecuentes</a>
    <a href="<?php echo esc_url( home_url( '/notificaciones/' ) ); ?>" data-bs-dismiss="offcanvas">Notificaciones</a>
    <a href="<?php echo esc_url( $perfil_url ); ?>"                    data-bs-dismiss="offcanvas">Perfil</a>
    <a href="<?php echo esc_url( home_url( '/configuracion/' ) ); ?>" data-bs-dismiss="offcanvas">Configuración</a>
    <a href="<?php echo esc_url( home_url( '/ayuda/' ) ); ?>"          data-bs-dismiss="offcanvas">Ayuda</a>
  </nav>
  <div class="vx-drawer__actions">
    <a href="<?php echo esc_url( wp_logout_url( home_url( '/login/' ) ) ); ?>" class="btn-vx btn-ghost-vx btn-vx-sm w-100 justify-content-center"><i class="ti ti-logout"></i> Cerrar sesión</a>
  </div>
</div>
