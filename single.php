<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! have_posts() ) {
    wp_safe_redirect( home_url( '/blog/' ) );
    exit;
}

the_post();

$post_id     = get_the_ID();
$title       = get_the_title();
$content     = get_the_content();
$date        = get_the_date( 'j \d\e F Y' );
$author_id   = (int) get_the_author_meta( 'ID' );
$author_name = get_the_author();
$author_bio  = get_the_author_meta( 'description' );
$author_role = get_the_author_meta( 'user_title' ) ?: get_the_author_meta( 'job_title' ) ?: '';
$avatar_url  = get_avatar_url( $author_id, [ 'size' => 96 ] );
$author_slug = (string) get_user_meta( $author_id, 'vx_perfil_slug', true );
$categories  = get_the_category();
$tags        = get_the_tags() ?: [];
$cat_name    = $categories[0]->name ?? 'Blog';
$read_time   = max( 1, (int) ceil( str_word_count( wp_strip_all_tags( $content ) ) / 200 ) );

$is_logged      = is_user_logged_in();
$current_uid    = get_current_user_id();
$react_count    = function_exists( 'vx_blog_reacciones_count' ) ? vx_blog_reacciones_count( $post_id ) : 0;
$has_reacted    = function_exists( 'vx_blog_usuario_reacciono' ) ? vx_blog_usuario_reacciono( $post_id, $current_uid ) : false;
$comments_count = (int) get_comments_number( $post_id );
$rest_ns        = defined( 'VX_REST_NAMESPACE' ) ? VX_REST_NAMESPACE : 'vitrinexo/v1';
$react_ep       = esc_url_raw( rest_url( $rest_ns . '/blog/' . $post_id . '/reaccion' ) );
$creact_base    = esc_url_raw( rest_url( $rest_ns . '/blog/comentario/' ) );
$rest_nonce     = wp_create_nonce( 'wp_rest' );
$login_url      = home_url( '/login/' );
$registro_url   = home_url( '/login/' );

// Habilita el JS nativo de respuestas anidadas (hilos).
if ( comments_open( $post_id ) ) {
    wp_enqueue_script( 'comment-reply' );
}

$prev_post = get_previous_post();
$next_post = get_next_post();

// Related posts (same category, excluding current)
$related = get_posts( [
    'category__in'   => wp_list_pluck( $categories, 'term_id' ),
    'post__not_in'   => [ $post_id ],
    'posts_per_page' => 3,
    'orderby'        => 'date',
    'order'          => 'DESC',
] );

$related_gradients = [
    'linear-gradient(135deg,var(--lavanda-hover),var(--lavanda))',
    'linear-gradient(135deg,var(--mantequilla-presion),var(--color-primary))',
    'linear-gradient(135deg,var(--rosa-hover),var(--rosa))',
];
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class( 'vx-single-blog bg-page-vx' ); ?>>

<?php
if ( is_user_logged_in() ) {
    get_template_part( 'partials/nav-logged' );
} else {
    get_template_part( 'partials/nav' );
}
?>

