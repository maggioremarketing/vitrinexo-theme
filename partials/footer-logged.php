<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<footer class="footer-vx footer-vx--logged">
  <div class="container">
    <div class="footer-vx__top">
      <a href="<?php echo esc_url( home_url( '/dashboard/' ) ); ?>" class="footer-vx__brand" aria-label="Ir al dashboard">
        <img src="<?php echo esc_url( vx_logo_url() ); ?>" alt="Vitrinexo">
      </a>
      <a href="<?php echo esc_url( home_url( '/editar-perfil/' ) ); ?>" class="btn-vx btn-soft-primary btn-vx-sm">
        <i class="ti ti-pencil"></i> Editar perfil
      </a>
    </div>
    <div class="footer-vx__grid">
      <div>
        <p class="footer-vx__text">Sigue construyendo tu vitrina, guardando nexos relevantes y activando nuevas conversaciones B2B.</p>
        <a class="footer-vx__contact" href="mailto:hola@vitrinexo.com">
          <i class="ti ti-mail"></i> hola@vitrinexo.com
        </a>
      </div>
      <nav class="footer-vx__nav" aria-label="Explorar">
        <span class="footer-vx__label">Explorar</span>
        <a class="footer-vx__link" href="<?php echo esc_url( home_url( '/directorio/' ) ); ?>">Directorio</a>
        <a class="footer-vx__link" href="<?php echo esc_url( home_url( '/nexos/' ) ); ?>">Mis nexos</a>
        <a class="footer-vx__link" href="<?php echo esc_url( home_url( '/favoritos/' ) ); ?>">Mis favoritos</a>
        <a class="footer-vx__link" href="<?php echo esc_url( home_url( '/4dinner/' ) ); ?>">4Dinner</a>
        <a class="footer-vx__link" href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">Blog</a>
        <a class="footer-vx__link" href="<?php echo esc_url( home_url( '/preguntas-frecuentes/' ) ); ?>">Preguntas frecuentes</a>
      </nav>
      <?php
      // Solo se listan las comunidades a las que el usuario pertenece.
      $vx_footer_user = class_exists( 'VX_User' ) ? VX_User::get( get_current_user_id() ) : null;
      $vx_footer_coms = [];
      if ( $vx_footer_user ) {
          foreach ( [
              'out2b'  => [ 'slug' => 'comunidad-out2b',  'label' => 'LGBTQ+' ],
              'woman'  => [ 'slug' => 'comunidad-woman',   'label' => 'Woman' ],
              'senior' => [ 'slug' => 'comunidad-senior',  'label' => 'Senior' ],
          ] as $vx_cid => $vx_cdata ) {
              if ( $vx_footer_user->is_in_community( $vx_cid ) ) {
                  $vx_footer_coms[] = $vx_cdata;
              }
          }
      }
      if ( $vx_footer_coms ) : ?>
      <nav class="footer-vx__nav" aria-label="Comunidades">
        <span class="footer-vx__label">Comunidades</span>
        <?php foreach ( $vx_footer_coms as $vx_c ) : ?>
        <a class="footer-vx__link" href="<?php echo esc_url( home_url( '/' . $vx_c['slug'] . '/' ) ); ?>"><?php echo esc_html( $vx_c['label'] ); ?></a>
        <?php endforeach; ?>
      </nav>
      <?php endif; ?>
      <nav class="footer-vx__nav" aria-label="Mi cuenta">
        <span class="footer-vx__label">Mi cuenta</span>
        <a class="footer-vx__link" href="<?php echo esc_url( home_url( '/dashboard/' ) ); ?>">Dashboard</a>
        <a class="footer-vx__link" href="<?php echo esc_url( home_url( '/perfil/' ) ); ?>">Mi perfil</a>
        <a class="footer-vx__link" href="<?php echo esc_url( home_url( '/conexiones/' ) ); ?>">Mis conexiones</a>
        <a class="footer-vx__link" href="<?php echo esc_url( home_url( '/notificaciones/' ) ); ?>">Notificaciones</a>
        <a class="footer-vx__link" href="<?php echo esc_url( home_url( '/configuracion/' ) ); ?>">Configuración</a>
        <a class="footer-vx__link" href="<?php echo esc_url( home_url( '/ayuda/' ) ); ?>">Ayuda</a>
        <a class="footer-vx__link" href="<?php echo esc_url( wp_logout_url( home_url( '/login/' ) ) ); ?>">Cerrar sesión</a>
      </nav>
    </div>
    <div class="footer-vx__bottom">
      <span>© <?php echo date( 'Y' ); ?> Vitrinexo SpA</span>
      <span>
        <a class="footer-vx__link" href="<?php echo esc_url( home_url( '/privacidad/' ) ); ?>">Privacidad</a> ·
        <a class="footer-vx__link" href="<?php echo esc_url( home_url( '/terminos/' ) ); ?>">Términos</a>
      </span>
    </div>
  </div>
</footer>
