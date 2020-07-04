# Build backend source
FROM composer as backend

#Install prestissimo for parallel composer installs
#https://github.com/hirak/prestissimo/issues/148
RUN set -xeu \
    && composer global require hirak/prestissimo \
    && composer clear-cache

WORKDIR /app

COPY composer.json composer.lock /app/

RUN composer install  \
    --ignore-platform-reqs \
    --no-ansi \
    --no-autoloader \
    --no-dev \
    --no-interaction \
    --no-scripts

COPY . /app/
RUN composer dump-autoload --optimize --classmap-authoritative

# Build frontend assets
FROM node:12 as frontend
WORKDIR /app

COPY package.json yarn.lock /app/
RUN yarn --pure-lockfile

COPY webpack.mix.js tsconfig.json tailwind.config.js /app/
COPY resources /app/resources
RUN yarn run production

# Build app image
FROM php:7.4-apache as app

# Modify UID of www-data user to match default user of host
RUN usermod --non-unique --uid 1000 www-data

# RUN apt-get update && \
#     apt-get install -y --no-install-recommends \
#         libcurl4-gnutls-dev  zlib1g-dev \
#     && rm -r /var/lib/apt/lists/*

# # #Install php modules
# RUN docker-php-ext-install mbstring curl zip

RUN pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis

RUN a2enmod rewrite

COPY build/apache.conf /etc/apache2/sites-available/000-default.conf
COPY build/php.ini ${PHP_INI_DIR}/conf.d/99-overrides.ini

WORKDIR /app

COPY --from=backend /app /app
COPY --from=frontend /app/public /app/public

RUN chgrp -R www-data /app/storage /app/bootstrap/cache && chmod -R ug+rwx /app/storage /app/bootstrap/cache
