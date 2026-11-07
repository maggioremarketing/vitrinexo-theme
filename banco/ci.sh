#!/usr/bin/env bash
# Levanta el banco y mide el área de miembros. Lo llaman los dos despliegues.
#
# Vive acá y no en un `uses:` de GitHub porque una acción local no puede estar
# fuera del espacio de trabajo, y el plugin necesita llamar a una que vive en
# el tema.
set -euo pipefail

TEMA="$( cd "$( dirname "${BASH_SOURCE[0]}" )/.." && pwd )"
cd "$TEMA"

echo "── PHP"
php -v | head -1
php -r 'exit(in_array("sqlite", PDO::getAvailableDrivers()) ? 0 : 1);' \
  || { echo "::error::este PHP no trae pdo_sqlite y el banco lo necesita"; exit 1; }

echo "── montando el banco"
npm run banco

echo "── levantándolo"
npm run banco:servir > /tmp/banco.log 2>&1 &
# Se espera a que responda de verdad, no a que el proceso exista.
for i in $(seq 1 25); do
  if curl -sf -o /dev/null http://127.0.0.1:8910/wp-login.php; then
    echo "   responde tras ${i}s"; break
  fi
  [ "$i" = 25 ] && { echo "::error::el banco no respondió"; tail -20 /tmp/banco.log; exit 1; }
  sleep 1
done

echo "── sesión"
npm run banco:sesion

echo "── midiendo las catorce pantallas"
npm run miembros
