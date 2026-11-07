<?php
/* Unas preguntas frecuentes para que la portada y su página tengan qué mostrar.
   Son las de la propuesta; en producción viven en la base y son muchas más.

   Cada una va con su categoría, y eso no es decoración: el acordeón de
   [vx_faq] agrupa por la taxonomía vx_faq_cat y pide get_terms con
   hide_empty, así que una pregunta sin categoría no se dibuja. Este archivo
   las sembró sin categoría desde el primer día, de modo que
   /preguntas-frecuentes/ respondía 200 y decía «Todavía no hay preguntas
   publicadas». La ruta estaba en la lista de la suite y ninguna comprobación
   miró jamás una pregunta: es la misma forma de pasar en verde sin medir nada
   que ya apareció en la sesión del banco, en el opcache y en el texto plegado.
   Lo pagó producción, que tenía seis entradas nombrando «4Dinner» a secas.

   Las categorías son las de producción, con el mismo nombre y el mismo orden,
   porque una fixture que agrupa distinto que el sitio real vuelve a medir otra
   cosa. */
require_once __DIR__ . '/wp/sitio/wp-load.php';

/* Las categorías, en el orden en que salen. La meta vx_orden es la que lee
   vx_faq_categorias_ordenadas(); sin ella el orden lo decide el alfabeto. */
$categorias = [
  'Qué es Vitrinexo'              => 1,
  'Funcionamiento de la plataforma' => 2,
  'Comunidades dentro de Vitrinexo' => 3,
  'Vitrinexo 4Dinner en detalle'  => 4,
  'Privacidad y legal'            => 5,
];

foreach ( $categorias as $nombre => $orden ) {
    $term = get_term_by( 'name', $nombre, 'vx_faq_cat' );
    if ( ! $term ) {
        $nuevo = wp_insert_term( $nombre, 'vx_faq_cat' );
        if ( is_wp_error( $nuevo ) ) { echo "  ! $nombre\n"; continue; }
        $term_id = $nuevo['term_id'];
        echo "  + $nombre\n";
    } else {
        $term_id = $term->term_id;
        echo "  = $nombre\n";
    }
    update_term_meta( $term_id, 'vx_orden', $orden );
}

$preguntas = [
  [ '¿Qué es Vitrinexo?', 'Un directorio B2B verificado de empresas de servicios profesionales. No es una red social ni un marketplace: no tiene feed ni transacciones, y el contacto entre miembros es directo.' , 'Qué es Vitrinexo' ],
  [ '¿Vitrinexo cobra comisión por los negocios?', 'No. Vitrinexo no cobra por conectar a dos miembros ni actúa como intermediario. Tampoco vende posiciones destacadas: el orden del directorio no se paga.' , 'Qué es Vitrinexo' ],
  [ '¿Cómo se verifica mi empresa?', 'Revisamos cada solicitud antes de activarla. Ningún perfil se activa solo. Si falta un dato, te escribimos para completarlo.' , 'Funcionamiento de la plataforma' ],
  [ '¿Tengo que estar activo para que funcione?', 'No. Creas tu perfil una vez y queda visible para los miembros aprobados. No hay feed, rachas ni contenido obligatorio.' , 'Funcionamiento de la plataforma' ],
  [ '¿Qué es el programa Miembro Pionero?', 'Los primeros 100 miembros aprobados reciben el distintivo Miembro Pionero, que es permanente, y el acceso es gratuito mientras el programa esté abierto. Al llegar a 100, el programa cierra: te avisamos con tiempo antes de cobrar y te quedas con el precio preferencial.' , 'Funcionamiento de la plataforma' ],
  [ '¿Qué es Vitrinexo 4Dinner?', 'Cenas de cuatro personas en una mesa, los miércoles a las 20:00 hora local. Vitrinexo arma cada mesa según el rubro, los intereses y los objetivos de negocio de cada miembro.' , 'Vitrinexo 4Dinner en detalle' ],
  [ '¿Puedo estar en más de una comunidad?', 'Sí. Vitrinexo Woman, Vitrinexo LGBTQ+ y Vitrinexo Senior son comunidades dentro del mismo directorio, y un miembro puede estar en varias.' , 'Comunidades dentro de Vitrinexo' ],
  [ '¿Quién puede ver mi perfil?', 'Solo los miembros aprobados ven tu perfil completo y tus datos de contacto. Tus datos no se venden ni se comparten con terceros.' , 'Privacidad y legal' ],
  [ '¿Es solo para empresas grandes?', 'No. Puede sumarse cualquier empresa de servicios B2B, sin importar su tamaño. El tamaño tampoco cambia tu lugar en el directorio.' , 'Qué es Vitrinexo' ],
  [ '¿Cómo me doy de baja?', 'Escríbenos a hola@vitrinexo.com. Respondemos en menos de 24 horas hábiles y tu perfil deja de estar visible para los miembros.' , 'Funcionamiento de la plataforma' ],
];

foreach ( $preguntas as $i => [ $t, $r, $cat ] ) {
    $ya = get_posts( [ 'post_type' => 'vx_faq', 'title' => $t, 'posts_per_page' => 1, 'fields' => 'ids' ] );
    $id = $ya ? $ya[0] : wp_insert_post( [
        'post_type' => 'vx_faq', 'post_status' => 'publish',
        'post_title' => $t, 'post_content' => $r, 'menu_order' => $i,
    ] );
    if ( is_wp_error( $id ) ) { echo "  ! $t\n"; continue; }

    // La categoría se escribe siempre, también en las que ya estaban: las que
    // sembró la versión anterior de este archivo no tienen ninguna y por eso
    // no se dibujan. Sin esto, un banco viejo sigue con la página vacía.
    wp_set_object_terms( $id, $cat, 'vx_faq_cat', false );

    if ( ! $ya && $i < 8 ) update_post_meta( $id, '_vx_faq_destacada', '1' );
    echo $ya ? "  = $t\n" : "  + $t\n";
}
echo "listo\n";
