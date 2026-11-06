<?php
/**
 * El contenido del manual, criterio por criterio.
 *
 * Lo que está escrito acá es lo que alguien necesita para aplicar la marca sin
 * preguntar. Lo que todavía no se decidió dice qué falta y a quién hay que
 * preguntárselo: un hueco declarado se cierra; un hueco callado lo improvisa
 * cada quien a su manera.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// ═══════════════════════════════════════════════════════ 01 Estrategia ══

function vx_marca_estrategia(): void {

    vx_marca_bloque( 'estrategia-contexto', function () {
        vx_marca_p( 'El mercado es el networking B2B de servicios profesionales, y la competencia no es otra plataforma: es la agenda. Asociaciones gremiales, diplomados, conferencias y cafés que no se concretan.' );
        vx_marca_p( 'Ese formato falla de tres maneras. <strong>Cuesta caro y no se puede medir</strong>: membresías, entradas y viajes contra una pila de tarjetas. <strong>Penaliza a quien no es extrovertido</strong>, porque premia al que sabe circular una sala y no al que hace bien el trabajo. Y <strong>LinkedIn dejó de convertir</strong>, porque nadie abre el mensaje de un desconocido sabiendo que le van a vender algo.' );
        vx_marca_p( 'Vitrinexo no compite por ser una sala mejor: cambia el contexto. Acá todos entraron para hacer negocios, así que ofrecer lo que haces no necesita disculpa ni rodeo.' );
        vx_marca_declaracion( 'No es una red social, no es un marketplace: es un directorio verificado.' );
        vx_marca_p( 'La distinción importa porque define qué no se construye. Sin feed, porque no hay contenido que seguir. Sin transacciones, porque el contacto es directo y la plataforma no se mete en el medio.' );
    } );

    vx_marca_bloque( 'estrategia-que-significa-la-marca', function () {
        vx_marca_p( 'El nombre son dos palabras y las dos mitades hacen falta.' );
        vx_marca_declaracion( 'Vitrina' );
        vx_marca_p( 'Mostrar sin pedir permiso. Una vitrina no interrumpe a nadie: está puesta, y quien necesita lo que hay adentro entra. Es lo contrario del mensaje frío. Dentro del producto, <strong>tu vitrina es tu perfil</strong>: qué haces, qué buscas y cómo te contactan.' );
        vx_marca_declaracion( 'Nexo' );
        vx_marca_p( 'Lo que convierte esa vitrina en negocio. Una vitrina sola es un catálogo. Dentro del producto, <strong>un nexo es una conexión de negocio</strong> generada en la plataforma, y el contacto va directo, sin intermediario que cobre por presentarlos.' );
        vx_marca_p( 'De ahí sale el verbo propio de la marca: <strong>vitrinear</strong>, recorrer el directorio mirando quién hace qué. Se usa en segunda persona y en imperativo, como en «vitrinea el directorio» o «empieza a vitrinear». Nunca en tercera persona ni convertido en sustantivo.' );
    } );

    vx_marca_bloque( 'estrategia-tagline', function () {
        vx_marca_declaracion( 'Tu vitrina para construir nexos de negocio.' );
        vx_marca_p( 'Se escribe entera y con punto final. Las dos mitades del nombre aparecen en ella en el mismo orden en que se leen, y por eso no se abrevia ni se parte en dos líneas por diseño.' );
        vx_marca_p( 'Vive declarado una sola vez, en <code>vx_tagline()</code>, y el titular de este manual sale de ahí: lo que estás leyendo arriba es el mismo texto que usaría cualquier plantilla.' );
        vx_marca_pendiente( 'La portada del sitio todavía lo escribe a mano, y vive en el plugin. Falta que lo lea de la misma función.' );
    } );

    vx_marca_bloque( 'estrategia-linea-de-producto', function () {
        vx_marca_p( 'Una plataforma y cinco piezas. <strong>Todas se nombran con la marca madre adelante</strong>, sin excepción y también en conversación: se escribe «Vitrinexo 4Dinner», nunca «4Dinner» a secas.' );
        vx_marca_p( '<strong>Vitrinexo</strong> es el directorio B2B completo, y es el producto. Todo lo demás vive adentro.' );
        vx_marca_p( '<strong>Vitrinexo LGBTQ+</strong>, <strong>Vitrinexo Woman</strong> y <strong>Vitrinexo Senior</strong> son comunidades verticales, no productos aparte: mismo directorio, afinidad distinta. Un miembro puede estar en varias.' );
        vx_marca_p( '<strong>Vitrinexo 4Dinner</strong> es la experiencia presencial: cuatro personas, una mesa, los miércoles a las 8 de la tarde, hora local de cada ciudad.' );
    } );

    vx_marca_bloque( 'estrategia-mision', function () {
        vx_marca_declaracion( 'Dar a las empresas de servicios B2B una vitrina verificada donde mostrar lo que hacen y encontrar con quién hacerlo, sin publicidad y sin intermediarios que cobren por el contacto.' );
        vx_marca_p( 'Es lo que el producto hace hoy, no lo que va a hacer. Cada palabra tiene su contraparte en el sistema: verificada porque ningún perfil se activa solo, sin publicidad porque no hay espacio que se venda, sin intermediarios porque el contacto sale del perfil y la plataforma no lo toca.' );
    } );

    vx_marca_bloque( 'estrategia-vision', function () {
        vx_marca_declaracion( 'Ser el primer lugar donde una empresa de servicios busca cuando necesita un aliado.' );
        vx_marca_p( '<strong>El lanzamiento es en Hispanoamérica y en español.</strong> La visión no se queda ahí: incluye el mundo hispanohablante completo, Brasil, Estados Unidos y Europa. Esa diferencia se sostiene al escribir, y en los dos sentidos: no se promete presencia donde todavía no la hay, y no se dice que Vitrinexo sea una plataforma latinoamericana.' );
    } );

    vx_marca_bloque( 'estrategia-valores', function () {
        vx_marca_p( 'Cinco, y cada uno con su consecuencia práctica. Un valor sin consecuencia es un adjetivo.' );
        foreach ( [
            [ 'Rigor', 'Ningún registro se activa solo. Ni con correo corporativo, ni por volumen, ni para acelerar una campaña. Crecer más lento es el precio, y está aceptado.' ],
            [ 'Franqueza', 'Acá se viene a vender y el copy no lo disfraza. Nada de «conversemos sin compromiso». Tampoco se promete lo que no se puede garantizar: el resultado de negocio depende de la propuesta, del mercado y del momento, y eso se dice.' ],
            [ 'Reciprocidad', 'Para ver un directorio verificado te dejas verificar. El perfil obliga a declarar las dos caras, lo que ofreces y lo que buscas, porque quien solo mira no alimenta el directorio.' ],
            [ 'Paridad', 'Entre pares. No hay posiciones destacadas pagadas ni publicidad, y el orden del directorio no se vende. El tamaño de la empresa no cambia su lugar.' ],
            [ 'Sobriedad', 'La plataforma no pide atención diaria. No hay feed, ni racha, ni contenido que publicar para seguir existiendo. Tu vitrina trabaja sin ti, y cualquier función que exija presencia constante contradice esto.' ],
        ] as [ $palabra, $consecuencia ] ) {
            vx_marca_valor( $palabra, $consecuencia );
        }
    } );

    vx_marca_bloque( 'estrategia-publicos-objetivos', function () {
        vx_marca_p( 'Tres, y el primero y el segundo suelen ser la misma persona en momentos distintos.' );
        vx_marca_valor( 'La empresa de servicios B2B en expansión', 'Dice «hago bien lo mío y necesito que lo sepan donde no tengo contactos». No dice «quiero hacer networking». El tamaño no la define: una consultora de tres personas y una de trescientas entran por la misma puerta.' );
        vx_marca_valor( 'El mismo miembro buscando proveedor', 'El que hoy pide recomendaciones por WhatsApp porque no le cree a un buscador. Llega con una necesidad concreta y poco tiempo.' );
        vx_marca_valor( 'Las comunidades verticales', 'Entran por afinidad, con LGBTQ+, Woman o Senior, y se quedan por el directorio. La afinidad abre la puerta; lo que retiene es el negocio.' );
        vx_marca_p( 'Deja fuera, y conviene decirlo: quien vende a consumidor final, quien busca empleo y quien quiere una audiencia para publicar contenido.' );
    } );

    vx_marca_bloque( 'estrategia-objetivos-estrategicos', function () {
        vx_marca_p( 'Uno manda sobre todos los demás, y tiene fecha.' );
        vx_marca_declaracion( '100 miembros aprobados antes de que termine 2026, para empezar a cobrar en 2027.' );
        vx_marca_p( 'De ahí sale el programa de lanzamiento entero, y por eso el sistema lo hace cumplir en vez de confiarlo al copy: los <strong>primeros 100 miembros aprobados</strong> reciben el distintivo de Miembro Pionero, que es permanente, y la membresía vence a los <strong>90 días</strong> de la aprobación.' );
        vx_marca_p( 'La consecuencia al escribir es que el programa no se anuncia como una promoción sino como una fecha de corte. Cuando se llegue a 100, cierra.' );
        vx_marca_pendiente( 'El modelo de suscripción de 2027 no está definido, y el sitio ya promete que los Miembros Pioneros tendrán condiciones preferenciales. Esa promesa hay que poder cumplirla.' );
    } );

    vx_marca_bloque( 'estrategia-propuesta-de-valor', function () {
        vx_marca_declaracion( 'Muestras lo que haces ante un directorio de empresas verificadas, y a cambio te dejas verificar.' );
        vx_marca_p( 'Ese es todo el trato, y por eso la verificación no es un trámite de entrada: es el producto. Lo que se promete es visibilidad ante empresas reales que están ahí para hacer negocios. Lo que no se promete es el negocio.' );
        vx_marca_p( 'Lo que se paga no es el contacto ni la comisión, sino estar en el directorio. Cualquier cobro por conectar a dos miembros rompe la propuesta.' );
    } );
}

// ═════════════════════════════════════════════════ 02 Identidad visual ══

function vx_marca_identidad(): void {

    vx_marca_bloque( 'identidad-logotipo', function () {
        vx_marca_p( 'El logotipo es un archivo vectorial, <code>assets/img/vitrinexo.svg</code>, de proporción <strong>724 × 246</strong>. Se usa siempre ese archivo: ninguna pantalla lo vuelve a dibujar, ni con tipografía ni con formas.' );
        ?>
        <div class="vx-logo-caja">
            <img src="<?php echo esc_url( vx_logo_url() ); ?>"
                 alt="Logotipo de Vitrinexo: la palabra vitrinexo en minúsculas, con un marco coral alrededor del centro y los puntos de las íes en salvia y lavanda."
                 class="vx-logo" data-logotipo="1">
        </div>
        <?php
        vx_marca_declaracion( 'Los cuatro colores del logotipo son del sistema.' );
        vx_marca_p( 'Eso es nuevo y es la diferencia que más importa. La palabra va en la tinta, el marco en el acento, y los puntos de las dos íes en salvia y lavanda. <strong>No hay un color de logotipo que la interfaz no pueda usar, ni uno de interfaz que el logotipo desconozca.</strong> Antes había dos mundos cromáticos conviviendo y había que explicarlo; ahora no hay nada que explicar.' );
        vx_marca_p( '<strong>El marco es la vitrina.</strong> Abre arriba y cierra abajo alrededor del centro de la palabra, y es el único elemento gráfico propio del logotipo. No se separa de la palabra, no se usa suelto como sello y no se rellena.' );
        vx_marca_p( '<strong>La palabra va en minúsculas, siempre.</strong> Es la única excepción a la regla de escritura: en texto corrido el nombre se escribe Vitrinexo con mayúscula inicial, y en el logotipo no. Nadie lo corrige.' );
        vx_marca_p( 'Va sobre fondo claro, blanco o hueso. Sobre tinta hace falta la versión monocroma en blanco, que todavía no existe como archivo.' );
        vx_marca_pendiente( 'Faltan tres cosas, las tres medidas: el área de respeto, el tamaño mínimo y las dos versiones monocromas, tinta y blanco. Propuesta a aprobar para las dos primeras: respeto igual al alto del marco por cada lado, y mínimo de 140 px de ancho en pantalla, que es donde la palabra deja de leerse cómoda.' );
    } );

    vx_marca_bloque( 'identidad-isotipo', function () {
        vx_marca_declaracion( 'La vitrina, con los nexos en sus aberturas.' );
        vx_marca_p( 'El isotipo sale del marco del logotipo, no de otra idea. En el logotipo el marco se abre a los lados y <strong>los dos puntos están centrados justo sobre esas aberturas</strong>: la vitrina se abre y los nexos la ocupan. El isotipo conserva eso y nada más.' );
        vx_marca_tamanos( 'isotipo.svg', 'Isotipo de Vitrinexo: un marco coral abierto a los lados, con un punto salvia y uno lavanda ocupando las aberturas.', [ 128, 64, 48, 32 ], 'La versión de marca. Se usa de 48 px para arriba: más abajo el trazo baja de 2 px y las aberturas se cierran.' );
        vx_marca_declaracion( 'De 32 para abajo, la versión llena.' );
        vx_marca_p( 'El trazo del marco mide 1,6 px cuando el isotipo se dibuja a 16, y a ese tamaño un contorno desaparece. La versión llena invierte el problema: la vitrina se rellena y <strong>los dos nexos quedan calados</strong>, así que lo que se ve es la forma y no la línea.' );
        vx_marca_tamanos( 'isotipo-lleno.svg', 'Isotipo lleno de Vitrinexo: un cuadrado coral redondeado con dos círculos calados.', [ 64, 48, 32, 24, 16 ], 'Es la misma idea resuelta al revés, y es la que vive en el favicon.' );
        vx_marca_p( 'No se mezclan: la abierta nunca baja de 48 y la llena nunca sube de 32. Entre medio manda el soporte, y si hay duda se usa la llena.' );
        vx_marca_pendiente( 'Faltan las dos monocromas, tinta y blanco, para fondos saturados y para impresión a un color.' );
    } );

    vx_marca_bloque( 'identidad-tipografias', function () {
        vx_marca_p( '<strong>Switzer Variable</strong>, y nada más. Se sirve desde el propio servidor en <code>assets/fonts/</code>, en eje de peso continuo de 100 a 900, con romana e itálica.' );
        vx_marca_p( 'Una sola familia en dos papeles: <code>--font-display</code> para lo que se lee antes y <code>--font-body</code> para el cuerpo. Hoy las dos apuntan a Switzer, y tenerlas separadas es lo que permite cambiar una sin tocar la otra.' );
        vx_marca_p( 'De los nueve pesos disponibles se usan cuatro: <strong>400</strong> para prosa, <strong>500</strong> para lo que acompaña, <strong>600</strong> para nombres y títulos, y <strong>700</strong> para el énfasis fuerte. Los demás existen y no se usan.' );
    } );

    vx_marca_bloque( 'identidad-jerarquia', function () {
        vx_marca_p( 'La jerarquía se arma con tres cosas y en este orden: <strong>tamaño</strong>, <strong>peso</strong> y <strong>tinta</strong>. El color nunca es lo único que distingue un nivel del siguiente, porque quien no distingue ese color se queda sin jerarquía.' );
        vx_marca_p( 'Un solo titular por pantalla. Lo que sigue baja un peldaño de la escala; saltarse dos peldaños para «que se note más» es lo que deja la página sin niveles intermedios.' );
        vx_marca_p( 'Esta página obedece la escala peldaño por peldaño, así que si alguien la rompe se nota leyendo.' );
    } );

    vx_marca_bloque( 'identidad-escala-tipografica', function () {
        vx_marca_p( 'Ocho peldaños y nada entre medio. Cada uno se muestra acá con el peldaño que documenta: lo que lees es el token, no una imagen del token.' );
        vx_marca_p( 'En pantalla angosta los dos titulares bajan un peldaño: el de portada usa el de sección y el de sección usa el de declaración. No es otra escala, es otro escalón de la misma.' );
        vx_marca_p( '<strong>El tracking va pegado al tamaño y no suelto.</strong> Cada peldaño trae el suyo, y por eso son dos valores y no uno: el mismo tracking que asienta un titular de 40 px aprieta una nota de 12. Los títulos cierran apenas, de 2% a 0,5%; la prosa no se toca; lo chico abre un poco.' );
        foreach ( [
            [ '--fs-display', 'Titular de portada', 'Uno por página, y solo en portada.' ],
            [ '--fs-h2', 'Parte', 'El título de una sección mayor, con su número al lado.' ],
            [ '--fs-h3', 'Declaración', 'Una frase que se sostiene sola. Es contenido, no rótulo.' ],
            [ '--fs-h4', 'Bloque', 'El título de un bloque dentro de una sección.' ],
            [ '--fs-body-l', 'Bajada', 'La entradilla de una parte. Más grande que la prosa porque se lee antes.' ],
            [ '--fs-body', 'Prosa', 'El cuerpo. Es el peldaño por defecto: si dudas, es este.' ],
            [ '--fs-body-s', 'Nota', 'Lo secundario, en tinta de apoyo.' ],
            [ '--fs-caption', 'Etiqueta y dato', 'Distintivos, pies de muestra y valores sueltos.' ],
        ] as [ $token, $uso, $como ] ) {
            vx_marca_peldano_texto( $token, $uso, $como );
        }
        vx_marca_p( 'Hay dos peldaños más que viven fuera de la escala fija de ocho, y son de tracking: <code>--ls-hero</code>, para el titular de la portada del sitio, que es el único que crece con el ancho hasta 64 px, y <code>--ls-sobretitulo-xs</code>, para la caja alta de 11 px o menos, porque cuanto más chica la mayúscula más aire necesita.' );
        vx_marca_p( '<strong>El tracking del sitio entero sale de acá.</strong> Había una regla general que apretaba todo h1 y h2 un 7% sin mirar el tamaño, que a 64 px son 4,5 px de menos entre letra y letra. Se borró, y las 66 reglas que declaraban tracking a mano ahora nombran su peldaño. Una prueba comprueba que no quede ninguna suelta.' );
        vx_marca_p( 'La escala se agregó al sistema con este manual. El CSS del producto trae <strong>277 declaraciones de tamaño escritas a mano</strong>, de las cuales <strong>166 caen fuera de estos ocho peldaños</strong>. Solo 13 px aparece 55 veces. Migrarlas es trabajo aparte y está anotado en el registro de decisiones.' );
    } );

    vx_marca_bloque( 'identidad-paleta-de-color', function () {
        vx_marca_declaracion( 'Pasteles sobre blanco, y un solo acento.' );
        vx_marca_p( 'La paleta tiene dos capas y solo una se puede nombrar desde una pantalla.' );
        vx_marca_p( '<strong>Los primitivos son el valor.</strong> Siete familias: la base, el coral y cinco pasteles. Cada familia trae su color, el que toma con el puntero encima, el que toma mientras se presiona y una versión suave para fondos. No significan nada por sí mismos: son colores.' );

        $prim = vx_marca_grupos_primitivos();
        foreach ( vx_marca_grupos_tokens() as $g ) {
            if ( in_array( $g['titulo'], $prim, true ) ) vx_marca_rampa( $g['titulo'], $g['tokens'] );
        }

        vx_marca_declaracion( 'El coral es el único acento, y por eso no se gasta.' );
        vx_marca_p( 'Va en la acción principal, en lo que hay que encontrar de un vistazo y en el marco del logotipo. Nada más. Si en una pantalla hay tres cosas en coral, dos están mal: el acento que se repite deja de serlo.' );
        vx_marca_p( 'Los pasteles acompañan y no compiten. Sirven de fondo, de distintivo y de estado, siempre con la tinta encima. <strong>Ninguno de los seis sirve como texto</strong>: salvia da 2,14 sobre la hoja, celeste 1,76, terracota 2,82. Lo que se lee va en tinta, en tinta secundaria o en el coral que se lee.' );

        vx_marca_p( '<strong>Los papeles dicen para qué sirve cada color, nunca de qué color son.</strong> Es lo único que una pantalla puede nombrar. El día que el coral cambie de valor, cambia en un lugar.' );

        foreach ( vx_marca_familias() as $f ) {
            $primero = array_key_first( $f['papeles'] );
            if ( str_starts_with( (string) $primero, '--color' ) ) vx_marca_familia( $f );
        }

        vx_marca_declaracion( 'Dos medidas que cambian cómo se pinta un botón.' );
        vx_marca_p( '<strong>La tinta sobre el coral es oscura, no blanca.</strong> Blanco sobre coral da 2,58 y no llega al mínimo de 4,5; la tinta da 6,99. Es lo contrario de lo que pide el reflejo, y por eso existe el papel <code>--color-on-primary</code>.' );
        ?>
        <div class="vx-celdas">
            <?php
            vx_marca_celda( '--color-on-primary', '--color-primary' );
            vx_marca_celda( '--color-text-inverse', '--color-primary' );
            vx_marca_celda( '--color-primary-ink', '--color-background' );
            vx_marca_celda( '--color-text-primary', '--color-surface' );
            vx_marca_celda( '--color-text-secondary', '--color-surface' );
            vx_marca_celda( '--color-text-primary', '--color-secondary' );
            ?>
        </div>
        <?php
        vx_marca_p( '<strong>El coral que se lee es un paso más oscuro que el del boceto.</strong> El valor propuesto daba 4,47 sobre la superficie hundida, y un papel que pasa en una superficie y falla en la de al lado no sirve. El que quedó da 4,80 en el peor caso de las tres.' );
        vx_marca_p( 'Y una consecuencia que conviene saber antes de diseñar: <strong>el foco va en tinta</strong>. Ningún pastel llega al 3:1 que la norma pide para un contorno, así que el contorno de foco no es de color de marca.' );
        vx_marca_pendiente( 'El repintado del producto va por la primera de tres etapas. La capa de tokens ya está, pero quedan 247 colores escritos a mano en el CSS del tema y 451 en el plugin, buena parte de ellos en los correos, donde no hay variables que valgan.' );
    } );

    vx_marca_bloque( 'identidad-iconografia', function () {
        vx_marca_p( '<strong>Tabler Icons 3.19.0</strong>, servidos como fuente de íconos. Trazo de 2 px sobre rejilla de 24, esquinas redondeadas, sin relleno.' );
        vx_marca_p( 'Un ícono nunca va solo cuando es la única manera de entender una acción: lleva texto al lado o etiqueta accesible. Y toma su color de la tinta que lo rodea, nunca uno propio.' );
        vx_marca_p( 'No se mezclan familias. Si falta un ícono, se busca en Tabler antes que en cualquier otro sitio, y si no está, se pide dibujarlo con el mismo trazo.' );
    } );

    vx_marca_bloque( 'identidad-motivos', function () {
        vx_marca_p( 'El motivo de la marca es la <strong>red</strong>: puntos que se unen con líneas, animada sobre el fondo en <code>assets/js/network.js</code>. Es la imagen literal de lo que hace el producto, así que no compite con el contenido: va detrás, tenue, y el contenido siempre queda encima.' );
        vx_marca_p( 'Aparece en la portada y en las páginas legales. No se usa como textura de tarjeta, ni detrás de texto largo, ni en correos.' );
    } );

    vx_marca_bloque( 'identidad-direccion-fotografica', function () {
        vx_marca_declaracion( 'Gente trabajando, nunca gente posando.' );
        vx_marca_p( 'En todas las fotos de la marca hay alguien a media acción: un apretón que ya empezó, una mano sobre el teclado, una copa levantada, una mirada a la pantalla. <strong>Nadie mira a la cámara</strong>, porque en cuanto alguien mira deja de trabajar y empieza a actuar, y la foto se vuelve stock.' );

        vx_marca_valor( 'El lugar dice el rubro, sin leyenda', 'Planos y una maqueta dicen arquitectura; una calculadora y gráficos dicen contabilidad; un casco al fondo dice obra. El oficio se reconoce por los objetos que hay sobre la mesa, así que la mesa se arma antes de disparar. Un escritorio limpio no dice nada.' );
        vx_marca_valor( 'Luz natural y lateral', 'Ventanal grande fuera de cuadro, sombra suave, nada de flash ni de estudio. De noche manda la luz que está en la escena: velas sobre la mesa, guirnalda arriba, ciudad desenfocada atrás.' );
        vx_marca_valor( 'Altura de los ojos y plano medio', 'Horizontal, de pie o sentado a la misma altura que la persona. Sin picados, sin contrapicados y sin primerísimos planos: se ve la persona y se ve dónde está.' );
        vx_marca_valor( 'Profundidad corta', 'El fondo se reconoce y no compite. La estantería, el ventanal y la ciudad están, pero suaves.' );
        vx_marca_valor( 'Ropa de trabajo de verdad', 'Camisa lisa, mangas arremangadas, sin corbata. Colores de la misma familia que la paleta: celeste, beige, blanco, verde oliva. Nada de traje de catálogo.' );
        vx_marca_valor( 'Diversidad que no es el tema', 'Edad, género y origen variados en el mismo cuadro, sin que la foto trate de eso. Hay gente de treinta y gente de sesenta, y nadie está ahí para representar una categoría.' );

        vx_marca_p( 'Hay <strong>dos registros de luz</strong> y no se mezclan. El de la plataforma es de día y frío: vidrio, hormigón, azul grisáceo y blanco, con madera y beige para templar. El de lo presencial es de noche y cálido: ámbar, vela y bokeh de ciudad.' );

        vx_marca_foto( 'nexo-ausente.jpg', 'Dos hombres se dan la mano en una oficina con estantería de madera; entre ellos, una tercera persona traslúcida con el contorno de línea discontinua.', 'El registro de día: luz de ventanal a la izquierda, madera, camisa celeste y beige, apretón a media altura.' );

        vx_marca_declaracion( 'La silueta del nexo' );
        vx_marca_p( 'Es el recurso propio de la marca en fotografía, y no se usa en cualquier foto: <strong>aparece solo cuando la imagen habla de la conexión que todavía no existe</strong>. Una persona o una escena entera, traslúcida, con contorno de línea discontinua blanca, integrada en la misma perspectiva y con la misma luz que el resto.' );
        vx_marca_p( 'Una por imagen, nunca dos. No tapa la cara de nadie real. Y no se usa en las fotos presenciales: ahí la gente ya está, que es justamente el punto.' );
        vx_marca_foto( 'escena-imaginada.jpg', 'Una mujer sentada frente a su laptop mira hacia una escena traslúcida de una reunión de seis personas; el contorno punteado rodea solo el apretón de manos.', 'Acá la silueta es una escena completa y el contorno marca una sola cosa: el apretón. Lo que ella está imaginando es el nexo, no la reunión.' );

        vx_marca_p( 'El <strong>apretón de manos es el gesto de la marca</strong> y aparece en casi todas las fotos. Siempre dentro de una escena de trabajo, nunca recortado sobre fondo blanco ni en primer plano sin contexto.' );

        vx_marca_foto( 'rubros.jpg', 'Tríptico: una mujer revisa gráficos con una calculadora, un hombre trabaja sobre planos junto a una maqueta, y dos personas se dan la mano en una oficina.', 'Para mostrar variedad de rubros: tres paneles verticales, un oficio por panel, la misma luz y la misma altura de cámara en los tres.' );
        vx_marca_foto( 'comunidades.jpg', 'Terraza de noche con guirnalda de luces, cuatro mesas redondas ocupadas por grupos distintos y la ciudad desenfocada al fondo.', 'Para hablar de las comunidades: un plano general, varias mesas en una misma azotea. Una foto que dice una plataforma y muchas comunidades sin escribirlo.' );
        vx_marca_foto( '4dinner.jpg', 'Cuatro personas cenando en una terraza de noche, con velas sobre la mesa y luces de la ciudad al fondo.', 'Vitrinexo 4Dinner: exactamente cuatro personas y una mesa redonda. El número es la promesa, así que la foto lo cumple.' );

        vx_marca_p( 'Lo que no se fotografía: nadie mirando a cámara, nadie saltando ni chocando los cinco, ningún gráfico ni ícono flotando encima de la imagen, ninguna oficina vacía, ningún escritorio sin rastro de trabajo y ningún recorte sobre fondo blanco.' );

        vx_marca_declaracion( 'Para encargar o generar una' );
        vx_marca_p( '<strong>Persona en su lugar de trabajo.</strong> «Fotografía editorial horizontal de una [profesión] de [edad] trabajando en su [lugar], a media acción y sin mirar a cámara. Sobre la mesa, los objetos de su oficio. Luz natural lateral de ventanal grande, sombras suaves, profundidad de campo corta con el fondo reconocible y desenfocado. Paleta de neutros fríos con madera y beige. Camisa lisa, mangas arremangadas, sin corbata. Altura de los ojos, plano medio.»' );
        vx_marca_p( '<strong>La silueta del nexo.</strong> Se agrega a la anterior: «Entre las personas, una figura humana traslúcida con contorno de línea discontinua blanca, integrada en la misma perspectiva y con la misma luz, que representa a alguien que todavía no está en la escena.»' );
        vx_marca_p( '<strong>Presencial de noche.</strong> «Cuatro personas cenando en una terraza urbana de noche, mesa redonda, conversación a media frase. Luz cálida de velas sobre la mesa y guirnalda de bombillas arriba; ciudad desenfocada al fondo. Paleta ámbar. Nadie mira a cámara. Horizontal, altura de los ojos.»' );
    } );

    vx_marca_bloque( 'identidad-favicon', function () {
        vx_marca_p( 'El ícono del navegador es <strong>el isotipo lleno</strong>, nunca el logotipo: la palabra completa a 16 px es una mancha.' );
        vx_marca_tamanos( 'favicon-32.png', 'Favicon de Vitrinexo a 32 píxeles.', [ 32, 16 ], 'Los dos tamaños que el navegador pide de verdad.' );
        vx_marca_p( 'Se sirven cuatro archivos: el SVG para los navegadores que lo toman, y PNG de 32, 16 y 180, este último para el ícono de pantalla de inicio en iOS. Todos llevan la versión del tema en la URL, porque el CDN cachea por URL y un ícono sin query no cambia nunca.' );
    } );

    vx_marca_bloque( 'identidad-imagen-para-compartir', function () {
        vx_marca_p( 'Lo que se ve cuando alguien pega un enlace de Vitrinexo en LinkedIn o en WhatsApp. Mide <strong>1200 × 630</strong>, va sobre hueso y lleva tres cosas: el logotipo, el tagline y el dominio. Nada más.' );
        ?>
        <figure class="vx-foto">
            <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/compartir.png' ); ?>"
                 alt="Tarjeta para compartir: el logotipo de Vitrinexo sobre fondo hueso, con el tagline debajo y el dominio al pie."
                 class="vx-imagen" loading="lazy">
            <figcaption class="vx-nota">Sin fotos y sin texto de campaña: lo que se comparte es la marca, no una promoción que caduca.</figcaption>
        </figure>
        <?php
        vx_marca_p( 'El texto sale del tagline declarado, así que si el tagline cambia, la tarjeta se regenera y no queda una versión vieja circulando por ahí.' );
    } );
}

// ══════════════════════════════════════════════════════════════ 03 Voz ══

function vx_marca_voz(): void {

    vx_marca_bloque( 'voz-idioma', function () {
        vx_marca_p( '<strong>Español, y se trata de tú.</strong> Nunca voseo: «tienes» y «puedes», no «tenés» ni «podés».' );
        vx_marca_p( 'El lanzamiento y la comunidad inicial son de Hispanoamérica, así que el español es el de la región y no el peninsular. La visión incluye Brasil, Estados Unidos y Europa, y eso obliga a una regla al escribir: <strong>no se dice que Vitrinexo sea una plataforma latinoamericana</strong>, aunque hoy sus miembros lo sean. Lo que se dice es dónde hay comunidad hoy.' );
        vx_marca_pendiente( 'Portugués e inglés no existen todavía. Cuando entren, hay que decidir si son el mismo texto traducido o se escriben aparte, y qué pasa con «vitrinear», que no se traduce.' );
    } );

    vx_marca_bloque( 'voz-tono', function () {
        vx_marca_declaracion( 'Directo y sin adorno, porque el interlocutor está trabajando.' );
        vx_marca_p( 'Vitrinexo le habla a alguien que dirige una empresa y tiene poco tiempo. Frase corta, dato adelante, y la conclusión antes que el argumento. Si un párrafo se puede leer en diagonal y aun así se entiende, está bien escrito.' );
        vx_marca_valor( 'Con quien todavía no es miembro', 'Se nombra el problema con sus palabras antes de ofrecer nada: «mucho evento, poco nexo». No se promete resultado de negocio, porque no depende de nosotros. Se promete visibilidad ante empresas verificadas, que sí.' );
        vx_marca_valor( 'Con el miembro', 'Se le habla como a un par, no como a un usuario al que hay que activar. Nada de urgencia inventada ni de rachas. Si no hay novedad, no se escribe.' );
        vx_marca_valor( 'Frente a una objeción', 'Se le da la razón en lo que la tiene y después se responde. «Nadie puede garantizar resultados de negocio» va antes que lo que sí se garantiza. Una objeción respondida a la defensiva confirma la sospecha.' );
        vx_marca_p( 'Lo que nunca se dice: que Vitrinexo es una red social, que hay que estar activo para que funcione, ni una cifra de comunidad que no se pueda sostener. Tampoco se pide disculpas por vender.' );
    } );

    vx_marca_bloque( 'voz-mayusculas-y-minusculas', function () {
        vx_marca_p( '<strong>Tipo oración en todas partes</strong>: en títulos, botones, etiquetas y avisos. Solo va mayúscula la primera letra y lo que sea nombre propio.' );
        vx_marca_p( 'La caja alta se reserva para los sobretítulos, que son rótulos de sección y no texto para leer. Nunca se usa para dar énfasis: para eso está el peso.' );
        vx_marca_p( 'El nombre de la marca se escribe siempre <strong>Vitrinexo</strong>, con una sola mayúscula. Nunca «VitriNexo» ni «VITRINEXO», tampoco en logotipos de terceros ni en firmas de correo.' );
    } );

    vx_marca_bloque( 'voz-vocabulario', function () {
        vx_marca_p( 'Los términos del producto tienen una sola forma. No son sinónimos intercambiables: cada uno de la izquierda es el que está en la base de datos, en los correos y en la interfaz.' );
        vx_marca_vocabulario( [
            [ 'Vitrinexo', 'VitriNexo, Vitrinexo.com' ],
            [ 'Miembro Pionero', 'Socio Fundador, Afiliado Original, early adopter' ],
            [ 'distintivo', 'badge, insignia' ],
            [ 'miembro', 'usuario, cliente, afiliado' ],
            [ 'perfil', 'ficha, tarjeta de empresa' ],
            [ 'conexión', 'match, contacto, vínculo' ],
            [ 'directorio', 'listado, catálogo, base de datos' ],
            [ 'membresía', 'suscripción, plan de pago' ],
        ] );
        vx_marca_p( 'Hay además cuatro palabras que no se dicen de ninguna forma, porque no significan nada y se nota: <strong>ecosistema</strong>, <strong>sinergia</strong>, <strong>solución</strong> y <strong>partner</strong>. No tienen reemplazo: si una frase las necesita, la frase no estaba diciendo nada.' );
        vx_marca_p( 'Y una regla de puntuación: <strong>no se usan guiones largos</strong> en ningún texto de la marca. Donde aparezca uno va una coma, un punto o dos puntos.' );
        vx_marca_p( 'El verbo propio, <strong>vitrinear</strong>, se conjuga normal y no se pone entre comillas. Está en el diccionario de la marca, no es una gracia.' );
    } );

    vx_marca_bloque( 'voz-patrones-de-copy', function () {
        vx_marca_valor( 'El botón dice el verbo y su objeto', 'Dentro de la plataforma va en infinitivo y en tipo oración: «Guardar ajustes», «Enviar solicitud», «Buscar». Nunca un verbo suelto que obligue a adivinar qué se guarda. En captación, y solo ahí, va en segunda persona: «Inscríbete», «Empieza a vitrinear». Sin flechas y sin puntos suspensivos. Una flecha escrita dentro del rótulo no es un ícono: no se alinea, no toma la tinta del texto y se ve distinta en cada sistema.' );
        vx_marca_valor( 'El título de pantalla es un sustantivo', 'Directorio, Conexiones, Mis publicaciones. No es una frase ni un saludo: el saludo va debajo si hace falta.' );
        vx_marca_valor( 'El error dice qué pasó y qué hacer', 'En ese orden, en segunda persona y sin culpar a nadie. «Incluye el prefijo de país con + en tu celular (ej: +56 9 1234 5678)» es el patrón: nombra el problema y muestra la forma correcta. Un error que solo dice «campo inválido» obliga a adivinar.' );
        vx_marca_valor( 'El campo obligatorio se reclama de una sola forma', '«El nombre de tu empresa es obligatorio.» Se nombra el campo, no se dice «campos requeridos» ni «no puede estar vacío» ni «requerido» a secas. Hoy conviven las cuatro formas y solo esa queda.' );
        vx_marca_valor( 'El aviso dice el estado y qué sigue', '«Tu perfil está en revisión. Te escribimos cuando esté aprobado.» Sin el qué sigue, el aviso deja a alguien esperando sin saber cuánto.' );
        vx_marca_valor( 'La pantalla vacía dice por qué está vacía', 'Y ofrece la acción que la llena. Una pantalla vacía sin explicación se lee como una falla del producto.' );
    } );

    vx_marca_bloque( 'voz-numeros-y-formatos', function () {
        vx_marca_p( 'La marca opera en varios países a la vez, y casi todas estas reglas existen por eso: un formato que se lee distinto en Santiago y en Bogotá no es un detalle de estilo, es un dato mal entendido.' );
        vx_marca_valor( 'Teléfono', 'Siempre con prefijo internacional y con el signo más adelante: +56 9 1234 5678. Lo pide el sistema al registrar, y es lo que necesita el botón de WhatsApp para funcionar desde cualquier país.' );
        vx_marca_valor( 'Moneda', 'El monto va precedido del código de tres letras: USD 49. Nunca el signo peso solo, porque en Hispanoamérica ese signo nombra siete monedas distintas. La moneda del sistema es un ajuste y hoy está en USD.' );
        vx_marca_valor( 'Fecha', 'Corta, 24/09/2026. Larga, miércoles 24 de septiembre de 2026, en minúscula el día y el mes. Con hora, 24/09/2026 20:00.' );
        vx_marca_valor( 'Hora', 'En formato de 24 horas y con dos puntos: 20:00. Cuando algo pasa a la vez en varias ciudades, la hora va siempre seguida de «hora local», porque sin eso la mitad de los invitados calcula mal.' );
        vx_marca_valor( 'Miles y decimales', 'Punto para los miles y coma para los decimales: 1.234,56. Es la convención del español, y mezclarla con la inglesa en la misma pantalla es la forma más rápida de que alguien lea mil veces de más.' );
        vx_marca_valor( 'Porcentaje', 'Pegado al número: 35%.' );
        vx_marca_pendiente( 'El sitio anuncia las cenas como «miércoles 8pm» y el código guarda 20:00. Hay que elegir uno. Y cuando entre Estados Unidos, la fecha corta d/m/Y se va a leer al revés: esa revisión queda anotada para entonces.' );
    } );

    vx_marca_bloque( 'voz-emojis', function () {
        vx_marca_declaracion( 'Ninguno, en ninguna parte.' );
        vx_marca_p( 'Ni en la interfaz, ni en los correos, ni en las notificaciones, ni en el panel de administración. No hay excepción por tono ni por canal.' );
        vx_marca_p( 'La regla cubre también los pictogramas que no son emojis de manual y hacen el mismo trabajo: el visto, el aspa, la flecha escrita dentro de un rótulo. Se ven distinto en cada sistema operativo, no toman la tinta que los rodea y no se pueden alinear. <strong>Donde hacía falta un símbolo va un ícono de Tabler</strong>, que sí hace las tres cosas.' );
        vx_marca_p( 'En un correo, donde el ícono tipográfico no se puede cargar, el símbolo se reemplaza por la palabra: «Fecha:» y «Lugar:» en vez de un calendario y un pin.' );
    } );

}
