#!/bin/sh
set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
  set -- php-fpm "$@"
fi

if [ "$1" = 'php-fpm' ] || [ "$1" = 'php' ] || [ "$1" = 'bin/console' ]; then
  if [ "$APP_ENV" != 'prod' ]; then
    echo "Setting up development environment..."
    ln -sf "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

    echo "Running composer install..."
    composer install --prefer-dist --no-progress -o --no-interaction

    echo "Setting var directory permissions..."
    chmod -R 777 var
  fi
fi

exec docker-php-entrypoint "$@"
