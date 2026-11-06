<?php
/*
 * Template Name: Confirmar correo
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
<body <?php body_class( 'vx-page-confirmar-correo' ); ?> style="background:var(--hueso-2)">

<?php get_template_part( 'partials/nav' ); ?>

<main>
  <?php the_content(); ?>
</main>

<?php get_template_part( 'partials/footer' ); ?>

<script>
(function () {
  const btn = document.getElementById('vx-reenviar-token');
  const msg = document.getElementById('vx-reenviar-msg');
  if (!btn) return;

  btn.addEventListener('click', async function () {
    btn.disabled = true;
    btn.textContent = 'Enviando...';
    try {
      const res = await fetch(vx_data.api_url + 'reenviar-token', {
        method: 'POST',
        headers: { 'X-WP-Nonce': vx_data.nonce },
      });
      const json = await res.json();
      msg.textContent = json.success ? 'Correo reenviado correctamente.' : 'No se pudo reenviar. Intenta más tarde.';
      msg.className = 'vx-alert mt-3 ' + (json.success ? 'vx-alert--success' : 'vx-alert--error');
    } catch {
      msg.textContent = 'Error de conexión.';
      msg.className = 'vx-alert vx-alert--error mt-3';
    }
    msg.classList.remove('d-none');
    setTimeout(() => { btn.disabled = false; btn.textContent = 'Reenviar correo'; }, 30000);
  });
})();
</script>

<?php wp_footer(); ?>
</body>
</html>
