<?php
if ( ! defined( 'ABSPATH' ) ) exit;

the_post();
$title   = get_the_title();
$content = get_the_content();

/* Esta es la plantilla POR DEFECTO: la usa toda pagina que no tenga la suya en
   el mapa de functions.php. Estaba escrita como si fuera la de los documentos
   legales, con la linea «Documento legal» clavada y la clase vx-page-legal, asi
   que cualquier pantalla que faltara en ese mapa salia disfrazada de contrato.

   Mordio en produccion: /blog-nuevo/ («Escribir articulo») y /blog-moderacion/
   («Moderacion del blog») se anunciaban como documento legal, y ninguna prueba
   las abria. El 7 de noviembre de 2026.

   Ahora la linea legal la ponen solo las paginas que de verdad lo son, y se
   declaran aca. Una pantalla que falte en el mapa sale sin adorno, que es feo
   pero no miente. */
$legales = [ 'privacidad', 'terminos' ];
$pagina_legal = in_array( get_post_field( 'post_name', get_the_ID() ), $legales, true );
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
<body <?php body_class( $pagina_legal ? 'vx-page-legal' : 'vx-page-simple' ); ?>>

<?php get_template_part( 'partials/nav' ); ?>

<main class="vx-legal-main">
  <div class="vx-legal-wrap">
    <?php if ( $pagina_legal ) : ?><p class="vx-legal-eyebrow">Documento legal</p><?php endif; ?>
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
