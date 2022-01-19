FROM php:8-alpine

RUN apk update; apk --no-cache add \
 php7-pcntl \
 php7-pdo_sqlite \
 postgresql-libs \
 postgresql-dev \
 supervisor \
 && docker-php-ext-install pdo_pgsql \
 && apk del postgresql-dev \
 && mkdir -p /etc/supervisor/conf.d \
 && mkdir -p /var/log/supervisor \
 && rm -rf /var/cache/apk/*

COPY supervisord.conf /etc/supervisor
COPY supervisord.conf.d/* /etc/supervisor/conf.d

ENTRYPOINT ["supervisord", "--nodaemon", "--configuration", "/etc/supervisord.conf"]
