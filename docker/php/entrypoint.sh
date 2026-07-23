#!/bin/sh
set -e

if [ ! -f vendor/autoload.php ]; then
    composer install --no-interaction --prefer-dist
fi

if [ ! -f .env ]; then
    cp .env.example .env
fi

php artisan key:generate --ansi --force

until php artisan db:show > /dev/null 2>&1; do
    echo "Aguardando o banco de dados..."
    sleep 2
done

php artisan migrate --force

chown -R www-data:www-data storage bootstrap/cache

exec "$@"
