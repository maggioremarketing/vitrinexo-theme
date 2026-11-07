<?php
/*
 * Template Name: Vitrinexo 4Dinner Público
 */
if ( ! defined( 'ABSPATH' ) ) exit;

if ( is_user_logged_in() ) {
    wp_safe_redirect( home_url( '/4dinner/' ) );
    exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class( 'vx-page-4dinner-public' ); ?>>

<?php get_template_part( 'partials/nav' ); ?>

<main>
  <?php echo do_shortcode( '[vx_landing_4dinner]' ); ?>
</main>

<?php get_template_part( 'partials/footer' ); ?>
<?php wp_footer(); ?>
</body>
</html>
