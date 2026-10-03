FROM php:8.3-fpm-alpine

RUN apk add --no-cache nginx supervisor sqlite-dev nodejs npm \
    && docker-php-ext-install -j$(nproc) bcmath opcache pdo_sqlite

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN npm install --ignore-scripts && npm run build

RUN mkdir -p public/share-images \
    && chown -R www-data:www-data storage bootstrap/cache public/share-images

COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/php.ini $PHP_INI_DIR/conf.d/99-app.ini

COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 8080

ENTRYPOINT ["/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
