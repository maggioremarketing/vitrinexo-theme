<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<!-- Tres columnas y una línea al pie, como en la propuesta. Antes había una
     fila de arriba con el logotipo y un botón, y cuatro columnas de enlaces:
     tres de las comunidades llevaban al mismo lugar, y «El problema» y
     «Perfiles» se leen bajando la página. -->
<footer class="footer-vx">
  <div class="container">
    <div class="footer-vx__grid">
      <div class="footer-vx__marca">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer-vx__brand" aria-label="Ir al inicio">
          <img src="<?php echo esc_url( vx_logo_url() ); ?>" alt="Vitrinexo">
        </a>
        <p class="footer-vx__text">Directorio B2B de empresas de servicios profesionales. Conecta, colabora y crece.</p>
      </div>
      <nav class="footer-vx__nav footer-vx__nav--dos" aria-label="Plataforma">
        <span class="footer-vx__label">La plataforma</span>
        <a class="footer-vx__link" href="<?php echo esc_url( home_url( '/' ) ); ?>#como-funciona">Cómo funciona</a>
        <a class="footer-vx__link" href="<?php echo esc_url( home_url( '/' ) ); ?>#comunidades">Comunidades</a>
        <a class="footer-vx__link" href="<?php echo esc_url( home_url( '/' ) ); ?>#for-dinner">Experiencia presencial</a>
        <a class="footer-vx__link" href="<?php echo esc_url( home_url( '/preguntas-frecuentes/' ) ); ?>">Preguntas frecuentes</a>
      </nav>
      <nav class="footer-vx__nav" aria-label="Cuenta y contacto">
        <span class="footer-vx__label">Cuenta</span>
        <a class="footer-vx__link" href="<?php echo esc_url( home_url( '/' ) ); ?>#afiliado-original">Inscríbete</a>
        <a class="footer-vx__link" href="<?php echo esc_url( home_url( '/login/' ) ); ?>">Ingresar</a>
        <a class="footer-vx__contact" href="mailto:hola@vitrinexo.com"><i class="ti ti-mail" aria-hidden="true"></i> hola@vitrinexo.com</a>
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
