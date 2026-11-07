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
    ];
    foreach ( $meta as $k => $v ) update_user_meta( $id, $k, $v );
    echo "  · $u ($id) $nombre $apellido — $empresa\n";
}
echo "listo\n";
