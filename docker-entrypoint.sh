#!/usr/bin/env bash
set -e

if [ -f composer.json ] && [ ! -d vendor ]; then
  echo "Installing composer dependencies..."
  composer install --no-interaction --prefer-dist
fi

mkdir -p public/uploads
chown -R www-data:www-data public/uploads || true
chmod -R 755 public/uploads || true
exec "$@"
