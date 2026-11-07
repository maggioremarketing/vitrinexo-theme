<?php
/*
 * Template Name: Vitrinexo 4Dinner (Privado)
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
<body <?php body_class( 'vx-page-4dinner' ); ?>>

<?php get_template_part( 'partials/nav-logged' ); ?>

<main>
  <?php echo do_shortcode( '[vx_4dinner]' ); ?>
</main>

<?php get_template_part( 'partials/footer-logged' ); ?>
<?php wp_footer(); ?>
</body>
</html>