<main>
<div class="container py-4">
  <!-- Breadcrumb (fuera de las columnas para alinear el título con el sidebar) -->
  <nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb" style="font-size:12px;background:none;padding:0;margin:0">
      <li class="breadcrumb-item"><a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>" class="link-primary-color">Blog</a></li>
      <li class="breadcrumb-item active" style="color:var(--color-text-secondary)"><?php echo esc_html( $cat_name ); ?></li>
    </ol>
  </nav>

  <div class="row g-4 justify-content-center">

    <!-- ── COLUMNA ARTÍCULO ── -->
    <div class="col-12 col-lg-8">

      <!-- Header artículo (título en caja propia, sin imagen) -->
      <div class="card-vx mb-4">
        <span class="section-landing-label" style="margin:0 0 .6rem;display:inline-block"><?php echo esc_html( $cat_name ); ?></span>
        <h1 style="font-size:clamp(1.5rem,3vw,2.1rem);font-weight:700;color:var(--color-text-primary);line-height:1.2;margin:0 0 1rem">
          <?php echo esc_html( $title ); ?>
        </h1>
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="border-top:1px solid var(--color-border);padding-top:1rem">
          <div class="d-flex align-items-center gap-2">
            <img src="<?php echo esc_url( $avatar_url ); ?>" class="avatar-36" alt="<?php echo esc_attr( $author_name ); ?>">
            <div>
              <div class="text-body-label"><?php echo function_exists( 'vx_nombre_enlazado' ) ? vx_nombre_enlazado( $author_id, $author_name, 'link-primary-color' ) : esc_html( $author_name ); ?></div>
              <div class="text-xs-muted"><?php echo esc_html( $date ); ?> · <?php echo esc_html( $read_time ); ?> min de lectura</div>
            </div>
          </div>
          <div class="d-flex gap-2">
            <button class="btn-vx btn-ghost-vx btn-vx-sm" onclick="navigator.share ? navigator.share({title:<?php echo wp_json_encode( $title ); ?>,url:window.location.href}) : navigator.clipboard.writeText(window.location.href)">
              <i class="ti ti-share-2"></i> Compartir
            </button>
          </div>
        </div>
      </div>

      <!-- Cuerpo del artículo -->
      <div class="card-vx mb-4 article-body">
        <?php echo apply_filters( 'the_content', $content ); ?>
      </div>

      <!-- Reacciones -->
      <div class="card-vx mb-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div class="text-sm-muted">¿Te sirvió este artículo?</div>
        <?php if ( $is_logged ) : ?>
        <button id="vx-react-btn" class="btn-vx btn-vx-sm <?php echo $has_reacted ? 'btn-primary-vx' : 'btn-ghost-vx'; ?>"
                data-reacted="<?php echo $has_reacted ? '1' : '0'; ?>">
          <i class="ti ti-hand-love-you me-1"></i> Aplaudir · <span id="vx-react-count"><?php echo (int) $react_count; ?></span>
        </button>
        <?php else : ?>
        <a href="<?php echo esc_url( $login_url ); ?>" class="btn-vx btn-ghost-vx btn-vx-sm">
          <i class="ti ti-hand-love-you me-1"></i> Aplaudir · <?php echo (int) $react_count; ?>
        </a>
        <?php endif; ?>
      </div>

      <!-- Comentarios -->
      <div class="card-vx mb-4" id="comentarios">
        <h3 class="subsection-title mb-3" style="font-size:16px">
          Comentarios<?php echo $comments_count ? ' · ' . (int) $comments_count : ''; ?>
        </h3>

        <?php if ( $is_logged ) : ?>
          <?php
          comment_form( [
              'title_reply'         => '',
              'label_submit'        => 'Publicar comentario',
              'class_submit'        => 'btn-vx btn-primary-vx btn-vx-sm',
              'comment_notes_before'=> '',
              'comment_notes_after' => '',
              'logged_in_as'        => '',
              'comment_field'       => '<div class="mb-3"><textarea name="comment" class="form-control-vx" rows="3" placeholder="Escribe un comentario..." required></textarea></div>',
          ] );
          ?>
        <?php else : ?>
          <div class="cta-card" style="text-align:center;padding:20px">
            <p class="text-body-muted mb-3">Inicia sesión o inscríbete en Vitrinexo para dejar un comentario.</p>
            <div class="d-flex gap-2 justify-content-center flex-wrap">
              <a href="<?php echo esc_url( $login_url ); ?>" class="btn-vx btn-ghost-vx btn-vx-sm">Ingresar</a>
              <a href="<?php echo esc_url( $registro_url ); ?>" class="btn-vx btn-primary-vx btn-vx-sm">Inscríbete</a>
            </div>
          </div>
        <?php endif; ?>

        <?php
        $vx_comments = get_comments( [ 'post_id' => $post_id, 'status' => 'approve', 'order' => 'ASC' ] );
        if ( $vx_comments ) : ?>
        <ul class="vx-comment-list" style="list-style:none;padding:0;margin:1.25rem 0 0">
          <?php wp_list_comments( [
              'style'       => 'ul',
              'callback'    => 'vx_render_comment',
              'max_depth'   => 4,
              'avatar_size' => 40,
          ], $vx_comments ); ?>
        </ul>
        <?php endif; ?>
      </div>

      <?php if ( $is_logged ) : ?>
      <script>
      (function(){
        var NONCE = <?php echo wp_json_encode( $rest_nonce ); ?>;
        // Reacción al artículo
        var btn = document.getElementById('vx-react-btn');
        if (btn) {
          btn.addEventListener('click', function(){
            btn.disabled = true;
            fetch(<?php echo wp_json_encode( $react_ep ); ?>, {
              method:'POST', headers:{'X-WP-Nonce': NONCE}
            }).then(function(r){ return r.json(); }).then(function(d){
              if (d && d.success) {
                document.getElementById('vx-react-count').textContent = d.count;
                btn.classList.toggle('btn-primary-vx', d.reacted);
                btn.classList.toggle('btn-ghost-vx', !d.reacted);
                btn.dataset.reacted = d.reacted ? '1' : '0';
              }
              btn.disabled = false;
            }).catch(function(){ btn.disabled = false; });
          });
        }
        // Reacciones a comentarios (me gusta)
        var CBASE = <?php echo wp_json_encode( $creact_base ); ?>;
        document.addEventListener('click', function(e){
          var b = e.target.closest ? e.target.closest('.vx-creact-btn') : null;
          if (!b) return;
          b.disabled = true;
          fetch(CBASE + b.dataset.id + '/reaccion', {
            method:'POST', headers:{'X-WP-Nonce': NONCE}
          }).then(function(r){ return r.json(); }).then(function(d){
            if (d && d.success) {
              var c = b.querySelector('.vx-creact-count'); if (c) c.textContent = d.count;
              b.dataset.reacted = d.reacted ? '1' : '0';
              b.style.color = d.reacted ? 'var(--color-primary)' : 'var(--color-text-secondary)';
              var ic = b.querySelector('i'); if (ic) ic.className = d.reacted ? 'ti ti-heart-filled' : 'ti ti-heart';
            }
            b.disabled = false;
          }).catch(function(){ b.disabled = false; });
        });
      })();
      </script>
      <?php endif; ?>

      <!-- Tags y navegación entre artículos -->
      <div class="card-vx d-flex align-items-center justify-content-between flex-wrap gap-3 mb-5">
        <div class="d-flex gap-1 flex-wrap">
          <?php foreach ( $categories as $cat ) : ?>
          <span class="tag-vx"><?php echo esc_html( $cat->name ); ?></span>
          <?php endforeach; ?>
          <?php foreach ( $tags as $tag ) : ?>
          <span class="tag-vx"><?php echo esc_html( $tag->name ); ?></span>
          <?php endforeach; ?>
        </div>
        <div class="d-flex gap-2">
          <a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>" class="btn-vx btn-ghost-vx btn-vx-sm">
            <i class="ti ti-arrow-left me-1"></i> Volver al blog
          </a>
          <?php if ( $next_post ) : ?>
          <a href="<?php echo esc_url( get_permalink( $next_post ) ); ?>" class="btn-vx btn-ghost-vx btn-vx-sm">
            Siguiente <i class="ti ti-arrow-right ms-1"></i>
          </a>
          <?php endif; ?>
        </div>
      </div>

    </div>

    <!-- ── SIDEBAR ── -->
    <div class="col-12 col-lg-4">

      <!-- Autor -->
      <div class="card-vx mb-3">
        <div class="d-flex align-items-center gap-3 mb-3">
          <img src="<?php echo esc_url( $avatar_url ); ?>"
               style="width:52px;height:52px;border-radius:var(--radius-sm);object-fit:cover;border:2px solid var(--color-border)"
               alt="<?php echo esc_attr( $author_name ); ?>">
          <div>
            <div style="font-size:14px;font-weight:600"><?php echo function_exists( 'vx_nombre_enlazado' ) ? vx_nombre_enlazado( $author_id, $author_name, 'link-primary-color' ) : esc_html( $author_name ); ?></div>
            <?php if ( $author_role ) : ?>
            <div class="text-xs-muted"><?php echo esc_html( $author_role ); ?></div>
            <?php endif; ?>
          </div>
        </div>
        <?php if ( $author_bio ) : ?>
        <p class="text-sm-muted" style="line-height:1.6;margin-bottom:.75rem"><?php echo esc_html( $author_bio ); ?></p>
        <?php endif; ?>
        <?php if ( $author_slug ) : ?>
          <?php if ( $is_logged ) : ?>
          <a href="<?php echo esc_url( home_url( '/perfil/' . $author_slug . '/' ) ); ?>" class="btn-vx btn-ghost-vx btn-vx-sm w-100">
            <i class="ti ti-user me-1"></i> Ver perfil en Vitrinexo
          </a>
          <?php else : ?>
          <a href="<?php echo esc_url( $registro_url ); ?>" class="btn-vx btn-soft-primary btn-vx-sm w-100">
            <i class="ti ti-lock me-1"></i> Inscríbete para ver su perfil
          </a>
          <div class="text-xs-muted text-center mt-2">¿Ya eres miembro? <a href="<?php echo esc_url( $login_url ); ?>" class="link-primary-color">Inicia sesión</a></div>
          <?php endif; ?>
        <?php endif; ?>
      </div>

      <!-- Más artículos -->
      <?php if ( $related ) : ?>
      <div class="card-vx mb-3">
        <h3 class="subsection-title mb-3" style="font-size:15px">Más artículos</h3>
        <div class="d-flex flex-column gap-3">
          <?php foreach ( $related as $i => $rpost ) :
            $rcat  = get_the_category( $rpost->ID );
            $rcat_name = $rcat[0]->name ?? 'Blog';
            $rtime = max( 1, (int) ceil( str_word_count( wp_strip_all_tags( $rpost->post_content ) ) / 200 ) );
            $grad  = $related_gradients[ $i % 3 ];
            $rthumb = get_the_post_thumbnail_url( $rpost->ID, 'thumbnail' );
            $rthumb_style = $rthumb
                ? 'background:url(' . esc_url( $rthumb ) . ') center/cover no-repeat'
                : 'background:' . $grad;
          ?>
          <a href="<?php echo esc_url( get_permalink( $rpost ) ); ?>" class="text-decoration-none d-flex gap-3 align-items-start">
            <div style="width:44px;height:44px;border-radius:var(--radius-sm);flex-shrink:0;<?php echo esc_attr( $rthumb_style ); ?>"></div>
            <div>
              <div class="text-body-label" style="line-height:1.35;margin-bottom:2px"><?php echo esc_html( get_the_title( $rpost ) ); ?></div>
              <div class="text-xs-muted"><?php echo esc_html( $rtime ); ?> min · <?php echo esc_html( $rcat_name ); ?></div>
            </div>
          </a>
          <?php endforeach; ?>
        </div>
        <a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>" class="btn-vx btn-ghost-vx btn-vx-sm w-100 mt-3">
          Ver todos los artículos <i class="ti ti-arrow-right ms-1"></i>
        </a>
      </div>
      <?php endif; ?>

      <!-- CTA directorio -->
      <div class="card-vx border-left-primary">
        <div class="eyebrow-vx" style="margin-bottom:.5rem">¿Ya estás en Vitrinexo?</div>
        <p class="text-body-muted" style="margin-bottom:.75rem">Conecta con las empresas de las que habla este artículo. Están en el directorio.</p>
        <a href="<?php echo esc_url( home_url( '/directorio/' ) ); ?>" class="btn-vx btn-soft-primary btn-vx-sm w-100">
          <i class="ti ti-layout-grid me-1"></i> Explorar el directorio
        </a>
      </div>

    </div>

  </div>
</div>
</main>

<?php
if ( is_user_logged_in() ) {
    get_template_part( 'partials/footer-logged' );
} else {
    get_template_part( 'partials/footer' );
}
?>
<?php wp_footer(); ?>
</body>
</html>
