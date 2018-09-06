# Build backend source
FROM composer as backend
WORKDIR /app

COPY composer.json composer.lock /app/
RUN composer install  \
    --ignore-platform-reqs \
    --no-autoloader \
    --no-dev \
    --ansi \
    --no-interaction \
    --no-scripts

COPY . /app/
RUN composer dump-autoload --optimize --classmap-authoritative

# Build frontend assets
# FROM node as frontend
# WORKDIR /app
#
# COPY package.json package-lock.json webpack.mix.js /app/
# RUN npm install
#
# COPY resources/assets /app/resources/assets
# RUN npm run production

# Build app image
FROM php:7.2 as app

RUN apt-get update && \
    apt-get install -y --no-install-recommends \
        libcurl4-gnutls-dev  zlib1g-dev \
    && rm -r /var/lib/apt/lists/*

#Install php modules
RUN docker-php-ext-install mbstring curl zip

RUN pecl install -o -f swoole \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable swoole

COPY --from=backend /app /var/www
# COPY --from=frontend /app/public /app/public

WORKDIR /var/www

RUN chgrp -R www-data /var/www/storage /var/www/bootstrap/cache && chmod -R ug+rwx /var/www/storage /var/www/bootstrap/cache

CMD php ./artisan swoole:http start

EXPOSE 1215
