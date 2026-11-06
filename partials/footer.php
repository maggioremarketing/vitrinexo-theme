<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<footer class="footer-vx">
  <div class="container">
    <div class="footer-vx__top">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer-vx__brand" aria-label="Ir al inicio">
        <img src="<?php echo esc_url( vx_logo_url() ); ?>" alt="Vitrinexo">
      </a>
      <a href="#afiliado-original" class="btn-vx btn-primary-vx btn-vx-sm">Inscríbete</a>
    </div>
    <div class="footer-vx__grid">
      <div>
        <p class="footer-vx__text">Directorio B2B de empresas de servicios profesionales. Conecta, colabora y crece.</p>
        <a class="footer-vx__contact" href="mailto:hola@vitrinexo.com">
          <i class="ti ti-mail"></i> hola@vitrinexo.com
        </a>
      </div>
      <nav class="footer-vx__nav" aria-label="Plataforma">
        <span class="footer-vx__label">La plataforma</span>
        <a class="footer-vx__link" href="#el-problema">El problema</a>
        <a class="footer-vx__link" href="#como-funciona">Cómo funciona</a>
        <a class="footer-vx__link" href="#para-quien">Para quién es</a>
        <a class="footer-vx__link" href="#for-dinner">Experiencia presencial</a>
        <a class="footer-vx__link" href="<?php echo esc_url( home_url( '/preguntas-frecuentes/' ) ); ?>">Preguntas frecuentes</a>
      </nav>
      <nav class="footer-vx__nav" aria-label="Comunidades">
        <span class="footer-vx__label">Comunidades</span>
        <a class="footer-vx__link" href="#el-multiverso">Vitrinexo LGBTQ+</a>
        <a class="footer-vx__link" href="#el-multiverso">Vitrinexo Woman</a>
        <a class="footer-vx__link" href="#el-multiverso">Vitrinexo Senior</a>
      </nav>
      <nav class="footer-vx__nav" aria-label="Cuenta">
        <span class="footer-vx__label">Cuenta</span>
        <a class="footer-vx__link" href="#afiliado-original">Inscríbete como Miembro Pionero</a>
        <a class="footer-vx__link" href="<?php echo esc_url( home_url( '/login/' ) ); ?>">Ingresar</a>
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
