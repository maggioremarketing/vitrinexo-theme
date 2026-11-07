# El banco local

Un WordPress de verdad en `127.0.0.1`, con SQLite en vez de MySQL, el tema y el
plugin enlazados desde sus repositorios, y siete miembros inventados. Sirve para
una sola cosa que no se podía hacer de ninguna otra forma: **medir el área de
miembros**, que solo existe detrás de una sesión iniciada.

Hasta que existió, catorce pantallas del producto no las había medido nunca
nadie. No porque fueran difíciles, sino porque hacía falta estar dentro, y las
comprobaciones siempre miraron desde fuera.

## Por qué no se usa la web publicada

Porque para entrar al área de miembros de `vitrinexo.com` haría falta una
cuenta real en producción. Acá los usuarios, la clave y los siete miembros son
inventados, viven en un archivo SQLite de esta máquina y no salen de ella.

## Levantarlo

    npm run banco           descarga WordPress, lo instala y crea los miembros
    npm run banco:servir    lo deja escuchando en 127.0.0.1:8910
    npm run banco:sesion    inicia las dos sesiones y guarda sus estados

`banco:servir` levanta PHP con **opcache apagado**, y eso no es un detalle de
rendimiento. La regla del proyecto dice que una comprobación no vale hasta que
se verifica rompiendo el código a propósito, y con opcache encendido el banco
sirve la versión anterior del archivo durante un par de segundos: el fallo
sembrado pasa en verde y la vuelta siguiente se pone roja con el código ya
arreglado. O sea, exactamente al revés. Se perdió un rato largo en eso.

La sesión también caduca. Si las rutas de miembro devuelven páginas de unos
pocos cientos de caracteres, no es que estén vacías: es que `sesion.json` ya no
sirve y se está midiendo el pie público. Se vuelve a hacer con
`npm run banco:sesion`.

Y después, las comprobaciones de pantalla sobre el área de miembros:

    VX_SESION=banco/wp/sesion.json VX_SESION_NUEVA=banco/wp/sesion-onboarding.json \
      VX_SESION_ADMIN=banco/wp/sesion-admin.json \
      npm run accesibilidad -- http://127.0.0.1:8910/marca

Sin `VX_SESION` las rutas de miembro no se piden, porque en producción
devuelven el login y medirlo no dice nada.

Son dos sesiones porque hay una pantalla que la primera no puede abrir.
`VX_SESION` es la de ana, que ya terminó su onboarding, y por eso el plugin la
manda al panel cuando pide `/onboarding/`. `VX_SESION_NUEVA` es la de gabriela,
que está a mitad del suyo, y es la única que ve esa pantalla. Importa porque su
paso 5 es donde se eligen las tres comunidades, o sea donde más veces se
nombran las piezas de la línea. Sin esa segunda sesión la comprobación de
vocabulario pasaba en verde sin haberla abierto nunca.

Y hay una tercera, `VX_SESION_ADMIN`, que es la del administrador del banco y
abre siete pantallas del escritorio de WordPress: las cinco del plugin, el
listado de usuarios y el de preguntas frecuentes. Ahí la copia también es
nuestra —los nombres de las columnas, las cabeceras del CSV, los avisos, las
etiquetas del menú— y antes se revisaba a ojo. Solo las mide la comprobación de
vocabulario: pedirle al escritorio la paleta, la tipografía o los 44 píxeles de
la casa sería medir a WordPress y llamarlo un fallo de Vitrinexo.

## Lo que no es

No es un entorno de desarrollo del producto ni una copia de la base de datos.
Los siete miembros son de mentira y los datos no se parecen a los reales más que
en la forma. Para ver un error que solo ocurre con datos de verdad, esto no
sirve.

`banco/wp/` no se versiona: son 40 MB de WordPress y un SQLite. Se reconstruye
con `npm run banco`.

## Lo que encontró el primer día

Treinta y cuatro fallos de contraste y un desborde, en pantallas que nadie
había medido nunca:

- Veinte reglas escribían texto en el paso `-presion` de un pastel, que no es
  una tinta. «Ofrece» daba 2,14:1 y «Busca» 1,50:1 en cada ficha del
  directorio. De ahí salieron los peldaños `-tinta` de cada familia.
- Los contadores del panel iban en lavanda y rosa pastel: 1,34:1.
- «Zona de peligro», en Configuración, iba en rosa pastel: 1,34:1. Es justo el
  enlace que no puede costar leer.
- Conexiones medía 635 px de ancho en un teléfono de 390: sus pestañas ni
  envolvían ni se deslizaban, así que empujaban la página entera de lado.

Y en la segunda vuelta, después de arreglar lo de arriba:

- Los enlaces del pie llegaban a 44 de alto pero no de ancho. «Blog» mide 28 y
  «Ayuda» 40, y son justo los cortos los que se fallan.
- `editar-perfil` traía un `<img src="">`. Sin src no pasa nada, pero con src
  vacío el navegador vuelve a pedir la página entera.
- `/mis-publicaciones/` daba 404 en el banco: esa página no la crea el plugin
  al activarse. La crea `banco/paginas.php`.

## En el despliegue

`banco/ci.sh` lo levanta y lo mide, y lo llaman los dos despliegues **antes**
del rsync. Eso lo hace un portón de verdad y no un aviso: las comprobaciones
de producción miden lo publicado y solo pueden avisar, el banco existe antes.

En el plugin corre siempre, porque casi todo lo que el plugin renderiza es el
área de miembros. En el tema, solo cuando el cambio toca estilos, plantillas,
el PHP o las propias comprobaciones: un cambio en el manual no levanta
WordPress.

## Lo que el banco no puede ver

Las preguntas frecuentes son un CPT: en producción las escribe alguien desde el
escritorio y viven en la base, no en el repo. `banco/preguntas.php` siembra diez
inventadas para que la página tenga qué mostrar, pero no son las de producción y
nunca lo van a ser. Lo mismo pasa con el título de cada página, que es un campo
editable: el plugin pone uno al crearla y después nadie lo vuelve a tocar desde
el código.

Por eso la compuerta puede estar verde acá y roja después del deploy, cuando la
misma comprobación corre contra vitrinexo.com. No es un falso positivo: es la
única mirada que alcanza esa copia. Lo que se arregla ahí se arregla en
wp-admin, no en el repo.

## Por qué el servidor va con ocho procesos

`banco:servir` lleva `PHP_CLI_SERVER_WORKERS=8`. El servidor de PHP por
defecto atiende una petición a la vez, y Playwright abre la página y sus
recursos a la vez y espera a `networkidle`. Con un solo proceso eso se
atasca: la carga no termina, la prueba cuenta la ruta como que no respondió y
—antes de que eso fuera una falla— la comprobación salía en verde sin haber
abierto la pantalla. Pasó: una corrida dio verde en las catorce pantallas sin
medir ni una sola ruta con sesión.
