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
  <style>
    .vx-legal-main { background: transparent; min-height: 100vh; position: relative; z-index: 1; }
    .vx-legal-wrap { max-width: 760px; margin: 0 auto; padding: 4rem 2rem 6rem; }
    .vx-legal-eyebrow { font-size: 11px; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; color: var(--color-primary-ink); margin: 0 0 .75rem; }
    .vx-legal-title { font-family: "Switzer", -apple-system, sans-serif; font-size: clamp(2rem, 4vw, 2.8rem); font-weight: 700; letter-spacing: -.04em; color: var(--color-text-primary); line-height: 1.15; margin: 0 0 2.5rem; padding-bottom: 1.5rem; border-bottom: 2px solid var(--color-primary-ink); }
    .vx-legal-content { font-family: "Switzer", -apple-system, sans-serif; font-size: 16px; line-height: 1.8; color: var(--color-text-secondary); }
    .vx-legal-content h2 { font-family: "Switzer", -apple-system, sans-serif; font-size: clamp(1.2rem, 2vw, 1.5rem); font-weight: 700; letter-spacing: -.03em; color: var(--color-text-primary); margin: 2.5rem 0 .6rem; padding-bottom: .5rem; border-bottom: 1px solid var(--color-border); }
    .vx-legal-content h3 { font-family: "Switzer", -apple-system, sans-serif; font-size: 1.05rem; font-weight: 600; color: var(--color-primary-ink); margin: 1.75rem 0 .4rem; letter-spacing: -.02em; }
    .vx-legal-content p { margin-bottom: 1.25rem; }
    .vx-legal-content ul { padding-left: 1.5rem; margin-bottom: 1.5rem; }
    .vx-legal-content ul li { margin-bottom: .4rem; }
    .vx-legal-content strong { font-weight: 600; color: var(--color-text-primary); }
    .vx-legal-content a { color: var(--color-primary-ink); text-decoration: none; border-bottom: 1px solid var(--color-primary-ink); }
    .vx-legal-content a:hover { opacity: .75; }
  </style>
</head>
<body <?php body_class( 'vx-page-legal' ); ?>>

<?php get_template_part( 'partials/nav' ); ?>

<main class="vx-legal-main">
  <div class="vx-legal-wrap">
    <p class="vx-legal-eyebrow">Vitrinexo</p>
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
