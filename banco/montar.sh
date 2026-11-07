#!/usr/bin/env bash
# Monta el WordPress local del banco. Idempotente: si ya está, no lo rehace.
set -euo pipefail
cd "$( dirname "${BASH_SOURCE[0]}" )"
TEMA="$( cd .. && pwd )"
PLUGIN="$TEMA/../vitrinexo-core"
WP="wp"

[ -d "$PLUGIN" ] || { echo "falta $PLUGIN al lado del tema"; exit 1; }

if [ ! -f "$WP/sitio/wp-settings.php" ]; then
  mkdir -p "$WP" && cd "$WP"
  echo "· bajando WordPress"
  curl -sL -o wp.tar.gz https://es.wordpress.org/latest-es_ES.tar.gz
  tar xzf wp.tar.gz && mv wordpress sitio && rm wp.tar.gz
  echo "· bajando el integrador de SQLite (wordpress.org)"
  curl -sL -o sqlite.zip https://downloads.wordpress.org/plugin/sqlite-database-integration.zip
  unzip -q sqlite.zip -d sitio/wp-content/plugins/ && rm sqlite.zip
  cd ..
fi

cd "$WP"
[ -f clave.txt ] || { php -r 'echo bin2hex(random_bytes(18));' > clave.txt; chmod 600 clave.txt; }

# El drop-in de SQLite y la configuracion.
if [ ! -f sitio/wp-content/db.php ]; then
  cp sitio/wp-content/plugins/sqlite-database-integration/db.copy sitio/wp-content/db.php
  php -r '
    $w = realpath("sitio");
    $p = "sitio/wp-content/db.php"; $s = file_get_contents($p);
    $s = str_replace("{SQLITE_IMPLEMENTATION_FOLDER_PATH}", $w."/wp-content/plugins/sqlite-database-integration", $s);
    $s = str_replace("{SQLITE_PLUGIN}", "sqlite-database-integration/load.php", $s);
    file_put_contents($p, $s);'
fi

php -r '
  if (file_exists("sitio/wp-config.php")) exit;
  $claves = "";
  foreach (["AUTH_KEY","SECURE_AUTH_KEY","LOGGED_IN_KEY","NONCE_KEY","AUTH_SALT","SECURE_AUTH_SALT","LOGGED_IN_SALT","NONCE_SALT"] as $k)
    $claves .= "define( \x27$k\x27, \x27" . bin2hex(random_bytes(32)) . "\x27 );\n";
  file_put_contents("sitio/wp-config.php", "<?php\n"
    . "/* Banco local. SQLite, sin MySQL. Todo vive en 127.0.0.1. */\n"
    . "define( \x27DB_NAME\x27, \x27vitrinexo_local\x27 );\n"
    . "define( \x27DB_USER\x27, \x27\x27 ); define( \x27DB_PASSWORD\x27, \x27\x27 ); define( \x27DB_HOST\x27, \x27\x27 );\n"
    . "define( \x27DB_CHARSET\x27, \x27utf8\x27 ); define( \x27DB_COLLATE\x27, \x27\x27 );\n"
    . "define( \x27DB_ENGINE\x27, \x27sqlite\x27 );\n" . $claves
    . "\$table_prefix = \x27wp_\x27;\n"
    . "define( \x27WP_DEBUG\x27, true ); define( \x27WP_DEBUG_DISPLAY\x27, true );\n"
    . "define( \x27WP_HOME\x27, \x27http://127.0.0.1:8910\x27 );\n"
    . "define( \x27WP_SITEURL\x27, \x27http://127.0.0.1:8910\x27 );\n"
    . "define( \x27DISABLE_WP_CRON\x27, true );\n"
    . "if ( ! defined( \x27ABSPATH\x27 ) ) define( \x27ABSPATH\x27, __DIR__ . \x27/\x27 );\n"
    . "require_once ABSPATH . \x27wp-settings.php\x27;\n");'

ln -sfn "$TEMA"   sitio/wp-content/themes/vitrinexo-theme
ln -sfn "$PLUGIN" sitio/wp-content/plugins/vitrinexo-core

php ../instalar.php
cd ..
php paginas.php
php miembros.php
cd "$WP"
echo "· listo. 'npm run banco:servir' lo levanta en 127.0.0.1:8910"
