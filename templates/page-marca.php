<?php
/*
 * Template Name: Manual de marca
 *
 * El manual de marca de Vitrinexo. Se genera del mismo CSS que el producto:
 * la paleta, la escala tipográfica y los papeles que aparecen acá se leen de
 * assets/css/style.css al renderizar. Si un token cambia, esta página cambia
 * sola.
 *
 * La página es además el primer lugar donde se comprueban las reglas que
 * enuncia: obedece la escala que documenta, usa solo papeles semánticos y pasa
 * las mismas pruebas de contraste y de área de toque que exige.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

require_once get_template_directory() . '/inc/marca/tokens.php';
require_once get_template_directory() . '/inc/marca/criterios.php';
require_once get_template_directory() . '/inc/marca/papeles.php';
require_once get_template_directory() . '/inc/marca/piezas.php';
require_once get_template_directory() . '/inc/marca/contenido.php';

$vx_partes    = vx_marca_partes();
$vx_contenido = [
    'estrategia' => 'vx_marca_estrategia',
    'identidad'  => 'vx_marca_identidad',
    'voz'        => 'vx_marca_voz',
];
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class( 'vx-marca' ); ?>>

<a class="vx-saltar" href="#documento">Saltar al contenido</a>

<header class="vx-barra">
  <button type="button" class="vx-barra-menu" aria-expanded="false" aria-controls="vx-menu">
    <i class="ti ti-menu-2" aria-hidden="true"></i>
    <span class="vx-solo-lectores">Abrir el menú</span>
  </button>
  <span class="vx-barra-titulo">Manual de marca</span>
</header>

<div class="vx-velo" hidden></div>

<aside class="vx-menu" id="vx-menu">
  <a class="vx-menu-logo" href="#documento">
    <img src="<?php echo esc_url( vx_logo_url() ); ?>"
         alt="Vitrinexo" data-logotipo="1">
  </a>
  <nav aria-label="Criterios del manual">
    <?php foreach ( $vx_partes as $vx_parte ) : ?>
      <p class="vx-menu-parte">
        <span class="vx-dato"><?php echo esc_html( $vx_parte['numero'] ); ?></span>
        <a href="#<?php echo esc_attr( $vx_parte['id'] ); ?>"><?php echo esc_html( $vx_parte['titulo'] ); ?></a>
      </p>
      <ul class="vx-menu-lista">
        <?php foreach ( $vx_parte['criterios'] as $vx_c ) : ?>
          <li>
            <a href="#<?php echo esc_attr( $vx_c['id'] ); ?>" data-criterio="<?php echo esc_attr( $vx_c['id'] ); ?>">
              <span><?php echo esc_html( $vx_c['nombre'] ); ?></span>
              <span class="vx-punto vx-punto-<?php echo esc_attr( $vx_c['estado'] ); ?>"
                    title="<?php echo esc_attr( vx_marca_etiqueta( $vx_c['estado'] ) ); ?>">
                <span class="vx-solo-lectores"><?php echo esc_html( vx_marca_etiqueta( $vx_c['estado'] ) ); ?></span>
              </span>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endforeach; ?>
  </nav>
</aside>

<main class="vx-doc" id="documento">
  <div class="vx-columna">
    <?php
    vx_marca_portada();
    foreach ( $vx_partes as $vx_parte ) {
        vx_marca_seccion( $vx_parte, $vx_contenido[ $vx_parte['id'] ] );
    }
    ?>
  </div>
</main>

<?php wp_footer(); ?>
</body>
</html>
