FROM php:8-fpm-alpine

RUN apk update; apk --no-cache add \
 php8-pcntl \
 php8-pdo_sqlite \
 postgresql-libs \
 postgresql-dev \
 $PHPIZE_DEPS && \
 docker-php-ext-install pdo_pgsql

ARG INSTALL_XDEBUG=false

RUN if [ "${INSTALL_XDEBUG}" = "true" ]; then \
 pecl install -f xdebug && \
 docker-php-ext-enable xdebug; \
 fi

ENV PHP_IDE_CONFIG=serverName=mydocker
COPY xdebug.ini /usr/local/etc/php/conf.d/

RUN apk del --purge $PHPIZE_DEPS postgresql-dev
