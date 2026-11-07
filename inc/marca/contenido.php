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
        vx_marca_p( 'La portada también lo lee de ahí, debajo del logotipo. <strong>No es su titular</strong>: el titular de la portada es una promesa concreta y cambia con la campaña, mientras que el tagline dice qué es Vitrinexo y no cambia. Sus dos mitades, vitrina y nexos, se destacan solas: <code>vx_tagline_html()</code> las marca a partir de la misma frase, así que el destacado es una regla y no una edición a mano que alguien tiene que repetir cuando el tagline cambie.' );
    } );

    vx_marca_bloque( 'estrategia-linea-de-producto', function () {
        vx_marca_p( 'Una plataforma y cinco piezas. <strong>Todas se nombran con la marca madre adelante</strong>, sin excepción y también en conversación: se escribe «Vitrinexo 4Dinner», nunca «<span data-cita>4Dinner</span>» a secas.' );
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
        vx_marca_p( '<strong>El lanzamiento es en Hispanoamérica y en español.</strong> La visión no se queda ahí: incluye el mundo hispanohablante completo, Brasil, Estados Unidos y Europa. Esa diferencia se sostiene al escribir, y en los dos sentidos: no se promete presencia donde todavía no la hay, y no se dice que Vitrinexo sea una <span data-cita>plataforma latinoamericana</span>.' );
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
        vx_marca_valor( 'Las comunidades verticales', 'Entran por afinidad, con Vitrinexo LGBTQ+, Vitrinexo Woman o Vitrinexo Senior, y se quedan por el directorio. La afinidad abre la puerta; lo que retiene es el negocio.' );
        vx_marca_p( 'Deja fuera, y conviene decirlo: quien vende a consumidor final, quien busca empleo y quien quiere una audiencia para publicar contenido.' );
    } );

    vx_marca_bloque( 'estrategia-objetivos-estrategicos', function () {
        vx_marca_p( 'Uno manda sobre todos los demás, y tiene fecha.' );
        vx_marca_declaracion( '100 miembros aprobados antes de que termine 2026, para empezar a cobrar en 2027.' );
        vx_marca_p( 'De ahí sale el programa de lanzamiento entero, y por eso el sistema lo hace cumplir en vez de confiarlo al copy: los <strong>primeros 100 miembros aprobados</strong> reciben el distintivo de Miembro Pionero, que es permanente.' );
        vx_marca_p( 'La consecuencia al escribir es que el programa no se anuncia como una promoción sino como una fecha de corte. Cuando se llegue a 100, cierra.' );
        vx_marca_declaracion( 'El período gratuito no vence en una fecha: vence en un número.' );
        vx_marca_p( 'Termina cuando el programa llega a sus <strong>100 miembros</strong>. Ahí se avisa y se da un plazo antes de cobrar, y recién entonces se fija la fecha. Hasta ese momento el plan de un Pionero no tiene vencimiento, y eso está escrito en el sistema y no en el copy: la aprobación guarda «sin vencimiento», y el cron de avisos no le manda nada a quien no tiene fecha.' );
        vx_marca_p( 'Antes la regla era noventa días desde la aprobación, y la decisión la cambió. El cambio no le quita nada a los 44 que ya se inscribieron leyendo la promesa anterior: con 44 de 100, llegar al cupo toma más que tres meses, así que el período se alarga. Lo único que desaparece es la fecha fija, y en su lugar queda dicho lo que sí se va a cumplir, que es avisar antes de cobrar.' );
        vx_marca_declaracion( 'Condiciones preferenciales quiere decir un precio más bajo, para siempre.' );
        vx_marca_p( 'No es un precio congelado ni un año gratis, que son otras dos promesas distintas: es <strong>19 en vez de 29 al mes, mientras el miembro siga siendo miembro</strong>. Los precios son los tres que el panel trae: <strong>29 mensual, 249 anual y 19 preferencial</strong>, y ahora sí son una decisión y no un valor que alguien escribió.' );
        vx_marca_p( 'Lo que falta no es una decisión sino una pieza: el disparador. Hoy nada ocurre solo al aprobar al miembro 100, no hay aviso ni plazo ni fecha, y mientras el contador vaya en 44 hay tiempo. Pero el día que llegue a 100, esto se hace a mano o no se hace, y conviene que esté dicho acá y no descubierto ese día.' );
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
        vx_marca_declaracion( 'El área de respeto es la altura de la o.' );
        vx_marca_p( 'Por los cuatro lados, y se mide sola: <strong>89 unidades de las 246 que mide el logotipo de alto</strong>, o sea un 36% de su altura, a cualquier tamaño. Se eligió una medida del propio logotipo y no un número redondo para que el respeto escale con él sin que nadie tenga que recalcularlo.' );
        vx_marca_declaracion( 'El tamaño mínimo son 90 px de ancho.' );
        vx_marca_p( 'Es donde la o mide 11 px, que es el piso en que una minúscula todavía se lee en pantalla. Más abajo no se achica el logotipo: se cambia por el isotipo.' );
        vx_marca_p( '<strong>Hay dos monocromas</strong>, una en tinta y otra en blanco, con la misma regla que el isotipo: la de tinta sobre claro y la blanca sobre oscuro. Son archivos aparte, no un filtro, porque nadie recolorea el logotipo desde el CSS.' );
        vx_marca_variantes( [
            [ 'vitrinexo.svg', '--color-background', 'color · sobre claro', 'Logotipo en color sobre fondo claro.' ],
            [ 'vitrinexo-tinta.svg', '--color-surface', 'tinta · sobre claro', 'Logotipo monocromo en tinta sobre fondo claro.' ],
            [ 'vitrinexo-blanco.svg', '--color-text-primary', 'blanco · sobre tinta', 'Logotipo monocromo en blanco sobre fondo de tinta.' ],
        ], 'En monocromo el marco pierde el acento, así que la palabra queda sola y el nombre pesa más.' );
        ?>
        <div class="vx-celdas">
            <?php
            vx_marca_celda( '--color-text-primary', '--color-background' );
            vx_marca_celda( '--color-text-inverse', '--color-text-primary' );
            ?>
        </div>
        <?php
    } );

    vx_marca_bloque( 'identidad-isotipo', function () {
        vx_marca_declaracion( 'La vitrina, con los nexos en sus aberturas.' );
        vx_marca_p( 'El isotipo sale del marco del logotipo, no de otra idea. En el logotipo el marco se abre a los lados y <strong>los dos puntos están centrados justo sobre esas aberturas</strong>: la vitrina se abre y los nexos la ocupan. El isotipo conserva eso y nada más.' );
        vx_marca_tamanos( 'isotipo.svg', 'Isotipo de Vitrinexo: un marco coral abierto a los lados, con un punto salvia y uno lavanda ocupando las aberturas.', [ 128, 64, 48, 32, 24, 16 ], 'Esta página afirmaba que se usaba de 48 px para arriba. Se midió: a 32 se lee entero y a 24 todavía. Lo que se pierde a 16 no es el marco, son los dos puntos.' );

        vx_marca_declaracion( 'Hubo una versión llena, y se retiró.' );
        vx_marca_p( 'Era un cuadrado coral con los dos nexos calados, y resolvía el tamaño chico a costa de la idea: sin las aberturas el marco deja de ser una vitrina y <strong>lo que queda son dos puntos dentro de una caja, que se lee como una cara</strong>. Un isotipo que a 16 px es legible y a 128 px es otra marca no sirve.' );
        vx_marca_p( 'Sus tres archivos se borraron del repositorio. No hay una resolución para arriba y otra para abajo: hay un isotipo.' );

        vx_marca_declaracion( 'Para el favicon, el mismo marco sobre una baldosa.' );
        vx_marca_p( 'Lo que el tamaño chico necesita no es rellenar el marco, es <strong>una silueta que lo sostenga</strong>. La baldosa pone el marco calado en blanco sobre un cuadrado coral: la forma de la vitrina intacta, y un bloque de color que la pestaña del navegador sí puede dibujar a 16 px.' );
        vx_marca_tamanos( 'isotipo-baldosa.svg', 'Isotipo de Vitrinexo calado en blanco sobre una baldosa coral redondeada.', [ 64, 48, 32, 24, 16 ], 'Vive en el favicon y en el ícono de pantalla de inicio, y en ningún otro sitio: dentro de una página el isotipo va suelto, sin baldosa.' );

        vx_marca_declaracion( 'Dos monocromas, y cada una tiene su fondo.' );
        vx_marca_p( 'Existen para donde el color no puede ir: un fondo saturado, una impresión a un color, un sello. No son un filtro sobre la versión de color, son archivos aparte, y <strong>la elección no es de gusto sino de fondo</strong>: la de tinta va sobre claro y la blanca sobre oscuro. Nunca al revés, y nunca la de color sobre un fondo que compita con el coral.' );
        vx_marca_variantes( [
            [ 'isotipo.svg', '--color-background', 'color · sobre claro', 'Isotipo en color sobre fondo claro.' ],
            [ 'isotipo-tinta.svg', '--color-surface', 'tinta · sobre claro', 'Isotipo monocromo en tinta sobre fondo claro.' ],
            [ 'isotipo-blanco.svg', '--color-text-primary', 'blanco · sobre tinta', 'Isotipo monocromo en blanco sobre fondo de tinta.' ],
            [ 'isotipo-blanco.svg', '--color-primary', 'blanco · sobre coral', 'Isotipo monocromo en blanco sobre fondo coral.' ],
        ], 'Sobre coral va la blanca y no la de color: el marco coral sobre coral desaparece.' );
        vx_marca_p( 'Cuatro archivos en total, y ninguno se genera desde el CSS.' );
    } );

    vx_marca_bloque( 'identidad-tipografias', function () {
        vx_marca_p( '<strong>Switzer Variable</strong> para todo lo que se lee, y <strong>JetBrains Mono</strong> para el dato. Las dos se sirven desde el propio servidor en <code>assets/fonts/</code>. Switzer trae eje de peso continuo de 100 a 900, con romana e itálica.' );
        vx_marca_p( 'Una sola familia en dos papeles: <code>--font-display</code> para lo que se lee antes y <code>--font-body</code> para el cuerpo. Hoy las dos apuntan a Switzer, y tenerlas separadas es lo que permite cambiar una sin tocar la otra.' );
        vx_marca_p( 'Se usan <strong>tres pesos</strong> y están en el sistema: <code>--peso-prosa</code> 400 para el cuerpo, <code>--peso-apoyo</code> 500 para lo que acompaña, <code>--peso-titulo</code> 600 para nombres y títulos. <strong>El 600 es el peso más alto que la identidad admite.</strong>' );
        vx_marca_p( 'Había diecisiete reglas en 700 y se migraron al 600. El 700 no estaba decidido: estaba escrito a mano, regla por regla, cada vez que algo tenía que verse más fuerte. Y el 640 del logotipo no entra en esta escala, porque el logotipo es un archivo SVG y no texto vivo: ese peso ya está dibujado.' );

        vx_marca_declaracion( 'El dato va en mono.' );
        vx_marca_p( '<strong>JetBrains Mono</strong> viste lo que se lee de un vistazo y no se lee seguido: etiquetas en caja alta, fechas, horas y cifras. «OFRECE · LEGAL», «VERIFICADO · 24/09/2026», «MIÉRCOLES · 20:00 HORA LOCAL». La mono los alinea en columna y los separa de la prosa sin necesitar un color.' );
        vx_marca_p( 'No va nunca en una frase. En mono, una frase parece un mensaje de error del sistema, que es exactamente el tono contrario al de una mesa entre colegas.' );
        vx_marca_p( 'Se sirve subconjuntada a lo que el producto escribe de verdad: latín con acentos, dígitos y puntuación. Los dos pesos juntos pesan <strong>7,8 kB</strong>. Enteros pesaban 184, cuatro veces Switzer completa, para vestir unas etiquetas.' );

        vx_marca_declaracion( 'El segundo golpe va en itálica.' );
        vx_marca_p( 'Switzer <strong>itálica 300</strong>, en <code>.vx-remate</code>: <strong>una sola frase corta por pieza</strong>, para el remate. Es lo contrario de un destacado, porque pesa menos que la prosa y no más. Si hay dos en la misma pieza, ninguna remata nada.' );
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
        vx_marca_p( '<strong>La tinta sobre el coral es oscura, no blanca.</strong> Blanco sobre coral da 2,58 y no llega al mínimo de 4,5; la tinta da 6,99. Es lo contrario de lo que pide el reflejo, y por eso existe el papel <code>--color-on-fill</code>.' );
        ?>
        <div class="vx-celdas">
            <?php
            vx_marca_celda( '--color-on-fill', '--color-primary' );
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
        vx_marca_p( 'Tres familias enteras no seguían a ningún token y se remaparon: el ámbar del distintivo Pionero es mantequilla, el rosa de Vitrinexo Woman es rosa y el púrpura de Vitrinexo LGBTQ+ es lavanda.' );
        vx_marca_declaracion( 'Cada familia tiene un oficio, y dos estaban cruzados.' );
        vx_marca_p( 'La paleta de la propuesta le da a <strong>terracota</strong> los detalles cálidos y Vitrinexo 4Dinner, y a <strong>mantequilla</strong> remarcar una palabra. El producto hacía lo contrario: Vitrinexo 4Dinner era mantequilla entera y terracota era el botón de borrar.' );
        vx_marca_p( 'Se corrigió, y de paso arregló algo que no se veía venir: mantequilla vestía <strong>Vitrinexo 4Dinner y Vitrinexo Senior a la vez</strong>, así que sus tarjetas eran indistinguibles. Con Vitrinexo 4Dinner en terracota, Vitrinexo Senior se queda con la mantequilla y las dos se separan solas.' );
        vx_marca_valor( 'El peligro pasa a rosa', 'Terracota era el botón de borrar, y un botón de borrar no puede compartir color con un evento. Rosa ya era el borde de error de los campos, así que error y peligro quedan en la misma familia, que es lo que debieron ser siempre. Tinta sobre rosa da 11,99:1.' );
        vx_marca_valor( 'Remarcar una palabra existe ahora', 'Era un oficio asignado a la mantequilla que ninguna pantalla implementaba. Es <code>--color-remarcado</code>, y viste <code>mark</code>. Para una palabra dentro de una frase, nunca para una frase entera: lo que se remarca todo deja de estar remarcado.' );
        vx_marca_valor( 'Ningún pastel hace de tinta', 'El paso <code>-presion</code> de una familia es un color, no una tinta. Había cuatro sitios escribiendo texto en mantequilla-presión sobre mantequilla-suave: <strong>1,13:1</strong>, invisible. Van en tinta, que da 16,50.' );
        vx_marca_p( 'Y una lectura que conviene dejar dicha: la propuesta le da a lavanda «avisos y comunidades». Acá se leyó <strong>avisos como notificaciones</strong>, no como advertencias: la advertencia sigue siendo mantequilla, que es el amarillo de precaución de siempre, y lavanda viste las comunidades y el sistema de notificaciones.' );
        vx_marca_p( '<strong>En el tema no queda un solo color escrito a mano: ni en hexadecimal, ni en <code>rgba()</code>, ni en <code>hsl()</code>, ni en CSS, ni en PHP, ni en JavaScript.</strong> Esta página lo afirmaba antes de que fuera cierto. El barrido de la paleta miró <code>color:</code> y <code>background:</code> en hexadecimal dentro de hojas de estilo, y por los dos huecos que dejó sobrevivieron 62 colores de la paleta vieja en sombras, degradados y veladuras, más el azul, el cian y el verde con los que <code>network.js</code> seguía pintando los puntos del fondo de la portada.' );
        vx_marca_p( 'Lo translúcido ya no se escribe con <code>rgba()</code> y una copia del color, sino con <code>color-mix()</code> sobre el token: <code>color-mix(in srgb, var(--tinta) 12%, transparent)</code>. La diferencia no es de estilo. <code>rgba()</code> copia el valor y se queda donde estaba; <code>color-mix()</code> lo lee, así que si la tinta cambia, la sombra la sigue. La prueba de colores ahora falla ante cualquier <code>rgba()</code> o <code>hsl()</code> con números.' ); // primitivo a propósito: el ejemplo tiene que mostrar la forma exacta que se escribe.
        vx_marca_p( 'En el plugin quedan <strong>362 hexadecimales escritos</strong>, y están todos dentro de esa excepción: 154 en plantillas de correo, 103 en pantallas de wp-admin, 100 en páginas de opciones y en el documento imprimible, 3 en páginas de <code>wp_die()</code> y 2 en un SVG que se arma en el servidor. En los shortcodes, que es todo lo que el sitio público renderiza con la hoja del tema cargada, <strong>no hay ninguno</strong>.' );
        vx_marca_p( 'Lo que faltaba no era barrerlos sino ponerle un borde a la excepción. Valía para el repositorio entero, así que un color a mano en un shortcode pasaba igual que uno en un correo; que hoy no haya ninguno era suerte, no una regla. La prueba del plugin ahora separa las dos cosas: donde la hoja no está cargada comprueba que el valor exista en la paleta, y donde sí lo está exige <code>var(--token)</code>. Y la paleta la lee del <code>:root</code> del tema en vez de una copia, que es lo mismo que <code>color-mix()</code> hace con las sombras.' );
        vx_marca_p( 'Los correos y el panel de administración son la excepción obligada: un cliente de correo no entiende variables y el panel no carga la hoja del tema. Ahí el valor va literal, y lo que se comprueba no es que se nombre un papel sino que el valor exista en esta paleta.' );
    } );

    vx_marca_bloque( 'identidad-iconografia', function () {
        vx_marca_p( '<strong>Tabler Icons 3.19.0</strong>, servidos como fuente de íconos. Trazo de 2 px sobre rejilla de 24, esquinas redondeadas, sin relleno.' );
        vx_marca_p( 'Un ícono nunca va solo cuando es la única manera de entender una acción: lleva texto al lado o etiqueta accesible. Y toma su color de la tinta que lo rodea, nunca uno propio.' );
        vx_marca_p( 'No se mezclan familias. Si falta un ícono, se busca en Tabler antes que en cualquier otro sitio, y si no está, se pide dibujarlo con el mismo trazo.' );
    } );

    vx_marca_bloque( 'identidad-motivos', function () {
        vx_marca_declaracion( 'Un encuentro tiene muchas caras.' );
        vx_marca_p( 'El motivo de la marca es el <strong>collage de perfiles</strong>: un rostro armado con recortes de varias personas distintas. No es un adorno, es la tesis dibujada. Un nexo no es una persona, es lo que pasa entre varias.' );
        vx_marca_foto( 'collage.svg', 'Un rostro armado con recortes de seis perfiles distintos, cada uno sobre un fondo pastel.', 'Seis recortes de seis personas. Lo que lo hace funcionar no es la composición: es que los rasgos están en el mismo lugar en todos los perfiles.' );

        vx_marca_valor( 'Los rasgos van en posiciones fijas', 'Ojos, nariz y boca ocupan las mismas coordenadas en todos los perfiles. Por eso un recorte calza con cualquier otro sin empujar nada, y por eso el collage se lee como una cara y no como un rompecabezas mal armado. Es la única regla que no se puede negociar: si una ilustración mueve los rasgos, deja de servir.' );
        vx_marca_valor( 'La piel es un color de la paleta', 'Lavanda, rosa, mantequilla, celeste, salvia o terracota. Nadie va en color carne. No es un recurso gráfico: es lo que permite que el directorio entero se dibuje sin que el color de piel de nadie sea un dato.' );
        vx_marca_valor( 'Cada recorte lleva su propio fondo', 'Pastel distinto por casilla. Cuando el fondo coincide con la piel de ese perfil, se cambia por hueso o blanco: una cara no puede desaparecer contra su propio fondo.' );
        vx_marca_valor( 'Los rubros rotan', 'Tecnología, arte, música, recursos humanos, gastronomía. El collage es el directorio, así que no puede parecer una sola industria.' );

        vx_marca_declaracion( 'El lugar libre.' );
        vx_marca_p( 'Es la silueta punteada que ocupa un recorte en vez de un rostro: <strong>el miembro que todavía no está</strong>. Era el recurso de la silueta en las fotos y ahora tiene nombre y oficio, porque es el argumento del programa de lanzamiento: queda un puesto y puede ser el tuyo.' );
        vx_marca_foto( 'collage-lugar-libre.svg', 'El mismo collage con un recorte punteado en lugar de un rostro.', 'Uno por pieza y nunca dos. Dos lugares libres dejan de ser una invitación y pasan a parecer un directorio vacío.' );
        vx_marca_p( 'Se usa en captación de Miembros Pioneros y en nada más. Nunca en una pantalla de miembro: adentro ya no hay lugar que ofrecer.' );
        vx_marca_p( 'Y no va en cada pieza: <strong>va donde el texto invita.</strong> El banner del problema dice «aquí todos están para hacer negocios», así que un hueco punteado ahí contradice la frase que ilustra, y sería la tercera invitación del mismo scroll, porque la portada y la pared ya llevan la suya. Una invitación que se repite tres veces en una pantalla deja de leerse como invitación y pasa a leerse como fondo.' );

        vx_marca_declaracion( 'A lo ancho el motivo cambia de forma.' );
        vx_marca_p( 'El collage no escala, y conviene decir por qué antes de que alguien lo intente. Funciona porque cada recorte es chico respecto de una cara: así los rasgos calzan y el ojo arma un rostro. A 1600 píxeles de ancho cada recorte pasa a ser más grande que una cara entera, y entonces no se lee un rostro hecho de varios sino cuatro caras gigantes inconexas, con el recorte punteado pareciendo un error de carga. Se armó, se miró y se descartó. <strong>El collage vive en vertical.</strong>' );
        vx_marca_p( 'Para lo ancho el motivo cambia de forma pero no de reglas: en lugar de recortes que arman una cara, <strong>retratos enteros en una rejilla</strong>. Los rasgos siguen en las mismas coordenadas, la piel sigue saliendo de la paleta y sigue habiendo un solo lugar libre. Las dos piezas se dibujan con <code>npm run retratos</code>; el manual muestra el mismo archivo que publica el sitio, no una copia.' );
        vx_marca_foto( 'retratos-portada.svg', 'Diez retratos ilustrados en dos filas de cinco; el cuarto de la primera fila es un lugar libre punteado sobre blanco.', 'La portada: diez retratos a 1600 × 900. La casilla mide 0,71 de ancho por alto contra los 0,80 del retrato, así que el recorte es mínimo y las cabezas salen enteras.' );
        vx_marca_foto( 'retratos-mosaico.svg', 'Una pared de treinta retratos ilustrados en tres filas de diez; uno es un lugar libre punteado.', 'La pared del directorio, entre Cómo funciona y Para quién es: treinta retratos a 1600 × 674. Dice «acá hay gente» mejor que cualquier frase.' );
        vx_marca_foto( 'retratos-banner.svg', 'Ocho retratos ilustrados en dos filas de cuatro, todos distintos y todos presentes.', 'El banner del problema: ocho retratos a 1600 × 970. Es la única de las tres que no lleva lugar libre, y la razón está abajo.' );
        vx_marca_valor( 'Treinta retratos, treinta personas', 'Los seis rasgos del generador tienen periodos 5, 3, 2, 6, 3 y 6; su mínimo común múltiplo es 30, así que las primeras treinta combinaciones no se repiten. La primera versión del mosaico repetía las seis personas del ejemplo cuatro veces y se leía como seis tipos clonados: una pared de directorio con gente repetida dice lo contrario de lo que la pared quiere decir.' );
        vx_marca_valor( 'La casilla se acerca a la proporción del retrato', 'El retrato se dibuja en 200 × 250, o sea 0,80. Mientras la casilla se mantenga cerca de ese número, el recorte no muerde la cabeza ni los hombros. La portada va en 0,71 y el mosaico en 0,71; a 0,30 — seis retratos en una sola fila de 16 a 9 — la cara queda cortada a ambos lados y la pieza se vuelve un rayado de colores.' );
        vx_marca_valor( 'El lugar libre va sobre blanco', 'Siempre, aunque le tocara un pastel por turno. Sobre un color parece una ficha a la que le falta la foto; sobre blanco parece un lugar que está esperando a alguien, que es lo que es.' );
        vx_marca_valor( 'La pieza se dibuja en la proporción en la que se publica', 'El mosaico mide 1600 × 674, que es 2,375, exactamente la proporción a la que la franja se publica en escritorio: 1140 × 480. Así no se recorta ninguna cara. En teléfono sí se recorta, y a propósito: entera mediría 158 píxeles de alto y las caras 15, que ya no es gente sino textura.' );

        vx_marca_declaracion( 'La red de puntos baja a fondo.' );
        vx_marca_p( 'Los puntos que se unen con líneas siguen existiendo, animados en <code>assets/js/network.js</code>, pero dejan de ser el motivo principal. Van tenues y detrás, nunca encima del contenido ni como textura de tarjeta. Dibujan la red; el collage dibuja a la gente, y la gente manda.' );

        vx_marca_p( 'Lo que nunca se dibuja: corazones, porcentajes de parecido, barras de compatibilidad ni nada que se parezca a una aplicación de citas. Acá no hay afinidad que medir, hay trabajo que se cruza.' );
    } );

    vx_marca_bloque( 'identidad-direccion-fotografica', function () {
        vx_marca_declaracion( 'La marca ilustra, no fotografía.' );
        vx_marca_p( 'Es la decisión más grande de la identidad y conviene decirla sin rodeos: <strong>el perfil ilustrado reemplaza a la foto</strong>. No por presupuesto ni por falta de material, sino porque la foto de oficina promete algo que el producto no entrega. Un directorio de empresas verificadas no se parece a una reunión con buena luz.' );
        vx_marca_foto( 'retrato.svg', 'Un perfil ilustrado: rostro de frente sobre fondo hueso, con la piel en mantequilla y la ropa en salvia.', 'Un perfil suelto. Mismo encuadre siempre: de frente, hombros adentro, mirada al lente, fondo liso de un solo color.' );
        vx_marca_valor( 'Encuadre', 'De frente y a la altura de los ojos, con los hombros dentro del cuadro y el rostro centrado. Vertical, proporción 4 a 5. Sin perfiles de tres cuartos ni planos cortados: el recorte tiene que poder cruzarse con cualquier otro.' );
        vx_marca_valor( 'Fondo', 'Liso, de un solo color de la paleta. Sin degradados, sin textura y sin escenario. El fondo identifica el recorte dentro del collage; si tuviera contenido, competiría con la cara.' );
        vx_marca_valor( 'Expresión', 'Neutra o apenas amable. Nadie ríe a carcajadas ni posa de brazos cruzados. El perfil es una ficha, no una foto de perfil de red social.' );
        vx_marca_p( 'Para encargar o generar una: «Retrato ilustrado vectorial de una persona, de frente, hombros dentro del cuadro, sobre fondo liso de un solo color. Formas planas sin degradados ni sombras. La piel en un color pastel que no imita el tono real. Rasgos simples en trazo oscuro. Proporción vertical 4 a 5.»' );
        vx_marca_declaracion( 'Las cenas son la única excepción.' );
        vx_marca_p( 'Vitrinexo 4Dinner es un evento real con gente real sentada a una mesa real, y eso no se ilustra: <strong>la foto se reserva para lo presencial</strong>. La frontera es simple de aplicar porque es la misma que separa al producto de su experiencia. Si ocurre en la plataforma, se ilustra. Si ocurre en un restaurante, se fotografía.' );
        vx_marca_foto( '4dinner.jpg', 'Cuatro personas cenando en una terraza de noche, con velas sobre la mesa y luces de la ciudad al fondo.', 'La excepción, y su registro: de noche, cálida, con la luz que ya está en la escena.' );
        vx_marca_valor( 'Cuatro personas y una mesa redonda', 'El número es la promesa del formato, así que la foto lo cumple. Nunca cinco, nunca una mesa larga, nunca una sala con varias mesas si la pieza habla de una cena.' );
        vx_marca_valor( 'La luz está en la escena', 'Velas sobre la mesa, guirnalda arriba, ciudad desenfocada atrás. Nada de flash ni de luz montada: si hiciera falta iluminar, la escena no era la correcta.' );
        vx_marca_valor( 'Conversación a media frase', 'Nadie mira a la cámara y nadie brinda para la foto. Se fotografía una conversación que ya estaba ocurriendo, que es exactamente lo que el formato promete.' );
        vx_marca_p( 'Para encargar o generar una: «Fotografía editorial horizontal de cuatro personas cenando en una terraza urbana de noche, mesa redonda, conversación a media frase. Luz cálida de velas sobre la mesa y guirnalda de bombillas arriba; ciudad desenfocada al fondo. Paleta ámbar. Nadie mira a cámara. Altura de los ojos.»' );
        vx_marca_p( 'Fuera de las cenas no hay foto. Ni retratos de miembros, ni oficinas, ni manos estrechándose: eso es el producto, y el producto se ilustra.' );
        vx_marca_p( 'Ya no queda ninguna foto de plataforma en el sitio: la portada, el banner del problema y la pared del directorio son las tres rejillas de retratos. Las dos fotos de lo presencial se quedan, que es de lo que trata esta regla.' );
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
        vx_marca_p( '<strong>Español, y se trata de tú.</strong> Nunca voseo: «tienes» y «puedes», no <span data-cita>«tenés» ni «podés»</span>.' );
        vx_marca_p( 'El lanzamiento y la comunidad inicial son de Hispanoamérica, así que el español es el de la región y no el peninsular. La visión incluye Brasil, Estados Unidos y Europa, y eso obliga a una regla al escribir: <strong>no se dice que Vitrinexo sea una <span data-cita>plataforma latinoamericana</span></strong>, aunque hoy sus miembros lo sean. Lo que se dice es dónde hay comunidad hoy.' );
        vx_marca_p( 'Las dos reglas se miden sobre el texto que la persona ve, en el sitio público y dentro del área de miembros: el voseo no se cuela en una portada que alguien revisó diez veces, se cuela en un saludo o en un aviso.' );

        vx_marca_declaracion( 'Portugués e inglés todavía no existen, y ya tienen regla.' );
        vx_marca_p( 'Escribirla antes vale la pena porque la decisión se toma mal con prisa, el día que haga falta el idioma. <strong>No se traduce: se vuelve a escribir.</strong> Un texto traducido conserva el ritmo del español y suena a manual de instrucciones; lo que viaja es el argumento, no la frase.' );
        vx_marca_valor( 'Lo que no cambia', 'El nombre, <span data-cita>Vitrinexo</span>, y <strong>nexo</strong>, que es la palabra que la marca inventó y no tiene equivalente. En portugués y en inglés se usan tal cual, porque traducirlas sería renunciar a lo único que no se puede copiar.' );
        vx_marca_valor( 'Lo que se vuelve a escribir', '<strong>Vitrinear</strong> no se traduce ni se translitera: es un verbo que solo funciona en español. En los otros idiomas la idea se dice con otra construcción, y esa construcción hay que encontrarla, no calcarla.' );
        vx_marca_valor( 'Lo que se decide una vez', '<strong>Miembro Pionero</strong> necesita su nombre propio en cada idioma, y ese nombre es una decisión de marca, no de traducción: una vez elegido, no se vuelve a discutir por pieza.' );
        vx_marca_p( 'Y la regla de la que se habló arriba vale igual en los tres idiomas: se dice dónde hay comunidad hoy, nunca de qué región es la marca.' );
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
        vx_marca_p( 'El nombre de la marca se escribe siempre <strong>Vitrinexo</strong>, con una sola mayúscula. Nunca <span data-cita>«VitriNexo» ni «VITRINEXO»</span>, tampoco en logotipos de terceros ni en firmas de correo.' );
        vx_marca_p( 'De ahí sale una regla que no es obvia hasta que choca: <strong>el nombre nunca va dentro de un sobretítulo</strong>. El sobretítulo es caja alta por definición, así que meter la marca ahí la grita aunque en el código esté bien escrita. Pasó en las páginas legales, donde el sobretítulo decía la marca y se leía <span data-cita>VITRINEXO</span>.' );
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
        vx_marca_p( 'Hay además siete palabras que no se dicen de ninguna forma: <span data-cita><strong>ecosistema</strong>, <strong>sinergia</strong>, <strong>solución</strong>, <strong>partner</strong>, <strong>match</strong>, <strong>early adopter</strong> y <strong>VITRINEXO</strong></span> en mayúsculas. Las cuatro primeras no significan nada y se nota. Las tres siguientes significan otra cosa: <span data-cita><em>match</em></span> es de las aplicaciones de citas y acá nadie busca pareja, <span data-cita><em>early adopter</em></span> ya tiene nombre en español y es Miembro Pionero, y el nombre en caja alta grita.' );
        vx_marca_p( 'Y una regla de puntuación: <strong>no se usan guiones largos</strong> en ningún texto de la marca. Donde aparezca uno va una coma, un punto o dos puntos.' );
        vx_marca_p( 'El verbo propio, <strong>vitrinear</strong>, se conjuga normal y no se pone entre comillas. Está en el diccionario de la marca, no es una gracia.' );
        vx_marca_p( 'El producto contradecía la prohibición de <span data-cita>«match»</span> en tres direcciones vivas. Ya no: la sección se llama <strong>Nexos</strong> y sus dos caras son <strong>lo que busco</strong> y <strong>lo que ofrezco</strong>, que es como la portada lo dice desde siempre. Las direcciones viejas redirigen permanente, porque son enlaces que los miembros pueden tener guardados y a los que apuntan correos ya enviados.' );
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
        vx_marca_p( 'El sitio anunciaba las cenas como <span data-cita>«miércoles 8pm»</span> mientras el código guardaba 20:00. Ya no: manda el formato de 24 horas en los dos lados, que es el que no se lee mal en ningún país donde la marca quiere estar.' );
        vx_marca_p( 'Las seis reglas se miden sobre el texto que la persona ve, en todas las páginas y en las cuatro anchuras. No sobre el código: ahí <code>strtotime</code> necesita <span data-cita>«8:00 AM»</span> en inglés para entender la frase, y eso no es copy.' );
        vx_marca_p( 'El día que se escribió esa comprobación había <strong>tres violaciones</strong>, y una era la que este mismo párrafo daba por corregida: la ficha del evento seguía anunciando <span data-cita>«8:00 pm»</span>. Las otras dos, un correo que decía <span data-cita>«9 AM»</span> y las instrucciones del repositorio, que seguían describiendo las cenas como <span data-cita>«8pm»</span> y por tanto se lo enseñaban a quien las leyera.' );
        vx_marca_p( 'Lo que entre Estados Unidos sigue abierto, y queda anotado para entonces y no antes: la fecha corta d/m/Y se va a leer al revés, y cambiarla hoy rompería la lectura de todos los miembros de ahora.' );
    } );

    vx_marca_bloque( 'voz-emojis', function () {
        vx_marca_declaracion( 'Ninguno, en ninguna parte.' );
        vx_marca_p( 'Ni en la interfaz, ni en los correos, ni en las notificaciones, ni en el panel de administración. No hay excepción por tono ni por canal.' );
        vx_marca_p( 'La regla cubre también los pictogramas que no son emojis de manual y hacen el mismo trabajo: el visto, el aspa, la flecha escrita dentro de un rótulo. Se ven distinto en cada sistema operativo, no toman la tinta que los rodea y no se pueden alinear. <strong>Donde hacía falta un símbolo va un ícono de Tabler</strong>, que sí hace las tres cosas.' );
        vx_marca_p( 'En un correo, donde el ícono tipográfico no se puede cargar, el símbolo se reemplaza por la palabra: «Fecha:» y «Lugar:» en vez de un calendario y un pin.' );
    } );

}
