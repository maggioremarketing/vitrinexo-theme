<?php
/* Miembros de mentira para que las pantallas tengan algo que dibujar.
   Datos inventados, en un WordPress de 127.0.0.1. Nada de esto sale de aqui. */
require_once __DIR__ . '/wp/sitio/wp-load.php';

$clave = trim( file_get_contents( __DIR__ . '/wp/clave.txt' ) );

$gente = [
  [ 'ana',    'Ana',    'Reyes',    'Estudio Jurídico Alcántara', 'Socia',            'Legal y ética',           'Santiago',     'Chile',     'CL' ],
  [ 'bruno',  'Bruno',  'Oliveira', 'Pampa Data',                 'Director de datos','Tecnología y software',   'São Paulo',    'Brasil',    'BR' ],
  [ 'carla',  'Carla',  'Mendoza',  'Mendoza Contadores',         'Fundadora',        'Contabilidad y finanzas', 'Lima',         'Perú',       'PE' ],
  [ 'diego',  'Diego',  'Fuentes',  'Taller Norte',               'Director creativo','Diseño y creatividad',    'Monterrey',    'México',    'MX' ],
  [ 'elisa',  'Elisa',  'Grandi',   'Grandi RR.HH.',              'Consultora',       'Recursos humanos',        'Buenos Aires', 'Argentina', 'AR' ],
  [ 'felipe', 'Felipe', 'Ortega',   'Ortega Logística',           'Gerente general',  'Logística',               'Bogotá',       'Colombia',  'CO' ],
];

$ofrece = [ [ 'Contratos', 'Compliance', 'Propiedad intelectual' ], [ 'Analítica', 'Dashboards', 'Migraciones' ],
            [ 'Auditoría', 'Impuestos', 'Control de gestión' ], [ 'Identidad', 'Empaque', 'Dirección de arte' ],
            [ 'Selección', 'Clima laboral', 'Compensaciones' ], [ 'Última milla', 'Almacenaje', 'Importación' ] ];
$busca = [ [ 'Diseño', 'Marketing' ], [ 'Legal', 'Contabilidad' ], [ 'Tecnología', 'Logística' ],
           [ 'Tecnología', 'Legal' ], [ 'Diseño', 'Logística' ], [ 'Marketing', 'Contabilidad' ] ];

foreach ( $gente as $i => [ $u, $nombre, $apellido, $empresa, $cargo, $industria, $ciudad, $pais, $cc ] ) {
    $id = username_exists( $u ) ?: wp_create_user( $u, $clave, "$u@127.0.0.1" );
    if ( is_wp_error( $id ) ) { echo "  ! $u: " . $id->get_error_message() . "\n"; continue; }
    wp_update_user( [ 'ID' => $id, 'display_name' => "$nombre $apellido", 'first_name' => $nombre, 'last_name' => $apellido ] );
    // La clave manda siempre: si el banco se vuelve a montar sobre una base que
    // ya existía, el usuario tendría la clave del montaje anterior y la sesión
    // no entraría, con el banco levantado y aparentemente sano.
    wp_set_password( $clave, $id );
    $meta = [
      'vx_nombre' => $nombre, 'vx_apellido' => $apellido, 'vx_empresa' => $empresa, 'vx_cargo' => $cargo,
      'vx_industria' => $industria, 'vx_ciudad' => $ciudad, 'vx_pais' => $pais, 'vx_pais_codigo' => $cc,
      'vx_estado' => 'activo', 'vx_account_activated' => '1', 'vx_es_fundador' => $i === 0 ? '1' : '',
      'vx_bio' => "$cargo en $empresa. Trabajo con empresas de servicios que quieren crecer fuera de su ciudad.",
      'vx_linkedin' => "https://www.linkedin.com/in/$u",
      'vx_offer_tags' => $ofrece[ $i ], 'vx_seek_tags' => $busca[ $i ],
      'vx_anos_experiencia' => 8 + $i, 'vx_contacto_preferido' => 'email',
      'vx_consentimiento_contacto' => '1',
      'vx_onboarding_completo' => '1', 'vx_onboarding_paso' => 7,
      // Sin slug no hay perfil. /perfil/ a secas devuelve «Perfil no
      // encontrado»: el perfil de verdad vive en /perfil/<slug>/, y hasta que
      // esto existió la suite medía esa frase de error creyendo que medía una
      // de las catorce pantallas.
      'vx_perfil_slug' => sanitize_title( "$nombre $apellido" ),
    ];
    foreach ( $meta as $k => $v ) update_user_meta( $id, $k, $v );
    // La sesión del banco es la de ana, y pertenece a las tres comunidades.
    // No es realismo: es la única forma de que las tres páginas de comunidad
    // se dibujen enteras y de que el menú y el pie listen sus tres enlaces.
    // Sin esto, /comunidad-woman/ devuelve la tarjeta de «comunidad privada» y
    // el nombre de la pieza no aparece en ninguna parte que se pueda medir.
    // Y además verificada, que es otra cosa: el distintivo del perfil solo se
    // dibuja con vx_senior_verificado, así que sin esto el nombre de la pieza
    // que lleva ese distintivo no aparece en ninguna pantalla medible.
    if ( 0 === $i ) {
        foreach ( [ 'vx_comunidad_out2b', 'vx_comunidad_woman', 'vx_comunidad_senior',
                    'vx_senior_verificado' ] as $k ) {
            update_user_meta( $id, $k, '1' );
        }
    }
    echo "  · $u ($id) $nombre $apellido — $empresa\n";
}
echo "listo\n";
