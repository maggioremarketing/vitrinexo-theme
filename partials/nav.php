<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<!-- Barra fija superior -->
<header class="vx-topbar">
  <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="vx-topbar__logo">
    <img src="<?php echo esc_url( vx_logo_url() ); ?>" alt="Vitrinexo" height="26">
  </a>

  <!-- Links desktop -->
  <nav class="vx-topbar__nav-desktop">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>#el-problema">El problema</a>
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>#como-funciona">Cómo funciona</a>
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>#perfiles">Perfiles</a>
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>#comunidades">Comunidades</a>
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>#for-dinner">Experiencia presencial</a>
    <a href="<?php echo esc_url( home_url( '/preguntas-frecuentes/' ) ); ?>">Preguntas frecuentes</a>
  </nav>

  <!-- Acciones desktop -->
  <div class="vx-topbar__actions-desktop">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>#afiliado-original" class="btn-vx btn-primary-vx btn-vx-sm">Inscríbete</a>
    <a href="<?php echo esc_url( home_url( '/login/' ) ); ?>" class="btn-vx btn-ghost-vx btn-vx-sm">Ingresar</a>
  </div>

  <!-- Hamburguesa solo en mobile -->
  <button class="vx-topbar__toggle" data-bs-toggle="offcanvas" data-bs-target="#vxDrawer" aria-controls="vxDrawer" aria-label="Abrir menú">
    <span></span><span></span><span></span>
  </button>
</header>

<!-- Drawer lateral (solo mobile) -->
<div class="offcanvas offcanvas-end vx-drawer" tabindex="-1" id="vxDrawer" aria-labelledby="vxDrawerLabel">
  <div class="vx-drawer__header">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
      <img src="<?php echo esc_url( vx_logo_url() ); ?>" alt="Vitrinexo" height="24">
    </a>
    <button type="button" class="vx-drawer__close" data-bs-dismiss="offcanvas" aria-label="Cerrar">
      <i class="ti ti-x"></i>
    </button>
  </div>
  <nav class="vx-drawer__nav">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>#el-problema"   data-bs-dismiss="offcanvas">El problema</a>
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>#como-funciona" data-bs-dismiss="offcanvas">Cómo funciona</a>
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>#perfiles"    data-bs-dismiss="offcanvas">Perfiles</a>
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>#comunidades" data-bs-dismiss="offcanvas">Comunidades</a>
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>#for-dinner"    data-bs-dismiss="offcanvas">Experiencia presencial</a>
    <a href="<?php echo esc_url( home_url( '/preguntas-frecuentes/' ) ); ?>" data-bs-dismiss="offcanvas">Preguntas frecuentes</a>
  </nav>
  <div class="vx-drawer__actions">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>#afiliado-original" class="btn-vx btn-primary-vx btn-vx-sm w-100 justify-content-center mb-2" data-bs-dismiss="offcanvas">Inscríbete</a>
    <a href="<?php echo esc_url( home_url( '/login/' ) ); ?>" class="btn-vx btn-ghost-vx btn-vx-sm w-100 justify-content-center">Ingresar</a>
  </div>
</div>
