<?php
/*
 * Template Name: Verificación pendiente
 */
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class( 'vx-page-verificacion-pendiente' ); ?> style="background:var(--hueso-2)">

<?php get_template_part( 'partials/nav' ); ?>

<main>
  <?php the_content(); ?>
</main>

<?php get_template_part( 'partials/footer' ); ?>
<?php wp_footer(); ?>
</body>
</html>
