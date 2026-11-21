FROM dunglas/frankenphp:1-php8.4-bookworm AS base

# Upload foto maks 4 MB (batas body Vercel Function ~4,5 MB); default php.ini hanya 2 MB.
RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && install-php-extensions pdo_mysql gd zip opcache \
    && printf 'upload_max_filesize=4M\npost_max_size=5M\n' > "$PHP_INI_DIR/conf.d/uploads.ini"

WORKDIR /app

FROM base AS dependencies

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --no-progress --no-scripts --optimize-autoloader --prefer-dist

# ponytail: tanpa tahap Node; @vite hanya dipakai welcome.blade.php yang tidak dirender. Tambah npm build bila view memakai @vite.
COPY . .
RUN mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && composer dump-autoload --no-dev --classmap-authoritative

FROM base AS runtime

COPY --from=dependencies --chown=www-data:www-data /app /app
COPY --chown=www-data:www-data Caddyfile /etc/frankenphp/Caddyfile

RUN setcap CAP_NET_BIND_SERVICE=+eip /usr/local/bin/frankenphp \
    && chown -R www-data:www-data /config/caddy /data/caddy

# Default non-rahasia; nilai di Vercel Dashboard menimpa ini. Rahasia (APP_KEY, DB_*, BLOB_*) hanya di Dashboard.
ENV PORT=80 \
    APP_ENV=production \
    APP_DEBUG=false \
    LOG_CHANNEL=stderr \
    CACHE_STORE=array \
    QUEUE_CONNECTION=sync \
    DB_CONNECTION=mysql \
    SESSION_DRIVER=database \
    MYSQL_ATTR_SSL_CA=/etc/ssl/certs/ca-certificates.crt

USER www-data

CMD ["frankenphp", "run", "--config", "/etc/frankenphp/Caddyfile"]
