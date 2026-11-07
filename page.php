<?php
if ( ! defined( 'ABSPATH' ) ) exit;

the_post();
$title   = get_the_title();
$content = get_the_content();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
  <?php /* Los estilos legales viven en assets/css/style.css, con los papeles
     del sistema. Este head tenia una copia entera, con la fuente y el tracking
     escritos a mano, y era la que de verdad pintaba la pagina: la copia
     sistematizada se habia renombrado y llevaba meses sin aplicarse. */ ?>
</head>
<body <?php body_class( 'vx-page-legal' ); ?>>

<?php get_template_part( 'partials/nav' ); ?>

<main class="vx-legal-main">
  <div class="vx-legal-wrap">
    <p class="vx-legal-eyebrow">Documento legal</p>
    <h1 class="vx-legal-title"><?php echo esc_html( $title ); ?></h1>
    <div class="vx-legal-content">
      <?php echo apply_filters( 'the_content', $content ); ?>
    </div>
  </div>
</main>

<?php get_template_part( 'partials/footer' ); ?>
<?php wp_footer(); ?>
</body>
</html>
