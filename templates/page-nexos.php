<?php
/*
 * Template Name: Nexos
 *
 * Se sirve por regla de reescritura desde inc/nexos.php, así que el contenido
 * no sale de una página de WordPress sino del shortcode que la ruta indica.
 */
if ( ! defined( 'ABSPATH' ) ) exit;
$vx_shortcode = $GLOBALS['vx_nexos_shortcode'] ?? 'vx_matches';
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class( 'vx-page-nexos' ); ?>>
<?php get_template_part( 'partials/nav-logged' ); ?>
<main>
  <?php echo do_shortcode( '[' . $vx_shortcode . ']' ); ?>
</main>
<?php get_template_part( 'partials/footer-logged' ); ?>
<?php wp_footer(); ?>
</body>
</html>
