# El banco local

Un WordPress de verdad en `127.0.0.1`, con SQLite en vez de MySQL, el tema y el
plugin enlazados desde sus repositorios, y seis miembros inventados. Sirve para
una sola cosa que no se podía hacer de ninguna otra forma: **medir el área de
miembros**, que solo existe detrás de una sesión iniciada.

Hasta que existió, catorce pantallas del producto no las había medido nunca
nadie. No porque fueran difíciles, sino porque hacía falta estar dentro, y las
comprobaciones siempre miraron desde fuera.

## Por qué no se usa la web publicada

Porque para entrar al área de miembros de `vitrinexo.com` haría falta una
cuenta real en producción. Acá el usuario, la clave y los seis miembros son
inventados, viven en un archivo SQLite de esta máquina y no salen de ella.

## Levantarlo

    npm run banco           descarga WordPress, lo instala y crea los miembros
    npm run banco:servir    lo deja escuchando en 127.0.0.1:8910
    npm run banco:sesion    inicia sesión y guarda el estado en sesion.json

Y después, las comprobaciones de pantalla sobre el área de miembros:

    VX_SESION=banco/wp/sesion.json npm run accesibilidad -- http://127.0.0.1:8910/marca

Sin `VX_SESION` las rutas de miembro no se piden, porque en producción
devuelven el login y medirlo no dice nada.

## Lo que no es

No es un entorno de desarrollo del producto ni una copia de la base de datos.
Los seis miembros son de mentira y los datos no se parecen a los reales más que
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
