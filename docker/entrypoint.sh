#!/bin/sh
set -e

touch database/database.sqlite
mkdir -p public/share-images storage/framework/views storage/framework/cache/data

php artisan config:cache
php artisan route:cache
php artisan view:cache

php artisan migrate --force

chown -R www-data:www-data database storage bootstrap/cache

exec "$@"
