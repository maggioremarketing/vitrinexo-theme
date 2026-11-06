<?php
/*
 * Template Name: Preguntas frecuentes
 */
if ( ! defined( 'ABSPATH' ) ) exit;
$vx_is_logged = is_user_logged_in();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class( 'vx-page-faq' ); ?>>

<?php
// Miembro logueado → header/footer de miembro; visitante → header/footer público.
if ( $vx_is_logged ) {
    get_template_part( 'partials/nav-logged' );
} else {
    get_template_part( 'partials/nav' );
}
?>

<main>
  <div class="container" style="max-width:900px;padding-top:2.5rem;padding-bottom:3.5rem;">
    <header style="text-align:center;margin-bottom:2rem;">
      <h1 style="font-weight:800;margin-bottom:.5rem;letter-spacing:var(--ls-display);">Preguntas frecuentes</h1>
      <p style="color:var(--color-text-secondary,var(--color-text-secondary));max-width:620px;margin:0 auto;">
        Todo lo que necesitas saber sobre Vitrinexo. Si no encuentras tu respuesta, escríbenos a
        <a href="mailto:hola@vitrinexo.com">hola@vitrinexo.com</a>.
      </p>
    </header>
    <?php echo do_shortcode( '[vx_faq]' ); ?>
  </div>
</main>

<?php
if ( $vx_is_logged ) {
    get_template_part( 'partials/footer-logged' );
} else {
    get_template_part( 'partials/footer' );
}
?>
<?php wp_footer(); ?>
</body>
</html>
