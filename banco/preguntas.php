<?php
/* Unas preguntas frecuentes para que la portada y su página tengan qué mostrar.
   Son las de la propuesta; en producción viven en la base y son muchas más. */
require_once __DIR__ . '/wp/sitio/wp-load.php';

$preguntas = [
  [ '¿Qué es Vitrinexo?', 'Un directorio B2B verificado de empresas de servicios profesionales. No es una red social ni un marketplace: no tiene feed ni transacciones, y el contacto entre miembros es directo.' ],
  [ '¿Vitrinexo cobra comisión por los negocios?', 'No. Vitrinexo no cobra por conectar a dos miembros ni actúa como intermediario. Tampoco vende posiciones destacadas: el orden del directorio no se paga.' ],
  [ '¿Cómo se verifica mi empresa?', 'Revisamos cada solicitud antes de activarla. Ningún perfil se activa solo. Si falta un dato, te escribimos para completarlo.' ],
  [ '¿Tengo que estar activo para que funcione?', 'No. Creas tu perfil una vez y queda visible para los miembros aprobados. No hay feed, rachas ni contenido obligatorio.' ],
  [ '¿Qué es el programa Miembro Pionero?', 'Los primeros 100 miembros aprobados reciben el distintivo Miembro Pionero, que es permanente. La membresía vence a los 3 meses de la aprobación. Al llegar a 100, el programa cierra.' ],
  [ '¿Qué es Vitrinexo 4Dinner?', 'Cenas de cuatro personas en una mesa, los miércoles a las 20:00 hora local. Vitrinexo arma cada mesa según el rubro, los intereses y los objetivos de negocio de cada miembro.' ],
  [ '¿Puedo estar en más de una comunidad?', 'Sí. Vitrinexo Woman, Vitrinexo LGBTQ+ y Vitrinexo Senior son comunidades dentro del mismo directorio, y un miembro puede estar en varias.' ],
  [ '¿Quién puede ver mi perfil?', 'Solo los miembros aprobados ven tu perfil completo y tus datos de contacto. Tus datos no se venden ni se comparten con terceros.' ],
  [ '¿Es solo para empresas grandes?', 'No. Puede sumarse cualquier empresa de servicios B2B, sin importar su tamaño. El tamaño tampoco cambia tu lugar en el directorio.' ],
  [ '¿Cómo me doy de baja?', 'Escríbenos a hola@vitrinexo.com. Respondemos en menos de 24 horas hábiles y tu perfil deja de estar visible para los miembros.' ],
];

foreach ( $preguntas as $i => [ $t, $r ] ) {
    $ya = get_posts( [ 'post_type' => 'vx_faq', 'title' => $t, 'posts_per_page' => 1, 'fields' => 'ids' ] );
    if ( $ya ) { echo "  = $t\n"; continue; }
    $id = wp_insert_post( [
        'post_type' => 'vx_faq', 'post_status' => 'publish',
        'post_title' => $t, 'post_content' => $r, 'menu_order' => $i,
    ] );
    if ( ! is_wp_error( $id ) && $i < 8 ) update_post_meta( $id, '_vx_faq_destacada', '1' );
    echo is_wp_error( $id ) ? "  ! $t\n" : "  + $t\n";
}
echo "listo\n";
