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

# El banco se apaga siempre, pase lo que pase.
#
# No lo hacía, y cuesta caro: php -S con PHP_CLI_SERVER_WORKERS deja ocho hijos
# que no cuelgan del npm, así que matar el npm no libera el puerto. El servidor
# viejo sobrevive, el nuevo no puede escuchar, y la suite mide contra el que
# quedó: dos servidores peleándose el 8910 y una tanda entera de rutas mudas
# que parecían un fallo del código. Se buscan por el puerto, que es lo único
# que los junta a todos.
puerto_libre() {
  if command -v lsof > /dev/null 2>&1; then
    pids="$( lsof -ti tcp:8910 2>/dev/null || true )"
    if [ -n "$pids" ]; then
      # shellcheck disable=SC2086
      kill -9 $pids 2>/dev/null || true
      sleep 1
    fi
  fi
  return 0
}
trap puerto_libre EXIT

echo "── levantándolo"
puerto_libre
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

echo "── midiendo el área de miembros"
npm run miembros
